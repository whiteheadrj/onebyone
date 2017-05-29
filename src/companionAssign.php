<?php
error_reporting(-1);
ini_set('display_errors', 1);
include 'models/MemberManager.php';
include 'models/TeacherManager.php';
include 'models/CompanionshipManager.php';
include 'models/DistanceManager.php';
$mngr = new MemberManager();
$tmngr = new TeacherManager();
$cmngr = new CompanionshipManager();
$dmngr = new DistanceManager();

$males = $mngr->getMales();
$sort = $mngr->getNameSortOrder();
$teachers = $tmngr->getTeachers();

$assignee = $mngr->getMemberByID($_REQUEST['id']);
$distances = $dmngr->getSortedDistancesForID($_REQUEST['id']);
$closest10 = array();

function printPotentialAssignee($id, $distance)
{
    global $i,$lastDistance,$closest10,$mngr,$tmngr,$cmngr,$dmngr;

    $mem = $mngr->getMemberByID($id);
    if (is_numeric($distance) && $distance != $lastDistance && !empty($mem->Lat) && $i < 10) {
        ++$i;
        $closest10[] = array(
                        'Count' => $i,
                        'Lat' => $mem->Lat,
                        'Long' => $mem->Long,
                    );
        $lastDistance = $distance;
    }

    $companions = $cmngr->getCompanionsByIndividualID($id);
    $companionNames = '';
    if (!empty($companions)) {
        $companionNames = $mngr->getMemberNamesByIDArray($companions);
    }
    echo '<tr>';
    echo '<td>'.$i.'</td>';
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
    echo '<td>'.$mngr->getMemberByID($id)->Name.'</td>';
    echo '<td>';
    echo $mem->Address1;
    if (!empty($mem->Address2)) {
        echo '<br>'.$mem->Address2;
    }
    echo '</td>';
    echo '<td>'.$distance.'</td>';
    echo '<td>'.$companionNames.'</td>';
    echo '<td><a class="btn btn-default" href="companionshipSave.php?action=addComp&amp;assignee='.$_REQUEST['id'].'&amp;comp='.$id.'">Assign</a></td>';
    echo '</tr>';
}
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

    <title>Assign Companion</title>

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


     <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"
   integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="
   crossorigin=""/>
   <style>
        #mapid { height: 400px; }

        .leaflet-div-icon {
            background: transparent;
            border: none;
        }

        .leaflet-marker-icon .number{
            position: relative;
            top: -37px;
            font-size: 12px;
            width: 25px;
            text-align: center;
        }
    </style>
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
            <li><a href="/teachers.php">Select Teachers</a></li>
            <li class="active"><a href="/companionships.php">Assign Companionship</a></li>
            <li><a href="/individuals.php">Assign Individuals</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
        <div class="panel panel-default">
          <div class="panel-heading"><span class='boldText'>Assignee:</span> <?php echo $assignee->Name; ?></div>
          <div class="panel-body">
            <?php
            if (!empty($assignee->Photo1) || !empty($assignee->Photo2)) {
                if (!empty($assignee->Photo1) && !empty($assignee->Photo2)) {
                    echo '<div class="col-md-3"><a href="#" class="pop"><img class="listImage" src="'.$assignee->Photo1.'" /></a></div>';
                    echo '<div class="col-md-3"><a href="#" class="pop"><img class="listImage" src="'.$assignee->Photo2.'" /></a></div>';
                } elseif (!empty($assignee->Photo1)) {
                    echo '<div class="col-md-6"><a href="#" class="pop"><img class="listImage" src="'.$assignee->Photo1.'" /></a></div>';
                } else {
                    echo '<div class="col-md-6"><a href="#" class="pop"><img class="listImage" src="'.$assignee->Photo2.'" /></a></div>';
                }
            }
            echo '<div class="col-md-6">';
                echo '<span class="boldText">Address:</span>';
                echo '<br>'.$assignee->Address1;
            if (!empty($assignee->Address2)) {
                echo '<br>'.$assignee->Address2;
            }
                echo '<br>'.$assignee->City.', '.$assignee->State.' '.$assignee->Zip;
            echo '</div>';
            ?>
          </div>
        </div>
        <div id="mapid"></div>

        <?php
            echo '<div class="table-responsive">';
            echo '<table class="table">';
            echo '<thead class="thead-default">';
            echo '<tr>';
            echo '<th>Count</th>';
            echo '<th colspan="2">&nbsp;</th>';
            echo '<th>Name</th>';
            echo '<th>Address</th>';
            echo '<th>Distance</th>';
            echo '<th>Companions</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $i = 0;
            $lastDistance = '';
        foreach ($distances as $id => $distance) {
            if (in_array($id, $teachers)) {
                printPotentialAssignee($id, $distance);
            }
        }
        foreach ($teachers as $id) {
            if (!array_key_exists($id, $distances) && $id != $_REQUEST['id']) {
                printPotentialAssignee($id, 'NA');
            }
        }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
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
     <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
   integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
   crossorigin=""></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Leaflet.MakiMarkers.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script>
        $('.pop').on('click', function() {
            $('.imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#imagemodal').modal('show');
});

        L.NumberedDivIcon = L.Icon.extend({
            options: {
            iconUrl: 'marker_hole.png',
            number: '',
            shadowUrl: null,
            iconSize: new L.Point(25, 41),
                iconAnchor: new L.Point(13, 41),
                popupAnchor: new L.Point(0, -33),
                /*
                iconAnchor: (Point)
                popupAnchor: (Point)
                */
                className: 'leaflet-div-icon'
            },

            createIcon: function () {
                var div = document.createElement('div');
                var img = this._createImg(this.options['iconUrl']);
                var numdiv = document.createElement('div');
                numdiv.setAttribute ( "class", "number" );
                numdiv.innerHTML = this.options['number'] || '';
                div.appendChild ( img );
                div.appendChild ( numdiv );
                this._setIconStyles(div, 'icon');
                return div;
            },

            //you could change this to add a shadow like in the normal marker if you really wanted
            createShadow: function () {
                return null;
            }
        });
    </script>

<?php
$center = '[40.77331, -73.98168]';
$individual = '[]';
if (!empty($assignee->Lat)) {
    $individual = '['.$assignee->Lat.', '.$assignee->Long.']';
    $center = $individual;
}

?>
    <script>
    L.MakiMarkers.accessToken = "pk.eyJ1Ijoid2hpdGVoZWFkcmoyIiwiYSI6ImNqMnl1ZTE2ZDAwM3IycXByZXNya3N1OXgifQ.nMcG7PmGB-S1p3qNzerR0A";
    var icon = L.MakiMarkers.icon({color: "#b0b", size: "m"});
    var mymap = L.map('mapid', {
        center: <?php echo $center?>,
        zoom: 15
    });
    L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/{id}/tiles/256/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
        maxZoom: 18,
        id: 'light-v9',
        accessToken: 'pk.eyJ1Ijoid2hpdGVoZWFkcmoyIiwiYSI6ImNqMnl1ZTE2ZDAwM3IycXByZXNya3N1OXgifQ.nMcG7PmGB-S1p3qNzerR0A'
    }).addTo(mymap);

    var closest10 = JSON.parse('<?php echo json_encode($closest10); ?>');
    var individualLatLong = JSON.parse("<?php echo $individual; ?>");
    if(individualLatLong.length>0){

        var marker = L.marker(L.latLng(individualLatLong[0], individualLatLong[1]), {icon: icon}).addTo(mymap);
    }
    closest10.map(function(data){
        var marker = new L.Marker(new L.LatLng(data.Lat, data.Long), {
            icon:   new L.NumberedDivIcon({number: data.Count})
        }).addTo(mymap);
    });
    </script>

  </body>
</html>
