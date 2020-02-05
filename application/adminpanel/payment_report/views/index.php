<?php 
$this->load->view('../../header');
?>
<style>
	.table > tbody > tr > td {
		 vertical-align: middle;
		 text-align:center;
	}
</style>

<link href="<?php echo base_url().ADMIN_THEME;?>assets/plugins/date-time-pickers/css/flatpicker-airbnb.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url().ADMIN_THEME;?>assets/plugins/bootstrap-clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css"/>

<body>
	<div class="overlay-loader no-display" style="display: none; background: none repeat scroll 0 0 rgba(238,238,238,0.75);left: 0;position: fixed;right: 0;top: 0;bottom: 0;z-index:99999999;">
		<img src="<?php echo base_url().ADMIN_THEME;?>assets/images/loading.gif" style="position: fixed;top: 46%;left: 46%;width: 8%;"/>
	</div> 
	<!-- Begin page -->
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
					<!-- Header End -->
					
					<div id="delete_confirmation" class="modal modal-color modal-success fade in" tabindex="-1" aria-labelledby="myModalLabel">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-close"></i></button>
									<h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
								</div>
								<div class="modal-body">
									<p>Are you sure you want to delete this Contact?</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-success btn-border waves-effect waves-light" id="delete_ok">Yes</button>
									&nbsp;
									<button type="button" class="btn btn-danger btn-border waves-effect" data-dismiss="modal">CLOSE</button>
								</div>
							</div><!--modal-content -->
						</div><!--modal-dialog -->
					</div>
					
					<!-- Page Breadcrumb -->
					<div class="row breadcrumb-area">
						<div class="col-sm-12">
							<h4 class="pull-left text-uppercase">Payment Report</h4>
							<ol class="breadcrumb pull-right">
								<li><a href="<?php echo base_url();?>" class="text-info">Dashboard</a></li>
								<li><a href="javascript:void();">Report</a></li>
							</ol>
						</div>
					</div>
					<!-- End Page Breadcrumb -->
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Listing</h3>
								</div>
								<div class="panel-body">
									<div class="portlet light">
										<div class="portlet-body form">
											<form method="post" enctype="multipart/form-data">  
											<div class="form-body">
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<label class="logs-leb"> Select Company </label>
															<select class="form-control" name="company_name" id="company_name">
																<option value="">Please select</option>
																<option value="1" <?php if($company_name == '1'){ echo 'selected'; }?>>Angel</option>
																<option value="2" <?php if($company_name == '2'){ echo 'selected'; }?>>Upstock</option>
																<option value="3" <?php if($company_name == '3'){ echo 'selected'; }?>>Other</option>
															</select>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label class="logs-leb"> Select Segment </label>
															<select class="form-control" name="segment" id="segment">
																<option value="">Please select</option>
																<option value="Equity" <?php if($segment == 'Equity'){ echo 'selected'; }?>>Equity</option>
																<option value="Future" <?php if($segment == 'Future'){ echo 'selected'; }?>>Future</option>
																<option value="Option" <?php if($segment == 'Option'){ echo 'selected'; }?>>Option</option>
																<option value="Positional" <?php if($segment == 'Positional'){ echo 'selected'; }?>>Positional</option>
															</select>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label class="logs-leb"> Date Range </label>
															<input class="form-control" type="text" id="date_range" name="date_range" value="<?php echo $date_range;?>">
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label class="logs-leb">&nbsp;</label>
															<div class="form-actions">
																<button class="btn btn-success">Search</button>
																&nbsp;<button type="button" class="btn btn-danger" onclick="window.location.href='<?php echo base_url().'payment_report'; ?>'">Clear Filter</button>
															</div>
														</div>
													</div>
												</div>	
											</div>
											</form>
										</div>
									</div>
									<div class="clearfix"></div>
									<br>
									<div class="table-responsive">
										<table id="example" class="table table-bordered t-h-info t-b-info">
											<thead>
												<tr>
													<th class="text-center">No</th>
													<th class="text-center">Client ID</th>
													<th class="text-center">Name</th>
													<th class="text-center">Company Name</th>
													<th class="text-center">Segments</th>
													<th class="text-center">Amount Paid</th>
													<th class="text-center">Start Date</th>
													<th class="text-center">End Date</th>
													<th class="text-center">Payment Date</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$TOTAL_EARNING = 0;
													if(!empty($payments_log)){
														$i = 0;
														foreach($payments_log as $payments_log_data){
															$TOTAL_EARNING = $TOTAL_EARNING + $payments_log_data['amount'];
															$i++;
												?>
													<tr class="text-center">
														<td><?php echo $i; ?></td>
														<td><?php echo ($payments_log_data['client_id'] != "") ? $payments_log_data['client_id'] : "-"; ?></td>
														<td><?php echo $payments_log_data['contact_name']; ?></td>
														<td><?php echo $payments_log_data['trade_company_text']; ?></td>
														<td><?php echo $payments_log_data['segments']; ?></td>
														<td><?php echo $payments_log_data['amount']; ?></td>
														<td><?php echo date("d-m-Y",strtotime($payments_log_data['start_date'])); ?></td>
														<td><?php echo date("d-m-Y",strtotime($payments_log_data['end_date'])); ?></td>
														<td><?php echo date("d-m-Y",strtotime($payments_log_data['created_date'])); ?></td>
													</tr>
												<?php			
														}
													}
												?>
											</tbody>
											<tfoot>
												<tr class="text-center">
													<td colspan="5">Total Earning</td>
													<td ><?php echo $TOTAL_EARNING; ?></td>
													<td colspan="3"></td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- End row -->
				</div> <!-- container -->
						   
			</div> <!-- content -->

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
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/plugins/date-time-pickers/js/flatpickr.js" type="text/javascript"></script>
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/plugins/date-time-pickers/js/date-time-picker-script.js"></script>
	
	<script>
	var WEBSITE_URL = '<?php echo base_url(); ?>';
	$(document).ready(function() {
		$('#example').DataTable();
		$("#date_range").flatpickr({
			mode: 'range',
			dateFormat: "d-m-Y",
		});
	});
	</script>
	<script>
		$('#import_attandance').validate({
			rules: {
				attend_date: {
					step: false
				},	
				CSV_file:{
					required: true
				}
			},	
			submitHandler: function(form) {
				
				$("#submit").attr("disabled",true);
				$(".overlay-loader").show();
				var data = new FormData(form);
				
				$.ajax({
					url: WEBSITE_URL+'attendance/ajaxAddAttendance', 
					type: "POST",             
					data: data,
					processData:false,
					contentType:false,
					cache:false,
					async:false,
					success: function(response) 
					{
						if(response.status == true)
						{
							$(".overlay-loader").hide();
							var options = 
							{
								status:"success",
								sound:true,
								duration:2500
							};

							mkNoti(
								"Success",
								response.message,
								options
							);
							
							setTimeout(function(){
							   window.location = response.redirect;
							},2500);
						}
						else
						{
							$(".overlay-loader").hide();
							var options = 
							{
								status:"danger",
								sound:true,
								duration:3500
							};

							mkNoti(
								"Fail",
								response.message,
								options
							);
							
							$("#submit").attr("disabled",false);	
							return false;
						}	
					}
				});
				return false;
			}
		});
	</script>
</body>

</html>