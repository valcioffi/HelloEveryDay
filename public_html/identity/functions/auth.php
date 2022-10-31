<?php
use Kreait\Firebase\Factory;

function getCurrentUser(){     
  $factory = (new Factory)->withServiceAccount($_SERVER['DOCUMENT_ROOT'].'/.config/auth/helloeveryday-valentinocioffi-firebase-adminsdk-j729l-5426aaa7d6.json');
  $auth = $factory->createAuth();
  $database = $factory->createDatabase();
  $GLOBALS["auth"]=$auth;
  
  if(isset($_COOKIE["session"])){
    try {
    $verifiedSessionCookie = $GLOBALS["auth"]->verifySessionCookie($_COOKIE["session"]);
      
      $uid = $verifiedSessionCookie->claims()->get('sub');
      $user = $GLOBALS["auth"]->getUser($uid);
      return $user;
    } catch (FailedToVerifySessionCookie | Kreait\Firebase\Exception\Auth\UserNotFound $e) { return null; }
  }else{
    return null;
  }
}

function login($email, $password, $external=false){
    $factory = (new Factory)->withServiceAccount($_SERVER['DOCUMENT_ROOT'].'/.config/auth/helloeveryday-valentinocioffi-firebase-adminsdk-j729l-5426aaa7d6.json');
    
    $auth = $factory->createAuth();
            
    try{
      if(!isset($auth->getUserByEmail($email)->customClaims["provider"]) || $external){
        $signInResult = $auth->signInWithEmailAndPassword($email, $password);
        $idToken=$signInResult->idToken();
        try {
              $oneWeek = new \DateInterval('P7D');
              $sessionCookieString = $auth->createSessionCookie($idToken, $oneWeek);
            $GLOBALS["session"]=$sessionCookieString;
            setcookie("session", $sessionCookieString, time() + (86400 * 30)*14, "/");
            header('Location: /');
    
          } catch (FailedToCreateSessionCookie $e) {
              echo $e->getMessage();
          }
      } else {
        echo "Please, log in trought ".$auth->getUserByEmail($email)->customClaims["provider"]." to continue.";
      }
      
    }catch (Kreait\Firebase\Exception\Auth\UserNotFound $e){
      echo "No user registered with the selected email. <a href='signup.php'>Sign Up</a>!";
    }
}

function signup($name, $email, $password, $custom=NULL, $external=false){
  $factory = (new Factory)->withServiceAccount($_SERVER['DOCUMENT_ROOT'].'/.config/auth/helloeveryday-valentinocioffi-firebase-adminsdk-j729l-5426aaa7d6.json');
    
  $auth = $factory->createAuth();

  $userProperties = [
  'email' => $email,
  'emailVerified' => true,
  'password' => $password,
  'displayName' => $name,
  'disabled' => false
  ];

  $createdUser = $auth->createUser($userProperties);

  if($custom!=NULL)
    $auth->setCustomUserClaims($createdUser->uid , $custom);
    
  login($email, $password, $external);
}

function logout(){
  setcookie("session", '', time() - 1, "/");
  header('Location: /');
}