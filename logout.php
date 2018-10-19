<div style="display:none;">
<?php
session_start();
include 'Connection.php';
//remove PHPSESSID from browser
if ( isset( $_COOKIE[session_name()] ) )
setcookie( session_name(), “”, time()-3600, “/” );
//clear session from globals
$_SESSION = array();
//clear session from disk
session_destroy();
echo "<script>location.href = 'index.html';</script>";
?>
</div>