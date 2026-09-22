<?php
include 'db.php';
session_start();
if(isset($_SESSION['id']))
{
	$id = $_SESSION['id'];
	header("Location: index.php?user_id=" . urlencode($id));
	exit;
}

$id_error = $pwd_error = $success = '';
$id_err = $pwd_err = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$id_error = $pwd_error = $success = '';
	$id = trim($_POST["login_id"] ?? '');
	$pwd = trim($_POST["password"] ?? '');
	if (empty($id)) {
		$id_err = "error-container";
		$id_error = render_error("The email address or mobile number you entered isn't connected to an account.");
	} elseif (empty($pwd)) {
		$pwd_err = "error-container";
		$pwd_error = render_error("The password you've entered is incorrect.");
	} else {
		$stmt = mysqli_prepare($conn, "SELECT * FROM User WHERE email_id = ? OR phone = ?");
		mysqli_stmt_bind_param($stmt, "ss", $id, $id);
		mysqli_stmt_execute($stmt);
		$res = mysqli_stmt_get_result($stmt);
		$user = mysqli_fetch_assoc($res);
		if ($user == null) {
			$id_err = "error-container";
			$id_error = render_error("The email address or mobile number you entered isn't connected to an account.");
		} elseif ($pwd != $user['password']) {
			$pwd_err = "error-container";
			$pwd_error = render_error("The password you've entered is incorrect.");
		} else {
			session_start();
			$_SESSION['id'] = $user['id'];
			header("Location: index.php?user_id=" . urlencode($user['id']));
			exit;
		}
	}
}

// validate form data
function render_error(string $message)
{
	return "
		<div class='error-alert'>
			<div class='icon'>
				<svg viewBox='0 0 24 24' fill='currentColor' width='16' height='16' aria-hidden='true'>
                	<path d='M12 23c6.075 0 11-4.925 11-11S18.075 1 12 1 1 5.925 1 12s4.925 11 11 11zm0-2a9 9 0 1 1 0-18 9 9 0 0 1 0 18zm1.25-5c0 .6-.416 1-1.25 1-.833 0-1.25-.4-1.25-1s.417-1 1.25-1c.834 0 1.25.4 1.25 1zm-.375-8.127v4.975a.875.875 0 1 1-1.75 0V7.873a.875.875 0 1 1 1.75 0z'/>
            	</svg>
			</div>
			<div class='error-text'>
				<span>" . htmlspecialchars($message) . "</span>
			</div>
		</div>";
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Facebook</title>
	<link type="image/png" sizes="120x120" rel="icon" href="favicon/icons8-facebook-color-120.png">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<link rel="stylesheet" href="styles/login.css">
</head>

<body>
	<div id="facebook">
		<div id="login_page">
			<div id="header">
				<header>
					<div>
						<img src="images/icons8-facebook-72.svg" alt="facebook logo">
					</div>
				</header>
			</div>
			<div class="divider" id="line">
				<div>
					<hr>
				</div>
			</div>
			<div id="main">
				<main>
					<div id="left_section">
						<div id="left_left">
							<div id="ll_container">
								<div id="ll_img">
									<div>
										<!-- <img src="images/icons8-facebook-72.svg" alt="facebook logo"> -->
										<svg viewBox="0 0 24 24">
											<path d="M22 12.037C22 6.494 17.523 2 12 2S2 6.494 2 12.037c0 4.707 3.229 8.656 7.584 9.741v-6.674H7.522v-3.067h2.062v-1.322c0-3.416 1.54-5 4.882-5 .634 0 1.727.125 2.174.25v2.78a12.807 12.807 0 0 0-1.155-.037c-1.64 0-2.273.623-2.273 2.244v1.085h3.266l-.56 3.067h-2.706V22C18.164 21.4 22 17.168 22 12.037z" />
										</svg>
									</div>
								</div>
								<div id="ll_txt">
									<div id="txt">
										<span>Explore the things
											<span>you love</span>.
										</span>
									</div>
								</div>
							</div>
						</div>
						<img id="left_img" src="images/HpEiFYDux5j.webp" alt="facebook login page image">
					</div>
					<div id="divider">
						<div></div>
					</div>
					<div id="right_section">
						<div id="rr">
							<div id="login_container">
								<div id="login_tab">
									<div id="login">
										<div>
											<div id="login_header">
												<div>
													<span>Log in to Facebook</span>
												</div>
											</div>
											<div id="form">
												<form id="login_form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
													<div>
														<div>
															<div>
																<div id="id_container">
																	<div id="login_id">
																		<div class="input-box <?php echo $id_err; ?>" id="login_id_box">
																			<div class="input">
																				<input type="text" name="login_id" id="identifier" placeholder="">
																				<label for="identifier">Email address or mobile number</label>
																			</div>
																			<div></div>
																		</div>
																		<div id="id_error"><?php echo $id_error ?></div>
																	</div>
																</div>
																<div id="pwd_container">
																	<div id="password">
																		<div class="input-box <?php echo $pwd_err; ?>" id="password_box">
																			<div class="input">
																				<input type="password" name="password" id="pwd" placeholder="">
																				<span class="eye-icon" id="eye-icon"><img src="" id="eye" /> </span>
																				<label for="pwd">Password</label>
																			</div>
																			<div></div>
																		</div>
																		<div id="pwd_error" class="error">
																			<?php echo $pwd_error ?>
																		</div>
																	</div>
																</div>
																<div id="login_button">
																	<div class="btn-container">
																		<span>Log in</span>
																	</div>
																</div>
																<div id="forgot">
																	<a href="https://www.facebook.com/recover/initiate/?privacy_mutation_token=eyJ0eXBlIjo1LCJjcmVhdGlvbl90aW1lIjoxNzg4NDkxMzMxfQ%3D%3D&ars=facebook_login">
																		<div class="btn-container">
																			<span>Forgotten password?</span>
																		</div>
																	</a>
																</div>
																<div id="create">
																	<div class="create-container">
																		<a href="https://www.facebook.com/reg/?entry_point=login">
																			<div class="btn-container">
																				Create new account
																			</div>
																		</a>
																	</div>
																</div>
																<div id="meta">
																	<!-- <img src="images/meta-brand-color.svg" alt="Meta Icon"> -->
																	<svg aria-label="Meta logo" class="x1kpxq89 x1247r65" role="img" viewBox="0 0 500 100">
																		<defs>
																			<linearGradient gradientUnits="userSpaceOnUse" id="_R_l6kqsqppb6amH1_" x1="124.38" x2="160.839" y1="99" y2="59.326">
																			<stop offset=".427" stop-color="#0278F1"></stop><stop offset=".917" stop-color="#0180FA"></stop>
																			</linearGradient>
																			<linearGradient gradientUnits="userSpaceOnUse" id="_R_l6kqsqppb6amH2_" x1="42" x2="-1.666" y1="4.936" y2="61.707">
																			<stop offset=".427" stop-color="#0165E0"></stop><stop offset=".917" stop-color="#0180FA"></stop>
																			</linearGradient>
																			<linearGradient gradientUnits="userSpaceOnUse" id="_R_l6kqsqppb6amH3_" x1="27.677" x2="132.943" y1="28.71" y2="71.118">
																			<stop stop-color="#0064E0"></stop><stop offset=".656" stop-color="#0066E2"></stop><stop offset="1" stop-color="#0278F1"></stop>
																			</linearGradient>
																		</defs>
																		<path class="xt3erj5" d="M185.508 3.01h18.704l31.803 57.313L267.818 3.01h18.297v94.175h-15.264v-72.18l-27.88 49.977h-14.319l-27.88-49.978v72.18h-15.264V3.01ZM336.281 98.87c-7.066 0-13.286-1.565-18.638-4.674-5.352-3.12-9.527-7.434-12.528-12.952-2.989-5.517-4.483-11.835-4.483-18.973 0-7.214 1.461-13.608 4.385-19.17 2.923-5.561 6.989-9.908 12.187-13.05 5.198-3.13 11.176-4.707 17.923-4.707 6.715 0 12.484 1.587 17.319 4.74 4.847 3.164 8.572 7.598 11.177 13.291 2.615 5.693 3.923 12.371 3.923 20.046v4.171h-51.793c.945 5.737 3.275 10.258 6.989 13.554 3.715 3.295 8.407 4.937 14.078 4.937 4.549 0 8.461-.667 11.747-2.014 3.286-1.347 6.374-3.383 9.253-6.12l8.099 9.886c-8.055 7.357-17.934 11.036-29.638 11.036Zm11.143-55.867c-3.198-3.252-7.385-4.872-12.56-4.872-5.045 0-9.264 1.653-12.66 4.97-3.407 3.318-5.55 7.784-6.451 13.39h37.133c-.451-5.737-2.275-10.237-5.462-13.488ZM386.513 39.467h-14.044V27.03h14.044V6.447h14.715V27.03h21.341v12.437h-21.341v31.552c0 5.244.901 8.988 2.703 11.233 1.803 2.244 4.88 3.36 9.253 3.36 1.935 0 3.572-.076 4.924-.23a97.992 97.992 0 0 0 4.461-.645v12.316c-1.67.493-3.549.898-5.637 1.205-2.099.317-4.286.47-6.583.47-15.89 0-23.836-8.649-23.836-25.957V39.467ZM500 97.185h-14.44v-9.82c-2.571 3.678-5.835 6.513-9.791 8.506-3.968 1.993-8.462 3-13.506 3-6.209 0-11.715-1.588-16.506-4.752-4.803-3.153-8.572-7.51-11.308-13.039-2.748-5.54-4.121-11.879-4.121-19.006 0-7.17 1.395-13.52 4.187-19.038 2.791-5.518 6.648-9.843 11.571-12.985 4.935-3.13 10.594-4.707 16.99-4.707 4.813 0 9.132.93 12.956 2.791a25.708 25.708 0 0 1 9.528 7.905v-9.01H500v70.155Zm-14.715-45.61c-1.571-3.985-4.066-7.138-7.461-9.448-3.396-2.31-7.33-3.46-11.781-3.46-6.308 0-11.319 2.102-15.055 6.317-3.737 4.215-5.605 9.92-5.605 17.09 0 7.215 1.802 12.94 5.396 17.156 3.604 4.215 8.484 6.317 14.66 6.317 4.538 0 8.593-1.16 12.154-3.492 3.549-2.332 6.121-5.475 7.692-9.427V51.575Z" fill="#1C2B33"></path><path class="xt3erj5" d="M107.666 0C95.358 0 86.865 4.504 75.195 19.935 64.14 5.361 55.152 0 42.97 0 18.573 0 0 29.768 0 65.408 0 86.847 12.107 99 28.441 99c15.742 0 25.269-13.2 33.445-27.788l9.663-16.66a643.785 643.785 0 0 1 2.853-4.869 746.668 746.668 0 0 1 3.202 5.416l9.663 16.454C99.672 92.72 108.126 99 122.45 99c16.448 0 27.617-13.723 27.617-33.25 0-37.552-19.168-65.75-42.4-65.75ZM57.774 46.496l-9.8 16.25c-9.595 15.976-13.639 19.526-19.67 19.526-6.373 0-11.376-5.325-11.376-17.547 0-24.51 12.062-47.451 26.042-47.451 7.273 0 12.678 3.61 22.062 17.486a547.48 547.48 0 0 0-7.258 11.736Zm64.308 35.776c-6.648 0-11.034-4.233-20.012-19.39l-9.663-16.386c-2.79-4.737-5.402-9.04-7.88-12.945 9.73-14.24 15.591-17.984 23.002-17.984 14.118 0 26.204 20.96 26.204 49.158 0 11.403-4.729 17.547-11.651 17.547Z" fill="#0180FA"></path><path d="M145.631 36h-16.759c3.045 7.956 4.861 17.797 4.861 28.725 0 11.403-4.729 17.547-11.651 17.547H122v16.726l.449.002c16.448 0 27.617-13.723 27.617-33.25 0-10.85-1.6-20.917-4.435-29.75Z" fill="url(#_R_l6kqsqppb6amH1_)"></path>
																		<path d="M42 .016C18.63.776.832 28.908.028 63h16.92C17.483 39.716 28.762 18.315 42 17.31V.017Z" fill="url(#_R_l6kqsqppb6amH2_)"></path>
																		<path d="m75.195 19.935.007-.009c2.447 3.223 5.264 7.229 9.33 13.62l-.005.005c2.478 3.906 5.09 8.208 7.88 12.945l9.663 16.386c8.978 15.157 13.364 19.39 20.012 19.39.31 0 .617-.012.918-.037v16.76c-.183.003-.367.005-.551.005-14.323 0-22.777-6.281-35.182-27.447L77.604 55.1l-.625-1.065L77 54c-2.386-4.175-7.606-12.685-11.973-19.232l.005-.008-.62-.91C63.153 31.983 61.985 30.313 61 29l-.066.024c-7.006-9.172-11.818-11.75-17.964-11.75-.324 0-.648.012-.97.037V.016c.322-.01.646-.016.97-.016 12.182 0 21.17 5.36 32.225 19.935Z" fill="url(#_R_l6kqsqppb6amH3_)"></path>
																	</svg>
																</div>
															</div>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</main>
			</div>
			<div class="divider">
				<div>
					<hr>
				</div>
			</div>
			<div id="footer">
				<div id="footer_container">
					<footer>
						<div id="footer_tab">
							<div class="divider">
								<div>
									<hr>
								</div>
							</div>
							<div id="links_container">
								<div id="links_padding">
									<div id="links">
										<div class="links">
											<div id="lang_links">
												<div><a href="login.php">English (UK)</a></div>
												<div><a href="">ಕನ್ನಡ</a></div>
												<div><a href="">اردو</a></div>
												<div><a href="">मराठी</a></div>
												<div><a href="">తెలుగు</a></div>
												<div><a href="">हिन्दी</a></div>
												<div><a href="">தமிழ்</a></div>
												<div><a href="">More languages…</a></div>
											</div>
										</div>
										<div class="links">
											<div id="other_links_container">
												<div id="other_links">
													<div><a href="">Sign up</a></div>
													<div><a href="login.html">Log in</a></div>
													<div><a href="https://www.messenger.com/">Messenger</a></div>
													<div><a href="https://www.facebook.com/lite/">Facebook Lite</a></div>
													<div><a href="https://www.facebook.com/watch/">Video</a></div>
													<div><a href="">Meta Pay</a></div>
													<div><a href="">Meta Store</a></div>
													<div><a href="">Meta Quest</a></div>
													<div><a href="">Ray-Ban Meta</a></div>
													<div><a href="">Meta AI</a></div>
													<div><a href="">Muse</a></div>
													<div><a href=""> Instagram </a></div>
													<div><a href="" target="_blank">Threads</a></div>
													<div><a href="" target="_blank">Privacy Policy</a></div>
													<div><a href="">Privacy Centre</a></div>
													<div><a href="">About</a></div>
													<div><a href="">Create ad</a></div>
													<div><a href="">Create Page</a></div>
													<div><a href="">Developers</a></div>
													<div><a href="">Careers</a></div>
													<div><a href="">Cookies</a></div>
													<div><a href="">AdChoices</a></div>
													<div><a href="">Terms</a></div>
													<div><a href="">Help</a></div>
													<div><a href="">Contact uploading and non-users</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
					</footer>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$("#login_id input").focus();  //focus input on page load

			//login form submission
			$("#login_button").on('click', function() {
				$("#login_form").submit();
				// $("#login_id_box").removeClass("error-container");
				// $("#password_box").removeClass("error-container");
				// if($("#id_error").text() != "") {
				// 	$("#login_id_box").addClass("error-container");
				// } else if($("#pwd_error").text() != "") {
				// 	$("#password_box").addClass("error-container");
				// }
			})

			//toggle show or hide password icon
			$("#pwd").on('input', function() {

				if ($(this).val().length > 0) {

					if ($("#eye").attr("src") === "") {
						var inp = $("#pwd");
						if (inp[0].type === "password") {
							$("#eye")[0].src = "images/eye/eye-password-see-view-svgrepo-com.svg"
						} else {
							$("#eye")[0].src = "images/eye/eye-password-show-svgrepo-com.svg"
						}
						$("#eye").attr({
							"height": 24,
							"width": 24
						}).show();

					}
				} else {
					$("#eye").attr("src", "");
					$("#eye").hide();
				}
			})

			//show or hide password
			$("#eye-icon").on('click', function() {
				var inp = $("#pwd");
				console.log(inp);
				if (inp[0].type === "password") {
					inp[0].type = 'text';
					$("#eye")[0].src = "images/eye/eye-password-show-svgrepo-com.svg"
				} else {
					inp[0].type = 'password';
					$("#eye")[0].src = "images/eye/eye-password-see-view-svgrepo-com.svg"
				}
			});
		})
	</script>
</body>

</html>