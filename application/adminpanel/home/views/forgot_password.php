<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8" />
        <title><?php echo SITE_NAME; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="shortcut icon" href="<?php echo base_url().ADMIN_THEME; ?>assets/images/favicon.png">
        <!--Bootstrap Css-->
        <link href="<?php echo base_url().ADMIN_THEME; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <!--Main Style Css-->
        <link href="<?php echo base_url().ADMIN_THEME; ?>assets/css/style.css" rel="stylesheet" type="text/css"/>
        <!--Responsive Css-->
        <link href="<?php echo base_url().ADMIN_THEME; ?>assets/css/responsive.css" rel="stylesheet" type="text/css"/>
        <!--Icons Css--> 
        <link href="<?php echo base_url().ADMIN_THEME; ?>assets/css/icons.css" rel="stylesheet" type="text/css"/>
        <!--Animate Css--> 
        <link href="<?php echo base_url().ADMIN_THEME; ?>assets/css/animate.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url().ADMIN_THEME; ?>assets/toast/css/mk-notifications.css" rel="stylesheet" type="text/css"/>
        <script src="<?php echo base_url().ADMIN_THEME; ?>assets/js/modernizr.min.js"></script>        
    </head>
    <body>
		<div class="overlay-loader no-display" style="display: none; background: none repeat scroll 0 0 rgba(238,238,238,0.75);left: 0;position: fixed;right: 0;top: 0;bottom: 0;z-index:99999999;">
			<img src="<?php echo base_url().ADMIN_THEME; ?>/assets/images/loading.gif" style="position: fixed;top: 46%;left: 46%;width: 8%;"/>
		</div>
		<div class="wrapper-page">
			<div class="panel">
				<div class="panel-body p-30">
					<h3 class="text-left mb-20 text-uppercase">Forget your Password</h3>
					<form name="forgot_pass" id="forgot_pass" method="post" action="<?php echo base_url().'home/ajaxForogotPassword'; ?>">
						<p class="text-muted text-left">Please enter your email address to retrieve your password. We will email you a link to follow the instruction</p>
						<hr>
						<div class="form-group mt-20">
							<div class="position-relative has-icon-left">
								<input type="text" name="forgot_email" id="forgot_email" required class="form-control" placeholder="Enter Email Address">
								<div class="form-control-position">
									<i class="icon-envelope"></i>
								</div>
							</div>
						</div>
						<div class="form-group mt-20 sr-only">
							<button class="btn btn-info btn-border btn-block  waves-effect waves-light" type="submit"><i class="ti-lock"></i> Recover Password</button>
						</div>
						<hr>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<input type="submit" id="submit" name="submit" class="pull-left text-info btn btn-rose" value="SEND">
									<a href="<?php echo base_url();?>" class="pull-left text-info btn btn-black ml-10">BACK TO LOGIN</a>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

    	<script>
            var resizefunc = [];
        </script>
        <!-- Main  Scripts-->
        <script src="<?php echo base_url().ADMIN_THEME; ?>assets/js/jquery.min.js"></script> 
        <script src="<?php echo base_url().ADMIN_THEME; ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url().ADMIN_THEME; ?>assets/js/waves.js"></script>
        <script src="<?php echo base_url().ADMIN_THEME; ?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?php echo base_url().ADMIN_THEME; ?>assets/js/app-script.js"></script>
        <script>
			$('.sidebar-menu').SidebarNav();
			$(".do-nicescrol").niceScroll({
				cursorcolor:"rgba(137, 150, 162, 0.3)",
				cursorwidth:"6px",
				cursorborder:"0px solid rgba(45, 53, 60, 0.3)",
			});
        </script>
        <!-- placeholdem -->
        <script src="<?php echo base_url().ADMIN_THEME; ?>assets/js/placeholdem.min.js"></script>
		<script type="text/javascript">
			Placeholdem( document.querySelectorAll( '[placeholder]' ) );
		</script>
		
		<script src="<?php echo base_url().ADMIN_THEME; ?>assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>
		<script src="<?php echo base_url().ADMIN_THEME;?>assets/toast/js/mk-notifications.js" type="text/javascript"></script>
		<script src="<?php echo base_url().ADMIN_THEME;?>assets/form/function.js" type="text/javascript"></script>
		<script src="<?php echo base_url().ADMIN_THEME;?>assets/form/forgot_password.js" type="text/javascript"></script>
		<script>
		jQuery(document).ready(function() {     
			FormValidation.init();
		});
		</script>
	</body>
</html>