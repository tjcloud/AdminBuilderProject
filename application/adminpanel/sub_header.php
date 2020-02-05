<header class="topbar">
	<!-- Mobile Menu -->
	<div class="navbar navbar-default" role="navigation">
		<div class="container">
			<div class="pull-left toggle-menu-icon">
				<i class="fa fa-bars menu-icon"></i>
			</div>
			<form class="navbar-form pull-left" role="search">
				<div class="form-group search-div">
					<input type="text" class="form-control search-bar" placeholder="Search">
					<a href="javascript:void();"><i class="icon-magnifier"></i></a>
				</div>
			</form>
			<ul class="nav navbar-nav navbar-right pull-right">
				
				<li class="dropdown">
					<a href="javascript:void();" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true"><img src="<?php echo base_url().ADMIN_THEME;?>assets/images/users/avatar-1.jpg" alt="user-img" class="img-circle"> </a>
					<ul class="dropdown-menu">
						<li class="dropdown-user">
							<a href="javascript:void(0);">
								<div class="media">
									<div class="media-left">
										<img src="<?php echo base_url().ADMIN_THEME;?>assets/images/users/avatar-1.jpg">
									</div>
									<div class="media-body clearfix">
										<div class="media-heading">
											<h5><?php echo $this->session->userdata['username']; ?></h5>
										</div>
										<p class="m-0">
											<small><?php echo $this->session->userdata['login_email']; ?></small>
										</p>
									</div>
								</div>
							</a>
						</li>
						<li class="divider"></li>
						<li><a href="<?php echo base_url().'profile/edit'; ?>"><i class="icon-user"></i>My Profile</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo base_url().'dashboard/logout'; ?>"><i class="icon-power"></i>Logout</a></li>
					</ul>
				</li>
			</ul>
			<!--/.nav-collapse -->
		</div>
	</div>
</header>