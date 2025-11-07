<?php

include "koneksi.php"; // file koneksi ke database

session_start();
session_destroy();
header("Location: ../index.php");
exit;
?>