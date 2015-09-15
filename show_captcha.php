<?php
define("allow_inc", true);
require_once("_func.global.php");
?>
<img id="captcha" src="/scr/securimage/securimage_show.php?<?php echo rand(); ?>" style="width: 215px; height: 80px;" alt="CAPTCHA Image" />