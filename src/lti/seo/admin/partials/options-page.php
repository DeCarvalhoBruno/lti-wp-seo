<?php
/**
 * LTI SEO plugin
 *
 * Admin View
 *
 */
?>
<div>
<?php print_r($this->settings); ?>
<br/>
<?php print_r($this->get_supported_post_types()); ?>
<br/><br/>

<h2><?php echo ltint('LTI SEO Settings'); ?></h2>
</div>

<div>
<form accept-charset="utf-8" method="POST" action="<?php echo admin_url( 'options-general.php?page=lti-seo-options' ); ?>" name="form-admin-seo-options">
<fieldset>
	<div class="input-group">
		<label for="sitewide_keywords">Site-wide keywords</label>
		<input type="text" name="sitewide_keywords" id="sitewide_keywords" value="<?php echo $this->settings->sitewide_keywords->value ?>"/>
	</div>
	<div class="input-group">
		<label for="social-twitter">Twitter information
		<input type="checkbox" name="social-twitter" id="social-twitter"/></label>
	</div>
	<div class="input-group">
		<label><input name="selection" type="radio" value="radio-numeric"/>Numeric</label>
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