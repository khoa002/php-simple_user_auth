<?php
if (!defined("allow_inc")) die("You can't access this file directly.");
if (!$user): ?>
<script type='text/javascript'> window.location = '/login/' </script>
<?php else:
	if (!$profile): ?>
		<script type='text/javascript'> window.location = '/profile/' </script>
	<?php else: ?>
		Home page
	<?php endif; ?>
<?php endif; ?>