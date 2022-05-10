<?php if(!isset($conn)){ include 'db_connect.php'; } ?>
<?php if (isset($_POST['save'])) {
  $target_dir = "Uploaded_Files/";
  $target_file = $target_dir . date("dmYhis") . "_". basename($_FILES["file"]["name"]);
  $uploadOk = 1;
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
 
  if($imageFileType != "jpg" || $imageFileType != "png" || $imageFileType != "jpeg" || $imageFileType != "gif" ) {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
      $files = date("dmYhis") . basename($_FILES["file"]["name"]);
    }else{
      echo "Error Uploading File";
      exit;
    }
  }else{
    echo "File Not Supported";
    exit;
  }
  $filename = $_POST['filename'];
  $location = "Uploaded_Files/" . $files;
  $sqli = "INSERT INTO `tblfiles` (`FileName`, `Location`) VALUES ('{$filename}','{$location}')";
  $result = mysqli_query($con,$sqli);
  if ($result) {
    echo "File has been uploaded";
  };

}
?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-body">
			<form action="" id="manage-project" enctype="multipart/form-data">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Project Name</label>
							<input type="text" class="form-control form-control-sm" name="name" value="<?php echo isset($name) ? $name : '' ?>" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="">Status</label>
							<select name="status" id="status" class="custom-select custom-select-sm" required>
								<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Not Started</option>
								<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>Started</option>
								<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>In Progress</option>
								<option value="4" <?php echo isset($status) && $status == 4 ? 'selected' : '' ?>>In Review</option>
								<option value="5" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>Completed</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 hide">
						<div class="form-group">
						<label for="" class="control-label hide">Start Date</label>
						<input type="hidden" name="start_date" value="<?php echo date('Y-m-d') ?>" required>
						</div>
					</div>
					<?php if($_SESSION['login_type'] == 3 ||  $_SESSION['login_type'] == 1 ): ?>
					<div class="col-md-6">
						<div class="form-group">
						<label for="" class="control-label">Dean</label>
						<select class="form-control form-control-sm select2" name="manager_id" required>
							<option></option>
							<?php 
							$managers = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type between 2 and 3 order by concat(firstname,' ',lastname) asc ");
							while($row= $managers->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($manager_id) && $manager_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
							<?php endwhile; ?>
						</select>
						</div>
					</div>
					<?php else: ?>
						<input type="hidden" name="manager_id" value="<?php echo $_SESSION['login_id'] ?>">
					<?php endif; ?>
					<div class="col-md-6">
						<div class="form-group">
						<label for="" class="control-label">Due Date</label>
						<input type="date" class="form-control form-control-sm" autocomplete="off" name="end_date" value="<?php echo isset($end_date) ? date("Y-m-d",strtotime($end_date)) : '' ?>" required>
						</div>
					</div>		
				</div>
				<div class="row">
					<input type="text" class="hide" name="proj_status" value="1">
					<?php if($_SESSION['login_type'] == 3 || $_SESSION['login_type'] == 1 ): ?>
					<div class="col-md-6">
						<div class="form-group">
						<label for="" class="control-label">Project Chair</label>
						<select class="form-control form-control-sm select2" name="chair_id" required>
							<option></option>
							<?php 
							$chairs = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type between 2 and 3 order by concat(firstname,' ',lastname) asc ");
							while($row= $chairs->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($chair_id) && $chair_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
							<?php endwhile; ?>
						</select>
						</div>
					</div>
					<?php else: ?>
						<input type="hidden" name="chair_id" value="<?php echo $_SESSION['login_id'] ?>">
					<?php endif; ?>
					<div class="col-md-6">
						<div class="form-group">
						<label for="" class="control-label">Team Members</label>
						<select class="form-control form-control-sm select2" multiple="multiple" name="user_ids[]" required>
							<option></option>
							<?php 
							$members = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type between 2 and 3 order by concat(firstname,' ',lastname) asc ");
							while($row= $members->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($user_ids) && in_array($row['id'],explode(',',$user_ids)) ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
							<?php endwhile; ?>
						</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="" class="control-label">Project Overview</label>
							<textarea name="description" id="" cols="30" rows="10" class="summernote form-control" required>
								<?php echo isset($description) ? $description : '' ?>
							</textarea>
						</div>
					</div>
					<div class="col-md-6 hide">
						<div class="form-group">
							<label for="" class="control-label">Task Documentation Link</label>
							<input type="url" class="form-control form-control-sm hide" name="project_url" placeholder="e.g. https://docs.google.com/spreadsheets/u/0/" value="">
						</div>
					</div>
					<div class="col-md-6 hide">
						<div class="form-group">
							<label for="" class="control-label">Task Time Sheet Link</label>
							<input type="url" class="form-control form-control-sm hide" name="project_time_sheet" placeholder="e.g. https://docs.google.com/spreadsheets/u/0/" value="">
						</div>
					</div>
					<div class="col-md-6 hide">
						<div class="form-group">
							<label for="" class="control-label">Upload Main Task File</label>
							<input type="url" class="form-control form-control-sm hide" name="project_files" placeholder="e.g. https://docs.google.com/spreadsheets/u/0/" value="">
						</div>
					</div>
				</div>
        	</form>
    	</div>
    	<div class="card-footer border-top border-info">
    		<div class="d-flex w-100 justify-content-center align-items-center">
    			<button class="btn btn-round bg-primary mx-2" form="manage-project">Save</button>
    			<button class="btn btn-round bg-info mx-2" type="button" onclick="location.href='index.php?page=project_list'">Cancel</button>
    		</div>
    	</div>
	</div>
</div>
<script>
	// $document.ready(function() {
	// 	// define variables
	// })
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