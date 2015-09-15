<?php
if (!defined("allow_inc")) die("You can't access this file directly.");
if (!$user): ?>
<script type='text/javascript'> window.location = '/login/' </script>
<?php else:
	if (!$profile): ?>
		<script type='text/javascript'> window.location = '/profile/' </script>
	<?php else: ?>
		<script type="text/javascript">
		$(function(){
			set_page_title("Change my password");
			load_captcha();
			$("a#refresh_captcha").click(load_captcha);
			function load_captcha() {
				$("span#captcha").load("/show_captcha.php");
				$("input#captcha_code").val("");
			}
			var width = 0;
			$("#change_pw label").each(function(){
				var newWidth = $(this).width();
				if (newWidth > width) { width = newWidth; }
			});
			if (width > 0) { $("#change_pw label").width(width); }
			$("form#change_pw").submit(function(e){
				e.preventDefault();
				$("form#change_pw button[type='submit']").prop("disabled", true);
				$("p#submitting").css("display", "inline");
				/* clean the inputs */
				$("form#change_pw input").each(function(){
					$(this).val($.trim($(this).val())); /* leading/trailing spaces */
					$(this).val($(this).val().replace('"','')); /* double quotes */
					$(this).val($(this).val().replace("'","")); /* single quotes */
				});
				/* check for blank inputs */
				var eMsgs = new Array();
				$("form#change_pw input").each(function(){
					if (!$(this).val()) { eMsgs.push($(this).attr("id")); }
				});
				var eMsgStr = "";
				$.each(eMsgs, function(key, value){
					if (eMsgStr == "") { eMsgStr += "Please provide the following: \n"; }
					eMsgStr += "- " + $("form#change_pw label[for='" + value + "']").text().replace(": ","") + "\n";
				});
				if (eMsgStr !== "") {
					load_captcha();
					alert(eMsgStr);
					$("form#change_pw button[type='submit']").prop("disabled", false);
					$("p#submitting").css("display", "none");
					return;
				}
				/* check if the passwords match */
				if ($("form#change_pw input#password1").val() !== $("form#change_pw input#password2").val()) {
					load_captcha();
					alert("Your new passwords do not match.");
					$("form#change_pw button[type='submit']").prop("disabled", false);
					$("p#submitting").css("display", "none");
					return;
				}
				
				$.post("/action_change_pw.php", $(this).serialize(), function (d){
					$("form#change_pw button[type='submit']").prop("disabled", false);
					$("p#submitting").css("display", "none");
					if (d !== "success") {
						load_captcha();
						$("input#password").val("");
						if (d === "incorrect_captcha") {
							alert("CAPTCHA string not correct.");
							return;
						}
						if (d === "incorrect_password") {
							alert("Your password is incorrect, please try again.");
							return;
						}
						alert("An error occurred (" + d + "), please contact the site admin.");
						return;
					};
					alert("Your password has been changed, you will be now logged out.");
					window.location = "/logout/";
				});
			});
		});
		</script>
		<form id="change_pw">
			<p><label for="password">Current password: </label><input type="password" id="password" name="password" /></p>
			<p>&nbsp;</p>
			<p><label for="password1">New password: </label><input type="password" id="password1" name="password1" /></p>
			<p><label for="password2">Verify password: </label><input type="password" id="password2" name="password2" /></p>
			<p><label for="captcha_code">CAPTCHA: </label><input type="text" id="captcha_code" name="captcha_code" /></p>
			<p><span id="captcha" style="width: 215px; height: 80px;"></span><br/><a id="refresh_captcha">[ Get another code ]</a></p>
			<p>&nbsp;</p>
			<p><button type="submit">Change my password</button></p>
			<p id="submitting" style="display: none;">Please wait...</p>
		</form>
	<?php endif; ?>
<?php endif; ?>