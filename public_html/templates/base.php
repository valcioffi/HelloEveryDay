<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/identity/functions/auth.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/templates/auth.php';

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
  <link href='/styles.css' rel='stylesheet'>
  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\">";
}

function heading(){
  if(getCurrentUser()!=null){
    $userOptions=getCurrentUser()->displayName." (<a href='/identity/logout'>logout</a>)";
    if(isset(getCurrentUser()->customClaims["provider"]))
      $userOptions=getProviderIcon(getCurrentUser()->customClaims["provider"], ["style"=> "margin-right: 0.25em; font-size: 80% !important;", "title"=>getCurrentUser()->customClaims["provider"]." account"]).$userOptions;
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