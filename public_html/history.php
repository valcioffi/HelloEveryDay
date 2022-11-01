<?php
require $_SERVER['DOCUMENT_ROOT'].'/templates/base.php';
require $_SERVER['DOCUMENT_ROOT'].'/hello.php';
$GLOBALS["lang_data"]=getHelloData();
$GLOBALS["lang_data"]=array_splice($GLOBALS["lang_data"], 0, getHelloIndex()+1);
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
            <ol>
            <?php 
                foreach($GLOBALS["lang_data"] as $data){
                    $audio=""; $video=""; $wiktionary="";
                    if(isset($data["audio"]))
                        $audio=" &#183; <button onclick=\"".listenHello($data)."\">Listen</button>";

                    if(isset($data["video"]))
                        $video=" &#183; <button onclick='window.location.href=\"".$data["video"]."\"'>Video</button>";

                    if(getHelloWiktionary($data["hello"])!=false)
                        $wiktionary=" &#183; <button onclick='window.location.href=\"".getHelloWiktionary($data["hello"])."\"'>Dictionary</button>";


                    echo "<li>".ucfirst($data["hello"]).$audio.$video.$wiktionary;
                }
            ?>
            </ol>
        </section>

        <?php baseFooter($text) ?>
    </body>
</html>