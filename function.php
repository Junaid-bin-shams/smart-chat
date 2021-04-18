<?php
session_start();
include("databaseConnection.php");
if($_POST["action"]=="register") {
     $sql="INSERT INTO users (name,email,contact,password) VALUES ('".$_POST["name"]."','".$_POST["email"]."','".$_POST["contact"]."','".$_POST["password"]."')";
    if($connect->query($sql)){
     echo 'success';
    }else
    {
        echo 'error';
    }
     
} else if($_POST["action"]=="login") {
    $users=mysqli_query($connect,"SELECT * FROM users WHERE email='".$_POST['email']."' AND password='".$_POST['password']."'");
    if(mysqli_num_rows($users)>0){
        $row=mysqli_fetch_assoc($users);
        $_SESSION["userId"]=$row["id"]; //here session hasbeen created
        $_SESSION["name"]=$row["name"];
        $_SESSION["email"]=$row["email"];
        $_SESSION["contact"]=$row["contact"];
        print_r($row);
        header("location:chatbox.php");
    }else
    {
        echo 'error';
    }   

} else if($_GET["action"]=="logout") {
    session_unset();
    session_destroy();
    header("Location:index.php");
} else {
    //
}

