<?php
/**
 * LTI SEO plugin
 *
 *
 * Box that appears on post types
 *
 */
?>
<div>
	<br/>
	<?php echo ltiopt('sitewide_keywords') ?>
	<br/>
	<?php print_r(get_post_type( $post->ID )); ?>
	<br/><br/>
</div>
<div>
	<div class="form-group">

	</div>
</div>