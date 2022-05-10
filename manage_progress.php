<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qryprogress = $conn->query("SELECT * FROM user_productivity where id = ".$_GET['id'])->fetch_array();
	foreach($qryprogress as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-progress">
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
		              	<option value="<?php echo $row['id'] ?>" <?php echo isset($task_id) && $task_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['task']) ?></option>
		              	<?php endwhile; ?>
		              </select>
		            </div>
		            <?php else: ?>
					<input type="hidden" name="task_id" value="<?php echo isset($_GET['tid']) ? $_GET['tid'] : '' ?>">
		            <?php endif; ?>
					<div class="form-group hide">
						<label for="">End Date</label>
						<input type="hidden" class="form-control form-control-sm" name="date" value="<?php echo isset($date) ? date("Y-m-d",strtotime($date)) : '' ?>" required>
					</div>
					<div class="form-group hide">
						<label for="">Start Time</label>
						<input type="hidden" name="start_time" value="<?php echo isset($start_time) ? date("H:i",strtotime("2020-01-01 "."Y-m-d")) : '' ?>" required>
					</div>
					<div class="form-group hide">
						<label for="">End Time</label>
						<input type="hidden" class="form-control form-control-sm" name="end_time" value="<?php echo isset($end_time) ? date("H:i",strtotime("2020-01-01 ".$end_time)) : '' ?>" required>
					</div>
					<div class="form-group">
						<label for="" class="control-label">Add Task File</label>
						<div class="custom-file">
						<input type="file" class="" id="custom_file" name="taskfile" onchange="displayFile(this,$(this))" required>
						</div>
					</div>
					<div class="form-group d-flex file-display">
						<?php if($file_type == 'pdf'){ ?>
                            <a id="dFile" target="_blank" href="<?php echo $file_path; ?>" title="<?php echo $file_name ?>" class="img-fluid img-thumbnail m-2 align-center text-danger">
                                <i class="fas fa-file-pdf fa-3x"></i>
                                <br/><small><?php echo $file_name ?></small>
                                <!-- <br/><span><a class="fa fa-trash"></a></span> -->
                            </a>
                            <?php 
                            }
                            elseif($file_type == 'docx'){ ?>
                            <a id="dFile" target="_blank" href="<?php echo $file_path; ?>" title="<?php echo $file_name ?>" class="img-fluid img-thumbnail m-2 align-center text-primary">
                                <i class="fas fa-file-word fa-3x"></i>
                                <br/><small><?php echo $file_name ?></small>
                                <!-- <br/><span><a class="fa fa-trash"></a></span> -->
                            </a>
                        	<?php 
                            }
                            elseif($file_type == 'xlsx'){ ?>
                            <a id="dFile" target="_blank" href="<?php echo $file_path; ?>" title="<?php echo $file_name ?>" class=" text-success">
                                <i class="fas fa-file-excel fa-3x"></i>
                                <br/><small><?php echo $file_name ?></small>
                                <!-- <br/><span><a class="fa fa-trash"></a></span> -->
                            </a>
                            <?php 
                            }
                            elseif($file_type == 'pptx'){ ?>
                            <a id="dFile" target="_blank" href="<?php echo $file_path; ?>" title="<?php echo $file_name ?>" class="img-fluid img-thumbnail m-2 align-center text-warning">
                                <i class="fas fa-file-powerpoint fa-3x"></i>
                                <br/><small><?php echo $file_name ?></small>
                                <!-- <br/><span><a class="fa fa-trash"></a></span> -->
                            </a>
							<?php 
							}
							elseif($file_type == 'png'){ ?>
								<a id="dFile" href="<?php echo $file_path; ?>" target="_blank"><img src="<?php echo $file_path; ?>" alt="" title="<?php echo $file_name ?>" class="img-fluid img-thumbnail" style="height:100px" /></a>
							<?php 
							}							
							elseif($file_type == 'jpg'){ ?>
								<a id="dFile" href="<?php echo $file_path; ?>" target="_blank"><img src="<?php echo $file_path; ?>" alt="" title="<?php echo $file_name ?>" class="img-fluid img-thumbnail" style="height:100px" /></a>
							<?php 
							}
							elseif($file_type == 'gif'){ ?>
								<a id="dFile" href="<?php echo $file_path; ?>" target="_blank"><img src="<?php echo $file_path; ?>" alt="" title="<?php echo $file_name ?>" class="img-fluid img-thumbnail" style="height:100px" /></a>
							<?php 
							}
							elseif($file_type == 'zip'){ ?>
								<a id="dFile" href="<?php echo $file_path; ?>" target="_blank"><i class="fas fa-file-archive" title="<?php echo $file_type ?>"></i> <?php echo $file_type ?></a>
								<?php 
							}
							elseif($file_type == 'rar'){ ?>
								<a id="dFile" href="<?php echo $file_path; ?>" target="_blank"><i class="fas fa-file-archive" title="<?php echo $file_type ?>"></i> <?php echo $file_type ?></a>
								<?php 
							}
							elseif($file_type == ''){ ?>
								<a id="dFile" href="#" target="_blank"><span>No File Upload</span></a>
                        	<?php 
                            }
                            else{ ?>
                            <a id="dFile" href="<?php echo $file_path; ?>" target="_blank"><img src="<?php echo $file_path; ?>" alt="" title="<?php echo $file_name ?>" class="img-fluid img-thumbnail" style="height:100px" /></a>
                        <?php } ?>
					</div>
					
				</div>
				<div class="col-md-7">
					<div class="form-group pb-4 mb-4">
						<label for="description">Comment/Progress Description</label>
						<textarea name="description" id="task_progress_desc" cols="30" rows="30" class="summernote form-control" required="">
							<?php echo isset($description) ? $description : '' ?>
						</textarea>
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
				$('#dFile').attr('href', e.target.result);
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
</script>