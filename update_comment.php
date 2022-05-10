<?php
// fetch table
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM project_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
//fetch.php;
if(isset($_POST["view"]))
{
include("db_connect.php");
if($_POST["view"] != '')
{
$update_query = "UPDATE task_list SET active=1 WHERE active=0";
mysqli_query($conn, $update_query);
}

 $query = "SELECT * FROM task_list t INNER JOIN users u ON t.user_id = u.id WHERE t.user_id = 4";
 $result = mysqli_query($conn, $query);
 $output = '';
 
if(mysqli_num_rows($result) > 0)
{
while($row = mysqli_fetch_array($result))
{
$output .= '
<a class="dropdown-item" href="javascript:void(0)" id="notified_list" style="y-overflow:auto">
    <i class="fa fa-tasks"></i> <strong>'.$row["task"].'</strong><br/>
    <i class="fa fa-dialog"></i> <em>'.$row["description"].'</em><br/>
    <button class="btn btn-success btn-xs mr-2 notifMeAccept" id="notif_accept">Accept</button>
    <button class="btn btn-default btn-xs notifMeDecline" id="notif_decline">Decline</button>
</a>
<div class="dropdown-divider"></div>
';
}
}
else
{
$output .= '<li class="m-auto text-center py-5 my-5" style="width:300px;font-size:20px"><a href="#" class="text-bold text-italic py-5 my-5"> No Notification Found </a></li>';
}
 
 $query_1 = "SELECT * FROM task_list WHERE active=0";
 $result_1 = mysqli_query($conn, $query_1);
 $count = mysqli_num_rows($result_1);
 $data = array(
  'notification'   => $output,
  'unseen_notification' => $count
 );
 echo json_encode($data);
}
?>