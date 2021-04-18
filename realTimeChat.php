
<?php
include("databaseConnection.php");

    $fromUser=$_POST["fromUser"];
    $toUser=$_POST["toUser"];
    $output=""; 

    $chats=mysqli_query($connect,"SELECT * FROM messages WHERE (fromUser='".$fromUser."' AND
    ToUser='".$toUser."') OR (fromUser='".$toUser."' AND ToUser='".$fromUser."')")
     or die("Failed to query Database".mysql_error());


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
      
        if($chat["image"]!=""){
            $imgscr = '<img src="../smartChat/uploads/'.$chat["image"].'" height="150">';
      } else {
            $imgscr = '';
      }
      if($chat["created_by"]==$fromUser){
      echo '<div class="msguser" style="text-align:right;">
      <p style="background-color:lightblue;word-wrap:break-word;display:inline-block;padding:5px;border-redius:10px;max width:70%;">
      '.$imgscr.$chat["message"].' <br>'.$mydt.'
      </p>
      </div>';

      }else{
      echo '<div class="msguser" style="text-align:left;">
      <p style="background-color:silver;word-wrap:break-word;display:inline-block;padding:5px;border-redius:10px;max width:70%;">
      '.$imgscr.$chat["message"].' <br>'.$mydt.'
      </p>
      </div>';
      }
     }
     echo $output;

?>
