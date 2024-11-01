=== WordPress Carbon Footprint ===
Contributors: Seans0n
Donate link: http://www.seanbluestone.com
Tags: carbon,green,carbon footprint,statistics
Requires at least: 2.2
Tested up to: 2.6.3
Stable tag: 1.1

WordPress Carbon Footprint is a simple plugin for WordPress which works out and displays the carbon footprint of your blog. It works out how many words and images are on your blog and then calculates how many sheets of paper it would take to print a copy of your blog. It finally works out how much carbon is in those sheets of paper and displays all this information for you or your readers so you can show off how much carbon you’re offsetting by hosting your content online.

== Description ==

WordPress Carbon Footprint is a simple plugin for WordPress which works out and displays the carbon footprint of your blog. It works out how many words and images are on your blog and then calculates how many sheets of paper it would take to print a copy of your blog. It finally works out how much carbon is in those sheets of paper and displays all this information for you or your readers so you can show off how much carbon you’re offsetting by hosting your content online.

* The script searches through every post and every page on your blog, counting the total number of words and images in each post.
* It then works out how many pages the text would take up assuming a standard of 794 words per page. This was worked out by taking the most widely used fonts, font sizes, the word processor being used and other print options.
* It also works out how many pages the images would take up, again based on DPI, margins and other factors.
* The most widely used paper is around 120gsm (grams per square meter) and standard A4 paper is 210x297mm. This means that we can approximate that there is 0.0074844g of carbon per sheet of paper which we multiply by our number of pages to give the total amount carbon saved by the blog vs it being printed out on paper.

Related Links:

* <a href="http://www.seanbluestone.com/wordpress-carbon-footprint">Plugin Homepage</a>
* <a href="http://www.seanbluestone.com/">WordPress Carbon Footprint Demo</a>

== Installation ==

1. Extract & upload the carbon-footprint folder to your '/wp-content/plugins/' directory.
2. Activate the plugin via the Plugins menu in WordPress.
3. Navigate to Design -> Theme Editor -> Sidebar.php (or whichever page you like) and insert this code wherever you want your top referrers displayed:

	`<?php carbonfootprint_display_footprint(); ?>`

Thats it! Your carbon footprint details should show up wherever you pasted the code. The sidebar is a good place because it's on every page. You can specify the detail to print out by calling `carbonfootprint_display_footprint('Small'); carbonfootprint_display_footprint('Medium'); carbonfootprint_display_footprint('Large'); or carbonfootprint_display_footprint('Extended');` which will also show some information about how the figures are worked out.

Small will display something like this:
42.4g

While Medium will display something like this:
Words: 12323 (18.9g)
Images: 2 (0.70g)
Pages: 19.6
Carbon: 0.15g

And Large will display a table like this:
	Count	Pages	Carbon
Words	12323	19.4	0.15g
Images	2	0.71	0.01g
Total		20.11	0.16g