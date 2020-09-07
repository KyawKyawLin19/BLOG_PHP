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

  if(!empty($_GET['id'])){

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);

    $stmt->execute();

    $user = $stmt->fetch();

  }

  if($_POST){

    if(empty($_POST['name']) || empty($_POST['email'])){

      if(empty($_POST['name'])){

        $nameError = '* Name cannot be null';

      }

      if(empty($_POST['email'])){

        $emailError = '* Email cannot be null';

      }

    } elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4) {

      if(strlen($_POST['password']) < 4 ){

        $passwordError = '* Password should be 4characters at least';

      }

    } else {

      if(!empty($_POST['role'])){

        $role = 1;

      } else {

          $role = 0;

      }

      $id = $_POST['id'];

      $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

      $name = $_POST['name'];

      $email = $_POST['email'];

      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");

      $stmt->bindValue(':email', $email);

      $stmt->bindValue(':id', $id);

      $stmt->execute();
      
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if($user){

        echo "<script>alert('Email Duplicated');</script>";

      } else {

        if ($password != null) {

          $stmt = $pdo->prepare("UPDATE users SET name=:name,email=:email,password=:password,role=:role WHERE id=:id");
          
          $result = $stmt->execute(
            array(
                ':name' => $name,
                ':password' => $password,
                ':email' => $email,
                ':role' => $role,
                ':id'=>$id
            )
        );

        }else{

          $stmt = $pdo->prepare("UPDATE users SET name=:name,email=:email,role=:role WHERE id=:id");
        
          $result = $stmt->execute(
            array(
                ':name' => $name,
                ':email' => $email,
                ':role' => $role,
                ':id' => $id
            )
        );

        }

        if($result){

            echo "<script>alert('User Updated Suceessfully');window.location.href='user_list.php';</script>";

        }
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

                        <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">

                        <div class="form-group">
                            <input type="hidden" name="id" class="form-control" value="<?php echo $user['id'] ?>">
                        </div>

                        <div class="form-group">
                            <label for="">Name</label><p style="color:red"><?php echo empty($nameError)? '' : $nameError; ?></p>
                            <input type="text" name="name" class="form-control" value="<?php echo escape($user['name']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="">Email</label><p style="color:red"><?php echo empty($emailError)? '' : $emailError; ?></p>
                            <input type="email" name="email" value="<?php echo escape($user['email']); ?>" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Password</label><p style="color:red"><?php echo empty($passwordError)? '' : $passwordError; ?></p>
                            <span style="font-size:10px;">The user already has a password</span>
                            <input type="password" name="password" class="form-control">
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
  