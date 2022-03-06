<?php if(!isset($conn)){ include 'db_connect.php'; }
// Get Group Manager List
$select_manager = "SELECT concat(firstname,' ',lastname) as name FROM users where type = 2 order by concat(firstname,' ',lastname) asc";
$manager_list = mysqli_query($conn, $select_manager);
// Get Group Members List
$select_members = "SELECT concat(firstname,' ',lastname) as name FROM users where type = 3 order by concat(firstname,' ',lastname) asc";
$members_list = mysqli_query($conn, $select_members);
// $membersList = [];
// foreach($members_list as $members_row){
// 	$membersList[] = $members_row['group_members'];
// }
// Get Task List
$select_tasks = "SELECT name FROM project_list where group_id = 1 order by id asc";
$tasksList = mysqli_query($conn, $select_tasks);
?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-body">
			<form  action="" id="manage-group" method="post">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Group Name</label>
							<input type="text" name="group_name" class="form-control form-control-sm" required value="<?php echo isset($group_name) ? $group_name : '' ?>">
							<small id="#msg"></small>
						</div>
						<div class="form-group">
							<label for="" class="control-label">Group Manager</label>
							<select class="form-control form-control-sm select2 select-manager" name="group_manager" required>
								<option></option>
								<?php foreach($manager_list as $key => $value){ ?>
								<option value="<?= $value['name']; ?>" <?= isset($group_manager) && $group_manager == $value['name'] ? "selected" : '' ?>><?= $value['name']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Group Members</label>
							<select class="form-control form-control-sm js-example-basic-multiple select2 select-members" multiple="multiple" name="group_members[]" required>
								<option></option>
								<?php foreach($members_list as $row){ ?>
								<option value="<?= $row['name']; ?>" <?= isset($group_members) && in_array($row['name'],explode(',',$group_members)) ? "selected" : '' ?>><?= $row['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label for="" class="control-label">Tasks</label>
							<select class="form-control form-control-sm js-example-basic-multiple select2 select-tasks" multiple="multiple" name="group_tasks[]" required>
								<option></option>
								<?php foreach($tasksList as $row){ ?>
								<option value="<?= $row['name']; ?>" <?= isset($group_tasks) && in_array($row['name'],explode(',',$group_tasks)) ? "selected" : '' ?>><?= $row['name']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2" form="manage-group">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=group_list'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	(function(){
		var items = ($('.select-members').val().split(','));
		$('.select-members').select2({
			placeholder: 'Select Group Members',
			multiple: true,
			allowClear: true
		});
		$('select-members').val(items).trigger('change');
		// var items2 = ($('.select-tasks').val().split(','));
		$('.select-tasks').select2({
			placeholder: 'Select Group Tasks',
			multiple: true,
			allowClear: true
		});
		// $('select-tasks').val(items2).trigger('change');
	});
	
	$('#manage-group').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_group',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved.',"success");
					setTimeout(function(){
						location.replace('index.php?page=group_list')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Group Name already exist.</div>");
					$('[name="group_name"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>