<?php
if (!defined("allow_inc")) die("You can't access this file directly.");
if (!$user): ?>
	<script type='text/javascript'> window.location = '/login/'; </script>
<?php else:
	if (!$profile):
		echo "Please take a few moments to create your profile.";
	else:
		echo "You're able to edit your profile below.";
	endif;
	?>
	<script type="text/javascript">
	$(function(){
		set_page_title("User Profile");
		var width = 0;
		$("#user_profile label").each(function(){
			if ($(this).width() > width) { width = $(this).width(); }
		});
		if (width > 0) { $("#user_profile label").width(width); }
		
		$("form#user_profile").submit(function(e){
			e.preventDefault();
			$("form#user_profile button[type='submit']").prop("disabled", true);
			$("p#submitting").css("display", "inline");
			/* clean the inputs */
			$("form#user_profile input").each(function(){
				$(this).val($.trim($(this).val())); /* leading/trailing spaces */
				$(this).val($(this).val().replace('"','')); /* double quotes */
				$(this).val($(this).val().replace("'","")); /* single quotes */
			});
			/* check for blank inputs */
			var eMsgs = new Array();
			$("form#user_profile input").each(function(){
				if (!$(this).val()) { eMsgs.push($(this).attr("id")); }
			});
			var eMsgStr = "";
			$.each(eMsgs, function(key, value){
				if (eMsgStr == "") { eMsgStr += "Please provide the following: \n"; }
				eMsgStr += "- " + $("form#user_profile label[for='" + value + "']").text().replace(": ","") + "\n";
			});
			if (eMsgStr !== "") {
				alert(eMsgStr);
				$("form#user_profile button[type='submit']").prop("disabled", false);
				$("p#submitting").css("display", "none");
				return;
			}
			$.post("/action_update_profile.php", $(this).serialize(), function (d){
				$("form#user_profile button[type='submit']").prop("disabled", false);
				$("p#submitting").css("display", "none");
				if (d !== "success") {
					if (d === "invalid_email_address") {
						alert("Please enter a valid email address.");
						$("input#email").val("").focus();
						return;
					}
					alert("An error occurred (" + d + "), please contact the site admin.");
					return;
				}
				/* success */
				alert("Profile updated!");
				window.location = "/profile/";
			});
		});
	});
	</script>
	<form id="user_profile">
		<p><img src="<?php echo (empty($user["profile_pic"]) ? "/img/profiles/default.png" : $user["profile_pic"]); ?>" style="width: 128px; height: 128px; border: 1px solid #360;" /></p>
		<p><a href="/change_pic/">Change my picture...</a></p>
		<p><a href="/change_pw/">Change my password...</a></p>
		<p><label for="email">Email: </label><input type="text" id="email" name="email" value="<?php echo (!empty($user["email"]) ? $user["email"] : ""); ?>" /></p>
		<p>&nbsp;</p>
		<p><label for="first_name">First Name: </label><input type="text" id="first_name" name="first_name"<?php echo (!empty($user["first_name"]) ? " value=\"{$user["first_name"]}\"" : ""); ?> /></p>
		<p><label for="last_name">Last Name: </label><input type="text" id="last_name" name="last_name"<?php echo (!empty($user["last_name"]) ? " value=\"{$user["last_name"]}\"" : ""); ?> /></p>
		<p><label for="address">Address: </label><input type="text" id="address" name="address"<?php echo (!empty($user["address"]) ? " value=\"{$user["address"]}\"" : ""); ?> /></p>
		<p><label for="city">City: </label><input type="text" id="city" name="city"<?php echo (!empty($user["city"]) ? " value=\"{$user["city"]}\"" : ""); ?> /></p>
		<p><label for="state">State: </label>
			<select id="state" name="state"><?php
				foreach ($us_states as $k => $v) {
					echo "\t\t\t<option value=\"{$k}\"" . (!empty($user["state"]) ? ($user["state"] == $k ? " selected" : "") : "") . ">{$v}</option>\n";
				}
				?>
			</select>
		</p>
		<p><label for="zip_code">Zip Code: </label><input type="text" id="zip_code" name="zip_code"<?php echo (!empty($user["zip_code"]) ? " value=\"{$user["zip_code"]}\"" : ""); ?> /></p>
		<p>&nbsp;</p>
		<p><button type="submit">Submit</button></p>
		<p id="submitting" style="display: none;">Please wait...</p>
	</form>
<?php if ($user["ulvl"] == 1):
			
		endif;
	endif; ?>