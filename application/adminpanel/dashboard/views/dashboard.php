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
<!-- Begin page -->
	<div id="wrapper">
		
		<div id="view_contact_details" class="modal modal-color modal-info fade" tabindex="-1"  aria-labelledby="myModalLabel">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title" id="myModalLabel">Contact Details</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									Name : <span class="contact_name">ABC</span>
								</div>
								<div class="col-md-6">
									Contact No. : <span class="contact_no">ABC</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									Whatsapp No. : <span class="whatsapp_no">ABC</span>
								</div>
								<div class="col-md-6">
									Trade Company : <span class="trade_company_text">ABC</span>
								</div>
							</div>
						</div>
						<div class="form-group is_company_selected">
							<div class="row">
								<div class="col-md-6">
									Client ID : <span class="client_id">ABC</span>
								</div>
								<div class="col-md-6">
									POA Status : <span class="poa_text_status">ABC</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									Segments : <span class="segments">ABC</span>
								</div>
								<div class="col-md-6">
									Paid / Free : <span class="payment_type">ABC</span>
								</div>
							</div>
						</div>
						<div class="form-group is_paid_selected">
							<div class="row">
								<div class="col-md-6">
									Amount : <span class="current_amount">ABC</span>
								</div>
							</div>
						</div>
						<div class="form-group is_paid_selected">
							<div class="row">
								<div class="col-md-6">
									Duration Type : <span class="duration_type">ABC</span>
								</div>
								<div class="col-md-6">
									Duration : <span class="duration_value">ABC</span>
								</div>
							</div>
						</div>
						<div class="form-group is_paid_selected">
							<div class="row">
								<div class="col-md-6">
									Start Date : <span class="start_date">ABC</span>
								</div>
								<div class="col-md-6">
									End Date : <span class="end_date">ABC</span>
								</div>
							</div>
						</div>
						<div class="form-group is_paid_selected">
							<div class="row">
								<div class="col-md-12">
									Note : <span class="note">ABC</span>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<a class="btn btn-warning waves-effect waves-light" id="snooze_from" href="javascript:void(0);" data-toggle="modal" data-target="#snooze_user_modal"> <i class="fa fa-clock-o"></i> Snooze User</a>
						<a class="btn btn-info waves-effect waves-light" id="edit_button" target="_blank">Edit</a>
						<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">CLOSE</button>
					</div>
				</div><!--modal-content -->
			</div><!--modal-dialog -->
		</div><!-- modal -->
		
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
				</div><!--modal-content -->
			</div><!--modal-dialog -->
		</div><!-- modal -->
	
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
					<!-- Page Breadcrumb -->
					<div class="row breadcrumb-area">
						<div class="col-sm-12">
							<h4 class="pull-left text-uppercase">Dashboard</h4>
							<ol class="breadcrumb pull-right">
								<li><a href="javascript:void();" class="text-info"><?php echo SITE_NAME; ?></a></li>
								<li class="active">Dashboard</li>
							</ol>
						</div>
					</div>
					<!-- End Page Breadcrumb -->
					<!-- Dashboard Content -->
					<div class="row">
						<!-- <div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Plan Expiry</h3>
								</div>
								<div class="panel-body">
									<div class="portlet light">
										<div class="portlet-body form">
											<form method="post" id="planExpiryDash" action="<?php echo base_url().'dashboard';?>">
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
															<label class="logs-leb">&nbsp;</label><br>
															<button class="btn btn-success">Search</button>
															&nbsp;<button type="button" class="btn btn-danger" onclick="window.location.href='<?php echo base_url().'dashboard'; ?>'">Clear Filter</button>
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
										<table id="example" class="table table-bordered  t-h-info t-b-info">
											<thead>
												<tr>
													<th class="text-center">Name</th>
													<th class="text-center">Contact No.</th>
													<th class="text-center">Expires On</th>
													<th style="display:none">Expires On val</th>
													<th class="text-center">Company Name</th>
													<th class="text-center">Client Status</th>
													<th class="text-center act-sort">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php	
													if(!empty($plan_expiry))
													{
														foreach($plan_expiry as $plan_expiry_data){
												?>
															<tr class="text-center">
																<td><?php echo $plan_expiry_data['contact_name']; ?></td>
																<td><?php echo $plan_expiry_data['contact_no']; ?></td>
																<td><?php echo $plan_expiry_data['DateDiff']; ?> days</td>
																<td style="display:none;"><?php echo $plan_expiry_data['DateDiff']; ?></td>
																<td><?php echo $plan_expiry_data['trade_company_text']; ?></td>
																<td>
																	<input type="checkbox" name="contact_status" id="contact_status<?php echo $plan_expiry_data['id']; ?>" class="js-switch2" data-color="#51D43A" data-secondary-color="#FD0405" <?php if($plan_expiry_data['is_active'] == 1){ echo 'checked'; } ?> data-userid="<?php echo $plan_expiry_data['id']; ?>"/>
																</td>
																<td>
																	<a href="https://api.whatsapp.com/send?phone=91<?php echo $plan_expiry_data['whatsapp_no']; ?>" target="_blank" class="waves-effect waves-light"> <i class="fa fa-whatsapp"></i> </a>
																	&nbsp;
																	<a href="javascript:void(0);" onclick="view_Contact('<?php echo $plan_expiry_data['id']; ?>');" class="waves-effect waves-light"> <i class="fa fa-eye"></i> </a>
																</td>
															</tr>
												<?php
														}
													}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div> -->
						<div class="row m-t-20">
						<!-- <div class="col-sm-12">
							<div class="panel panel-default">
								<div class="panel-body">
									<form role="form" id="add_contact" action="<?php echo base_url();?>dashboard/ajaxAddProject" method="post" enctype="multipart/form-data">
										<h4 class="form-header">
											<i class="fa fa-mobile"></i>
											Project Builder
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
											<button type="submit" id="submit" class="btn btn-success btn-border waves-effect waves-light"><i class="fa fa-check-square-o"></i> SAVE</button>
											&nbsp;
											<a href="<?php echo base_url();?>contacts" class="btn btn-danger btn-border waves-effect waves-light"><i class="fa fa-times"></i> CANCEL</a>
										</div>
									</form>
								</div>
								panel-body
							</div>
							panel
						</div> -->
						<!-- col -->
					</div>
					</div>
					<!-- <div class="row">
						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Not Traded</h3>
								</div>
							</div>
							<ul class="nav nav-tabs tabs nav-tabs-success">
								<li class="active tab">
									<a href="#tab33" data-toggle="tab" aria-expanded="false"> 
										<span class=""><i class="zmdi zmdi-city-alt zmdi-hc-fw"></i></span> 
										<span class="hidden-xs">Angel</span> 
									</a> 
								</li> 
								<li class="tab"> 
									<a href="#tab34" data-toggle="tab" aria-expanded="false"> 
										<span class=""><i class="zmdi zmdi-city-alt zmdi-hc-fw"></i></span> 
										<span class="hidden-xs">Upstock</span> 
									</a> 
								</li> 
							</ul> 
							<div class="tab-content"> 
								<div class="tab-pane animated fadeIn active" id="tab33"> 
									<div class="table-responsive"> 
										<table id="example2" class="table table-bordered t-h-info t-b-info">
											<thead>
												<tr>
													<th class="text-center">Name</th>
													<th class="text-center">Contact No</th>
													<th class="text-center">Attendance %</th>
													<th class="text-center">Snooze days</th>
													<th class="text-center">Segments</th>
													<th class="text-center act-sort">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php	
													if(!empty($not_trader_angle))
													{
														foreach($not_trader_angle as $not_trader_angle_data){
												?>
															<tr class="text-center">
																<td><?php echo $not_trader_angle_data['contact_name']; ?></td>
																<td><?php echo $not_trader_angle_data['contact_no']; ?></td>
																<td><?php echo $not_trader_angle_data['PaidFree_Percentage']; ?></td>
																<td><?php echo $not_trader_angle_data['snooze_days']; ?></td>
																<td><?php echo $not_trader_angle_data['segments']; ?></td>
																<td>
																	<a href="https://api.whatsapp.com/send?phone=91<?php echo $not_trader_angle_data['whatsapp_no']; ?>" target="_blank" class="waves-effect waves-light"> <i class="fa fa-whatsapp"></i> </a>
																	&nbsp;
																	<a href="javascript:void(0);" onclick="view_Contact('<?php echo $not_trader_angle_data['id']; ?>');" class=" waves-effect waves-light"> <i class="fa fa-eye"></i> </a>
																</td>
															</tr>
												<?php
														}
													}
												?>
											</tbody>
										</table>
									</div> 
								</div> 
								<div class="tab-pane animated fadeIn" id="tab34">
									<div class="table-responsive"> 
										<table id="example3" class="table table-bordered t-h-info t-b-info">
											<thead>
												<tr>
													<th class="text-center">Name</th>
													<th class="text-center">Contact No</th>
													<th class="text-center">Attendance %</th>
													<th class="text-center">Snooze days</th>
													<th class="text-center">Segments</th>
													<th class="text-center act-sort">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php	
													if(!empty($not_trader_upstock))
													{
														foreach($not_trader_upstock as $not_trader_upstock_data){
												?>
															<tr class="text-center">
																<td><?php echo $not_trader_upstock_data['contact_name']; ?></td>
																<td><?php echo $not_trader_upstock_data['contact_no']; ?></td>
																<td><?php echo $not_trader_upstock_data['PaidFree_Percentage']; ?></td>
																<td><?php echo $not_trader_upstock_data['snooze_days']; ?></td>
																<td><?php echo $not_trader_upstock_data['segments']; ?></td>
																<td>
																	<a href="https://api.whatsapp.com/send?phone=91<?php echo $not_trader_upstock_data['whatsapp_no']; ?>" target="_blank" class="waves-effect waves-light"> <i class="fa fa-whatsapp"></i> </a>
																	&nbsp;
																	<a href="javascript:void(0);" onclick="view_Contact('<?php echo $not_trader_upstock_data['id']; ?>');" class="waves-effect waves-light"> <i class="fa fa-eye"></i> </a>
																</td>
															</tr>
												<?php
														}
													}
												?>
											</tbody>
										</table>
									</div> 
								</div> 
								
							</div> 
						</div>
						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">No POA</h3>
								</div>
							</div>
							<ul class="nav nav-tabs tabs nav-tabs-rose">
								<li class="active tab">
									<a href="#tab36" data-toggle="tab" aria-expanded="false"> 
										<span class=""><i class="zmdi zmdi-city-alt zmdi-hc-fw"></i></span> 
										<span class="hidden-xs">Angel</span> 
									</a> 
								</li> 
								<li class="tab"> 
									<a href="#tab37" data-toggle="tab" aria-expanded="false"> 
										<span class=""><i class="zmdi zmdi-city-alt zmdi-hc-fw"></i></span> 
										<span class="hidden-xs">Upstock</span> 
									</a> 
								</li> 
							</ul> 
							<div class="tab-content"> 
								<div class="tab-pane animated fadeIn active" id="tab36"> 
									<div class="table-responsive">
										<table id="example5" class="table table-bordered t-h-info t-b-info">
											<thead>
												<tr>
													<th class="text-center">Name</th>
													<th class="text-center">Contact No</th>
													<th class="text-center">POA Status</th>
													<th class="text-center">Segments</th>
													<th class="text-center act-sort">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php	
													if(!empty($no_poa_angel))
													{
														foreach($no_poa_angel as $no_poa_angel_data){
												?>
															<tr class="text-center">
																<td><?php echo $no_poa_angel_data['contact_name']; ?></td>
																<td><?php echo $no_poa_angel_data['contact_no']; ?></td>
																<td><?php echo $no_poa_angel_data['poa_text_status']; ?></td>
																<td><?php echo $no_poa_angel_data['segments']; ?></td>
																<td>
																	<a href="https://api.whatsapp.com/send?phone=91<?php echo $no_poa_angel_data['whatsapp_no']; ?>" target="_blank" class="waves-effect waves-light"> <i class="fa fa-whatsapp"></i> </a>
																	&nbsp;
																	<a href="javascript:void(0);" onclick="view_Contact('<?php echo $no_poa_angel_data['id']; ?>');" class=" waves-effect waves-light"> <i class="fa fa-eye"></i> </a>
																</td>
															</tr>
												<?php
														}
													}
												?>
											</tbody>
										</table>
									</div> 
								</div> 
								<div class="tab-pane animated fadeIn" id="tab37">
									<div class="table-responsive">
										<table id="example6" class="table table-bordered t-h-info t-b-info">
											<thead>
												<tr>
													<th class="text-center">Name</th>
													<th class="text-center">Contact No</th>
													<th class="text-center">POA Status</th>
													<th class="text-center">Segments</th>
													<th class="text-center act-sort">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php	
													if(!empty($no_poa_upstock))
													{
														foreach($no_poa_upstock as $no_poa_upstock_data){
												?>
															<tr class="text-center">
																<td><?php echo $no_poa_upstock_data['contact_name']; ?></td>
																<td><?php echo $no_poa_upstock_data['contact_no']; ?></td>
																<td><?php echo $no_poa_upstock_data['poa_text_status']; ?></td>
																<td><?php echo $no_poa_upstock_data['segments']; ?></td>
																<td>
																	<a href="https://api.whatsapp.com/send?phone=91<?php echo $no_poa_upstock_data['whatsapp_no']; ?>" target="_blank" class="waves-effect waves-light"> <i class="fa fa-whatsapp"></i> </a>
																	&nbsp;
																	<a href="javascript:void(0);" onclick="view_Contact('<?php echo $no_poa_upstock_data['id']; ?>');" class="waves-effect waves-light"> <i class="fa fa-eye"></i> </a>
																</td>
															</tr>
												<?php
														}
													}
												?>
											</tbody>
										</table>
									</div> 
								</div> 
							</div> 
						</div>
					</div> -->
					<!--End Row-->
					<!-- <div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Pending Issues</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table id="example7" class="table table-bordered  t-h-info t-b-info">
											<thead>
												<tr>
													<th class="text-center">Name</th>
													<th class="text-center">Contact No.</th>
													<th class="text-center">Pending For</th>
													<th class="text-center">Company Name</th>
													<th class="text-center act-sort">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php	
													if(!empty($pending_issue))
													{
														foreach($pending_issue as $pending_issue_data){
												?>
															<tr class="text-center">
																<td><?php echo $pending_issue_data['contact_name']; ?></td>
																<td><?php echo $pending_issue_data['contact_no']; ?></td>
																<td><?php echo implode("<br>",explode(",",$pending_issue_data['pending_issue'])); ?></td>
																<td><?php echo $pending_issue_data['trade_company_text']; ?></td>
																<td>
																	<a href="https://api.whatsapp.com/send?phone=91<?php echo $pending_issue_data['whatsapp_no']; ?>" target="_blank" class="waves-effect waves-light"> <i class="fa fa-whatsapp"></i> </a>
																	&nbsp;
																	<a href="javascript:void(0);" onclick="view_Contact('<?php echo $pending_issue_data['id']; ?>');" class="waves-effect waves-light"> <i class="fa fa-eye"></i> </a>
																</td>
															</tr>
												<?php
														}
													}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Whatsapp Broadcast List</h3>
								</div>
								<div class="panel-body">
									<div class="portlet light">
										<div class="portlet-body form">
											<form method="post" id="whatsappBroadcastList" action="<?php echo base_url().'dashboard';?>">
											<div class="form-body">
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<label class="logs-leb"> Select Whatsapp Group </label>
															<select class="form-control" name="wp_broadcast_segment" id="wp_broadcast_segment">
																<option value="">Please select</option>
																<option value="All 1" <?php if($wp_broadcast_segment == 'All 1'){ echo 'selected'; }?>>All 1</option>
																<option value="All 2" <?php if($wp_broadcast_segment == 'All 2'){ echo 'selected'; }?>>All 2</option>
																<option value="Angel" <?php if($wp_broadcast_segment == 'Angel'){ echo 'selected'; }?>>Angel</option>
																<option value="Upstock" <?php if($wp_broadcast_segment == 'Upstock'){ echo 'selected'; }?>>Upstock</option>
																<option value="Other" <?php if($wp_broadcast_segment == 'Other'){ echo 'selected'; }?>>Other</option>
																<option value="Equity" <?php if($wp_broadcast_segment == 'Equity'){ echo 'selected'; }?>>Equity</option>
																<option value="Future" <?php if($wp_broadcast_segment == 'Future'){ echo 'selected'; }?>>Future</option>
																<option value="Option" <?php if($wp_broadcast_segment == 'Option'){ echo 'selected'; }?>>Option</option>
															</select>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label class="logs-leb">&nbsp;</label><br>
															<button class="btn btn-success">Search</button>
															&nbsp;<button type="button" class="btn btn-danger" onclick="window.location.href='<?php echo base_url().'dashboard'; ?>'">Clear Filter</button>
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
										<table id="example8" class="table table-bordered  t-h-info t-b-info">
											<thead>
												<tr>
													<th class="text-center">Name</th>
													<th class="text-center">Contact No.</th>
													<th class="text-center">Group Name</th>
													<th class="text-center">Company Name</th>
													<th class="text-center act-sort">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php	
													if(!empty($whatsapp_broadcast_list))
													{
														foreach($whatsapp_broadcast_list as $whatsapp_broadcast_list_data){
												?>
															<tr class="text-center">
																<td><?php echo $whatsapp_broadcast_list_data['contact_name']; ?></td>
																<td><?php echo $whatsapp_broadcast_list_data['contact_no']; ?></td>
																<td><?php echo implode("<br>",explode(",",$whatsapp_broadcast_list_data['whatsapp_broadcast_list'])); ?></td>
																<td><?php echo $whatsapp_broadcast_list_data['trade_company_text']; ?></td>
																<td>
																	<a href="https://api.whatsapp.com/send?phone=91<?php echo $whatsapp_broadcast_list_data['whatsapp_no']; ?>" target="_blank" class="waves-effect waves-light"> <i class="fa fa-whatsapp"></i> </a>
																	&nbsp;
																	<a href="javascript:void(0);" onclick="view_Contact('<?php echo $whatsapp_broadcast_list_data['id']; ?>');" class="waves-effect waves-light"> <i class="fa fa-eye"></i> </a>
																</td>
															</tr>
												<?php
														}
													}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div> -->
					
					<!--End Dashboard Content-->
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
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/form/contacts.js" type="text/javascript"></script>
	<!-- <script src="<?php echo base_url().ADMIN_THEME;?>assets/plugins/date-time-pickers/js/flatpickr.js" type="text/javascript"></script>
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/plugins/date-time-pickers/js/date-time-picker-script.js"></script> -->
	<script>
	jQuery(document).ready(function() {   
		FormValidation.init();
	});
	var WEBSITE_URL = '<?php echo base_url(); ?>';
	
	// $(document).ready(function() {
		
	// 	$("#snooze_date").flatpickr({
	// 		minDate: new Date(),
	// 		mode: "range",
	// 		dateFormat: "Y-m-d",
	// 	});
		
	// 	$('#example').DataTable({
	// 		responsive: true,
	// 		"lengthMenu": [10,20,40,60,80,100],
	// 		"pageLength": 5,
	// 		"order": [[ 3, "asc" ]],
	// 		"columnDefs" : [ { orderable: false, targets: [5] } ],
	// 	});
	// 	$('#example2').DataTable({
	// 		responsive: true,
	// 		"lengthMenu": [10,20,40,60,80,100],
	// 		"pageLength": 5
	// 	});
	// 	$('#example3').DataTable({
	// 		responsive: true,
	// 		"lengthMenu": [10,20,40,60,80,100],
	// 		"pageLength": 5
	// 	});
	// 	$('#example4').DataTable({
	// 		responsive: true,
	// 		"lengthMenu": [10,20,40,60,80,100],
	// 		"pageLength": 5
	// 	});
	// 	$('#example5').DataTable({
	// 		responsive: true,
	// 		"lengthMenu": [10,20,40,60,80,100],
	// 		"pageLength": 5
	// 	});
	// 	$('#example6').DataTable({
	// 		responsive: true,
	// 		"lengthMenu": [10,20,40,60,80,100],
	// 		"pageLength": 5
	// 	});
	// 	$('#example7').DataTable({
	// 		responsive: true,
	// 		"lengthMenu": [10,20,40,60,80,100],
	// 		"pageLength": 5
	// 	});
	// 	$('#example8').DataTable({
	// 		responsive: true,
	// 		"lengthMenu": [10,20,40,60,80,100],
	// 		"pageLength": 5
	// 	});
	// });
	</script>
	<!-- <script src="<?php echo base_url().ADMIN_THEME;?>assets/plugins/switchery/js/switchery.min.js"></script> -->
	<!-- <script type="text/javascript">
	  var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch2'));
	  $('.js-switch2').each(function() {
			new Switchery($(this)[0], $(this).data());
	   });
	</script> -->
	<!-- <script src="<?php echo base_url().ADMIN_THEME;?>assets/plugins/bootstrap-switch/bootstrap-switch.min.js"></script> -->
	<!-- <script type="text/javascript">
	        $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
	        var radioswitch = function() {
	            var bt = function() {
	                $(".radio-switch").on("switch-change", function() {
	                    $(".radio-switch").bootstrapSwitch("toggleRadioState")
	                }), $(".radio-switch").on("switch-change", function() {
	                    $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck")
	                }), $(".radio-switch").on("switch-change", function() {
	                    $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck", !1)
	                })
	            };
	            return {
	                init: function() {
	                    bt()
	                }
	            }
	        }();
	        $(document).ready(function() {
	            radioswitch.init()
	        });
	</script>
	<script>
		$(document).ready(function() {
			
			$(".js-switch2").change(function() {
				var userid = $(this).data('userid');
				var is_active = 0;
				if(this.checked) {
					is_active = 1;
				}else{
					is_active = 0;
				}
				
				$(".overlay-loader").show();
					
					var url = WEBSITE_URL+"contacts/ajaxChangeContactStatus";
					
					$.ajax({
						type: 'POST',
						url: url,
						data: {userid : userid,is_active : is_active},
						success: function(resp) 
						{
							$(".overlay-loader").hide();
							if(resp.status == true){
								var options = 
								{
									status:"success",
									sound:true,
									duration:3500
								};
	
								mkNoti(
									"Success",
									resp.message,
									options
								);
							} else{
								var options = 
								{
									status:"danger",
									sound:true,
									duration:3500
								};
	
								mkNoti(
									"Fail",
									resp.message,
									options
								);
							}	
						}
					});
			});
		});
		
		function view_Contact(val){
			$("#view_contact_details").modal();
			$(".overlay-loader").show();	
			
			var url = WEBSITE_URL+"contacts/ajaxGetContactDetails";
			
			$.ajax({
				type: 'POST',
				url: url,
				data: {userid : val},
				success: function(resp) 
				{
					$(".overlay-loader").hide();
	
					$(".contact_name").text(resp.contact_name);
					$("#cid").val(resp.id);
					$(".contact_no").text(resp.contact_no);					
					$(".whatsapp_no").text(resp.whatsapp_no);
					$(".trade_company_text").text(resp.trade_company_text);
					
					if(resp.trade_company == 1 || resp.trade_company == 2){
						$(".is_company_selected").show();
						$(".client_id").text(resp.client_id);
						$(".poa_text_status").text(resp.poa_text_status);
					} else {
						$(".is_company_selected").hide();
					}
					
					$(".segments").text(resp.segments);
					
					if(resp.payment_type == 1){
						$(".is_paid_selected").show();
						$(".current_amount").text(resp.current_amount);
						
						if(resp.duration_type == 1){
							$(".duration_type").text('Day');	
						}else if(resp.duration_type == 2){
							$(".duration_type").text('Month');	
						}else  if(resp.duration_type == 3){
							$(".duration_type").text('Year');	
						}
						
						$(".duration_value").text(resp.duration_value);
						
						var start_date = new Date(resp.start_date)
						var start_date = start_date.getDate() + "-" + (start_date.getMonth() + 1) + "-" + start_date.getFullYear()
						
						$(".start_date").text(start_date);
						
						var end_date = new Date(resp.end_date)
						var end_date = end_date.getDate() + "-" + (end_date.getMonth() + 1) + "-" + end_date.getFullYear()
						
						$(".end_date").text(end_date);
						$(".payment_type").text('Paid');
					}else{
						$(".is_paid_selected").hide();
						$(".payment_type").text('Free');
					}
					
					$(".note").text(resp.note);
					$("#edit_button").attr('href',WEBSITE_URL+'contacts/edit/'+resp.id);
				}
			});
		}
	</script>
	<script>
		$('#snooze_form').validate({
			rules: {
				snooze_date:{
					required: true
				}
			},	
			submitHandler: function(form) {
				$("#snooze_save").attr("disabled",true);
				$(".overlay-loader").show();
				var data = new FormData(form);
				
				$.ajax({
					url: '<?php echo base_url();?>dashboard/ajaxSnoozeUser', 
					type: "POST",             
					data: data,
					processData:false,
					contentType:false,
					cache:false,
					async:false,
					success: function(response) 
					{
						if(response.status == true) {
							$(".overlay-loader").hide();
							$("#snooze_save").attr("disabled",false);
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
							   location.reload();
							},2500);
						} else {
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
							
							$("#snooze_save").attr("disabled",false);
							return false;
						}	
					}
				});
				return false;
			}
		});
	</script> -->
</body>
</html>