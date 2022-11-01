<?php
require $_SERVER['DOCUMENT_ROOT'].'/../vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'].'/templates/base.php';
require $_SERVER['DOCUMENT_ROOT'].'/hello.php';

$GLOBALS["lang_data"]=getHelloData()[getHelloIndex()];
?>
<html>
    <head>
        <?php baseHead(['title' => $GLOBALS["lang_data"]["hello"]]) ?>
        <?php if(isset($GLOBALS["lang_data"]["font"])) echo "<link href='https://fonts.googleapis.com/css?family=".$GLOBALS["lang_data"]["font"]."' rel='stylesheet'>"?>
    </head>
    <body>
        <?php baseHeader() ?>
        <br/>
        <section id="content">
            <div id="helloContainer">
                <span id="lan" class="txG"><?php echo $GLOBALS["lang_data"]["language"];?></span>
                <h1 id="hello" class="txB" style="<?php if(isset($GLOBALS["lang_data"]["font"])) echo "font-family:'".$GLOBALS["lang_data"]["font"]."';"?>"><?php echo ucfirst($GLOBALS["lang_data"]["hello"]);?></h1>
                <?php  if(isset($GLOBALS["lang_data"]["translitteration"])) {echo "<h2 id='translitteration' class='txB'>".$GLOBALS["lang_data"]["translitteration"]."</h2>";}
                ?>
                <hr width="70%"/>
                <button id="listen" onclick="<?php echo listenHello(); ?>" <?php if(!isset($GLOBALS["lang_data"]["audio"])):?> title='Audio not aviable, sorry' disabled<?php endif; ?>><span>Listen</span></button>
                <button onclick="window.location.href='<?php if(getHelloWiktionary()) echo getHelloWiktionary(); ?>'" <?php if(getHelloWiktionary()==false): ?> title='Dictionary not aviable, sorry' disabled <?php endif; ?>>Dictionary</button>
                <br/>
                <span class="txG"><?php echo (getHelloIndex()+1)."/".count(getHelloData()); ?></span>
                
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
      <?php if(getCurrentUser()==null): ?>
        <p>Have access to extra functionalities by <a href="identity/signup.php">Signing Up</a> or <a href="identity/login.php">Logging In</a>!</p>
      <?php endif; ?>
      <button onclick="window.location.href='/history'" <?php if(getCurrentUser()==null): ?>title="You need do be Logged In" disabled<?php endif; ?>>History</button>
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

        baseFooter($text) ?>
    </body>
</html>