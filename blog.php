<?php
require_once 'app/helpers.php';
$title = 'Blog Page';
start_session('smilysess');

if (isset($_GET['c']) && is_numeric($_GET['c'])) {
    db_connect();
    $cat = mysqli_real_escape_string($mysql_link, $_GET['c']);
    $sql = "SELECT u.name, c.category, up.signature, up.avatar, p.title, p.article, p.id, p.user_id, p.date FROM posts AS p
            JOIN users u ON u.id = p.user_id
            JOIN users_profile up ON up.user_id = p.user_id
            JOIN categories c ON c.id = p.categorie_id
            WHERE p.categorie_id = $cat
            ORDER BY p.date DESC ";
} else {
    $sql = "SELECT u.name, c.category, up.signature, up.avatar, p.title, p.article, p.id, p.user_id, p.date FROM posts AS p
           JOIN users u ON u.id = p.user_id
           JOIN users_profile up ON up.user_id = p.user_id
           JOIN categories c ON c.id = p.categorie_id
           ORDER BY p.date DESC ";
}

$uid = $_SESSION['user_id'] ?? '';

$posts = db_query_all($sql);

?>

<?php include 'tmp/header.php';?>
<main>
  <div class="container mt-5">
    <div class="row mb-5">
      <div class="col-md-12 text-center">
        <h1>Share your happy with the world!</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis fuga fugit sequi laborum quos temporibus
          recusandae numquam a sapiente saepe!</p>
      </div>
    </div>
    <div class="row">
      <div class="col-2"></div>
      <div class="col-5">
        <?php if (verify_user()): ?>
        <a class="btn btn-outline-dark" href="addPost.php"><i class="fas fa-plus"></i> Add your post</a>
        <?php endif;?>
      </div>
      <div class="col-5">
        <ul class="nav mr-auto text-right ">
          <li class="nav-item">
            <a class="nav-link filter-by-category" href="blog.php">All</a>
          </li>
          <li class="nav-item">
            <a class="nav-link filter-by-category" href="blog.php?c=1">jokes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link filter-by-category" href="blog.php?c=2">stories</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row my-5">
      <?php foreach ($posts as $post): ?>
      <div class="col-md-10  m-auto">
        <div class="card mb-3">
          <!-- start card-header -->
          <div class="card-header">
            <div class="container">
              <div class="row">
                <div class="col-3">
                  <span>
                    <?=$post['name'];?> </span>
                  <?php if(in_array($post['avatar'],['default.png', 'default_female.png'])): ?>
                  <img class="d-block" src="sys_prof_img/<?=$post['avatar'];?>" height="80" alt="">
                  <?php else:?>
                  <img class="d-block" src="images/<?=$post['avatar'];?>" height="80" alt="">
                  <?php endif;?>
                </div>
                <div class="col-6">
                  <h4>
                    <?=$post['title'] . '  ' . '<small> /' . $post['category'] . '</small>'; ?>
                  </h4>
                </div>
                <div class="col-3">
                  <span class="float-right">
                    <?=$post['date'];?> </span>
                </div>
              </div>
            </div>
          </div>
          <!-- end card-header -->
          <div class="card-body">

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