<?php
if (!defined("allow_inc")) die("You can't access this file directly.");
if ($user === false): ?>
You're not logged in.
<?php else: ?>
<script type="text/javascript">
	$.post("/action_logout.php", function(d){
		if (d !== "success") {
			alert(d);
			return;
		}
		window.location = "/"
	});
</script>
Logging you out...
<?php endif; ?>