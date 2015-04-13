<?php
/**
 * LTI SEO plugin
 *
 *
 * Box that appears on post types
 *
 */
?>
<div id="plseo">
	<div style="font-family: Courier, monospace;font-size:0.9em">
		<?php
		echo ltiopt( 'sitewide_keywords' );
		print_r( get_post_type( $post->ID ) );

		?>
		<br/><br/>
	</div>
	<div class="form-group">
		<div class="input-group">
			<label for="lti_seo_description">Description</label>
			<textarea name="lti_seo[description]" id="lti_seo_description"><?php echo ltiopt( 'global_keywords' ); ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<label for="lti_seo_keywords">Keywords</label>
			<input type="text" name="lti_seo[keywords]" id="lti_seo_keywords"/>
		</div>
	</div>

</div>
