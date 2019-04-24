<?php
require_once 'app/helpers.php';
$title = 'Blog Page';
start_session('smilysess');
 
session_destroy();
header('location:login.php');