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

<div id="lti_seo_wrapper">

	<div class="lti-seo-title">
		<h2><?php echo ltint( 'LTI SEO Settings' ); ?></h2>
	</div>
	<div role="tabpanel">

		<ul id="lti_seo_tabs" class="nav nav-tabs" role="tablist">
			<li role="presentation">
				<a href="#tab_general" aria-controls="tab_general" role="tab" data-toggle="tab">General</a>
			</li>
			<li role="presentation">
				<a href="#tab_frontpage" aria-controls="tab_frontpage" role="tab" data-toggle="tab">Front page</a>
			</li>
			<li role="presentation">
				<a href="#tab_social" aria-controls="tab_social" role="tab" data-toggle="tab">Social</a>
			</li>
		</ul>

		<form id="flseo" accept-charset="utf-8" method="POST"
		      action="<?php echo admin_url( 'options-general.php?page=lti-seo-options' ); ?>">
			<?php echo wp_nonce_field( 'lti_seo_options', 'lti_seo_token' ); ?>
			<div class="tab-content">
				<?php
				/***********************************************************************************************
				 *                                  GENERAL TAB
				 ***********************************************************************************************/
				?>
				<div role="tabpanel" class="tab-pane active" id="tab_general">
					<div class="form-group">
						<div class="input-group">
							<div class="checkbox">
								<label for="canonical_urls">Canonical URLs
									<input type="checkbox" name="canonical_urls"
									       id="canonical_urls" <?php echo ltichk( 'canonical_urls' ); ?>/>
								</label>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p>Lets Wordpress generate a canonical URL for single pages, and will try to generate
									one for other types of pages.</p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label>Keywords tag
								<input type="checkbox" name="keyword_support" data-toggle="seo-options"
								       data-target="#keyword_chk_group"
								       id="keyword_support" <?php echo ltichk( 'keyword_support' ); ?>/>
							</label>

							<div id="keyword_chk_group">
								<div class="checkbox-group">
									<label for="keyword_cat_based">Based on categories
										<input type="checkbox" name="keyword_cat_based"
										       id="keyword_cat_based" <?php echo ltichk( 'keyword_cat_based' ); ?>/>
									</label>
									<label for="keyword_tag_based">Based on tags
										<input type="checkbox" name="keyword_tag_based"
										       id="keyword_tag_based" <?php echo ltichk( 'keyword_tag_based' ); ?>/>
									</label>
								</div>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p>A list of comma delimited words (i.e "cats, dogs, elephants") that will be added
									before the keywords for all selected post types.</p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label>Robots tag
								<input type="checkbox" name="robots_support" data-toggle="seo-options" data-target="#robots_chk_group"
								       id="robots_support" <?php echo ltichk( 'robots_support' ); ?>/>
							</label>

							<div id="robots_chk_group">
								<div class="input-group">
									<label>Attributes to apply</label>
									<table class="table">
										<thead>
										<tr>
											<th colspan="1"></th>
											<th colspan="2">Scope</th>
										</tr>
										<tr>
											<th>Type</th>
											<th>Global</th>
											<th>Posts and media</th>
										</tr>
										</thead>
										<tbody>
										<tr>
											<td>NOINDEX</td>
											<td><input type="checkbox" name="robots_noindex"
											           id="robots_noindex" <?php echo ltichk( 'robots_noindex' ); ?>/>
											</td>
											<td><input type="checkbox" name="post_robots_noindex"
											           id="post_robots_noindex" <?php echo ltichk( 'post_robots_noindex' ); ?>/>
											</td>
										</tr>
										<tr>
											<td>NOFOLLOW</td>
											<td><input type="checkbox" name="robots_nofollow"
											           id="robots_nofollow" <?php echo ltichk( 'robots_nofollow' ); ?>/>
											</td>
											<td><input type="checkbox" name="post_robots_nofollow"
											           id="post_robots_nofollow" <?php echo ltichk( 'post_robots_nofollow' ); ?>/>
											</td>
										</tr>
										<tr>
											<td>NOODP</td>
											<td><input type="checkbox" name="robots_noodp"
											           id="robots_noodp" <?php echo ltichk( 'robots_noodp' ); ?>/></td>
											<td><input type="checkbox" name="post_robots_noodp"
											           id="post_robots_noodp" <?php echo ltichk( 'post_robots_noodp' ); ?>/>
											</td>
										</tr>
										<tr>
											<td>NOYDIR</td>
											<td><input type="checkbox" name="robots_noydir"
											           id="robots_noydir" <?php echo ltichk( 'robots_noydir' ); ?>/>
											</td>
											<td><input type="checkbox" name="post_robots_noydir"
											           id="post_robots_noydir" <?php echo ltichk( 'post_robots_noydir' ); ?>/>
											</td>
										</tr>
										<tr>
											<td>NOARCHIVE</td>
											<td><input type="checkbox" name="robots_noarchive"
											           id="robots_noarchive" <?php echo ltichk( 'robots_noarchive' ); ?>/>
											</td>
											<td><input type="checkbox" name="post_robots_noarchive"
											           id="post_robots_noarchive" <?php echo ltichk( 'post_robots_noarchive' ); ?>/>
											</td>
										</tr>
										<tr>
											<td>NOSNIPPET</td>
											<td><input type="checkbox" name="robots_nosnippet"
											           id="robots_nosnippet" <?php echo ltichk( 'robots_nosnippet' ); ?>/>
											</td>
											<td><input type="checkbox" name="post_robots_nosnippet"
											           id="post_robots_nosnippet" <?php echo ltichk( 'post_robots_nosnippet' ); ?>/>
											</td>
										</tr>
										</tbody>
									</table>
								</div>

								<div class="input-group">
									<label>Global attributes applied to:</label>

									<div class="checkbox-group">
										<label for="robots_date_based">Date archives
											<input type="checkbox" name="robots_date_based"
											       id="robots_date_based" <?php echo ltichk( 'robots_date_based' ); ?>/>
										</label>
										<label for="robots_cat_based">Categories
											<input type="checkbox" name="robots_cat_based"
											       id="robots_cat_based" <?php echo ltichk( 'robots_cat_based' ); ?>/>
										</label>
										<label for="robots_tag_based">Tags
											<input type="checkbox" name="robots_tag_based"
											       id="robots_tag_based" <?php echo ltichk( 'robots_tag_based' ); ?>/>
										</label>
										<label for="robots_author_based">Author pages
											<input type="checkbox" name="robots_author_based"
											       id="robots_author_based" <?php echo ltichk( 'robots_author_based' ); ?>/>
										</label>
										<label for="robots_search_based">Searches
											<input type="checkbox" name="robots_search_based"
											       id="robots_search_based" <?php echo ltichk( 'robots_search_based' ); ?>/>
										</label>
										<label for="robots_notfound_based">"Not found" page
											<input type="checkbox" name="robots_notfound_based"
											       id="robots_notfound_based" <?php echo ltichk( 'robots_notfound_based' ); ?>/>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p>A list of comma delimited words (i.e "cats, dogs, elephants") that will be added
									before the keywords for all selected post types.</p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<div class="checkbox">
								<label for="meta_description">Description meta tag support
									<input type="checkbox" name="meta_description"
									       id="meta_description" <?php echo ltichk( 'meta_description' ); ?>/>
								</label>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p>Enables a field in the LTI SEO meta box allowing you to input a custom description
									for posts/pages that will be used in a description meta tag.</p>
							</div>
						</div>
					</div>
				</div>
				<?php
				/***********************************************************************************************
				 *                                      FRONTPAGE TAB
				 ***********************************************************************************************/
				?>
				<div role="tabpanel" class="tab-pane" id="tab_frontpage">
					<div class="form-group">
						<div class="input-group">
							<label for="frontpage_description">Description
								<input type="checkbox" name="frontpage_description" data-toggle="seo-options" data-target="#description_group"
								       id="frontpage_description" <?php echo ltichk( 'frontpage_description' ); ?>/>
							</label>
							<div id="description_group">
							<textarea name="frontpage_description_text"
							          id="frontpage_description_text"><?php echo ltiopt( 'frontpage_description_text' ); ?></textarea>
							<span id="wfrontpage_description_text" class="char-counter">Character count:&nbsp;<span
									id="cfrontpage_description_text"></span></span>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p>Adds your own description meta tag that will be applied to the front page.</p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label for="frontpage_keyword">Keywords
								<input type="checkbox" name="frontpage_keyword" data-toggle="seo-options" data-target="#keyword_group"
								       id="frontpage_keyword" <?php echo ltichk( 'frontpage_keyword' ); ?>/>
							</label>
							<div id="keyword_group">
							<textarea name="frontpage_keyword_text"
							          id="frontpage_keyword_text"><?php echo ltiopt( 'frontpage_keyword_text' ); ?></textarea>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p>Adds your own keyword meta tag that will be applied to the front page.</p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>JSON-LD</label>

						<div class="form-group">
							<div class="input-group">
								<label for="jsonld_org_info">Person/Organization
									<input type="checkbox" name="jsonld_org_info" data-toggle="seo-options" data-target="#jsonld_org_group"
									       id="jsonld_org_info" <?php echo ltichk( 'jsonld_org_info' ); ?>/>
								</label>

								<div id="jsonld_org_group">
									<div class="input-group">
										<label>
											<input name="jsonld_entity_type"
											       type="radio" <?php echo ltirad( 'jsonld_entity_type', 'person' ); ?>
											       value="person"/>Person
										</label>
										<label><input name="jsonld_entity_type"
										              type="radio" <?php echo ltirad( 'jsonld_entity_type',
												'organization' ); ?> value="organization"/>Organization
										</label>
									</div>
									<div class="input-group">
										<label for="jsonld_type_name">Name
											<input type="text" name="jsonld_type_name" id="jsonld_type_name"
											       value="<?php echo ltiopt( 'jsonld_type_name' ); ?>"/>
										</label>
									</div>
									<div class="input-group file-selector">
										<label for="jsonld_img">Logo</label>
										<input id="jsonld_img" class="upload_image" type="text" readonly="readonly"
										       name="jsonld_type_logo_url"
										       value="<?php echo ltiopt( 'jsonld_type_logo_url' ); ?>"/>
										<input id="jsonld_img_button" class="upload_image_button button-primary"
										       type="button"
										       value="<?php echo ltint( 'Choose image' ); ?>"/>
										<input id="jsonld_img_id" type="hidden"
										       name="jsonld_type_logo_id"
										       value="<?php echo ltiopt( 'jsonld_type_logo_id' ); ?>"/>
									</div>
								</div>
							</div>
							<div class="form-help-container">
								<div class="form-help">
									<p>JSON LD website search capabilities. The logo only applies to organizations.</p>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<label>Social accounts</label>

								<div class="input-group">
									<label for="account_facebook">Facebook
										<input type="text" name="account_facebook" id="account_facebook"
										       value="<?php echo ltiopt( 'account_facebook' ); ?>"/>
									</label>
									<label for="account_twitter">Twitter
										<input type="text" name="account_twitter" id="account_twitter"
										       value="<?php echo ltiopt( 'account_twitter' ); ?>"/>
									</label>
									<label for="account_gplus">Google+
										<input type="text" name="account_gplus" id="account_gplus"
										       value="<?php echo ltiopt( 'account_gplus' ); ?>"/>
									</label>
									<label for="account_instagram">Instagram
										<input type="text" name="account_instagram" id="account_instagram"
										       value="<?php echo ltiopt( 'account_instagram' ); ?>"/>
									</label>
									<label for="account_youtube">YouTube
										<input type="text" name="account_youtube" id="account_youtube"
										       value="<?php echo ltiopt( 'account_youtube' ); ?>"/>
									</label>
									<label for="account_linkedin">LinkedIn
										<input type="text" name="account_linkedin" id="account_linkedin"
										       value="<?php echo ltiopt( 'account_linkedin' ); ?>"/>
									</label>
									<label for="account_myspace">Myspace
										<input type="text" name="account_myspace" id="account_myspace"
										       value="<?php echo ltiopt( 'account_myspace' ); ?>"/>
									</label>
								</div>
							</div>
							<div class="form-help-container">
								<div class="form-help">
									<p>A list of comma delimited words (i.e "cats, dogs, elephants") that will be added
										before the keywords for all selected post types.</p>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<label for="jsonld_website_info">Website info
									<input type="checkbox" name="jsonld_website_info"
									       id="jsonld_website_info" <?php echo ltichk( 'jsonld_website_info' ); ?>/>
								</label>
							</div>
							<div class="form-help-container">
								<div class="form-help">
									<p>JSON LD website search capabilities.</p>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<label for="meta_description">Social media image
								</label>

								<div class="input-group file-selector">
									<label for="frontpage_social_img">Image</label>
									<input id="frontpage_social_img" type="text" name="frontpage_social_img_url"
									       value="<?php echo ltiopt( 'frontpage_social_img_url' ); ?>"
									       readonly="readonly"/>
									<input id="frontpage_social_img_button" class="button-primary upload_image_button"
									       type="button"
									       value="<?php echo ltint( 'Choose image' ); ?>"/>
									<input id="frontpage_social_img_id" type="hidden"
									       name="frontpage_social_img_id"
									       value="<?php echo ltiopt( 'frontpage_social_img_id' ); ?>"/>
								</div>
							</div>
							<div class="form-help-container">
								<div class="form-help">
									<p>If the "Open Graph Support" checkbox is ticked (see "Social" tab), will add type,
										site_name, title, url, description (if one is provided), and locale Open Graph
										tags to the front page. If an Image is provided, an extra image
										tag will be added.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label for="frontpage_extra_tags">Extra Tags
									<textarea name="frontpage_extra_tags"
									          id="frontpage_extra_tags"><?php echo ltichk( 'frontpage_extra_tags' ); ?></textarea>
							</label>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p>Extra tags.</p>
							</div>
						</div>
					</div>
				</div>
				<?php
				/***********************************************************************************************
				 *                             SOCIAL TAB
				 ***********************************************************************************************/
				?>
				<div role="tabpanel" class="tab-pane" id="tab_social">
					<div class="form-group">
						<div class="input-group">
							<div class="checkbox">
								<label for="meta_description">Open Graph support
									<input type="checkbox" name="open_graph_support" data-toggle="seo-options" data-target="#fb_publisher_group"
									       id="open_graph_support" <?php echo ltichk( 'open_graph_support' ); ?> />
								</label>
							</div>
							<div id="fb_publisher_group">
							<label for="facebook_publisher">Facebook publisher URL
								<input type="text" name="facebook_publisher" id="facebook_publisher"
								       value="<?php echo ltiopt( 'facebook_publisher' ); ?>"
								       placeholder="https://www.facebook.com/publisher"/>
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
									<li>Image, an upload form will appear in the LTI SEO meta box for you to specify an
										image from the media library.
									</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="input-group">
							<div class="checkbox">
								<label for="meta_description">Twitter Cards support
									<input type="checkbox" name="twitter_card_support"  data-toggle="seo-options" data-target="#twitter_card_group"
									       id="twitter_card_support" <?php echo ltichk( 'twitter_card_support' ); ?> />
								</label>
							</div>
							<div id="twitter_card_group" class="input-group">
								<label>
									<input name="twitter_card_type"
									       type="radio" <?php echo ltirad( 'twitter_card_type', 'summary' ); ?>
									       value="summary"/>Summary card
								</label>
								<label>
									<input name="twitter_card_type"
									       type="radio" <?php echo ltirad( 'twitter_card_type',
										'summary_img' ); ?>
									       value="summary_img"/>Summary card with large image
								</label>
								<label for="twitter_publisher">Twitter publisher username
									<input type="text" name="twitter_publisher" id="twitter_publisher"
									       value="<?php echo ltiopt( 'twitter_publisher' ); ?>"
									       placeholder="@publisher"/>
								</label>
							</div>

						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p></p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label for="gplus_publisher">Google+ publisher URL</label>
							<input type="text" name="gplus_publisher" id="gplus_publisher"
							       value="<?php echo ltiopt( 'gplus_publisher' ); ?>"
							       placeholder="https://plus.google.com/+Editor/"/>

						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group-submit">
				<div class="button-group-submit">
					<input id="in-seopt-submit" class="button-primary" type="submit"
					       value="<?php echo ltint( 'Save Changes', 'add-meta-tags' ); ?>"/>
					<input id="in-seopt-reset" class="button-primary" type="submit"
					       value="<?php echo ltint( 'Reset to defaults', 'add-meta-tags' ); ?>"/>
				</div>
			</div>
		</form>
	</div>
</div>