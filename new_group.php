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
						<select class="form-control form-control-sm js-example-basic-multiple select2 select-member" multiple="multiple" name="group_members[]" required>
							<option></option>
							<?php foreach($members_list as $row){ ?>
							<option value="<?= $row['name']; ?>" <?= isset($group_members) && in_array($row['name'],explode(',',$group_members)) ? "selected" : '' ?>><?= $row['name']; ?></option>
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
	$('.select-members').select2({
		placeholder: 'Select Group Members',

	})
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