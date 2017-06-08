
<?php

// put your code here
session_start();
require 'autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'vZno8h0LY56t2hPSam5UEGi6j'); // add your app consumer key between single quotes
define('CONSUMER_SECRET', 'elScX9CvSJKuCRbwICyr8ac25jHKmeE3nTKnZdJ5rryxXwbfJQ'); // add your app consumer secret key between single quotes
define('OAUTH_CALLBACK', 'http://localhost/twitter/callback.php'); // your app callback URL
//$tweet = '@george check out http://www.google.co.uk #google';
$data = json_decode(file_get_contents('php://input'), true);
$_SESSION['data'] = $data;
$result = postTweet($data);
print_r(json_decode($result));
die();

function postTweet($tweet) {
    if (!isset($_SESSION['access_token'])) {
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        $callbackUrl = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        header('Location: ' . $callbackUrl);
        exit;
    } else {

        $access_token = $_SESSION['access_token'];
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
        return json_encode($response);
    }
}
?>

