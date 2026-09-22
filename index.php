<?php
	session_start();

	if (!isset($_SESSION['id'])) {
		header("Location: login.php");
		exit();
	} 

	include 'db.php';
	$user_id = isset($_GET["user_id"])? $_GET["user_id"] : $_SESSION['id'];
	$logged_user_id = isset($_SESSION['id'])? $_SESSION['id']: '';
	$user_stmt = mysqli_prepare($conn, "SELECT 
											*
										FROM
											User
										WHERE
											id = ?;");
	$frnd_stmt = mysqli_prepare($conn, "SELECT
											person2
										FROM
											friends
										WHERE
											person1 = ? ");

	$photos_stmt = mysqli_prepare($conn, "SELECT
											*
										FROM
											Photo
										WHERE
											user_id = ? ");

	$reels_stmt = mysqli_prepare($conn, "SELECT
											*
										FROM 
											Reel
										WHERE 
											user_id = ? ");

	mysqli_stmt_bind_param($user_stmt, "i", $user_id);
	mysqli_stmt_execute($user_stmt);
	$res = mysqli_stmt_get_result($user_stmt);
	if(!mysqli_num_rows($res)) die("User not found");
	$user = mysqli_fetch_assoc($res);
	$first_name = explode(" ",$user['name'])[0];

	mysqli_stmt_bind_param($user_stmt , "i", $logged_user_id);
	mysqli_stmt_execute($user_stmt);
	$new_res = mysqli_stmt_get_result($user_stmt);
	$logged_user = mysqli_fetch_assoc($new_res);

	mysqli_stmt_bind_param($frnd_stmt, "i", $user_id);
	mysqli_stmt_execute($frnd_stmt);
	$frnd_res = mysqli_stmt_get_result($frnd_stmt);
	$frnd_num = mysqli_num_rows($frnd_res);
	
	$tab = isset($_GET['tab']) ? $_GET['tab'] : '';
	$tabs = [ '', 'posts', 'about', 'reels', 'photos', 'followers'];
	if(!in_array($tab, $tabs)) $tab='posts';
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Facebook</title>
	<link type="image/png" sizes="120x120" rel="icon" href="favicon/icons8-facebook-color-120.png">
	<link rel="stylesheet" href="styles/index.css">
	<link href="https://fonts.cdnfonts.com/css/facebook-sans" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

	<header class="site-header">
		<div class="banner">
			<div class="banner-left">
				<div class="brand">
					<div class="facebook-icon">
						<svg viewBox="0 0 36 36" height="40" width="40">
							<path d="M20.181 35.87C29.094 34.791 36 27.202 36 18c0-9.941-8.059-18-18-18S0 8.059 0 18c0 8.442 5.811 15.526 13.652 17.471L14 34h5.5l.681 1.87Z"></path>
							<path d="M13.651 35.471v-11.97H9.936V18h3.715v-2.37c0-6.127 2.772-8.964 8.784-8.964 1.138 0 3.103.223 3.91.446v4.983c-.425-.043-1.167-.065-2.081-.065-2.952 0-4.09 1.116-4.09 4.025V18h5.883l-1.008 5.5h-4.867v12.37a18.183 18.183 0 0 1-6.53-.399Z"></path>
						</svg>
					</div>
				</div>
				<div class="search">
					<label for="search_input">
						<span>
							<svg viewBox="0 0 16 16" width="16" height="16"  aria-hidden="true">
								<g fill-rule="evenodd">
									<g fill-rule="nonzero">		
										<path d="M10.743 2.257a6 6 0 1 1-8.485 8.486 6 6 0 0 1 8.485-8.486zm-1.06 1.06a4.5 4.5 0 1 0-6.365 6.364 4.5 4.5 0 0 0 6.364-6.363z" ></path>
										<path d="M10.39 8.75a2.94 2.94 0 0 0-.199.432c-.155.417-.23.849-.172 1.284.055.415.232.794.54 1.103a.75.75 0 0 0 1.112-1.004l-.051-.057a.39.39 0 0 1-.114-.24c-.021-.155.014-.356.09-.563.031-.081.06-.145.08-.182l.012-.022a.75.75 0 1 0-1.299-.752z"></path>
										<path d="M9.557 11.659c.038-.018.09-.04.15-.064.207-.077.408-.112.562-.092.08.01.143.034.198.077l.041.036a.75.75 0 0 0 1.06-1.06 1.881 1.881 0 0 0-1.103-.54c-.435-.058-.867.018-1.284.175-.189.07-.336.143-.433.2a.75.75 0 0 0 .624 1.356l.066-.027.12-.061z" ></path>
										<path d="m13.463 15.142-.04-.044-3.574-4.192c-.599-.703.355-1.656 1.058-1.057l4.191 3.574.044.04c.058.059.122.137.182.24.249.425.249.96-.154 1.41l-.057.057c-.45.403-.986.403-1.411.154a1.182 1.182 0 0 1-.24-.182zm.617-.616.444-.444a.31.31 0 0 0-.063-.052c-.093-.055-.263-.055-.35.024l.208.232.207-.206.006.007-.22.257-.026-.024.033-.034.025.027-.257.22-.007-.007zm-.027-.415c-.078.088-.078.257-.023.35a.31.31 0 0 0 .051.063l.205-.204-.233-.209z"></path>
									</g>
								</g>
							</svg>
						</span>
						<input type="text" placeholder="Search Facebook" id="search_input">
					</label>
				</div>
			</div>
			<div class="banner-middle">
				<ul>
					<li>
						<a href="javascript:void(0)" title="Home">
							<span>
								<svg viewBox="0 0 24 24" width="24" height="24">
									<path d="M8.99 23H7.93c-1.354 0-2.471 0-3.355-.119-.928-.125-1.747-.396-2.403-1.053-.656-.656-.928-1.475-1.053-2.403C1 18.541 1 17.425 1 16.07v-4.3c0-1.738-.002-2.947.528-4.006.53-1.06 1.497-1.784 2.888-2.826L6.65 3.263c1.114-.835 2.02-1.515 2.815-1.977C10.294.803 11.092.5 12 .5c.908 0 1.707.303 2.537.786.795.462 1.7 1.142 2.815 1.977l2.232 1.675c1.391 1.042 2.359 1.766 2.888 2.826.53 1.059.53 2.268.528 4.006v4.3c0 1.355 0 2.471-.119 3.355-.124.928-.396 1.747-1.052 2.403-.657.657-1.476.928-2.404 1.053-.884.119-2 .119-3.354.119H8.99zM7.8 4.9l-2 1.5C4.15 7.638 3.61 8.074 3.317 8.658 3.025 9.242 3 9.937 3 12v4c0 1.442.002 2.424.101 3.159.095.706.262 1.033.485 1.255.223.223.55.39 1.256.485.734.099 1.716.1 3.158.1V14.5a2.5 2.5 0 0 1 2.5-2.5h3a2.5 2.5 0 0 1 2.5 2.5V21c1.443 0 2.424-.002 3.159-.101.706-.095 1.033-.262 1.255-.485.223-.222.39-.55.485-1.256.099-.734.101-1.716.101-3.158v-4c0-2.063-.025-2.758-.317-3.342-.291-.584-.832-1.02-2.483-2.258l-2-1.5c-1.174-.881-1.987-1.489-2.67-1.886C12.87 2.63 12.425 2.5 12 2.5c-.425 0-.87.13-1.53.514-.682.397-1.495 1.005-2.67 1.886zM14 21v-6.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5V21h4z"></path>
								</svg>
							</span>
						</a>
						<div class="a-hover"></div>
					</li>
					<li>
						<a href="javascript:void(0)" title="Reels">
							<span>
								<svg viewBox="0 0 24 24" width="24" height="24">
									<path d="M10.996 12.132A1 1 0 0 0 9.5 13v4a1 1 0 0 0 1.496.868l3.5-2a1 1 0 0 0 0-1.736l-3.5-2z"></path>
									<path d="M12.075 1h-.15C9.632 1 7.81 1 6.38 1.192c-1.472.198-2.674.616-3.623 1.565-.949.95-1.367 2.15-1.565 3.623C1 7.81 1 9.632 1 11.925v.15c0 2.293 0 4.116.192 5.545.198 1.472.616 2.674 1.565 3.623.95.949 2.15 1.367 3.623 1.565C7.81 23 9.632 23 11.925 23h.15c2.293 0 4.116 0 5.545-.192 1.472-.198 2.674-.616 3.623-1.565.949-.95 1.367-2.15 1.565-3.623.192-1.43.192-3.252.192-5.545v-.15c0-2.293 0-4.116-.192-5.545-.198-1.472-.616-2.674-1.565-3.623-.95-.949-2.15-1.367-3.623-1.565C16.19 1 14.368 1 12.075 1zM4.172 4.172c.515-.516 1.224-.83 2.475-.998l.183-.023L8.113 7H3.132c.013-.121.027-.239.042-.353.168-1.25.482-1.96.998-2.475zM10.22 7 8.895 3.023C9.778 3 10.801 3 12 3c.642 0 1.234 0 1.78.004L15.114 7H10.22zm6.253 2h4.507c.02.86.02 1.848.02 3 0 2.385-.002 4.074-.174 5.353-.168 1.25-.482 1.96-.998 2.475-.515.516-1.224.83-2.475.998-1.28.172-2.968.174-5.353.174s-4.074-.002-5.353-.174c-1.25-.168-1.96-.482-2.475-.998-.516-.515-.83-1.224-.998-2.475C3.002 16.073 3 14.385 3 12c0-1.152 0-2.14.02-3h13.454zm.747-2-1.316-3.949c.537.026 1.016.065 1.448.123 1.25.168 1.96.482 2.475.998.516.515.83 1.224.998 2.475.015.114.03.232.042.353H17.22z"></path>
								</svg>
							</span>
						</a>
						<div class="a-hover"></div>
					</li>
					<li>
						<a href="javascript:void(0)" title="Friends">
							<span>
								<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
									<path d="M12.496 5a4 4 0 1 1 8 0 4 4 0 0 1-8 0zm4-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-9 2.5a4 4 0 1 0 0 8 4 4 0 0 0 0-8zm-2 4a2 2 0 1 1 4 0 2 2 0 0 1-4 0zM5.5 15a5 5 0 0 0-5 5 3 3 0 0 0 3 3h8.006a3 3 0 0 0 3-3 5 5 0 0 0-5-5H5.5zm-3 5a3 3 0 0 1 3-3h4.006a3 3 0 0 1 3 3 1 1 0 0 1-1 1H3.5a1 1 0 0 1-1-1zm12-9.5a5.04 5.04 0 0 0-.37.014 1 1 0 0 0 .146 1.994c.074-.005.149-.008.224-.008h4.006a3 3 0 0 1 3 3 1 1 0 0 1-1 1h-3.398a1 1 0 1 0 0 2h3.398a3 3 0 0 0 3-3 5 5 0 0 0-5-5H14.5z"></path>
								</svg>
							</span>
						</a>
						<div class="a-hover"></div>
					</li>
					<li>
						<a href="javascript:void(0)" title="Groups">
							<span>
								<svg viewBox="0 0 24 24" width="24" height="24">
									<path d="M.5 12c0 6.351 5.149 11.5 11.5 11.5S23.5 18.351 23.5 12 18.351.5 12 .5.5 5.649.5 12zm2 0c0-.682.072-1.348.209-1.99a2 2 0 0 1 0 3.98A9.539 9.539 0 0 1 2.5 12zm.84-3.912A9.502 9.502 0 0 1 12 2.5a9.502 9.502 0 0 1 8.66 5.588 4.001 4.001 0 0 0 0 7.824 9.514 9.514 0 0 1-1.755 2.613A5.002 5.002 0 0 0 14 14.5h-4a5.002 5.002 0 0 0-4.905 4.025 9.515 9.515 0 0 1-1.755-2.613 4.001 4.001 0 0 0 0-7.824zM12 5a4 4 0 1 1 0 8 4 4 0 0 1 0-8zm-2 4a2 2 0 1 0 4 0 2 2 0 0 0-4 0zm11.291 1.01a9.538 9.538 0 0 1 0 3.98 2 2 0 0 1 0-3.98zM16.99 20.087A9.455 9.455 0 0 1 12 21.5c-1.83 0-3.54-.517-4.99-1.414a1.004 1.004 0 0 1-.01-.148V19.5a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v.438a1 1 0 0 1-.01.148z"></path>
								</svg>
							</span>
						</a>
						<div class="a-hover"></div>
					</li>
					<li class="hidden-class">
						<a>
							<span>
								<svg viewBox="0 0 24 24" width="24" height="24">
									<path d="M8 8a1 1 0 0 1 1 1v2h2a1 1 0 1 1 0 2H9v2a1 1 0 1 1-2 0v-2H5a1 1 0 1 1 0-2h2V9a1 1 0 0 1 1-1zm8 2a1.5 1.5 0 1 0 3 0 1.5 1.5 0 0 0-3 0zm-2 4a1.5 1.5 0 1 0 3 0 1.5 1.5 0 0 0-3 0z"></path>
									<path d="M.5 11a7 7 0 0 1 7-7h9a7 7 0 0 1 7 7v2a7 7 0 0 1-7 7h-9a7 7 0 0 1-7-7v-2zm7-5a5 5 0 0 0-5 5v2a5 5 0 0 0 5 5h9a5 5 0 0 0 5-5v-2a5 5 0 0 0-5-5h-9z"></path>
								</svg>
							</span>
						</a>
						<div class="a-hover"></div>
					</li>
				</ul>
			</div>
			<div class="banner-right">
				<div class="find">
					<span>Find friends</span>
				</div>
				<div class="right-icon">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true" >
						<path d="M18.5 1A1.5 1.5 0 0 0 17 2.5v3A1.5 1.5 0 0 0 18.5 7h3A1.5 1.5 0 0 0 23 5.5v-3A1.5 1.5 0 0 0 21.5 1h-3zm0 8a1.5 1.5 0 0 0-1.5 1.5v3a1.5 1.5 0 0 0 1.5 1.5h3a1.5 1.5 0 0 0 1.5-1.5v-3A1.5 1.5 0 0 0 21.5 9h-3zm-16 8A1.5 1.5 0 0 0 1 18.5v3A1.5 1.5 0 0 0 2.5 23h3A1.5 1.5 0 0 0 7 21.5v-3A1.5 1.5 0 0 0 5.5 17h-3zm8 0A1.5 1.5 0 0 0 9 18.5v3a1.5 1.5 0 0 0 1.5 1.5h3a1.5 1.5 0 0 0 1.5-1.5v-3a1.5 1.5 0 0 0-1.5-1.5h-3zm8 0a1.5 1.5 0 0 0-1.5 1.5v3a1.5 1.5 0 0 0 1.5 1.5h3a1.5 1.5 0 0 0 1.5-1.5v-3a1.5 1.5 0 0 0-1.5-1.5h-3zm-16-8A1.5 1.5 0 0 0 1 10.5v3A1.5 1.5 0 0 0 2.5 15h3A1.5 1.5 0 0 0 7 13.5v-3A1.5 1.5 0 0 0 5.5 9h-3zm0-8A1.5 1.5 0 0 0 1 2.5v3A1.5 1.5 0 0 0 2.5 7h3A1.5 1.5 0 0 0 7 5.5v-3A1.5 1.5 0 0 0 5.5 1h-3zm8 0A1.5 1.5 0 0 0 9 2.5v3A1.5 1.5 0 0 0 10.5 7h3A1.5 1.5 0 0 0 15 5.5v-3A1.5 1.5 0 0 0 13.5 1h-3zm0 8A1.5 1.5 0 0 0 9 10.5v3a1.5 1.5 0 0 0 1.5 1.5h3a1.5 1.5 0 0 0 1.5-1.5v-3A1.5 1.5 0 0 0 13.5 9h-3z"></path>
					</svg>
				</div>
				<div class="right-icon">
					<svg viewBox="0 0 16 16" width="20" height="20" fill="currentColor" aria-hidden="true">
						<path fill-rule="evenodd" d="M.5 8a7.5 7.5 0 1 1 4.006 6.638.341.341 0 0 0-.236-.041l-2.193.534A1 1 0 0 1 .87 13.923l.534-2.193a.341.341 0 0 0-.04-.236A7.47 7.47 0 0 1 .5 8zm11.389-.907a.56.56 0 0 0-.79-.78L9.25 7.75 7.294 6.327a1 1 0 0 0-1.386.205L4.111 8.906a.56.56 0 0 0 .791.781L6.75 8.25l1.957 1.423a1 1 0 0 0 1.385-.205l1.797-2.375z" clip-rule="evenodd"></path>
					</svg>
				</div>
				<div class="right-icon">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
						<path d="M3 9.5a9 9 0 1 1 18 0v2.927c0 1.69.475 3.345 1.37 4.778a1.5 1.5 0 0 1-1.272 2.295h-4.625a4.5 4.5 0 0 1-8.946 0H2.902a1.5 1.5 0 0 1-1.272-2.295A9.01 9.01 0 0 0 3 12.43V9.5zm6.55 10a2.5 2.5 0 0 0 4.9 0h-4.9z"></path>
					</svg>
					<div class="badge">
						<span class="badge-span"> 2 </span>
					</div>
				</div>
				<div class="profile-picture">
					<img src="images/<?php echo $user['photo']?>" height="40" width="40">
					<div class="dropdown">
						<svg viewBox="0 0 16 16" width="12" height="12" fill="currentColor" aria-hidden="true" class="x14rh7hd x1lliihq x1tzjh5l x1k90msu x2h7rmj x1qfuztq" style="--x-color: var(--primary-icon);">
							<g fill-rule="evenodd" transform="translate(-448 -544)">
								<path fill-rule="nonzero" d="M452.707 549.293a1 1 0 0 0-1.414 1.414l4 4a1 1 0 0 0 1.414 0l4-4a1 1 0 0 0-1.414-1.414L456 552.586l-3.293-3.293z"></path>
							</g>
						</svg>
					</div>
				</div>
				<div class="settings-box">
					<div class="all-profiles">
						<div class="shown-profile">
							<div class="shown-profile-img">
								<img src="images/<?php echo $logged_user['photo']?>" height="38" width="38"/>
							</div>
							<div class="shown-profile-name"><span><?php echo $logged_user['name'];?></span></div>
							<div class="shown-profile-hover"></div>
						</div>
						<hr class="all-profiles-ruler">
						<div class="all-profiles-button">
							<svg viewBox="0 0 16 16" width="16" height="16" fill="currentColor" aria-hidden="true" class="setting-svg">
								<path d="M8 .5a7.498 7.498 0 0 0-6.575 3.888.75.75 0 1 0 1.315.724A6.003 6.003 0 0 1 13.812 6.5h-.562a.75.75 0 0 0-.53 1.28l1.5 1.5a.75.75 0 0 0 1.28-.53V8A7.501 7.501 0 0 0 8 .5zM1.78 6.72a.75.75 0 0 0-1.28.53V8a7.501 7.501 0 0 0 14.075 3.612.75.75 0 0 0-1.314-.724c-.232.42-.511.81-.832 1.16A3.52 3.52 0 0 0 9.23 10H6.769a3.52 3.52 0 0 0-3.198 2.048A5.986 5.986 0 0 1 2.19 9.5h.561a.75.75 0 0 0 .53-1.28l-1.5-1.5z"></path>
								<path d="M5.5 6.5a2.5 2.5 0 1 1 5 0 2.5 2.5 0 0 1-5 0z"></path>
							</svg>
							<span>See all profiles</span>
						</div>
					</div>
					<div class="setting">
						<a href="javascript:void(0)" class="setting-link">
							<div class="setting-svg-container">
								<div class="setting-svg-inner">
									<svg viewBox="0 0 20 20" width="20" height="20" fill="currentColor" aria-hidden="true" class="setting-svg">
										<path d="M7.5 10a2.5 2.5 0 1 1 5 0 2.5 2.5 0 0 1-5 0z"></path>
										<path d="M17.773 8.983a1.748 1.748 0 0 0 0 2.034v-.001l.949 1.328c.302.423.31.988.023 1.42l-1.387 2.081a1.25 1.25 0 0 1-1.195.547l-1.235-.154a1.75 1.75 0 0 0-1.856 1.122l-.498 1.328a1.25 1.25 0 0 1-1.17.811H8.597a1.25 1.25 0 0 1-1.17-.811l-.476-1.269a1.75 1.75 0 0 0-1.91-1.115l-1.165.182a1.248 1.248 0 0 1-1.246-.561L1.238 13.75a1.25 1.25 0 0 1 .036-1.4l.934-1.307a1.75 1.75 0 0 0-.018-2.059l-.904-1.22a1.249 1.249 0 0 1-.06-1.399l1.398-2.272a1.25 1.25 0 0 1 1.258-.58l1.16.181A1.75 1.75 0 0 0 6.95 2.579l.476-1.269a1.25 1.25 0 0 1 1.17-.811h2.807c.52 0 .987.323 1.17.811l.498 1.328a1.752 1.752 0 0 0 1.856 1.122l1.235-.154a1.25 1.25 0 0 1 1.195.547l1.387 2.081a1.25 1.25 0 0 1-.023 1.42l-.95 1.329zM10 6a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"></path>
									</svg>
								</div>
							</div>
							<span>Settings & privacy</span>
							<div class="setting-svg-arrow">
								<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true">
									<path d="M8.116 3.116a1.25 1.25 0 0 1 1.768 0c.887.887 1.777 1.774 2.668 2.662 1.429 1.425 2.86 2.852 4.283 4.282a2.747 2.747 0 0 1-.005 3.88 8169.154 8169.154 0 0 1-6.946 6.944 1.25 1.25 0 0 1-1.768-1.768l2.683-2.681 4.263-4.263a.247.247 0 0 0 0-.349c-1.42-1.427-2.844-2.847-4.27-4.268a2473.35 2473.35 0 0 1-2.676-2.671 1.25 1.25 0 0 1 0-1.768z"></path>
								</svg>
							</div>
						</a>
					</div>
					<div class="setting">
						<a href="javascript:void(0)" class="setting-link">
							<div class="setting-svg-container">
								<div class="setting-svg-inner">
									<svg viewBox="0 0 20 20" width="20" height="20" fill="currentColor" aria-hidden="true" class="setting-svg">
										<path d="M10 .5a9.5 9.5 0 1 0 0 19 9.5 9.5 0 0 0 0-19zm0 4.996a2 2 0 0 0-2 2 .75.75 0 1 1-1.5 0 3.5 3.5 0 1 1 5.179 3.072c-.282.154-.53.34-.7.538-.166.194-.229.364-.229.515v.625a.75.75 0 0 1-1.5 0v-.625c0-.608.264-1.111.59-1.492a4.02 4.02 0 0 1 1.119-.877A2 2 0 0 0 10 5.496zm.001 10.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"></path>
									</svg>
								</div>
							</div>
							<span>Help & support</span>
							<div class="setting-svg-arrow">
								<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true">
									<path d="M8.116 3.116a1.25 1.25 0 0 1 1.768 0c.887.887 1.777 1.774 2.668 2.662 1.429 1.425 2.86 2.852 4.283 4.282a2.747 2.747 0 0 1-.005 3.88 8169.154 8169.154 0 0 1-6.946 6.944 1.25 1.25 0 0 1-1.768-1.768l2.683-2.681 4.263-4.263a.247.247 0 0 0 0-.349c-1.42-1.427-2.844-2.847-4.27-4.268a2473.35 2473.35 0 0 1-2.676-2.671 1.25 1.25 0 0 1 0-1.768z"></path>
								</svg>
							</div>
						</a>
					</div>
					<div class="setting">
						<a href="javascript:void(0)" class="setting-link">
							<div class="setting-svg-container">
								<div class="setting-svg-inner">
									<svg viewBox="0 0 20 20" width="20" height="20" fill="currentColor" aria-hidden="true" class="setting-svg">
										<path d="M.544 18.788c.044.302.169.795.673 1.033.503.238.964.02 1.225-.137.26-.158.561-.406.881-.67l2.1-1.729c.144-.119.225-.185.289-.23a.601.601 0 0 1 .061-.04h.003s.003-.002.008-.002a.61.61 0 0 1 .064-.007C5.926 17 6.03 17 6.218 17h6.587c1.367 0 2.47 0 3.337-.116.9-.122 1.658-.38 2.26-.982.602-.602.86-1.36.982-2.26.116-.867.116-1.97.116-3.337V8.696c0-1.367 0-2.47-.116-3.337-.122-.9-.38-1.658-.982-2.26-.602-.601-1.36-.86-2.26-.981C15.275 2 14.172 2 12.805 2h-5.61c-1.367 0-2.47 0-3.336.116-.9.12-1.659.38-2.26.982-.603.601-.861 1.36-.982 2.26C.5 6.226.5 7.328.5 8.696v8.986c0 .415 0 .805.044 1.106zM10 5.001a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5a.75.75 0 0 1 .75-.75zM10 14a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"></path>
									</svg>
								</div>
							</div>
						<span>Report a problem</span>
						</a>
					</div>
					<div class="setting">
						<a href="javascript:void(0)" class="setting-link">
							<div class="setting-svg-container">
								<div class="setting-svg-inner">
									<svg viewBox="0 0 20 20" width="20" height="20" fill="currentColor" aria-hidden="true" class="setting-svg">
										<path d="M10.293 1.691A.75.75 0 0 0 9.66.501a9.503 9.503 0 0 0 .343 19c4.594-.001 8.426-3.26 9.31-7.593a.75.75 0 0 0-1.07-.822 6.336 6.336 0 0 1-7.95-9.395z"></path>
									</svg>
								</div>
							</div>
							<span>Display & accesibilty</span>
							<div class="setting-svg-arrow">
								<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true">
									<path d="M8.116 3.116a1.25 1.25 0 0 1 1.768 0c.887.887 1.777 1.774 2.668 2.662 1.429 1.425 2.86 2.852 4.283 4.282a2.747 2.747 0 0 1-.005 3.88 8169.154 8169.154 0 0 1-6.946 6.944 1.25 1.25 0 0 1-1.768-1.768l2.683-2.681 4.263-4.263a.247.247 0 0 0 0-.349c-1.42-1.427-2.844-2.847-4.27-4.268a2473.35 2473.35 0 0 1-2.676-2.671 1.25 1.25 0 0 1 0-1.768z"></path>
								</svg>
							</div>
						</a>
					</div>
					<div class="setting">
						<a href="logout.php" class="setting-link">
							<div class="setting-svg-container">
								<div class="setting-svg-inner">
									<i data-visualcompletion="css-img" style="background-image: url(&quot;https://static.xx.fbcdn.net/rsrc.php/y0/r/RSUSvP7GGtO.webp&quot;); background-position: 0px -109px; background-size: auto; width: 20px; height: 20px; background-repeat: no-repeat; display: inline-block;"></i>
								</div>
							</div>
								<span>Log out</span>
						</a>
					</div>	
					<footer>
					<span>
						<ul class="footer-links">
							<li>
								<a href="javascript:void(0)" role="link">Privacy</a>
								<span><span class="seperator">·</span></span>	
							</li>
							<li>
								<a href="javascript:void(0)" role="link"> Terms</a>
								<span><span class="seperator">·</span></span>	
							</li>
							<li>
								<a href="javascript:void(0)" role="link"> Advertising</a>
								<span><span class="seperator">·</span></span>	
							</li>
							<li>
								<a href="javascript:void(0)" role="link">
									Ad choices 
								</a>
								<span class="seperator">
									<svg viewBox="0 0 12 12" width="12" height="12" fill="currentColor" aria-hidden="true">
										<path d="M10.063 5.892a.125.125 0 0 1 0 .216L5.63 8.668V7a.625.625 0 1 0-1.25 0v1.884c0 .866.937 1.407 1.687.974l4.62-2.667a1.375 1.375 0 0 0 0-2.382L3.188.48a1.375 1.375 0 0 0-2.062 1.19v8.66a1.375 1.375 0 0 0 2.063 1.191l.398-.23a.625.625 0 0 0-.625-1.082l-.398.23a.125.125 0 0 1-.188-.109V1.67c0-.096.104-.156.188-.108l7.5 4.33z"></path>
										<path d="M5 5.75A.625.625 0 1 0 5 4.5a.625.625 0 0 0 0 1.25z"></path>
									</svg>
								</span>
							<span><span class="seperator">·</span></span>	
							</li>
							<li>
								<a href="javascript:void(0)" role="link"> Cookies</a>
								<span><span class="seperator">·</span></span>	
							</li>
							<li>
								<a href="javascript:void(0)" role="link"> More</a>
								<span></span>
							</li>
						</ul>
					</span>
				</footer>
				</div>
			</div>
		</div> 
	</header>


	<div class="main">
		<main>
			<div id="top_tab">
				<div id='cover_container'>
					<div id="cover">
						<div class='cover-wrap'>
							<div class="cover">
								<img src="images/<?php echo htmlspecialchars($user['cover_photo']) ?>" alt="Cover photo" class="cover-image">
							</div>
						</div>
					</div>
				</div>
				<div id="profile_container">
					<div id="profile_inner">
						<div id="profile">
							<div id="profile_pic">
								<div class="picture">
									<div id="img">
										<img src="images/<?php echo htmlspecialchars($user['photo']) ?>" alt="Profile photo" class="profile-pic">
									</div>
								</div>
							</div>
							<div class="info-container">
								<div id="info">
									<div id="info_top">
										<div id="info_left">
											<div id="name_container">
												<div id="name_inner">
													<div id="name">
														<div>
															<span>
																<?php echo htmlspecialchars($user['name']) ?> <span><svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" role="img" title="Verified account">
																	<title>Verified account</title>
																	<path d="M8.004 1.183a1.5 1.5 0 0 1 2.049-.55L12 1.759 13.947.634a1.5 1.5 0 0 1 2.05.549L17.045 3H19.5A1.5 1.5 0 0 1 21 4.5v2.453l1.817 1.05a1.5 1.5 0 0 1 .55 2.049L22.241 12l1.124 1.947a1.5 1.5 0 0 1-.55 2.05L21 17.044V19.5a1.5 1.5 0 0 1-1.5 1.5h-2.454l-1.05 1.817a1.5 1.5 0 0 1-2.048.549L12 22.241l-1.948 1.125a1.5 1.5 0 0 1-2.049-.549L6.955 21H4.5A1.5 1.5 0 0 1 3 19.5v-2.455l-1.817-1.049a1.5 1.5 0 0 1-.549-2.049L1.758 12 .634 10.053a1.5 1.5 0 0 1 .549-2.05L3 6.954V4.5A1.5 1.5 0 0 1 4.5 3h2.454l1.05-1.817zm9.703 9.024a1 1 0 0 0-1.414-1.414l-5.44 5.44a.5.5 0 0 1-.707 0l-2.439-2.44a1 1 0 0 0-1.414 1.414l2.44 2.44a2.5 2.5 0 0 0 3.535 0l5.44-5.44z"></path>
																</svg></span>

															</span>

															
														</div>
													</div>
												</div>
											</div>
											<div id="frnds">
												<span><a href="index.php?user_id=<?php echo $user_id?>&tab=followers"><strong>121M</strong> followers</a></span>

											</div>
										</div>
										<div id="info_right">
											<div class="follow-button info-button">
												<img draggable="false" height="16" width="16" alt="" referrerpolicy="origin-when-cross-origin" src="https://static.xx.fbcdn.net/rsrc.php/yk/r/nSurzCxCD4K.webp">
												<span>Follow</span>
											</div>
											<div class="search-button info-button">
												<svg viewBox="0 0 16 16" width="16" height="16"  aria-hidden="true">
													<g fill-rule="evenodd">
														<g fill-rule="nonzero">		
															<path d="M10.743 2.257a6 6 0 1 1-8.485 8.486 6 6 0 0 1 8.485-8.486zm-1.06 1.06a4.5 4.5 0 1 0-6.365 6.364 4.5 4.5 0 0 0 6.364-6.363z" ></path>
															<path d="M10.39 8.75a2.94 2.94 0 0 0-.199.432c-.155.417-.23.849-.172 1.284.055.415.232.794.54 1.103a.75.75 0 0 0 1.112-1.004l-.051-.057a.39.39 0 0 1-.114-.24c-.021-.155.014-.356.09-.563.031-.081.06-.145.08-.182l.012-.022a.75.75 0 1 0-1.299-.752z"></path>
															<path d="M9.557 11.659c.038-.018.09-.04.15-.064.207-.077.408-.112.562-.092.08.01.143.034.198.077l.041.036a.75.75 0 0 0 1.06-1.06 1.881 1.881 0 0 0-1.103-.54c-.435-.058-.867.018-1.284.175-.189.07-.336.143-.433.2a.75.75 0 0 0 .624 1.356l.066-.027.12-.061z" ></path>
															<path d="m13.463 15.142-.04-.044-3.574-4.192c-.599-.703.355-1.656 1.058-1.057l4.191 3.574.044.04c.058.059.122.137.182.24.249.425.249.96-.154 1.41l-.057.057c-.45.403-.986.403-1.411.154a1.182 1.182 0 0 1-.24-.182zm.617-.616.444-.444a.31.31 0 0 0-.063-.052c-.093-.055-.263-.055-.35.024l.208.232.207-.206.006.007-.22.257-.026-.024.033-.034.025.027-.257.22-.007-.007zm-.027-.415c-.078.088-.078.257-.023.35a.31.31 0 0 0 .051.063l.205-.204-.233-.209z"></path>
														</g>
													</g>
												</svg>
												<span>Search</span>
											</div>
											<div class="expand-button info-button">
												<svg viewBox="0 0 16 16" width="16" height="16" fill="currentColor" aria-hidden="true">
													<path d="M6.94 10.354a1.5 1.5 0 0 0 2.12 0l2.647-2.647a1 1 0 0 0-1.414-1.414L8 8.586 5.707 6.293a1 1 0 0 0-1.414 1.414l2.646 2.647z"></path>
												</svg>
											</div>
										</div>
									</div>
									<div class="profile-about">
										<span>Bringing the world closer together.</span>
									</div>
									<div class="info-extras">
										<div class="extras-container">
												<span>
													<span class="extras-img"><img height="12" width="12" class="xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw x1b0d499 xep6ejk" alt="" aria-hidden="true" referrerpolicy="origin-when-cross-origin" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E %3Cpath d='M7.43 3h9.14c1.355 0 2.471 0 3.355.119.928.124 1.747.396 2.403 1.053.657.656.928 1.475 1.053 2.403.12.884.119 2 .119 3.354v4.141c0 1.355 0 2.471-.119 3.355-.125.928-.396 1.747-1.053 2.403-.656.657-1.475.928-2.403 1.053-.884.12-2 .119-3.354.119H7.429c-1.354 0-2.47 0-3.354-.119-.928-.125-1.747-.396-2.403-1.053-.657-.656-.929-1.475-1.053-2.403-.12-.884-.119-2-.119-3.354V9.929c0-1.354 0-2.47.119-3.354.124-.928.396-1.747 1.053-2.403.656-.657 1.475-.928 2.403-1.053.884-.12 2-.119 3.354-.119ZM21.4 6.841c-.096-.706-.263-1.033-.486-1.255-.222-.223-.55-.39-1.255-.485-.584-.079-1.324-.096-2.328-.1L19.731 8h1.751a11.546 11.546 0 0 0-.082-1.16ZM12.08 5l2.4 3h2.689l-2.4-3h-2.688Z'/%3E %3C/svg%3E"></span>
													<span>Public figure</span>
												</span>
										</div>
										<div class="extras-container">
											<span>
												<span class="extras-img"><img height="12" width="12" class="xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw x1b0d499 xep6ejk" alt="" aria-hidden="true" referrerpolicy="origin-when-cross-origin" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E %3Cpath d='M12 .5a9 9 0 0 0-9 9c0 2.64 1.351 5.422 2.859 7.736 1.529 2.347 3.325 4.37 4.418 5.522a2.365 2.365 0 0 0 3.446 0c1.093-1.152 2.89-3.175 4.418-5.522C19.65 14.922 21 12.14 21 9.5a9 9 0 0 0-9-9Zm0 4.999a4 4 0 1 1 0 8 4 4 0 0 1 0-8Z'/%3E %3C/svg%3E"></span>
												<span>Palo Alto, CA</span>
											</span>
										</div>
										<div class="extras-container">
											<span>
												<span class="extras-img"><img height="12" width="12" class="xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw x1b0d499 xep6ejk" alt="" aria-hidden="true" referrerpolicy="origin-when-cross-origin" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E %3Cpath d='M7 .5a1 1 0 0 0-1 1V4a2 2 0 0 0-2 2v1h-.5A2.5 2.5 0 0 0 1 9.5v11A2.5 2.5 0 0 0 3.5 23h17a2.5 2.5 0 0 0 2.5-2.5V6.015a2.5 2.5 0 0 0-1.572-2.32l-7-2.8A2.5 2.5 0 0 0 11 3.214V7h-1V6a2 2 0 0 0-2-2V1.5a1 1 0 0 0-1-1ZM3.5 9h2.55c-.033.162-.05.329-.05.5V21H3.5a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5Zm10.186-6.249 7 2.8a.5.5 0 0 1 .314.464V20.5a.5.5 0 0 1-.5.5H18V9.5A2.5 2.5 0 0 0 15.5 7H13V3.215a.5.5 0 0 1 .686-.464ZM10 12a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2h-2a1 1 0 0 1-1-1Zm0 3.5a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2h-2a1 1 0 0 1-1-1Z'/%3E %3C/svg%3E"></span>
												<span>Meta</span>
											</span>
										</div>
										<div class="extras-container">
											<span>
												<span class="extras-img"><img height="12" width="12" class="xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw x1b0d499 xep6ejk" alt="" aria-hidden="true" referrerpolicy="origin-when-cross-origin" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E %3Cpath d='M13.387 2.223a2.5 2.5 0 0 0-2.774 0L.945 8.668a1 1 0 0 0 0 1.664l9.668 6.445.015.01.54.36a1.5 1.5 0 0 0 1.664 0l.546-.364.009-.006 8.113-5.409V14a1 1 0 1 0 2 0V9.5a.995.995 0 0 0-.456-.84l-9.657-6.437Z'/%3E %3Cpath d='M4 16.833v-2.662l6.336 4.224a3 3 0 0 0 3.328 0L20 14.171v2.662a1 1 0 0 1-.445.832l-6.168 4.112a2.5 2.5 0 0 1-2.774 0l-6.168-4.112A1 1 0 0 1 4 16.833Z'/%3E %3C/svg%3E"></span>
												<span>Harvard University</span>
											</span>
										</div>
									</div>
									<div id="profile_space">
										<div></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="line-container">
					<div class="line-inner">
						<div class="line"></div>
					</div>
				</div>

			</div>

			<div></div>

			<div id="nav_bar" sticky-show="false">
				<nav id="nav">
					<div id="nav_container">
						<div id="links">
							<div id="left_links_container">
								<div id="left_links">
									<a href="javascript:void(0)" class="tab-link selected" tab="posts">
										<div class="link">
											<span>All</span>
											<div class="link-active"></div>
										</div>
										<div class="link-hover"></div>
									</a>
									<a href="javascript:void(0)" class="tab-link" tab="about">
										<div class="link">
											<span>About</span>
											<div class="link-active"></div>
										</div>
										<div class="link-hover"></div>
									</a>
									<a href="javascript:void(0)" class="tab-link reels-link" tab="reels">
										<div class="link">
											<span>Reels</span>
											<div class="link-active"></div>
										</div>
										<div class="link-hover"></div>
									</a>
									<a href="javascript:void(0)" class="tab-link photos-link" tab="photos">
										<div class="link">
											<span>Photos</span>
											<div class="link-active"></div>
										</div>
										<div class="link-hover"></div>
									</a>
									<a href="javascript:void(0)" class="tab-link followers-link" tab="followers">
										<div class="link">
											<span>Friends</span>
											<div class="link-active"></div>
										</div>
										<div class="link-hover"></div>
									</a>
									<a href="javascript:void(0)" class="tab-link unselected">
										<div class="link">
											<span>More</span>
											<svg viewBox="0 0 20 20" width="16" height="16" fill="currentColor" aria-hidden="true">
												<path d="M10 14a1 1 0 0 1-.755-.349L5.329 9.182a1.367 1.367 0 0 1-.205-1.46A1.184 1.184 0 0 1 6.2 7h7.6a1.18 1.18 0 0 1 1.074.721 1.357 1.357 0 0 1-.2 1.457l-3.918 4.473A1 1 0 0 1 10 14z"></path>
											</svg>
										</div>
										<div class="link-hover"></div>
									</a>

								</div>
								<div id="sticky_container">
									<div id="sticky_inner">
										<div id="sticky">
											<a>
												<div id="sticky_profile">
													<div>
													<img src="images/<?php echo htmlspecialchars($user['photo']);?>" alt="profile pic"  height="40" width="40"/>
													</div>
												</div>
												<div id="sticky_name">
													<span><?php echo htmlspecialchars($user['name']);?></span>
												</div>
											</a>
										</div>
									</div>

								</div>
							</div>
							<div id="right_link">
								<div id="more_btn">
									<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true">
										<circle cx="12" cy="12" r="2.5"></circle>
										<circle cx="19.5" cy="12" r="2.5"></circle>
										<circle cx="4.5" cy="12" r="2.5"></circle>
									</svg>
								</div>
							</div>
						</div>
					</div>
				</nav>
			</div>

			<div id="content_container">
				<div id="content">
				</div>
			</div>
			
		</main>
	</div>
	<script>
		$(document).ready(function() {
			var navTopPosition = $("#nav_bar").offset().top;

			//make navigation bar sticky
			$(window).scroll(function() {
				var currentScroll = $(window).scrollTop();

				if (currentScroll >= (navTopPosition - 56)) {
					$("#nav_bar").attr("sticky-show", "true");
				} else {
					$("#nav_bar").attr("sticky-show", "false");
				}
			});


			//get posts tab content
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				$("#content").html(this.responseText); 
			};
			xhttp.open("GET",`tabs/posts.php?user_id=<?php echo $user_id;?>`, true);
			xhttp.send();

			//On clicking tab nav link make nav link active and take to respective tab
			$(".tab-link").click(function(event) {
				event.preventDefault();

				$(".tab-link ").removeClass("selected"); 
				console.log($(".tab-link"));

				if(!($(this).hasClass("unselected"))) {
					$(this).addClass("selected");  
				}

				var tab = $(this).attr("tab");
				if(tab) {
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
						$("#content").html(this.responseText); 
					};
					xhttp.open("GET", `tabs/${tab}.php?user_id=<?php echo $user_id;?>`, true);
					xhttp.send(); 
				}
			});

			//On clicking see photos tab header make photos nav link active and take to photos tab
			$(".photos-tab .header").click(function() {
				$(".photos-tab .header").removeClass("active-header");
				$(this).addClass("active-header");
			});

			//login form submission
			$(".submit").on('click', function() {
				$("#login_form").submit();
			})

			//open or close settings box
			$(".profile-picture").click(function(){
				$(".settings-box").toggleClass("display-box");
			})

			//go to logged user profile
			$(".shown-profile").click(function(){
				var loc ="index.php?user_id=<?php echo $logged_user_id?>";
				window.location.href = loc;

			})
		});
	</script>
</body>
</html>

