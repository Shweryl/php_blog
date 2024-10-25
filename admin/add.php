<?php
session_start();
require("../config/config.php");
require("../config/common.php");
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_SESSION['role'] != 1) {
  header("Location:../index.php");
}
if ($_POST) {
  if (empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image'])) {
    if(empty($_POST['title'])){
      $titleErr = "Please fill title.";
    }
    if(empty($_POST['content'])){
      $contentErr = "Please fill content";
    }
    if(empty($_FILES['image'])){
      $imageErr = "At least one pic is required to upload";
    }
  }else {
    $file = 'images/' . ($_FILES['image']['name']);
    $imageType = pathinfo($file, PATHINFO_EXTENSION);

    if ($imageType != 'png' && $imageType != 'jpeg' && $imageType != 'jpg') {
      echo "<script>alert('Image must be png/jpg/jpeg')</script>";
    } else {
      $title = $_POST['title'];
      $content = $_POST['content'];
      $image = $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'], $file);

      $stmt = $pdo->prepare("INSERT INTO posts(title,content,author_id,image) VALUES(:title, :content, :author_id, :image)");
      $result = $stmt->execute(
        array(
          ':title' => $title,
          ':content' => $content,
          ':author_id' => $_SESSION['user_id'],
          ':image' => $image
        )
      );
      if ($result) {
        echo "<script>alert('Successfully added');window.location.href='index.php';</script>";
      }
    }
  }
}
?>

<?php include("header.php"); ?>
<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form action="add.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
              <div class="form-group">
                <label for="">Title</label>
                <input type="text" class="form-control <?php echo $titleErr ? 'is-invalid' : ''; ?>" name="title">
                <div class="invalid-feedback">
                  <?php echo $titleErr; ?>
                </div>
              </div>
              <div class="form-group">
                <label for="">Content</label>
                <textarea type="text" rows="8" class="form-control <?php echo $contentErr ? 'is-invalid' : ''; ?>" name="content"></textarea>
                <div class="invalid-feedback">
                  <?php echo $contentErr; ?>
                </div>
              </div>
              <div class="form-group">
                <label for="">Image</label><br>
                <input type="file" class="<?php echo $imageErr ? 'is-invalid' : ''; ?>" name="image">
                <div class="invalid-feedback">
                  <?php echo $imageErr; ?>
                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-info">Submit</button>
                <a href="index.php" type="button" class="btn btn-warning">Back</a>
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
<?php include("footer.html"); ?>
