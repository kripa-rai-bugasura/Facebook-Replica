<?php
	include 'db.php';

	$user_id = isset($_GET["user_id"])? intval($_GET["user_id"]) : 0;
	$post = isset($_POST["post"]) ? test_input($_POST["post"]) : '';

	if($user_id>0 && !empty($post))
	{
		$user_stmt = mysqli_prepare($conn, "SELECT name, photo FROM User WHERE id = ?");
		mysqli_stmt_bind_param($user_stmt, "i", $user_id);
		mysqli_stmt_execute($user_stmt);
		$user_res = mysqli_stmt_get_result($user_stmt);
		$user = mysqli_fetch_assoc($user_res);

		// add post to the wall
		$current_time = date("Y-m-d H:i:s");
		$insert_stmt = mysqli_prepare($conn, "INSERT INTO 
													Post (user_id, content, DOC) 
												VALUES 
													(?, ?, ?)");
		mysqli_stmt_bind_param($insert_stmt, "iss", $user_id, $post, $current_time);
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
		if(mysqli_stmt_execute($insert_stmt))
		{
			$date = date("d M Y, h:i A", strtotime($current_time));
			
?>
			<div class="posts">
				<div>
					<div class="header-tab posts-tab-header">
						<div class="post-header">
							<a>
								<div class="post-profile">
									<img src="images/<?php echo htmlspecialchars($user['photo']); ?>" alt="profile pic" height="40" width="40"/>
								</div>
								<div class="post-name">
									<span class="name"><?php echo htmlspecialchars($user['name']); ?></span>
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
							<div class='content'><p><?php echo $post; ?></p></div>
							
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
								<div class="user-img">
									<img src="images/<?php echo $user['photo'];?>" height="32" width="32"/>
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
<?php
		exit;
		} else {
			http_response_code(500);
			echo "Failed to post. Database error.";
			exit;
		}
	}
	else {
		http_response_code(400);
		echo "Failed to post. Message content cannot be empty.";
		exit;
	}

	// validate form data
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	};


?>

