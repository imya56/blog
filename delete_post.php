<?php
require_once 'app/helpers.php';
start_session('smilysess');
$title = 'Delete post Page';
 

if (!verify_user()) {
    header('location:blog.php');
    exit;
}
$uid = $_SESSION['user_id'];
if(isset($_GET['pid']) && is_numeric($_GET['pid'])){
    db_connect();
    $pid = mysqli_real_escape_string($mysql_link, $_GET['pid']);
    $sql = "DELETE FROM posts WHERE id = $pid AND user_id = $uid LIMIT 1";
    $res = db_delete($sql) ? '?sm=post deleted' : '';
    header('location:blog.php' . $res);
}