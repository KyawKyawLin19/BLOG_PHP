<?php

  session_start();

  require_once('../config/config.php');

  require_once('../config/common.php');

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {

    header('Location: login.php');

  }

  if($_SESSION['role'] == 0 ) {

    header('Location: login.php');

  }

  if(!empty($_POST['search'])) {

    setcookie('search', $_POST['search'], time() + (86400 * 30 ), "/");

  } else {

    if(empty($_GET['pageno'])) {

      unset($_COOKIE['search']);
      
      setcookie('search', null, -1, '/');

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
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fa fa-user" aria-hidden="true"></i> User Listings</h3>
              </div>
              <!-- /.card-header -->

            <?php

                if(!empty($_GET['pageno'])){

                    $pageno = $_GET['pageno'];

                } else {

                    $pageno = 1;

                } 

                $numofrows = 5;
                
                $offset = ($pageno - 1) * $numofrows;

                if(empty($_POST['search']) && empty($_COOKIE['search'])){

                  $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");

                  $stmt->execute();

                  $rows = $stmt->fetchAll();

                  $totalPages = ceil(count($rows) / $numofrows);
                  
                  $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$numofrows");

                  $stmt->execute();

                  $users = $stmt->fetchAll(PDO::FETCH_OBJ);

                } else {

                  $search = !empty($_POST['search'])? $_POST['search'] : $_COOKIE['search'];

                  $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$search%' ORDER BY id DESC");

                  $stmt->execute();

                  $rawResult = $stmt->fetchAll();

                  $totalPages = ceil(count($rawResult) / $numofrows);

                  $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numofrows");

                  $stmt->execute();
                  
                  $users = $stmt->fetchAll(PDO::FETCH_OBJ);

                }

            ?>

              <div class="card-body">
                <div>
                  <a href="user_add.php" class="btn btn-primary" type="button"><i class="fa fa-user-plus" aria-hidden="true"></i> Add User</a>
                </div>
                <br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    
                        if($pageno == 1){

                            $id = 1;

                        }else {

                            $id = ($pageno - 1) * $numofrows + 1;

                        }
                    
                    ?>
                    <?php foreach($users as $user){  ?>
                    <tr>
                      <td><?php echo $id; ?></td>
                      <td><?php echo escape($user->name) ?></td>
                      <td><?php echo escape($user->email) ?></td>
                      <td><?php if($user->role){ echo 'admin';} else { echo 'user';} ?></td>
                      <td>
                        <div class="btn-group">
                          <div class="container">
                            <a href="user_edit.php?id=<?php echo $user->id;?>" class="btn btn-warning" type="button">Edit</a>
                          </div>
                          <div class="container">
                            <a href="user_delete.php?id=<?php echo $user->id;?>" 
                            onclick="return confirm('Are you sure you want to delete this User')"
                            class="btn btn-danger" type="button">Delete</a>
                          </div>
                        </div>
                      </td>
                    </tr>

                    <?php $id++; } ?>
                  </tbody>
                </table>
                <br>
                <nav aria-label="Page navigation example" style="float:right;">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                    
                    <li class="page-item <?php if($pageno <= 1 ){ echo 'disabled'; } ?>">
                      <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; }else{ echo '?pageno='.($pageno-1); } ?>">Previous</a>
                    </li>
                    
                    <li class="page-item"><a class="page-link" href="#"><?php echo $pageno ?></a></li>
                    
                    <li class="page-item <?php if($pageno >= $totalPages){ echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno >= $totalPages){ echo '#'; } else { echo '?pageno='.($pageno+1); } ?>" >Next</a>
                    </li>
                    
                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $totalPages; ?>">Last</a></li>
                  </ul>
                </nav>
              </div>
              <!-- /.card-body -->

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
  