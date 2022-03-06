<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$type_arr = array('',"Admin","Project Manager","Employee");
	$qry = $conn->query("SELECT * FROM group_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<div class="card card-widget shadow">
      <div class="widget-user-header bg-dark m-0 p-1 pl-4">
        <h3 class="widget-user-username"><?php echo ucwords($group_name) ?></h3>
      </div>
      <div class="card-footer">
        <div class="container-fluid">
			<dl>
        		<dt>Group Manager</dt>
        		<dd><?php echo $group_manager ?></dd>
        	</dl>
			<dl>
        		<dt>Group Members</dt>
        		<dd><?php echo $group_members ?></dd>
        	</dl>
			<dl>
        		<dt>Group Tasks</dt>
        		<dd><?php echo $group_tasks ?></dd>
        	</dl>
        </div>
    </div>
	</div>
</div>
<div class="modal-footer display p-0 m-0">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<style>
	#uni_modal .modal-footer{
		display: none
	}
	#uni_modal .modal-footer.display{
		display: flex
	}
</style>