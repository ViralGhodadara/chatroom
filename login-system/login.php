<?php
    require '../connection.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Here</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body bgcolor="beige">
    <br>
    <h3 class="title">Login Here</h3>
    <center>
        <div class="loginContainer">
            <form method="post">
                <br>
                <p class="error"><?php if (isset($_SESSION['account-msg'])) { echo $_SESSION['account-msg']; } ?></p>
                <br><br>
                <input type="email" name="emailid" class="box" placeholder="Email id" required><p class="error" id="email-error"></p>
                <input type="password" name="password" class="box" placeholder="Password" required><p class="error" id="password-error"></p>
                <button type="submit" name="submitLoginDetail" class="btn">Login</button>
            </form>
            <p class="lastLine">Back to <a href="http://localhost/viral/chatroom/">Home ?</a></p>
        </div>
    </center>
</body>
</html>
<?php
    if (isset($_POST['submitLoginDetail'])) {
        $username = $_POST['emailid'];
        $password = $_POST['password'];

        $checkEmail = $conn->prepare("SELECT * FROM signup WHERE email_id = ?");

        $checkEmail->execute([$username]);

        if ($checkEmail->rowCount() == 1) {
            $data = $checkEmail->fetch();

            if ($data['account_status'] == 'active') {
                if (password_verify($password, $data['user_password']) == true) {
                    $_SESSION['first_name'] = $data['first_name'];
                    $_SESSION['email'] = $username;
                ?>
                    <script>
                        alert("Your detail can verify.......");
                        window.location.replace('../loginchatrom.php');
                    </script>
                <?php
                } else {
                    $_SESSION['account-msg'] = "Please enter the valid password";
                }
            } else {
                $_SESSION['account-msg'] = "Please verify your account";
            }
        } else {
            $_SESSION['account-msg'] = "Please enter the valid email id";
        }
    }

    // session_end();
?>