<?php

$identitiy = "abcdefgh";

global $dKey, $identitiy, $dIP;

include_once 'dbLogIn.php';

$sql = "SELECT * FROM key WHERE ID = 1;";

$table = $conn->query($sql)->fetchall();

$cLight = $table[0][5];
$dIP = $table[0][1];
$dKey = $table[0][4];

if(isset($_POST['light'])) {

    $gLight = $_POST['light'];

    $sql = $conn->prepare("UPDATE key SET light = '$gLight' WHERE ID = 1");

    $sql->execute();
    
}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    
    <title>Lights</title>

    <script>
        function change (name) {
            
            document.getElementById("current").innerHTML = name;
        }
    </script>

  </head>
  <body>

    <?php include_once 'nav.php'; ?>
    
    <script> document.getElementById("lights").classList.add("active");</script>

    <div style="text-align:center" class="container-sm mx-auto">

    <h1>Select light/group</h1>

    <br>
    
    <h2>Selected light</h2>

    <h5 id="current"></h5>
    
<?php   //-------------------------------------

    if($_POST['name']) {

    ?>

     <script> change('<?php echo $_POST["name"] ?>'); </script>

    <?php

    } else {

    global $dKey, $identitiy, $dIP, $cLight;

    exec('timeout 2 coap-client -m get -u '.$identitiy.' -k '.$dKey.' "coaps://'.$dIP.':5684/'.$cLight.'" '
        , $cljs );

    if(sizeof($cljs) > 3){

    $current = json_decode($cljs[3], TRUE);

    
    
    foreach ($current as $key => $val) {

        if($key == 9001){
           ?> <script>
           
            change('<?php echo $val ?>');

           </script> <?php
        }
    }
    } else { change('No devices found'); }

    }

//---------------------------------?>

    <br>

    <h2>Lights</h2>
    <br>

    <div class="row row-cols-1 row-cols-md-3">

    <?php

    global $dKey, $identitiy, $dIP;

    exec(
    'timeout 2 coap-client -m get -u '.$identitiy.' -k '.$dKey.' "coaps://'.$dIP.':5684/15001" '
    , $lsjs );

    if(sizeof($lsjs) > 3){

    $lights = json_decode($lsjs[3], TRUE);

    foreach($lights as $key => $num) { light($num); }

    } else { echo '<p class="mx-auto">No devices found</p>'; }

    function light($id) {

        global $dKey, $identitiy, $dIP;

        exec(
        'coap-client -m get -u '.$identitiy.' -k '.$dKey.' "coaps://'.$dIP.':5684/15001/'.$id.'" '
        , $ljs );

        $light = json_decode($ljs[3], TRUE);

        foreach ($light as $key => $val) {

                if($key == 9001){
                    $lName = $val;
                }

                if($key == 9003){
                    $lID = $val;
                }
        }

        ?>
        
                <div class="col mb-4">
                 <div class="card" style="width: 20rem;">
                  <div class="card-header">
                    <?php echo $lName; ?>
                  </div>
                  <div class="card-body">
                    <form method="post" action="lights.php">
                     <input type="text" class="form-control" id="light"
                        name="light" value="<?php echo "15001/", $lID;?>" hidden>
                     <input type="text" class="form-control" id="name"
                        name="name" value="<?php echo $lName; ?>" hidden>
                     <button type="submit" class="btn btn-primary"
                        onclick="change('<?php echo $lName ?>')">Select</button>
                    </form>
                  </div>
                 </div>
                </div>
                <br>

        <?php
    }

    //-------------------------------------

    ?>

    </div>

    <br>
    
    <h2>Groups</h2>

    <br>

    <div class="row row-cols-1 row-cols-md-3">

    <?php
    
    global $dKey, $identitiy, $dIP;

    exec(
    'timeout 2 coap-client -m get -u '.$identitiy.' -k '.$dKey.' "coaps://'.$dIP.':5684/15004" '
    , $gsjs );

    if(sizeof($gsjs) > 3){

    $groups = json_decode($gsjs[3], TRUE);

    foreach($groups as $key => $num) { group($num); }

    } else { echo '<p class="mx-auto">No devices found</p>'; }

    function group($id) {

        global $dKey, $identitiy, $dIP;

        exec(
        'coap-client -m get -u '.$identitiy.' -k '.$dKey.' "coaps://'.$dIP.':5684/15004/'.$id.'" '
        , $gjs );

        $group = json_decode($gjs[3], TRUE);

        foreach ($group as $key => $val) {

                if($key == 9001){
                    $gName = $val;
                }

                if($key == 9003){
                    $gID = $val;
                }
        }
        ?>
        
                <div class="col mb-4">
                 <div class="card" style="width: 20rem;">
                  <div class="card-header">
                    <?php echo $gName; ?>
                  </div>
                  <div class="card-body">
                    <form method="post" action="lights.php">
                     <input type="text" class="form-control" id="light"
                        name="light" value="<?php echo "15004/", $gID;?>" hidden>
                     <input type="text" class="form-control" id="name"
                        name="name" value="<?php echo $gName; ?>" hidden>
                     <button type="submit" class="btn btn-primary"
                        onclick="change('<?php echo $gName ?>')">Select</button>
                    </form>
                  </div>
                 </div>
                </div>
                <br>

        <?php
    }

    //-------------------------------------
    ?>
    
    </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>
