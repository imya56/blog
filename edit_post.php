<?php
require_once 'app/helpers.php';
$title = 'Blog Page';
start_session('smilysess');
if (!verify_user()) {
    header('location:blog.php');
    exit;
}


db_connect();

$pid = filter_var($_GET['pid'], FILTER_SANITIZE_STRING);
$pid = mysqli_real_escape_string($mysql_link, $pid);
$uid = $_SESSION['user_id'];

$sql = "SELECT * FROM posts p WHERE p.id = $pid AND p.user_id = $uid LIMIT 1";

$post = db_query($sql);
if (!$post) {
    header('location:blog.php');
    exit;
}

 
$new_title = trim(filter_input(INPUT_POST, 'new_title', FILTER_SANITIZE_STRING));
$new_title = mysqli_real_escape_string($mysql_link, $new_title);
$new_article = trim(filter_input(INPUT_POST, 'new_article', FILTER_SANITIZE_STRING));
$new_article = mysqli_real_escape_string($mysql_link, $new_article);
$valid = true;
if (!$new_title || mb_strlen($new_title) < 3) {
    $error['new_title'] = ' * error title, minimum 3 chars';
    $valid = false;

}
if (!$new_article || mb_strlen($new_article) < 3) {
    $error['new_article'] = ' * error article, minimum 3 chard';
    $valid = false;
}

if ($valid) {

     $sql = "UPDATE posts p SET article = '$new_article', title='$new_title'  WHERE id = $pid AND user_id = $uid";
     $res = db_update($sql);
    if ($res) {
        header('location: blog.php');
    } else {
        $error = ' * try later pls'; // ЧЕТО ТУТ НЕ ТАК

    }
}

?>

<?php include 'tmp/header.php';?>
<main>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6 text-center m-auto">
        <h1>INDEX PAGE</h1>
        <form action="" method="POST">
          <div class="form-group">
            <label for="new_title">Title:</label>
            <input type="text" name="new_title" id="new_title" class="form-control" value="<?=$post['title'];?>">
          </div>
          <div class="form-group">
            <label for="new_article">Article</label>
            <textarea class="form-control" name="new_article" id="new_article" rows="10"> <?=$post['article'];?> </textarea>
          </div>
          <input type="submit" value="save" name="submit" class="btn btn-primary btn-block mb-5">
          <a href="blog.php" class="btn btn-primary float-left" > < </a>
         </form>
      </div>
      
    </div>
  </div>
</main>



<?php include 'tmp/footer.php';?>