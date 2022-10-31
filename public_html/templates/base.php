<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/identity/functions/auth.php';

use Kreait\Firebase\Factory;

function meta($conf=[]){
  $title="HelloEveryDay";
  if(isset($conf["title"]))
    echo "<title>".$conf["title"]." - ".$title."</title>";
  else
    echo "<title>".$title."</title>";
    
  echo"
  <meta name='viewport' content='initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
  <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
  <link href='/styles.css' rel='stylesheet'>";
}

function heading(){
  if(getCurrentUser()!=null){
    $userOptions=getCurrentUser()->displayName." (<a href='/identity/logout'>logout</a>)";
    if(isset(getCurrentUser()->customClaims["provider"]))
      $userOptions=getProviderIcon(getCurrentUser()->customClaims["provider"]).$userOptions;
  }else{
    $userOptions="<a href='/identity/signup'>Sign Up</a> &#183;
     <a>Log In</a>";
  }
  echo '<header id="header" class="base">
          <span id="left" class="base">
            <span class="txB base">HelloEveryDay</span>
          </span>
          <span id="right" class="base">
        '.$userOptions.'
          </span>
        </header>';
}

function footer($extra=''){
      echo '<hr width="90%" class="base">
        <footer id="footer" class="txG base">
        
        <p class="base">A project by Valentino Cioffi</p>
        <br/>
        '.$extra.'
      </footer>';
}