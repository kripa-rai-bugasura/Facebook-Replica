<?php
	include 'db.php';
	$user_id = isset($_GET["user_id"])? $_GET["user_id"] : '';
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

	mysqli_stmt_bind_param($user_stmt, "i", $user_id);
	mysqli_stmt_execute($user_stmt);
	$res = mysqli_stmt_get_result($user_stmt);
	if(!mysqli_num_rows($res)) die("User not found");
	$user = mysqli_fetch_assoc($res);
	$first_name = explode(" ",$user['name'])[0];

	mysqli_stmt_bind_param($frnd_stmt, "i", $user_id);
	mysqli_stmt_execute($frnd_stmt);
	$frnd_res = mysqli_stmt_get_result($frnd_stmt);
	$frnd_num = mysqli_num_rows($frnd_res);
?>

<div id="followers" class="tab">
	<div id="follower_tab">
		<div id="followtab_head" class="header-tab">
			<div id="frnd_header"><h2><a>Friends</a></h2></div>
			<div id="frnd_search">
				<div>
					<label for="search" id="search_label">
						<span>
							<svg viewBox="0 0 16 16" width="16" height="16" fill="currentColor" aria-hidden="true">
								<path d="M7.5 1a6.5 6.5 0 1 0 3.835 11.749l1.958 1.958a1 1 0 0 0 1.414-1.414l-1.958-1.958A6.5 6.5 0 0 0 7.5 1zM3 7.5a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0z"></path>
							</svg>
						</span>
						<input id="search" type="text" placeholder="Search"></input>
					</label>
				</div>
			</div>
		</div>
		<div class="second-header">
			<div class="header active-header">
				<span>Followers</span>
				<div class="content-link"></div>
			</div>
			<div class="second-space">
			</div>
		</div>
		<div id="friends">
			<?php while($frnd = mysqli_fetch_assoc($frnd_res)){
				mysqli_stmt_bind_param($user_stmt, "i", $frnd['person2']);
				mysqli_stmt_execute($user_stmt);
				$ufrnd_res = mysqli_stmt_get_result($user_stmt);
				$person = mysqli_fetch_assoc($ufrnd_res);
				$name = explode(" ",$person['name'])[0];
			?>
				<div class="frnd-card">
					<a href="index.php?user_id=<?php echo $frnd['person2']?>">
						<div><img src="images/<?php echo $person['photo']; ?>" alt="<?php echo $name?>'s photo"/></div>
						<div class="frnd-name"><span><?php echo $person['name']; ?></span></div>
					</a>
				</div>
			<?php } ?>
		</div>

	</div>
</div>

<script>
$(document).ready(function() {
	$('#search').on('input', function() {
		var value = $(this).val().toLowerCase();
		
		$('#friends .frnd-card').filter(function() {
			var name = $(this).find('.frnd-name span').text().toLowerCase();
			$(this).toggle(name.indexOf(value) > -1);
		});
	});
})
</script>

