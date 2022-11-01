<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/identity/functions/auth.php';

function getHelloData(){
    return json_decode(file_get_contents("hello.json"), true); 
}

function getHelloIndex(){

    if(isset($_GET["langindex"]))
        return $_GET["langindex"];

    $lang_data=getHelloData();

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

    $lang_count=count($lang_data);

    return $difference-(floor($difference/$lang_count)*$lang_count);
}

function getHelloWiktionary($hello=null){
    if($hello==null)
        $hello=getHelloData()[getHelloIndex()]["hello"];

    $url = 'https://en.wiktionary.org/w/api.php';
    $data = [
        "action" => "parse",
        "format" => "json",
        "page" => $hello,
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
        return "https://en.wiktionary.org/wiki/".$pagename."#".$GLOBALS["lang_data"]["language"];
    } else return false;
}

function listenHello($index=null){
    if ($index==null)
        $index=getHelloIndex();

    if(!isset(getHelloData()[$index]["audio"]))
        return false;

    echo '
    function(){
        var el=document.createElement(\'AUDIO\');
        el.onended=function(){
          this.classList.remove(\'playing\');
        }
        el.src=\''.getHelloData()[$index]["audio"].'\';
        el.play();
        this.classList.add(\'playing\');
    }
    ';
}