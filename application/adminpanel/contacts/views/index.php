<?php 
$this->load->view('../../header');
?>
<style>
	.table > tbody > tr > td {
		 vertical-align: middle;
		 text-align:center;
	}
	a i.fa.fa-whatsapp{
	  color: #4caf50;
	  font-size: 18px;
	}
	a i.fa.fa-pencil{
	  color: #0acae2;
	  font-size: 18px;
	} 
	a i.fa.fa-trash{
	  color: #ff3e43;
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

<link href="<?php echo base_url().ADMIN_THEME;?>assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" />
<link href="<?php echo base_url().ADMIN_THEME;?>assets/plugins/bootstrap-switch/bootstrap-switch.min.css" rel="stylesheet">

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
							<h4 class="pull-left text-uppercase">Contact Listing</h4>
							<ol class="breadcrumb pull-right">
								<li><a href="<?php echo base_url();?>" class="text-info">Dashboard</a></li>
								<li><a href="javascript:void();">Contact</a></li>
							</ol>
						</div>
					</div>
					<!-- End Page Breadcrumb -->

					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<div class="btn-group pull-right">
										<button type="button" class="btn btn-block btn-grd-2 waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
										<ul class="dropdown-menu animated flipInY" role="menu">
											<li>
												<a href="<?php echo base_url();?>contacts/create" title="Add Contact"><i class="fa fa-plus-square-o"></i> Add Contact</a>
											</li>
										</ul>
									</div>
									<h3 class="panel-title">Listing</h3>
								</div>
								<div class="panel-body">
									<div class="portlet light">
										<div class="portlet-body form">
											<form method="post">
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
															<label class="logs-leb"> Select POA Status </label>
															<select class="form-control" name="poa_status" id="poa_status">
																<option value="">Please select</option>
																<option value="0" <?php if($poa_status == '0'){ echo 'selected'; }?>>Fresh</option>
																<option value="1" <?php if($poa_status == '1'){ echo 'selected'; }?>>Sent</option>
																<option value="2" <?php if($poa_status == '2'){ echo 'selected'; }?>>Recived</option>
																<option value="3" <?php if($poa_status == '3'){ echo 'selected'; }?>>Submitted</option>
															</select>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label class="logs-leb"> Select Paid Status </label>
															<select class="form-control" name="paid_status" id="paid_status">
																<option value="">Please select</option>
																<option value="0" <?php if($paid_status == '0'){ echo 'selected'; }?>>Free</option>
																<option value="1" <?php if($paid_status == '1'){ echo 'selected'; }?>>Paid</option>
															</select>
														</div>
													</div>
													
												</div>	
											</div>
											<div class="form-actions">
												<button class="btn btn-success">Search</button>
												&nbsp;<button type="button" class="btn btn-danger" onclick="window.location.href='<?php echo base_url().'contacts'; ?>'">Clear Filter</button>
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
													<th class="text-center">Contact No</th>
													<th class="text-center">Acc. Type</th>
													<th class="text-center">Exp. Remaining Days</th>
													<th class="text-center">Attandance</th>
													<th class="text-center act-sort">Client Status</th>
													<th class="text-center act-sort">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php 
												if(!empty($contacts))
												{
													$i = 0;
													foreach($contacts as $contactsData){
														
														$days = 0;
														if($contactsData['payment_type'] == 1){
															if(date("Y-m-d H:i:s",strtotime($contactsData['start_date'])) > date("Y-m-d H:i:s")){
																$days = "<span class='btn btn-warning btn-border waves-effect waves-light btn-sm'>Not Started Yet</span>";	
															}else if(date("Y-m-d H:i:s") > date("Y-m-d H:i:s",strtotime($contactsData['end_date']))){
																$days = "<span class='btn btn-danger btn-border waves-effect waves-light btn-sm'>Terminated</span>";	
															}else{
																$diff = strtotime(date("Y-m-d H:i:s")) - strtotime($contactsData['end_date']); 
																$days = "<span class='btn btn-success btn-border waves-effect waves-light btn-sm'>".abs(round($diff / 86400)).' Days</span>'; 	
															}
														}else{
															$days = "<span class='btn btn-info btn-border waves-effect waves-light btn-sm'>Infinite Days</span>";	
														}
														
														$i++;
												?>
														<tr>
															<td><?php echo $i; ?></td>
															<td><?php echo ($contactsData['client_id']) ? $contactsData['client_id'] : "-"; ?></td>
															<td><?php echo $contactsData['contact_name']; ?></td>
															<td><?php echo $contactsData['contact_no']; ?></td>
															<td><?php echo ($contactsData['payment_type'] == 1) ? 'Paid' : 'Free'; ?></td>
															<td><?php echo $days; ?></td>
															<td>
																<span style="color:#9100ff;">Overall : <?php echo $contactsData['Overall_Percentage'].'%'; ?></span><br>
																<?php 
																	if(isset($contactsData['PaidFree_Percentage'])){
																?>
																	<span style="color:green;"><?php echo $contactsData['PaidFree_Percentage']; ?></span><br>
																<?php
																	}
																?>
																<span style="color:red;">Not Active : <?php echo $contactsData['NotActive_Percentage'].'%'; ?></span><br>
																<span style="color:#229faf !important;">Avg : <?php echo $contactsData['Avg_Percentage'].'%'; ?></span>
															</td>
															<td>
																<input type="checkbox" name="contact_status" id="contact_status<?php echo $contactsData['id']; ?>" class="js-switch2" data-color="#51D43A" data-secondary-color="#FD0405" <?php if($contactsData['is_active'] == 1){ echo 'checked'; } ?> data-userid="<?php echo $contactsData['id']; ?>"/>
															</td>
															<td>
																<a href="https://api.whatsapp.com/send?phone=91<?php echo $contactsData['whatsapp_no']; ?>" target="_blank" class="waves-effect waves-light"> <i class="fa fa-whatsapp"></i> </a>
																&nbsp;
																<a href="<?php echo base_url().'contacts/edit/'.$contactsData['id']; ?>" class="waves-effect waves-light"> <i class="fa fa-pencil"></i> </a>
																&nbsp;
																<!-- <button type="button" class="waves-effect waves-light" onclick="delete_confirm('<?php echo $contactsData['id']; ?>');"> <i class="fa fa-trash"></i> </button> -->
																<a class="waves-effect waves-light" onclick="delete_confirm('<?php echo $contactsData['id']; ?>');"> <i class="fa fa-trash"></i> </a>
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
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/plugins/switchery/js/switchery.min.js"></script>
	<script type="text/javascript">
	  var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch2'));
	  $('.js-switch2').each(function() {
			new Switchery($(this)[0], $(this).data());
	   });
	</script>
	<script src="<?php echo base_url().ADMIN_THEME;?>assets/plugins/bootstrap-switch/bootstrap-switch.min.js"></script>
	<script type="text/javascript">
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
	
	var WEBSITE_URL = '<?php echo base_url(); ?>';
	
	$(document).ready(function() {
		// simple Ordering
		$('#example').DataTable();
		
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
	
	function delete_confirm(user_id)
	{
		$("#delete_confirmation").modal();	
		$("#delete_ok").unbind().click(function(){
			
			$(".overlay-loader").show();	
			$("#delete_ok").attr('disabled',true);
			
			var url = WEBSITE_URL+"contacts/ajaxDeleteContact";
			
			$.ajax({
				type: 'POST',		
				url: url,
				data: {user_id : user_id},
				success: function (response)
				{
					$(".overlay-loader").hide();	
					$("#delete_confirmation").modal('toggle');	
					
					if(response.status == true)
					{
						var options = 
						{
							status:"success",
							sound:true,
							duration:3500
						};

						mkNoti(
							"Success",
							response.message,
							options
						);

						setTimeout(function(){								
							window.location = response.redirect;
						},3500);
					}
					else
					{
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
						
						setTimeout(function(){								
							$("#delete_ok").attr('disabled',false);
						},3500);
					}
				}
			});
		});
	}
	
	$('#contact_status').change(function() {
		if(this.checked) {
			alert();
		}else{
			alert(2);
		}
	});
	</script>
	
</body>

</html>