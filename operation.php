<?php
    include 'connection.php';

    if (isset($_GET['deleteId'])) {
        echo '<title>Delete</title>';
        $delId = $_GET['deleteId'];

        $deleteRoom = $conn->prepare("DELETE FROM chartooms WHERE id = ?");

        if ($deleteRoom->execute([$delId])) {
        ?>
            <script>
                alert("Room can deleted successfully");
                window.location.replace("http://localhost/viral/chatroom/ownchatroom.php");
            </script>
        <?php
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body bgcolor="beige">
    <!-- Detail of the room -->
    <?php
        if (isset($_GET['editId'])) {

            $idEdit = $_GET['editId'];

            $dataOfRoom = $conn->prepare("SELECT * FROM chartooms WHERE id = ?");
            $dataOfRoom->execute([$idEdit]);

            $roomData = $dataOfRoom->fetch(); 
        }
    ?>
    <center>
        <div class="container-editRoom">
            <br>
            <h3 class="title">Edit Here.....</h3>
            <p class="error" id="room-Err"></p>
            <form method="post">
                <input type="text" name="roomName" placeholder="Room Name" class="box" value="<?php echo $roomData['roomName']; ?>" required>
                <input type="text" name="roomPassword" placeholder="Password" class="box" value="<?php echo $roomData['roomPassword']; ?>" required>
                <button type="submit" name="updateRoom" class="btn">Update Room</button>
            </form>
            <p class="lastLine">Back to <a href="http://localhost/viral/chatroom/ownchatroom.php">Home ?</a></p>
        </div>
    </center>

    <?php
        if (isset($_POST['updateRoom'])) {
            $roomName = $_POST['roomName'];
            $roomPassword = $_POST['roomPassword'];

            $checkRoomAvailable = $conn->prepare("SELECT * FROM chartooms WHERE roomName = ?");
            $checkRoomAvailable->execute([$roomName]);

            if ($checkRoomAvailable->rowCount() == 0) {
                if (strlen($roomName) >= 3 && strlen($roomName) <= 8) {
                    if (strlen($roomPassword) >= 3 && strlen($roomPassword) <= 8) {
                        $updateRoom = $conn->prepare("UPDATE chartooms SET roomName = ?, roomPassword = ? WHERE id = ?");

                        if ($updateRoom->execute([$roomName, $roomPassword, $idEdit])) {
                        ?>
                            <script>
                                alert('Your room can updated successfully.........');
                                window.location.replace('http://localhost/viral/chatroom/ownchatroom.php');
                            </script>
                        <?php
                        }
                    } else {
                    ?>
                        <script>
                            document.getElementById('room-Err').innerHTML = 'Please Enter the Password greter than 2 and less than 9';
                        </script>
                    <?php
                    }
                    
                } else {
                ?>
                    <script>
                        document.getElementById('room-Err').innerHTML = 'Please Enter the room name greter than 2 and less than 9';
                    </script>
                <?php
                }
                
            } else {
            ?>
                <script>
                    document.getElementById('room-Err').innerHTML = 'Please enter the another room name this room will be exist';
                </script>
            <?php
            }
        }
    ?>
</body>
</html>