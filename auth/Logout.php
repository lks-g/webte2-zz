<?php
session_start();
unset($_SESSION['name']);
unset($_SESSION['role']);
unset($_SESSION['id']);
header("Location:AuthIndex.php");