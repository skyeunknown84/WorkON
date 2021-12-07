<?php 
if(!isset($conn)){ include 'db_connect.php'; }
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM task_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
include 'header.php';
?>
<div class="container-fluid">
	<form action="" id="manage-task" method="post" encytype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
		<div class="form-group">
			<label for="">Task</label>
			<input type="text" class="form-control form-control-sm" name="task" value="<?php echo isset($task) ? $task : '' ?>" required>
		</div>
		<div class="form-group">
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
		<div class="form-group">
			<label for="">Description</label>
			<textarea name="description" id="" cols="30" rows="10" height="50px" class="summernote form-control">
				<?php echo isset($description) ? $description : '' ?>
			</textarea>
		</div>		
		<div class="form-group">
			<label for="">Status</label>
			<select name="status" id="status" class="custom-select custom-select-sm">
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Not Started</option>
				<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>Started</option>
				<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>In Progress</option>
				<option value="4" <?php echo isset($status) && $status == 4 ? 'selected' : '' ?>>In Review</option>
				<option value="4" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>Over Due</option>
				<option value="5" <?php echo isset($status) && $status == 6 ? 'selected' : '' ?>>Completed</option>
			</select>
		</div>
		<div class="form-group">
			<div class="custom-file">
				<input type="file" class="custom-file-input" id="customFile" name="myFile" onchange="displayFile(this,$(this))">
				<label class="custom-file-label" for="customFile">Choose file</label>
			</div>
			<div class="form-group d-flex justify-content-center align-items-center">
				<p alt="Avatar" id="viewFile" class="img-fluid img-thumbnail "><?php echo isset($file_uploaded) ? 'assets/uploads/'.$file_uploaded :'' ?></p>
			</div>
			<!-- <div class="hide">
				<input type="file" name="file" id="myFile" >
				<input type="button" id="uploadFile" value="Upload">
			</div> -->
		</div>
	</form>
</div>

<script>
	$(document).ready(function(){

		$('.summernote').summernote({
			height: 200,
			toolbar: [
				[ 'style', [ 'style' ] ],
				[ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
				[ 'fontname', [ 'fontname' ] ],
				[ 'fontsize', [ 'fontsize' ] ],
				[ 'color', [ 'color' ] ],
				[ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
				[ 'table', [ 'table' ] ],
				[ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
			]
		})

		$('#uploadFile').click(function(e) {
			e.preventDefault();
			var myfile = $("#myFile")[0].files;
			console.log(myfile);
		})
		
    })
    function displayFile(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#viewFile').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
    $('#manage-task').submit(function(e){
    	e.preventDefault()
    	start_load()
    	$.ajax({
    		url:'ajax.php?action=save_task',
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
						location.reload()
					},1500)
				}
			}
    	})
    })
</script>
<?php include 'footer.php' ?>