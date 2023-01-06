<!--
This PHP script is used to upload and save files to a server and to update a database with the status of file uploads for a user. 
If the user is not logged in, they will be redirected to the login page. If the user clicks the logout button, the session will be 
destroyed, and the user will be redirected to the login page. The script has a function called save_file(), which takes a file name 
as an argument. This function moves the files from the temporary uploaded location to a new permanent location. It does this using 
the move_uploaded_file() function. When the user clicks the upload button, the script creates a new directory on the server for the 
user and uses the save_file() function to save the chosen files. It then updates the database to show that the files have been uploaded 
for the user. If the upload is successful, the user will be redirected to a new page
-->

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


function save_file($name){
    $location = "data/".$GLOBALS['SNO'];
    $temp_location = $_FILES[$name]['tmp_name'];
    $new_location = $location."/".$name.".pdf";

    move_uploaded_file($temp_location, $new_location);
}

//Uploading files and modifying database
if (isset($_POST['upload'])){

    mkdir("data/".$SNO);

    save_file('aadhaar');
    save_file('study');
    save_file('bonafide');
    save_file('tenth');
    save_file('twealth');
    save_file('transfer');

    include 'connection.php';

    $sql = "UPDATE student SET PROCTOR=0 WHERE SNO='$SNO';";

    mysqli_query($con, $sql);

    mysqli_close($con);
    
    echo "<script>alert('Documents uploaded successfully.');
    window.location.href = 'student_status.php';</script>";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVIT</title>
    <link rel="stylesheet" href="portal_style.css">
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
            <form id="form_format" method="POST" enctype="multipart/form-data">
                <div class="column">
                    <div>
                        Aadhaar Card *: 
                        <div class="upload_style">
                            <input type="file" name="aadhaar" id="aadhaar" hidden required>
                            <input type="text" id="aadhaar_chosen" value="No file chosen" disabled><label for="aadhaar">Choose File</label>
                        </div>
                    </div>
                    <div>
                        Bonafide Certificate: 
                        <div class="upload_style">
                            <input type="file" name="bonafide" id="bonafide" hidden>
                            <input type="text" id="bonafide_chosen" value="No file chosen" disabled><label for="bonafide">Choose File</label>
                        </div>
                    </div>
                    <div>
                        X Marks Card *: 
                        <div class="upload_style">
                            <input type="file" name="tenth" id="tenth" hidden required>
                            <input type="text" id="tenth_chosen" value="No file chosen" disabled><label for="tenth">Choose File</label>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div>
                        Study Certificate *: 
                        <div class="upload_style">
                            <input type="file" name="study" id="study" hidden required>
                            <input type="text" id="study_chosen" value="No file chosen" disabled><label for="study">Choose File</label>
                        </div>
                    </div>
                    <div>
                        Transfer Certificate: 
                        <div class="upload_style">
                            <input type="file" name="transfer" id="transfer" hidden>
                            <input type="text" id="transfer_chosen" value="No file chosen" disabled><label for="transfer">Choose File</label>
                        </div>
                    </div>
                    <div>
                        XII Marks Card *: 
                        <div class="upload_style">
                            <input type="file" name="twealth" id="twealth" hidden required>
                            <input type="text" id="twealth_chosen" value="No file chosen" disabled><label for="twealth">Choose File</label>
                        </div>
                    </div>
                </div>
            </form>
            <input class="btn2" form="form_format" type="submit" name="upload" value="Submit">
        </div>
    </div>
    <script>
        function upload_anime(file_btn, file_input){
            const actualBtn = document.getElementById(file_btn);
            const fileChosen = document.getElementById(file_input);

            actualBtn.addEventListener('change', function(){
                fileChosen.value = this.files[0].name
        })
        }

        upload_anime('aadhaar','aadhaar_chosen');
        upload_anime('study','study_chosen');
        upload_anime('bonafide','bonafide_chosen');
        upload_anime('tenth','tenth_chosen');
        upload_anime('twealth','twealth_chosen');
        upload_anime('transfer','transfer_chosen');
    </script>
</body>
</html>
