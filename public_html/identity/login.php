<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'].'/templates/base.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/templates/auth.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/identity/functions/auth.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)->withServiceAccount($_SERVER['DOCUMENT_ROOT'].'/.config/auth/helloeveryday-valentinocioffi-firebase-adminsdk-j729l-5426aaa7d6.json');

$auth = $factory->createAuth();
$GLOBALS["auth"]=$auth;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input field
  if (empty($_POST['email']) or empty($_POST['password'])) {
    echo "Error. Invalid POST data";
  } else {
    login($_POST['email'], $_POST['password']);
  }
}else{
  if(isset($_GET["logout"])){
    if(isset($_COOKIE["session"])){
      try {
      $verifiedSessionCookie = $GLOBALS["auth"]->verifySessionCookie($_COOKIE["session"]);
        
        $uid = $verifiedSessionCookie->claims()->get('sub');
        $user = $GLOBALS["auth"]->getUser($uid);
        $GLOBALS["user"]=$user;

        $GLOBALS["auth"]->revokeRefreshTokens($uid);
        unset($_COOKIE['session']); 
        setcookie('session', null, -1, '/'); 
        $GLOBALS["logout"]="Logged out successfully!";
      } catch (FailedToVerifySessionCookie $e) {
        $GLOBALS["logout"]="ERROR: you are not logged in!";
      }
    }else{
      $GLOBALS["logout"]="ERROR: you are not logged in!";
    }
  }
}
?>
<html>
    <head>
        <?php meta(); authStyles(); ?>
    </head>
    <body>
        <?php heading() ?>
        <section id="content" >
            <?php
              if(isset($GLOBALS["logout"]))
                echo "<div class='alert'>".$GLOBALS["logout"]."<a href='/'>Homepage</a></div>";
            ?>
            <table width="100%" id="main">
              <tr>
                <th><div id="helloContainer" style="width:100% !important; margin:0 !important; padding:1em;">
              <h1>Log In</h1>
              <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <table style="margin-left: auto; margin-right: auto;">
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
              <h1>Don't have an account? <a href="signup.php">Sign Up</a></h1>
              <br/><br/><?php externalAuths() ?>
            </div></th>
              </tr>
            </table>
            
            
        </section>
        <?php footer() ?>
  
</body>
</html>