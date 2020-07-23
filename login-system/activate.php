<?php
    require '../connection.php';

    session_start();

    if (isset($_GET['token'])) {
        
        $token = $_GET['token'];

        $updateStatus = $conn->prepare("UPDATE signup SET account_status = 'active' WHERE token = ?");

        if ($updateStatus->execute([$token])) {
            $_SESSION['account-msg'] = 'Please enter your username and password';
            header('location: http://localhost/viral/chatroom/login-system/login.php');
        }
    }
?>