<?php
include("/usr/local/cpanel/php/cpanel.php");  // Instantiate the CPANEL object.
$cpanel = new CPANEL();
print $cpanel->header( "Pocketbase installer" );

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"./pocketbase.css\" />";
echo "<div class=\"pocketbase-container\">";

try {
  $domain_info = $cpanel->uapi( 
    'DomainInfo', 'domains_data',
    array(
        'format'    => 'hash',
    )
  );
  $domain_info = $domain_info['cpanelresult'] ?? null;
  
  if (!$domain_info) {
    throw new Exception("Unable to get account details.");
  }
    
  $main_domain_details = $domain_info['result']['data']['main_domain'] ?? null;
  $main_domain = $main_domain_details['domain'] ?? null;
  $home_dir = $main_domain_details['homedir'] ?? null;
  if (!$main_domain || !$home_dir) {
    throw new Exception("Unable to get domain details.");
  }

  $subdomains = [];
  $main_domain_length = strlen($main_domain);
  $end_offset = 0 - ($main_domain_length + 1);
  foreach ($domain_info['result']['data']['sub_domains'] as $subdomain_details) {
    $subdomains[] = substr($subdomain_details['domain'], 0, $end_offset);
  }
    
  define("PB_DOMAIN_NAME", $main_domain);
  define("PB_URL", "https://" . PB_DOMAIN_NAME);
  define("PB_SUBDOMAINS", $subdomains);
  define("PB_HOME_DIR", $home_dir);
    
} catch (Exception $e) {
  echo $e->getMessage();
}

?>