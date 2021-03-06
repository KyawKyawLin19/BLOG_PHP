<?php

  session_start();

  require_once('../config/config.php');

  require_once('../config/common.php');

  if(empty($_SESSION['user_id'] && $_SESSION['logged_in'])){

    header('Location: login.php');

  }

  if($_SESSION['role'] == 0 ) {

    header('Location: login.php');

  }

  if($_POST){

    if(empty($_POST['title']) || empty($_POST['content'])) {

      if(empty($_POST['title'])){

        $titleError = '* Title cannot be null';

      }

      if(empty($_POST['content'])){

        $contentError = '* Content cannot be null';

      }

    } else {

      $id = $_POST['id'];

      $title = $_POST['title'];

      $content = $_POST['content'];

        if($_FILES['image']['name'] != null) {

          $file = 'images/'.($_FILES['image']['name']);

          $imageType = pathinfo($file,PATHINFO_EXTENSION);

          if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg' ) {

              echo "<script>alert('Image must be png,jpg,jpeg');</script>";

          } else {

              $title = $_POST['title'];

              $content = $_POST['content'];

              $image = $_FILES['image']['name'];

              move_uploaded_file($_FILES['image']['tmp_name'], $file);

              $stmt = $pdo->prepare("UPDATE posts SET title=:title,content=:content,image=:image WHERE id=:id");

              $result = $stmt->execute(
                array(
                  ':title' => $title,
                  ':content' => $content,
                  ':image' => $image,
                  'id'=>$id
              )
              );

              if($result){

                  echo "<script>alert('Successfully Updated!');window.location.href='index.php';</script>";
              
              }
              
          }


      } else {

          $stmt = $pdo->prepare("UPDATE posts SET title=:title,content=:content WHERE id=:id");

          $result = $stmt->execute(
            array(
              ':title' => $title,
              ':content' => $content,
              'id'=>$id
          )
          );

          if($result){

              echo "<script>alert('Successfully Updated!');window.location.href='index.php';</script>";
          
          }

      }

    }

  }

  $stmt = $pdo->prepare("SELECT * FROM posts WHERE id =".$_GET['id']);

  $stmt->execute();

  $post = $stmt->fetch(PDO::FETCH_ASSOC);

  require_once('header.php');
 
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          
          <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="" class="" method="post" enctype="multipart/form-data">
                        
                        <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                        
                        <div class="form-group">
                            <input type="hidden" name="id" class="form-control" value="<?php echo $post['id']; ?>" >
                        </div>
                        
                        <div class="form-group">
                            <label for="">Title</label><p style="color:red"><?php echo empty($titleError)? '' : $titleError; ?></p><br>
                            <input type="text" name="title" class="form-control" value="<?php echo escape($post['title']); ?>" >
                        </div>

                        <div class="form-group">
                            <label for="">Content</label><br><p style="color:red"><?php echo empty($contentError)? '' : $contentError; ?></p><br>
                            <textarea name="content" id="" cols="80" rows="8"><?php echo escape($post['content']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="">Image</label><br>
                            <img src="images/<?php echo $post['image'] ?>" class="img-fluid" alt="" width="150" height="150"><br><br>
                            <input type="file" name="image">
                        </div>

                        <div class="from-group">
                          <input type="submit" class="btn btn-success" value="Submit">
                          <a href="index.php" class="btn btn-warning">Back</a>
                        </div>
                
                    </form>
                </div>
            </div>
            <!-- /.card -->
          </div>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php
    require_once('footer.html');
  ?>
  