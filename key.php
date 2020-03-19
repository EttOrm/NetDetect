<?php

$identitiy = "abcdefgh";


include_once 'dbLogIn.php';

$sql = $conn->prepare("CREATE TABLE IF NOT EXISTS key ( 
ID INTEGER PRIMARY KEY AUTOINCREMENT,
IP varchar(255),
MAC VARCHAR(15),
code VARCHAR(16),
key VARCHAR(16),
light VARCHAR(255)
);");

$sql->execute();

$sql = $conn->prepare("CREATE TABLE IF NOT EXISTS mac ( 
num INTEGER PRIMARY KEY AUTOINCREMENT,
name varchar(255),
mac VARCHAR(15)
);");

$sql->execute();

//---------------

$nMAC = $_POST['gMAC'];
$nCode = $_POST['gCode'];

if(isset($nMAC) && isset($nCode)) {

    $sql = $conn->prepare("UPDATE key SET code = '$nCode', MAC = '$nMAC' WHERE ID = 1");

    $sql->execute();

}

//---------------

$sql = "SELECT * FROM key WHERE ID = 1;";

$table = $conn->query($sql)->fetchall();

if($table[0][0] == null) {

    $sql = $conn->prepare("INSERT INTO key (IP, MAC, code, key, light)
                            VALUES ('IP','MAC','Code','Key','Lamp')");
    $sql->execute();
}

$dIP = $table[0][1];
$dMAC = $table[0][2];
$dCode = $table[0][3];
$dKey = $table[0][4];

?>



<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Gateway</title>
  </head>
  <body>

    <?php include_once 'nav.php'; ?>
    
    <script> document.getElementById("key").classList.add("active");</script>

    <div style="text-align:center" class="container-sm mx-auto">

<h1>Gateway setup</h1>

<br>

<?php

if(!isset($_POST['conTest'])) {

//-------------------------------------?>

    <form method="post" action="key.php">
      <div class="form-group">
        <label for="mac">Gateway MAC</label>
        <input type="text" class="form-control col-md-4 offset-md-4" id="gMAC"
            name="gMAC" value="<?php echo $dMAC; ?>" required>
        <br>
    
        <label for="name">Security code</label>
        <input type="text" class="form-control col-md-4 offset-md-4" id="gCode"
            name="gCode" value="<?php echo $dCode; ?>" required>
        <br>
        <br>

        <button type="submit" class="btn btn-primary">Update</button>
       </div>
    </form>

    <p>The MAC-adress is a twelve character code/adrees in the
            format "** : ** : ** : ** : ** : **" that can be found on
            the lable of the gateway.

        <br>
        <br>
        The security code is a 16 character code
            that can be found on the same lable as the MAC-adress
    </p>

    <form method="post" action="key.php">
        <input type="int" class="form-control" id="conTest"
            name="conTest" value="1" hidden>
        <button type="submit" class="btn btn-primary">Finalize/diagnose connection</button>
    </form>

    <br>
    <br>
    
    <?php

} else {


if(isset($nMAC) && !isset($dIP))
{
    $isIP = newIP($nMAC);
} else if(isset($dIP)) {

    $isIP = true;
} else {

    $isIP = false;
}



if($isIP) {
    switch(test()) {
        case 0:
            ?> <p style="color:red"> No connection/wrong MAC.
                <br> Check your connection and try a few times,
                    for some reason it doesn´t always work.</p> <?php
            break;
        case 1:
            ?> <p style="color:red"> Wrong security code.
                <br> Could also be a security measure by the gateway.
                <br> No workaround is currently included.</p> <?php
            break;
        case 2:
            ?> <p style="color:green"> complete</p> <?php
            break;
        }
    
} else {

    echo "insufficient input;";
}

?>

<a href="key.php" class="btn btn-primary">Back</a>

<?php

}


//--------------------------------------

function test () {

    switch (gateway()) {

        case 0:
            global $dMAC;
            if(newIP($dMAC)) {
                if(gateway() == 4) {
                    return 2;
                }
            }
            
            return 0;
            break;
        
        case 1:
            global $dMAC;
            if(newIP($dMAC)) {
                if(gateway() == 4) {
                    return 2;
                }
            }
            
            return 0;
            break;

        case 2:
            global $dCode, $nCode;
        
            if(isset($dCode)) {
                if(newKey($dCode)) {
                    if(gateway()) {
                        return 2;    
                    }
                }
                
            } else if(isset($nCode)){
                if(newKey($nCode)) {
                    if(gateway()) {
                        return 2;    
                    }
                }
            }
            
            return 1;
            break;
    
        case 4:
        
            return 2;
            break;
    }
}

function gateway(){

    global $dIP, $dKey, $identitiy;

    if($dKey != null) {

    exec('timeout 2 coap-client -m get -u '.$identitiy.' -k '.$dKey.'\
    "coaps://'.$dIP.':5684/15011/15012" | cut -f2', $testr);

    echo sizeof($testr), ";1";

    return sizeof($testr);

    } else {

        return 2;
    }

}

function newIP ($iMAC) {

    exec('sudo arp-scan -l -x | cut -f2', $mac);
    exec('sudo arp-scan -l -x | cut -f1', $IP);

    $arp = array_search($iMAC, $mac);

    echo $IP;

    echo $arp, "arp";

    if(isset($arp) && $arp != "") {

        echo "new ip;";
        global $dIP, $conn;
        echo "a",$arp,"a;";
        $dIP = $IP[$arp];
        echo $dIP, ";";

        
        
        $sql = $conn->prepare("UPDATE key SET IP = '$dIP' WHERE ID = 1");

        $sql->execute();

        return true;
        
    } else {

        echo "failure ip;";
        return false;
    
    }
}


function newKey($iCode) {

    global $dIP, $dKey, $conn, $identity;

    exec('coap-client -m post -u "Client_identity"\
    -k '.$iCode.' -e "{\"9090\":\"'.$identitiy.'"\}"\
    "coaps://"'.$dIP.'":5684/15011/9063"', $res);

    if(sizeof($res) == 4) {

        $result = json_decode($res[3], TRUE);

        foreach ($result as $key => $val) {

            if($key == 9091) {
                $dKey = $val;

                $sql = $conn->prepare("UPDATE key SET key = '$dKey' WHERE ID = 1");

                $sql->execute();
                
                echo $dKey;
                return true;
            }
        }
    }

    echo "failure key;";
    return false;
}

?>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    </div>
  </body>
</html>
