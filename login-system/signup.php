<?php
    session_start();
    include "../connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an account</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body bgcolor="beige">
    <h3 class="title">Create an account</h3>
    <center>
        <div class="container">
            <form method="post">
                <br>
                <input type="email" name="email" placeholder="Email id" class="box" required><p id="error-email" class="error"></p>
                <input type="text" name="fname" placeholder="First Name" class="box" required><p class="error" id="error-fname"></p>
                <input type="text" name="mname" placeholder="Middle Name" class="box" required><p class="error" id="error-mname"></p>
                <input type="text" name="lname" placeholder="Last Name" class="box" required><br><p class="error" id="error-lname"></p>
                <input type="date" name="dob" class="box" placeholder="Date of Birth" required><br>
                <input type="number" name="contact_number" placeholder="Contact Number" class="box" required><p class="error" id="error-contactNumber"></p>
                <select name="gender" class="dropdown" required><p class="error"></p>
                    <option value="">Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <input type="password" name="password" class="box" placeholder="Password" required><p class="error" id="password-error"></p>
                <input type="password" name="conform_password" class="box" placeholder="Conform password" required><br><p class="error" id="conformpassword-error"></p>
                <button class="btn" name="submit" type="submit">Create an account</button>
            </form>
            <p class="lastLine">Go to <a href="http://localhost/viral/chatroom/">Home ?</a></p>
        </div>
    </center>
</body>
</html>
<?php
    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $firstName = $_POST['fname'];
        $middleName = $_POST['mname'];
        $lastName = $_POST['lname'];
        $date_of_birth = $_POST['dob'];
        $contactNumber = $_POST['contact_number'];
        $gender = $_POST['gender'];
        $password = $_POST['password'];
        $conform_password = $_POST['conform_password'];

        $checkEmail = $conn->prepare("SELECT * FROM signup WHERE email_id = ?");

        if ($password === $conform_password) {
            if (strlen($password) >= 3 && strlen($password) <= 8 && strlen($conform_password) >= 3 && strlen($conform_password) <= 8) {
                if ($checkEmail->execute([$email])) {
                    if ($checkEmail->rowCount() > 0) {
                    ?>
                        <script>
                            document.getElementById('error-email').innerHTML = "*** Please Enter the another email address";
                        </script>
                    <?php
                    } else {
                        if (strlen($firstName) >= 3 && strlen($firstName) <= 20) {
                            if (strlen($middleName) >= 3 && strlen($middleName) <= 20) {
                                if (strlen($lastName) >= 3 && strlen($lastName) <= 20) {
                                    if (strlen($contactNumber) == 10) {

                                        $token = bin2hex(random_bytes(10));
                                        $userPasswordHash = password_hash($password, PASSWORD_BCRYPT);
                                        $userConformPasswordHash = password_hash($conform_password, PASSWORD_BCRYPT);
                                        $insertSql = "INSERT INTO signup (email_id, first_name, middle_name, last_name, date_of_birth, contact_number, gender, user_password, conform_password, token, account_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                        $insert = $conn->prepare($insertSql);

                                        if ($insert->execute([$email, $firstName, $middleName, $lastName, $date_of_birth, $contactNumber, $gender, $userPasswordHash, $userConformPasswordHash, $token, 'inactive'])) {
                                            $to = 'viralghodadra37@gmail.com';
                                            $title = 'Account activation .............';
                                            $msg = "Please click the link and verify your email link = http://localhost/viral/chatroom/login-system/activate.php?token=$token";
                                            
                                            $_SESSION['account-msg'] = "Please open your email and verify your account";

                                            if (mail($email, $title, $msg)) {
                                            ?>
                                                <script>
                                                    alert('Your detail submited successfully.........');
                                                    window.location.replace('http://localhost/viral/chatroom/login-system/login.php');
                                                </script>
                                            <?php
                                            }
                                        }
                                    } else {
                                    ?>
                                        <script>
                                            document.getElementById('error-contactNumber').innerHTML = "*** Please Enter valid contact number";
                                        </script>
                                    <?php       
                                    }
                                } else {
                                ?>
                                    <script>
                                        document.getElementById('error-lname').innerHTML = "*** Please Enter valid last name";
                                    </script>
                                <?php
                                }
                            } else {
                            ?>
                                <script>
                                    document.getElementById('error-mname').innerHTML = "*** Please Enter valid middle name";
                                </script>
                            <?php        
                            }
                        } else {
                        ?>
                            <script>
                                document.getElementById('error-fname').innerHTML = "*** Please Enter valid first name";
                            </script>
                        <?php
                        }
                    }
                }    
            } else {
            ?>  
            <script>
                document.getElementById('password-error').innerHTML = "*** Please enter the password gereter than 3 and less than 9";
                document.getElementById('conformpassword-error').innerHTML = "*** Please enter the conform password gereter than 3 and less than 9";
            </script>
        <?php
            }
        } else {
        ?>  
            <script>
                document.getElementById('password-error').innerHTML = "*** Please enter the same password and conform password";
                document.getElementById('conformpassword-error').innerHTML = "*** Please enter the same password and conform password";
            </script>
        <?php
        }
    }

?>