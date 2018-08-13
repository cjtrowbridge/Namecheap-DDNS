<?php

/*

  Namecheap DDNS

  CJ Trowbridge
  2018-08-12

  This is a free script which automatically updated CNAME records through the Namecheap API. 
  It is designed to work with the DDNS service inside DD-WRT, but it will probably work with most any DDNS service.

*/

error_reporting(E_ALL);
ini_set('display_errors', '1');

if(!(isset($_GET['username']))){die('Username required.');}
if(!(isset($_GET['password']))){die('Password required.');}
if(!(isset($_GET['hostname']))){die('Hostname required.');}
if(!(isset($_GET['ip']))){die('IP required.');}

$NamecheapDDNS = new NamecheapDDNS(
  $_GET['username'],
  $_GET['password']
);
$NamecheapDDNS->Update(
  $_GET['hostname'],
  $_GET['ip']
);

class NamecheapDDNS{
  private $NamecheapAPIUsername = '[ENTER YOUR NAMECHEAP API USERNAME]';
  private $NamecheapAPIKey      = '[ENTER YOUR NAMECHEAP API KEY]';
  private $NamecheapUsername    = '[ENTER YOUR NAMECHEAP USERNAME]';
  private $Verbose              = false;
  private $DryRun               = false;
  private $ExistingRecords      = false;
  
  function __construct($Username, $Password){
    if(isset($_REQUEST['verbose'])){
      $this->Verbose = true;
    }
    if(isset($_REQUEST['dryrun'])){
      $this->DryRun = true;
    }
  }
  
  public function Update($DDNSHostname, $DDNSClientIP){
    
    //Get a list of current records for the domain
    $this->getRecords($DDNSHostname);
    
    //Check if hostname is in list of hostnames fetched from API.
    
    
    //Create CNAME if no match, update CNAME if match. Otherwise error.
    
  }
  
  
  private function getRecords(){
    //Fetches a list of DNS records associated with the domain in the Namecheap API.
    
    $this->ExistingRecords = $this->APIRequest(array(
      'Command'     => 'namecheap.domains.dns.getHosts',
      'ClientIp'    => $ClientIP,
      'SLD'         => $SLD,
      'TLD'         => $TLD
    ));
    
  }
  
  private function updateCNAME($Hostname,$IP){
    //Updates the specified CNAME record to point to the specified IP
    
    $Domains = explode('.',$Hostname);
    
    $TLD = substr($Hostname,(0-strrpos($Hostname,'.')));
    $SLD = substr($Hostname,0,strrpos($Hostname,'.'));
    
    //Prepend a protocol to the IP so the CNAME alias will work.
    $IP = 'http://'.$IP;
    
    //Get the IP of the server for the requests since for some reason Namecheap is not able to do this themselves.
    $ClientIP = $_SERVER['SERVER_ADDR'];
    
    /*
      Namecheap's DNS API is really terrible. First we need to get a list of all current records, 
      then update it to reflect changes, then submit it to save changes. 
      Otherwise, only the new record we submit will be saved and all other records will be deleted.
    */
    
    
    
    //Parse existing records into some kind of normal format instead of XML.
    
    
    //Update existing records to reflect the change.
    
    
    //Submit new records to replace existing records
    $SaveChanges = $this->APIRequest(array(
      'Command'     => 'namecheap.domains.dns.setHosts',
      'ClientIp'    => $ClientIP,
      'SLD'         => $SLD,
      'TLD'         => $TLD,
      'HostName1'   => '@',
      'RecordType1' => 'CNAME',
      'Address1'    => $IP,
      'TTL1'        => '100'
    ));
    
  }
  
  private function APIRequest($PassedArguments){
    //Add each passed argument to the array.
    foreach($PassedArguments as $Key => $Value){
      $Arguments[$Key]=$Value;
    }
    
    //Add all requried global arguments to the array.
    $Arguments['ApiUser']  = $this->NamecheapAPIUsername;
    $Arguments['ApiKey']   = $this->NamecheapAPIKey;
    $Arguments['UserName'] = $this->NamecheapUsername;
    $Arguments['ClientIp'] = $_SERVER['SERVER_ADDR'];
    
    //Comment out whichever server you don't want to use. Start with sandbox and test your configuration before running in production mode.
    $APIServer = 'https://api.sandbox.namecheap.com/xml.response';
    //$APIServer = 'https://api.namecheap.com/xml.response';
    
    //Run request.
    $Data = $this->Request($APIServer,$Arguments);
    
    
    
    //Parse results.
    
    
  }
  
  private function Request($URL, $Arguments = false){
    //Add arguments onto the URL.
    $URL.='?'.http_build_query($Arguments);
    
    if($this->Verbose){
      echo '<fieldset><p>Running Request: '.$URL.'</p><hr>';
    }
    
    //Set up cURL  
    $cURL = curl_init();
    curl_setopt($cURL,CURLOPT_URL, $URL);

    //Run cURL and close it
    if($this->DryRun){
      $Data = '[DRY RUN: No Response.]';
    }else{
      $Data = curl_exec($cURL);
    }
    curl_close($cURL);
    
    if($this->Verbose){
      echo '<p>Response: '.$URL.'</p></fieldset>';
    }

    //Return data
    return $Data;
  }
  
  
}
