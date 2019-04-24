<!doctype html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="smile">
  <meta name="author" content="Veta ">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
    crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/"
    crossorigin="anonymous">
  <link href="css/">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <title>

    <?=$title;?>

  </title>
</head>

<body class="bg-light">

  <header>
    <nav class="navbar navbar-expand-md navbar-dark  bg-dark fixed-top">
      <div class="container">

        <a class=" navbar-brand text-white  font-italic" href="./"><i class="far fa-grin-squint-tears fa-1x"></i>

          Jokes</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="text-white"><i class="fas fa-align-justify"></i></span>
        </button>

        <div class="  collapse navbar-collapse " id="navbarSupportedContent">
          <ul class="navbar-nav  mr-auto ">
          
            <li class="nav-item ">
              <a class="nav-link text-white hvr-bounce-to-top  " href="about.php">About</a>
            </li>


            <li class="nav-item">
              <a class="nav-link text-white" href="blog.php">Blog</a>
            </li>

            <li class="nav-item ">
              <a class="nav-link text-white hvr-bounce-to-top  " href="contact.php">Contact-us</a>
            </li>

          </ul>
          <ul class="navbar-nav ml-auto">
            <?php if (!isset($_SESSION['user_id'])): ?>
            <li class="nav-item">

              <a class="nav-link text-white" href="signin.php"> <i class="fas fa-sign-in-alt mr-1"></i>Sign in</a>
            </li>
            <li class="nav-item">

              <a class="nav-link text-white" href="signup.php"> <i class="fas fa-user-plus mr-1"></i>Sign up</a>
            </li>
            <?php else: ?>

            <li class="nav-item disabled">

              <a class="nav-link  text-white" href="user.php" id="font">


                Profile </a>
            </li>



            <a class="nav-link text-white " id="font">
              <span class="blink_me"> <i class='far fa-dot-circle  ' style="color: green"></i></span>

              <?=htmlspecialchars($_SESSION['user_name']);?> </a>

            <li class="   nav-item" id="font">
              <a class="nav-link text-white" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i>Logout</a>
            </li>
            <?php endif;?>
          </ul>
        </div>
      </div>
    </nav>


  </header>

  <?php if (isset($_GET['sm'])): ?>




  <div id="sm-box" class="container fixed-top mt-3">
    <div class="col-8 m-auto">
      <div class="alert alert-success text-center" role="alert">
        <?=$_GET['sm'];?>
      </div>
    </div>
  </div>
  <?php endif;?>