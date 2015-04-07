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
	<?php print_r($this->settings); ?>
	<br/>
	<?php print_r(get_post_type( $post->ID )); ?>
	<br/><br/>