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
		echo ltiopt( 'keywords' );
		print_r( get_post_type( $post->ID ) );

		?>
		<br/><br/>
	</div>
	<div class="form-group">
		<div class="input-group">
			<label for="lti_seo_description">Description</label>
			<textarea name="lti_seo[description]" id="lti_seo_description" placeholder="<?php echo ltiopt( 'description_suggestion' ); ?>"><?php echo ltiopt( 'description' ); ?></textarea>
			<span id="wlti_seo_description" class="char-counter">Character count:&nbsp;<span id="clti_seo_description"></span></span>
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<label for="lti_seo_keywords">Keywords</label>
			<input type="text" name="lti_seo[keywords]" id="lti_seo_keywords" value="<?php echo ltiopt( 'keywords' ); ?>" placeholder="<?php echo ltiopt( 'keywords_suggestion' ); ?>"/>
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<label for="meta_description">Social media image</label>
			<div class="input-group file-selector">
				<input id="lti_social_img" type="text" name="lti_seo[social_img_url]"
				       value="<?php echo ltiopt( 'social_img_url' ); ?>"
				       readonly="readonly"/>
				<input id="lti_social_img_button" class="button-primary upload_image_button"
				       type="button"
				       value="<?php echo ltint( 'Choose image' ); ?>"/>
				<input id="lti_social_img_id" type="hidden"
				       name="lti_seo[social_img_id]"
				       value="<?php echo ltiopt( 'social_img_id' ); ?>"/>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="input-group">
			<label>Robots meta tag</label>

			<div class="checkbox-group">
				<label for="post_robots_noindex">NOINDEX
					<input type="checkbox" name="lti_seo[post_robots_noindex]"
					       id="post_robots_noindex" <?php echo ltichk( 'post_robots_noindex' ); ?>/>
				</label>
				<label for="post_robots_nofollow">NOFOLLOW
					<input type="checkbox" name="lti_seo[post_robots_nofollow]"
					       id="post_robots_nofollow" <?php echo ltichk( 'post_robots_nofollow' ); ?>/>
				</label>
				<label for="post_robots_noodp">NOODP
					<input type="checkbox" name="lti_seo[post_robots_noodp]"
					       id="post_robots_noodp" <?php echo ltichk( 'post_robots_noodp' ); ?>/>
				</label>
				<label for="post_robots_noydir">NOYDIR
					<input type="checkbox" name="lti_seo[post_robots_noydir]"
					       id="post_robots_noydir" <?php echo ltichk( 'post_robots_noydir' ); ?>/>
				</label>
				<label for="post_robots_noarchive">NOARCHIVE
					<input type="checkbox" name="lti_seo[post_robots_noarchive]"
					       id="post_robots_noarchive" <?php echo ltichk( 'post_robots_noarchive' ); ?>/>
				</label>
				<label for="post_robots_nosnippet">NOSNIPPET
					<input type="checkbox" name="lti_seo[post_robots_nosnippet]"
					       id="post_robots_nosnippet" <?php echo ltichk( 'post_robots_nosnippet' ); ?>/>
				</label>
			</div>
		</div>
	</div>

</div>
