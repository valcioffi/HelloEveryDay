<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/templates/auth.php';
require $_SERVER['DOCUMENT_ROOT'].'/templates/base.php';

use Kreait\Firebase\Factory;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input field
  if (empty($_POST['email']) or empty($_POST['password'])) {
    echo "Error. Invalid POST data";
  } else {
    signup($_POST['name'], $_POST['email'], $_POST['password']);
  }
}
?>
<html>
    <head>
        <?php meta(); authStyles() ?>
        
    </head>
    <body>
        <?php heading() ?>
        <section id="content" >
            <table width="100%" id="main">
              <tr>
                <th><div id="helloContainer" style="width:100% !important; margin:0 !important; padding:1em;">
              <h1>Sign Up</h1>
              <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <table style="margin-left: auto; margin-right: auto;">
                  <tr>
                    <th>Username</th>
                    <th></th>
                    <th><input type="text" name="name"></th>
                  </tr>
                  <tr>
                    <th>EMail</th>
                    <th></th>
                    <th><input type="text" name="email"></th>
                  </tr>
                  <tr>
                    <th>Pasword</th>
                    <th></th>
                    <th><input type="password" name="password"></th>
                  </tr>
                  
                </table>

                <input type="submit" class="button">
              </form>
            </div></th>
                <th><div id="helloContainer" style="width:100% !important; margin:0 !important; padding:1em;">
              <h1>Already signed up? <a href="login.php">Log In</a></h1>
              <br/><br/><?php externalAuths() ?>
            </div></th>
              </tr>
            </table>
            
            
        </section>
        <?php footer() ?>
  
</body>
</html>
