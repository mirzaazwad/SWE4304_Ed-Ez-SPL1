<?php
$root_path = '../../../';
$profile_path = '../';
require $root_path . 'LibraryFiles/DatabaseConnection/config.php';
require $root_path . 'LibraryFiles/URLFinder/URLPath.php';
require $root_path . 'LibraryFiles/SessionStore/session.php';
require $root_path . 'LibraryFiles/ValidationPhp/InputValidation.php';
require $root_path . 'LibraryFiles/Utility/Utility.php';
foreach (glob($root_path . 'LibraryFiles/ClassroomManager/*.php') as $filename) {
  require $filename;
}
session::profile_not_set($root_path);
session::profile_not_set($root_path);
$validate = new InputValidation();
$email = new EmailValidator($_SESSION['email']);
$resource_id = null;
$resource_id = $_SESSION['resource_id'];
$resources = $database->performQuery("SELECT * FROM resources");
foreach ($resources as $resource) {
  if (isset($_POST[$resource['resource_id']])) {
    $resource_id = $resource['resource_id'];
  }
}
$database->performQuery("UPDATE resource_frequency SET frequency=frequency+1 WHERE resource_id = '$resource_id' AND email='" . $email->get_email() . "'");
$database->fetch_results($resource, "SELECT * FROM resources,resource_uploaded WHERE resources.resource_id = '$resource_id' AND resource_uploaded.resource_id = '$resource_id'");
$database->fetch_results($user, "SELECT * FROM users WHERE email='" . $resource['email'] . "'");

$upvote = $database->performQuery("SELECT * FROM resource_upvote WHERE resource_id='$resource_id' AND  email='" . $email->get_email() . "'");
$downvote = $database->performQuery("SELECT * FROM resource_downvote WHERE resource_id='$resource_id' AND  email='" . $email->get_email() . "'");
if (isset($_POST['upvote']) && $resource['email'] !== $email->get_email()) {
  if ($upvote->num_rows == 0) {
    $database->performQuery("INSERT INTO resource_upvote VALUES('$resource_id','" . $email->get_email() . "')");
    if($downvote->num_rows >0){
      $database->performQuery("DELETE FROM resource_downvote WHERE resource_id='$resource_id' AND email='" . $email->get_email() . "'");
    }
  } else {
    $database->performQuery("DELETE FROM resource_upvote WHERE resource_id='$resource_id' AND email='" . $email->get_email() . "'");
  }
}

if (isset($_POST['downvote']) && $resource['email'] !== $email->get_email()) {
  if ($downvote->num_rows == 0) {
    $database->performQuery("INSERT INTO resource_downvote VALUES('$resource_id','" . $email->get_email() . "')");
    if($upvote->num_rows >0){
      $database->performQuery("DELETE FROM resource_upvote WHERE resource_id='$resource_id' AND email='" . $email->get_email() . "'");
    }
  } else {
    $database->performQuery("DELETE FROM resource_downvote WHERE resource_id='$resource_id' AND email='" . $email->get_email() . "'");
  }
}

$saved=$database->performQuery("SELECT * FROM resource_saved WHERE resource_id='$resource_id' AND  email='".$email->get_email()."'");
if(isset($_POST['save'])){
  if($saved->num_rows==0){
    try {
      $database->performQuery("INSERT INTO resource_saved VALUES('$resource_id','" . $email->get_email() . "')");
      $database->performQuery("INSERT INTO resource_frequency VALUES('$resource_id','" . $email->get_email() . "',0)");
    }
    catch(Exception $e){
      echo $e->getMessage();
    }
  }
  else{
    $database->performQuery("DELETE FROM resource_saved WHERE resource_id='$resource_id' AND email='".$email->get_email()."'");
    $database->performQuery("DELETE FROM resource_frequency WHERE resource_id='$resource_id' AND email='".$email->get_email()."'");
  }
}

if(isset($_POST['delete'])){
  $database->performQuery("DELETE FROM resources WHERE resource_id='$resource_id'");
  header("Location: ../index.php");
}

$upvote = $database->performQuery("SELECT * FROM resource_upvote WHERE resource_id='$resource_id' AND  email='" . $email->get_email() . "'");
$downvote = $database->performQuery("SELECT * FROM resource_downvote WHERE resource_id='$resource_id' AND  email='" . $email->get_email() . "'");
$saved=$database->performQuery("SELECT * FROM resource_saved WHERE resource_id='$resource_id' AND  email='".$email->get_email()."'");
if($saved->num_rows > 0){
  $saved_color = 'black';
}
else{
  $saved_color = 'white';
}

if($upvote->num_rows > 0){
  $upvote_color = 'black';
}
else{
  $upvote_color = 'white';
}

if($downvote->num_rows > 0){
  $downvote_color = 'black';
}
else{
  $downvote_color = 'white';
}
$_SESSION['resource_id'] = $resource_id;


$database->fetch_results($resource_upvote, "SELECT count(*) AS upvote FROM resource_upvote WHERE resource_id = '$resource_id'");
$database->fetch_results($resource_downvote, "SELECT count(*) AS downvote FROM resource_downvote WHERE resource_id = '$resource_id'");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Resources</title>
  <link rel="icon" href="<?php echo $root_path; ?>title_icon.jpg" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="<?php echo $root_path; ?>css/bootstrap.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link href="<?php echo $root_path; ?>boxicons-2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <script defer src="script.js"></script>
</head>

<body>
  <script src="<?php echo $root_path; ?>js/bootstrap.js"></script>
  <div class="main-container d-flex">
    <?php
    $profile_type = $_SESSION['tableName'] === 'student' ? '../../StudentProfile/' : '../../TeacherProfile/';
    require $profile_type . 'navbar.php';
    $_SESSION['tableName'] === 'student' ? student_navbar($root_path, false) : teacher_navbar($root_path, false);
    ?>
    <section class="content-section m-auto px-5 py-3">
      <?php
      if (!is_null($resource_id)) {
      ?>
        <div class="card intro-card w-75 text-bg-secondary m-auto mb-3">
          <div class="card-header d-flex justify-content-between">
            <h5 class="card-title" style="text-align:left">Shared By <?php echo $user['name'] ?> <?php echo $resource['post_date_time'] ?></h5>
            <?php
                    $userResource=$database->performQuery("SELECT * FROM resource_uploaded WHERE resource_uploaded.resource_id='$resource_id' AND resource_uploaded.email='" . $email->get_email() . "'");
                    if($userResource->num_rows>0){
                      ?>
            <button type="button" class="btn btn-primary btn-delete" data-bs-toggle="modal" data-bs-target="#exampleModal">Delete Resource</button>
            <?php
                    }
                    ?>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Resource</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="" method="POST">
                  <div class="modal-body">
                  Are you sure you want to delete this resource?
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    
                    <button type="submit" class="btn btn-primary" name="delete">Delete</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body text-success">
            <object data="<?php echo FileManagement::get_file_url_static($database, URLPath::getFTPServer(), $resource['file_id']) ?>" type="application/pdf" width="100%" height="500px">
              <p>It appears your Web browser is not configured to display PDF files. No worries, just <a href="<?php echo FileManagement::get_file_url_static($database, URLPath::getFTPServer(), $resource['file_id']) ?>"></p>
            </object>
          </div>
          <form name="Review" action="" method="POST">
            <div class="card-footer d-flex justify-content-between border-success">
              <div>
                <button type="submit" id="upvote" name="upvote" class="bx btn bx-sm  bxs-up-arrow me-2" style="color:<?php echo $upvote_color; ?>"></button>
                <label for="upvote"><?php echo is_null($resource_upvote['upvote']) ? 0 : $resource_upvote['upvote'] ?></label>
                <button type="submit" id="downvote" name="downvote" class='bx btn bx-sm  bxs-down-arrow' style="color:<?php echo $downvote_color; ?>"></button>
                <label for="downvote"><?php echo is_null($resource_downvote['downvote']) ? 0 : $resource_downvote['downvote'] ?></label>
              </div>
              <div>
                <button type="submit" id="save" name="save" class='bx btn bx-sm bxs-bookmark-plus' style="color:<?php echo $saved_color; ?>"></button>
              </div>
            </div>
          </form>
        <?php
      } else {
        ?>
          <h5>There are no files to load</h5>
        <?php
      }
        ?>

    </section>
  </div>

</body>

</html>