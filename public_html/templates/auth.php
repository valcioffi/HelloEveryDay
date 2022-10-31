<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/identity/external/mediawiki.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/identity/external/google.php';

function getProviderIcon($provider, $conf=[]){
  $title="";
  $style="";
  if(isset($conf["style"])){
    $style="style='".$conf["style"]."' ";
  }
  if(isset($conf["title"])){
    $title="title='".$conf["title"]."'";
  }
  switch(strtolower($provider)){
    case 'google';
      return "<i class=\"fa fa-google\" aria-hidden=\"true\" ".$style.$title."></i>";
      break;

    case 'wikimedia';
      return "<i class=\"fa fa-wikipedia-w\" aria-hidden=\"true\" ".$style.$title."></i>";
      break;
  }
}

function externalAuths(){
  echo "
  <button class='extauthbtn' onclick='window.location.href=\"".mediawiki()."\"'>".getProviderIcon("wikimedia")."Sign In with Wikimedia</button><br/><br/>
  <button class='extauthbtn' onclick='window.location.href=\"".google()."\"'>".getProviderIcon("google")."Sign In with Google</button>
  ";
}

function authStyles(){
  echo "<style>
          body *:not(.base){
            font-size:100% !important;
          }
          table:not(#main):not(.base) *:not(.base){
            font-weight: normal;
            text-align:left;
          }
          .extauthbtn{
            width:60%;
          }
          .extauthbtn i{
            float:left;
          }
        </style>";
}