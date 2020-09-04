<?php


  session_start();

  require_once('header.php');

  require_once('../config/config.php');

  if(empty($_SESSION['user_id'] && $_SESSION['logged_in'])){

    header('Location: login.php');

  }

  if($_SESSION['role'] == 0 ) {

    header('Location: login.php');

  }

  if($_POST) {
    
    $file = 'images/'.($_FILES['image']['name']);

    $imageType = pathinfo($file,PATHINFO_EXTENSION);

    if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg' ) {

      echo "<script>alert('Image must be png,jpg,jpeg');</script>";

    } else {

      $title = $_POST['title'];

      $content = $_POST['content'];

      $image = $_FILES['image']['name'];

      move_uploaded_file($_FILES['image']['tmp_name'], $file);

      $stmt = $pdo->prepare("INSERT INTO posts(title,content,image,author_id) VALUES (:title,:content,:image,:author_id)");

      $result = $stmt->execute(

                  array(':title'=>$title,':content'=>$content,':image'=>$image,':author_id'=>$_SESSION['user_id'])

                );

      if($result){

        echo "<script>alert('Successfully added!');window.location.href='index.php';</script>";
      
      }
      
    }

  }
 
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
                    <form action="add.php" class="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="">Content</label><br>
                            <textarea name="content" id="" cols="80" rows="8"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="">Image</label>
                            <input type="file" name="image" required>
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
  