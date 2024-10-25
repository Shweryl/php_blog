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
  if (empty($_POST['title']) || empty($_POST['content'])) {
    if (empty($_POST['title'])) {
      $titleErr = "Please fill title.";
    }
    if (empty($_POST['content'])) {
      $contentErr = "Please fill content";
    }
  } else {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if ($_FILES['image']['name'] != null) {
      $file = 'images/' . ($_FILES['image']['name']);
      $imageType = pathinfo($file, PATHINFO_EXTENSION);

      if ($imageType != 'png' && $imageType != 'jpeg' && $imageType != 'jpg') {
        echo "<script>alert('Image must be png/jpg/jpeg')</script>";
      } else {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $file);

        $stmt = $pdo->prepare("UPDATE posts SET title=:title, content=:content, image= :image WHERE id='$id'");
        $result = $stmt->execute(
          array(
            ':title' => $title,
            ':content' => $content,
            ':image' => $image
          )
        );
        if ($result) {
          echo "<script>alert('Successfully added');window.location.href='index.php';</script>";
        }
      }
    } else {
      $stmt = $pdo->prepare("UPDATE posts SET title=:title, content=:content WHERE id='$id'");
      $result = $stmt->execute(
        array(
          ':title' => $title,
          ':content' => $content
        )
      );
      if ($result) {
        echo "<script>alert('Successfully added');window.location.href='index.php';</script>";
      }
    }
  }
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=" . $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();
?>



<?php include("header.php"); ?>
<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
              <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
              <div class="form-group">
                <label for="">Title</label>
                <input type="text" class="form-control <?php echo $titleErr ? 'is-invalid' : ''; ?>" name="title" value="<?php echo escape($result[0]['title']); ?>">
                <div class="invalid-feedback">
                  <?php echo $titleErr; ?>
                </div>
              </div>
              <div class="form-group">
                <label for="">Content</label>
                <textarea type="text" rows="8" name="content" class="form-control <?php echo $titleErr ? 'is-invalid' : ''; ?>"><?php echo escape($result[0]['content']); ?></textarea>
                <div class="invalid-feedback">
                  <?php echo $contentErr; ?>
                </div>
              </div>
              <div class="form-group">
                <label for="">Image</label><br>
                <img src="images/<?php echo escape($result[0]['image']) ?>" width="150" height="150" alt=""><br>
                <input type="file" class="mt-2" name="image">
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-info">Update</button>
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
