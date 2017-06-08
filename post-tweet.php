<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require 'twitter/autoload.php';
include 'restRequest.php';

require_once __DIR__ . '/lib/google/vendor/autoload.php';
//Sheet 
define('SPREAD_SHEET_ID','1oNqG6PFCj7K7H1wZJiqmyz7qzTZx1SsHF2VLAVMFHiQ');
define('OAUTH2_CLIENT_ID', '1089990018340-frdjldsicdgrbn7r637b63brstqj0fie.apps.googleusercontent.com');
define('OAUTH2_CLIENT_SECRET', 'QY1xdsM49JzQ-AmujkyzSl6b');

//twitter auth
define('CONSUMER_KEY', 'sQuMMP3NNOlt1hX2qBnvjx7z7'); // add your app consumer key between single quotes
define('CONSUMER_SECRET', 'V5UKrlvOPWJvsGLRD5nDSICitMXX6kZ3SyIWcqy6mhsY7FEHYu'); // add your app consumer secret key between single quotes
define('OAUTH_CALLBACK', 'http://localhost/loqtaSheet/twitter/callback.php'); // your app callback URL
$access_token = '529196638-bHn1yqxMG3rV0gjpOYLqeot6RyfYPqnVX2MCdWAX';
$outh_token = 'PBMQFfX9viWxa4XaJm9Bfj8mltEOBPqG5LdkvJBMyg1bh';
use Abraham\TwitterOAuth\TwitterOAuth;


////////////////////////////////////////////////////////////
// Client init for read and write on Excel
$key = file_get_contents('token.txt');
$client = new Google_Client();
$client->setClientId(OAUTH2_CLIENT_ID);
$client->setAccessType('offline');
$client->setApprovalPrompt('force');
$client->setAccessToken($key);
$client->setClientSecret(OAUTH2_CLIENT_SECRET);
/**
     * Check to see if our access token has expired. If so, get a new one and save it to file for future use.
     */
 if($client->isAccessTokenExpired()) {
        $newToken = json_encode($client->getAccessToken());
        $client->refreshToken($newToken->refresh_token);
       file_put_contents('token.txt', json_encode($client->getAccessToken()));
 }
$tokenSessionKey = 'token-' . $client->prepareScopes();
if (isset($_SESSION[$tokenSessionKey])) {
  $client->setAccessToken($_SESSION[$tokenSessionKey]);
  echo $client->getAccessToken();
}  

if ($client->getAccessToken()) {

    $service = new Google_Service_Sheets($client);
$range = 'Sheet1!A2:D';
$response = $service->spreadsheets_values->get(SPREAD_SHEET_ID, $range);
$values = $response->getValues();
rsort($values);
if (count($values) == 0) {
} else {
  foreach ($values as $row) {
    // Print columns A and E, which correspond to indices 0 and 4.
   $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token, $outh_token);
   $post = $connection->post('statuses/update', array('status' => $row[3],'in_reply_to_status_id'=>'872334943048912897'));
  }
}
    
	
	//$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token, $outh_token);
	
     //  $post = $connection->post('statuses/update', array('status' => '@HussamKurd'));
                    

}