<?php 
include('db_connect.php');
session_start();
if(isset($_GET['id'])){
$user = $conn->query("SELECT * FROM group_list where id =".$_GET['id']);
foreach($user->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
}
?>
<div class="container-fluid">
	<div id="msg"></div>
	
	<form action="" id="manage-group">	
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<div class="form-group">
			<label for="name">Group Name</label>
			<input type="text" name="group_name" id="group_name" class="form-control" value="<?php echo isset($meta['group_name']) ? $meta['group_name']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="name">Group Manager</label>
			<input type="text" name="group_manager" id="group_manager" class="form-control" value="<?php echo isset($meta['group_manager']) ? $meta['group_manager']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="name">Group Members</label>
			<input type="text" name="group_members" id="group_members" class="form-control" value="<?php echo isset($meta['group_members']) ? $meta['group_members']: '' ?>" required>
		</div>

	</form>
</div>
<script>
	$('#manage-group').submit(function(e){
		e.preventDefault();
		start_load()
		$.ajax({
			url:'ajax.php?action=update_group',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp ==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}else{
					$('#msg').html('<div class="alert alert-danger">Group Name already exist</div>')
					end_load()
				}
			}
		})
	})

</script>