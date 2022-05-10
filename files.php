<?php
function formatBytes($bytes) {
    if ($bytes > 0) {
        $i = floor(log($bytes) / log(1024));
        $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        return sprintf('%.02F', round($bytes / pow(1024, $i),1)) * 1 . ' ' . @$sizes[$i];
    } else {
        return 0;
    }
}
// $zip = new ZipArchive();
// $zip_name = time().".zip"; // Zip name
// $zip->open($zip_name,  ZipArchive::CREATE);


// $files_qry = $conn->query("SELECT file_name FROM user_productivity")->fetch_array();
// 	foreach($files_qry as $kname => $vname){
// 		$$kname = $vname;
// 	}
// $files = array($vname);
// $zipname = time().'_files.zip';
// $zip = new ZipArchive();
// $zip->open($zipname, ZipArchive::CREATE);
// foreach ($files as $file_download) {
//   $zip->addFile($file_download);
// }
// $zip->close();


// create zip file
$zip_file = "assets/uploads/files/zip/".date("Ymd_his")."_all_files.zip";
touch($zip_file);

// open zip file
$zip = new ZipArchive;
$this_zip = $zip->open($zip_file);

// zip 1 file, dl 1 file
// if($this_zip) {
//     $file_with_path = "assets/uploads/files/work-on-logo.png";
//     $fname = 'work-on-logo.png';
//     $zip->addFile($file_with_path,$fname);
// }
// zip all, dl all
if($this_zip) {
    $folder_zip = opendir('assets/uploads/files');
    if($folder_zip) {
        while( false !== ($allfiles = readdir($folder_zip))){
            if($allfiles !== '.' && $allfiles !== '..' && $allfiles !== 'zip'){
                // echo $image;
                // echo '<br/>';
                $file_with_path = "assets/uploads/files/".$allfiles;
                $zip->addFile($file_with_path,$allfiles);
            }
        }
        closedir($folder_zip);
    }
}
// download created zip file
if(file_exists($zip_file)){
    // $demo_name = "your_all_files.zip";

    // header('Content-type: application/zip');
    // header('Content-Disposition: attachment; filename="'.$demo_name.'"');

    readfile($zip_file); //auto-download

    // delete this zip file after download
    // unlink($zip_file);
    // array_map('unlink', glob($zip_file));
    // $dir = 'assets/uploads/files/zip/';
    // array_map('unlink', glob("{$dir}".date("Ymd_his").'_all_files.zip));
}


?>
<section>
    <div class="container-fluid">
    <div class="card card-outline card-success">
        <div class="card-body">
            <div class="table table-striped mt-1 d-flex hide" id="previews actions">
                <form action="" id="manage_upload_file" enctype="multipart/form-data" class="col-12 mx-auto align-center hide">
                
                    <div class="form-group">
                        <label for="" class="control-label">Project Name</label>
                        <select class="form-control form-control-sm select2" name="project_id" >
                            <option></option>
                            <?php 
                            $qry = $conn->query("SELECT * FROM project_list $where order by id asc");
                            while($row = $qry->fetch_assoc()):
                                $pid = $row['id']
                            ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo isset($pid) && $pid == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="custom-file col-lg-7 col-md-6 col-sm-12 mx-1">
                        <input type="file" class="custom-file-input" name="file" id="customFile">
                        <label class="custom-file-label" for="customFile">Add New File</label>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary col-lg-4 col-md-5 col-sm-12 mx-1 mt-1"><i class="fa fa-upload"></i> Upload</button>
                </form>                               
            </div>
            <div class="row col-12">            
                <span class="mr-auto text-primary">Total: <?php echo $conn->query("SELECT * FROM user_productivity")->num_rows; ?> files</span>
                <!--  -->
                <a class="btn btn-info ml-auto" href="<?php echo $zip_file ?>" data-download=""><i class="fa fa-download"></i> Download All Files</a>
            </div>
            <hr/>
            <div class="conntainer">
                <table class="table table-hover table-condensed" id="list-files">
                    <thead>
                        <th width="5%">#</th>
                        <th>File Name</th>
                        <th>Size</th>
                        <th>Type</th>
                        <th class="text-center">Action</th>
                    </thead>
                    <tbody>
                        <?php                        
                        // Get images from the database
                        $query = $conn->query("SELECT * FROM user_productivity ORDER BY date_uploaded DESC");

                        if($query->num_rows > 0){
                            $i = 1;
                            while($row = $query->fetch_assoc()){
                                $imageURL = 'assets/uploads/files/'.$row["file_name"];
                                $exFormat = $row['file_type'];
                                $file_name = $row['file_name'];
                                $file_size = $row['file_size'];
                        ?>
                        <tr>
                            <td width="5%"><?php echo $i++ ?></td>
                            <td><a target="_blank" href="<?php echo $imageURL; ?>" id="file_<?php echo $row['id'] ?>" title="View"><?php echo $file_name ?></a></td>
                            <td><?php echo formatBytes($file_size) ?></td>
                            <td>
                                <?php if($exFormat == 'pdf'){ ?>
                                    <i class="fas fa-file-pdf text-danger" title="<?php echo $exFormat ?>"></i> <?php echo $exFormat ?>
                                <?php 
                                }
                                elseif($exFormat == 'docx'){ ?>
                                    <i class="fas fa-file-word text-info" title="<?php echo $exFormat ?>"></i> <?php echo $exFormat ?>
                                <?php 
                                }
                                elseif($exFormat == 'xlsx'){ ?>
                                    <i class="fas fa-file-excel text-success" title="<?php echo $exFormat ?>"></i> <?php echo $exFormat ?>
                                <?php 
                                }
                                elseif($exFormat == 'pptx'){ ?>
                                    <i class="fas fa-file-powerpoint text-warning" title="<?php echo $exFormat ?>"></i> <?php echo $exFormat ?>
                                <?php 
                                }
                                elseif($exFormat == 'png'){ ?>
                                    <i class="fas fa-image " title="<?php echo $exFormat ?>"></i> <?php echo $exFormat ?>
                                    <?php 
                                }
                                elseif($exFormat == 'jpg'){ ?>
                                    <i class="fas fa-image " title="<?php echo $exFormat ?>"></i> <?php echo $exFormat ?>
                                <?php 
                                }
                                elseif($exFormat == 'gif'){ ?>
                                    <i class="fas fa-image " title="<?php echo $exFormat ?>"></i> <?php echo $exFormat ?>
                                <?php 
                                }
                                elseif($exFormat == 'zip'){ ?>
                                    <i class="fas fa-file-archive" title="<?php echo $exFormat ?>"></i> <?php echo $exFormat ?>
                                    <?php 
                                }
                                elseif($exFormat == 'rar'){ ?>
                                    <i class="fas fa-file-archive" title="<?php echo $exFormat ?>"></i> <?php echo $exFormat ?>
                                <?php 
                                }
                                else{ ?>
                                    <?php echo $exFormat ?>
                                <?php 
                                } ?>
                            </td>
                            <td class="text-center">
								<a download="assets/uploads/files/<?php echo $file_name; ?>" href="<?php echo $file_name; ?>" title="Download" class="text-success download_file mr-2" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fa fa-download text-info"></i></a>
								
								<a class="delete_file" href="javascript:void(0)" title="Delete" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash text-danger"></i></a>
								
                            </td>
                        </tr>
                        <?php 
                            }
                        } 
                        ?>
                    </tbody>
                </table>
                <ul class="row hdie" style="">
                    <?php
                    
                        // Get images from the database
                        $query = $conn->query("SELECT * FROM user_productivity ORDER BY date_uploaded DESC");
                        
                        if($query->num_rows > 0){
                            
                            while($row = $query->fetch_assoc()){
                                $imageURL = 'assets/uploads/files/'.$row["file_name"];
                                $exFormat = $row['file_type'];
                                $file_name = $row['file_name'];
                        ?>
                    <li class="col-lg-2 col-md-3 col-sm-6 m-2 align-center hide">
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
    
    $('#list-files').dataTable()
})
// Upload File Form
    $('#manage_upload_file').submit(function(e){
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
                setTimeout(function(){
                    alert_toast("File successfully uploaded",'success')
                    location.replace('index.php?page=project_list')
                },1000)
                end_load()
				if(resp==1){
					alert("File successfully uploaded",'success')
					setTimeout(function(){
						location.replace('index.php?page=project_list')
					},1500)
                    // end_load()
				}else{
					$('#msg').html("<div class='alert alert-danger'>Upload Error. File already exist or format not supported</div>");
					$('[name="file_name"]').addClass("border-danger")
				}
			}
		})
	})
    
    function delete_file($id){
		start_load()
        var id = $(this).attr('data-id');
        var path = $( '#file_'+id ).attr("href");
        
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