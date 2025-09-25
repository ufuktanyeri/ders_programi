<?php
session_start();

// Session'ı temizle
session_destroy();

// Login sayfasına yönlendir
header('Location: login.php?msg=logout');
exit;
?>