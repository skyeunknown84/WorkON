<!-- SweetAlert2 -->
<script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="assets/plugins/toastr/toastr.min.js"></script>
<!-- Switch Toggle -->
<script src="assets/plugins/bootstrap4-toggle/js/bootstrap4-toggle.min.js"></script>
<!-- Select2 -->
<script src="assets/plugins/select2/js/select2.full.min.js"></script>
<!-- Summernote -->
<script src="assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- dropzonejs -->
<script src="assets/plugins/dropzone/min/dropzone.min.js"></script>
<script src="assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- DateTimePicker -->
<script src="assets/dist/js/jquery.datetimepicker.full.min.js"></script>
<!-- Bootstrap Switch -->
<script src="assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
 <!-- MOMENT -->
<script src="assets/plugins/moment/moment.min.js"></script>
<script>
	$(document).ready(function(){
	  $('.select2').select2({
		// dropdownParent: $('#uni_modal'),
	    placeholder:"Please select here",
	    width: "100%"
	  });
	  $("#team_members").CreateMultiCheckBox({ width: '230px', defaultText : 'Please select here', height:'250px'});
	  
  	})
	window.start_load = function(){
	    $('body').prepend('<div id="preloader2"></div>')
	}
	window.end_load = function(){
	    $('#preloader2').fadeOut('fast', function() {
	        $(this).remove();
		})
	}
	window.viewer_modal = function($src = ''){
	    start_load()
	    var t = $src.split('.')
	    t = t[1]
	    if(t =='mp4'){
	      var view = $("<video src='"+$src+"' controls autoplay></video>")
	    }else{
	      var view = $("<img src='"+$src+"' />")
	    }
	    $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove()
	    $('#viewer_modal .modal-content').append(view)
	    $('#viewer_modal').modal({
			show:true,
			backdrop:'static',
			keyboard:false,
			focus:true
		})
		end_load()
	}
	window.uni_modal = function($title = '' , $url='',$size=""){
		start_load()
		$.ajax({
			url:$url,
			error:err=>{
				console.log()
				alert("An error occured")
			},
			success:function(resp){
				if(resp){
					$('#uni_modal .modal-title').html($title)
					$('#uni_modal .modal-body').html(resp)
					if($size != ''){
						$('#uni_modal .modal-dialog').addClass($size)
					}else{
						$('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md")
					}
					$('#uni_modal').modal({
						show:true,
						backdrop:'static',
						keyboard:false,
						focus:true
					})
					end_load()
				}
			}
		})
	}
	window._conf = function($msg='',$func='',$params = []){
		$('#confirm_modal #confirm').attr('onclick',$func+"("+$params.join(',')+")")
		$('#confirm_modal .modal-body').html($msg)
		$('#confirm_modal').modal('show')
	}
	window.alert_toast= function($msg = 'TEST',$bg = 'success' ,$pos=''){
		var Toast = Swal.mixin({
	      	toast: true,
	      	position: $pos || 'top-end',
	      	showConfirmButton: false,
	      	timer: 5000
	    });
		Toast.fire({
	        icon: $bg,
	        title: $msg
		})
	}
	$(function () {
		bsCustomFileInput.init();

		$('.summernote').summernote({
			height: 100,
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

		$('.datetimepicker').datetimepicker({
			format:'Y/m/d H:i',
		})
		
	})
	$(".switch-toggle").bootstrapToggle();
	$('.number').on('input keyup keypress',function(){
		var val = $(this).val()
		val = val.replace(/[^0-9]/, '');
		val = val.replace(/,/g, '');
		val = val > 0 ? parseFloat(val).toLocaleString("en-US") : 0;
		$(this).val(val)
	})

	// notifications
	$('#notifMeAccept').change(function(){
		var notifid = $(this).val();
		$.ajax({
			url:'members_selection.php',
			data: {id:save_member},
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(data){
				$("#team_members").html(data);
				if(data == 1){
					alert_toast('Data successfully saved',"success");
					setTimeout(function(){
						location.href = 'index.php?page=project_list'
					},2000)
				}
			}
		})
		// $.post("members_selection.php", {id:member_id}, function(data){
		// 	$("#team_members").html(data);
		// })
	});

	// dropdown-checkbox
	$(document).ready(function () {
		$(document).on("click", ".MultiCheckBox", function () {
			var detail = $(this).next();
			detail.show();
		});

		$(document).on("click", ".MultiCheckBoxDetailHeader input", function (e) {
			e.stopPropagation();
			var hc = $(this).prop("checked");
			$(this).closest(".MultiCheckBoxDetail").find(".MultiCheckBoxDetailBody input").prop("checked", hc);
			$(this).closest(".MultiCheckBoxDetail").next().UpdateSelect();
		});

		$(document).on("click", ".MultiCheckBoxDetailHeader", function (e) {
			var inp = $(this).find("input");
			var chk = inp.prop("checked");
			inp.prop("checked", !chk);
			$(this).closest(".MultiCheckBoxDetail").find(".MultiCheckBoxDetailBody input").prop("checked", !chk);
			$(this).closest(".MultiCheckBoxDetail").next().UpdateSelect();
		});

		$(document).on("click", ".MultiCheckBoxDetail .cont input", function (e) {
			e.stopPropagation();
			$(this).closest(".MultiCheckBoxDetail").next().UpdateSelect();

			var val = ($(".MultiCheckBoxDetailBody input:checked").length == $(".MultiCheckBoxDetailBody input").length)
			$(".MultiCheckBoxDetailHeader input").prop("checked", val);
		});

		$(document).on("click", ".MultiCheckBoxDetail .cont", function (e) {
			var inp = $(this).find("input");
			var chk = inp.prop("checked");
			inp.prop("checked", !chk);

			var multiCheckBoxDetail = $(this).closest(".MultiCheckBoxDetail");
			var multiCheckBoxDetailBody = $(this).closest(".MultiCheckBoxDetailBody");
			multiCheckBoxDetail.next().UpdateSelect();

			var val = ($(".MultiCheckBoxDetailBody input:checked").length == $(".MultiCheckBoxDetailBody input").length)
			$(".MultiCheckBoxDetailHeader input").prop("checked", val);
		});

		$(document).mouseup(function (e) {
			var container = $(".MultiCheckBoxDetail");
			if (!container.is(e.target) && container.has(e.target).length === 0) {
				container.hide();
			}
		});
	});

	var defaultMultiCheckBoxOption = { width: '220px', defaultText: 'Select Below', height: '200px' };

	jQuery.fn.extend({
		CreateMultiCheckBox: function (options) {

			var localOption = {};
			localOption.width = (options != null && options.width != null && options.width != undefined) ? options.width : defaultMultiCheckBoxOption.width;
			localOption.defaultText = (options != null && options.defaultText != null && options.defaultText != undefined) ? options.defaultText : defaultMultiCheckBoxOption.defaultText;
			localOption.height = (options != null && options.height != null && options.height != undefined) ? options.height : defaultMultiCheckBoxOption.height;

			this.hide();
			this.attr("multiple", "multiple");
			var divSel = $("<div class='MultiCheckBox'>" + localOption.defaultText + "<span class='k-icon k-i-arrow-60-down'><svg aria-hidden='true' focusable='false' data-prefix='fas' data-icon='sort-down' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 512' class='svg-inline--fa fa-sort-down fa-w-10 fa-2x'><path fill='currentColor' d='M41 288h238c21.4 0 32.1 25.9 17 41L177 448c-9.4 9.4-24.6 9.4-33.9 0L24 329c-15.1-15.1-4.4-41 17-41z' class=''></path></svg></span></div>").insertBefore(this);
			divSel.css({ "width": localOption.width });

			var detail = $("<div class='MultiCheckBoxDetail'><div class='MultiCheckBoxDetailHeader'><input type='checkbox' class='mulinput' value='-1982' /><div>Select All</div></div><div class='MultiCheckBoxDetailBody'></div></div>").insertAfter(divSel);
			detail.css({ "width": parseInt(options.width) + 10, "max-height": localOption.height });
			var multiCheckBoxDetailBody = detail.find(".MultiCheckBoxDetailBody");

			this.find("option").each(function () {
				var val = $(this).attr("value");

				if (val == undefined)
					val = '';

				multiCheckBoxDetailBody.append("<div class='cont'><div><input type='checkbox' class='mulinput' value='" + val + "' /></div><div>" + $(this).text() + "</div></div>");
			});

			multiCheckBoxDetailBody.css("max-height", (parseInt($(".MultiCheckBoxDetail").css("max-height")) - 28) + "px");
		},
		UpdateSelect: function () {
			var arr = [];

			this.prev().find(".mulinput:checked").each(function () {
				arr.push($(this).val());
			});

			this.val(arr);
		},
	});

	// $("#save_members").click(function(){
	// 	alert($("#chair_data").val());
	// });
	// $('#chair_data').change(function(){
	// 	var save_member = $(this).val();
	// 	$.ajax({
	// 		url:'members_selection.php',
	// 		data: {id:save_member},
	// 	    cache: false,
	// 	    contentType: false,
	// 	    processData: false,
	// 	    method: 'POST',
	// 	    type: 'POST',
	// 		success:function(data){
	// 			$("#team_members").html(data);
	// 			if(data == 1){
	// 				alert_toast('Data successfully saved',"success");
	// 				setTimeout(function(){
	// 					location.href = 'index.php?page=project_list'
	// 				},2000)
	// 			}
	// 		}
	// 	})
	// 	// $.post("members_selection.php", {id:member_id}, function(data){
	// 	// 	$("#team_members").html(data);
	// 	// })
	// });
	
	// function reload(){
	// 	// alert($('#chairdata').val())
	// 	var cid = document.getElementById('chair_data').value;
	// 	// document.write(cid)
	// 	self.location='dropdown-checkbox.php?chairid=' + cid;
	// }

	

</script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- WorkON JS App -->
<script src="assets/dist/js/workon.js"></script>
<!-- Chosen -->
<script src="assets/plugins/chosen/chosen.jquery.js"></script>
<script src="assets/plugins/chosen/docsupport/init.js"></script>
<!-- Multi-Select -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<!-- PAGE assets/plugins -->
<!-- jQuery Mapael -->
<script src="assets/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="assets/plugins/raphael/raphael.min.js"></script>
<script src="assets/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="assets/plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="assets/plugins/chart.js/Chart.min.js"></script>

<!-- <script src="assets/dist/js/demo.js"></script> -->
<!-- WorkON dashboard demo (This is only for demo purposes) -->
<!-- <script src="assets/dist/js/pages/dashboard2.js"></script> -->
<!-- DataTables  & Plugins -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="assets/plugins/jszip/jszip.min.js"></script>
<!-- <script src="assets/plugins/pdfmake/pdfmake.min.js"></script> -->
<!-- <script src="assets/plugins/pdfmake/vfs_fonts.js"></script> -->
<script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

