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
                        <a href="#">Edit Detail</a>
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

        <div id="detail" class="mydetail">
            <div class="container-editDetail">
                <br>
                <h3 class="title">Edit Here....</h3>
                <form method="post">
                    <input type="text" name="fname" placeholder="First Name" class="box" value="<?php echo $dataOfUser['first_name']; ?>" required><br>
                    <input type="text" name="mname" placeholder="Middle Name" class="box" value="<?php echo $dataOfUser['middle_name']; ?>" required><br>
                    <input type="text" name="lname" placeholder="Last Name" class="box" value="<?php echo $dataOfUser['last_name']; ?>" required><br>
                    <input type="number" name="contactNumber" placeholder="Contact Number" class="box" value="<?php echo $dataOfUser['contact_number']; ?>" required><br>
                    <input type="date" name="dob" placeholder="Date of Birth" class="box" value="<?php echo $dataOfUser['date_of_birth']; ?>" required><br>
                    <select name="gender" class="dropdown" value="<?php echo $dataOfUser['gender']; ?>" required><br>
                        <option value="">Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select><br>
                    <button type="submit" name="updateDetail" class="btn">Update</button>
                </form>
            </div>
            <?php
                if (isset($_POST['updateDetail'])) {
                    $fname = $_POST['fname'];
                    $mname = $_POST['mname'];
                    $lname = $_POST['lname'];
                    $contactNumber = $_POST['contactNumber'];
                    $dob = $_POST['dob'];
                    $gender = $_POST['gender'];

                    $updateDetail = $conn->prepare("UPDATE signup SET first_name = ?, middle_name = ?, last_name = ?, date_of_birth = ?, contact_number = ?, gender = ? WHERE id = ?");
                    if ($updateDetail->execute([$fname, $mname, $lname, $dob, $contactNumber, $gender, $dataOfUser['id']])) {
                    ?>
                        <script>
                            alert("Detail can edit sucessfully...........");
                        </script>
                    <?php
                    }
                }
            ?>
        </div>

    </center>
</body>
</html>