<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
require 'autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'vZno8h0LY56t2hPSam5UEGi6j'); // add your app consumer key between single quotes
define('CONSUMER_SECRET', 'elScX9CvSJKuCRbwICyr8ac25jHKmeE3nTKnZdJ5rryxXwbfJQ'); // add your app consumer secret key between single quotes
define('OAUTH_CALLBACK', 'http://localhost/twitter/callback.php'); // your app callback URL

if (isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) && $_REQUEST['oauth_token'] == $_SESSION['oauth_token']) {
    $request_token = [];
    $request_token['oauth_token'] = $_SESSION['oauth_token'];
    $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
    $access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
    $_SESSION['access_token'] = $access_token;
    // redirect user back to index page
    //header('Location: ./');
    $tweet = $_SESSION['data'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $connection->setTimeouts(10, 30);
    $result = $connection->post('statuses/update', $tweet);
    if ($connection->getLastHttpCode() == 200) {
        // Tweet posted succesfully
        $response = ['status' => 1];
    } else {
        // Handle error case
        $response = ['status' => 0];
    }

    $json = json_encode($response);
}
