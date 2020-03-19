<?php

include_once 'dbLogIn.php';

$newMac = $_GET['Nmac'];

$addMac = $_POST['addmac'];
$addName = $_POST['addname'];

$addMac = trim($addMac, " ");


?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>New user</title>
  </head>
  <body>

    <?php include_once 'nav.php'; ?>
    
    <script> document.getElementById("add").classList.add("active");</script>

    <div style="text-align:center" class="container-sm mx-auto">

    <?php


if(isset($addName)){

    $sql = $conn->prepare("INSERT INTO mac (mac, name) VALUES ('$addMac', '$addName')");

    if($sql->execute()){
        ?>
	<div class="alert alert-success" role="alert">
          <?php echo "$addName successfully added"; ?>
        </div>
        <?php
    }else{
	?>
	<div class="alert alert-danger" role="alert">
          <?php echo "Failed to add $addName"; ?>
        </div>
        <?php
    } 
}

    
    if(!isset($newMac)){

    exec('sudo arp-scan -l -x | cut -f2', $mac);
    exec('sudo arp-scan -l -x | cut -f3', $man);

    $length = sizeof($mac);
    
    ?>
        <h1>Select device to add</h1>
        <br>
        <a href=add.php?Nmac=manual>Add device manually</a>
        <br>
        <br>
        <div class="row row-cols-1 row-cols-md-3">

        <?php
        if ($length > 0) {
            for($i = 0; $i < $length; $i++) {
        ?>
            <div class="col mb-4">
             <div class="card" style="width: 20rem;">
              <div class="card-header">
                <?php echo $mac[$i]; ?>
              </div>
              <div class="card-body">
                <p class="card-title">Manufacturer: <?php echo $man[$i]; ?></p>
                <a href="add.php?Nmac=<?php echo $mac[$i];?>" class="btn btn-primary">Add device</a>
              </div>
             </div>
            </div>
            <br>
        <?php
            }
        }
        else {
            echo "No devices detected. Please try again";
        }
        ?>
    <?php

    }
    else if ($newMac == "manual") {

    ?>
    <h1>Add device infromartion</h1>
    <br>
    <br>
    
    <form method="post" action="add.php">
      <div class="form-group">
        <label for="mac">MAC adress</label>
        <input type="text" class="form-control col-md-4 offset-md-4" id="mac" name="addmac" placeholder="1a:2b:3c:4d:5e:6f" required>
      </div>
      <div class="form-group">
        <label for="name">Device name</label>
        <input type="text" class="form-control col-md-4 offset-md-4" id="name" name="addname" placeholder="...'s phone etc." required>
      </div>
    <button type="submit" class="btn btn-primary">Submit</button>
     <a class="btn btn-danger" href="add.php" role="button">Back</a> 
    </form>

    <?php

    }
    else {

    ?>

    <h1>Add device infromartion</h1>
    <br>
    <br>
    
    <form method="post" action="add.php">
      <div class="form-group">
        <label for="mac">MAC adress</label>
        <input type="text" class="form-control col-md-4 offset-md-4" id="mac" name="addmac" value="<?php echo $newMac;?> " readonly>
      </div>
      <div class="form-group">
        <label for="name">Device name</label>
        <input type="text" class="form-control col-md-4 offset-md-4" id="name" name="addname" placeholder="...'s phone etc." required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
       <a class="btn btn-danger" href="add.php" role="button">Back</a>
    </form>

    <?php

    }

    ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
  </body>
</html>
  

