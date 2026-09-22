<?php 
	session_start();
	include('../db.php');
	$user_id = isset($_GET["user_id"])? $_GET["user_id"] : '';

	$user_stmt = mysqli_prepare($conn, "SELECT 
									*
								FROM
									User
								WHERE
									id = ?;");
	$reels_stmt = mysqli_prepare($conn, "SELECT
											*
										FROM 
											Reel
										WHERE 
											user_id = ? ");

	$photos_stmt = mysqli_prepare($conn, "SELECT
											*
										FROM
											Photo
										WHERE
											user_id = ? ");

	mysqli_stmt_bind_param($user_stmt, "i", $user_id);
	mysqli_stmt_execute($user_stmt);
	$res = mysqli_stmt_get_result($user_stmt);
	if(!mysqli_num_rows($res)) die("User not found");
	$user = mysqli_fetch_assoc($res);
	$first_name = explode(" ",$user['name'])[0];
?>
<div id="about">
	<div id="about_tab">
		<nav class="about-nav">
			<div id="about_tab_header">
				<h2><span>About</span></h2>
			</div>
			<div id="nav_links">
				<a href="javascript:void(0)" tab="#overview" class="active-sub-link"><span>Overview</span></a>
				<a href="javascript:void(0)" tab="#overview"><span>Work and education</span></a>
				<a href="javascript:void(0)" tab="#overview"><span>Places lived</span></a>
				<a href="javascript:void(0)" tab="#overview"><span>Contact and basic info</span></a>
				<a href="javascript:void(0)" tab="#overview"><span>Privacy and legal info</span></a>
				<a href="javascript:void(0)" tab="#overview"><span>Profile transparency</span></a>
				<a href="javascript:void(0)" tab="#overview"><span>Family and relationships</span></a>
				<a href="javascript:void(0)" tab="#overview"><span>Details about Mark</span></a>
				<a href="javascript:void(0)" tab="#overview"><span>Life updates</span></a>
			</div>
		</nav>
		<div id="about_tab_inner">
			<div>
				<div class="about-tab" id="overview">
					<div></div>
					<div class="about-info">
						<div><img src="images/work.jpg" /></div>
						<div class="about-txt"><span>Works at Meta and Biohub</span></div>
					</div>
					<div class="about-info">
						<div><img src="images/study.jpg"/></div>
						<div class="about-txt">
							<div><span>Studied Computer Science and Psychology at Harvard University</span></div>
							<div><span>Attended from 2002 to 2004</span></div>
						</div>
					</div>
					<div class="about-info">
						<div><img src="images/live.jpg" /></div>
						<div class="about-txt"><span>Lives in Palo Alto, California</span></div>
					</div>
					<div class="about-info">
						<div><img src="images/location.jpg" /></div>
						<div class="about-txt"><span>From Dobbs Ferry, New York</span></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="reels" class="tab">
	<div id="reel_tab_header" class="header-tab">
		<h2><a>Reels</a></h2>
	</div>
	<div class="second-header">
		<div class="header active-header">
			<span><?php echo $first_name;?>'s reels</span>
			<div class="content-link"></div>
		</div>
		<div class="second-space">
		</div>
	</div>
	<div id="reel">
		<div class="tab-card">
			<?php 
				mysqli_stmt_bind_param($reels_stmt, "i", $user_id);
				mysqli_stmt_execute($reels_stmt);
				$reels_res = mysqli_stmt_get_result($reels_stmt);
				while($reel = mysqli_fetch_assoc($reels_res)){
			?>
				<div class="reel-card">
					<div><img src="images/<?php echo $reel['reel']; ?>" alt="<?php echo $first_name?>'s reel"/></div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>	
<div id="photos" class="tab">
	<div class="photos-tab">
		<div class="photos_tab_header header-tab">
			<h2><a>Photos</a></h2>
		</div>
		<div class="second-header">
			<div class="header active-header">
				<span><?php echo $first_name;?>'s Photos</span>
				<div class="content-link"></div>
			</div>
			<div class="header">
				<span>Photos of <?php echo $first_name;?></span>
				<div class="content-link"></div>
			</div>
			<div class="header">
				<span>Albums</span>
				<div class="content-link"></div>
			</div>
			<div class="second-space">
			</div>
		</div>
		<div id="photo">
			<div class="tab-card">
				<?php 
					mysqli_stmt_bind_param($photos_stmt, "i", $user_id);
					mysqli_stmt_execute($photos_stmt);
					$photos_res = mysqli_stmt_get_result($photos_stmt);
					while($photo = mysqli_fetch_assoc($photos_res)){
				?>
					<div class="photo-card">
						<div><img src="images/<?php echo $photo['image']; ?>" alt="<?php echo $first_name?>'s photo"/></div>
					</div>
				<?php } ?>
			</div>
		</div>

	</div>
</div>
					
<script>
	$(document).ready(function(){

		//make clicked link active on navigation
		$("#nav_links a").click(function(event) {
			event.preventDefault();

			$("#nav_links a").removeClass("active-sub-link"); 
			$(this).addClass("active-sub-link");            

			var target = $(this).attr("tab");
			$(".about-tab").hide();    
			$(target).show(); 
		});

		//On clicking photos header make photos nav link active and take to photos tab
		$(".photos-tab .header").click(function() {
			$(".photos-tab .header").removeClass("active-header");
			$(this).addClass("active-header");
		});

		$(".header-tab a").click(function(){
			var xhttp = new XMLHttpRequest();
			var tab = $(this).text().toLowerCase();
			$(".tab-link ").removeClass("selected"); 
			$(`[tab='${tab}']`).addClass("selected"); 
			xhttp.onreadystatechange = function() {
				$("#content").html(this.responseText); 
			};
			xhttp.open("GET",`tabs/${tab}.php?user_id=<?php echo $user_id;?>`, true);
			xhttp.send();
		})
	})
	

</script>