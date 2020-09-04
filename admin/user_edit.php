<?php

  session_start();

  require_once('../config/config.php');

  if(empty($_SESSION['user_id'] && $_SESSION['logged_in'])){

    header('Location: login.php');

  }

  if($_SESSION['role'] == 0 ) {

    header('Location: login.php');

  }

  if(!empty($_GET['id'])){

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);

    $stmt->execute();

    $user = $stmt->fetch();

  }

  if($_POST){

    if(!empty($_POST['role'])){

        $role = 1;

    } else {

        $role = 0;

    }

    $name = $_POST['name'];

    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email and id!=:id");

    $stmt->bindValue(':email', $email);

    $stmt->bindValue(':id', $_GET['id']);

    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){

      echo "<script>alert('Email Duplicated');</script>";

    } else {

      $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id=".$_POST['id']);

      if($stmt->execute()){

          echo "<script>alert('User Updated Suceessfully');window.location.href='user_list.php';</script>";

      }
      
    }

  }

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
          <h2>Edit User Info</h2>
            <div class="card">
                <div class="card-body">
                    <form action="" class="" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <input type="hidden" name="id" class="form-control" value="<?php echo $user['id'] ?>">
                        </div>

                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="">Email</label><br>
                            <input type="email" name="email" value="<?php echo $user['email']; ?>" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="">Role</label><br>
                            <input type="checkbox" name="role" <?php if($user['role']){ echo 'checked'; } ?>>
                        </div>

                        <div class="from-group">
                          <input type="submit" class="btn btn-success" value="Submit">
                          <a href="user_list.php" class="btn btn-warning">Back</a>
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
  