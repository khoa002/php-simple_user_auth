<?php
if (!defined("allow_inc")) die("You can't access this file directly.");
if (!$user): ?>
<script type='text/javascript'> window.location = '/login/' </script>
<?php else:
	if (!$profile): ?>
		<script type='text/javascript'> window.location = '/profile/' </script>
	<?php else: ?>
		<script type="text/javascript" src="/scr/uploadify/jquery.uploadify.min.js"></script>
		<script type="text/javascript">
		$(function(){
			set_page_title("Change my picture");
			
			$(function() {
				$('#file_upload').uploadify({
					'swf'      : '/scr/uploadify/uploadify.swf',
					'uploader' : '/action_change_pic.php',
					'multi'    : false,
					'formData' : { 'session' : '<?php echo session_id();?>' },
					'onUploadSuccess' : function(f, d, r) {
						if (d !== "success") {
							alert(d);
							return;
						}
						window.location = "/profile/"
					}
				});
			});
		});
		</script>
		<form id="change_pic" enctype="multipart/form-data">
		<p><img src="<?php echo (empty($user["profile_pic"]) ? "/img/profiles/default.png" : $user["profile_pic"]); ?>" style="width: 128px; height: 128px; border: 1px solid #360;" /></p>
		<p>&nbsp;</p>
		<p><input type="file_upload" name="file_upload" id="file_upload" /></p>
		</form>
	<?php endif; ?>
<?php endif; ?>