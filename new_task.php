<?php if(!isset($conn)){ include 'db_connect.php'; } ?>

<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-body">
			<form action="" id="manage-project">

        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<?php echo isset($name) ? $name : '' ?>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="" class="control-label">Task Name</label>
					<input type="text" class="form-control form-control-sm" name="name" value="<?php echo isset($name) ? $name : '' ?>">
				</div>
			</div>
			<div class="col-md-6">
			<label for="">Assignee</label>
				<select class="form-control form-control-sm select2" multiple="multiple" name="user_ids[]">
					<option></option>
					<?php 
					$employees = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 3 order by concat(firstname,' ',lastname) asc ");
					while($row= $employees->fetch_assoc()):
					?>
					<option value="<?php echo $row['id'] ?>" <?php echo isset($user_ids) && in_array($row['id'],explode(',',$user_ids)) ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
					<?php endwhile; ?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-md-10">
				<div class="form-group">
					<label for="" class="control-label">Description</label>
					<textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
						<?php echo isset($description) ? $description : '' ?>
					</textarea>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="">Status</label>
					<select name="status" id="status" class="custom-select custom-select-sm">
						<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Not Started</option>
						<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>Started</option>
						<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>In Progress</option>
						<option value="4" <?php echo isset($status) && $status == 4 ? 'selected' : '' ?>>In Review</option>
						<option value="5" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>Over Due</option>
						<option value="6" <?php echo isset($status) && $status == 6 ? 'selected' : '' ?>>Completed</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<form action="upload_file.php" id="form" method="post" encytype="multipart/form-data">
					<input type="file" name="file" id="myFile">
					<input type="submit" id="uploadfile" value="Upload">
				</form>
			</div>
		</div>
        </form>
    	</div>
    	<div class="card-footer border-top border-info">
    		<div class="d-flex w-100 justify-content-center align-items-center">
    			<button class="btn btn-round  bg-gradient-primary mx-2" form="manage-project">Save</button>
    			<button class="btn btn-round bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=project_list'">Cancel</button>
    		</div>
    	</div>
	</div>
</div>
<script>
	$('#manage-project').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_project',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			// file: 'myFile',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved',"success");
					setTimeout(function(){
						location.href = 'index.php?page=project_list'
					},2000)
				}
			}
		})
	})
</script>