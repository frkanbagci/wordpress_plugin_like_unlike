=== Like Dislike Counter===
Contributors: rahulbrilliant2004, tikendramaitry, jetonr
Donate link: http://www.wpfruits.com
Tags: counter, like, dislike, comments, posts, pages
Requires at least: 2.8
Tested up to: 3.4
Stable tag: 1.02
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple like dislike counter for posts, pages and comments.   

== Description ==
Like Dislike counter plugin is simple plugin which show likes and dislikes for a particular(post, page or a comment). Its very simple to use. You just need to write a simple code and the text that you want to diplay. It counts unique likes/dislikes. 

Read more instructions about how to use here http://www.wpfruits.com/downloads/wp-plugins/wp-like-dislike-counter-plugin/

=Features  =
    This plugin adds simple like dislike counter to posts and pages or comments as you put the code in the page templates. 


== Installation ==

0. Upload `like-dislike-counter` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
2. This plugin will not automatically added to your theme. You need to add php lines manually.
3. For Posts use the code in loop
	For like(with in php tags)
	 if(function_exists('like_counter_p')) { like_counter_p("text for like"); } 

	For Dislike(with in php tags)
	 if(function_exists('dislike_counter_p')) { dislike_counter_p("text for un-like"); } 

	Parameter provide is optional. HTML can also be used as parameter.
4. For Comments use the code in loop

	For like(with in php tags)
	 if(function_exists('like_counter_c')) { like_counter_c("text for like"); } 

	For Dislike(with in php tags)
	 if(function_exists('dislike_counter_c')) { dislike_counter_c("text for dislike"); } 

5. To get the total number of likes for post just use ( dont forget the php <?php ?> tags )
	
	echo get_post_meta ( $post->post_ID, 'like', true );

6. To get the total number of likes per comment within comment loop ( dont forget the php <?php ?> tags )
	
	echo get_comment_meta($comment->comment_ID, 'c_like', true);

7.Does not have any backend admin panel.

	Thanks for using this plugin

For More Details please visit http://www.wpfruits.com/downloads/wp-plugins/wp-like-dislike-counter-plugin/

== Frequently asked questions ==

= A question that someone might have =

An answer to that question.

== Screenshots ==



== Changelog ==
1.1
Updated plugin to show down.png in comments too. Earlier it was showing up.png for both like and dislike. 



== Upgrade notice ==



== Arbitrary section 1 ==

