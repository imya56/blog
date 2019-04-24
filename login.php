<?php
require_once 'app/helpers.php';
$title = 'Login Page';
$error = '';
start_session('smilysess');
if(verify_user()){
  header('location: blog.php');
}

if (isset($_POST['submit'])) {
echo 'no';
  if(isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']){
    echo 'ye';
    db_connect();
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
    $email = mysqli_real_escape_string($mysql_link,$email);
    $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
    $password = mysqli_real_escape_string($mysql_link,$password);
 
    if (!$email) {
        $error  
        = ' * false email';
    } elseif (!$password) {
        $error = ' * false password';
    } else {

    
       
        $sql = "SELECT * FROM users WHERE email = '$email'  LIMIT 1";
        $user = db_query( $sql);
        if ($user) {
 
         if(  password_verify( $password , $user['password'])){
            sess_login($user['id'],$user['name']);
             header('location:blog.php?sm=gdfg');
            
          
            exit;
         }    
        } else {
            $error = ' * false email and password combination';
        }
    }

  }

    $token = csrf_token();

} else {

  $token = csrf_token();

}
?>

<?php include 'tmp/header.php';?>
<main>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6  m-auto">
        <h1 class="text-center">Login</h1>
        <form action="" method="POST" autocomplete="off" novalidate="novalidate">
          <input type="hidden" name="token" value="<?= $token; ?>">
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" value="<?=old('email');?>" class="form-control" id="password" name="email">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control">
          </div>
          <input type="submit" name="submit" value="Login" class="mt-3 btn btn-warning">
          <?php if (isset($error)): ?>
          <span class="text-danger ml-3">
            <?=$error?> </span>
          <?php endif;?>
        </form>
      </div>
    </div>
  </div>
</main>



<?php include 'tmp/footer.php';?>