<?php 
include 'db_connect.php';
session_start();
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM task_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
$projid = $_SESSION['pid'];
$tid = $_GET['id'];
$qrytasks_owner = $conn->query("SELECT avatar,concat(firstname,' ',lastname) as uname FROM users u INNER JOIN project_list p ON u.id = p.user_ids WHERE p.id in ($projid) order by uname asc");

?>
<div class="container-fluid">
	<dl>
		<dt><b class="border-bottom border-primary">Task</b></dt>
		<dd><?php echo ucwords($task) ?></dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Assignee</b></dt>
		
		
		<dd>
			<a href=""><?php echo ucwords($task_owner) ?></a>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Status</b></dt>
		<dd>
			<?php 
        	if($status == 1){
		  		echo "<span class='badge badge-secondary'>Not Started</span>";
        	}elseif($status == 2){
		  		echo "<span class='badge badge-primary'>Started</span>";
        	}elseif($status == 3){
				echo "<span class='badge badge-info'>In Progress</span>";
		  	}elseif($status == 4){
				echo "<span class='badge badge-warning'>In Review</span>";
		  	}elseif($status == 5){
				echo "<span class='badge badge-success'>Completed</span>";
		  	}
			// elseif($status == 6){
			// 	echo "<span class='badge badge-success'>Completed</span>";
		  	// }
        	?>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Description</b></dt>
		<dd><?php echo html_entity_decode($description) ?></dd>
	</dl>
	<dl class="hide">
		<dt><b class="border-bottom border-primary hide">Task External Documents Link:</b></dt>
		<dd>
			<span class="fa fa-link pr-1" title="attachment (docs link / screenshots / docs / recorded video)"></span>
			<a href="<?php echo $task_url ?>" target="_blank" rel="noopener noreferrer"><?php echo $task_url ?></a>
		</dd>
	
	</dl>
</div>