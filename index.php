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
            $sql = "INSERT INTO teacher (NAME, EMAIL, PHONE, PASSWORD, POST) VALUES ('$name', '$email', '$phone', '$hash', '$rank');";
        }

        // Execute the query
        if (mysqli_query($con, $sql)) {
            echo '<script>alert("Account created successfully. Sign In to continue.");</script>';
        } else {
            echo '<script>alert("Sign Up failed. Please try again.");</script>';
        }

    } else {
        echo '<script>alert("Password does not match")</script>';
    }

    // Close the database connection
    mysqli_close($con);
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
                    session_unset();
                    session_destroy();
                    echo "<script>alert('Your documents had been uploaded and they are still under process of verification.');
                    window.location.href = 'index.php';
                    </script>;";
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
    <!-- <div id="navbar">
        <a href="home.html"><button class="btn">Home</button></a>
        <a href="downloads.html"><button class="btn">Downloads</button></a>
        <a href="contacts.html"><button class="btn">Contacts</button></a>
        <a href="help.html"><button class="btn">Help</button></a>
        <span class="search_engine">
            <input class="search_box" type="text">
            <input class="search_btn" type="button" value="Search">
        </span>
    </div> -->
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
                <label class="form_label2">Name</label> <input class="input_box2" type="text" name="name" id="name" maxlength="30" required>
                <label class="form_label2">Email</label> <input class="input_box2" type="text" name="email" id="email" maxlength="50" required>
                <label class="form_label2">Phone</label> <input class="input_box2" type="number" name="phone" id="phone" required>
                <label class="form_label2">Password</label> <input class="input_box2" type="password" name="password" id="password" required>
                <label class="form_label2">Confirm Password</label> <input class="input_box2" type="password" name="c_password" id="c_password" required>
                <br>
                <select class="input_box2" name="rank">
                    <option value="student">Student</option>
                    <option value="PROCTOR">Proctor</option>
                    <option value="HOD">HOD</option>
                    <option value="OFFICE">Office</option>
                </select>
                <input class="btn2" type="submit" name="signup" value="Sign Up">
            </form>
        </div>
    </div>
</body>
</html>