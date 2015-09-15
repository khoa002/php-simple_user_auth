<?php
if (!defined("allow_inc")) die("You can't access this file directly.");
if ($user !== false): ?>
You're already logged in.
<?php else: ?>
<script type="text/javascript">
$(function(){
	set_page_title("Please log in!");
	load_captcha();
	$("a#refresh_captcha").click(load_captcha);
	function load_captcha() {
		$("span#captcha").load("/show_captcha.php");
		$("input#captcha_code").val("");
	}
	var width = 0;
	$("#user_login label").each(function(){
		var newWidth = $(this).width();
		if (newWidth > width) { width = newWidth; }
	});
	if (width > 0) { $("#user_login label").width(width); }
	$("form#user_login").submit(function(e){
		e.preventDefault();
		$("form#user_login button[type='submit']").prop("disabled", true);
		$("p#submitting").css("display", "inline");
		/* clean the inputs */
		$("form#user_login input").each(function(){
			$(this).val($.trim($(this).val())); /* leading/trailing spaces */
			$(this).val($(this).val().replace('"','')); /* double quotes */
			$(this).val($(this).val().replace("'","")); /* single quotes */
		});
		/* check for blank inputs */
		var eMsgs = new Array();
		$("form#user_login input").each(function(){
			if (!$(this).val()) { eMsgs.push($(this).attr("id")); }
		});
		var eMsgStr = "";
		$.each(eMsgs, function(key, value){
			if (eMsgStr == "") { eMsgStr += "Please provide the following: \n"; }
			eMsgStr += "- " + $("form#user_login label[for='" + value + "']").text().replace(": ","") + "\n";
		});
		if (eMsgStr !== "") {
			load_captcha();
			alert(eMsgStr);
			$("form#user_login button[type='submit']").prop("disabled", false);
			$("p#submitting").css("display", "none");
			return;
		}
		
		$.post("/action_login.php", $(this).serialize(), function (d){
			$("form#user_login button[type='submit']").prop("disabled", false);
			$("p#submitting").css("display", "none");
			if (d !== "success") {
				load_captcha();
				$("input#password").val("");
				if (d === "incorrect_captcha") {
					alert("CAPTCHA string not correct.");
					return;
				}
				if (d === "username_not_found") {
					alert("Username not found in system, please create a new account if you do not have one.");
					return;
				}
				if (d === "incorrect_password") {
					alert("Your password is incorrect, please try again.");
					return;
				}
				if (d === "user_not_active") {
					alert("Your account is not active, please check your email for a validation. Be sure to check your spam folder as well.");
					return;
				}
				alert("An error occurred (" + d + "), please contact the site admin.");
				return;
			};
			window.location = "/profile/";
		});
	});
});
</script>
<form id="user_login">
	<p class="section_title">Please log in using your username and password:</p>
	<p><label for="username">Username: </label><input type="text" id="username" name="username" /></p>
	<p><label for="password">Password: </label><input type="password" id="password" name="password" /></p>
	<p>&nbsp;</p>
	<p><label for="captcha_code">CAPTCHA: </label><input type="text" id="captcha_code" name="captcha_code" /></p>
	<p><span id="captcha" style="width: 215px; height: 80px;"></span><br/><a id="refresh_captcha">[ Get another code ]</a></p>
	<p>&nbsp;</p>
	<p><button type="submit">Log me in!</button></p>
	<p id="submitting" style="display: none;">Please wait...</p>
</form>
<p>Don't have an account? <a href="/register/">Create one...</a></p>
<?php endif; ?>