<!DOCTYPE html>
<html lang="en">
<?php session_start() ?>
<?php 
	if(!isset($_SESSION['login_id']))
	    header('location:login.php');
    include 'db_connect.php';
    ob_start();
    if(!isset($_SESSION['system'])){
        $system = $conn->query("SELECT * FROM system_settings")->fetch_array();
        foreach($system as $k => $v){
        $_SESSION['system'][$k] = $v;
        }
    }
    ob_end_flush();
    // Header
	include 'header.php' 
?>
<body>
    <div class="container">
        <h1>Upload file</h1>
        <form action="uploadfile.php" method="post">
            <label for="name">File URL</label>
            <input type="file" name="File_URL" id="">
        </form>
    </div>

    <!-- Footer -->
    <?php include 'footer.php' ?>
</body>
</html>