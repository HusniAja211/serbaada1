<?php 

session_start();
// Menghapus semua session
session_unset();
session_destroy();

header("Location: ../../../index.php");
exit;