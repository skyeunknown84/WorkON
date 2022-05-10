<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM user_productivity where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$taskmeta[$k] = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="update-progress">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-md-5">
					<?php if(!isset($_GET['tid'])): ?>
					 <div class="form-group">
		              <label for="" class="control-label">Task Name</label>
		              <select class="form-control form-control-sm select2" name="task_id" >
		              	<option></option>
		              	<?php 
		              	$tasks = $conn->query("SELECT * FROM task_list where project_id = {$_GET['pid']} order by task asc ");
		              	while($row= $tasks->fetch_assoc()):
		              	?>
		              	<option value="<?php echo $row['id'] ?>" <?php echo isset($row['id']) && $row['id'] == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['task']) ?></option>
		              	<?php endwhile; ?>
		              </select>
		            </div>
		            <?php else: ?>
					<input type="hidden" name="task_id" value="<?php echo isset($_GET['tid']) ? $_GET['tid'] : '' ?>">
		            <?php endif; ?>
					<div class="form-group">
						<label for="" class="control-label">Assigned To</label>
						<?php 
						$tasks = $conn->query("SELECT * FROM task_list where project_id = {$_GET['pid']} order by task asc ");
						if($row= $tasks->fetch_assoc()):
						?>
						<div name="user_id" class="" id="task_progress_desc" <?php echo $row['id'] ?>>
							<?php echo ucwords($row['task_owner']) ?>
						</div>
						<?php endif; ?>
					</div>
					<div class="form-group d-flex">						
						<div class="">
							<label for="viewFile" class="control-label p-0 col-12">Attached Task File</label>
							<a id="viewFile" name="file_name" href="<?php echo isset($taskmeta['file_name']) ? 'assets/uploads/files/'.$taskmeta['file_name'] :'' ?>" target="_blank" rel="noopener noreferrer"><?php echo isset($taskmeta['file_name']) ? $taskmeta['file_name'] :'' ?></a>
						</div>
					</div>
					<div class="form-group pb-4 mb-4">
						<label for="">Progress Description</label>
						<div name="description" class="" id="task_progress_desc">
							<?php echo isset($taskmeta['description']) ? $taskmeta['description'] : 'desc' ?>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div>
						<label for="">Comments</label>
						<textarea name="comment" id="comment" class="col-12" rows="3"></textarea>
						<div>
							<a href="" class="btn btn-primary btn-sm hide" id="saveComment">Save</a>
							<a href="" class="btn btn-default btn-sm" id="clearComment">Clear</a>
						</div>						
						<hr/>
						<div class="y-scroll" style="height:200px;">
							<p><?php echo isset($comment) ? $comment : '' ?></p>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</form>
</div>
<style>
	.custom-file .custom-file-input {
		height: 1rem !important;
		padding: 0 !important;
	}
</style>
<script>
	function displayFile(input,_this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#viewFile').attr('href', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
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
     $('.select2').select2({
	    placeholder:"Please select here",
	    width: "100%"
	  });
     })
    $('#manage-progress').submit(function(e){
    	e.preventDefault()
    	start_load()
    	$.ajax({
    		url:'ajax.php?action=save_progress',
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

	$('#update-progress').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
    		url:'ajax.php?action=update_progress',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully updated',"success");
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
    	})
	})
	$('#clearComment').on('click',function(e){
		e.preventDefault()
		$('#comment').val('');
	})
</script>