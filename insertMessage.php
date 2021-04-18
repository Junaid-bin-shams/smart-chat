<?php
    session_start();
    include("databaseConnection.php");

    $fromUser=$_POST["fromUser"];
    $toUser=$_POST["toUser"];
    $message=$_POST["message"];
    $date_time= date("Y-m-d H:i"); 
    $tmpname = $_FILES["uploads"]["tmp_name"];

    $fileName = $_FILES["uploads"]["name"]; 
    $targetFilePath = '../smartChat/uploads/' . $fileName;

    if(move_uploaded_file($tmpname,$targetFilePath)) {
        $result['file'] = 'file upload successfully';
    } else {
        $result['file'] = 'error, something went wrong';
    }

    $sql="INSERT INTO messages (created_by,fromUser,toUser,message,image,date_time) VALUES ('$fromUser','$fromUser','$toUser','$message','$fileName','$date_time')";
    //exit();
    if($connect->query($sql))
    {
        $result['output']= 'data added successfully done' ; 
    }
    else
    {
        $result['output']="Error. please try again.";
    }

    $myJSON = json_encode($result);
    //print"<pre>"; print_r($myJSON); print"</pre>";
    echo $myJSON;

?>