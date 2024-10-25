<?php
  session_start();
  require("config/config.php");
  require("config/common.php");
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header("Location:login.php");
  }

  if(!empty($_GET['pageno'])){
    $pageno = $_GET['pageno'];
  }else{
    $pageno = 1;
  }
  $numOfRec = 6;
  $offset = ($pageno-1) * $numOfRec;
  $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
  $stmt->execute();
  $dataOnTrack = $stmt->fetchAll();
  $total_pages = ceil(count($dataOnTrack) / $numOfRec);

  $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfRec");
  $stmt->execute();
  $results = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blogs Page</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="">

  <!-- Content Wrapper. Contains page content -->
  <div class="" style="min-height: 654px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-sm-12">
            <h1 class="text-center">Blog Site</h1>
          </div>
        </div>
        <div class="row">
          <?php
            if($results){
              foreach($results as $value){
          ?>
          <div class="col-md-4">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div class="card-title float-none text-center">
                  <?php echo escape($value['title']); ?>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <a href="blogDetails.php?id=<?php echo $value['id'] ?>">
                  <img class="img-fluid pad" style="width:100%;height: 250px!important;" src="admin/images/<?php echo $value['image']; ?>" alt="Photo">
                </a>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <?php
              }
            }
          ?>
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-12">
            <nav aria-label="Page navigation example" class="float-right">
                <ul class="pagination">
                  <li class="page-item">
                    <a class="page-link" href="?pageno=1">First</a>
                  </li>
                  <li class="page-item <?php if($pageno <= 1){ echo "disabled"; } ?>">
                    <a class="page-link" href="<?php if($pageno <= 1){ echo "#";}else{echo "?pageno=".($pageno-1);} ?>">Previous</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#"><?php echo $pageno; ?></a>
                  </li>
                  <li class="page-item <?php if($pageno >= $total_pages){ echo "disabled";} ?>">
                    <a class="page-link" href="<?php if($pageno >= $total_pages){echo "#";}else{echo "?pageno=".($pageno+1);} ?>">Next</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a>
                  </li>
                </ul>
            </nav>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>

  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer ml-0">
    <div class="float-right d-none d-sm-block">
      <a href="logout.php" type="btn" class="btn btn-info">Log out</a>
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="./plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="./dist/js/demo.js"></script>
</body>
</html>
