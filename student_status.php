<?php
session_start();
//checking if session already exists
if($_SESSION['name']){
    $SNO = $_SESSION['sno'];
} else {
    header("Location: index.php");
}

if (isset($_GET['logout'])){
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

function status_output($num){
    if($num==1){
        echo "Approved";
    } elseif($num == -2) {
        echo "Rejected";
    } else {
        echo "Pending";
    }
}

if(isset($_GET['reupload'])){
    include 'connection.php';

    $sql = "UPDATE student SET PROCTOR = -1, HOD = -1, PRINCIPAL = -1, OFFICE = -1 WHERE SNO = " . $SNO . ";";
    mysqli_query($con, $sql);
    mysqli_close($con);
    header("Location: portal.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVIT</title>
    <link rel="stylesheet" href="status.css">
</head>
<body>
    <div id="navbar">
        <form method="get">
            <input class="btn" type="submit" name="logout" value="Log Out">
        </form>
    </div>
    <div id="container">
        <div id="info">
            <div class="data">
                <div>
                    <label>Name: </label>
                    <label>Email: </label>
                    <label>Phone: </label>
                </div>
                <div>
                    <label><?php echo $_SESSION['name']; ?></label>
                    <label><?php echo $_SESSION['email']; ?></label>
                    <label><?php echo $_SESSION['phone']; ?></label>
                </div>
            </div>
            <div class="photo">
                <img src="profile.png" alt="Image not found">
            </div>
        </div>
        <div id="content">
            <div id="heading">
                APPLICATOIN STATUS
            </div>
            <div class="data1">
                <div>
                    <label>Proctor: </label>
                    <label>HOD: </label>
                    <label>PRINCIPAL: </label>
                    <label>OFFICE: </label>
                </div>
                <?php
                include 'connection.php';

                $sql = "SELECT * FROM student WHERE SNO=" . $SNO . ";";

                $result = mysqli_query($con, $sql);
                $data = mysqli_fetch_assoc($result);
                ?>

                <div>
                    <label><?php status_output($data['PROCTOR']) ?></label>
                    <label><?php status_output($data['HOD']) ?></label>
                    <label><?php status_output($data['PRINCIPAL']) ?></label>
                    <label><?php status_output($data['OFFICE']) ?></label>
                </div>
            </div>
            <div>
                <form method="get">
                    <input class="btn2" type="submit" name="reupload" value="Re-Upload">
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>