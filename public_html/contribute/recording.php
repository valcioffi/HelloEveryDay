<?php

require $_SERVER['DOCUMENT_ROOT'].'/templates/base.php';
require_once include_autoload();

OOUI\Theme::setSingleton( new WikimediaUITheme() );
OOUI\Element::setDefaultDir( 'ltr' );


?>
<html>
    <head>
        <?php meta() ?>
        <!-- Use 'wikimediaui' or 'apex' -->
        <link rel="stylesheet" href="/dist/oojs-ui-core-wikimediaui.css">
        <link rel="stylesheet" href="/dist/oojs-ui-images-wikimediaui.css">
    </head>
    <body>
        <?php heading() ?>
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
        <?php footer() ?>
    </body>
</html>
