<?php
    session_start();
    include 'connection.php';

    $selectData = $conn->prepare("SELECT * FROM signup WHERE email_id = ?");
    $selectData->execute([$_SESSION['email']]);

    $dattOfMsg = $selectData->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatroom - <?php if(isset($_SESSION['roomName'])) { echo $_SESSION['roomName']; } ?></title>
    <link rel="icon" href="images/icon.png" type="image/gif" sizes="10x10">
    <link rel="stylesheet" href="style.css">
    <!-- Fontawsome icon link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>

    <!-- Scroll down script link -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 

</head>
<body bgcolor="beige">
    <h3 class="title">Chat Here :)</h3>
    <!-- Msg data coding -->
    <?php
        $allMsg = $conn->prepare("SELECT * FROM chatmsg WHERE room_name= ?");
        $allMsg->execute([$_SESSION['roomName']]);

        // $msgVar = $allMsg->fetch();
    ?>
    <center>
        <div class="container-chatMsg" id="repeat">
            <div class="child" id="repeat-child"></div>
        </div>
        <input type="text" name="userMsg" id="userMsg" class="sendMsg" placeholder="Write a message....." >
        <button type="button" name="submitMsg" id="submitMsg" class="sendMsgIcon"><i class="fa fa-fast-forward" aria-hidden="true" style="font-size: 20px;"></i></button>
    </center>
    <script src="http://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    
    <script type="text/javascript">

        //This is a scoroll down effect start 
        // $(document).ready(function() {
		// 	$("#btn").click(function() {
		// 		$(document).ready(function () {
		// 			$('#new').scrollTop($(document).height());
		// 		});
		// 	});
		// });

        // $(function(){
		// 	$('#submitMsg').click(function(){
		// 		$('html, body').animate(
		// 		{
		// 			scrollTop:$('#new').offset().top
		// 		},
		// 		'slow'
		// 		)
		// 	})
		// })
        //This is a scoroll down effect end 

        let input = document.getElementById("userMsg");
        input.addEventListener("keyup", function(event) {
            runFunction();
            if (event.keyCode === 13) {
                event.preventDefault();
                document.getElementById("submitMsg").click();
            }
        });

        $('#submitMsg').click(function(){
            let clientmsg = $('#userMsg').val();
            let len = clientmsg.length;
            console.log(clientmsg);
            if(len > 0) {
                $.post("postmsg.php", {text: clientmsg, room: '<?php echo $_SESSION['roomName']; ?>', email: '<?php echo $dattOfMsg['email_id']; ?>', first_name: '<?php echo $dattOfMsg['first_name']; ?>'},
                function(data, status) { 
                    document.getElementsByClassName('child')[0].innerHTML = data;});
                    $('#userMsg').val('');
                    return false;
            }
        });

        setInterval(runFunction, 10);
        function runFunction() {
            console.log('This is a called');
            $.post('htcont.php', {room : '<?php echo $_SESSION['roomName']; ?>'},
                function(data, status) {
                console.log('This is a function');
                document.getElementsByClassName('child')[0].innerHTML = data;
                console.log(data);
            });
        }
        
    </script>
    <a href="http://localhost/viral/chatroom/loginchatrom.php"><button class="btn logout">Logout</button></a>
</body>
</html>