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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 

</head>
<body bgcolor="beige">
    <h3 class="title" style="margin-bottom: 5px;">Chat Here - <?php echo $_SESSION['roomName']; ?>  :)</h3>
    <!-- Msg data coding -->
    <?php
        $allMsg = $conn->prepare("SELECT * FROM chatmsg WHERE room_name= ?");
        $allMsg->execute([$_SESSION['roomName']]);

        function del() {
            echo "Hellow ";
        }

        // $msgVar = $allMsg->fetch();
    ?>
    <center>
        <!-- <p class="roominfo"><span style="color: black; text-decoration: underline; font-weight: bold; font-size: 25px;"><?php echo $dattOfMsg['first_name']. " " .$dattOfMsg['last_name']; ?></span> please message here and enjoy it thank you.......</p> -->
        <button class="btn logout"><a href="http://localhost/viral/chatroom/loginchatrom.php">Logout</a></button>

    </center>
    <div class="main">
        <div class="main-child">
            <p class="title">New Message</p>
            <div class="container-chatMsg" id="repeat">
                <div class="child"></div>
            </div>
            <input type="text" name="userMsg" id="userMsg" class="sendMsg" placeholder="Write a message here....." >
            <button type="button" name="submitMsg" id="submitMsg" class="sendMsgIcon"><i class="fa fa-fast-forward" aria-hidden="true" style="font-size: 20px;"></i></button>
        </div>
        <div class="main-child">
            <p class="title">Old Message</p>
            <div class="container-chatMsg">
                <div class="child">
                    <?php
                            while ($msgAll = $allMsg->fetch()) {
                                if ($msgAll['email'] == $_SESSION['email']) {
                                ?>
                                    <div class="container-myMsg">
                                        <p><?php echo $msgAll['msg']; ?></p>
                                        <br>
                                        <p><?php echo $msgAll['currentdateandtime']; ?> <a href="operation.php?deleteMsgId=<?php echo $msgAll['id']; ?>" class="deleteMsgIcon"><i class="fa fa-trash" aria-hidden="true"></i></a> </p>
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <div class="container-anotherUser">
                                        <p><?php echo $msgAll['msg']; ?></p>
                                        <br>
                                        <p><?php echo $msgAll['currentdateandtime']; ?> </p>
                                    </div>
                                <?php
                                }
                            }
                        
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="http://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    
    <script type="text/javascript">

    // gotoBottom();

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
        // var objDiv = $(".container-chatMsg");
    	// var h = objDiv.get(0).scrollHeight;
    	// objDiv.animate({scrollTop: h});
        

        function gotoBottom() {
            var element = document.getElementById('repeat');
            console.log("This is a scroll Height : "+element.scrollHeight);
            console.log("This is a client scroll Height : "+element.clientHeight);
            element.scrollTop = element.scrollHeight - element.clientHeight;
            // console.log("This is a print : "+element.scrollTop);
        }

        //This is a scoroll down effect end 

        // new code for scroll start

        // new code for scroll ends

        // when user click the enter so button was clickd
        let input = document.getElementById("userMsg");
        input.addEventListener("keyup", function(event) {
            runFunction();
            if (event.keyCode === 13) {
                event.preventDefault();
                gotoBottom();
                document.getElementById("submitMsg").click();
            }
        });

        $('#submitMsg').click(function(){
            // gotoBottom();
            let clientmsg = $('#userMsg').val();
            let len = clientmsg.length;
            console.log(clientmsg);
            if(len > 0) {
                $.post("postmsg.php", {text: clientmsg, room: '<?php echo $_SESSION['roomName']; ?>', email: '<?php echo $dattOfMsg['email_id']; ?>', first_name: '<?php echo $dattOfMsg['first_name']; ?>'},
                function(data, status) { 
                    document.getElementsByClassName('child')[0].innerHTML = data;
                    gotoBottom();
                });
                $('#userMsg').val('');
                gotoBottom();
                return false;
            }
        });

        setInterval(runFunction, 0.0000001);

        function runFunction() {
            // console.log('This is a called');
            $.post('htcont.php', {room : '<?php echo $_SESSION['roomName']; ?>'},
                function(data, status) {
                // console.log('This is a function');
                document.getElementsByClassName('child')[0].innerHTML = data;
                gotoBottom();
                // console.log(data);
            });
        }
        
    </script>
    

    <!-- <script src="https://unpkg.com/vue@2.6.11/dist/vue.js"></script> -->
</body>
</html>
<?php
// function setInterval($f, $milliseconds)
// {
//     $seconds=(int)$milliseconds/1000;
//     while(true)
//     {
//         $f();
//         sleep($seconds);
//     }
// }
// setInterval(function(){
//     echo "hi!\n";
// }, 1000);
?>
