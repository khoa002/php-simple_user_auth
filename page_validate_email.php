<?php
if (!defined("allow_inc")) die("You can't access this file directly.");
if ($user !== false) die("You're already logged in.");
if (isset($_GET["param1"])):
	$token = $_GET["param1"];
?>
<script type="text/javascript">
	$(function(){
		$.post("/action_validate_email.php", { token: "<?php echo $token; ?>" }, function(d){
			if (d !== "success") {
				if (d === "invalid_token") {
					$("div#msg").text("Your validation token is not found or is no longer valid.");
					return;
				}
				alert("An error occurred (" + d + "), please contact the site admin.");
				return;
			}
			$("div#msg").html("Thanks! Your email has been validated, you can now <a href='/login/'>log into</a> the site.");
		});
	});
</script>
<?php
else:
?>
<script type="text/javascript">
	$(function(){
		// Placeholder for when user wants to manually resend activation email, etc... (not required for assessment)
		$("div#msg").text("Please use the link in your email to validate your account.");
	});
</script>
<?php
endif;
?>
<div id="msg"></div>