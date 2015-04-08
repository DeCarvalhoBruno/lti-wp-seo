<?php
/**
 * LTI SEO plugin
 *
 * Admin View
 *
 */
?>
<!--<div id="lseo-header">-->
<!--<div id="lseo-header-img"></div>-->

<div>

<?php echo $this->plugin_dir_url ?>
<br/>
<?php print_r(ltiopt('version')); ?>
<br/>
<?php print_r($this->get_supported_post_types()); ?>
<br/><br/>

<div class="lti-seo-title">
	<h2><?php echo ltint('LTI SEO Settings'); ?></h2>
</div>

<form id="flseo" accept-charset="utf-8" method="POST" action="<?php echo admin_url( 'options-general.php?page=lti-seo-options' ); ?>">
<fieldset>
	<div class="form-group">
		<div class="input-group">
			<label for="frontpage_description">Front page description
				<input type="checkbox" name="frontpage_description" id="frontpage_description" <?php echo ltichk('frontpage_description'); ?>/>
			</label>
			<textarea name="frontpage_description_text" id="frontpage_description_text"><?php echo ltiopt('frontpage_description_text'); ?></textarea>
		</div>
		<div class="form-help-container">
			<div class="form-help">
				<p>Tick the box if you want to add your own description meta tag that will be applied to the front page.</p>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<div class="checkbox">
				<label for="canonical_urls">Canonical URLs
					<input type="checkbox" name="canonical_urls" id="canonical_urls" <?php echo ltichk('canonical_urls'); ?>/>
				</label>
			</div>
		</div>
		<div class="form-help-container">
			<div class="form-help">
				<p>Display a canonical URL for each page</p>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<label for="global_keywords">Site-wide keywords</label>
			<textarea name="global_keywords" id="global_keywords"><?php echo ltiopt('global_keywords'); ?></textarea>
		</div>
		<div class="form-help-container">
			<div class="form-help">
				<p>A list of comma delimited words (i.e "cats, dogs, elephants") that will be added before the keywords for all selected post types.</p>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<label>Generate keywords
				<input type="checkbox" name="generate_keywords" id="generate_keywords" <?php echo ltichk('generate_keywords'); ?>/>
			</label>
			<div class="checkbox-group">
				<label for="keyword_cat">Based on categories
					<input type="checkbox" name="keyword_cat" id="keyword_cat"/>
				</label>
				<label for="keyword_tag">Based on tags
					<input type="checkbox" name="keyword_tag" id="keyword_tag"/>
				</label>
			</div>
		</div>
		<div class="form-help-container">
			<div class="form-help">
				<p>Generate a list of keywords automatically.</p>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<div class="checkbox">
				<label for="meta_description">Description meta tag support
					<input type="checkbox" name="meta_description" id="meta_description"/>
				</label>
			</div>
		</div>
		<div class="form-help-container">
			<div class="form-help">
				<p>Enables a field in the LTI SEO meta box allowing you to input a custom description for posts/pages that will be used in a description meta tag.</p>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<div class="checkbox">
				<label for="meta_description">Open Graph support
					<input type="checkbox" name="open_graph" id="open_graph"/>
				</label>
			</div>
		</div>
		<div class="form-help-container">
			<div class="form-help">
				<p>Adds the following open graph tags:</p>
				<ul>
					<li>Type, set to 'Article'</li>
					<li>Site name, set to the name of the website</li>
					<li>Url, set to the canonical url for the post type</li>
					<li>Description, set to the value of the description field for the post type</li>
					<li>Locale, set to the site language</li>
					<li>Updated time, set to the last time the post type was updated</li>
					<li>Image, an upload form will appear in the LTI SEO meta box for you to specify an image from the media library.</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<label><input name="selection" type="radio" value="radio-numeric"/>Numeric</label>
		</div>
	</div>

	<?php echo wp_nonce_field( 'lti_seo_options', 'lti_seo_token' ); ?>
	</fieldset>

	<div class="form-group-submit">
		<div class="button-group-submit">
		<input id="in-seopt-submit" class="button-primary" type="submit" value="<?php echo ltint('Save Changes', 'add-meta-tags'); ?>" />
		<input id="in-seopt-reset" class="button-primary" type="submit" value="<?php echo ltint('Reset to defaults', 'add-meta-tags'); ?>" />
		</div>
	</div>
</form>

</div>