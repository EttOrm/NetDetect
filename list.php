<?php

include_once 'dbLogIn.php';

$length = sizeof($mac);

$upId = $_GET['upId'];

$upMAC = $_POST['upMAC'];
$upName = $_POST['upName'];
$upNum = $_POST['upNum'];

$delCon = $_POST['delCon'];
$delNum = $_POST['delNum'];

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Users</title>
  </head>
  <body>

    <?php include_once 'nav.php'; ?>
    
    <script> document.getElementById("users").classList.add("active");</script>


    <div style="text-align:center" class="container-sm mx-auto">

    <?php

if(isset($delNum)){

    $_POST = array();
    
    $sql = "SELECT name FROM mac WHERE num='$delNum';";
	
    $result = $conn->query($sql)->fetch();

    $sql = $conn->prepare("DELETE FROM mac WHERE num='$delNum';");

    if($sql->execute()){
        ?>
	<div class="alert alert-warning" role="alert">
          <?php echo $result['name']." successfully removed"; ?>
        </div>
        <?php
    }else{
	?>
	<div class="alert alert-danger" role="alert">
          <?php echo "Failed to remove ".$result['name']; ?>
        </div>
        <?php
    }
}

if(isset($upNum)){

    $_POST = array();

    $sql = $conn->prepare("UPDATE mac SET mac='$upMAC', name='$upName' WHERE num = '$upNum'");

    if($sql->execute()){
        ?>
	<div class="alert alert-success" role="alert">
          <?php echo "$upName successfully updated"; ?>
        </div>
        <?php
    }else{
	?>
	<div class="alert alert-danger" role="alert">
          <?php echo "Failed to update $upName"; ?>
        </div>
        <?php
    }
}

    
    if(!isset($delCon) && !isset($upId)){

    $sql = "SELECT * FROM mac;";

  	
    $result = $conn->query($sql)->fetchall();
    
    ?>
        <h1>User list</h1>
        <br>
        <div class="row row-cols-1 row-cols-md-3">

        <?php
        if(sizeof($result) > 0){
            foreach($result as $result) {
            ?>
                <div class="col mb-4">
                 <div class="card" style="width: 20rem;">
                  <div class="card-header">
                    <?php echo $result['name']; ?>
                  </div>
                  <div class="card-body">
                    <p class="card-title">MAC: <?php echo $result['mac']; ?></p>
                    
                    <form action="list.php" method="post">
                    
                    <a href="list.php?upId=<?php echo $result['num']; ?>"
                class="btn btn-primary col-3">
                        Edit
                    </a>
                    
                    <input type="int" name="delCon" value="<?php echo $result['num']; ?>" hidden readonly>
                    <input class="btn btn-danger col-3" type="submit" value="Delete">
                    </form>
                   
                   </div>
                  </div>
        </div>
            <br>
            <?php
                }
        }
        else {
            ?>
            <p>No users found, add new users <a href="add.php">here</a></p>
    
    <?php
        }
    }
    else if (isset($upId)) {

    $sql = "SELECT * FROM mac WHERE num = '$upId';";

  	
    $result = $conn->query($sql)-> fetch();

    ?>
    <h1>Update device infromartion</h1>
    <br>
    <br>
    
    <div class="col-md-4 offset-md-4">
    <form method="post" action="list.php">
      <div class="form-group">
        <label for="mac">Device name</label>
        <input type="text" class="form-control" id="name"
    name="upName" value="<?php echo $result['name']; ?>" required>
        <br>
        <label for="name">MAC adress</label>
        <input type="text" class="form-control"
    id="mac" name="upMAC" value="<?php echo $result['mac']; ?>" required>
      <input type="text" name="upNum" id="num" value="<?php echo $upId; ?>" hidden>
      </div>
    <br>
    <button type="submit" class="btn btn-primary col-3">Submit</button>
    <a class="btn btn-danger col-3" href="list.php" role="button">Back</a>
    </form>
    </div>

    <?php

    }
    else if(isset($delCon)) {

    ?>

    <h1>Delete device?</h1>
    <br>
    <p>This action is irreversible</p>
    <br>
    
    <div class="col-md-4 offset-md-4">
    <form method="post" action="list.php">
    <input type="int" name="delNum" value="<?php echo $delCon; ?>"  hidden readonly>
      <button type="submit" class="btn btn-primary col-3">Yes</button>
        <a class="btn btn-danger col-3" href="list.php" role="button">No</a>
    </form>
    </div>

    <?php

    }

    ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    
  </body>
</html>
  

