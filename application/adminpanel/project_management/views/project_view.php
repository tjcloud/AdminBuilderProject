<?php 
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
	<div class="overlay-loader no-display" style="display: none; background: none repeat scroll 0 0 rgba(238,238,238,0.75);left: 0;position: fixed;right: 0;top: 0;bottom: 0;z-index:99999999;">
		<img src="<?php echo base_url().ADMIN_THEME;?>assets/images/loading.gif" style="position: fixed;top: 46%;left: 46%;width: 8%;"/>
	</div> 
	<div id="wrapper">
		<div id="snooze_user_modal" class="modal modal-color modal-info fade" tabindex="-1"  aria-labelledby="myModalLabel">
			<div class="modal-dialog">
				<div class="modal-content">
					<form role="form" id="snooze_form" method="post" enctype="multipart/form-data">
						<input type="hidden" name="cid" id="cid">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">Snooze User</h4>
						</div>
						<div class="modal-body" style="height: 330px !important;">
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<label class="control-label">Snooze Date :</label>
										<input type="text" class="form-control" name="snooze_date" id="snooze_date" autocomplete="off" value="">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-info waves-effect waves-light" id="snooze_save">Snooze</button>
							<button type="button" class="btn btn-default waves-effect" id="dismissal_pop" data-dismiss="modal">CLOSE</button>
						</div>
					</form>
				</div>
			</div>
		</div>
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
							<h4 class="pull-left text-uppercase">Project Management</h4>
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
										<form role="form" id="add_contact" action="<?php echo base_url();?>project_management/ajaxAddProject" method="post" enctype="multipart/form-data">
											<h4 class="form-header">
												<i class="fa fa-mobile"></i>
												Project Details
											</h4>
											<div class="form-group">
												<div class="row">
													<div class="col-md-6">
														<label class="control-label">Project Name :</label>
														<input type="text" class="form-control" name="project_name" id="contact_name" autocomplete="off">
													</div>
													<div class="col-md-6">
														<label class="control-label">Database Name :</label>
														<input type="text" class="form-control" name="database_name" id="contact_no" autocomplete="off"> 
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="row">
													<div class="col-md-6">
														<label class="control-label">URL :</label>
														<input type="text" class="form-control" name="url" id="url" autocomplete="off">
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
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/form/project_details.js" type="text/javascript"></script>
	<script>
	jQuery(document).ready(function() {   
		FormValidation.init();
	});
	</script>
</body>
</html>