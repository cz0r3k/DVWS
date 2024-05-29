<?php
session_start();
$session_directory = session_save_path()."/json";
$session_file = $session_directory."/sess_".session_id();
unlink($session_file);
unset($_SESSION["username"]);
unset($_SESSION["firstname"]);
unset($_SESSION["lastname"]);
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
header("Location:csrf.php");
exit();
