<?php
session_start();
session_unset(); // desactive la session
session_destroy();
setcookie('session', '', time() -3444, '/', null, false, true);
header ('location: index.php');