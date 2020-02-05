<?php 
	$this->load->view('../../header');
?>
<body>
	<!-- Begin page -->
	<div class="overlay-loader no-display" style="display: none; background: none repeat scroll 0 0 rgba(238,238,238,0.75);left: 0;position: fixed;right: 0;top: 0;bottom: 0;z-index:99999999;">
		<img src="<?php echo base_url().ADMIN_THEME;?>assets/images/loading.gif" style="position: fixed;top: 46%;left: 46%;width: 8%;"/>
	</div> 
	<div id="wrapper">
		<!-- Start Menu -->
		<?php 
			$this->load->view('../../sidebar');
			?>
		<!-- End Menu-->
		<!-- ============================================================== -->
		<!-- Start right Content here -->
		<!-- ============================================================== -->                      
		<div class="content-page">
			<!-- Start content -->
			<div class="content">
				<div class="container">
					<!-- Header Start -->
					<?php 
						$this->load->view('../../sub_header');
						?>
					<!-- Page Breadcrumb -->
					<div class="row breadcrumb-area">
						<div class="col-sm-12">
							<h4 class="pull-left text-uppercase">Update Profile</h4>
							<ol class="breadcrumb pull-right">
								<li><a href="<?php echo base_url();?>" class="text-info">Dashboard</a></li>
								<li class="active">Admin Profile</li>
							</ol>
						</div>
					</div>
					<!-- End Page Breadcrumb -->
					<div class="row m-t-20">
						<div class="col-sm-12">
							<div class="panel panel-default">
								<div class="panel-body">
									<form role="form" id="adminProfile" action="<?php echo base_url();?>profile/ajaxEditAdminEmail" method="post" enctype="multipart/form-data">
										<h4 class="form-header">
											<i class="fa fa-pencil"></i>
											Admin Profile
										</h4>
										<div class="form-group">
											<div class="row">
												<div class="col-md-6">
													<label class="control-label">Email: </label>
													<input type="text" class="form-control" name="email" id="email" autocomplete="off" value="<?php echo $admin_details[0]->email; ?>">
												</div>
											</div>
										</div>	
										<div class="form-footer">
											<button type="submit" id="submit" class="btn btn-success btn-border waves-effect waves-light"><i class="fa fa-check-square-o"></i> SAVE</button>
										</div>
									</form>
								</div>
								<!-- panel-body -->
							</div>
							<!-- panel -->
							
							<div class="panel panel-default">
								<div class="panel-body">
									<form role="form" id="changePassword" action="<?php echo base_url();?>profile/ajaxChangePassword" method="post" enctype="multipart/form-data">
										<h4 class="form-header">
											<i class="fa fa-pencil"></i>
											Change Password
										</h4>
										<div class="form-group">
											<div class="row">
												<div class="col-md-6">
													<label class="control-label">Password : </label>
													<input type="password" class="form-control" name="password" id="password" autocomplete="off" value="">
												</div>
												<div class="col-md-6">
													<label class="control-label">Confirm Password : </label>
													<input type="password" class="form-control" name="confirm_password" id="confirm_password" autocomplete="off" value="">
												</div>
											</div>
										</div>	
										<div class="form-footer">
											<button type="submit" id="submit" class="btn btn-success btn-border waves-effect waves-light"><i class="fa fa-check-square-o"></i> SAVE</button>
										</div>
									</form>
								</div>
								<!-- panel-body -->
							</div>
							
						</div>
						<!-- col -->
					</div>
					<!-- End row -->
				</div>
				<!-- container -->
			</div>
			<!-- content -->
			<?php 
				$this->load->view('../../footer');
				?>
			<!--Back To Button-->
			<a class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
			<!--End Back To Button-->
		</div>
		<!-- ============================================================== -->
		<!-- End Right content here -->
		<!-- ============================================================== -->
	</div>
	<!-- END wrapper -->
	<?php 
	$this->load->view('../../js_files');
	?>
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/form/profile.js" type="text/javascript"></script>
	<script>
	jQuery(document).ready(function() {     
		FormValidation.init();
	});
	</script>
</body>
</html>