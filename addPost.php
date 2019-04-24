<?php
require_once 'app/helpers.php';
$title = 'post Page';
start_session('smilysess');

if (!verify_user()) {
    header('location: blog.php');
    exit;
}

$error = [
    'ptitle' => '',
    'article' => '',
    'category' => '',
];
$valid = true;

if (isset($_POST['submit'])) {
    db_connect();
    $ptitle = trim(filter_input(INPUT_POST, 'ptitle', FILTER_SANITIZE_STRING));
    $ptitle = mysqli_real_escape_string($mysql_link, $ptitle);
    $article = trim(filter_input(INPUT_POST, 'article', FILTER_SANITIZE_STRING));
    $article = mysqli_real_escape_string($mysql_link, $article);
    $cat = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $cat = mysqli_real_escape_string($mysql_link, $cat);

    if (!$ptitle || mb_strlen($ptitle) < 3) {
        $error['ptitle'] = ' * error title, minimum 3 chars';
        $valid = false;

    }
    if (!$article || mb_strlen($article) < 3) {
        $error['article'] = ' * error article, minimum 3 chard';
        $valid = false;
    }

    if (!$cat || !in_array($cat, [1, 2])) {
        $error['category'] = ' * you must choose category';
        $valid = false;

    }

    if ($valid) {
        $uid = $_SESSION['user_id'];
        $cid = $_POST['category'];
        $sql = "INSERT INTO posts VALUES(null, $uid, $cid ,'$ptitle', '$article', NOW())";
        $res = db_insert($sql);
        if ($res) {
            header('location: blog.php');
        } else {
            $error = ' * try later pls'; // ЧЕТО ТУТ НЕ ТАК

        }
    }

}

?>

<?php include 'tmp/header.php';?>
<main>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6 text-center m-auto">
        <h1>add post PAGE</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis fuga fugit sequi laborum quos temporibus
          recusandae numquam a sapiente saepe!</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 m-auto">
        <form action="" novalidate="novalidate" autocomplete="off" method="POST">
          <div class="form-group">
            <label for="ptitle">Title:</label>
            <input type="text" name="ptitle" id="ptitle" value="<?=old('ptitle');?>" class="form-control">
            <span class="text-danger">
              <?=$error['ptitle'];?>
            </span>
          </div>
          <div class="form-controle mb-4">
            <label for="category">Choose category</label>
            <select name="category" id="category" class="form-control">
              <option value="">Choose post category</option>
              <option value="1">Joke</option>
              <option value="2">Story</option>
            </select>
            <span class="text-danger">
              <?=$error['category'];?>
            </span>
          </div>
          <div class="form-group">
            <label for="article">Article:</label>
            <textarea class="form-control" name="article" id="article" rows="10"> <?=old('article');?></textarea>
            <span class="text-danger">
              <?=$error['article']?>
            </span>
          </div>
          <input type="submit" value="Post" name="submit" class="btn btn-primary mb-5">
        </form>
      </div>
    </div>
  </div>
</main>



<?php include 'tmp/footer.php';?>