<?php
include 'models/MemberManager.php';
include 'models/TeacherManager.php';
$mngr = new MemberManager();
$tmngr = new TeacherManager();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Teachers</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/starter-template.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">HT</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>
            <li><a href="/notTeaching.php">Not Teaching</a></li>
            <li class="active"><a href="/teachers.php">Select Teachers</a></li>
            <li><a href="/companionships.php">Assign Companionship</a></li>
            <li><a href="/individuals.php">Assign Individuals</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
        <h2>Select Teachers</h2>
<?php

$males = $mngr->getMales();
$sort = $mngr->getNameSortOrder();
$teachers = $tmngr->getTeachers();
if (!empty($males)) {
    echo '<form method="post" action="teachersSave.php">';
    echo '<div class="table-responsive">';
    echo '<table class="table">';
    echo '<thead class="thead-default">';
    echo '<tr>';
    echo '<th colspan="2">&nbsp;</th>';
    echo '<th>Name</th>';
    echo '<th>Include as Teacher</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($sort as $id) {
        if (in_array($id, $males)) {
            $mem = $mngr->getMemberByID($id);
            echo '<tr>';
            if (!empty($mem->Photo1) || !empty($mem->Photo2)) {
                if (!empty($mem->Photo1) && !empty($mem->Photo2)) {
                    echo '<td><a href="#" class="pop"><img class="listImage" src="'.$mem->Photo1.'" /></a></td>';
                    echo '<td><a href="#" class="pop"><img class="listImage" src="'.$mem->Photo2.'" /></a></td>';
                } elseif (!empty($mem->Photo1)) {
                    echo '<td><a href="#" class="pop"><img class="listImage" src="'.$mem->Photo1.'" /></a></td>';
                    echo '<td>&nbsp;</td>';
                } else {
                    echo '<td><a href="#" class="pop"><img class="listImage" src="'.$mem->Photo2.'" /></a></td>';
                    echo '<td>&nbsp;</td>';
                }
            } else {
                echo '<td>&nbsp;</td>';
                echo '<td>&nbsp;</td>';
            }
            echo '<th scope="row">'.$mngr->getMemberByID($id)->Name.'</th>';
            $checked = '';
            if (in_array($id, $teachers)) {
                $checked = ' checked="checked"';
            }
            echo '<td><input'.$checked.' class="form-check-input" type="checkbox" name="t[]" value="'.$id.'" /></td>';
            echo '</tr>';
        }
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    echo '<div class="btn-group">';
    echo '<button type="submit" class="btn">Submit</button>';
    echo '</button>';
    echo '</form>';
} else {
    echo '<p>No eligible teachers found</p>';
}

?>
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">              
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <img src="" class="imagepreview" style="width: 100%;" >
      </div>
    </div>
  </div>
</div>
    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script>
        $('.pop').on('click', function() {
            $('.imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#imagemodal').modal('show');
});

    </script>
  </body>
</html>
