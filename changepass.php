<?php
    session_start();

    include 'connection.php';

    $userDetail = "SELECT * FROM signup WHERE email_id = ?";
    $statUserDetail = $conn->prepare($userDetail);
    $statUserDetail->execute([$_SESSION['email']]);

    $dataOfUser = $statUserDetail->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin panel - <?php echo $_SESSION['first_name']; ?></title>
    <link rel="stylesheet" href="style.css">

</head>
<body style="margin: 0; padding: 0; background: beige">
    <header>
        <nav>   
            <div class="nav-div">
                <ul>
                    <li>
                        <a href="loginchatrom.php">Login ChatRoom</a>
                    </li>
                    <li>
                        <a href="ownchatroom.php">Create Own ChatRoom</a>
                    </li>
                    <li>
                        <a href="editdetail.php">Edit Detail</a>
                    </li>
                    <li>
                        <a href="#">Change Password</a> <!-- This is a require -->
                    </li>
                    <li>
                        <a href="http://localhost/viral/chatroom/">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <center>

        <div class="changePassword">
            <br>
            <h3 class="title">Change Password</h3>
            <p class="error" id="error-changepass"></p>
            <form method="post">
                <input type="password" name="password" class="box" placeholder="Password" required>
                <input type="password" name="conform_password" class="box" placeholder="Conform Password" required>
                <button type="submit" name="changepass" class="btn">Change Password</button>
            </form>
        </div>
        <?php
            if (isset($_POST['changepass'])) {
                $pass = $_POST['password'];
                $cpass = $_POST['conform_password'];

                $hashPass = password_hash($pass, PASSWORD_BCRYPT);
                $hashCpass = password_hash($cpass, PASSWORD_BCRYPT);
                
                if ($pass === $cpass) {

                    if (strlen($pass) >=3 && strlen($pass) <= 8) {
                        
                        $updatePass = $conn->prepare("UPDATE signup SET user_password = ?, conform_password = ? WHERE id = ?");

                        if ($updatePass->execute([$hashPass, $hashCpass, $dataOfUser['id']])) {
                        ?>
                            <script>
                                alert("Password can change successfully......");
                            </script>
                        <?php
                        } 
                    } else {
                    ?>
                        <script>
                            document.getElementById('error-changepass').innerHTML = 'Please enter the password and conform password length greter than 2 and less than 9';
                        </script>
                    <?php        
                    }
                } else {
                ?>
                    <script>
                        document.getElementById('error-changepass').innerHTML = 'Please enter the same password and conform password';
                    </script>
                <?php
                }
            }
        ?>

    </center>
</body>
</html>