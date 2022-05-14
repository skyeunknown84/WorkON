<?php
// fetch table
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM task_list where id = ".$_GET['id'])->fetch_array();
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

 $query = "SELECT *,concat(firstname,' ',lastname) as uname,u.id as u_id FROM users u INNER JOIN task_list t ON concat(firstname,' ',lastname) = t.task_owner WHERE t.active = 1";
 $result = mysqli_query($conn, $query);
 $output = '';
 
if(mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_array($result))
    {
    $output .= '
    <a class="dropdown-item y-scroll" href="javascript:void(0)" id="notified_list" style="y-overflow:auto">
        <i class="fa fa-tasks"></i> <strong>'.$row["task"].'</strong><br/>
        <i class="fa fa-dialog"></i> <em>'.$row["description"].'</em><br/>
        <a class="btn btn-success btn-xs ml-3 mb-2 notifMeAccept" id="notif_accept">Accept</a>
        <a class="btn btn-default btn-xs mb-2 notifMeDecline" id="notif_decline">Decline</a>
    </a>
    <div class="dropdown-divider"></div>
    ';
    }
}
// else
// {
//     $output .= '<li class="m-auto text-center py-5 my-5" style="width:300px;font-size:20px"><a href="#" class="text-bold text-italic py-5 my-5"> No Notification Found </a></li>';
// }
 
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