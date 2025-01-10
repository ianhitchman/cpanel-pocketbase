<?php

function check_domain_format($domain) {
  // can have a-z, 0-9, and hyphens, must not start or end with a hyphen
  return preg_match('/^[a-z0-9][a-z0-9\-\.]*[a-z0-9]$/i', $domain);
}

function check_subdomain($subdomain) { 
  if (empty($subdomain)) { 
    throw new Exception("Please enter a subdomain.");
  }
  if (!check_domain_format($subdomain)) {
    throw new Exception("Please enter a valid subdomain.");
  }
  if (in_array($subdomain, PB_SUBDOMAINS)) {
    throw new Exception("This subdomain is already in use.");
  }
}

function create_subdomain($subdomain) {
  $create = CPANEL->uapi('SubDomain','addsubdomain',
    array (
        'domain' => $subdomain,
        'rootdomain' => PB_DOMAIN_NAME,
        'dir' => PB_HOME_DIR . '/' . $subdomain
    )    
  );
  $errors = $create['cpanelresult']['result']['errors'] ?? null;
  if (is_array($errors)) {
    $errors_str = "<p>" . implode('</p><p>', $errors) . "</p>";
  }
  if (isset($errors_str)) {
    throw new Exception($errors_str);
  }
}

function check_pocketbase($subdomain) {
  $home_dir = PB_HOME_DIR . '/' . $subdomain;
  if (!file_exists($home_dir)) {
    throw new Exception("Home folder '$home_dir' doesn't exist. Possibly an error while creating subdomain.");
  }
  if (file_exists($home_dir . '/pocketbase')) {
    throw new Exception("Pocketbase is already installed on this subdomain.");
  }
}

function install_pocketbase($subdomain) {
  $home_dir = PB_HOME_DIR . '/' . $subdomain;
  check_pocketbase($subdomain);
  $versions = get_pocketbase_versions();
  $latest_version = $versions['latest_version'] ?? null;
  if (empty($latest_version)) {
    throw new Exception("Unable to get latest Pocketbase version.");
  }
  $latest_version = substr($latest_version, 1);

  $filename = "pocketbase_${latest_version}_linux_amd64.zip";
  $new_path = $home_dir . '/' . $filename;
  $url = "https://github.com/pocketbase/pocketbase/releases/download/v${latest_version}/${filename}";
  $file = file_get_contents($url);
  file_put_contents($new_path, $file);
  if (!file_exists($new_path)) {
    throw new Exception("Unable to download Pocketbase.");
  }

  // extract zip
  exec("unzip $new_path -d $home_dir && rm $new_path");

  if (!file_exists($home_dir . '/pocketbase')) {
    throw new Exception("Unable to extract Pocketbase.");
  }
}

function get_pocketbase_versions() {
  $url = "https://api.github.com/repos/pocketbase/pocketbase/releases";
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, 'PHP'); // GitHub API requires a User-Agent header
  $response = curl_exec($ch);
  curl_close($ch);
  $data = json_decode($response, true);

  // Extract tag names into an array
  $versions = [];
  foreach ($data as $release) {
      if (isset($release['tag_name'])) {
          $versions[] = $release['tag_name'];
      }
  }

  // Get the latest version
  $latest_version = $versions[0] ?? null;

  // Return the versions and latest version as an array
  return [
      'versions' => $versions,
      'latest_version' => $latest_version,
  ];
}

function find_available_port() {
  $start = 8090;
  $end = 65535;
  for ($i = $start; $i <= $end; $i++) {
    if (!is_port_in_use($i)) {
      return $i;
    }
  }
  return null;
}

function is_port_in_use($port) {
  $command = "netstat -tuln | grep :$port";  
  exec($command, $output, $status);
  return !empty($output);
}

function setup_daemon($subdomain, $port) {
  $home_dir = PB_HOME_DIR . '/' . $subdomain;
  $full_domain = $subdomain . '.' . PB_DOMAIN_NAME;
  $command = "pocketbase serve --host 0.0.0.0 --port $port";
  $script = "[Unit]
Description=API for $full_domain
After=network.target

[Service]
ExecStart=/home/w3vps1/api/w3vps1/pocketbase serve --http 127.0.0.1:8091
WorkingDirectory=/home/w3vps1/api/w3vps1/
Restart=always
User=w3vps1
Group=w3vps1
RestartSec=10
Environment=PATH=/usr/bin:/usr/local/bin

[Install]
WantedBy=multi-user.target";
}

function flush_output() {
  echo str_repeat(' ', 1024 * 100);
  ob_flush();
  flush();
}

?>