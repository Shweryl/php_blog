<?php
session_start();
require('config/config.php');
require('config/common.php');

if ($_POST) {
  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])) {
    if (empty($_POST['name'])) {
      $nameErr = "Name is required.";
    }
    if (empty($_POST['email'])) {
      $emailErr = "Email is required.";
    }
    if (empty($_POST['password'])) {
      $passwordErr = "Password is required.";
    }
  } elseif (strlen($_POST['password']) < 4) {
    $passwordErr = "Password should be at least four characters.";
  } else {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email='$email'");
    $stmt->execute();
    $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($user) {
      echo "<script>alert('Email is already used.Create with another email.')</script>";
    } else {
      $stmt = $pdo->prepare("INSERT INTO users(name, email, password, role) VALUES(:name, :email, :password, :role)");
      $result = $stmt->execute(
        array(
          ":name" => $name,
          ":email" => $email,
          ":password" => $password,
          ":role" => 0
        )
      );

      if ($result) {
        echo "<script>alert('Now login in to enter account');window.location.href='login.php';</script>";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog | Register</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href=""><b>Blog</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Register A New Account</p>

        <form action="register.php" method="POST">
          <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
          <div class="input-group mb-3">
            <input type="text" name="name" class="form-control <?php echo $nameErr ? 'is-invalid' : ''; ?>" placeholder="Name">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
            <div class="invalid-feedback">
              <?php echo $nameErr; ?>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control <?php echo $emailErr ? 'is-invalid' : ''; ?>" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
            <div class="invalid-feedback">
              <?php echo $emailErr; ?>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control <?php echo $passwordErr ? 'is-invalid' : ''; ?>" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
            <div class="invalid-feedback">
              <?php echo $passwordErr; ?>
            </div>
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="container">
              <button type="submit" class="btn btn-primary btn-block">Register</button>
              <a type="button" href="login.php" class="btn btn-default btn-block">Login</a>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../t/js/adminlte.min.js"></script>
</body>

</html>
