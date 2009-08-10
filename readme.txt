=== Plugin Name ===
Contributors: oriolfb
Donate link: http://www.farre.cat/blog
Tags: twitter, Google Analytics, Tag, tweet,
Requires at least: 2.7.0
Tested up to: 2.8.3
Stable tag: trunk

Tag all your URL's posted to twitter with Google Analytics tags using Twitter Tools

== Description ==

Twitter Tools - Google Analytics Tagger integrats with Alex King's Twitter Tools to tag all the URL's published with the Google Analytics Campaign. 

The final URL will look like this: `http://www.foo.com/?utm_source=twitter&utm_medium=post&utm_campaign=social`

You can read more about it here: [URL Builder](http://www.google.com/support/analytics/bin/answer.py?hl=en&answer=55578 "Google Analytics URL Builder") 

== Installation ==

1. Upload the folder twitter-tools-google-analytics-tagging to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Options -> Twitter Tools to configure your Google Analytics 
1. Enjoy :-)

Configuration:

* **Campaign Source** (*utm_source*): **Required**. Use `utm_source` to identify a search engine, newsletter name, or other source. Example: `utm_source=google`

* **Campaign Medium** (*utm_medium*): **Required**. Use `utm_medium` to identify a medium such as email or cost-per- click. Example: `utm_medium=cpc`

* **Campaign Term** (*utm_term*): Used for paid search. Use `utm_term` to note the keywords for this ad. Example: `utm_term=running+shoes`

* **Campaign Content** (*utm_content*): Used for A/B testing and content-targeted ads. Use `utm_content` to differentiate ads or links that point to the same URL. Examples: `utm_content=logolink or utm_content=textlink`

* **Campaign Name** (*utm_campaign*): **Required**. Used for keyword analysis. Use `utm_campaign` to identify a specific product promotion or strategic campaign. Example: `utm_campaign=spring_sale`

== Changelog ==

= 1.0 =
* First version	
	
== Frequently Asked Questions ==

= Is compatible with the other Twitter Tools extensions? =

Yes, it is :-)