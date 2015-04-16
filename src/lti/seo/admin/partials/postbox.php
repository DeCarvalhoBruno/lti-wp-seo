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

		?>
		<br/><br/>
	</div>
	<?php if ( $this->settings->get( 'description_support' ) === true ) { ?>
		<div class="form-group">
			<div class="input-group">
				<label for="lti_seo_description">Description</label>
				<textarea name="lti_seo[description]" id="lti_seo_description"
				          placeholder="<?php echo ltiopt( 'description_suggestion' ); ?>"><?php echo ltiopt( 'description' ); ?></textarea>
				<span id="wlti_seo_description" class="char-counter">Character count:&nbsp;<span
						id="clti_seo_description"></span></span>
			</div>
		</div>
	<?php } ?>
	<?php if ( $this->settings->get( 'keyword_support' ) === true ) { ?>
		<div class="form-group">
			<div class="input-group">
				<label for="lti_seo_keywords">Keywords</label>
				<input type="text" name="lti_seo[keywords]" id="lti_seo_keywords"
				       value="<?php echo ltiopt( 'keywords' ); ?>"/>
				<?php if ( !is_null(ltiopt( 'keywords_suggestion' )) ) { ?>
				<span id="keywords_suggestion_box">Suggestion: <span id="lti_seo_keywords_suggestion"><?php echo ltiopt( 'keywords_suggestion' ); ?></span><a
					onclick="document.getElementById('lti_seo_keywords').setAttribute('value',document.getElementById('lti_seo_keywords_suggestion').textContent);">
					(Copy)</a></span>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
	<?php if ( $this->settings->get( 'open_graph_support' ) === true || $this->settings->get( 'twitter_cards_support' ) === true ) { ?>
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
	<?php } ?>

	<?php if ( $this->settings->get( 'robot_support' ) === true ) { ?>
		<div class="form-group">
			<div class="input-group">
				<label>Robots meta tag</label>

				<div class="checkbox-group">
					<label for="post_robot_noindex">NOINDEX
						<input type="checkbox" name="lti_seo[post_robot_noindex]"
						       id="post_robot_noindex" <?php echo ltichk( 'post_robot_noindex' ); ?>/>
					</label>
					<label for="post_robot_nofollow">NOFOLLOW
						<input type="checkbox" name="lti_seo[post_robot_nofollow]"
						       id="post_robot_nofollow" <?php echo ltichk( 'post_robot_nofollow' ); ?>/>
					</label>
					<label for="post_robot_noodp">NOODP
						<input type="checkbox" name="lti_seo[post_robot_noodp]"
						       id="post_robot_noodp" <?php echo ltichk( 'post_robot_noodp' ); ?>/>
					</label>
					<label for="post_robot_noydir">NOYDIR
						<input type="checkbox" name="lti_seo[post_robot_noydir]"
						       id="post_robot_noydir" <?php echo ltichk( 'post_robot_noydir' ); ?>/>
					</label>
					<label for="post_robot_noarchive">NOARCHIVE
						<input type="checkbox" name="lti_seo[post_robot_noarchive]"
						       id="post_robot_noarchive" <?php echo ltichk( 'post_robot_noarchive' ); ?>/>
					</label>
					<label for="post_robot_nosnippet">NOSNIPPET
						<input type="checkbox" name="lti_seo[post_robot_nosnippet]"
						       id="post_robot_nosnippet" <?php echo ltichk( 'post_robot_nosnippet' ); ?>/>
					</label>
				</div>
			</div>
		</div>
	<?php } ?>

</div>
