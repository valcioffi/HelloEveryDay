<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'].'/templates/base.php';

OOUI\Theme::setSingleton( new WikimediaUITheme() );
OOUI\Element::setDefaultDir( 'ltr' );


?>
<html>
    <head>
        <?php baseHead() ?>
        <!-- Use 'wikimediaui' or 'apex' -->
        <link rel="stylesheet" href="/dist/oojs-ui-core-wikimediaui.css">
        <link rel="stylesheet" href="/dist/oojs-ui-images-wikimediaui.css">
    </head>
    <body>
        <?php baseHeader() ?>
        <section id="content">
            <?php
            $selectfile= new OOUI\SelectFileWidget( [
                    'accept'=> [
                        'audio/mpeg',
                        'audio/ogg',
                        'audio/wav'
                    ],
                    "showDropTarget"=> true
                ] );        

            echo $selectfile;
            ?>
        </section>
        <?php baseFooter() ?>
    </body>
</html>
