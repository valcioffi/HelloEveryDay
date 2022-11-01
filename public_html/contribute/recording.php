<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'].'/templates/base.php';

?>
<html>
    <head>
        <?php baseHead() ?>
    </head>
    <body>
        <?php baseHeader() ?>
        <section id="content">
            <h1>Record</h1>
            <h1>Upload</h1>
        </section>
        <?php baseFooter() ?>
    </body>
</html>
