<!DOCTYPE html>
<html lang="<?php echo str_replace('_', '-', app()->getLocale())?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Booking nails admin</title>
	<link rel="stylesheet" type="text/css" href="<?php echo asset('css/style.css')?>">
	<link rel="stylesheet" type="text/css" href="<?php echo asset('css/admin.css')?>">
</head>
<body>
	<div style="margin-bottom: 100px; width: 100%; float: left; padding-top: 21px">
		<div id="app">
			<div id="header" v-if="!showMenu">
				<button @click="showMenu = true">show Menu</button>
			</div>
			<div id="left-menu" v-if="showMenu">
				<div class="menu-detail">
					<div class="fl" style="width: 100%" @click="showMenu = false">
						<router-link to="/manage_service" class="nav-item">
							<span class="nav-item-content">
								<img src="images/profile.png"/>
								Manage services
							</span>
						</router-link>
					</div>
					<div class="fl" style="width: 100%" @click="showMenu = false">
						<router-link to="/appointments" class="nav-item">
							<span class="nav-item-content">
								<img src="images/profile.png"/>
								Appointments
							</span>
						</router-link>
					</div>
					<div class="fl" style="width: 100%" @click="showMenu = false">
						<router-link to="/customers" class="nav-item">
							<span class="nav-item-content">
								<img src="images/profile.png"/>
								Customers
							</span>
						</router-link>
					</div>
				</div>
				<div class="over-lay" @click="showMenu = false"></div>
			</div>
			<!-- Main component -->
			<section class="main">
    			<transition>
				  <keep-alive>
				    <router-view></router-view>
				  </keep-alive>
				</transition>
			</section> 
			<notifications />
		</div>
	</div>

	<script src="<?php echo asset('js/admin.js')?>"></script>
</body>
</body>
</html>