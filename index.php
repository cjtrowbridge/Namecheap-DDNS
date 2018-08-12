<?php

/*

  Namecheap DDNS

  CJ Trowbridge
  2018-08-12

  This is a free script which automatically updated CNAME records through the Namecheap API. 
  It is designed to work with the DDNS service inside DD-WRT, but it will probably work with most any DDNS service.

*/

$NamecheapDDNS = new NamecheapDDNS(
  $_SERVER['PHP_AUTH_USER'],
  $_SERVER['PHP_AUTH_PW'],
  $_GET['hostname'],
  $_GET['ip']
);
$NamecheapDDNS->Update($IP);

class NamecheapDDNS{
  private $DDNSClientUsername = false;
  private $DDNSClientPassword = false;
  private $NamecheapAPIKey = '';
  
  function __construct($Username, $Password){
    $this->DDNSClientUsername = $Username;
    $this->DDNSClientPassword = $Username;
    
    //Fetch list of cname records for the given domain.
    
    //If authentication fails, throw an error.
    
  }
  
  public function Update($DDNSClientIP){
    
    //Check if hostname is in list of hostnames fetched from API.
    
    //Create CNAME if no match, update CNAME if match. Otherwise error.
    
  }
  
}
