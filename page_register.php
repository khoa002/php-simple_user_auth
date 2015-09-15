<?php
if (!defined("allow_inc")) die("You can't access this file directly.");
if ($user !== false): ?>
You're already logged in.
<?php else: ?>
<script type="text/javascript">
$(function(){
	set_page_title("New user registration");
	load_captcha();
	$("a#refresh_captcha").click(load_captcha);
	var width = 0;
	$("#user_registration label").each(function(){
		if ($(this).width() > width) { width = $(this).width(); }
	});
	if (width > 0) { $("#user_registration label").width(width); }
	
	function load_captcha() {
		$("span#captcha").load("/show_captcha.php");
		$("input#captcha_code").val("");
	}
	
	$("form#user_registration").submit(function(e){
		e.preventDefault();
		$("form#user_registration button[type='submit']").prop("disabled", true);
		$("p#submitting").css("display", "inline");
		/* clean the inputs */
		$("form#user_registration input").each(function(){
			$(this).val($.trim($(this).val())); /* leading/trailing spaces */
			$(this).val($(this).val().replace('"','')); /* double quotes */
			$(this).val($(this).val().replace("'","")); /* single quotes */
		});
		/* check for blank inputs */
		var eMsgs = new Array();
		$("form#user_registration input").each(function(){
			if (!$(this).val()) { eMsgs.push($(this).attr("id")); }
		});
		var eMsgStr = "";
		$.each(eMsgs, function(key, value){
			if (eMsgStr == "") { eMsgStr += "Please provide the following: \n"; }
			eMsgStr += "- " + $("form#user_registration label[for='" + value + "']").text().replace(": ","") + "\n";
		});
		if (eMsgStr !== "") {
			load_captcha();
			alert(eMsgStr);
			$("form#user_registration button[type='submit']").prop("disabled", false);
			$("p#submitting").css("display", "none");
			return;
		}
		/* check if the passwords match */
		if ($("form#user_registration input#password1").val() !== $("form#user_registration input#password2").val()) {
			load_captcha();
			alert("Your new passwords do not match.");
			$("form#user_registration button[type='submit']").prop("disabled", false);
			$("p#submitting").css("display", "none");
			return;
		}
		$.post("/action_register.php", $(this).serialize(), function (d){
			$("form#user_registration button[type='submit']").prop("disabled", false);
			$("p#submitting").css("display", "none");
			if (d !== "success") {
				load_captcha();
				if (d === "incorrect_captcha") {
					alert("CAPTCHA string not correct.");
					return;
				}
				if (d === "username_already_exists") {
					alert("This username already exists.");
					return;
				}
				if (d === "email_already_exists") {
					alert("This email is already registered.");
					return;
				}
				if (d === "invalid_email_address") {
					alert("Please enter a valid email address.");
					$("input#email").val("").focus();
					return;
				}
				if (d === "email_error") {
					alert("Unable to send validation email, please contact the site admin.");
					return;
				}
				alert("An error occurred (" + d + "), please contact the site admin.");
				return;
			}
			/* success */
			alert("Account created, an email has been sent to your email address to validate your account. Please be sure to check your spam folder in case it was sent there.");
			window.location = "/";
		});
	});
});
</script>
<form id="user_registration">
	<p class="section_title">New User Registration</p>
	<p>Please enter the following <strong>required</strong> information:</p>
	<p><label for="username">Username: </label><input type="text" id="username" name="username" /></p>
	<p><label for="password1">New Password: </label><input type="password" id="password1" name="password1" /></p>
	<p><label for="password2">Verify Password: </label><input type="password" id="password2" name="password2" /></p>
	<p><label for="email">Email: </label><input type="text" id="email" name="email" /></p>
	<p>&nbsp;</p>
	<p><label for="captcha_code">CAPTCHA: </label><input type="text" id="captcha_code" name="captcha_code" /></p>
	<p><span id="captcha" style="width: 215px; height: 80px;"></span><br/><a id="refresh_captcha">[ Get another code ]</a></p>
	<p>&nbsp;</p>
	<p><button type="submit">Submit</button></p>
	<p id="submitting" style="display: none;">Please wait...</p>
</form>
<?php endif; ?>