<?php
require_once 'app/helpers.php';
$title = 'Registrare';
start_session('smilysess');
$error = [
    'name' => '',
    'email' => '',
    'password' => '',
    'passwordConfirm' => '',
    'image' => '',
];

if (isset($_POST['submit'])) {
  

    if(isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']){

        db_connect();
        $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
        $name = mysqli_real_escape_string($mysql_link, $name);
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
        $email = mysqli_real_escape_string($mysql_link, $email);
        $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
        $password = mysqli_real_escape_string($mysql_link, $password);
        $passwordConfirm = trim(filter_input(INPUT_POST, 'passwordConfirm', FILTER_SANITIZE_STRING));
        $passwordConfirm = mysqli_real_escape_string($mysql_link, $passwordConfirm);
        $gender = filter_var($_POST['gender']);
        $gender = mysqli_real_escape_string($mysql_link, $gender);
        $formValid = true;
          if (!$name || mb_strlen($name) < 2) {
            $error['name'] = ' * name must be minimum 2 chars';
            $formValid = false;
          }
          if (!$email) {
            $error['email'] = ' * false email';
          } elseif ($row = db_query("SELECT * FROM users WHERE email = '$email' LIMIT 1")) {
            $error['email'] = 'this email used';
            $formValid = false;
          }
          if (!$password || strlen($password) < 8 || strlen($password) > 15) {
            $error['password'] = ' * password must be between 6-18 chars';
            $formValid = false;
          }
          if ($password != $passwordConfirm) {
            $error['passwordConfirm'] = 'sismaot lo teomot';
            $formValid = false;
          }
     
          if ($formValid) {                               
             if (isset($_FILES['image'])) {
                 $validAva = false;
                 $ex = ['png', 'jpg', 'jpeg', 'bmp', 'svg'];
                 $maxsize = 1024 * 1024 * 5;
                   if (isset($_FILES['image']['error']) && $_FILES['image']['error'] == 0) {
                      if (isset($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
                         if (isset($_FILES['image']['size']) && $_FILES['image']['size'] <= $maxsize) {
                            $file_info = pathinfo($_FILES['image']['name']);
                             if (in_array($file_info['extension'], $ex)) {
                                 $image_name = date('d.m.y.h.i.s') . '-' . $_FILES['image']['name'];
                                 move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $image_name);
                                 $user_avatar = $image_name;
                                 $validAva = true;
                             }
    
                         }
                     }
                 }
    
                if (!$validAva) {
                    $error['image'] = ' * can\'t upload image now, try later please ';
                    $user_avatar = ($gender == 'male') ? 'default.png' : 'default_female.png';
                }
    
            } else {
              $user_avatar = ($gender == 'male') ? 'sys_prof_img/default.png' : 'sys_prof_img/default_female.png';
            }
            $password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users VALUES(null, '$name', '$email', '$password', NOW())";
            $uid = db_insert($sql, true);
            $sql = "INSERT INTO users_profile VALUES(null, $uid, '$gender', '$user_avatar','')";
            db_insert($sql);
            sess_login($uid, $name);
            header('location:blog.php?sm= Welcom to our blog,' . $name . ', and share your stories!');
        }

    }

    $token = csrf_token();

} else {
    
    $token = csrf_token();
}

?>



<?php include 'tmp/header.php';?>
<main>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-5  pt-2 m-auto">
        <h1 class="text-center mb-3">Registrare PAGE</h1>
        <form action="" method="POST" enctype="multipart/form-data" novalidate="novalidate" autocomplete="off">
        <input type="hidden" name="token" value="<?= $token; ?>">
          <div class="form-group">
            <label class="text-left" for="name"> * Name:</label>
            <input class="form-control" type="text" name="name" id="name" value="<?=old('name');?>">
            <span class="text-danger">
              <?=$error['name'];?></span>
          </div>
          <div class="form-group">
            <label for="email"> * Email:</label>
            <input class="form-control" type="email" name="email" id="email" value="<?=old('email');?>">
            <span class="text-danger">
              <?=$error['email'];?></span>
          </div>
          <div class="form-group">
            <label for="password"> * Password:</label>
            <input class="form-control" type="password" name="password" id="password">
            <span class="text-danger">
              <?=$error['password'];?></span>
          </div>
          <div class="form-group">
            <label for="passwordConfirm"> * Password confirm:</label>
            <input class="form-control" type="password" name="passwordConfirm" id="passwordConfirm">
            <span class="text-danger">
              <?=$error['passwordConfirm'];?></span>
          </div>
          <div class="from-group">
            <label class="radio-inline mr-2">
                choose gender: 
            </label>
            <label class="radio-inline mr-1">
                <input type="radio" name="gender" value="male"> male
            </label>
            <label class="radio-inline mr-3">
                <input type="radio" name="gender" value="female"> female
            </label>
 
        </div>
          <div class="form-group">
            <label for="image">Profile image</label>
            <div class="input-group">
              <div class="custom-file">
                <input type="file" name="image" class="custom-file-input" id="inputGroupFile04">
                <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
              </div>
            </div>
          </div>
          <input type="submit" class="btn btn-warning btn-block" value="Registrate Me!" name="submit">
          <span class="text-danger">
            <?=$error['image'];?> </span>
        </form>
      </div>
    </div>
  </div>
</main>

 



<?php include 'tmp/footer.php';?>