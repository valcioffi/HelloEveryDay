<?php
require $_SERVER['DOCUMENT_ROOT'].'/../vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'].'/templates/base.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/identity/functions/auth.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)->withServiceAccount($_SERVER['DOCUMENT_ROOT'].'/../.config/auth/helloeveryday-valentinocioffi-firebase-adminsdk-j729l-5426aaa7d6.json');

$auth = $factory->createAuth();
$database = $factory->createDatabase();
$GLOBALS["auth"]=$auth;
$GLOBALS["database"]=$database;

$now = time(); // or your date as well

if(getCurrentUser()!=null){
  $created_at=getCurrentUser()->metadata->createdAt;
  $start_date=new \DateTime();
  $start_date->setTimestamp($created_at->getTimestamp());
  $display=$start_date->format('Y-m-d');
  $start_date=strtotime($display);
} else {
  $start_date=strtotime("0-0-0");

}

$difference=floor(($now-$start_date) / (60 * 60 * 24));

$GLOBALS["lang_data"]=json_decode(file_get_contents("hello.json"), true); 
$GLOBALS["lang_count"]=count($GLOBALS["lang_data"]);
$GLOBALS["lang_index"]=$difference-(floor($difference/$GLOBALS["lang_count"])*$GLOBALS["lang_count"]);
  
?>
<html>
    <head>
        <?php baseHead(['title' => "History"]) ?>
        <?php foreach($GLOBALS["lang_data"] as $data)
            if(isset($data["font"])) echo "<link href='https://fonts.googleapis.com/css?family=".$data["font"]."' rel='stylesheet'>"?>
    </head>
    <body>
        <?php baseHeader() ?>
        <br/>
        <section id="content">
            <ul>
            <?php 
                foreach($GLOBALS["lang_data"] as $data){
                    echo "<li>".$data["hello"]."</li>";
                }
            ?>
            </ul>
        </section>

        <?php baseFooter($text) ?>
    </body>
</html>