<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Closed Application</title>
    <link rel="stylesheet" href="teacher_portal.css">
    <style>
        h1{
            font-size: 50px;
            font-family: 'times new roman';
            font-weight: 900;
            text-align: center;
            letter-spacing: 4px;
            word-spacing: 20px;
            color: indigo;
            margin: 30px;
        }
    </style>
</head>
<body>
    <div id="navbar">
        <form action="teacher_portal.php">
            <input class="btn" type="submit" value="Back">
        </form>
    </div>
    <div id="container">
        <div id="content">
            <h1>CLOSED APPLICATION</h1>
            <table>
                <tr>
                    <th>FILE NO</th>
                    <th>NAME</th>
                    <th>PHONE</th>
                </tr>
                <?php
                include 'connection.php';

                $sql = "SELECT * FROM closed;";
                $result = mysqli_query($con, $sql);

                while($row=mysqli_fetch_assoc($result)){ ?>
                    <tr>
                        <form method="get">
                            <td><?php echo $row["SNO"]; ?></td>
                            <td><?php echo $row["NAME"]; ?></td>
                            <td><?php echo $row["PHONE"]; ?></td>
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