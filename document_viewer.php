<?php
session_start();
//checking if session already exists
if($_SESSION['name']){
    $file_name = $_SESSION['student_number'];
    $document = $_SESSION['document_name'];
} else {
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aadhaar</title>
    <style>
        .iframe_size{
            width: 100%;
            height: 700px;
        }

        body{
            background-color: grey;
        }
    </style>
</head>
<body>
    <?php
    echo "<iframe class='iframe_size' title='aadhaar' src='data/" . $file_name . "/". $document .".pdf'></iframe>";
    ?>
</body>
</html>