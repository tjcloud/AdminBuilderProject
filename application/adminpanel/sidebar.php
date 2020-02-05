<?php
	$current_class = $this->router->fetch_class();
	$current_method = $this->router->fetch_method();
?>
<aside class="sidebar-left do-nicescrol" style="background: #095de8;background-image: linear-gradient(45deg, #095de8 0%, #03f3ff 100%);">
	<nav class="navbar navbar-inverse">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<div class="logo">
				<a href="<?php echo base_url();?>"><img src="<?php echo base_url().ADMIN_THEME;?>assets/images/logo-icon-white.png">
				<span class="text-white"><?php echo SITE_NAME; ?></span></a>
			</div>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<div class="user-details">
				<div class="pull-left">
					<img src="<?php echo base_url().ADMIN_THEME;?>assets/images/users/avatar-1.jpg" alt="" class="thumb-md img-circle">
				</div>
				<div class="user-info">
					<div class="dropdown">
						<a href="javascript:void();" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo $this->session->userdata['username']; ?> <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo base_url().'profile/edit'; ?>"><i class="icon-user"></i>My Profile </a></li>
							<li class="divider"></li>
							<li><a href="<?php echo base_url().'dashboard/logout'; ?>"><i class="icon-power"></i>Logout</a></li>
						</ul>
					</div>
					<p class="designation m-0"><?php echo $this->session->userdata['login_email']; ?></p>
				</div>
			</div>
			<ul class="sidebar-menu">
				<li>
					<a href="<?php echo base_url();?>dashboard" class="waves-effect">
						<i class="icon-home"></i> <span>Dashboard</span>
					</a>
				</li>
				<li class="treeview <?php if($current_class == 'contacts'){ ?>active<?php } ?>">
					<a href="<?php echo base_url()."project_management" ?>" class="waves-effect">
						<i class="fa fa-users"></i> <span>Project Management</span>
					</a>
				</li>
			</ul>
		</div>
		<!-- /.navbar-collapse -->
	</nav>
</aside>

