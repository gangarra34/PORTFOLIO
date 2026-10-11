<?php require __DIR__.'/includes/functions.php';$_SESSION=[];session_regenerate_id(true);$_SESSION['flash']='You have logged out successfully.';header('Location: index.php');exit;
