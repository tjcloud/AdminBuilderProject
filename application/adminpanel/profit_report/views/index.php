<?php 
$this->load->view('../../header');
$previous_month = date("m",strtotime('-1 month'));
$previous_month_text = date("M",strtotime('-1 month'));
$current_month = date("m");
$current_month_text = date("M");
?>
<style>
	.table > tbody > tr > td {
		 vertical-align: middle;
		 text-align:center;
	}
	
	.bs-example{
		font-family: sans-serif;
		position: relative;
		margin: 50px;
	}
	.tt-hint {
		border: 2px solid #CCCCCC;
		border-radius: 8px;
		font-size: 24px;
		height: 30px;
		line-height: 30px;
		outline: medium none;
		padding: 8px 12px;
		width: 396px;
	}
	.typeahead {
		background-color: #FFFFFF;
	}
	.typeahead:focus {
		border: 2px solid #0097CF;
	}
	.tt-query {
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
	}
	.tt-hint {
		color: #999999;
	}
	.tt-dropdown-menu {
		background-color: #FFFFFF;
		border: 1px solid rgba(0, 0, 0, 0.2);
		border-radius: 8px;
		box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
		margin-top: 12px;
		padding: 8px 0;
		width: 422px;
	}
	.tt-suggestion {
		font-size: 18px;
		line-height: 24px;
		padding: 3px 20px;
	}
	.tt-suggestion.tt-is-under-cursor {
		background-color: #0097CF;
		color: #FFFFFF;
	}
	.tt-suggestion p {
		margin: 0;
	}
	.form-control.typeahead.tt-query{
		background-color:none !important;
	}
	div#profit_report_data {
	    margin-top: 20px;
	}
	#report_from:focus{
		border: 1px solid transparent;
	}
	.col-md-4.total-amt-row{
		display: flex;
		align-items: center;
		margin-top: 20px;
	}
	@media (max-width: 375px){
		.col-md-4.total-amt-row{
			flex-direction: column;
			align-items: flex-start;
		}
		.col-md-4.total-amt-row .col-md-6:nth-child(2){
			padding-left: 0;
		}
	}
	
	.t-b-blue{
		border: 2px solid #4285F4 !important;
	}
	.t-h-blue thead tr th {
	  background: #4285F4 !important;
	}
</style>


<link href="<?php echo base_url().ADMIN_THEME;?>assets/plugins/date-time-pickers/css/flatpicker-airbnb.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url().ADMIN_THEME;?>assets/plugins/bootstrap-clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url().ADMIN_THEME;?>assets/plugins/select2/css/select2.min.css" rel="stylesheet"/>
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
					
					<div id="edit_exit_price_modal" class="modal modal-color modal-info fade" tabindex="-1"  aria-labelledby="myModalLabel">
						<div class="modal-dialog">
							<div class="modal-content">
								<form role="form" id="exit_price_form" method="post" enctype="multipart/form-data">
									<input type="hidden" name="record_id" id="record_id">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h4 class="modal-title" id="myModalLabel">Update Exit Price and End Date</h4>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<div class="row">
												<div class="col-md-12">
													<label class="control-label">SL :</label>
													<input type="text" class="form-control" name="sl_price_edit" id="sl_price_edit" autocomplete="off" readonly>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-md-12">
													<label class="control-label">Target :</label>
													<input type="text" class="form-control" name="target_price_edit" id="target_price_edit" autocomplete="off" readonly>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-md-12">
													<label class="control-label">Exit Price :</label>
													<input type="text" class="form-control" name="exit_price_edit" id="exit_price_edit" autocomplete="off" value="0" onKeyPress="return validQty(event,this.value);">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-md-12">
													<label class="control-label">End Date :</label>
													<input type="text" class="form-control" name="exit_end_date_edit" id="exit_end_date_edit" autocomplete="off" >
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="submit" class="btn btn-info waves-effect waves-light" id="edit_price_save">Save</button>
										<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">CLOSE</button>
									</div>
								</form>
							</div><!--modal-content -->
						</div><!--modal-dialog -->
					</div><!-- modal -->
					
					<!-- Page Breadcrumb -->
					<div class="row breadcrumb-area">
						<div class="col-sm-12">
							<h4 class="pull-left text-uppercase">Profit Report</h4>
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
								<div class="panel-body">
									<div class="portlet light">
										<div class="portlet-body form">
											<h4 class="form-header">
												<i class="fa fa-file"></i>
												Import Lot CSV
											</h4>
											<form method="post" id="import_lot" enctype="multipart/form-data">  
											<div class="form-body">
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label class="logs-leb">Select Month</label>
															<select class="form-control" name="profit_month" id="profit_month">
																<option value="">Please select</option>
																<option value="<?php echo $previous_month; ?>"><?php echo $previous_month_text; ?></option>
																<option value="<?php echo $current_month; ?>"><?php echo $current_month_text; ?></option>
															</select>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="logs-leb"> CSV File </label>
															<input class="form-control" type="file" name="CSV_file" id="CSV_file" accept=".csv" required >
														</div>
													</div>
													<div class="col-md-4">
														<label class="logs-leb">&nbsp;</label>
														<div class="form-actions">
															<button class="btn btn-success" type="submit" id="submit">Import</button>
														</div>
													</div>
												</div>	
											</div>
											</form>
										</div>
									</div>
									<div class="portlet light">
										<div class="portlet-body form">
											<h4 class="form-header">
												<i class="fa fa-search"></i>
												Search and edit lot
											</h4>
											<form method="post" id="edit_lot" enctype="multipart/form-data">  
											<div class="form-body">
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label class="logs-leb"> Company </label>
															<select class="form-control single-select" name="company_name" id="company_name">
																<option value="">Please select</option>
																<?php
																	if(!empty($company_lot)){
																		foreach($company_lot as $company_lot_data){
																			$month_selected = "";
																			$month_selected = date("M",strtotime(date("d-".$company_lot_data['month_report'].'-Y')));
																?>
																<option value="<?php echo $company_lot_data['id']; ?>" data-lotsize="<?php echo $company_lot_data['lot_size']; ?>"><?php echo $company_lot_data['company_name'].' - '.$month_selected; ?></option>
																<?php			
																		}
																	}
																?>
															</select>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="logs-leb"> Lot Size </label>
															<input class="form-control" type="text" name="lot_size" id="lot_size" onKeyPress="return validQty2(event,this.value);">
														</div>
													</div>
													<div class="col-md-4">
														<label class="logs-leb">&nbsp;</label>
														<div class="form-actions">
															<button class="btn btn-success" type="submit" id="submit2">Edit</button>
														</div>
													</div>
												</div>	
											</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row m-t-20">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-body">
									<form role="form" id="calculate_profit_rules" method="post" enctype="multipart/form-data">
										<h4 class="form-header">
											<i class="fa fa-money"></i>
											Calculate & Save Profit Report
										</h4>
										<div class="row html-content-holder" style="background-color:white;">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-3">
														<div class="form-group hide_in_image">
															<label class="control-label">Report Date :</label>
															<input type="text" class="form-control" name="report_date" id="report_date" autocomplete="off" value="">
														</div>
													</div>	
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label">&nbsp;</label>
															<input type="text" class="form-control text-center" name="report_from" id="report_from" autocomplete="off" value="" readonly style="background-color:white; color: #000;">
														</div>
													</div>	 

													<div class="col-md-3">
														<div class="form-group hide_in_image">
															<label class="control-label">Segment :</label>
															<select class="form-control" name="report_segment" id="report_segment" onchange="set_report_from(this.value);">
																<option value="">Please select</option>
																<option value="Equity">Equity</option>
																<option value="Future">Future</option>
																<option value="Option">Option</option>
																<option value="Positional">Positional</option>
															</select>
														</div>
													</div>	
												</div>	
												
												<div class="row">
													<div class="col-md-12" id="profit_report_data">
														<div class="row" id="profit_report_1">
															<div class="col-md-2">
																<div class="form-group">
																	<label class="control-label">Company Name</label>
																	<input type="text" class="form-control typeahead tt-query change_color_it" name="company_name_report[]" id="company_name1" autocomplete="off" spellcheck="false" placeholder="Company Name">
																</div>
																<div class="form-group hide_if_not_positional" style="display:none;">
																	<input type="text" class="form-control" name="sl_price[]" id="sl_price1" autocomplete="off" placeholder="SL" onKeyPress="return validQty(event,this.value);">
																</div>		
															</div>
															<div class="col-md-2">
																<div class="form-group">
																	<label class="control-label">Buy / Sell</label>
																	<select class="form-control change_color_it" name="buy_sell[]" id="buy_sell1" autocomplete="off" onchange="calculate_profit('1');">
																		<option value="Buy">Buy</option>
																		<option value="Sell">Sell</option>
																	</select>
																</div>
																<div class="form-group hide_if_not_positional" style="display:none;">
																	<input type="text" class="form-control" name="target_price[]" id="target_price1" autocomplete="off" placeholder="Target" onKeyPress="return validQty(event,this.value);">
																</div>			
															</div>
															<div class="col-md-2">
																<div class="form-group">
																	<label class="control-label">Entry Price</label>
																	<input type="text" class="form-control change_color_it" name="entry_price[]" id="entry_price1" autocomplete="off" onKeyPress="return validQty(event,this.value);" onkeyup="calculate_profit('1');">
																</div>	
																<div class="form-group hide_if_not_positional" style="display:none;">
																	<input type="text" class="form-control" name="entry_start_date[]" id="entry_start_date1" autocomplete="off" placeholder="Start Date">
																</div>	
															</div>
															<div class="col-md-2">
																<div class="form-group">
																	<label class="control-label">Exit Price</label>
																	<input type="text" class="form-control change_color_it" name="exit_price[]" id="exit_price1" autocomplete="off" onKeyPress="return validQty(event,this.value);" onkeyup="calculate_profit('1');">
																</div>	
																<div class="form-group hide_if_not_positional" style="display:none;">
																	<input type="text" class="form-control" name="exit_end_date[]" id="exit_end_date1" autocomplete="off" placeholder="End Date">
																</div>	
															</div>
															<div class="col-md-2">
																<div class="form-group">
																	<label class="control-label">Lot Size</label>
																	<input type="text" class="form-control change_color_it" name="lot_size[]" id="lot_size1" autocomplete="off" onKeyPress="return validQty2(event,this.value);" onkeyup="calculate_profit('1');">
																</div>	
															</div>
															<div class="col-md-2">
																<a class="hide_this_unused" href="javascript:void(0);"></a>
																<div class="form-group">
																	<label class="control-label">Profit / Loss</label>
																	<input type="text" class="form-control final_total_text" name="final_amount[]" id="final_amount1" autocomplete="off" readonly value="0" style="background-color: white">
																</div>	
															</div>
														</div>
													</div>
												</div>
												
												<div class="form-group">
													<div class="row">
														<!-- <div class="col-md-offset-10 col-md-2">
															<label class="control-label">Final Total :</label>
															<input type="text" class="form-control" name="overall_total" id="overall_total" autocomplete="off" value="0" readonly style="background-color:white;">
														</div> -->
														<div class="col-md-offset-8 col-md-4 total-amt-row"> 
															<div class="col-md-6 text-right" style="padding-left: 0">
																<label class="control-label" style="margin-bottom: 0">Profit / Loss :</label>
															</div>
															<div class="col-md-6" style="padding-right: 0">
																<input type="text" class="form-control" name="overall_total" id="overall_total" autocomplete="off" value="0" readonly style="background-color:white;">
															</div>
														</div>
													</div>
												</div>	
											</div>	
										</div>	
										<div class="form-footer">
											<button type="submit" id="submit3" class="btn btn-success btn-border waves-effect waves-light"><i class="fa fa-check-square-o"></i> SAVE</button>
											&nbsp;
											<button type="button" id="submit4" style="display:none;" class="btn btn-warning btn-border waves-effect waves-light" onclick="window.location.href='<?php echo base_url().'profit_report';?>'"><i class="fa fa-refresh"></i> Refresh</button>
											&nbsp;											
											<a href="javascript:void(0);" id="btn-Convert-Html2Image" class="btn btn-info btn-border waves-effect waves-light hide_if_positional"><i class="fa fa-download"></i> Generate Image</a>
											&nbsp;
											<a href="javascript:void(0);" id="btn-Convert-Html2Image-download" class="btn btn-primary btn-border waves-effect waves-light" style="display:none;"><i class="fa fa-download"></i> Download</a>
											&nbsp;
											<button type="button" id="add_more" class="btn btn-rose btn-border waves-effect waves-light" onclick="add_more_report();"><i class="fa fa-plus"></i> Add more</button>
											<input type="hidden" name="total_row_added" id="total_row_added" value="1">
										</div>
										<p style="color:red;font-size: 16px;font-weight: bold;">Note : If you gonna insert similar dates data again then old data will be removed from the system. Kindly please verify data and hit the submit button.</p>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="row m-t-20">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Profit / Loss Data</h3>
								</div>
								<div class="panel-body">
									<div class="portlet light">
										<div class="portlet-body form">
											<div class="form-actions" style="float:right;">
												<select class="form-control" name="report_segment_filter" id="report_segment_filter" style="width:auto;display: inline;">
													<option value="Equity">Equity</option>
													<option value="Future">Future</option>
													<option value="Option">Option</option>
													<option value="Positional">Positional</option>
												</select>
												&nbsp;
												<input class="form-control" type="text" id="date_range" name="date_range" placeholder="Date range" style="width:auto;display: none;">
												&nbsp;
												<select class="form-control" name="slot_status" id="slot_status" style="width:auto;display: none;">
													<option value="">Please select</option>
													<option value="Open">Open</option>
													<option value="Close">Close</option>
												</select>
												&nbsp;
												<button class="btn btn-success" type="button" onclick="generateWeeklyReport();" id="generateWeeklyReport">Weekly Report (Mon - Fri)</button>
												&nbsp;
												<button class="btn btn-warning" type="button" onclick="generateMonthlyReport();" id="generateMonthlyReport">Monthly Report (1st To 30/31)</button>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
									<br>
									<div class="table-responsive" style="background-color:white;">
										<table id="generated_report" class="table table-bordered t-h-success t-b-success">
											<thead>
												<tr>
													<th class="text-center report_header" colspan="8" style="font-size: 18px;text-transform:capitalize;">Equity Report From <span class="report_date_se"></span></th>
												</tr>
												<tr>
													<th class="text-center">Date</th>
													<th class="text-center status_column" style="display:none;">Status</th>
													<th class="text-center">Company Name</th>
													<th class="text-center">Buy / Sell</th>
													<th class="text-center">Entry Price</th>
													<th class="text-center">Exit Price</th>
													<th class="text-center">Lot Size</th>
													<th class="text-center">Profit / Loss</th>
													<th class="text-center">Total</th>
												</tr>
											</thead>
											<tbody class="report_data">
												<tr>
													<td class="text-center" colspan="8">Click on above buttons to view data.</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td class="text-center gtotal_cols" colspan="7">Grand Total</td>
													<td class="text-center" id="gtotal"></td>
												</tr>
											</tfoot>
										</table>
									</div>
									<div class="form-actions" style="float:right;">
										<a href="javascript:void(0);" id="btn-Convert-Html2Image-new" class="btn btn-info btn-border waves-effect waves-light"><i class="fa fa-download"></i> Generate Image</a>
										&nbsp;
										<a href="javascript:void(0);" id="btn-Convert-Html2Image-download-new" class="btn btn-primary btn-border waves-effect waves-light" style="display:none;"><i class="fa fa-download"></i> Download</a>
										&nbsp;
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
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/plugins/select2/js/select2.min.js"></script>
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/js/html2canvas.js"></script>
	<script>
	var WEBSITE_URL = '<?php echo base_url(); ?>';
	$(document).ready(function() {
		
		$("#report_date").flatpickr({
			maxDate: new Date(),
			dateFormat: "d-m-Y",
			onChange: function(selectedDates, dateStr, instance) {
				var report_segment = $("#report_segment").val();
				
				if(report_segment == 'Equity'){
					$("#report_from").css('background-color','#4caf50');
					$("#report_from").css('color','#ffffff');
				} else if(report_segment == 'Future'){
					$("#report_from").css('background-color','#4285F4');
					$("#report_from").css('color','#ffffff');
				} else if(report_segment == 'Option'){
					$("#report_from").css('background-color','#ffa200');
					$("#report_from").css('color','#ffffff');
				} else if(report_segment == 'Positional'){
					$("#report_from").css('background-color','#ff3e43');
					$("#report_from").css('color','#ffffff');
				} else{
					$("#report_from").css('background-color','#4caf50');
					$("#report_from").css('color','#ffffff');
				}
				
				if(report_segment != ""){
					$("#report_from").val(report_segment+" Report of "+dateStr);
				}
			}
		});
		
		$("#entry_start_date1").flatpickr({
			maxDate: new Date(),
			dateFormat: "d-m-Y"
		});
		$("#exit_end_date1").flatpickr({
			minDate: new Date(),
			dateFormat: "d-m-Y"
		});
		
		$("#exit_end_date_edit").flatpickr({
			minDate: new Date(),
			dateFormat: "d-m-Y"
		});
		
		$('.single-select').select2();
	});
	</script>
	<script>	
		var element = $(".html-content-holder"); // global variable	
		var getCanvas; // global variable	
			
		$("#btn-Convert-Html2Image").on('click', function () {
			
			$(".hide_in_image").hide();
			$(".hide_this_unused").hide();
			$(".change_color_it").css('color','black');
			
			html2canvas(element, {
				onrendered: function (canvas) {
					getCanvas = canvas;
				}
			});
			
			$(".hide_in_image").show();
			$(".hide_this_unused").show();
			$(".change_color_it").css('color','rgba(0, 0, 0, 0.6)');
			
			$("#btn-Convert-Html2Image-download").css("display","inline-block");	
			$("#btn-Convert-Html2Image").css("display","none");	
		});
		
		$("#btn-Convert-Html2Image-download").on('click', function () {
			var imgageData = getCanvas.toDataURL("image/png");
			var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
			
			$("#btn-Convert-Html2Image-download").attr("download", "profit_report.png").attr("href", newData);
			
			$("#btn-Convert-Html2Image-download").css("display","none");	
			$("#btn-Convert-Html2Image").css("display","inline-block");	
		});
		
		var element2 = $(".table-responsive"); // global variable	
		var getCanvas2; // global variable	
		
		$("#btn-Convert-Html2Image-new").on('click', function () {
			
			$(".change_in_image").text("Open");
			$(".change_in_image_finaltotal").text("Open").css('color','#337ab7');
			$(".give_color").css('color','black');
			
			html2canvas(element2, {
				onrendered: function (canvas) {
					getCanvas2 = canvas;
				}
			});
			
			$(".change_in_image").html('<i class="fa fa-pencil"></i>'); 
			$(".change_in_image_finaltotal").text(0).css('color','green');
			$(".give_color").css('color','#797979');
			
			$("#btn-Convert-Html2Image-download-new").css("display","inline-block");	
			$("#btn-Convert-Html2Image-new").css("display","none");	
		});
		
		$("#btn-Convert-Html2Image-download-new").on('click', function () {
			var imgageData = getCanvas2.toDataURL("image/png");
			var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
			
			$("#btn-Convert-Html2Image-download-new").attr("download", "profit_report.png").attr("href", newData);
			
			$("#btn-Convert-Html2Image-download-new").css("display","none");	
			$("#btn-Convert-Html2Image-new").css("display","inline-block");	
		});
		
	</script>
	<script>
		$('#import_lot').validate({
			rules: {
				profit_month:{
					required: true
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
					url: WEBSITE_URL+'profit_report/ajaxAddCSV', 
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
							
							$("#submit").attr("disabled",false);	
							return false;
						}	
					}
				});
				return false;
			}
		});
		
		$('#edit_lot').validate({
			rules: {
				company_name:{
					required: true
				},
				lot_size:{
					required: true,
					number: true
				}
			},	
			submitHandler: function(form) {
				
				$("#submit2").attr("disabled",true);
				$(".overlay-loader").show();
				var data = new FormData(form);
				
				$.ajax({
					url: WEBSITE_URL+'profit_report/ajaxEditCompanyLot', 
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
							
							$("#submit").attr("disabled",false);	
							return false;
						}	
					}
				});
				return false;
			}
		});
		
		$('#calculate_profit_rules').validate({
			rules: {
				report_date:{
					required: true
				},
				report_segment:{
					required: true
				}
			},	
			submitHandler: function(form) {
				
				$("#submit3").attr("disabled",true);
				
				$(".overlay-loader").show();
				
				var check = checkEmptyReportData();
				if(check == '0'){
					$("#submit3").attr("disabled",false);
					$(".overlay-loader").hide();
					return false;
				}
				
				var data = new FormData(form);
				
				$.ajax({
					url: '<?php echo base_url();?>profit_report/ajaxCalculateProfit', 
					type: "POST",             
					data: data,
					processData:false,
					contentType:false,
					cache:false,
					async:false,
					success: function(response) 
					{
						$("#submit3").css("display","none");
						$("#submit4").css("display","inline-block");
				
						if(response.status == true) {
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
							
							/*setTimeout(function(){
							   window.location = response.redirect;
							},2500);*/
							
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
							
							$("#submit").attr("disabled",false);	
							return false;
						}	
					}
				});
				return false;
			}
		});
		
		$('#exit_price_form').validate({
			rules: {
				exit_price_edit:{
					required: true
				},
				exit_end_date_edit:{
					required: true
				}
			},	
			submitHandler: function(form) {
				$("#edit_price_save").attr("disabled",true);
				$(".overlay-loader").show();
				var data = new FormData(form);
				$.ajax({
					url: '<?php echo base_url();?>profit_report/ajaxEditExitPrice', 
					type: "POST",             
					data: data,
					processData:false,
					contentType:false,
					cache:false,
					async:false,
					success: function(response) 
					{
						$("#edit_exit_price_modal").modal('toggle');
						if(response.status == true) {
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
								$("#edit_price_save").attr("disabled",false);	
								var stat = $("#slot_status").val();
								after_edit_exit_price(stat);
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
							$("#edit_price_save").attr("disabled",false);	
							return false;
						}	
					}
				});
				return false;
			}
		});
	</script>
	<script>
		$(document).ready(function() {
			$('#company_name').on('select2:select', function (e) {
				var lotsize = $(this).find(":selected").data("lotsize");
				$("#lot_size").val(lotsize);
			});
			
			$("#report_segment_filter").on('change',function(){
				
				$(".report_header").html($(this).val()+' Report From <span class="report_date_se"></span>');
				
				if($(this).val() == 'Equity'){
					$(".report_header").css('color','#ffffff');
					
					if($("#generated_report").hasClass('t-h-warning t-b-warning')){
						$("#generated_report").removeClass('t-b-warning t-h-warning');
					} else if($("#generated_report").hasClass('t-h-danger t-b-danger')){
						$("#generated_report").removeClass('t-b-danger t-h-danger');
					} else if($("#generated_report").hasClass('t-h-blue t-b-blue')){
						$("#generated_report").removeClass('t-b-blue t-h-blue');
					}
					
					$("#generated_report").addClass('t-h-success t-b-success');
					
				} else if($(this).val() == 'Future'){
					$(".report_header").css('color','#ffffff');
					
					if($("#generated_report").hasClass('t-h-success t-b-success')){
						$("#generated_report").removeClass('t-b-success t-h-success');
					} else if($("#generated_report").hasClass('t-h-danger t-b-danger')){
						$("#generated_report").removeClass('t-b-danger t-h-danger');
					} else if($("#generated_report").hasClass('t-h-warning t-b-warning')){
						$("#generated_report").removeClass('t-b-warning t-h-warning');
					}
					
					$("#generated_report").addClass('t-h-blue t-b-blue');
					
				} else if($(this).val() == 'Option'){
					$(".report_header").css('color','#ffffff');
					
					if($("#generated_report").hasClass('t-h-success t-b-success')){
						$("#generated_report").removeClass('t-b-success t-h-success');
					} else if($("#generated_report").hasClass('t-h-danger t-b-danger')){
						$("#generated_report").removeClass('t-b-danger t-h-danger');
					} else if($("#generated_report").hasClass('t-h-blue t-b-blue')){
						$("#generated_report").removeClass('t-b-blue t-h-blue');
					}
					
					$("#generated_report").addClass('t-h-warning t-b-warning');
					
				} else if($(this).val() == 'Positional'){
					$(".report_header").css('color','#ffffff');
					
					if($("#generated_report").hasClass('t-h-success t-b-success')){
						$("#generated_report").removeClass('t-b-success t-h-success');
					} else if($("#generated_report").hasClass('t-h-warning t-b-warning')){
						$("#generated_report").removeClass('t-b-warning t-h-warning');
					} else if($("#generated_report").hasClass('t-h-blue t-b-blue')){
						$("#generated_report").removeClass('t-b-blue t-h-blue');
					}
					
					$("#generated_report").addClass('t-h-danger t-b-danger');
					
				} else {
					$(".report_header").css('color','#ffffff');
					
					if($("#generated_report").hasClass('t-h-warning t-b-warning')){
						$("#generated_report").removeClass('t-b-warning t-h-warning');
					} else if($("#generated_report").hasClass('t-h-danger t-b-danger')){
						$("#generated_report").removeClass('t-b-danger t-h-danger');
					} else if($("#generated_report").hasClass('t-h-blue t-b-blue')){
						$("#generated_report").removeClass('t-b-blue t-h-blue');
					}
					
					$("#generated_report").addClass('t-h-success t-b-success');
				}
				
				if($(this).val() == 'Positional'){
					$("#date_range").css('display','inline');
					$("#slot_status").css('display','inline');
					$("#generateWeeklyReport").css('display','none');
					$("#generateMonthlyReport").css('display','none');
				} else {
					$("#date_range").css('display','none');
					$("#slot_status").css('display','none');
					$("#generateWeeklyReport").css('display','inline');
					$("#generateMonthlyReport").css('display','inline');
				}
			});
			
			$("#slot_status").on('change',function(){
				if($("#date_range").val() != "") {
					var stat = $(this).val();
					if(stat == 'Open'){
						after_edit_exit_price(stat);
					} else {
						after_edit_exit_price(stat);
					}
				}
			});
			
			$("#date_range").flatpickr({
				mode: 'range',
				dateFormat: "d-m-Y",
				onChange: function(selectedDates, dateStr, instance) {
					$(".report_date_se").text(dateStr);
					$(".status_column").show();
					$(".report_header").attr('colspan','9');
					$(".gtotal_cols").attr('colspan','8');
					
					var report_segment_filter = $("#report_segment_filter").val();
					var status_val = $("#status_val").val();
					var report_se_date = dateStr;
					
					$(".overlay-loader").show();
					
					$.ajax({
						url: '<?php echo base_url();?>profit_report/ajaxGenerateReport', 
						method: 'POST',
						data: {report_segment_filter:report_segment_filter,report_se_date:report_se_date,status_val:status_val},
						success: function(response){
							var gtotal = 0;
							
							setTimeout(function(){
							   $(".report_data").html(response);
							   
							   var prevTDVal3 = "";
							   
							   $("#generated_report tbody tr td:nth-child(8)").each(function() { //for each first td in every tr
									var $this = $(this);
									prevTDVal3  = $this.text();
									if(prevTDVal3 >= 0){
										$(this).css('color','green');
										 $(this).css('font-weight','bold');
									 } else {
										 $(this).css('color','red');
										 $(this).css('font-weight','bold');
									 }
							   });
							   
							   var span = 1;
							   var prevTD = "";
							   var prevTDVal = "";
							   $("#generated_report tbody tr td:first-child").each(function() { //for each first td in every tr
								  var $this = $(this);
								  if ($this.text() == prevTDVal) { // check value of previous td text
									 span++;
									 if (prevTD != "") {
										prevTD.attr("rowspan", span); // add attribute to previous td
										$this.remove(); // remove current td
									 }
								  } else {
									 prevTD     = $this; // store current td 
									 prevTDVal  = $this.text();
									 span       = 1;
								  }
							   });
							   
							   var span2 = 1;
							   var prevTD2 = "";
							   var prevTDVal2 = "";
							   
								$("#generated_report tbody tr td:last-child").each(function() { //for each first td in every tr
								  var $this = $(this);
								  if ($this.text() == prevTDVal2) { // check value of previous td text
									 span2++;
									 if (prevTD2 != "") {
										prevTD2.attr("rowspan", span2); // add attribute to previous td
										$this.remove(); // remove current td
									 }
								  } else {
									 prevTD2     = $this; // store current td 
									 prevTDVal2  = $this.text();
									 span2       = 1;
									 
									 if(prevTDVal2 >= 0){
										 $(this).css('color','green');
										 $(this).css('font-weight','bold');
									 } else {
										 $(this).css('color','red');
										 $(this).css('font-weight','bold');
									 }
									 
									 if(prevTDVal2 != ""){
										gtotal = gtotal + parseFloat(prevTDVal2);	
									 }
								  }
								});
							   
								$("#gtotal").text(gtotal);
							   
								if(gtotal >= 0){
									$("#gtotal").css('color','green');
									 $("#gtotal").css('font-weight','bold');
								 } else {
									 $("#gtotal").css('color','red');
									 $("#gtotal").css('font-weight','bold');
								 }
							   
							   $(".overlay-loader").hide();
							},1500);											
						}
					});
					
				},
			});
		});
		
		function after_edit_exit_price(status_val){
			$(".overlay-loader").show();
			
			var report_segment_filter = $("#report_segment_filter").val();
			var report_se_date = $("#date_range").val();
			
			$.ajax({
				url: '<?php echo base_url();?>profit_report/ajaxGenerateReport', 
				method: 'POST',
				data: {report_segment_filter:report_segment_filter,report_se_date:report_se_date,status_val : status_val},
				success: function(response){
					var gtotal = 0;
					
					setTimeout(function(){
					   $(".report_data").html(response);
					   
					   var prevTDVal3 = "";
					   
					   $("#generated_report tbody tr td:nth-child(8)").each(function() { //for each first td in every tr
							var $this = $(this);
							prevTDVal3  = $this.text();
							if(prevTDVal3 >= 0){
								$(this).css('color','green');
								 $(this).css('font-weight','bold');
							 } else {
								 $(this).css('color','red');
								 $(this).css('font-weight','bold');
							 }
					   });
					   
					   var span = 1;
					   var prevTD = "";
					   var prevTDVal = "";
					   $("#generated_report tbody tr td:first-child").each(function() { //for each first td in every tr
						  var $this = $(this);
						  if ($this.text() == prevTDVal) { // check value of previous td text
							 span++;
							 if (prevTD != "") {
								prevTD.attr("rowspan", span); // add attribute to previous td
								$this.remove(); // remove current td
							 }
						  } else {
							 prevTD     = $this; // store current td 
							 prevTDVal  = $this.text();
							 span       = 1;
						  }
					   });
					   
					   var span2 = 1;
					   var prevTD2 = "";
					   var prevTDVal2 = "";
					   
					   $("#generated_report tbody tr td:last-child").each(function() { //for each first td in every tr
						  var $this = $(this);
						  if ($this.text() == prevTDVal2) { // check value of previous td text
							 span2++;
							 if (prevTD2 != "") {
								prevTD2.attr("rowspan", span2); // add attribute to previous td
								$this.remove(); // remove current td
							 }
						  } else {
							 prevTD2     = $this; // store current td 
							 prevTDVal2  = $this.text();
							 span2       = 1;
							 
							 if(prevTDVal2 >= 0){
								 $(this).css('color','green');
								 $(this).css('font-weight','bold');
							 } else {
								 $(this).css('color','red');
								 $(this).css('font-weight','bold');
							 }
							 
							 if(prevTDVal2 != ""){
								gtotal = gtotal + parseFloat(prevTDVal2);	
							 }
						  }
					   });
					   
					   $("#gtotal").text(gtotal);
					   $(".overlay-loader").hide();
					},1500);											
				}
			});
		}
		
		function edit_exit_price(primary_id,sl_price,target_price){
			$("#edit_exit_price_modal").modal();	
			$("#exit_price_edit").val(0);
			$("#exit_end_date_edit").val("");
			$("#record_id").val(primary_id);
			$("#sl_price_edit").val(sl_price);
			$("#target_price_edit").val(target_price);
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
		
		function checkEmptyReportData()
		{
			var total_items = $("#total_row_added").val();
			var status = 1;
			
			var report_segment = $("#report_segment").val();
			var is_positional = 0;
			
			for(var i = 1; i <= total_items ; i++)
			{
				var company_name = $("#company_name"+i).val();
				var buy_sell = $("#buy_sell"+i).val();
				var entry_price = $("#entry_price"+i).val();
				var exit_price = $("#exit_price"+i).val();
				var lot_size = $("#lot_size"+i).val();
				var final_amount = $("#final_amount"+i).val();
					
				if(report_segment == 'Positional'){
					var is_positional = 1;
					var esdate = $("#entry_start_date"+i).val();
					var sl_price = $("#sl_price"+i).val();
					var target_price = $("#target_price"+i).val();
				} 			
				
				if(company_name == ''){
					//$("#company_name"+i).parent(this).parent(this).addClass("has-error");	
					$("#company_name"+i).focus();
					status = 0; break;
				} else if(is_positional == 1 && sl_price == ''){
					$("#sl_price"+i).focus();
					status = 0; break;
				} else if(buy_sell == '') {
					//$("#buy_sell"+i).parent(this).parent(this).addClass("has-error");	
					$("#buy_sell"+i).focus();
					status = 0; break;
				} else if(is_positional == 1 && target_price == ''){
					$("#target_price"+i).focus();
					status = 0; break;
				} else if(entry_price == ''){
					//$("#entry_price"+i).parent(this).parent(this).addClass("has-error");	
					$("#entry_price"+i).focus();
					status = 0; break;
				} else if(exit_price == '' && is_positional != 1){
					//$("#exit_price"+i).parent(this).parent(this).addClass("has-error");	
					$("#exit_price"+i).focus();
					status = 0; break;
				} else if(lot_size == ''){
					//$("#lot_size"+i).parent(this).parent(this).addClass("has-error");	
					$("#lot_size"+i).focus();
					status = 0; break;
				} else if(final_amount == ''){
					//$("#final_amount"+i).parent(this).parent(this).addClass("has-error");	
					$("#final_amount"+i).focus();
					status = 0; break;
				} else if(is_positional == 1 && esdate == ''){
					$("#entry_start_date"+i).focus();
					status = 0; break;
				} 
			}	
			return status;
		}
		
		function add_more_report(){
			var cnt = $("#total_row_added").val();
			cnt++;
			
			var add = '';
			var check = checkEmptyReportData();
			
			if(check == '1'){
				add += '<div class="row" id="profit_report_'+cnt+'">';
					add += '<div class="col-md-2">';
						add += '<div class="form-group">';
							add += '<label class="control-label hide_this_unused">&nbsp;</label>';
							add += '<input type="text" class="form-control typeahead tt-query searchtype change_color_it" name="company_name_report[]" id="company_name'+cnt+'" autocomplete="off" spellcheck="false" placeholder="Company Name">';
						add += '</div>';
						add += '<div class="form-group hide_if_not_positional">';
							add += '<input type="text" class="form-control" name="sl_price[]" id="sl_price'+cnt+'" autocomplete="off" placeholder="SL" onKeyPress="return validQty(event,this.value);">';
						add += '</div>';		
					add += '</div>';
					add += '<div class="col-md-2">';
						add += '<div class="form-group">';
							add += '<label class="control-label hide_this_unused">&nbsp;</label>';
							add += '<select class="form-control change_color_it" name="buy_sell[]" id="buy_sell'+cnt+'" autocomplete="off" onchange="calculate_profit(\''+cnt+'\');">';
								add += '<option value="Buy">Buy</option>';
								add += '<option value="Sell">Sell</option>';
							add += '</select>';
						add += '</div>';
						add += '<div class="form-group hide_if_not_positional">';
							add += '<input type="text" class="form-control" name="target_price[]" id="target_price'+cnt+'" autocomplete="off" placeholder="Target" onKeyPress="return validQty(event,this.value);">';
						add += '</div>';								
					add += '</div>';
					add += '<div class="col-md-2">';
						add += '<div class="form-group">';
							add += '<label class="control-label hide_this_unused">&nbsp;</label>';
							add += '<input type="text" class="form-control change_color_it" name="entry_price[]" id="entry_price'+cnt+'" autocomplete="off" onKeyPress="return validQty(event,this.value);" onkeyup="calculate_profit(\''+cnt+'\');">';
						add += '</div>';	
						add += '<div class="form-group hide_if_not_positional">';
							add += '<input type="text" class="form-control" name="entry_start_date[]" id="entry_start_date'+cnt+'" autocomplete="off" placeholder="Start Date">';
						add += '</div>';	
					add += '</div>';
					add += '<div class="col-md-2">';
						add += '<div class="form-group">';
							add += '<label class="control-label hide_this_unused">&nbsp;</label>';
							add += '<input type="text" class="form-control change_color_it" name="exit_price[]" id="exit_price'+cnt+'" autocomplete="off" onKeyPress="return validQty(event,this.value);" onkeyup="calculate_profit(\''+cnt+'\');">';
						add += '</div>';	
						add += '<div class="form-group hide_if_not_positional">';
							add += '<input type="text" class="form-control" name="exit_end_date[]" id="exit_end_date'+cnt+'" autocomplete="off" placeholder="End Date">';
						add += '</div>';	
					add += '</div>';
					add += '<div class="col-md-2">';
						add += '<div class="form-group">';
							add += '<label class="control-label hide_this_unused">&nbsp;</label>';
							add += '<input type="text" class="form-control change_color_it" name="lot_size[]" id="lot_size'+cnt+'" autocomplete="off" onKeyPress="return validQty2(event,this.value);" onkeyup="calculate_profit(\''+cnt+'\');">';
						add += '</div>';
					add += '</div>';
					add += '<div class="col-md-2">';
						add += '<a class="hide_this_unused" href="javascript:;" onclick="removeProfitLine(\''+cnt+'\');" style="float: right;color: red;font-size: 20px;"><i class="fa fa-times"></i></a>';
						add += '<div class="form-group">';
							add += '<label class="control-label hide_this_unused">&nbsp;</label>';
							add += '<input type="text" class="form-control final_total_text" name="final_amount[]" id="final_amount'+cnt+'" autocomplete="off" readonly value="0" style="background-color: white;">';
						add += '</div>';	
					add += '</div>';
				add += '</div>';
				
				$("#profit_report_data").append(add);
				$("#total_row_added").val(cnt);
				
				var report_segment = $("#report_segment").val();
				if(report_segment == 'Positional'){
					$(".hide_if_not_positional").css('display','inline-block');
				} else {
					$(".hide_if_not_positional").css('display','none');
				}
				
				$('#company_name'+cnt).typeahead({
					name: 'typeahead',
					valueKey: 'company_name',
					remote: {
						url: WEBSITE_URL+'profit_report/ajaxSearchCompany/%QUERY',
						filter: function (parsedResponse) {
							return parsedResponse;
						}
					},
					limit : 50
				}).bind("typeahead:selected", function(obj, datum, name) {
					$(this).typeahead('setQuery',datum.company_name_text);
					var lotsize = datum.lot_size;
					var current_id = $(this).attr("id");
					if(lotsize == "") {
						$('#lot_size'+cnt).val(0);
					}else{
						$('#lot_size'+cnt).val(lotsize);
					}
				});
				
				$("#entry_start_date"+cnt).flatpickr({
					maxDate: new Date(),
					dateFormat: "d-m-Y"
				});
				$("#exit_end_date"+cnt).flatpickr({
					minDate: new Date(),
					dateFormat: "d-m-Y"
				});
			}
		}
		
		function calculate_profit(divid){
			var buy_sell = $("#buy_sell"+divid).val();
			var entry_price = ($("#entry_price"+divid).val() != "") ? $("#entry_price"+divid).val() : 0;
			var exit_price = ($("#exit_price"+divid).val() != "") ? $("#exit_price"+divid).val() : 0;
			var lot_size = ($("#lot_size"+divid).val() != "") ? $("#lot_size"+divid).val() : 0;
			
			if(buy_sell == 'Buy') {
				var final_amount1 = exit_price - entry_price;
				
				if(exit_price == 0 || entry_price == 0){
					var final_amount = 0;	
				} else {
					var final_amount = final_amount1 * lot_size;
				}
				
				if(final_amount >= 0){
					$("#final_amount"+divid).val(final_amount.toFixed(2)).css({'background-color':'#34A853','color':'white'});
				}
				else{
					$("#final_amount"+divid).val(final_amount.toFixed(2)).css({'background-color':'#EA4335','color':'white'});
				}

			}else if(buy_sell == 'Sell') {
				var final_amount1 = entry_price - exit_price;
				
				if(exit_price == 0 || entry_price == 0){
					var final_amount = 0;	
				} else {
					var final_amount = final_amount1 * lot_size;
				}
				
				if(final_amount >= 0){
					$("#final_amount"+divid).val(final_amount.toFixed(2)).css({'background-color':'#34A853', 'color':'white'});
				}
				else{
					$("#final_amount"+divid).val(final_amount.toFixed(2)).css({'background-color':'#EA4335','color':'white'});
				}
			}
			
			var sum = 0;
			$('.final_total_text').each(function(){
				sum += parseFloat($(this).val());  // Or this.innerHTML, this.innerText
			});
			if(sum >= 0){
				$("#overall_total").val(sum).css({'background-color':'#34A853', 'color':'white'});
			}
			else{
				$("#overall_total").val(sum).css({'background-color':'#EA4335','color':'white'});
			}
		}
		
		function removeProfitLine(divid){
			$("#profit_report_"+divid).remove();
			calculate_profit(divid);
		}
		
		function getMonday(d) {
		  d = new Date(d);
		  var day = d.getDay(),
			  diff = d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
		  return new Date(d.setDate(diff));
		}
		
		function gfg_Run(monday) { 
            var date = monday.toJSON().slice(0, 10); 
            var nDate = date.slice(8, 10) + '/'  
                       + date.slice(5, 7) + '/'  
                       + date.slice(0, 4); 
            return nDate; 
        } 
		
		function generateWeeklyReport(){
			
			$(".status_column").hide();
			$(".report_header").attr('colspan','8');
			$(".gtotal_cols").attr('colspan','7');
			
			var monday = getMonday(new Date());	
			var current_date = gfg_Run(new Date());
			var monday_formated = gfg_Run(monday);
			
			$(".report_date_se").text(monday_formated+' to '+current_date);
			
			var report_segment_filter = $("#report_segment_filter").val();
			$("#generateWeeklyReport").attr("disabled",true);
			$(".overlay-loader").show();
			
			$.ajax({
				url: '<?php echo base_url();?>profit_report/ajaxGenerateReport', 
				type: "POST",             
				data: {type : 'Weekly',report_segment_filter:report_segment_filter},
				success: function(response) 
				{
					var gtotal = 0;
					
					setTimeout(function(){
					   $(".report_data").html(response);
					   $("#generateWeeklyReport").attr("disabled",false);
					   
					   var prevTDVal3 = "";
					   
					   $("#generated_report tbody tr td:nth-child(7)").each(function() { //for each first td in every tr
							var $this = $(this);
							prevTDVal3  = $this.text();
							if(prevTDVal3 >= 0){
								$(this).css('color','green');
								 $(this).css('font-weight','bold');
							 } else {
								 $(this).css('color','red');
								 $(this).css('font-weight','bold');
							 }
					   });
					   
					   var span = 1;
					   var prevTD = "";
					   var prevTDVal = "";
					   $("#generated_report tbody tr td:first-child").each(function() { //for each first td in every tr
						  var $this = $(this);
						  if ($this.text() == prevTDVal) { // check value of previous td text
							 span++;
							 if (prevTD != "") {
								prevTD.attr("rowspan", span); // add attribute to previous td
								$this.remove(); // remove current td
							 }
						  } else {
							 prevTD     = $this; // store current td 
							 prevTDVal  = $this.text();
							 span       = 1;
						  }
					   });
					   
					   var span2 = 1;
					   var prevTD2 = "";
					   var prevTDVal2 = "";
					   
					   $("#generated_report tbody tr td:last-child").each(function() { //for each first td in every tr
						  var $this = $(this);
						  if ($this.text() == prevTDVal2) { // check value of previous td text
							 span2++;
							 if (prevTD2 != "") {
								prevTD2.attr("rowspan", span2); // add attribute to previous td
								$this.remove(); // remove current td
							 }
						  } else {
							 prevTD2     = $this; // store current td 
							 prevTDVal2  = $this.text();
							 span2       = 1;
							 
							 if(prevTDVal2 >= 0){
								 $(this).css('color','green');
								 $(this).css('font-weight','bold');
							 } else {
								 $(this).css('color','red');
								 $(this).css('font-weight','bold');
							 }
							 
							 if(prevTDVal2 != ""){
								gtotal = gtotal + parseFloat(prevTDVal2);	
							 }
						  }
					   });
					   
					   if(gtotal >= 0){
							$("#gtotal").css('color','green');
							$("#gtotal").css('font-weight','bold');
					   } else {
							$("#gtotal").css('color','red');
							$("#gtotal").css('font-weight','bold');
					   }
					   
					   $("#gtotal").text(gtotal);
					   $(".overlay-loader").hide();
					},1500);
				}
			});
			return false;
		}
		
		function generateMonthlyReport(){
			
			$(".status_column").hide();
			$(".report_header").attr('colspan','8');
			$(".gtotal_cols").attr('colspan','7');
			
			var current_date = gfg_Run(new Date());
			
			/*var today = new Date();
			var dd = today.getDate();

			var mm = today.getMonth()+1; 
			var yyyy = today.getFullYear();
			if(dd<10) {
				dd='0'+dd;
			} 
			if(mm<10) 
			{
				mm='0'+mm;
			} 
			firstDay_date = dd+'/'+mm+'/'+yyyy;*/
			
			var date = new Date();
			var firstDay = new Date(date.getFullYear(), date.getMonth(), 2);
			var firstDay_date = gfg_Run(firstDay);
			
			$(".report_date_se").text(firstDay_date+' to '+current_date);
			
			var report_segment_filter = $("#report_segment_filter").val();
			$("#generateMonthlyReport").attr("disabled",true);
			$(".overlay-loader").show();
			
			$.ajax({
				url: '<?php echo base_url();?>profit_report/ajaxGenerateReport', 
				type: "POST",             
				data: {type : 'Monthly',report_segment_filter:report_segment_filter},
				success: function(response) 
				{
					var gtotal = 0;
					setTimeout(function(){
					   $(".report_data").html(response);
					   $("#generateMonthlyReport").attr("disabled",false);
					   
					   $("#generated_report tbody tr td:nth-child(7)").each(function() { //for each first td in every tr
							var $this = $(this);
							prevTDVal3  = $this.text();
							if(prevTDVal3 >= 0){
								$(this).css('color','green');
								 $(this).css('font-weight','bold');
							 } else {
								 $(this).css('color','red');
								 $(this).css('font-weight','bold');
							 }
					   });
					   
					   var span = 1;
					   var prevTD = "";
					   var prevTDVal = "";
					   $("#generated_report tbody tr td:first-child").each(function() { //for each first td in every tr
						  var $this = $(this);
						  if ($this.text() == prevTDVal) { // check value of previous td text
							 span++;
							 if (prevTD != "") {
								prevTD.attr("rowspan", span); // add attribute to previous td
								$this.remove(); // remove current td
							 }
						  } else {
							 prevTD     = $this; // store current td 
							 prevTDVal  = $this.text();
							 span       = 1;
						  }
					   });
					   
					   var span2 = 1;
					   var prevTD2 = "";
					   var prevTDVal2 = "";
					   
					   $("#generated_report tbody tr td:last-child").each(function() { //for each first td in every tr
						  var $this = $(this);
						  if ($this.text() == prevTDVal2) { // check value of previous td text
							 span2++;
							 if (prevTD2 != "") {
								prevTD2.attr("rowspan", span2); // add attribute to previous td
								$this.remove(); // remove current td
							 }
						  } else {
							 prevTD2     = $this; // store current td 
							 prevTDVal2  = $this.text();
							 span2       = 1;
							 
							 if(prevTDVal2 >= 0){
								 $(this).css('color','green');
								 $(this).css('font-weight','bold');
							 } else {
								 $(this).css('color','red');
								 $(this).css('font-weight','bold');
							 }
							 
							 if(prevTDVal2 != ""){
								gtotal += parseFloat(prevTDVal2);	
							 }
						  }
					   });
					   
					   if(gtotal >= 0){
							$("#gtotal").css('color','green');
							$("#gtotal").css('font-weight','bold');
					   } else {
							$("#gtotal").css('color','red');
							$("#gtotal").css('font-weight','bold');
					   }
					   
					   $("#gtotal").text(gtotal);
					   
					   $(".overlay-loader").hide();
					   
					},1500);
				}
			});
			return false;
		}
		
		function set_report_from(segment_val) {
			
			if(segment_val == 'Positional'){
				$(".hide_if_positional").css('display','none');
				$(".hide_if_not_positional").css('display','inline-block');
			} else {
				$(".hide_if_positional").css('display','inline-block');
				$(".hide_if_not_positional").css('display','none');
			}
			
			if(segment_val == 'Equity'){
				$("#report_from").css('background-color','#4caf50');
			} else if(segment_val == 'Future'){
				$("#report_from").css('background-color','#4285F4');
			} else if(segment_val == 'Option'){
				$("#report_from").css('background-color','#ffa200');
			} else if(segment_val == 'Positional'){
				$("#report_from").css('background-color','#ff3e43');
			} else{
				$("#report_from").css('background-color','#4caf50');
			}
			
			var report_date = $("#report_date").val();
			if(report_date != "") {
				$("#report_from").val(segment_val+" Report of "+report_date);
			} else {
				$("#report_date").focus();
				var options = 
				{
					status:"danger",
					sound:true,
					duration:3500
				};

				mkNoti(
					"Fail",
					'Please select report date first.',
					options
				);
			} 
		}
	</script>
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/js/typeahead.min.js"></script>
	<!--script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"-->

	<script>
    $(document).ready(function(){
		$('#company_name1').typeahead({
			name: 'typeahead',
			valueKey: 'company_name',
			remote: {
				url: WEBSITE_URL+'profit_report/ajaxSearchCompany/%QUERY',
				filter: function (parsedResponse) {
					return parsedResponse;
				},
			},
			limit : 50
		}).bind("typeahead:selected", function(obj, datum, name) {
			$(this).typeahead('setQuery',datum.company_name_text);
			var lotsize = datum.lot_size;
			if(lotsize == "") {
				$('#lot_size1').val(0);
			}else{
				$('#lot_size1').val(lotsize);
			}
        });
	});
	</script> 

	 
</body>

</html>