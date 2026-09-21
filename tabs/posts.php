<?php
	session_start();
	include 'db.php';
	$user_id = isset($_GET["user_id"])? $_GET["user_id"] : '';
	$logged_user_id = isset($_SESSION['id'])? $_SESSION['id']: '';

	$logged = true;
	if (!isset($_SESSION['email'])) {
		$_logged = false;
	}
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
	// $frnd = mysqli_fetch_assoc($frnd_res);
	$frnd_num = mysqli_num_rows($frnd_res);
	function formatDate($dateString) {
		$timestamp = strtotime($dateString);
		if (!$timestamp) {
			return htmlspecialchars($dateString);
		}

		$currentTime = time();
		$timeDifference = $currentTime - $timestamp;

		if ($timeDifference < 0 || $timeDifference < 60) {
			return "Just now";
		}

		$minutes = round($timeDifference / 60);
		if ($minutes < 60) {
			return $minutes === 1 ? "a minute ago" : $minutes . " minutes ago";
		}
		
		$hours = round($timeDifference / 3600);
		if ($hours < 24) {
			return $hours === 1 ? "an hour ago" : $hours . " hours ago";
		}
		
		$days = round($timeDifference / 86400);
		if ($days < 7) {
			return $days === 1 ? "a day ago" : $days . " days ago";
		}
		
		$postYear = date('Y', $timestamp);
		$currentYear = date('Y', $currentTime);

		if ($postYear === $currentYear) {
			return date('j F', $timestamp);
		} else {
			return date('j F Y', $timestamp);
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div class="posts-tab">
			<div class="posts-left">
				<div class="intro-tab left-tab">
				<div class="det">
					<div class="det-header">
						<h2>Personal details</h2>
					</div>
					<div class="det-body">
						<div class="ele-container">
							<div class="body-ele">
								<div class="ele-img">
									<img height="24" width="24" class="xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw x1b0d499 xep6ejk" alt="" aria-hidden="true" referrerpolicy="origin-when-cross-origin" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E %3Cpath d='M12 5.499a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 4a2 2 0 1 1 4 0 2 2 0 0 1-4 0Z'/%3E %3Cpath d='M12 .5a9 9 0 0 0-9 9c0 2.64 1.351 5.422 2.859 7.736 1.529 2.347 3.325 4.37 4.418 5.522a2.365 2.365 0 0 0 3.446 0c1.093-1.152 2.89-3.175 4.418-5.522C19.65 14.922 21 12.14 21 9.5a9 9 0 0 0-9-9Zm-7 9a7 7 0 0 1 14 0c0 2.036-1.077 4.408-2.535 6.645-1.435 2.204-3.14 4.128-4.192 5.236a.365.365 0 0 1-.546 0c-1.051-1.108-2.757-3.032-4.192-5.236C6.077 13.908 5 11.535 5 9.5Z'/%3E %3C/svg%3E">
								</div>
								<div class="ele-det">
									<span>Lives in Palo Alto, California</span>
								</div>
							</div>
						</div>
						<div class="ele-container">
							<div class="body-ele">
								<div class="ele-img">
									<img height="24" width="24" class="xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw x1b0d499 xep6ejk" alt="" aria-hidden="true" referrerpolicy="origin-when-cross-origin" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E %3Cpath d='M15.029 23h.042c1.354 0 2.47 0 3.354-.12.928-.124 1.747-.396 2.403-1.052.657-.656.928-1.476 1.053-2.403.12-.884.12-2 .119-3.355V9.766a1 1 0 0 0 1.125-1.647l-5.552-4.442c-1.156-.925-2.094-1.675-2.92-2.186C13.792.958 12.957.622 12 .622c-.957 0-1.791.336-2.652.869-.827.51-1.765 1.261-2.92 2.186L5 4.82V3a1 1 0 0 0-2 0v3.419l-2.125 1.7A1 1 0 0 0 2 9.766v6.304c0 1.354 0 2.47.119 3.355.125.927.396 1.747 1.053 2.403.656.656 1.475.928 2.403 1.053.884.119 2 .119 3.354.119h6.1Zm-4.63-19.81c.689-.425 1.155-.569 1.601-.569.447 0 .912.144 1.6.57.711.44 1.555 1.112 2.773 2.086L20 8.18V16c0 1.442-.002 2.423-.1 3.158-.096.706-.263 1.033-.486 1.256-.222.222-.55.39-1.255.485-.551.074-1.24.093-2.159.099V14.5a2.5 2.5 0 0 0-2.5-2.5h-3A2.5 2.5 0 0 0 8 14.5v6.498c-.918-.006-1.608-.025-2.159-.1-.706-.094-1.033-.262-1.255-.484-.223-.223-.39-.55-.485-1.256C4.002 18.423 4 17.442 4 16V8.18l3.627-2.902C8.845 4.304 9.69 3.631 10.4 3.191ZM14 21h-4v-6.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V21Z'/%3E %3C/svg%3E">		
								</div>
								<div class="ele-det">
									<span>From Dobbs Ferry, New York</span>
								</div>
							</div>
						</div>
						<div class="ele-container">
							<div class="body-ele">
								<div class="ele-img">
									<img height="24" width="24" class="xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw x1b0d499 xep6ejk" alt="" aria-hidden="true" referrerpolicy="origin-when-cross-origin" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E %3Cpath d='M10.704 1.973c-.09.17-.204.431-.204.727 0 .81.627 1.55 1.5 1.55s1.5-.74 1.5-1.55c0-.296-.115-.558-.204-.727C12.982 1.376 12.472.911 12 .44c-.471.472-.982.937-1.296 1.534ZM12 5.5a1 1 0 0 1 1 1V9h4.066c.886 0 1.65 0 2.262.082.655.088 1.284.287 1.793.797.51.51.709 1.138.797 1.793.082.612.082 1.376.082 2.262v4.128c0 .654 0 1.241-.064 1.717-.07.52-.232 1.052-.668 1.489-.437.436-.97.598-1.489.668-.476.064-1.063.064-1.717.064H5.938c-.654 0-1.241 0-1.717-.064-.52-.07-1.052-.232-1.489-.668-.436-.437-.598-.97-.668-1.489C2 19.303 2 18.716 2 18.062v-4.128c0-.886 0-1.65.082-2.262.088-.655.287-1.284.797-1.793.51-.51 1.138-.709 1.793-.797C5.284 9 6.048 9 6.934 9H11V6.5a1 1 0 0 1 1-1Zm8 8.706V14c0-.971-.002-1.599-.064-2.061-.059-.434-.153-.57-.229-.646-.076-.076-.212-.17-.646-.229C18.6 11.002 17.971 11 17 11H7c-.971 0-1.599.002-2.061.064-.434.059-.57.153-.646.229-.076.076-.17.212-.229.646C4.002 12.4 4 13.029 4 14v.206l.072.062c.608.52 1.515.515 2.122-.016a3.61 3.61 0 0 1 4.75-.005 1.603 1.603 0 0 0 2.112 0 3.61 3.61 0 0 1 4.75.005 1.624 1.624 0 0 0 2.122.016l.072-.062Zm-16 2.27V18c0 .735.002 1.186.046 1.513.039.286.093.334.1.34v.001c.007.007.055.061.341.1.327.044.778.046 1.513.046h12c.735 0 1.186-.002 1.513-.046.286-.039.334-.093.34-.1h.001c.007-.007.061-.055.1-.341.044-.327.046-.778.046-1.513v-1.523a3.63 3.63 0 0 1-3.51-.72 1.61 1.61 0 0 0-2.117-.004 3.603 3.603 0 0 1-4.746 0 1.61 1.61 0 0 0-2.116.004 3.63 3.63 0 0 1-3.511.72Z'/%3E %3C/svg%3E">
								</div>
								<div class="ele-det">
									<span>14 May,1984</span>
								</div>
							</div>
						</div>
						<div class="ele-container">
							<div class="body-ele">
								<div class="ele-det">
									<span>See more personal details</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="det">
					<div class="det-header">
						<h2>Communities</h2>
					</div>
					<div class="det-body">
						<div class="ele-container">
							<div class="body-ele">
								<div class="ele-img">
									<img height="24" width="24" alt="" aria-hidden="true" referrerpolicy="origin-when-cross-origin" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E %3Cpath d='M8.11 6.693a1 1 0 0 1 0 1.415 5.48 5.48 0 0 0-1.61 3.889 5.48 5.48 0 0 0 1.61 3.889A1 1 0 1 1 6.698 17.3 7.48 7.48 0 0 1 4.5 11.997c0-2.071.84-3.948 2.197-5.304a1 1 0 0 1 1.414 0Zm7.78 0a1 1 0 0 1 1.413 0 7.48 7.48 0 0 1 2.197 5.304 7.48 7.48 0 0 1-2.197 5.303 1 1 0 0 1-1.414-1.414 5.48 5.48 0 0 0 1.611-3.89 5.48 5.48 0 0 0-1.61-3.889 1 1 0 0 1 0-1.414ZM12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z'/%3E %3Cpath d='M.5 12C.5 5.649 5.649.5 12 .5S23.5 5.649 23.5 12 18.351 23.5 12 23.5c-1.922 0-3.736-.472-5.33-1.308a.63.63 0 0 0-.447-.069l-3.4.882a1.5 1.5 0 0 1-1.828-1.829l.882-3.4a.63.63 0 0 0-.07-.445A11.454 11.454 0 0 1 .5 12ZM12 2.5A9.5 9.5 0 0 0 2.5 12c0 1.59.39 3.088 1.08 4.402.287.55.404 1.216.233 1.877l-.668 2.576 2.577-.668a2.626 2.626 0 0 1 1.875.234A9.454 9.454 0 0 0 12 21.5a9.5 9.5 0 0 0 0-19Z'/%3E %3C/svg%3E">
								</div>
								<div class="ele-det">
									<span>Meta Channel</span>
								</div>
							</div>
						</div>
						<div class="ele-container"></div>
					</div>
				</div>
				<div class="det">
					<div class="det-header">
						<h2>Work</h2>
					</div>
					<div class="det-body">
						<div class="ele-container">
							<div class="body-ele">
								<div class="ele-img">
									<svg aria-hidden="true"  data-visualcompletion="ignore-dynamic" role="none" style="height: 40px; width: 40px;"><mask id="_r_30_"><circle cx="20" cy="20" fill="white" r="20"></circle></mask><g mask="url(#_r_30_)"><image style="height: 40px; width: 40px;" x="0" y="0" height="100%" preserveAspectRatio="xMidYMid slice" width="100%" xlink:href="https://scontent.fblr20-4.fna.fbcdn.net/v/t39.30808-1/549467469_836487459040466_8493633843466289931_n.jpg?stp=cp0_dst-jpg_tt6&amp;cstp=mx1080x1080&amp;ctp=s40x40&amp;_nc_cat=1&amp;ccb=1-7&amp;_nc_sid=f907e8&amp;_nc_ohc=6tTMhWdcbH0Q7kNvwGD5uDN&amp;_nc_oc=AdqGQGWG6s_v-LT7HO74Fbs-FpXHEq4JB3wqO9fhgtwECrHvvIixY2c72Ut9vc15evgN7_rqNy8q1njAjlyKNp4O&amp;_nc_zt=24&amp;_nc_ht=scontent.fblr20-4.fna&amp;_nc_gid=b4Og_o_hewAtnZHDkkOSew&amp;_nc_ss=7b2a8&amp;oh=00_AQKAAY2c99GIf1Q_ynw7u-sSdeMDbcseqziK68UL-KfMAw&amp;oe=6AB6761F"></image><circle  cx="20" cy="20" r="20"></circle></g></svg>
								</div>
								<div class="ele-det">
									<span>Meta</span>
								</div>
							</div>
						</div>
						<div class="ele-container">
							<div class="body-ele">
								<div class="ele-det">
									<span>See more work</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="det">
					<div class="det-header">
						<h2>Education</h2>
					</div>
					<div class="det-body">
						<div class="ele-container">
							<div class="body-ele">
								<div class="ele-img">
									<svg aria-hidden="true" data-visualcompletion="ignore-dynamic" role="none" style="height: 40px; width: 40px;"><mask id="_r_34_"><circle cx="20" cy="20" fill="white" r="20"></circle></mask><g mask="url(#_r_34_)"><image style="height: 40px; width: 40px;" x="0" y="0" height="100%" preserveAspectRatio="xMidYMid slice" width="100%" xlink:href="https://scontent.fblr20-4.fna.fbcdn.net/v/t39.30808-1/279079571_364449472388453_298263262942608273_n.jpg?stp=cp0_dst-jpg_tt6&amp;cstp=mx960x960&amp;ctp=s40x40&amp;_nc_cat=1&amp;ccb=1-7&amp;_nc_sid=f907e8&amp;_nc_ohc=yAR8vYuu684Q7kNvwFtieCN&amp;_nc_oc=AdqXNPFYVz0CTsPA2IA6ayigtS8WnCpe9lSQAKW0Nlba6waEtW9vILHUXU2KpqiASL2EbilrClwUPDQNJgvCK0gz&amp;_nc_zt=24&amp;_nc_ht=scontent.fblr20-4.fna&amp;_nc_gid=b4Og_o_hewAtnZHDkkOSew&amp;_nc_ss=7b2a8&amp;oh=00_AQJd74alMoi5tfRUJwBxNR1RspXHL2Saz_RrXgA45PBIlw&amp;oe=6AB677BD"></image><circle cx="20" cy="20" r="20"></circle></g></svg>
								</div>
								<div class="ele-det">
									<span>Harvard University</span>
								</div>
							</div>
						</div>
						<div class="ele-container">
							<div class="body-ele">
								<div class="ele-det">
									<span>See more education</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
				<div class="photos-tab left-tab">
					<div class="left-header left-header-photos">
						<h2><a>Photos</a></h2>
						<a class="photos-link"><span>See All Photos</span></a> 
					</div>
					<div id="about_photo">
						<div>
							<div class="about-tab-card-container">
								<?php 
										mysqli_stmt_bind_param($photos_stmt, "i", $user_id);
										mysqli_stmt_execute($photos_stmt);
										$photos_res = mysqli_stmt_get_result($photos_stmt);
										if(mysqli_num_rows($photos_res)==0){
								?>
									<div><span> No images to display. </span></div>
								<?php } else {?>
									<div class="about-tab-card">
										<?php
											while($photo = mysqli_fetch_assoc($photos_res)){
										?>
											<div class="about-photo-card">
												<div><img src="images/<?php echo $photo['image']; ?>" alt="<?php echo $first_name?>'s photo"/></div>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>

				</div>
				<footer>
					<span>
						<ul class="footer-links">
							<li>
								<a href="/privacy/policy/?entry_point=comet_dropdown" role="link">Privacy</a>
								<span><span class="seperator">·</span></span>	
							</li>
							<li>
								<a href="/policies?ref=pf" role="link"> Terms</a>
								<span><span class="seperator">·</span></span>	
							</li>
							<li>
								<a href="/business/" role="link"> Advertising</a>
								<span><span class="seperator">·</span></span>	
							</li>
							<li>
								<a href="/help/568137493302217" role="link">
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
								<a href="/policies/cookies/" role="link"> Cookies</a>
								<span><span class="seperator">·</span></span>	
							</li>
							<li>
								<a href="" role="link"> More</a>
								<span></span>
							</li>
						</ul>
					</span>
				</footer>


			</div>
			<div class="posts-right">

				<div class="filter-tab posts">
					<div class="filter-header"><h2><span>Posts</span></h2></div>
					<div class="filter-container">
						<div class="filter">
							<div class="filter-svg">
								<svg viewBox="0 0 16 16" width="16" height="16" fill="currentColor" aria-hidden="true" >
									<path d="M2.614 5.75H1.75a.75.75 0 0 1 0-1.5h.864a2.501 2.501 0 1 1 0 1.5zM11 8.5c1.12 0 2.067.736 2.386 1.75h.864a.75.75 0 0 1 0 1.5h-.864A2.501 2.501 0 1 1 11 8.5zM7.5 11a.75.75 0 0 0-.75-.75h-5a.75.75 0 0 0 0 1.5h5A.75.75 0 0 0 7.5 11zm1.75-6.75a.75.75 0 0 0 0 1.5h5a.75.75 0 0 0 0-1.5h-5z"></path>
								</svg>
							</div>
							<span>Filters</span>
						</div>
					</div>
				</div>

			<?php if($_SESSION['id'] == $user_id) { ?>
				<div class="posts">
					<div>
						<div class="posts-tab-header">
							<div class="create-new-post">
								<div class="post-profile">
									<div>
										<img src="images/<?php echo htmlspecialchars($user['photo']);?>" alt="profile pic"  height="40" width="40"/>
									</div>
								</div>
								<div class="create-post-button">
									<a>What's on your mind?</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="backdrop hidden-class">
					<div class="modal">
						<div class="modal-header">
							<h2>Create post</h2>
							<div class="modal-header-svg">
								<svg viewBox="0 0 20 20" width="20" height="20" fill="currentColor" aria-hidden="true" >
									<path d="M15.543 3.043a1 1 0 1 1 1.414 1.414L11.414 10l5.543 5.542a1 1 0 0 1-1.414 1.415L10 11.414l-5.543 5.543a1 1 0 0 1-1.414-1.415L8.586 10 3.043 4.457a1 1 0 1 1 1.414-1.414L10 8.586l5.543-5.543z"></path>
								</svg>
							</div>
						</div>
						<div class="modal-body">
							<div class="modal-header-tab">
								<div class="modal-profile-header">
									<a>
										<div class="modal-profile">
											<div>
											<img src="images/<?php echo htmlspecialchars($user['photo']);?>" alt="profile pic"  height="40" width="40"/>
											</div>
										</div>
										<div>
											<span class="name"><?php echo htmlspecialchars($user['name']);?></span>
											<div class="sub-buttons">
												<div class="friends-dropdown modal-mini-button">
													<img draggable="false" height="12" width="12" alt="Friends" referrerpolicy="origin-when-cross-origin" src="https://static.xx.fbcdn.net/rsrc.php/y7/r/OjzrEPbbX0p.webp">
													<span>Friends</span>
													<i data-visualcompletion="css-img" class="x15mokao x1ga7v0g x16uus16 xbiv7yw x1b0d499 xep6ejk" style="background-image: url(&quot;https://static.xx.fbcdn.net/rsrc.php/yp/r/twpm7Tz4xLN.webp&quot;); background-position: 0px -796px; background-size: auto; width: 12px; height: 12px; background-repeat: no-repeat; display: inline-block;"></i>
												</div>
												<div class="ai-off modal-mini-button">
													<svg viewBox="0 0 12 12" width="12" height="12" fill="currentColor" aria-hidden="true">
														<path d="M6 .25a5.75 5.75 0 1 0 0 11.5A5.75 5.75 0 0 0 6 .25zM6 3a.5.5 0 0 1 .5.5v2h2a.5.5 0 0 1 0 1h-2v2a.5.5 0 0 1-1 0v-2h-2a.5.5 0 0 1 0-1h2v-2A.5.5 0 0 1 6 3z"></path>
													</svg>
													<span>AI label off</span>
													<i data-visualcompletion="css-img" class="x15mokao x1ga7v0g x16uus16 xbiv7yw x1b0d499 xep6ejk" style="background-image: url(&quot;https://static.xx.fbcdn.net/rsrc.php/yp/r/twpm7Tz4xLN.webp&quot;); background-position: 0px -796px; background-size: auto; width: 12px; height: 12px; background-repeat: no-repeat; display: inline-block;"></i>
												</div>
											</div>
										</div>
									</a>
								</div>
							</div>
							<div class="post-input">
								<textarea class="new-post-input" id="new_post" placeholder="What's on your mind?"></textarea>
							</div>
						</div>
						<div class="modal-footer">
							<button class="post-button" disabled>Post</button>
						</div>
					</div>
				</div>
				<!-- <div class="posts">
					<div>
						<div class="header-tab posts-tab-header">
							<div class="create-new-post">
								<div class="post-profile">
									<div>
									<img src="images/<?php echo htmlspecialchars($user['photo']);?>" alt="profile pic"  height="60" width="60"/>
									</div>
								</div>
								<div id="post_form">
									<div class="create-post">
										<label id="post_label" for="new_post">
											<textarea id="new_post" name="post" placeholder="What's on your mind?"></textarea>
										</label>
									</div>
									<button id="post_button">Post</button>
								</div>
							</div>
						</div>
					</div>
				</div> -->
				<?php } ?>
				<?php 
					$post_stmt = mysqli_prepare($conn,"SELECT 
															*
														FROM 
															Post
														WHERE user_id = ? ");
					mysqli_stmt_bind_param($post_stmt, "i", $user_id);
					mysqli_stmt_execute($post_stmt);
					$post_res = mysqli_stmt_get_result($post_stmt);

					$cmnt_stmt = mysqli_prepare($conn,"SELECT 
															*
														FROM 
															Comments
														WHERE post_id = ?");

					$likes_stmt = mysqli_prepare($conn,"SELECT 
															*
														FROM 
															Likes
														WHERE user_id = ? AND post_id = ?");

					while($post = mysqli_fetch_assoc($post_res)) {

						mysqli_stmt_bind_param($cmnt_stmt, "i", $post['id']);
						mysqli_stmt_execute($cmnt_stmt);
						$cmnt_res = mysqli_stmt_get_result($cmnt_stmt);
						$num_cmnt = mysqli_num_rows($cmnt_res);

						mysqli_stmt_bind_param($likes_stmt, "ii", $user_id, $post['id']);
						mysqli_stmt_execute($likes_stmt);
						$like_res = mysqli_stmt_get_result($likes_stmt);
						$num_likes = mysqli_num_rows($like_res);
						$date = date("d M Y, h:i A",strtotime($post["DOC"]));
				?>
		
				<div class="posts">
						<div>
							<div class="header-tab posts-tab-header">
								<div class="post-header">
										<a>
											<div class="post-profile">
												<div>
												<img src="images/<?php echo htmlspecialchars($user['photo']);?>" alt="profile pic"  height="40" width="40"/>
												</div>
											</div>
											<div class="post-name">
												<span class="name"><?php echo htmlspecialchars($user['name']);?></span>
												<span class="post-date"><?php echo formatDate($date)?> <svg viewBox="0 0 16 16" width="12" height="12" fill="currentColor" xmlns="http://w3.org">
													<g fill-rule="evenodd" transform="translate(-448 -544)">
														<g transform="translate(354 143.5)">
														<path d="M109.5 408.5c0 3.23-2.04 5.983-4.903 7.036l.07-.036c1.167-1 1.814-2.967 2-3.834.214-1 .303-1.3-.5-1.96-.31-.253-.677-.196-1.04-.476-.246-.19-.356-.59-.606-.73-.594-.337-1.107.11-1.954.223a2.666 2.666 0 0 1-1.15-.123c-.007 0-.007 0-.013-.004l-.083-.03c-.164-.082-.077-.206.006-.36h-.006c.086-.17.086-.376-.05-.529-.19-.214-.54-.214-.804-.224-.106-.003-.21 0-.313.004l-.003-.004c-.04 0-.084.004-.124.004h-.037c-.323.007-.666-.034-.893-.314-.263-.353-.29-.733.097-1.09.28-.26.863-.8 1.807-.22.603.37 1.166.667 1.666.5.33-.11.48-.303.094-.87a1.128 1.128 0 0 1-.214-.73c.067-.776.687-.84 1.164-1.2.466-.356.68-.943.546-1.457-.106-.413-.51-.873-1.28-1.01a7.49 7.49 0 0 1 6.524 7.434"/>
														<path d="M104.107 415.696A7.498 7.498 0 0 1 94.5 408.5a7.48 7.48 0 0 1 3.407-6.283 5.474 5.474 0 0 0-1.653 2.334c-.753 2.217-.217 4.075 2.29 4.075.833 0 1.4.561 1.333 2.375-.013.403.52 1.78 2.45 1.89.7.04 1.184 1.053 1.33 1.74.06.29.127.65.257.97a.174.174 0 0 0 .193.096"/>
														<path fill-rule="nonzero" d="M110 408.5a8 8 0 1 1-16 0 8 8 0 0 1 16 0zm-1 0a7 7 0 1 0-14 0 7 7 0 0 0 14 0z"/>
														</g>
													</g>
													</svg>
												</span>
											</div>
										</a>
								</div>
							</div>
						</div>
						<div class="tab-card posts-tab-inner">
							<div class="posts-card">
								<div class="card">
									<?php
										if($post['content']) {
											echo "<div class='content'><p>".$post["content"]."</p></div>";
										}
										if($post['image']) {
											echo "<div class='card-image'><img src='images/posts/".htmlspecialchars($post['image'])."' alt='Post Image'/></div>";
										}
									?>
									<div class="bar">
										<div class="activity-stat">
											<div  class='cmnt-shr'>
												<div class="act-stat">
													<div class="activity">
														<span><i data-visualcompletion="css-img" style="background-image: url(&quot;https://static.xx.fbcdn.net/rsrc.php/yE/r/yzprM0PNrGU.webp&quot;); background-position: 0px -441px; background-size: 21px 809px; width: 20px; height: 20px; background-repeat: no-repeat; display: inline-block;"></i></span>
													</div>
													<div class="stat"><span>10K</span></div>
												</div>								
												<div class="act-stat">
													<div class="activity">
														<span><i data-visualcompletion="css-img" style="background-image: url(&quot;https://static.xx.fbcdn.net/rsrc.php/yE/r/yzprM0PNrGU.webp&quot;); background-position: 0px -231px; background-size: 21px 809px; width: 20px; height: 20px; background-repeat: no-repeat; display: inline-block;"></i></span>
													</div>
													<div class="stat"><span>5K</span></div>
												</div>
												<div class="act-stat">
													<div class="activity">
														<span><i data-visualcompletion="css-img" style="background-image: url(&quot;https://static.xx.fbcdn.net/rsrc.php/yE/r/yzprM0PNrGU.webp&quot;); background-position: 0px -588px; background-size: 21px 809px; width: 20px; height: 20px; background-repeat: no-repeat; display: inline-block;"></i></span>
													</div>
													<div class="stat"><span>8K</span></div>
												</div>
											</div>
											<div class="react"><div class="like-img"><img src='images/reaction.svg' height='18' width='18'/><img src='images/heart.svg' height='18' width='18'/></div></div>

										</div>
									</div>
									<div class='comments'>
										<!-- <?php 
											while($cmnt = mysqli_fetch_assoc($cmnt_res)){
												$cmnt_date = date("d M Y, h:i A",strtotime($cmnt["created"]));
										?>
											<div class='comment'>
												<div class='cmnt-date'><?php echo htmlspecialchars($cmnt_date);?></div>
												<div><p><?php echo htmlspecialchars($cmnt['comment'])?></p></div>
											</div>
										<?php } ?> -->

										<div class="user-img">
											<img src="images/<?php echo $logged_user['photo'];?>" height="32" width="32"/>
											<div class="dropdown">
												<svg viewBox="0 0 16 16" width="12" height="12" fill="currentColor" aria-hidden="true" class="x14rh7hd x1lliihq x1tzjh5l x1k90msu x2h7rmj x1qfuztq" style="--x-color: var(--primary-icon);">
													<g fill-rule="evenodd" transform="translate(-448 -544)">
														<path fill-rule="nonzero" d="M452.707 549.293a1 1 0 0 0-1.414 1.414l4 4a1 1 0 0 0 1.414 0l4-4a1 1 0 0 0-1.414-1.414L456 552.586l-3.293-3.293z"></path>
													</g>
												</svg>
											</div>
										</div>
										<div class="cmnt-field">
											<input class="cmnt-inp" placeholder="Write a comment..." />
										</div>

									</div>
								</div>
							</div>
						</div>
				</div>
				<?php } ?>
			</div>		
	</div>
	<script>
		$(document).ready( function() {
			$(".photos-link").click( function(){
				var xhttp = new XMLHttpRequest();
				$(".tab-link ").removeClass("selected"); 
				$("[tab='photos']").addClass("selected"); 
				xhttp.onreadystatechange = function() {
					$("#content").html(this.responseText); 
				};
				xhttp.open("GET",`tabs/photos.php?user_id=<?php echo $user_id;?>`, true);
				xhttp.send();
			})

			$(".left-header-photos").click( function(){
				var xhttp = new XMLHttpRequest();
				$(".tab-link ").removeClass("selected"); 
				$("[tab='photos']").addClass("selected"); 
				xhttp.onreadystatechange = function() {
					$("#content").html(this.responseText); 
				};
				xhttp.open("GET",`tabs/photos.php?user_id=<?php echo $user_id;?>`, true);
				xhttp.send();
			})

			$(document).ready(function() {
				$('.new-post-input').on('input', function() {
					if ($.trim($(this).val()) === '') {
						$('.post-button').attr('disabled', 'disabled');
					} else {
						$('.post-button').removeAttr('disabled');
					}
				});
			});

			$(".create-post-button").click(function(e) {
				$(".backdrop").toggleClass("hidden-class");
				$("#new_post").focus();
			})

			$(".modal-header-svg").click(function()	{
				$(".backdrop").addClass("hidden-class");
			})
			$(".post-button").click(function(e){

				// prevent default behavior on submit
				e.preventDefault();
				const post = document.getElementById("new_post").value;
				console.log(post);
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if(this.readyState == 4 ) 	{
						if(this.status == 200) {
							document.getElementById("new_post").value = "";
							$(".posts-right").children().eq(1).after(this.responseText);
						} else {
							alert("An error occurred: " + this.responseText)
						}
						$(".backdrop").addClass("hidden-class");
					}
				}
				xmlhttp.open("POST","tabs/post.php?user_id=" + <?php echo $user_id?> ,true);
				xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xmlhttp.send("post="+encodeURIComponent(post));
				
			})
		})
	</script>
</body>
</html>