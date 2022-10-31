<?php
require $_SERVER['DOCUMENT_ROOT'].'/templates/base.php';
autoload();
require_once $_SERVER['DOCUMENT_ROOT'].'/.config/auth/secure.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/identity/functions/auth.php';

use Kreait\Firebase\Factory;

function authIntegration($name, $email, $provider){
  $factory = (new Factory)->withServiceAccount($_SERVER['DOCUMENT_ROOT'].'/.config/auth/helloeveryday-valentinocioffi-firebase-adminsdk-j729l-5426aaa7d6.json');
  
  $auth = $factory->createAuth();
  try{
    if($auth->getUserByEmail($email)){
      if(isset($auth->getUserByEmail($email)->customClaims["provider"]))
        login($email, secureExternalAuth($name, $email), true);
      else
        echo "This account is already signed up locally. Can't Log In trought ".$provider;
    }
  }catch (Kreait\Firebase\Exception\Auth\UserNotFound $e){
    signup($name, $email, secureExternalAuth($name, $email), ['provider' => $provider], true);
  }
}