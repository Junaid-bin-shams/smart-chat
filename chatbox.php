<?php
session_start();
include("databaseConnection.php");
if(!isset($_SESSION["userId"])){
    header("location:index.php");
}

if(isset($_GET["userId"])){
    $toid = $_GET["userId"];
} else {
    $toid = 0;
}
$fromid = $_SESSION["userId"];

$userFrom= mysqli_query($connect,"SELECT * FROM users WHERE id='".$fromid."' ")
        or die("Failed to query database" .mysql_error());
        $rowFromUser= mysqli_fetch_assoc($userFrom);
$userTo= mysqli_query($connect,"SELECT * FROM users WHERE id='".$toid."' ")
        or die("Failed to query database" .mysql_error());
        $rowToUser= mysqli_fetch_assoc($userTo);
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">

<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="bootstrap/dist/js/bootstrap.min.js"></script>

</head>
<body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                   <h3> <p> Send Massage to:</p></h3>
                    <ul>
                        <?php
                            $msgs =  mysqli_query($connect,"SELECT * FROM users")
                                or die("Failed to query database".mysql_error());
                                while($msg=mysqli_fetch_assoc($msgs))
                                {
                                    if($msg["id"]!=$fromid)
                                    echo '<li> <a href="chatbox.php?userId='.$msg["id"].'">'.$msg["name"].'</a></li>';
                                }

                        ?>
                    </ul>
                   <h3> <p>Current User: <?php echo $rowFromUser['name']; ?></p></h3>
                    <a href="index.php"><button type="button" class="btn btn-primary">Back</button>
</a>
                    </div>
                    <div class="col-md-4">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>
                                <?php
                                    if(isset($_SESSION["userId"]))
                                    {
                                        
                                       // echo'<input type="text" value="'.$toid.'" id="toUser" hidden/>';
                                        echo $rowToUser["name"];
                                     }
                                    
                                ?>
                                </h4>
                    </div>
                    <div class="modal-body" id="msgBody" style="height:400px;overflow-y: scroll; overflow-x: hidden; ">
                    <?php
                        if(isset($_SESSION["userId"])){
                        //echo "SELECT * FROM massgaes WHERE (fromUser='".$fromid."' AND toUser='".$toid."' OR fromUser='".$toid."' AND toUser='".$fromid."')";
                        $chats=mysqli_query($connect,"SELECT * FROM messages WHERE (fromUser='".$fromid."' AND toUser='".$toid."' OR fromUser='".$toid."' AND toUser='".$fromid."')")
                        or die("Failed to query Database".mysqli_error());
                        
                        while($chat=mysqli_fetch_assoc($chats))
                        { 
                            $my_date = $chat["date_time"]; 

                            $current_date = date('Y-m-d H:i:s'); 
                            
                            $diff = abs(strtotime($current_date) - strtotime($my_date)); 
                            
                            $years   = floor($diff / (365*60*60*24)); 
                            $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
                            $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                            
                            $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
                            
                            $minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
                            
                            $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60)); 
                            if($my_date=$months)
                                {
                                    $mydt = $months." months ago";
                                }elseif($my_date=$days)
                                {
                                    $mydt = $days." days ago";
                                }elseif($my_date=$hours)
                                {
                                    $mydt = $hours." hours ago";
                                }elseif($my_date=$minuts)
                                {
                                    $mydt = $minuts." minuts ago";
                                }elseif($my_date=$seconds)
                                {
                                    $mydt = $seconds." seconds ago";
                                }

                            if($chat["image"]!="")
                            {
                                $imgscr = '<img src="../smartChat/uploads/'.$chat["image"].'" height="200">';
                            }
                            else{
                                $imgscr='';
                            }

                            if($chat["created_by"]==$fromid){
                                echo '<div class="msguser" style="text-align:right;">
                                <p style="background-color:lightblue;word-wrap:break-word;display:inline-block;padding:5px;border-redius:10px;max width:70%;">
                                 '.$imgscr.$chat["message"].'<br><small>'.$mydt.'</small>
                                </p>
                                </div>';

                            } else {
                            //if($chat["created_by"]==$toid){
                                echo '<div class="msguser" style="text-align:left;">
                                <p style="background-color:silver;word-wrap:break-word;display:inline-block;padding:5px;border-redius:10px;max width:70%;">
                                '.$imgscr.$chat["message"].'<br><small>'.$mydt.'</small>
                                </p>
                                </div>';
     
                            }
                        }
                    }

  
                    ?>
                </div>
                <div class="modal-footer">
                <form id="chatForm" method="post" enctype="multipart/form-data">
                <input type="file" id="uploads" name="uploads"/>
                        <textarea id="message" name="message" class="form-control" style="height:70px;"></textarea>                        
                        <input type="text" id="fromUser" name="fromUser" value="<?php echo $fromid; ?>" hidden/>                        
                        <input type="text" id="toUser" name="toUser" value="<?php echo $toid; ?>" hidden/>
                        <button type="button" id="send" class="btn btn-primary" style="height:70%;">send</button>
                        </form>
            </div>
        </div>
  </div>
    </div>
                    <div class="col-md-4">
                    </div>
                </div>
            </div>
</body>
<script type="text/javascript">
    var scrolled=0;
    $(document).ready(function(){
        scrollMe();
        $(document).on("click","#send", function(){
            if ($('#message').val()=="" && $('#uploads').val()=="")
        { $('#message').focus(); $('#message').addClass('border-danger'); return false; }
  
            var form = $('#chatForm')[0]; // Get form			
            var data = new FormData(form); // Create an FormData object 
            $.ajax({
                type: 'POST',
                datatype: 'json',
                url:'insertMessage.php',
               
                enctype: 'multipart/form-data',
                contentType: false,
                cache: false,
                processData: false,
                
                data:data,
                
                success:function(data)
                {
                    console.log('file message: ',data.file);
                    console.log('query output: ',data.output);
                    // location.reload();	
                    $('#message').val('');
                    $('#uploads').val('');			

                    $.ajax({
                        url:"realTimeChat.php",
                        method:'POST',
                        data:{
                            fromUser:$("#fromUser").val(),
                            toUser:$("#toUser").val(),
                        },
                        datatype:"text",
                        success:function(data)
                        {
                            $("$msgBody").html(data);
                            scrollMe();
                        }
                    });
                }
            });
        });

        setInterval(function(){
            $.ajax({
                url:"realTimeChat.php",
                method:"POST",
                data:{
                    fromUser:$("#fromUser").val(),
                    toUser:$("#toUser").val(),
                },
                datatype:"text",
                success:function(data)
               {
                   //console.log(data);
                   $("#msgBody").html(data);
               }
            });
        }, 1000);
    });

    function scrollMe()
    {
       $("#msgBody .msguser").each(function(){
           scrolled = scrolled + $(this).height();
       });

       $("#msgBody").animate({
           scrollTop: scrolled
       });
       console.log(scrolled);
    }
</script>
</html>