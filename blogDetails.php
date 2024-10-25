<?php
session_start();
require('config/config.php');
require('config/common.php');
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header("Location:login.php");
}
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=" . $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

if ($_POST) {
  if (empty($_POST['comment'])) {
    $commentErr = "Please fill comment.";
  } else {
    $comment = $_POST['comment'];
    $postId = $_GET['id'];
    $stmt = $pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES(:content, :author_id, :post_id)");
    $result = $stmt->execute(
      array(
        ':content' => $comment,
        ':author_id' => $_SESSION['user_id'],
        ':post_id' => $postId
      )
    );
    if ($result) {
      echo "<script>window.location.href='blogDetails.php?id={$postId}';</script>";
    }
  }
}

$stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id =" . $_GET['id']);
$stmt->execute();
$results = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog Details</title>

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
    <div class="">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row d-flex justify-content-center">
            <div class="col-md-6">
              <!-- Box Comment -->
              <div class="card card-widget">
                <div class="card-header">
                  <div class="card-title float-none text-center">
                    <?php echo escape($result[0]['title']) ?>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <img class="img-fluid pad" src="admin/images/<?php echo $result[0]['image']; ?>" alt="Photo">

                  <p class="mt-2"><?php echo escape($result[0]['content']); ?></p>
                </div>
                <!-- /.card-body -->
                <div class="card-footer card-comments">
                  <h4>Comments</h3>
                    <hr>
                    <?php
                    if ($results) {
                      foreach ($results as $result) {
                        $author_id = $result['author_id'];
                        $stmt = $pdo->prepare("SELECT * FROM users WHERE id='$author_id'");
                        $stmt->execute();
                        $data = $stmt->fetchAll();

                    ?>
                        <div class="card-comment">
                          <div class="comment-text ml-0">
                            <span class="username">
                              <?php echo escape($data[0]['name']) ?>
                              <span class="text-muted float-right"><?php echo escape($result['created_at']); ?></span>
                            </span><!-- /.username -->
                            <?php echo escape($result['content']); ?>
                          </div>
                          <!-- /.comment-text -->
                        </div>
                        <!-- /.card-comment -->
                    <?php
                      }
                    }
                    ?>

                </div>
                <!-- /.card-footer -->
                <div class="card-footer">
                  <form action="" method="post">
                    <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                    <div class="img-push">
                      <input type="text" name="comment" class="form-control form-control-sm <?php echo $commentErr ? 'is-invalid' : ''; ?>" placeholder="Press enter to post comment">
                      <div class="invalid-feedback">
                        <?php echo $commentErr; ?>
                      </div>
                    </div>

                  </form>
                </div>
                <!-- /.card-footer -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>

      <a id="back-to-top" href="#" class="btn btn-primary back-to-top" style="bottom: 5.25rem !important;" role="button" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
      </a>
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer ml-0">
      <div class="float-right d-none d-sm-block">
        <a href="index.php" type="button" class="btn btn-info">Back</a>
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
