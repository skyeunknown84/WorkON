<section>
    <div class="container-fluid">
    <div class="card card-outline card-success">
        <div class="card-body">
            <div class="table table-striped files mt-1 d-flex" id="previews actions">
                <form action="" id="manage_upload_file" enctype="multipart/form-data" class="col-12 mx-auto align-center">
                    <div class="custom-file col-lg-7 col-md-6 col-sm-12 mx-1">
                        <input type="file" class="custom-file-input" name="file" id="customFile">
                        <label class="custom-file-label" for="customFile">Add New File</label>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary col-lg-4 col-md-5 col-sm-12 mx-1 mt-1"><i class="fa fa-upload"></i> Upload</button>
                </form>
                <br/>
                               
            </div>
            <hr/>
            <div class="row col-12 hide">
                <a class="btn btn-warning delete_file" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i> Remove Files</a>
            </div> 
            <div class="conntainer">
                <ul class="row" style="">
                    <?php
                    
                        // Get images from the database
                        $query = $conn->query("SELECT * FROM tbl_files ORDER BY date_uploaded DESC");

                        if($query->num_rows > 0){
                            while($row = $query->fetch_assoc()){
                                $imageURL = 'assets/uploads/files/'.$row["file_name"];
                                $exFormat = $row['file_type'];
                                $file_name = $row['file_name'];
                        ?>
                    <li class="col-lg-2 col-md-3 col-sm-6 m-2 align-center">
                        <?php if($exFormat == 'pdf'){ ?>
                            <a target="_blank" href="<?php echo $imageURL; ?>" title="<?= $file_name ?>" class="img-fluid img-thumbnail m-2 align-center text-danger">
                                <i class="fas fa-file-pdf fa-3x"></i>
                                <br/><small><?= $file_name ?></small>
                                <!-- <br/><span><a class="fa fa-trash"></a></span> -->
                            </a>
                            <?php 
                            }
                            elseif($exFormat == 'docx'){ ?>
                            <a  target="_blank" href="<?php echo $imageURL; ?>" title="<?= $file_name ?>" class="img-fluid img-thumbnail m-2 align-center text-primary">
                                <i class="fas fa-file-word fa-3x"></i>
                                <br/><small><?= $file_name ?></small>
                                <!-- <br/><span><a class="fa fa-trash"></a></span> -->
                            </a>
                        <?php 
                            }
                            elseif($exFormat == 'xlsx'){ ?>
                            <a  target="_blank" href="<?php echo $imageURL; ?>" title="<?= $file_name ?>" class=" text-success">
                                <i class="fas fa-file-excel fa-3x"></i>
                                <br/><small><?= $file_name ?></small>
                                <!-- <br/><span><a class="fa fa-trash"></a></span> -->
                            </a>
                            <?php 
                            }
                            elseif($exFormat == 'pptx'){ ?>
                            <a  target="_blank" href="<?php echo $imageURL; ?>" title="<?= $file_name ?>" class="img-fluid img-thumbnail m-2 align-center text-warning">
                                <i class="fas fa-file-powerpoint fa-3x"></i>
                                <br/><small><?= $file_name ?></small>
                                <!-- <br/><span><a class="fa fa-trash"></a></span> -->
                            </a>
                        <?php 
                            }
                            else{ ?>
                            <img src="<?php echo $imageURL; ?>" alt="" title="<?= $file_name ?>" class="img-fluid img-thumbnail" style="height:100px" />
                        <?php } ?>        
                        <a class="btn btn-default delete_file" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash text-danger"></i></a>
                        
                    </li>
                    <?php 
                            }
                        }else{ ?>
                        <div class="py-5 my-5 mx-auto">
                            <p class="py-5 my-5 mx-auto text-center">No file(s) found...</p>
                        </div>
                            
                        <?php } ?>
                </ul>
                
            </div>
        </div>
    </div>
    
    </div>
</section>

<script>
$(document).ready(function(){
    // $('#data-list').dataTable();	
    $('.delete_file').click(function(){
    _conf("Are you sure to delete this file?","delete_file",[$(this).attr('data-id')])
    })
})
// Upload File Form
$('#manage_upload_file').submit(function(e){
		e.preventDefault()
        start_load()
		$.ajax({
			url:'ajax.php?action=save_file',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
                    alert("asda");
                    location.replace('index.php?page=project_list')
					alert_toast('File successfully saved.',"success");
					setTimeout(function(){
						location.replace('index.php?page=project_list')
					},750)
                    end_load()
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>File already exist.</div>");
					$('[name="file_name"]').addClass("border-danger")
				}
			}
		})
	})
    
    function delete_file($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_file',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)
                    end_load()
				}
			}
		})
	}
</script>