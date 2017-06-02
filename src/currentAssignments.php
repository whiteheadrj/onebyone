<?php
error_reporting(-1);
ini_set('display_errors', 1);
include 'models/MemberManager.php';
include 'models/TeacherManager.php';
include 'models/CompanionshipManager.php';
include 'models/DistanceManager.php';
include 'models/AssignmentsManager.php';
$mngr = new MemberManager();
$tmngr = new TeacherManager();
$cmngr = new CompanionshipManager();
$dmngr = new DistanceManager();
$aMngr = new AssignmentsManager();

$sort = $mngr->getNameSortOrder();

$companionships = $cmngr->getCompanionships();

$cmapData = array();
$conversion = array(
    0 => 'A',
    1 => 'B',
    2 => 'C',
    3 => 'D',
    4 => 'E',
);
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

    <title>Current Assignments</title>

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
        #mapid {
            height: 300px; 
        }
        .cmap{
            height: 400px; 
        }

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
            <li><a href="./index.php">Home</a></li>
            <li><a href="./notTeaching.php">Not Teaching</a></li>
            <li><a href="./teachers.php">Select Teachers</a></li>
            <li><a href="./companionships.php">Assign Companionship</a></li>
            <li><a href="./individuals.php">Assign Individuals</a></li>
            <li class="active"><a href="./individuals.php">Current Assignments</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
        <h2>Current Assignments</h2>
<?php

if (!empty($companionships)) {
    $i = 1;
    foreach ($companionships as $companionshipKey => $companions) {
        $companions = $companionships[$companionshipKey];
        $companionCount = count($companions);
        $teachees = $aMngr->getIndividualsAssignedToComp($companionshipKey);
        $sortedTeachees = $dmngr->getSortedAvgDistanceFromComp($companions, $teachees);

        $compMapData = array();
        $compMapData['teachees'] = array();
        $compMapData['key'] = $companionshipKey;
        echo '<div class="panel panel-default companionship">';
        echo '<div class="panel-heading">';
        echo '<h3 class="panel-title">#'.$i.' -- '.count($sortedTeachees).' ASSIGNED</h3>';
        echo '</div>'; //end panel-heading
        echo '<div class="panel-body">';
        echo '<div class="row">';

        foreach ($companions as $k => $id) {
            $compLetter = $conversion[$k];
            $mem = $mngr->getMemberByID($id);
            $compMapData['companions'][] = array(
                        'Index' => $compLetter,
                        'Lat' => $mem->Lat,
                        'Long' => $mem->Long,
                    );
            $compMapData['viewport'][] = array(
                        'Lat' => $mem->Lat,
                        'Long' => $mem->Long,
                    );
            if ($companionCount == 3) {
                echo '<div class="col-md-4 col-print-4">';
            } else {
                echo '<div class="col-md-6 col-print-6">';
            }
            echo '<div class="panel panel-default">';
            echo '<div class="panel-heading">';
            echo '<h3 class="panel-title">'.$compLetter.' -- '.$mem->Name.'</h3>';
            echo '</div>';
            echo '<div class="panel-body">';

            echo '<div class="row">';
            echo '<div class="well">';
            echo $mem->Phone.'<br>';
            echo $mem->Email.'<br>';
            echo $mem->Address1;
            if (!empty($mem->Address2)) {
                echo '<br>'.$mem->Address2;
            } else {
                echo '<br>&nbsp;';
            }
            echo '</div>';
            echo '</div>'; //end row
                    echo '<div class="row">';
            if (!empty($mem->Photo1) || !empty($mem->Photo2)) {
                if (!empty($mem->Photo1) && !empty($mem->Photo2)) {
                    echo '<div class="col-md-6 col-print-6"><a href="#" class="pop"><img class="listImage" src="'.$mem->Photo1.'" /></a></div>';
                    echo '<div class="col-md-6 col-print-6"><a href="#" class="pop"><img class="listImage" src="'.$mem->Photo2.'" /></a></div>';
                } elseif (!empty($mem->Photo1)) {
                    echo '<div class="col-md-6 col-print-6"><a href="#" class="pop"><img class="listImage" src="'.$mem->Photo1.'" /></a></div>';
                    echo '<div class="col-md-6 col-print-6">&nbsp;</div>';
                } else {
                    echo '<div class="col-md-6 col-print-6"><a href="#" class="pop"><img class="listImage" src="'.$mem->Photo2.'" /></a></div>';
                    echo '<div class="col-md-6 col-print-6">&nbsp;</div>';
                }
            }
            echo '</div>'; //row

                    echo '</div>'; //panelbody
                    echo '</div>'; //panel
                    echo '</div>'; //column
        }

        echo '</div>'; //row

                echo '<div class="row">';
        echo '<div class="cmap" id="cmap-'.$companionshipKey.'"></div>';
        echo '</div>'; //row

        if (!empty($sortedTeachees)) {
            echo '<div class="row teachees">';

            echo '<div class="table-responsive">';
            echo '<table class="table">';
            echo '<thead class="thead-default">';
            echo '<tr>';
            echo '<th>Location</th>';
            echo '<th colspan="2">&nbsp;</th>';
            echo '<th>Name</th>';
            echo '<th>Address</th>';
            echo '<th>Phone</th>';
            echo '<th>Email</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $locationCount = 0;
            $lastDistance = '';
            foreach ($sortedTeachees as $memID => $distance) {
                $mem = $mngr->getMemberByID($memID);
                if (is_numeric($distance) && $distance != $lastDistance && !empty($mem->Lat)) {
                    $lastDistance = $distance;
                    ++$locationCount;
                    $compMapData['teachees'][] = array(
                        'Index' => $locationCount,
                        'Lat' => $mem->Lat,
                        'Long' => $mem->Long,
                    );
                    $compMapData['viewport'][] = array(
                        'Lat' => $mem->Lat,
                        'Long' => $mem->Long,
                    );
                }

                echo '<tr>';
                echo '<td>'.$locationCount.'</td>';
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
                echo '<td>'.$mem->Name.'</td>';
                echo '<td>';
                echo $mem->Address1;
                if (!empty($mem->Address2)) {
                    echo '<br>'.$mem->Address2;
                }
                echo '</td>';
                echo '<td>'.$mem->Phone.'</td>';
                echo '<td>'.$mem->Email.'</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>'; //table responsive
            echo '</div>'; //row
        }

        echo '</div>'; //panelbody
        echo '</div>'; //panel
        ++$i;
        $cmapData[] = $compMapData;
    }
} else {
    echo '<p>No companionships exist</p>';
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
     <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
   integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
   crossorigin=""></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/Leaflet.MakiMarkers.js"></script>
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script>
        $('.pop').on('click', function() {
            $('.imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#imagemodal').modal('show');
});

    </script>
    <script>

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
        L.NumberedDivIconGreen = L.Icon.extend({
            options: {
            iconUrl: 'marker_hole_green.png',
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
        L.NumberedDivIconOrange = L.Icon.extend({
            options: {
            iconUrl: 'marker_hole_orange.png',
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

        function printCMaps(){
            var cmapData = JSON.parse('<?php echo json_encode($cmapData); ?>');
            cmapData.map(function(data){
                createCMap(data);
            });
        }

        function createCMap(data){
            L.MakiMarkers.accessToken = "pk.eyJ1Ijoid2hpdGVoZWFkcmoyIiwiYSI6ImNqMnl1ZTE2ZDAwM3IycXByZXNya3N1OXgifQ.nMcG7PmGB-S1p3qNzerR0A";
            var icon = L.MakiMarkers.icon({color: "#b0b", size: "m"});
            var mymap = L.map('cmap-'+data.key);
            mymap.fitBounds(getBoundsArray(data.viewport));
            L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/{id}/tiles/256/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                maxZoom: 18,
                id: 'light-v9',
                accessToken: 'pk.eyJ1Ijoid2hpdGVoZWFkcmoyIiwiYSI6ImNqMnl1ZTE2ZDAwM3IycXByZXNya3N1OXgifQ.nMcG7PmGB-S1p3qNzerR0A'
            }).addTo(mymap);
            if(data.teachees.length>0){
                data.teachees.map(function(data){
                    var marker = new L.Marker(new L.LatLng(parseFloat(data.Lat), parseFloat(data.Long)), {
                        icon:   new L.NumberedDivIcon({number: data.Index})
                    }).addTo(mymap);
                });
            }
            if(data.companions.length>0){
                data.companions.map(function(data){
                    var marker = new L.Marker(new L.LatLng(parseFloat(data.Lat), parseFloat(data.Long)), {
                        icon:   new L.NumberedDivIconOrange({number: data.Index})
                    }).addTo(mymap);
                });
            }
        }

        function getBoundsArray(data){
            var boundsArray = [];
            data.map(function(data){
                boundsArray.push([parseFloat(data.Lat),parseFloat(data.Long)]);
            });
            return boundsArray;
        }

        printCMaps();
    </script>
  </body>
</html>
