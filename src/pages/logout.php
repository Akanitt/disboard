<?php
require '../config/database.php';
session_destroy();

header('location: ../pages/login.php');
exit();
?>