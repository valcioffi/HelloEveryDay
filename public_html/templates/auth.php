<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/identity/external/mediawiki.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/identity/external/google.php';

function externalAuths(){
  echo "
  <button class='extauthbtn' onclick='window.location.href=\"".mediawiki()."\"'><i class=\"fa fa-wikipedia-w\" aria-hidden=\"true\"></i>Sign In with Wikimedia</button><br/><br/>
  <button class='extauthbtn' onclick='window.location.href=\"".google()."\"'><i class=\"fa fa-google\" aria-hidden=\"true\"></i>Sign In with Google</button>
  ";
}

function authStyles(){
  echo "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\"><style>
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