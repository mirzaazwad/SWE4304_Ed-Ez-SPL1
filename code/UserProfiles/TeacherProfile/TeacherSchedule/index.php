<?php
$root_path = '../../../';
$profile_path='../';
require $root_path . 'LibraryFiles/DatabaseConnection/config.php';
require $root_path . 'LibraryFiles/URLFinder/URLPath.php';
require $root_path . 'LibraryFiles/SessionStore/session.php';
session::profile_not_set($root_path);
$temp = hash('sha512', $_SESSION['email']);



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Schedule</title>
  <link rel="icon" href="<?php echo $root_path; ?>title_icon.jpg" />
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="main.min.css" />
  <link rel="stylesheet" href="<?php echo $root_path; ?>css/bootstrap.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link href="<?php echo $root_path;?>boxicons-2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <script src="script.js"></script>

</head>

<body>

  <script src="<?php echo $root_path; ?>js/bootstrap.js"></script>
  <script src="main.min.js"></script>
  <div class="main-container d-flex">
      <?php 
        require $profile_path.'navbar.php';
        teacher_navbar($root_path);
      ?>
      <div class="container">
      <div id='calendar'></div>
      </div>
    </div>
</body>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      
      navLinks: true, // can click day/week names to navigate views
      selectable: true,
      selectMirror: true,
      
      select: function(arg) {
        var title = prompt('Event Title:');
        if (title) {
          calendar.addEvent({
            title: title,
            start: arg.start,
            end: arg.end,
            allDay: arg.allDay
          })
        }
        calendar.unselect()
      },
      editable: true,
      dayMaxEvents: true,
      events: [
        <?php 
            $recordsTask=$database->performQuery("select * from event,task,teacher_classroom,task_classroom where event.event_id=task.event_id and task_classroom.task_id=task.task_id and teacher_classroom.email='$temp' and teacher_classroom.class_code=task_classroom.class_code;");
            $first=false;
            foreach($recordsTask as $i){
              if(!$first){
                $first=true;
              }
              else{
                echo ',';
              }
          ?>
        {
          
          title: '<?php echo $i['task_title']; ?>',
          start: '<?php echo $i['event_start_datetime'];?>'
          
        }
        <?php
            }
          ?>
      ]
    });

    calendar.render();
  });
  </script>
</html>