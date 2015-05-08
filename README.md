# Wordpress SEO Plugin

[![Build Status](https://travis-ci.org/DeCarvalhoBruno/lti-wp-seo.svg)](https://travis-ci.org/DeCarvalhoBruno/lti-wp-seo)

## Description

LTI SEO will add metadata information to the html header of your pages.

Available languages:
- English
- French

= Yet another SEO plugin, why? =

- We wanted to contribute a distraction-free, WYSIWYG plugin that goes straight to the point.
- Provide a sturdy, testable object-oriented codebase that the community can contribute to.
- Our main concern as a dev community is to automate our own processes, but we also want to show that we're willing to put ourselves out there and share awesome code!


### How is that metadata useful? =

A lot of the traffic over the internet goes through search engines, which send hoards of little crawler to sift through millions of pages every day.

As the provider of content, you can help search engines understand what type of content you're featuring by providing not just code, but semantic information about the content. That information will allow search engines to unmistakably determine the context in which you want this data to be shared.

Also, search engines that find relevant content on your sites are more likely to feature them prominently on search results, which is why we're referring to this process as search engine __optimization__.


### What kind of data does it add? =
The following information can be added, if the corresponding option is activated:
- Link rel tags:
 - Canonical, helps search engine determine a single URL for specific content,
 - Author, allows search engine to link the author with their contributed content,
 - Publisher, helps identify the publisher of the content.
- Keywords tag,
- Robots tag:
 - NOINDEX
 - NOFOLLOW
 - NOODP
 - NOYDIR
 - NOARCHIVE
 - NOSNIPPET
- Description tag, featured in search results
- JSON-LD tags:
 - Front page:
  - Publisher (show as Organization : name, alias, logo image, website, social accounts),
  - Author (shown as Person: public e-mail, job title, work location, social accounts),
  - Type of site (Blog or WebSite),
 - Pages and posts:
  - Type of post (Article, Blog post, News, Scholarly article, Tech article)
  - Author information (same person object as a above)
- Twitter cards:
 - Summary card by default
 - Summary with large image,
 - Gallery for gallery post types,
 - Photo for attachments
- Open Graph tags:
 - Type website on the frontpage
 - Type article on posts, with attached or featured images, if any.

## Installation ==

The easiest way to install the plugin is to use the plugins management page in your administration panel.

Also, the package can be unzipped in the /wp-content/plugins/ directory.

When resources have been copied, the plugin can be activated by looking for a "LTI Search engine optimization" entry in the plugins page and clicking on "Activate".

Configure the options by going to Settings->LTI SEO. Note that by default, no header tags are added to the page. LTI SEO will only add content that you activate in the LTI SEO options page.