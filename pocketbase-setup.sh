#!/bin/bash

# Function to print error and exit
function error_exit {
    echo "Error: $1" >&2
    exit 1
}

# Check if exactly three arguments are provided
if [ "$#" -ne 3 ]; then
    error_exit "Usage: $0 <domain> <subdomain> <port>"
fi

# Assign arguments to variables
domain=$1
subdomain=$2
port=$3

# Sanitize inputs using regex
echo "$domain" | grep -E -q '^[a-zA-Z0-9.-]+$' || error_exit "Invalid domain: $domain"
echo "$subdomain" | grep -E -q '^[a-zA-Z0-9.-]+$' || error_exit "Invalid subdomain: $subdomain"
echo "$port" | grep -E -q '^[0-9]+$' || error_exit "Invalid port: $port"

# Get the current user and group
user="${SUDO_USER:-$(whoami)}"
group=$(id -gn "$user")

# Define the service file path
service_file="/etc/systemd/system/pocketbase-$subdomain.service"

# Create the service file
sudo bash -c "cat > $service_file" <<EOL
[Unit]
Description=Pocketbase for $subdomain.$domain
After=network.target

[Service]
ExecStart=/home/$user/$subdomain/pocketbase serve --http 127.0.0.1:$port
WorkingDirectory=/home/$user/$subdomain
Restart=always
User=$user
Group=$group
RestartSec=10
Environment=PATH=/usr/bin:/usr/local/bin

[Install]
WantedBy=multi-user.target
EOL

# Set correct permissions for the service file
sudo chmod 644 $service_file

# Add the service
sudo systemctl daemon-reload
sudo systemctl enable pocketbase-$subdomain.service
sudo systemctl start pocketbase-$subdomain.service

# Create Apache configuration files
apache_conf_dir="/etc/apache2/conf.d/userdata"

# SSL Configuration file
ssl_conf_file="$apache_conf_dir/ssl/2_4/$user/$subdomain.$domain/$subdomain.conf"
# Standard Configuration file
std_conf_file="$apache_conf_dir/std/2_4/$user/$subdomain.$domain/$subdomain.conf"

# Ensure the directories exist
sudo mkdir -p "$(dirname "$ssl_conf_file")"
sudo mkdir -p "$(dirname "$std_conf_file")"

# Create the content for the Apache config files
conf_content="<Location \"/\">
    ProxyPass http://127.0.0.1:$port/
    ProxyPassReverse http://127.0.0.1:$port/
    ProxyPassReverse /
    RewriteEngine on
    RewriteCond %{HTTP:Upgrade} websocket [NC]
    RewriteCond %{HTTP:Connection} upgrade [NC]
    RewriteRule ^/?(.*) \"ws://127.0.0.1:$port/\" [P,L]
</Location>"

# Create the SSL configuration file
sudo bash -c "echo \"$conf_content\" > $ssl_conf_file"

# Create the Standard configuration file
sudo bash -c "echo \"$conf_content\" > $std_conf_file"

# Set the correct permissions for the Apache config files
sudo chmod 644 "$ssl_conf_file"
sudo chmod 644 "$std_conf_file"

/scripts/ensure_vhost_includes --user=$user
sudo systemctl restart httpd
