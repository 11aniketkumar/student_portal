<!--
//This code is a PHP script that contains a login and signup form for a user. The login form has input fields for the user's email, password, 
//and rank (either student or teacher). When the user submits the form, the script verifies the email and password by checking the values 
//stored in the 'student' or 'teacher' table in the database. If the email and password are valid, the user is redirected to the 'portal.php' 
//or 'teacher_portal.php' page based on their rank. If the email is not found in the database or the password is incorrect, an error message 
//is displayed.

//The signup form has input fields for the user's name, email, phone, password, and rank. When the user submits the form, the script checks 
//if the password and confirm password fields match. If they do, it hashes the password using the 'password_hash()' function and stores the 
//user's information in the 'student' or 'teacher' table based on their rank. If the user's rank is 'teacher', the script also checks for a 
//valid pin before storing the information in the table. If the password and confirm password fields do not match or the pin is invalid, an 
//error message is displayed.
-->

<?php
if(isset($_POST['signup'])){
    // Collect post variables
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];
    $rank = $_POST['rank'];

    if($password == $c_password){

        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        include 'connection.php';
        
        if($rank=='student'){
            $sql = "INSERT INTO student (NAME, EMAIL, PHONE, PASSWORD) VALUES ('$name', '$email', '$phone', '$hash');";
        } else {
            $pin = $_POST['teacher_check'];
            if($pin == "1234"){
                $sql = "INSERT INTO teacher (NAME, EMAIL, PHONE, PASSWORD, POST) VALUES ('$name', '$email', '$phone', '$hash', '$rank');";
            } else {
                echo "<script>alert('Invalid Pin entered.');
                window.location.href = 'index.php';</script>";
            }
        }

        // Execute the query
        if (mysqli_query($con, $sql)) {
            echo '<script>alert("Account created successfully. Sign In to continue.");</script>';
        } else {
            echo '<script>alert("Sign Up failed. Please try again.");</script>';
        }
        // Close the database connection
        mysqli_close($con);

    } else {
        echo '<script>alert("Password does not match")</script>';
    }
}


if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rank = $_POST['rank'];

    include 'connection.php';

    if($rank=='student'){
        $sql = "SELECT * FROM student WHERE EMAIL LIKE '$email';";
    } else {
        $sql = "SELECT * FROM teacher WHERE EMAIL LIKE '$email';";
    }

    //running query
    $result = mysqli_query($con, $sql);

    if(mysqli_num_rows($result)>0){
        $data = mysqli_fetch_assoc($result);
        $pass =$data["PASSWORD"];
        if(password_verify($password, $pass)){
            session_start();
            $_SESSION['sno'] = $data["SNO"];
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $data["NAME"];
            $_SESSION['phone'] = $data["PHONE"];

            //taking user to the respective page according to their roles
            if($rank=='student'){
                //checking if student has already uploaded the documents or not
                if($data["PROCTOR"] == -1){
                    header("Location: portal.php");
                } else {
                    header("Location: student_status.php");
                }
            } else {
                $_SESSION['post'] = $data["POST"];
                header("Location: teacher_portal.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid password entered.');</script>";
        }
    } else {
        echo "<script>alert('Email not found in the database');</script>";
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVIT</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="container">
        <div id="login">
            <h1>LOGIN</h1>
            <form class="form_align" method="post">
                <label class="form_label">Email</label> <input class="input_box" type="text" name="email" id="email" maxlength="50" required>
                <label class="form_label">Password</label> <input class="input_box" type="password" name="password" id="password" required>
                <span>
                    <input class="form_radio" type="radio" name="rank" value="student" required><label class="form_label">Student</label>
                    <input class="form_radio" type="radio" name="rank" value="teacher" required><label class="form_label">Faculty</label>    
                </span>
                <input class="btn2" type="submit" name="login" value="Log In">
            </form>
        </div>
        <div id="signup">
            <h1>SIGN UP</h1>
            <form class="form_align" method="post">
                <input class="input_box2" type="text" name="name" id="name" maxlength="30" placeholder="Enter your Name" required>
                <input class="input_box2" type="text" name="email" id="email" maxlength="50" placeholder="Enter your Email" required>
                <input class="input_box2" type="number" name="phone" id="phone" placeholder="Enter your Phone Number" required>
                <input class="input_box2" type="password" name="password" id="password" placeholder="Enter Password" required>
                <input class="input_box2" type="password" name="c_password" id="c_password" placeholder="Confirm Password" required>
                <select class="input_box2" name="rank" onchange="student_check(this);">
                    <option value="student">Student</option>
                    <option value="PROCTOR">Proctor</option>
                    <option value="HOD">HOD</option>
                    <option value="OFFICE">Office</option>
                </select>
                <input class="input_box2" type="password" name="teacher_check" id="teacher_check"  style="display: none;" placeholder="Please insert university pin">
                <input class="btn2" type="submit" name="signup" value="Sign Up">
            </form>
        </div>
    </div>
    <script>
        function student_check(that){
            if(that.value=="student"){
                document.getElementById('teacher_check').style.display = "none";
            } else {
                document.getElementById('teacher_check').style.display = "block";
            }
        }
    </script>
</body>
</html>
