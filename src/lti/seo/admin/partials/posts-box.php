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
			<label for="lti_seo_keywords">Title</label>
			<input type="text" name="lti_seo[title]" id="lti_seo_keywords" value="<?php echo ltiopt( 'title' ); ?>"/>
		</div>
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
			<label>Robots meta tag</label>

			<div class="checkbox-group">
				<label for="robots_noindex">NOINDEX
					<input type="checkbox" name="lti_seo[robots_noindex]"
					       id="keyword_cat_based" <?php echo ltichk( 'robots_noindex' ); ?>/>
				</label>
				<label for="robots_nofollow">NOFOLLOW
					<input type="checkbox" name="lti_seo[robots_nofollow]"
					       id="keyword_tag_based" <?php echo ltichk( 'robots_nofollow' ); ?>/>
				</label>
				<label for="robots_noodp">NOODP
					<input type="checkbox" name="lti_seo[robots_noodp]"
					       id="keyword_tag_based" <?php echo ltichk( 'robots_noodp' ); ?>/>
				</label>
				<label for="robots_noydir">NOYDIR
					<input type="checkbox" name="lti_seo[robots_noydir]"
					       id="keyword_tag_based" <?php echo ltichk( 'robots_noydir' ); ?>/>
				</label>
				<label for="robots_noarchive">NOARCHIVE
					<input type="checkbox" name="lti_seo[robots_noarchive]"
					       id="robots_noarchive" <?php echo ltichk( 'robots_noarchive' ); ?>/>
				</label>
				<label for="robots_nosnippet">NOSNIPPET
					<input type="checkbox" name="lti_seo[robots_nosnippet]"
					       id="robots_nosnippet" <?php echo ltichk( 'robots_nosnippet' ); ?>/>
				</label>
			</div>
		</div>
	</div>

</div>
