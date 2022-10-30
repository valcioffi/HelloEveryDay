<?php
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'].'/templates/base.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/identity/functions/auth.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)->withServiceAccount($_SERVER['DOCUMENT_ROOT'].'/.config/auth/helloeveryday-valentinocioffi-firebase-adminsdk-j729l-5426aaa7d6.json');

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

if(isset($_GET["langindex"]))
  $GLOBALS["lang_index"]=$_GET["langindex"];

$GLOBALS["lang_data"]=$GLOBALS["lang_data"][$GLOBALS["lang_index"]];

$url = 'https://en.wiktionary.org/w/api.php';
$data = [
	"action" => "parse",
	"format" => "json",
	"page" => $GLOBALS["lang_data"]["hello"],
	"prop" => "",
	"formatversion" => "2"
];

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$pagename=json_decode($result)->parse->title;
if ($pagename){
      $GLOBALS["wiktionary"]="https://en.wiktionary.org/wiki/".$pagename."#".$GLOBALS["lang_data"]["language"];
}

  
?>
<html>
    <head>
        <?php meta(['title' => $GLOBALS["lang_data"]["hello"]]) ?>
        <?php if(isset($GLOBALS["lang_data"]["font"])) echo "<link href='https://fonts.googleapis.com/css?family=".$GLOBALS["lang_data"]["font"]."' rel='stylesheet'>"?>
    </head>
    <body>
        <?php heading() ?>
        <br/>
        <section id="content">
            <div id="helloContainer">
                <span id="lan" class="txG"><?php echo $GLOBALS["lang_data"]["language"];?></span>
                <h1 id="hello" class="txB" style="<?php if(isset($GLOBALS["lang_data"]["font"])) echo "font-family:'".$GLOBALS["lang_data"]["font"]."';"?>"><?php echo ucfirst($GLOBALS["lang_data"]["hello"]);?></h1>
                <?php  if(isset($GLOBALS["lang_data"]["translitteration"])) {echo "<h2 id='translitteration' class='txB'>".$GLOBALS["lang_data"]["translitteration"]."</h2>";}
                ?>
                <hr width="70%"/>
                
                <script>
                  var el=document.createElement("AUDIO");
                  el.onended=function(){
                    document.getElementById("listen").classList.remove("playing")
                  }
                  <?php 
                    if(isset($GLOBALS["lang_data"]["audio"]))
                      echo "el.src='".$GLOBALS["lang_data"]["audio"]."';";
                  ?>
                  function listen(){
                    el.play();          document.getElementById("listen").classList.add("playing")
                  }
                </script>
                <button id="listen" onclick="listen()" <?php if(!isset($GLOBALS["lang_data"]["audio"])) echo "title='Audio not aviable, sorry' disabled" ?>><span>Listen</span></button>
                <button onclick="window.location.href='<?php
      if(isset($GLOBALS["wiktionary"])) echo $GLOBALS["wiktionary"]?>'" <?php if(!isset($GLOBALS["wiktionary"])) echo "title='Dictionary not aviable, sorry' disabled" ?>>Dictionary</button>
                <br/>
                <span class="txG"><?php echo ($GLOBALS["lang_index"]+1)."/".$GLOBALS["lang_count"]; ?></span>
                
            </div>
            <br/><br/>
      <?php
    if(!isset($GLOBALS["lang_data"]["audio"])){
      if(isset($GLOBALS["lang_data"]["video"]))
        $hope=" You will still be able to listen the word by watching <a href='".$GLOBALS["lang_data"]["video"]."'>this video</a>.";
      echo "<span id='noaudio'>Sorry, no audio aviable for this language.".$hope." <a href='contribute.php'>Contribute</a></span><br/>";
    }
  
      if(isset($GLOBALS["lang_data"]["video"]))
        echo "<br/><br/><iframe width='420' height='315' src='".str_replace("youtube.com/watch?v=","youtube.com/embed/",$GLOBALS["lang_data"]["video"])."'>
  </iframe><br/><br/>
  ";
            
      ?>
      <a href="identity/signup.php">Customize your experience by creating an account</a>
      <a href="identity/login.php">Log In</a>
        </section>
        <section id="about">
          <h2>HelloEveryDay</h2>
          <h3>Hello</h3>
          <span>Each day you will learn how to greet someone in a new language.</span><br/><br/>
          <span>Please notice that the greeting won't always correspond to "Hello", but will be the most common ones, which could also translate with "How are you?" or "Peace upon you".<br/><br/>For more information on the meaning of the greeting, press the "Dictionary" button!</span>
          <br/><br/><h3>Contribute</h3>
          <blockquote><span id="quote_tx">“Language is a city to the building of which every human being brought a stone”</span><br/><span id="quote_au">– Ralph Waldo Emerson</span></blockquote>
          <br/><br/><button>Add a new greeting</button><button>Record a greeting</button>
        </section>
        <?php
        $text="";
        if(isset($GLOBALS["lang_data"]["source"]) or isset($GLOBALS["lang_data"]["audio"])){
          $text=$text."<a href='";
          if(isset($GLOBALS["lang_data"]["source"]))
            $text=$text. $GLOBALS["lang_data"]["source"];
          else
            $text=$text. preg_replace("/(?=https:\/\/upload\.wikimedia\.org\/wikipedia\/commons\/)(.*)(?<=\/)/", "https://commons.wikimedia.org/wiki/File:",$GLOBALS["lang_data"]["audio"]);
          $text=$text. "'>Audio source</a>";
          if(isset($GLOBALS["lang_data"]["source"]))
            $text=$text. "<h3>Legal disclaimer - Fair use</h3>Copyright Disclaimer under section 107 of the Copyright Act 1976, allowance is made for “fair use” for purposes such as criticism, comment, news reporting, teaching, scholarship, education and research.\n\nFair use is a use permitted by copyright statute that might otherwise be infringing.\n\nNon-profit, educational or personal use tips the balance in favor of fair use.";
        }

        footer($text) ?>
    </body>
</html>