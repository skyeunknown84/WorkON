<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM task_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<dl>
		<dt><b class="border-bottom border-primary">Task</b></dt>
		<dd><?php echo ucwords($task) ?></dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Assignee</b></dt>
		<dd><?php echo ucwords($task_owner) ?></dd>
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
				echo "<span class='badge badge-warning'>Over Due</span>";
		  	}elseif($status == 6){
				echo "<span class='badge badge-success'>Completed</span>";
		  	}
        	?>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Description</b></dt>
		<dd><?php echo html_entity_decode($description) ?></dd>
	</dl>
</div>