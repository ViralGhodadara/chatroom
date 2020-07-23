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
                        <a href="#">Login ChatRoom</a> <!---- This is a require -->
                    </li>
                    <li>
                        <a href="ownchatroom.php">Create Own ChatRoom</a>
                    </li>
                    <li>
                        <a href="editdetail.php">Edit Detail</a>
                    </li>
                    <li>
                        <a href="changepass.php">Change Password</a>
                    </li>
                    <li>
                        <a href="http://localhost/viral/chatroom/">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <center>

        <div id="loginChatroom" class="loginChatRoom"> 
            <div class="container-loginChatroom">
                <br>
                <h3 class="title">Login ChatRoom</h3>
                <p class="error" id="roomLogin-err"></p>
                <form method="post">
                    <input type="text" name="roomName" placeholder="RoomName" class="box" required><br>
                    <input type="password" name="roomPassoword" class="box" placeholder="Password" required>
                    <button class="btn" name="loginChatRoom" type="submit">Chatroom</button>
                </form>
            </div>
        </div>

        <?php
            if (isset($_POST['loginChatRoom'])) {
                $roomName = $_POST['roomName'];
                $roomPassword = $_POST['roomPassoword'];

                $checkData = $conn->prepare("SELECT * FROM chartooms WHERE roomName = ? AND roomPassword = ?");
                $checkData->execute([$roomName, $roomPassword]); 

                if ($checkData->rowCount() == 1) {
                    $_SESSION['roomName'] = $roomName;
                    $_SESSION['emailId'] = $dataOfUser['emaild_id'];
                ?>
                    <script>
                        alert('You can message it');
                        window.location.replace('http://localhost/viral/chatroom/chatmsg.php');
                    </script>
                <?php                   
                } else {
                ?>
                    <script>
                        document.getElementById('roomLogin-err').innerHTML = "Please enter the valid room name and password";
                    </script>
                <?php
                }
                
            }
        ?>
        
    </center>
</body>
</html>