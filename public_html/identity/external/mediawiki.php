<?php

// Require the library and set up the classes we're going to use in this first part.
require $_SERVER['DOCUMENT_ROOT'].'/templates/base.php';
require_once include_autoload();
require_once $_SERVER['DOCUMENT_ROOT']."/identity/external/integration.php";

use MediaWiki\OAuthClient\Client;
use MediaWiki\OAuthClient\ClientConfig;
use MediaWiki\OAuthClient\Consumer;
use MediaWiki\OAuthClient\Token;
session_start();

if ( isset( $_GET['oauth_verifier'] ) ) {
  require_once $_SERVER['DOCUMENT_ROOT'].'/.config/auth/mediawikiconfig.php';
  // Configure the OAuth client with the URL and consumer details.
  $conf = new ClientConfig( $oauthUrl );
  $conf->setConsumer( new Consumer( $consumerKey, $consumerSecret ) );
  $conf->setUserAgent( 'HelloEveryDay/1.0' );
  $client = new Client( $conf );

  // Get the Request Token's details from the session and create a new Token object.
  session_start();
  $requestToken = new Token( $_SESSION['request_key'], $_SESSION['request_secret'] );

  // Send an HTTP request to the wiki to retrieve an Access Token.
  $accessToken = $client->complete( $requestToken,  $_GET['oauth_verifier'] );

  // At this point, the user is authenticated, and the access token can be used to make authenticated
  // API requests to the wiki. You can store the Access Token in the session or other secure
  // user-specific storage and re-use it for future requests.
  $_SESSION['access_key'] = $accessToken->key;
  $_SESSION['access_secret'] = $accessToken->secret;

  // You also no longer need the Request Token.
  unset( $_SESSION['request_key'], $_SESSION['request_secret'] );

  // Make the api.php URL from the OAuth URL.
  $apiUrl = preg_replace( '/index\.php.*/', 'api.php', $oauthUrl );

  // Configure the OAuth client with the URL and consumer details.
  $conf = new ClientConfig( $oauthUrl );
  $conf->setConsumer( new Consumer( $consumerKey, $consumerSecret ) );
  $conf->setUserAgent( 'DemoApp MediaWikiOAuthClient/1.0' );
  $client = new Client( $conf );

  // Load the Access Token from the session.
  session_start();
  $accessToken = new Token( $_SESSION['access_key'], $_SESSION['access_secret'] );

  // Example 1: get the authenticated user's identity.
  $ident = $client->identify( $accessToken );
  authIntegration($ident->username, $ident->username.".wikimedia@helloeveryday.toolforge.org", "Wikimedia");
}

function mediawiki(){
  // Make sure the config file exists. This is just to make sure the demo makes sense if someone loads
  // it in the browser without reading the documentation.
  $configFile = $_SERVER['DOCUMENT_ROOT'].'/.config/auth/mediawikiconfig.php';
  if ( !file_exists( $configFile ) ) {
  	echo "Configuration could not be read. Please create $configFile by copying config.dist.php";
  	exit( 1 );
  }
  
  // Get the wiki URL and OAuth consumer details from the config file.
  require_once $configFile;
  
  // Configure the OAuth client with the URL and consumer details.
  $conf = new ClientConfig( $oauthUrl );
  $conf->setConsumer( new Consumer( $consumerKey, $consumerSecret ) );
  $conf->setUserAgent( 'HelloEveryDay/1.0' );
    
  $client = new Client( $conf );
  $client->setCallback('https://helloeveryday.toolforge.org/identity/login.php');
  // Send an HTTP request to the wiki to get the authorization URL and a Request Token.
  // These are returned together as two elements in an array (with keys 0 and 1).
  list( $authUrl, $token ) = $client->initiate();
  
  // Store the Request Token in the session. We will retrieve it from there when the user is sent back
  // from the wiki (see demo/callback.php).
  $_SESSION['request_key'] = $token->key;
  $_SESSION['request_secret'] = $token->secret;
  
  // Redirect the user to the authorization URL. This is usually done with an HTTP redirect, but we're
  // making it a manual link here so you can see everything in action.
  return $authUrl;
  
}