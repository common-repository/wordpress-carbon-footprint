<?php
/*
Plugin Name: WordPress Carbon Footprint
Version: 1.1
Plugin URI: http://www.seanbluestone.com/wordpress-carbon-footprint
Author: Sean Bluestone
Author URI: http://www.seanbluestone.com
Description: Carbon Footprint for WordPress

Copyright 2008  Sean Bluestone  (email : thedux0r@gmail.com)
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


How are these figures worked out?

The script searches through every post and every page on your blog, counting the total number of words and images in each post.
It then works out how many pages the text would take up assuming a standard of 794 words per page. This was worked out by taking the most widely used fonts, font sizes, the word processor being used and other print options.
It also works out how many pages the images would take up, again based on DPI, margins and other factors.
The most widely used paper is around 120gsm (grams per square meter) and standard A4 paper is 210x297mm. This means that we can approximate that there is 0.0074844g of carbon per sheet of paper which we multiply by our number of pages to give the total amount carbon saved by the blog vs it being printed out on paper.

The script does not factor in the ink which would be used, nor the electricity used to power the printer because both of these values are negligable. If your blog were somehow large enough for them to be a real factor they would also be extremely inaccurate.

Tools used in working these figures out:
* http://www.jmcpaper.com/carboncalculator/
* http://www.unitconversion.org/typography/centimeters-to-pixels-y-conversion.html

*/


register_activation_hook(__FILE__, 'carbonfootprint_get_footprint');
add_action('publish_page', 'carbonfootprint_get_footprint');
add_action('publish_post', 'carbonfootprint_get_footprint');


function carbonfootprint_get_footprint(){
	global $wpdb;

	$WordCount=$WordPages=$WordCarbon=$ImageCount=$ImagePages=$ImageCarbon=$TotalPages=$TotalCarbon=0;

	$getPosts=mysql_query("SELECT post_content FROM `".$wpdb->prefix."posts` WHERE post_status = 'publish'");
	while($Post=mysql_fetch_assoc($getPosts)){

		preg_match_all("/<img.*width=\"?([0-9]+)\"?.*height=\"?([0-9]+)\"?.*/",$Post['post_content'],$ImagesArray,PREG_SET_ORDER);

		foreach ($ImagesArray as $Images){
			if(count($Images)>0){
				$ImageCount++;
				$Width=$Images[2];
				$Height=$Images[1];

				// Assuming a DPI of 92 these are the standard pixel sizes for an A4 page.
				$ImagePages+=($Width/793.7)*($Height/1122.5);
			}
		}

	$WordCount+=str_word_count($Post['post_content']);
	}

	// The 1.38 is assuming some inefficiency from margins, single images per page, etc
	$ImagePages=number_format(($ImagePages*1.38),2);
	$ImageCarbon=number_format((.0074844*$ImagePages),2);

	// The 1.22 is assuming a small amount of inefficiency (carriage returns, margins, etc)
	$FormatMe=$WordCount/794;
	$WordPages=number_format((($WordCount/794)*1.22),1);
	$WordCarbon=number_format((.0074844*$WordPages),2);

	$TotalPages=$WordPages+$ImagePages;
	$TotalCarbon=$ImageCarbon+$WordCarbon; // number_format((.0074844*$TotalPages),2);

	update_option('wcf_word_count',$WordCount);
	update_option('wcf_word_pages',$WordPages);
	update_option('wcf_word_carbon',$WordCarbon);
	update_option('wcf_image_count',$ImageCount);
	update_option('wcf_image_pages',$ImagePages);
	update_option('wcf_image_carbon',$ImageCarbon);
	update_option('wcf_total_pages',$TotalPages);
	update_option('wcf_total_carbon',$TotalCarbon);
}

function carbonfootprint_display_footprint($Display='Medium'){

	$WordCount=get_option('wcf_word_count');
	$WordPages=get_option('wcf_word_pages');
	$WordCarbon=get_option('wcf_word_carbon');
	$ImageCount=get_option('wcf_image_count');
	$ImagePages=get_option('wcf_image_pages');
	$ImageCarbon=get_option('wcf_image_carbon');
	$TotalPages=get_option('wcf_total_pages');
	$TotalCarbon=get_option('wcf_total_carbon');

	if($Display=='Large'){

		echo "<table>
		<tr><td></td><td>Count</td><td>Pages</td><td>Carbon</td></tr>
		<tr><td>Words:</td><td>$WordCount</td><td>$WordPages</td><td>{$WordCarbon}g</td></tr>
		<tr><td>Images:</td><td>$ImageCount</td><td>$ImagePages</td><td>{$ImageCarbon}g</td></tr>
		<tr><td>Total:</td><td></td><td>$TotalPages</td><td><b>{$TotalCarbon}g</b></td></tr>
		<tr><td colspan=\"4\"><font size=\"-2\">Powered by <a href=\"http://www.seanbluestone.com/wordpress-carbon-footprint\">WordPress Carbon Footprint</a></font></td></tr>
		</table>";

	}elseif($Display=='Medium'){

		echo "Words: $WordCount ({$WordCarbon}g)<br>Images: $ImageCount ({$ImageCarbon}g)<br>Pages: $TotalPages<br>Carbon: {$TotalCarbon}g";

	}elseif($Display=='Small'){

		echo "{$TotalCarbon}g";

	}elseif($Display=='Extended'){

		echo "Below is a table of how many words and images are used on this site, how many pages those words and images would take up if printed out, and how much carbon would be used in that process.<br><br>

		These number of pages is worked out by adding together the area of the images and comparing it against the pixel size of A4 paper when printed at 92 DPI. The number of words is also factored in and based on the most widely used fonts and font sizes.<br><br>

		The carbon total is worked out assuming that standard A4 printer paper of 120gsm is used, meaning that each sheet of paper would contain 0.0074844 grams of carbon. Once we know how many pages it would take to print the entire blog we can work out how much carbon would be used in that process.<br><br>

		<table>
		<tr><td></td><td>Count<sup>1</sup></td><td>Pages<sup>2</sup></td><td>Carbon<sup>3</sup></td></tr>
		<tr><td>Words</td><td>$WordCount</td><td>$WordPages</td><td>{$WordCarbon}g</td></tr>
		<tr><td>Images</td><td>$ImageCount</td><td>$ImagePages</td><td>{$ImageCarbon}g</td></tr>
		<tr><td>Total</td><td></td><td>$TotalPages</td><td><b>{$TotalCarbon}g</b></td></tr>
		<tr><td colspan=\"4\"><sup>Powered by <a href=\"http://www.seanbluestone.com/wordpress-carbon-footprint\">WordPress Carbon Footprint</a></sup></td></tr>
		</table><br><br>

		<sup>1</sup> The total count of words or images used in the blog.<br>
		<sup>2</sup> The number of sheets of paper it would take to print this out.<br>
		<sup>3</sup> The amount of carbon that would be used in this process.";
	}
}

?>