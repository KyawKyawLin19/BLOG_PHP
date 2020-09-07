<?php

    session_start();

    require_once('config/config.php');

    require_once('config/common.php');

    if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {

        header('Location: login.php');

    }

    $blogId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM posts where id=".$blogId);

    $stmt->execute();

    $post = $stmt->fetch(PDO::FETCH_OBJ);

    $stmt = $pdo->prepare("SELECT * FROM comments where post_id=".$blogId);

    $stmt->execute();

    $cmtResult = $stmt->fetchAll();

    $authorsResult = [];

    if($cmtResult) {

      foreach($cmtResult as $key=>$value) {

        $authorId = $cmtResult[$key]['author_id'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id=$authorId");

        $stmt->execute();

        $authorsResult[] = $stmt->fetchAll();

      }

    }
    

    if($_POST){

      if(empty($_POST['comment'])){

        if(empty($_POST['comment'])){
  
          $commentError = '* Comment cannot be null';
  
        }
        
      } else {

          $comment = $_POST['comment'];

          $stmt = $pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES (:content,:author_id,:post_id)");

          $result = $stmt->execute(
                array(
                  ':content' => $comment, 
                  ':author_id' => $_SESSION['user_id'],
                  ':post_id' => $blogId
                )
          );

          if($result) {

            header('Location: blogdetail.php?id='.$blogId);

          }

        }

      } 

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Widgets</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left:0px !important;">

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-md-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div class="card-title"  style="text-align:center !important;float:none;">
                  <h4><?php echo $post->title; ?></h4>
                </div>
                <!-- /.user-block -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <img class="img-fluid pad" src="admin/images/<?php echo $post->image; ?>" alt="Photo">

                <p><?php echo $post->content; ?></p>
              <a href="/blog" class="btn btn-default" type="button">Back</a><br><br>
              
              <h3>Comments</h3><hr>
              </div>
              <!-- /.card-body -->

              <?php 
                if($cmtResult){
              ?>

              <?php
                foreach($cmtResult as $key=>$value) { 
              ?>

              <div class="card-footer card-comments">
                <div class="card-comment">
                  <div class="comment-text" style="margin-left:0px !important;">
                    <span class="username">
                      <?php echo $authorsResult[$key][0]['name']; ?>
                      <span class="text-muted float-right"><?php echo $value['created_at']?></span>
                    </span><!-- /.username -->
                    <?php echo $value['content']; ?>
                  </div>
                  <!-- /.comment-text -->
                </div>
                <!-- /.card-comment -->
              </div>

              <?php 
                }
              } 
              ?>
              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="" method="post">

                  <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">

                  <p style="color:red"><p style="color:red"><?php echo empty($commentError)? '' : $commentError; ?></p>
                  <!-- .img-push is used to add margin to elements next to floating images -->
                  <div class="img-push">
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <a href="logout.php" type="button" class="btn btn-default">Logout</a>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2020 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
