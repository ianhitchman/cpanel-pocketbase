<?php

function check_domain_format($domain) {
  // can have a-z, 0-9, and hyphens, must not start or end with a hyphen
  return preg_match('/^[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])?$/i', $domain);
}

function check_subdomain($subdomain) { 
  $error = null;
  if (empty($subdomain)) { 
    $error = "Please enter a subdomain.";
  }
  if (!check_domain_format($subdomain)) {
    $error = "Please enter a valid subdomain.";
  }
  if (in_array($subdomain, PB_SUBDOMAINS)) {
    $error = "This subdomain is already in use.";
  }
  return array('error' => $error);  
}

function create_subdomain($subdomain) {
  $create = CPANEL->uapi('SubDomain','addsubdomain',
    array (
        'domain' => 'subdomain',
        'rootdomain' => PB_DOMAIN_NAME,
        'dir' => PB_HOME_DIR . '/' . $subdomain
    )    
  );
  $errors = $create['cpanelresult']['result']['errors'] ?? null;
  if (is_array($errors)) {
    $errors_str = "<p>" . implode('</p><p>', $errors) . "</p>";
  }
  if (isset($errors_str)) {
    return array('error' => $errors_str);
  }
}

function flush_output() {
  echo str_repeat(' ', 1024 * 100);
  ob_flush();
  flush();
}

?>