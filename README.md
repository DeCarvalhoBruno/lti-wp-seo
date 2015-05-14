# Wordpress SEO Plugin [![Build Status](https://travis-ci.org/DeCarvalhoBruno/lti-wp-seo.svg)](https://travis-ci.org/DeCarvalhoBruno/lti-wp-seo) [![Code Climate](https://codeclimate.com/github/DeCarvalhoBruno/lti-wp-seo/badges/gpa.svg)](https://codeclimate.com/github/DeCarvalhoBruno/lti-wp-seo)

**Note:** this is the development repository for the plugin. If you're installing the plugin on your please use the Wordpress plugin repository instead.

## Installation ##

### In Wordpress: ###
The easiest way to install the plugin is to use the plugins management page in your administration panel.

Also, the package can be downloaded manually and unzipped in the /wp-content/plugins/ directory.

When resources have been copied, the plugin can be activated by looking for a "LTI SEO" entry in the plugins page and clicking on **"Activate"**.

Configure the options through Settings->LTI SEO. Note that **by default, no header tags are added to the page**. LTI SEO will only add content that you activate in the LTI SEO options page.

Clicking on the **"Deactivate"** button will disable the user profile fields and the post editing box information associated with the plugin. The **"Delete"** button will remove any LTI SEO related field in the database.

###In a dev environment: ###

- Unzip the archive downloaded from github or git checkout the code in your wordpress plugin directory.
- Install composer dependencies (only one tiny package at time of this writing)
```
    $ composer install
```
- Optionally, if you want to tinker with CSS and JS:
```
    $ npm install
```

## Contribute ##

You can help us by:
- Translating the plugin in your own language (get in touch with me for details),
- Submitting bugs and feature requests in this project's [issue tracker](https://github.com/DeCarvalhoBruno/lti-wp-seo/issues),
- Submitting code via [pull requests](https://github.com/DeCarvalhoBruno/lti-wp-seo/pulls),
- [Visiting our blog](http://dev.linguisticteam.org) to interact with us and have awesome discussions around dev issues.

## Thank You ##

- To [The WordPress Plugin Boilerplate](http://wppb.io/) which was used to kickstart this project,
- To the existing Wordpress SEO ecosystem for the inspiration ([Yoast](https://github.com/Yoast/wordpress-seo) and [Add Meta Tags](http://www.g-loaded.eu/2006/01/05/add-meta-tags-wordpress-plugin/) in particular).
I don't think I pilfered code from you guys, but if I did, thanks again.