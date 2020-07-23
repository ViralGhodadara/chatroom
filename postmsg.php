<?php
    include 'connection.php';

    if (isset($_POST['text']) && isset($_POST['room']) && isset($_POST['email']) && isset($_POST['first_name'])) {
        $msg = $_POST['text'];
        $roomName = $_POST['room'];
        $email = $_POST['email'];
        $firstName = $_POST['first_name'];
    
        $insert = $conn->prepare("INSERT INTO chatmsg (msg, email, first_name, room_name) VALUES (?, ?, ?, ?)");
        $insert->execute([$msg, $email, $firstName, $roomName]);
    }

?>