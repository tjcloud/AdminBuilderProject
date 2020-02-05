<?php 
$this->load->view('../../header');
?>
<link href="<?php echo base_url().ADMIN_THEME;?>assets/plugins/date-time-pickers/css/flatpicker-airbnb.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url().ADMIN_THEME;?>assets/plugins/bootstrap-clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css"/>
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
							<h4 class="pull-left text-uppercase">Contact List</h4>
							<ol class="breadcrumb pull-right">
								<li><a href="<?php echo base_url();?>" class="text-info">Dashboard</a></li>
								<li><a href="<?php echo base_url();?>contacts">Contact List</a></li>
								<li class="active">Add Contact</li>
							</ol>
						</div>
					</div>
					<!-- End Page Breadcrumb -->
					<div class="row m-t-20">
						<div class="col-sm-12">
							<div class="panel panel-default">
								<div class="panel-body">
									<form role="form" id="add_contact" action="<?php echo base_url();?>contacts/ajaxEditContact" method="post" enctype="multipart/form-data">
										<input type="hidden" name="contact_id" id="contact_id" value="<?php if(isset($contact_details[0]['id'])){ echo $contact_details[0]['id']; } ?>">
										<h4 class="form-header">
											<i class="fa fa-mobile"></i>
											Contact Details
										</h4>
										<div class="form-group">
											<div class="row">
												<div class="col-md-6">
													<label class="control-label">Name :</label>
													<input type="text" class="form-control" name="contact_name" id="contact_name" autocomplete="off" value="<?php echo (isset($contact_details[0]['contact_name'])) ? $contact_details[0]['contact_name'] : "";?>">
												</div>
												<div class="col-md-6">
													<label class="control-label">Contact No :</label>
													<input type="text" class="form-control" name="contact_no" id="contact_no" autocomplete="off" onKeyPress="return validQty2(event,this.value);" value="<?php echo (isset($contact_details[0]['contact_no'])) ? $contact_details[0]['contact_no'] : "";?>"> 
												</div>
											</div>
										</div>	
										<div class="form-group">
											<div class="row">
												<div class="col-md-6">
													<label class="control-label">Whatsapp No :</label>
													<input type="text" class="form-control" name="whatsapp_no" id="whatsapp_no" autocomplete="off" onKeyPress="return validQty2(event,this.value);" value="<?php echo (isset($contact_details[0]['whatsapp_no'])) ? $contact_details[0]['whatsapp_no'] : "";?>">
												</div>
												<div class="col-md-6">
													<div style="margin-top: 6%;">
														<input type="checkbox" class="filled-in chk-col-success" name="is_same_contact" id="is_same_contact">
														<label for="is_same_contact">Is whatsapp no same as above?</label>
													</div>
												</div>
											</div>
										</div>	
										<div class="form-group">
											<div class="row">
												<div class="col-md-4">
													<label class="control-label">Trade Company :</label>
													<div>
														<input name="trade_company" type="radio" id="radio_9" class="radio-col-success" value="1" <?php if(isset($contact_details[0]['trade_company']) && $contact_details[0]['trade_company'] == '1'){ echo 'checked'; }?>>
														<label for="radio_9">Angel</label>
														<input name="trade_company" type="radio" id="radio_10" class="radio-col-success" value="2" <?php if(isset($contact_details[0]['trade_company']) && $contact_details[0]['trade_company'] == '2'){ echo 'checked'; }?>>
														<label for="radio_10">Upstock</label>
														<input name="trade_company" type="radio" id="radio_11" class="radio-col-success" value="3" <?php if(isset($contact_details[0]['trade_company']) && $contact_details[0]['trade_company'] == '3'){ echo 'checked'; }?>>
														<label for="radio_11">Other</label>
														
														<input name="trade_company_text" type="hidden" id="trade_company_text" value="<?php echo (isset($contact_details[0]['trade_company_text'])) ? $contact_details[0]['trade_company_text'] : "";?>">
													</div>
												</div>
												<div class="col-md-4 hide_this" style="<?php if((isset($contact_details[0]['trade_company']) || isset($contact_details[0]['trade_company'])) && ($contact_details[0]['trade_company'] == '1' || $contact_details[0]['trade_company'] == '2')){ ?>display:block;<?php }else{?>display:none;<?php }?>">
													<label class="control-label">Client ID :</label>
													<input type="text" class="form-control" name="client_id" id="client_id" autocomplete="off" value="<?php echo (isset($contact_details[0]['client_id'])) ? $contact_details[0]['client_id'] : "";?>">
												</div>
												<div class="col-md-4 hide_this" style="<?php if((isset($contact_details[0]['trade_company']) || isset($contact_details[0]['trade_company'])) && ($contact_details[0]['trade_company'] == '1' || $contact_details[0]['trade_company'] == '2')){ ?>display:block;<?php }else{?>display:none;<?php }?>">
													<label class="control-label">POA Status :</label>
													<select class="form-control" name="poa_status" id="poa_status" onchange="Set_Status_Text(this.value);" >
														<option value="">Please select</option>
														<option value="0" <?php if(isset($contact_details[0]['poa_status']) && $contact_details[0]['poa_status'] == '0'){ echo 'selected'; }?>>Fresh</option>
														<option value="1" <?php if(isset($contact_details[0]['poa_status']) && $contact_details[0]['poa_status'] == '1'){ echo 'selected'; }?>>Sent</option>
														<option value="2" <?php if(isset($contact_details[0]['poa_status']) && $contact_details[0]['poa_status'] == '2'){ echo 'selected'; }?>>Recived</option>
														<option value="3" <?php if(isset($contact_details[0]['poa_status']) && $contact_details[0]['poa_status'] == '3'){ echo 'selected'; }?>>Submitted</option>
													</select>
													<input type="hidden" name="poa_text_status" id="poa_text_status" value="Fresh">
												</div>
												<div class="col-md-8">
													<?php 
														$segments = array();
														if(isset($contact_details[0]['segments'])){
															$segments = explode(",",$contact_details[0]['segments']);
														}
													?>
													<label class="control-label">Segments :</label>
													<div>
														<input type="checkbox" class="filled-in chk-col-success" name="segments[]" id="segments1" value="Equity" <?php if(in_array('Equity',$segments)){ echo 'checked'; }?>>
														<label for="segments1">Equity</label>
														&nbsp;
														<input type="checkbox" class="filled-in chk-col-success" name="segments[]" id="segments2" value="Future" <?php if(in_array('Future',$segments)){ echo 'checked'; }?>>
														<label for="segments2">Future</label>
														&nbsp;
														<input type="checkbox" class="filled-in chk-col-success" name="segments[]" id="segments3" value="Option" <?php if(in_array('Option',$segments)){ echo 'checked'; }?>>
														<label for="segments3">Option</label>
														&nbsp;
														<input type="checkbox" class="filled-in chk-col-success" name="segments[]" id="segments4" value="Positional" <?php if(in_array('Positional',$segments)){ echo 'checked'; }?>>
														<label for="segments4">Positional</label>
													</div>
												</div>
											</div>
										</div>	
										<div class="form-group">
											<div class="row">
												<div class="col-md-4">
													<label class="control-label">Payment Type :</label>
													<div>
														<input name="payment_type" type="radio" id="radio_12" class="radio-col-success" value="0" <?php if(isset($contact_details[0]['payment_type']) && $contact_details[0]['payment_type'] == '0'){ echo 'checked'; }?>>
														<label for="radio_12">Free</label>
														<input name="payment_type" type="radio" id="radio_13" class="radio-col-success" value="1" <?php if(isset($contact_details[0]['payment_type']) && $contact_details[0]['payment_type'] == '1'){ echo 'checked'; }?>>
														<label for="radio_13">Paid</label>
													</div>
												</div>
											</div>
										</div>	
										<div class="form-group plan_div" style="<?php if(isset($contact_details[0]['payment_type']) && $contact_details[0]['payment_type'] == '1'){ ?>display:block;<?php }else{?>display:none;<?php }?>">
											<div class="row">
												<div class="col-md-3">
													<label class="control-label">Amount :</label>
													<input type="text" class="form-control" name="current_amount" id="current_amount" autocomplete="off" onKeyPress="return validQty(event,this.value);" value="<?php echo (isset($contact_details[0]['current_amount']) && $contact_details[0]['current_amount']) ? $contact_details[0]['current_amount'] : "";?>">
												</div>
												<div class="col-md-3">
													<label class="control-label">Duration Type :</label>
													<select class="form-control" name="duration_type" id="duration_type" autocomplete="off" onchange="getEndDate();">
														<option value="">Please select</option>
														<option value="1" <?php if(isset($contact_details[0]['duration_type']) && $contact_details[0]['duration_type'] == '1'){ echo 'selected'; }?>>Days</option>
														<option value="2" <?php if(isset($contact_details[0]['duration_type']) && $contact_details[0]['duration_type'] == '2'){ echo 'selected'; }?>>Month</option>
														<option value="3" <?php if(isset($contact_details[0]['duration_type']) && $contact_details[0]['duration_type'] == '3'){ echo 'selected'; }?>>Year</option>
													</select>
												</div>
												<div class="col-md-3">
													<label class="control-label">Duration Value :</label>
													<input type="text" class="form-control" name="duration_value" id="duration_value" autocomplete="off" onkeyup="getEndDate();" onKeyPress="return validQty2(event,this.value);" value="<?php echo (isset($contact_details[0]['duration_value'])) ? $contact_details[0]['duration_value'] : "";?>">
												</div>
												<div class="col-md-3">
													<label class="control-label">&nbsp;</label>
													<div>
														<input type="checkbox" class="filled-in chk-col-success" name="is_new_payment_entry" id="is_new_payment_entry" value="1">
														<label for="is_new_payment_entry">Is New Payment</label>
														<p style="color : red;font-weight: bolder;">Notes : Tick this when you change "Free" to "Paid" or "Renew"
														</p>		
													</div>
												</div>
											</div>
										</div>	
										<div class="form-group plan_div" style="<?php if(isset($contact_details[0]['payment_type']) && $contact_details[0]['payment_type'] == '1'){ ?>display:block;<?php }else{?>display:none;<?php }?>">
											<div class="row">
												<div class="col-md-6">
													<label class="control-label">Start Date :</label>
													<input type="text" class="form-control" name="start_date" id="start_date" autocomplete="off"  value="<?php echo (isset($contact_details[0]['start_date']) && $contact_details[0]['start_date'] != '0000-00-00 00:00:00') ? date("Y-m-d",strtotime($contact_details[0]['start_date'])) : "";?>">
												</div>
												<div class="col-md-6">
													<label class="control-label">End Date :</label>
													<input type="text" class="form-control" name="end_date" id="end_date" autocomplete="off"  readonly value="<?php echo (isset($contact_details[0]['end_date']) && $contact_details[0]['start_date'] != '0000-00-00 00:00:00') ? date("Y-m-d",strtotime($contact_details[0]['end_date'])) : "";?>">
												</div>
											</div>
										</div>	
										<div class="form-group plan_div" style="<?php if(isset($contact_details[0]['payment_type']) && $contact_details[0]['payment_type'] == '1'){ ?>display:block;<?php }else{?>display:none;<?php }?>">
											<div class="row">
												<div class="col-md-12">
													<label class="control-label">Payment for which segment? </label>
													<div>
														<input name="payment_for_segment" type="radio" id="radio_22" class="radio-col-success" value="Equity" <?php if(isset($contact_details[0]['payment_for_segment']) && $contact_details[0]['payment_for_segment'] == 'Equity'){ echo 'checked'; }?> >
														<label for="radio_22">Equity</label>
														<input name="payment_for_segment" type="radio" id="radio_23" class="radio-col-success" value="Future" <?php if(isset($contact_details[0]['payment_for_segment']) && $contact_details[0]['payment_for_segment'] == 'Future'){ echo 'checked'; }?>>
														<label for="radio_23">Future</label>
														<input name="payment_for_segment" type="radio" id="radio_24" class="radio-col-success" value="Option" <?php if(isset($contact_details[0]['payment_for_segment']) && $contact_details[0]['payment_for_segment'] == 'Option'){ echo 'checked'; }?>>
														<label for="radio_24">Option</label>
														<input name="payment_for_segment" type="radio" id="radio_25" class="radio-col-success" value="Positional" <?php if(isset($contact_details[0]['payment_for_segment']) && $contact_details[0]['payment_for_segment'] == 'Positional'){ echo 'checked'; }?>>
														<label for="radio_25">Positional</label>
													</div>
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<div class="row">
												<div class="col-md-12">
												
													<?php 
														$pending_issue = array();
														if(isset($contact_details[0]['pending_issue'])){
															$pending_issue = explode(",",$contact_details[0]['pending_issue']);
														}
													?>
												
													<label class="control-label">Pending Issue :</label>
													<div>
														<input type="checkbox" class="filled-in chk-col-success" name="pending_issue[]" id="pending_issue1" value="Payment Pending" <?php if(in_array('Payment Pending',$pending_issue)){ echo 'checked'; }?>>
														<label for="pending_issue1">Payment Pending</label>
														&nbsp;
														<input type="checkbox" class="filled-in chk-col-success" name="pending_issue[]" id="pending_issue2" value="Angle Acc. Pending" <?php if(in_array('Angle Acc. Pending',$pending_issue)){ echo 'checked'; }?>>
														<label for="pending_issue2">Angle Acc. Pending</label>
														&nbsp;
														<input type="checkbox" class="filled-in chk-col-success" name="pending_issue[]" id="pending_issue3" value="Upstock Acc. Pending" <?php if(in_array('Upstock Acc. Pending',$pending_issue)){ echo 'checked'; }?>>
														<label for="pending_issue3">Upstock Acc. Pending</label>
														&nbsp;
														<input type="checkbox" class="filled-in chk-col-success" name="pending_issue[]" id="pending_issue4" value="Whatsapp Broadcast Add Pending" <?php if(in_array('Whatsapp Broadcast Add Pending',$pending_issue)){ echo 'checked'; }?>>
														<label for="pending_issue4">Whatsapp Broadcast Add Pending</label>
														<input type="checkbox" class="filled-in chk-col-success" name="pending_issue[]" id="pending_issue5" value="Other Pending Issue" <?php if(in_array('Other Pending Issue',$pending_issue)){ echo 'checked'; }?>>
														<label for="pending_issue5">Other Pending Issue</label>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-md-12">
												
													<?php 
														$whatsapp_broadcast_list = array();
														if(isset($contact_details[0]['whatsapp_broadcast_list'])){
															$whatsapp_broadcast_list = explode(",",$contact_details[0]['whatsapp_broadcast_list']);
														}
													?>
												
													<label class="control-label">Whatsapp Broadcast List :</label>
													<div>
														<input type="checkbox" class="filled-in chk-col-success" name="whatsapp_broadcast_list[]" id="whatsapp_broadcast_list1" value="All 1" <?php if(in_array('All 1',$whatsapp_broadcast_list)){ echo 'checked'; }?>>
														<label for="whatsapp_broadcast_list1">All 1</label>
														&nbsp;
														<input type="checkbox" class="filled-in chk-col-success" name="whatsapp_broadcast_list[]" id="whatsapp_broadcast_list2" value="All 2" <?php if(in_array('All 2',$whatsapp_broadcast_list)){ echo 'checked'; }?>>
														<label for="whatsapp_broadcast_list2">All 2</label>
														&nbsp;
														<input type="checkbox" class="filled-in chk-col-success" name="whatsapp_broadcast_list[]" id="whatsapp_broadcast_list3" value="Angel" <?php if(in_array('Angel',$whatsapp_broadcast_list)){ echo 'checked'; }?>>
														<label for="whatsapp_broadcast_list3">Angel</label>
														&nbsp;
														<input type="checkbox" class="filled-in chk-col-success" name="whatsapp_broadcast_list[]" id="whatsapp_broadcast_list4" value="Upstock" <?php if(in_array('Upstock',$whatsapp_broadcast_list)){ echo 'checked'; }?>>
														<label for="whatsapp_broadcast_list4">Upstock</label>
														<input type="checkbox" class="filled-in chk-col-success" name="whatsapp_broadcast_list[]" id="whatsapp_broadcast_list5" value="Other" <?php if(in_array('Other',$whatsapp_broadcast_list)){ echo 'checked'; }?>>
														<label for="whatsapp_broadcast_list5">Other</label>
														&nbsp;
														<input type="checkbox" class="filled-in chk-col-success" name="whatsapp_broadcast_list[]" id="whatsapp_broadcast_list6" value="Equity" <?php if(in_array('Equity',$whatsapp_broadcast_list)){ echo 'checked'; }?>>
														<label for="whatsapp_broadcast_list6">Equity</label>
														&nbsp;
														<input type="checkbox" class="filled-in chk-col-success" name="whatsapp_broadcast_list[]" id="whatsapp_broadcast_list7" value="Future" <?php if(in_array('Future',$whatsapp_broadcast_list)){ echo 'checked'; }?>>
														<label for="whatsapp_broadcast_list7">Future</label>
														&nbsp;
														<input type="checkbox" class="filled-in chk-col-success" name="whatsapp_broadcast_list[]" id="whatsapp_broadcast_list8" value="Option" <?php if(in_array('Option',$whatsapp_broadcast_list)){ echo 'checked'; }?>>
														<label for="whatsapp_broadcast_list8">Option</label>
														
													</div>
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<div class="row">
												<div class="col-md-12">
													<label class="control-label">Notes :</label>
													<textarea class="form-control" name="note" id="note" autocomplete="off" rows="5"><?php echo (isset($contact_details[0]['note'])) ? $contact_details[0]['note'] : "";?></textarea>
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
								<!-- panel-body -->
							</div>
							<!-- panel -->
						</div>
						<!-- col -->
					</div>
					<div class="row m-t-20">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Attendance Report</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table id="example" class="table table-bordered t-h-info t-b-info">
											<thead>
												<tr>
													<th class="text-center">Month</th>
													<th class="text-center">Start Date</th>
													<th class="text-center">End Date</th>
													<th class="text-center">Total Treding Days</th>
													<th class="text-center">Total Trade Days</th>
													<th class="text-center">Percentage</th>
													<th class="text-center">Status</th>
												</tr>
											</thead>
											<tbody>
												<?php
													if(!empty($attandace_report)){
														foreach($attandace_report as $attandace_report_data){
															?>
															<tr class="text-center">
																<td><?php echo $attandace_report_data['Month']; ?></td>
																<td><?php echo $attandace_report_data['start_date']; ?></td>
																<td><?php echo $attandace_report_data['end_date']; ?></td>
																<td><?php echo $attandace_report_data['total_treding_days']; ?></td>
																<td><?php echo $attandace_report_data['total_trade_days']; ?></td>
																<td><?php echo $attandace_report_data['percentage'].'%'; ?></td>
																<td><?php echo $attandace_report_data['Status']; ?></td>
															</tr>
															<?php
														}
													}
												?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="5" class="text-right">Total Average Percentage</td>
													<td class="text-center"><?php echo $average_percentage.'%'; ?></td>
													<td ></td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>
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
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/form/contacts.js" type="text/javascript"></script>
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/plugins/date-time-pickers/js/flatpickr.js" type="text/javascript"></script>
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/plugins/date-time-pickers/js/date-time-picker-script.js"></script>
	<script>
	jQuery(document).ready(function() {     
		FormValidation.init();
		$('#example').DataTable({
			"ordering": false
		});
		
		$("#start_date").flatpickr({
			minDate: new Date(),
			onChange: function(selectedDates, dateStr, instance) {
				if($("#duration_type").val() == ""){
					$("#duration_type").focus();
					$("#start_date").val("");
					var options = 
					{
						status:"danger",
						sound:true,
						duration:3500
					};
					mkNoti(
						"Fail",
						"Please select duration type",
						options
					);
				}else if($("#duration_value").val() == ""){
					$("#duration_value").focus();
					$("#start_date").val("");
					var options = 
					{
						status:"danger",
						sound:true,
						duration:3500
					};
					mkNoti(
						"Fail",
						"Please select duration value",
						options
					);
				} else {
					
					var duration_type = $("#duration_type").val();
					var duration_value = $("#duration_value").val();
					var selected_date = dateStr;
					
					var date = new Date(selected_date),
					    days = parseInt($("#duration_value").val(), 10);
					
					if(duration_type == 1){
						
						if(!isNaN(date.getTime())){
							date.setDate(date.getDate() + days);
							$("#end_date").val(date.toInputFormat());
						} else {
							var options = 
							{
								status:"danger",
								sound:true,
								duration:3500
							};
							mkNoti(
								"Fail",
								"Invalid Date",
								options
							);
						}
						
					} else if(duration_type == 2){
						if(!isNaN(date.getTime())){
							date.setMonth(date.getMonth() + days);
							$("#end_date").val(date.toInputFormat());
						} else {
							var options = 
							{
								status:"danger",
								sound:true,
								duration:3500
							};
							mkNoti(
								"Fail",
								"Invalid Date",
								options
							);
						}
						
					} else if(duration_type == 3){
						
						if(!isNaN(date.getTime())){
							date.setFullYear(date.getFullYear() + days);
							$("#end_date").val(date.toInputFormat());
						} else {
							var options = 
							{
								status:"danger",
								sound:true,
								duration:3500
							};
							mkNoti(
								"Fail",
								"Invalid Date",
								options
							);
						}
						
					}
				}	
			}	
		});
		
		Date.prototype.toInputFormat = function() {
		   var yyyy = this.getFullYear().toString();
		   var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
		   var dd  = this.getDate().toString();
		   var perfect_date = yyyy + "/" + (mm[1]?mm:"0"+mm[0]) + "/" + (dd[1]?dd:"0"+dd[0]); // padding
		   
		   var mkdate = new Date(perfect_date);
		   
		   if(isNaN(mkdate)){
				return '0000-00-00';
		   }else{
			   return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding;
		   }
		};
		
		window.getEndDate = function(){
			
			var duration_type = $("#duration_type").val();
			var duration_value = $("#duration_value").val();
			var selected_date = $("#start_date").val();
			
			if(selected_date != ""){
				var date = new Date(selected_date),
					days = parseInt($("#duration_value").val(), 10);
				
				if(duration_type == 1){
					
					if(!isNaN(date.getTime())){
						date.setDate(date.getDate() + days);
						$("#end_date").val(date.toInputFormat());
					} else {
						var options = 
						{
							status:"danger",
							sound:true,
							duration:3500
						};
						mkNoti(
							"Fail",
							"Invalid Date",
							options
						);
					}
					
				} else if(duration_type == 2){
					if(!isNaN(date.getTime())){
						date.setMonth(date.getMonth() + days);
						$("#end_date").val(date.toInputFormat());
					} else {
						var options = 
						{
							status:"danger",
							sound:true,
							duration:3500
						};
						mkNoti(
							"Fail",
							"Invalid Date",
							options
						);
					}
					
				} else if(duration_type == 3){
					
					if(!isNaN(date.getTime())){
						date.setFullYear(date.getFullYear() + days);
						$("#end_date").val(date.toInputFormat());
					} else {
						var options = 
						{
							status:"danger",
							sound:true,
							duration:3500
						};
						mkNoti(
							"Fail",
							"Invalid Date",
							options
						);
					}
					
				}
			}
		}
	});
	</script>
	<script>	
		$(document).ready(function() {
			$('#is_same_contact').change(function() {
				if(this.checked) {
					var contact_no = $("#contact_no").val();	
					$("#whatsapp_no").val(contact_no);
				}else{
					$("#whatsapp_no").val("");
				}
			});
			
			$('input[name=trade_company]').change(function(){
				var trade_company = $( 'input[name=trade_company]:checked' ).val();
				
				if(trade_company == 1){
					$(".hide_this").css('display','block');
					$("#trade_company_text").val('Angel');
				}else if(trade_company == 2){
					$(".hide_this").css('display','block');
					$("#trade_company_text").val('Upstock');
				}else{
					$(".hide_this").css('display','none');
					$("#trade_company_text").val('Other');
				}
				
			});
			
			$('input[name=payment_type]').change(function(){
				var trade_company2 = $( 'input[name=payment_type]:checked' ).val();
				if(trade_company2 == 1){
					$(".plan_div").css('display','block');
				}else{
					$(".plan_div").css('display','none');
				}
				
			});
		});
	</script>
	<script>
		function Set_Status_Text(val){
			if(val == 0){
				$("#poa_text_status").val('Fresh');
			}else if(val == 1){
				$("#poa_text_status").val('Sent');
			}else if(val == 2){
				$("#poa_text_status").val('Received');
			}else if(val == 3){
				$("#poa_text_status").val('Submitted');
			}else{
				$("#poa_text_status").val('Fresh');
			}
		}
		
		function validQty(checkDigit, boxValue)
		{
			var charCode = (checkDigit.which) ? checkDigit.which : checkDigit.keyCode;
			if(boxValue.length > 100)
			{
				return false;
			}
			else
			{
				if(charCode >31 && (charCode <48 || charCode >57) && charCode != 46)
				{
					return false;	
				}
				return true;
			}
		}
		
		function validQty2(checkDigit, boxValue)
		{
			var charCode = (checkDigit.which) ? checkDigit.which : checkDigit.keyCode;
			if(boxValue.length > 100)
			{
				return false;
			}
			else
			{
				if(charCode >31 && (charCode <48 || charCode >57))
				{
					return false;	
				}
				return true;
			}
		}
	</script>
</body>
</html>