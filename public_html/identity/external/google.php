<?php
/*
 * Copyright 2013 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT']."/identity/external/integration.php";
use Kreait\Firebase\Factory;

$factory = (new Factory)->withServiceAccount($_SERVER['DOCUMENT_ROOT'].'/.config/auth/helloeveryday-valentinocioffi-firebase-adminsdk-j729l-5426aaa7d6.json');

$auth = $factory->createAuth();

$oauth_credentials=$_SERVER['DOCUMENT_ROOT'].'/.config/auth/client_secret_752334791449-9cd0c4jp1m9kf5mutnvjerpdo0ikg3qr.apps.googleusercontent.com.json';


// add "?logout" to the URL to remove a token from the session
if (isset($_REQUEST['logout'])) {
    unset($_SESSION['google_token']);
}

/************************************************
 * If we have a code back from the OAuth 2.0 flow,
 * we need to exchange that with the
 * Google\Client::fetchAccessTokenWithAuthCode()
 * function. We store the resultant access token
 * bundle in the session, and redirect to ourself.
 ************************************************/
if (isset($_GET['code'])) {
    $client = new Google\Client;
    $client->setApplicationName("HelloEveryDay");
    $client->setAuthConfig($oauth_credentials);
    $client->setRedirectUri('https://helloeveryday.toolforge.org/identity/login.php');
    $client->addScope(["email", "profile"]);
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code'])["access_token"];
      // store in the session also
    $_SESSION['google_token'] = $token;

    $client = new Google\Client;
    $client->setAccessToken($_SESSION['google_token']);
      $oauth2 = new Google_Service_Oauth2($client);
    if ( !$oauth2 ) {
          return new WP_Error('invalid_access_token', 'The access_token was invalid.');   
      }
  
      // Contains basic user info
      $google_user = $oauth2->userinfo->get();
      $name=$google_user["givenName"];
      if(isset($google_user["familyName"]))
        $name=$name." ".$google_user["familyName"];
      authIntegration($name, $google_user["email"], "Google");

}

function google(){
  $oauth_credentials=$_SERVER['DOCUMENT_ROOT'].'/.config/auth/client_secret_752334791449-9cd0c4jp1m9kf5mutnvjerpdo0ikg3qr.apps.googleusercontent.com.json';

  $client = new Google\Client;
  $client->setApplicationName("HelloEveryDay");
  $client->setAuthConfig($oauth_credentials);
  $client->setRedirectUri('https://helloeveryday.toolforge.org/identity/login.php');
  $client->addScope(["email", "profile"]);

  $authUrl = $client->createAuthUrl();
  return $authUrl;
}