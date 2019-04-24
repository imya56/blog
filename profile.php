<?php
require_once 'app/helpers.php';
$title = 'Blog Page';
start_session('smilysess');

if (!verify_user()) {
    header('location:blog.php');
}
$uid = $_SESSION['user_id'];
$user_sql = "SELECT u.id,u.name,u.email, up.signature, up.gender, DATE_FORMAT(u.registr_at,'%d.%m.%Y')  
                    registr_at , up.avatar FROM users u
             JOIN users_profile up ON u.id = up.user_id
             WHERE u.id = $uid";
$user = db_query($user_sql);
 
if (isset($_GET['del']) && filter_var($_GET['del']) === 'd') {
  if(file_exists('images/' . $user['avatar'])){
    unlink('images/' . $user['avatar']);
  }
    $user_avatar = ($user['gender'] == 'male') ? 'default.png' : 'default_female.png';
    $sql = "UPDATE users_profile SET avatar = '$user_avatar' WHERE user_id = $uid";
    db_update($sql);

    header('location:profile.php');

}
if (isset($_POST['submit'])) {
    db_connect();
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
    $name = mysqli_real_escape_string($mysql_link, $name);
    $signature = trim(filter_input(INPUT_POST, 'signature', FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES));
    $signature = mysqli_real_escape_string($mysql_link, $signature);
    if (!$name || mb_strlen($name) < 2) {
        $error = ' * name must be minimum 2 chars';
    } else {

        if ($name != $user['name']) {
            $sql_name = "UPDATE users SET name = '$name' WHERE id = $uid";
            db_update($sql_name);
            $_SESSION['user_name'] = $name;
            sleep(0.5);
            header('location: profile.php');
        }
        if ($signature != $user['signature']) {
            $sql_signature = "UPDATE users_profile SET signature = '$signature' WHERE id = $uid";
            db_update($sql_signature);
            sleep(0.5);
            header('location: profile.php');
        }

    }

}

if (isset($_POST['submitAva'])) {
    $validAva = false;
    $ex = ['png', 'jpg', 'jpeg', 'bmp', 'svg'];
    $maxsize = 1024 * 1024 * 5;
    if (isset($_FILES['avatar']['error']) && $_FILES['avatar']['error'] == 0) {
        if (isset($_FILES['avatar']['tmp_name']) && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
            if (isset($_FILES['avatar']['size']) && $_FILES['avatar']['size'] <= $maxsize) {
                $file_info = pathinfo($_FILES['avatar']['name']);
                if (in_array($file_info['extension'], $ex)) {
                    $avatar_name = date('d.m.y.h.i.s') . '-' . $_FILES['avatar']['name'];
                    move_uploaded_file($_FILES['avatar']['tmp_name'], 'images/' . $avatar_name);
                    $user_avatar = $avatar_name;
                    $validAva = true;
                    $sql = "UPDATE users_profile SET avatar = '$user_avatar' WHERE user_id = $uid";
                    db_update($sql);
                    header('location:profile.php');
                }

            }
        }
    }

    if (!$validAva) {
        $error['avatar'] = ' * can\'t upload avatar now, ty later please ';
    }
}

if (isset($_GET['cat']) && is_numeric($_GET['cat'])) {
    db_connect();
    $cat = filter_var($_GET['cat'], FILTER_SANITIZE_STRING);
    $cat = mysqli_real_escape_string($mysql_link, $_GET['cat']);


    $post_sql = "SELECT p.title,p.id, p.user_id, p.article, p.date, p.categorie_id, up.signature FROM posts p
             JOIN categories c ON c.id = p.categorie_id
             JOIN users_profile up ON up.user_id = p.user_id
             WHERE p.user_id = $uid AND p.categorie_id = $cat ";
} else {
    $post_sql = "SELECT p.title,p.id, p.user_id, p.article, p.date, p.categorie_id, up.signature FROM posts p
  JOIN categories c ON c.id = p.categorie_id
  JOIN users_profile up ON up.user_id = p.user_id
  WHERE p.user_id = $uid";
}
$posts = db_query_all($post_sql);

?>

<?php include 'tmp/header.php';?>
<main>
  <div class="container-fluid mt-4">
    <div class="container ">
      <div class="row">
        <div class="col-6">
          <?php if(in_array($user['avatar'], ['default.png','default_female.png'])): ?>
          <img src="sys_prof_img/<?=$user['avatar'];?>" height="150" alt="">
          <?php else: ?>
          <img src="images/<?=$user['avatar'];?>" height="150" alt="">
          <?php endif;?>
          <form action="" method="POST" enctype="multipart/form-data" novalidate="novalidate" autocomplete="off">
            <div class="mt-2">
              <label for="avatar" size="1" id="avatar2" style="cursor:pointer;"> Change avatar <i class="fas fa-camera fa-2x"></i>
                <input type="file" size="1" name="avatar" id="avatar" style="opacity:0; display:none;">
              </label>
              <input type="submit" value="" name="submitAva" id="submitAva" style="opacity:0;">
            </div>
          </form>
          <span class="mb-1"><a href="profile.php?del=d">delete avatar </a></span>

          <span class="d-block mb-1"><b>Registrate date:</b>
            <?=$user['registr_at']?> </span>
          <span> <b>Registrate email: </b>
            <?=$user['email'];?> </span>
        </div>

        <div class="col-6">
          <form action="" method="POST" enctype="multipart/form-data" novalidate="novalidate" autocomplete="off">
            <div class="form-group">
              <label for="name">Your name:</label>
              <input type="text" name="name" id="name" value="<?=output($user['name'])?>">
            </div>
            <div class="form-group">
              <label for="signature">Your signature:</label>
              <textarea class="form-control" name="signature" id="signature" rows="5"> <?=output($user['signature']);?> </textarea>
            </div>
            <input type="submit" name="submit" class="btn btn-warning btn-block" value="save">


          </form>

        </div>
      </div>


      <div class="row mt-5">
        <div class="col-12">
          <span class="ml-5"> My posts: </span>
          <a href="profile.php" class="ml-3 text-dark"> all </a>
          <a href="profile.php?cat=1" class="ml-3 text-dark"> jokes </a>
          <a href="profile.php?cat=2" class="ml-3 text-dark"> storis </a>
        </div>
      </div>
      <hr class="profile-hr">
      <?php foreach ($posts as $post): ?>
      <div class="col-md-10  m-auto">
        <div class="card mb-3">
          <div class="card-header">
            <span>
              <?=$user['name'];?> </span>
            <?php if(in_array($user['avatar'],['default.png', 'default_female.png'])): ?>
            <img class="d-block" src="sys_prof_img/<?=$user['avatar'];?>" height="80" alt="">
            <?php else:?>
            <img class="d-block" src="images/<?=$user['avatar'];?>" height="80" alt="">
            <?php endif;?>
            <span class="float-right">
              <?=$post['date'];?> </span>
          </div>
          <div class="card-body">
            <h4>
              <?=$post['title'];?>
            </h4>
            <p>
              <?=$post['article']?>
            </p>

            <?php if (!empty($post['signature'])): ?>
            <hr class='post-hr mt-5'>
            <span class="text-center p-2 mb-4 ml-3">
              <?=output($post['signature']);?> </span>
            <br>
            <?php endif;?>

            <?php if($post['user_id'] == $uid): ?>         
            <div class="btn-group float-right">
              <button class="btn btn-light btn-editor btn-sm dropdown-toggle" type="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
              </button>
              <div class="dropdown-menu">
                <a href="edit_post.php?pid=<?=$post['id'];?>" class="dropdown-item "> <i class="fas fa-edit"></i> Edit
                </a>
                <a href="delete_post.php?pid=<?=$post['id'];?>" class="dropdown-item "> <i class="fas fa-trash-alt"></i>
                  Delete
                </a>
              </div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
      <?php endforeach;?>
          </div>

        </div>




</main>



<?php include 'tmp/footer.php';?>