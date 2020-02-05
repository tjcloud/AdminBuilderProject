<?php 
if($project_data)
{
	$id = $project_data[0]['id'];
	$project_name = $project_data[0]['project_name'];
	$database_name = $project_data[0]['database_name'];
	$site_url = $project_data[0]['site_url'];
}
else
{
	$id = "";
	$project_name = "";
	$database_name = "";
	$site_url = "";
}

$this->load->view('../../header');
?>
<link href="<?php echo base_url().ADMIN_THEME;?>assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" />
<link href="<?php echo base_url().ADMIN_THEME;?>assets/plugins/bootstrap-switch/bootstrap-switch.min.css" rel="stylesheet">
<link href="<?php echo base_url().ADMIN_THEME;?>assets/plugins/date-time-pickers/css/flatpicker-airbnb.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url().ADMIN_THEME;?>assets/plugins/bootstrap-clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
	a i.fa.fa-whatsapp{
	  color: #4caf50;
	  font-size: 18px;
	}
	a i.fa.fa-eye{
	  color: #0acae2;
	  font-size: 18px;
	} 
	table.dataTable thead .act-sort.sorting:after, 
	table.dataTable thead .act-sort.sorting_asc:after, 
	table.dataTable thead .act-sort.sorting_desc:after, 
	table.dataTable thead .act-sort.sorting_asc_disabled:after, 
	table.dataTable thead .act-sort.sorting_desc_disabled:after{
		content: '';
	} 
	table.dataTable thead .act-sort.sorting, 
	table.dataTable thead .act-sort.sorting_asc, 
	table.dataTable thead .act-sort.sorting_desc, 
	table.dataTable thead .act-sort.sorting_asc_disabled, 
	table.dataTable thead .act-sort.sorting_desc_disabled{
		cursor: unset;
	}
	table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:after{
		right: 1px;
	}
</style>
<body>
	<!-- <div class="overlay-loader no-display" style="display: none; background: none repeat scroll 0 0 rgba(238,238,238,0.75);left: 0;position: fixed;right: 0;top: 0;bottom: 0;z-index:99999999;">
		<img src="<?php echo base_url().ADMIN_THEME;?>assets/images/loading.gif" style="position: fixed;top: 46%;left: 46%;width: 8%;"/>
	</div>  -->
	<div id="wrapper">
		<?php 
		$this->load->view('../../sidebar');
		?>              
		<div class="content-page">
			<div class="content">
				<div class="container">
					<?php 
						$this->load->view('../../sub_header');
						?>
					<div class="row breadcrumb-area">
						<div class="col-sm-12">
							<h4 class="pull-left text-uppercase">Main Management</h4>
							<ol class="breadcrumb pull-right">
								<li><a href="javascript:void();" class="text-info"><?php echo SITE_NAME; ?></a></li>
								<li class="active">Dashboard</li>
							</ol>
						</div>
					</div>
					<div class="row">
						<div class="row m-t-20">
							<div class="col-sm-12">
								<div class="panel panel-default">
									<div class="panel-body">
										<input type="hidden" id="clicks" value="2">
										<form role="form" id="add_management" action="<?php echo base_url();?>main_management/ajaxAddManagement" method="post" enctype="multipart/form-data">
											<h4 class="form-header">
												<i class="fa fa-mobile"></i>
												<?php echo 'Project <u>'.$project_name.'</u> Detail'; ?>
											</h4>
											<div class="form-group">
												<div class="row">
													<div class="col-md-6">
														<label class="control-label">Enter Main Menu :</label>
														<input type="text" class="form-control" name="main_menu" id="main_menu" autocomplete="off">
													</div>
													<div class="col-md-6">
														<label class="control-label">Is Sub Menu :</label>
														<select class="form-control" name="is_sub_menu" id="is_sub_menu">
															<option>-is sub menu-</option>
															<option value="yes">Yes</option>
															<option value="no">No</option>
														</select>
													</div>
												</div>
											</div>
											<div>
												
											<div id="sub_menu">
											</div>
											<div class="form-group add_btn" style="display: none;">
												<div class="row">
													<div class="col-md-12" align="right">
														<button type="button" class="btn btn-success" id="add_text_box">+</button>
													</div>
												</div>
											</div>									
											<div class="form-footer">
												<button type="submit" class="btn btn-success btn-border waves-effect waves-light"><i class="fa fa-check-square-o"></i> SAVE</button>
												&nbsp;
												<a href="<?php echo base_url();?>contacts" class="btn btn-danger btn-border waves-effect waves-light"><i class="fa fa-times"></i> CANCEL</a>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php 
				$this->load->view('../../footer');
			?>
			<a class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
		</div>
	</div>
	<?php 
		$this->load->view('../../js_files');
	?>
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/form/management_details.js" type="text/javascript"></script>
	<script>
	jQuery(document).ready(function() {   
		FormValidation.init();
	});
	$('#is_sub_menu').on('change',function(){
		var is_sub_menu = $(this).val();
		var html = "";
		if(is_sub_menu == "yes")
		{
			$('.add_btn').show();
			html += '<div class="form-group">';
			html += '<div class="row col1">';
			html += '<div class="col-md-5">';
			html += '<label class="control-label">Enter Sub Menu Title :</label>';
			html += '<input type="text" class="form-control" name="sub_menu[]" id="sub_menu" autocomplete="off">';
			html += '</div>';
			html += '<div class="col-md-1" style="margin-top:28px;">';
			html += '<button type="button" class="btn btn-danger remove" onclick="remove_submenu('+1+')" id="remove1">X</button>'
			html += '</div>';
			html += '</div>';
			$('#sub_menu').html(html);
		}
		else if(is_sub_menu == "no")
		{
			$('#sub_menu').remove();
			$('.add_btn').hide();
		}
	});
	$('#add_text_box').on('click',function(){
		var clicks = $('#clicks').val();
		var html = "";
		html += '<div class="row col'+clicks+'">';
		html += '<div class="col-md-5">';
		html += '<label class="control-label">Enter Sub Menu Title :</label>';
		html += '<input type="text" class="form-control" name="sub_menu[]" id="sub_menu" autocomplete="off">';
		html += '</div>';
		html += '<div class="col-md-1" style="margin-top:28px;">';
		html += '<button type="button" class="btn btn-danger remove" onclick="remove_submenu('+clicks+')" id="remove'+clicks+'">X</button>'
		html += '</div>';
		$('#clicks').val(parseInt(clicks)+parseInt(1));
		$('#sub_menu').append(html);
	});
	function remove_submenu(val)
	{
		$('.col'+val).remove();
		var clicks = $('#clicks').val();
		$('#clicks').val(parseInt(clicks)-parseInt(1));
	}
	</script>
</body>
</html>
