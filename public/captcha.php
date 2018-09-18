<?php
require('../recaptcha/autoload.php');
if(isset($_POST['submitpost']))
{
  if(isset($_POST['g-recaptcha-response']))
  {
    $recaptcha = new ReCaptcha\ReCaptcha('6LeVzWkUAAAAAPDSuMnmvFBCekz7P08Av3s2-U6S');
    $resp = $recaptcha->verify($_POST['g-recaptcha-response']);
    if ($resp->isSuccess()) {
      var_dump('Captcha Valide');
    } else {
      $errors = $resp->getErrorCodes();
      var_dump('Captcha Invalide');
      var_dump($errors);
    }
  }
  else
  {
    var_dump('Captcha non rempli');
  }
}
?>
<html>
  <head>
    <title>reCAPTCHA demo: Simple page</title>
     <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <body>
    <form method="POST">
      <div class="g-recaptcha" data-sitekey="6LeVzWkUAAAAAH5NF82WSqEiMQ9Rl83MG1XYOWz9"></div>
      <br/>
      <input type="submit" value="Valider" name="submitpost">
    </form>
  </body>
</html>