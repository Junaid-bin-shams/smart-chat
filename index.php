<?php
session_start();
include("databaseConnection.php");
if(isset($_GET["userId"]))
{
    //$_SESSION["userId"]=$_GET["userId"]; //here session hasbeen created
    header("location:chatbox.php");  //here session goes to chatbox.php
}

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

<script type="text/javascript">
    $('#group_chat').click(function(){
        $('group_chat_dialog').dialog('open');
        $('#is_active_group_chat_window').val('yes');
        fetch_group_chat_history();
    });

    $('#send_group_chat').click(function(){
        var chat_message=$('group_chat_message').val();
        var action='insert_data';
        if(chat_message!='')
        {
            $.ajax({
                url:"group_chat.php",
                method:"POST",
                data:{chat_message:chat_message, action:action},//action variable value has been send to server
                success:function(data){
                    $('#group_chat_message').val('');
                    $('#group_chat_history').html(data);
                }
            })
        }
    });

   
</script>
        <div class="modal-dialog d-none">
            <div class="modal-content">
                <div class="modal-header">
                    <h4> Please Select Your Account</h4>
                </div>
                <div class="modal-body">
               <ol>
                    <?php
                        $users = mysqli_query($connect,"SELECT * FROM users")
                        or die("Failed to query database".mysql_error());
                        while($user=mysqli_fetch_assoc($users))
                        {
                            echo'<li>
                            <a href="index.php?userId='.$user["id"].'">'.$user["name"].'</a>
                            </li>
                            ';
                        }
                    ?>
                    </ol>
                    <a href="registerUser.php" style="float:right;">Register Here..</a>
                </div>
            </div>
        </div>
<!-- here we check login condition if id is set so it will go on chatbox otherwise it comes on index -->
<?php if(isset($_SESSION["userId"])){ ?> 
    <a href="chatbox.php" style="float:center;"><button type="button" class="btn btn-secondary">ChatBox</button>
</a><br>
    <a href="function.php?action=logout" style="float:center;"><button type="button" class="btn btn-danger">Logout</button>
</a>
<?php } else { ?>
    <div class="row">
<div class="col-sm-6">
<div id="login">
        <h3 class="text-center text-white pt-5">Login form</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-12">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="function.php" method="post">
                            <h3 class="text-center text-info">Login</h3>
                            <div class="form-group">
                                <label for="email" class="text-info">Email:</label><br>
                                <input type="text" name="email" id="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="text" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="action" value="login">
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>
</body>
</html>