<?php
session_start();
//checking if session already exists
if($_SESSION['name']){
    $post = $_SESSION['post'];
} else {
    header("Location: index.php");
    exit();
}

if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}


function view_document($sno, $document){
    $_SESSION['student_number'] = $sno;
    $_SESSION['document_name'] = $document;
    header("Location: document_viewer.php");
}


//Checking if asked to open a file
if(isset($_GET['aadhaar'])){
    view_document($_GET['sno'], 'aadhaar');
}

if(isset($_GET['study'])){
    view_document($_GET['sno'], 'study');
}

if(isset($_GET['bonafide'])){
    view_document($_GET['sno'], 'bonafide');
}

if(isset($_GET['tenth'])){
    view_document($_GET['sno'], 'tenth');
}

if(isset($_GET['twealth'])){
    view_document($_GET['sno'], 'twealth');
}

if(isset($_GET['transfer'])){
    view_document($_GET['sno'], 'transfer');
}

if(isset($_GET['approve'])){
    include 'connection.php';
    $sno = $_GET['sno']; 
    $post_array = ['PROCTOR','HOD','PRINCIPAL','OFFICE','STATUS'];
    $index = array_search($post, $post_array);
    $new_index = $index + 1;
    $element = $post_array[$new_index];

    $sql = "UPDATE student SET " . $post . "=1, " . $element . "=0 WHERE SNO=" . $sno . ";";

    mysqli_query($con, $sql);

    if($post=='OFFICE'){
        $sql = "INSERT INTO closed (SNO, NAME, PHONE)
        SELECT SNO, NAME, PHONE FROM student WHERE SNO=". $sno .";";
        mysqli_query($con, $sql);
        echo "<script>alert('Application closed successfully.');</script>";
    }
    mysqli_close($con);
}

if(isset($_GET['reject'])){
    include 'connection.php';
    $sno = $_GET['sno'];

    $sql = "UPDATE student SET " . $post . "=-2 WHERE SNO=" . $sno . ";";
    mysqli_query($con, $sql);

    echo "<script>alert('Application Rejected.');</script>";

    mysqli_close($con);

}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>teacher_portal</title>
    <link rel="stylesheet" href="teacher_portal.css">
</head>
<body>
    <div id="navbar">
        <form method="get">
            <input class="btn" type="submit" name="logout" value="Log Out">
        </form>
        <form action="view_closed.php">
            <input class="btn" type="submit" value="Closed Application">
        </form>
    </div>
    <div id="container">
        <div id="info">
            <div class="data">
                <div>
                    <label>Name: </label>
                    <label>Email: </label>
                    <label>Phone: </label>
                    <label>Post: </label>
                </div>
                <div>
                    <label><?php echo $_SESSION['name']; ?></label>
                    <label><?php echo $_SESSION['email']; ?></label>
                    <label><?php echo $_SESSION['phone']; ?></label>
                    <label><?php echo $post; ?></label>
                </div>
            </div>
            <div class="photo">
                <img src="profile.png" alt="Image not found">
            </div>
        </div>
        <div id="content">
            <table>
                <tr>
                    <th style="width:50%">NAME</th>
                    <th>FILES</th>
                    <th>CONTROLS</th>
                </tr>
                <?php
                include 'connection.php';

                $sql = "SELECT * FROM student WHERE ".$post."=0;";
                $result = mysqli_query($con, $sql);

                while($row=mysqli_fetch_assoc($result)){ ?>
                    <tr>
                        <form method="get">
                            <td><input class="hidden_input" type="text" name="sno" value='<?php echo $row["SNO"]; ?>'>
                            <?php echo $row["NAME"]; ?></td>
                            <td>
                                <span class="span_display">
                                    <input class="btn2" type="submit" name="aadhaar" value="Aadhaar">
                                    <input class="btn2" type="submit" name="study" value="Study">
                                    <input class="btn2" type="submit" name="bonafide" value="Bonafide">
                                    <input class="btn2" type="submit" name="tenth" value="Tenth">
                                    <input class="btn2" type="submit" name="twealth" value="Twealth">
                                    <input class="btn2" type="submit" name="transfer" value="Transfer">
                                </span>
                            </td>
                            <td>
                                <span class="span_display">
                                    <input class="btn3" type="submit" name="approve" value="Approve">
                                    <input class="btn4" type="submit" name="reject" value="Reject">
                                </span>
                            </td>
                        </form>
                    <tr>
               <?php 
                }
                mysqli_close($con);
                ?>
            </table>
        </div>
    </div>
</body>
</html>