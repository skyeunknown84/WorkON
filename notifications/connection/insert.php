<?php
    include('DB.php');
    $notifications_name =  $_POST["notifications_name"];
    $message            =  $_POST["message"];

    $insert_query = "INSERT INTO inf(notifications_name,message,active)VALUES('".$notifications_name."','".$message."','1')";
    
    $result = mysqli_query($connection,$insert_query);
    
?>