<?php
require_once 'lib/function.php';
require_once 'class/User.php';


$newLogin = new User;
$login = $newLogin->UserLogin();

require_once 'header.php';
require_once 'footer.php';


getFooter();