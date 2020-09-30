<!DOCTYPE html>
<html lang="<?php echo str_replace('_', '-', app()->getLocale())?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Booking nails </title>
	<link rel="stylesheet" type="text/css" href="<?php echo asset('css/style.css')?>">
	<script async defer src="https://connect.facebook.net/en_US/sdk.js"></script>
	<script>
	  window.fbAsyncInit = function() {
	    FB.init({
	      appId            : '549592788810021',
	      autoLogAppEvents : true,
	      xfbml            : true,
	      version          : 'v7.0'
	    });
	  };
	</script>
</head>
<body>
	<div style="margin-bottom: 100px; width: 100%; float: left">
		<div id="app">
			<header>
				<div id="logo-container">
					<img src="images/logo.jpg">
				</div>
				<div id="slogan"> Nail Booking</div>
			</header>
			<section>
				<div class="description">
                    <span class="description-title">Demo version</span>
                    <div>
						<p>This is demo version without beautiful design. Just be the ux and function</p>
					</div>
				</div>
			</section>
			<section>
				<div class="navigator">
					<div class="nav-item-contain">
						<router-link class="nav-item" to="/booking" @click.capture="changePage('booking', $event)">
							<span class="nav-item-content">
								<img src="images/booking-icon2.png"/>
								Book now
							</span>
						</router-link>
						<router-link v-if="user.id" class="nav-item" to="/manage_appointment" @click.capture="changePage('manageAppoinment', $event)">
							<span class="nav-item-content">
								<img src="images/booking-icon.png"/>
								Manage Appoinment
							</span>
						</router-link>
						<router-link v-if="!user.id" to="/login" class="nav-item" @click.capture="changePage('login', $event); login()">
							<span class="nav-item-content">
								<img src="images/login-icon1.png"/>
								Login
							</span>
						</router-link>
						<router-link v-else to="/profile" class="nav-item" @click.capture="changePage('profile', $event)">
							<span class="nav-item-content">
								<img src="images/profile.png"/>
								Profile
							</span>
						</router-link>
						<div v-if="user.id" class="nav-item" @click="logout()">
							<span class="nav-item-content">
								<img src="images/Untitled.png"/>
								Logout
							</span>
						</div>
						<div class="nav-item router-link-active">
							<a style="color: white" href="/admin">Login Admin</a>
						</div>
					</div>
				</div>
			</section>
				
			<!-- Main component -->
			<section class="main">
    			<transition>
				  <keep-alive>
				    <router-view></router-view>
				  </keep-alive>
				</transition>
			</section> 
		</div>
	</div>
	<script src="<?php echo asset('js/frontend.js')?>"></script>
</body>
</body>
</html>