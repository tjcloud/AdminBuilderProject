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
					<div class="rocks-logo">
						<img src="<?php echo base_url().ADMIN_THEME; ?>assets/images/rocks-logo.png">
					</div>
					<h3 class="text-center"><?php echo SITE_NAME; ?></h3>
					<form id="loginform" action="" method="POST">
						<div class="form-group">
							<div class="position-relative has-icon-left">
								<input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
								<div class="form-control-position">
									<i class="icon-user"></i>
								</div>
							</div>
						</div>

						<div class="form-group mt-20">
							<div class="position-relative has-icon-left">
								<input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
								<div class="form-control-position">
									<i class="icon-lock"></i>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-7">
									&nbsp;
								</div>
								<div class="col-sm-5">
									<a href="<?php echo base_url();?>home/forgot_password" class="pull-right text-info mt-10">Forgot password?</a>
								</div>
							</div>
						</div>
							
						<div class="form-group mb-30">
							<button class="btn btn-info btn-border btn-block w-lg waves-effect waves-light" type="submit" id="submit"><i class="icon-lock"></i> Login</button>
							<p class="user_result" style="margin-top: 10px;"></p>
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
        <script src="<?php echo base_url().ADMIN_THEME; ?>assets/js/placeholdem.min.js"></script>
		<script type="text/javascript">
			Placeholdem( document.querySelectorAll( '[placeholder]' ) );
		</script>
		
		<script src="<?php echo base_url().ADMIN_THEME; ?>assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>
		<script src="<?php echo base_url().ADMIN_THEME;?>assets/toast/js/mk-notifications.js" type="text/javascript"></script>
		<script src="<?php echo base_url().ADMIN_THEME;?>assets/form/function.js" type="text/javascript"></script>
		<script src="<?php echo base_url().ADMIN_THEME;?>assets/form/login.js" type="text/javascript"></script>
		<script>
		jQuery(document).ready(function() {     
			FormValidation.init();
		});
		</script>
	</body>
</html>