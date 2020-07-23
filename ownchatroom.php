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
    <!-- Fontawsome icon link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



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
                        <a href="#">Create Own ChatRoom</a> <!-- This is a require -->
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

        <div class="ownroom">
            <div class="first">
            <br>
            <h3 class="title">Create own chatroom</h3>
            <p class="error" id="error-changepass"></p>
            <form method="post">
                <input type="text" name="chatroomName" class="box" placeholder="Chatroom Name" required>
                <input type="password" name="conform_password" class="box" placeholder="Password" required>
                <button type="submit" name="changepass" class="btn">Change Password</button>
            </form>
        </div>
        <?php

            if (isset($_POST['changepass'])) {
                $chatroomName = $_POST['chatroomName'];
                $pass = $_POST['conform_password'];
                
                $checkRoom = $conn->prepare("SELECT * FROM chartooms WHERE roomName LIKE ?");

                $checkRoom->execute([$chatroomName]);

                if ($checkRoom->rowCount() == 0) {
                    if (strlen($pass) >= 3 && strlen($pass) <= 8) {
                        // $passHash = password_hash($pass, PASSWORD_BCRYPT);

                        if (strlen($chatroomName) >= 3 && strlen($chatroomName) <= 8) {
                            $insertRoom = $conn->prepare("INSERT INTO chartooms (roomName, roomPassword	, adminName, email_id) VALUES (?, ?, ?, ?)");

                            if ($insertRoom->execute([$chatroomName, $pass, $dataOfUser['first_name'], $dataOfUser['email_id']])) {
                            ?>
                                <script>
                                    alert("You chatroom can successfully created..");
                                </script>
                            <?php
                            }
                        } else {
                        ?>
                            <script>
                                document.getElementById('error-changepass').innerHTML = "Please enter the Room name less than 9 and greter than 2";
                            </script>
                        <?php        
                        }
                    } else {
                    ?>
                        <script>
                            document.getElementById('error-changepass').innerHTML = "Please enter the password less than 9 and greter than 2";
                        </script>
                    <?php    
                    }
                } else {
                ?>
                    <script>
                        document.getElementById('error-changepass').innerHTML = "Please enter another name this name will exists";
                    </script>
                <?php
                }
            }
        ?>
            <div class="second" style="overflow: scroll;">
                <!-- My rooms data -->
                <?php
                    $myRooms = $conn->prepare("SELECT * FROM chartooms WHERE email_id = ?");
                    $myRooms->execute([$dataOfUser['email_id']]);

                    // echo $myRooms->rowCount();
                ?>
                <br>
                <h3 class="title">My Rooms</h3>
                <p class="error" id="roomsErr"></p>
                <div>
                    <table class="tbl">
                        <thead>
                            <tr class="heading">
                                <td class="table-title">ROOM NAME</td>
                                <td class="table-title">PASSWORD</td>
                                <td colspan="2" class="table-title">OPERATION</td>
                            </tr>
                        </thead>
                        <?php
                            if ($myRooms->rowCount() > 0) {
                                while ($room = $myRooms->fetch()) {
                                ?>
                                    <tr>
                                        <td><?php echo $room['roomName'] ?></td>
                                        <td><?php echo $room['roomPassword'] ?></td>
                                        <td style="text-align: center;">
                                            <a href="operation.php?editId=<?php echo $room['id'] ?>" title="EDIT" style="color: red;">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="operation.php?deleteId=<?php echo $room['id'] ?>" title="DELETE" style="color: green;">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </td>

                                        
                                    </tr>
                                <?php
                                }
                            ?>
                            <?php
                            } else {
                            ?>
                                <script>
                                    document.getElementById('roomsErr').innerHTML = "No Rooms available";
                                </script>
                            <?php
                            }
                        ?>
                    </table>
                </div>
            </div>

        </div>
            
    </center>

</body>
</html>