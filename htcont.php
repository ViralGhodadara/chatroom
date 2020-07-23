<style>
.para {
    color: brown;
    font-family: verdana;
    margin-top: 50px;
}
</style>
<?php
    require "connection.php";

    session_start();

    $_SESSION['email'];

    $room = $_POST['room'];

    $data = $conn->prepare("SELECT * FROM chatmsg WHERE room_name = ?");
    $data->execute([$room]);

    $dataMsg = '';

    if ($data->rowCount() > 0) {
        while ($myData = $data->fetch()) {

            if ($myData['email'] == $_SESSION['email']) {

                $dataMsg = $dataMsg . '<div class="container-myMsg">';
                $dataMsg = $dataMsg . $myData["msg"];
                $dataMsg = $dataMsg . '<br>';
                $dataMsg = $dataMsg . '<br>';
                $dataMsg = $dataMsg . $myData["currentdateandtime"];
                $dataMsg = $dataMsg . '</div>';
                } else {
                
                $dataMsg = $dataMsg . "<div class='container-anotherUser'>";
                $dataMsg = $dataMsg . $myData["first_name"]." : ".$myData["msg"];
                $dataMsg = $dataMsg . '<br>';
                $dataMsg = $dataMsg . '<br>';
                $dataMsg = $dataMsg . $myData["currentdateandtime"];;
                $dataMsg = $dataMsg . '</div>';   
                }

        }
        echo $dataMsg;
    } else {
        echo '<p class="para">No Message available......</p>';
    }
?>


