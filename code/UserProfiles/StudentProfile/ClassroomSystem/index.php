<?php
$root_path = '../../../';
include $root_path . 'LibraryFiles/DatabaseConnection/config.php';
include $root_path . 'LibraryFiles/URLFinder/URLPath.php';
include $root_path . 'LibraryFiles/SessionStore/session.php';
session::create_or_resume_session();
session::profile_not_set($root_path);
$temp = hash('sha512', $_SESSION['email']);
$tableName = $_SESSION['tableName'];
$row = mysqli_fetch_assoc($database->performQuery("SELECT * FROM users WHERE email='$temp';"));
$error="";
$name = $row['name'];
if (isset($_POST['Join'])) {
  $classCode=$_REQUEST['classCode'];
  $existenceCheck=$database->performQuery("SELECT * FROM student_classroom WHERE class_code='$classCode';");
  if($existenceCheck->num_rows==0){
    $database->performQuery("INSERT INTO student_classroom(email,class_code) VALUES('$temp','$classCode');");
  }
  else{
    $error="You are already enrolled in this classroom";
  }
}


$classrooms = $database->performQuery("SELECT * FROM classroom,student_classroom where classroom.class_code=student_classroom.class_code and student_classroom.email='$temp';");
foreach($classrooms as $dummy_classroom){
  if(isset($_POST[$dummy_classroom['class_code']])){
    $_SESSION['class_code']=$dummy_classroom['class_code'];
    header('Location: StudentClassroom/index.php');
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Classroom</title>
  <link rel="icon" href="<?php echo $root_path;?>logo4.jpg" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="<?php echo $root_path;?>css/bootstrap.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jsSHA/2.0.2/sha.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://kit.fontawesome.com/d0f239b9af.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jsSHA/2.0.2/sha.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://kit.fontawesome.com/d0f239b9af.js" crossorigin="anonymous"></script>
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
  <script defer src="script.js"></script>
  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</head>

<body>

  <script src="<?php echo $root_path;?>js/bootstrap.js"></script>
  <div class="main-container d-flex">
    <div class="sidebar" id="side_nav">
      <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between ">
        <h1 class="fs-4"><span class="bg-white text-dark rounded shadow px-4 mx-4 my-5"><span class="text-whte"> Ed-Ez</span></span></h1>
        <button class="btn d-block close-btn text-white"><i class='bx bx-menu'></i></button>
      </div>
      <ul class="list-unstyled px-2">
        <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block"><i class='bx bxs-dashboard pe-2'></i>Dashboard</a></li>
        <li class=""><a href="<?php echo $root_path;?>UserProfiles/StudentProfile/index.php" class="text-decoration-none px-3 py-2 d-block"><i class='bx bx-user-circle pe-2'></i>Profile</a></li>
        <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block"><i class='bx bxs-calendar-plus pe-2'></i>Schedule</a></li>
        <li class=""><a href="<?php echo $root_path;?>UserProfiles/StudentProfile/ClassroomSystem/index.php" class="text-decoration-none px-3 py-2 d-block"><i class='bx bx-chalkboard pe-2'></i>Classrooms</a></li>
        <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block"><i class='bx bxs-bar-chart-alt-2 pe-2'></i>Grades</a></li>
      </ul>
      <hr class="h-color mx-2 my-5">
      <hr class="h-color mx-2 my-5">
      <hr class="h-color mx-2 my-5">
      <hr class="h-color mx-2 my-5">
      <hr class="h-color mx-2 my-5">
      <hr class="h-color mx-2 my-5">
      <hr class="h-color mx-2 my-5">
      <ul class="list-unstyled px-2 ">
        <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block "><i class='bx bx-wrench pe-2'></i>Settings</a></li>
      </ul>
    </div>
    <div class="content">
      <nav class="navbar navbar-expand p-3" style="background-color: #4596be;">
        <div class="container-fluid mx-5 px-4">
          <div class="d-flex justify-content-between d-block">
            <button class="btn btn-primary open-btn me-2"><i class='bx bx-menu'></i></i></button>
            <a href="#" class="navbar-brand fs-5 px-3 mx-4" href="#"><span class="bg-dark rounded px-2 py-0 text-white">Ed-Ez</span></a>
          </div>
          <!-- <button class="navbar-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fal fa-bars"></i>
          </button> -->
          <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav mb-2 mb-lg-0">
              <li class="nav-item">
                <button type="button" class="btn btn-primary me-2 d-flex">
                <a href="<?php echo $root_path;?>UserProfiles/Logout/logout.php" style="text-decoration: none; color:black">Log Out</a>
                </button>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <section class="content-section m-auto px-1 w-75">
        <div class="container bg-white rounded mt-5 mb-5"></div>
        <div class="px-3 me-3 d-flex flex-row-reverse">
          <button type="button" class="btn btn-outline-primary btn-join d-flex p-4 py-3" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@fat"><b>Join new classroom</b></button>
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Join classroom</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="" method="POST">
                    <div class="mb-3">
                      <input type="text" name="classCode" class="form-control" placeholder="Enter classroom code" aria-label="Leave a comment">
                    </div>
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <input type="submit" name="Join" value="Join" class="btn btn-primary btn-join">
                </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div id="error" style="color:red">
          <?php echo $error;?>
        </div>
        <div class="container bg-white rounded m-auto justify-content-center mt-5 mb-5"></div>
        <!-- <h2 class="fs-5">Profile</h2> -->
        <div class="row justify-content-start m-auto">
        <?php
          foreach ($classrooms as $i) {
            $classCode=$i['class_code'];
            $instructor_name=mysqli_fetch_assoc($database->performQuery("select name from users where email in (select teacher.email from teacher,teacher_classroom where teacher.email=teacher_classroom.email and class_code='$classCode');"));
          ?>
            <div class="card-element col-lg-4 col-md-6 p-4 px-2">
              <div class="card card-box-shadow">
                <div class="card-body">
                  <h4 class="card-title"><?php echo $i['classroom_name']; ?></h4>
                  <p class="card-text"><?php echo 'Course Instructor: '.$instructor_name['name']; ?></p>
                </div>
                <form action="" method="POST">
                <div class="pb-5 px-5"><input type="submit" name="<?php echo $i['class_code'] ?>" value="Enter Class" class="btn btn-primary btn-go"/></div>
                </form>
              </div>
            </div>
          <?php
          }
          ?>
    </div>
    </section>
  </div>
  </div>


</body>

</html>