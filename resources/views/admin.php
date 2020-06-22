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
	<div style="margin-bottom: 100px; width: 100%; float: left">
		<div id="app">
				
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
	<script src="<?php echo asset('js/admin.js')?>"></script>
</body>
</body>
</html>