<section>
    <div class="container">
    <div class="card">
        <div class="card-body">
            <div id="actions" class="row">
                <div class="col-lg-6">
                <div class="btn-group w-100">
                    <span class="btn btn-success col fileinput-button dz-clickable">
                    <i class="fas fa-plus"></i>
                    <span>Add files</span>
                    </span>
                    <button type="submit" class="btn btn-primary col start">
                    <i class="fas fa-upload"></i>
                    <span>Start upload</span>
                    </button>
                    <button type="reset" class="btn btn-warning col cancel">
                    <i class="fas fa-times-circle"></i>
                    <span>Cancel upload</span>
                    </button>
                </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center">
                <div class="fileupload-process w-100">
                    <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress=""></div>
                    </div>
                </div>
                </div>
            </div>
            <div class="table table-striped files" id="previews">
                
            </div>
        </div>
    </div>
    
    </div>
</section>