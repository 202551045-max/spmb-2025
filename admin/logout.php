<?php
require_once '../config.php';
startSession();

if (!isAdminLoggedIn()) {
    redirect('login.php');
}

session_destroy();
redirect('login.php');
?>
