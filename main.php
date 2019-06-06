<?php
/*
Plugin Name: Like Dislike Sayacı
Description: Like dislike Sayacı gönderiler ve yorumlar için
Author: Furkan Bağcı
Version: 4.9.4
Author URI: http://www.furkanbagci.com
*/

//include widget
include 'like_dislike_widget.php';

// Still ekleme
add_action( 'wp_enqueue_scripts', 'LikeDislike_Styles' );
function LikeDislike_Styles() {
        /** Enqueue Style Sheets */
			if(@file_exists(TEMPLATEPATH.'/like_dislike_style.css')) {
		 wp_enqueue_style('LikeDislike-Plugin-Style', get_stylesheet_directory_uri().'/like_dislike_style.css', array(), '1.1', 'screen');
	} else {
		 wp_enqueue_style( 'LikeDislike-Plugin-Style', plugin_dir_url( __FILE__ ) . 'like_dislike_style.css', array(), '1.1', 'screen' );
	}
}


// Eklenti için jQuery ekleme
add_action('init', 'like_dislike_couter_jquery_add');
function like_dislike_couter_jquery_add() {
    wp_enqueue_script( 'jquery' );
}    

// Post Likes 
function like_counter_p($text="Likes: ",$post_id=NULL)
{
	global $post;
	if(empty($post_id)) { $post_id = $post->ID; }
	$like_count = get_post_meta($post_id, 'like', true);
	if(empty($like_count)) { $like_count=0; }
	
	echo "<span class='ul_lcont' onclick=\"alter_ul_post_values(this,'$post_id','like')\" ><div class=\"like_dislike_text\">".$text."</div>(<span class=\"like_dislike_numbers\">".$like_count."</span>)</span>";
}

// Post Dislikes 
function dislike_counter_p($text="dislikes: ",$post_id=NULL)
{
	global $post;
	if(empty($post_id)) { $post_id = $post->ID; }
	$dislike_count = get_post_meta($post_id, 'dislike', true);
	if(empty($dislike_count)) { $dislike_count=0; }

	echo "<span class='ul_dcont' onclick=\"alter_ul_post_values(this,'$post_id','dislike')\" ><div class=\"like_dislike_text\">".$text."</div>(<span class=\"like_dislike_numbers\">".$dislike_count."</span>)</span>";
}

// Yorum Likes 
function like_counter_c($text="Likes: ",$post_id=NULL)
{
	global $comment;
	if(empty($post_id))	{ $post_id = get_comment_ID(); }
	$like_count = get_comment_meta($post_id, 'c_like', true);
	if(empty($like_count)) { $like_count=0; }

	echo "<span class='ul_lcont' onclick=\"alter_ul_post_values(this,'$post_id','c_like')\" ><div class=\"like_dislike_text\">".$text."</div>(<span class=\"like_dislike_numbers\">".$like_count."</span>)</span>";
}

// Yorum Dislikes 
function dislike_counter_c($text="dislikes: ",$post_id=NULL)
{
	global $comment;
	if(empty($post_id)) { $post_id = get_comment_ID(); }
	$dislike_count = get_comment_meta($post_id, 'c_dislike', true);
	if(empty($dislike_count)) { $dislike_count=0; }

	echo "<span class='ul_dcont' onclick=\"alter_ul_post_values(this,'$post_id','c_dislike')\" ><div class=\"like_dislike_text\">".$text."</div>(<span class=\"like_dislike_numbers\">".$dislike_count."</span>)</span>";
}

// Yorum meta tablosunda like, dislike güncelleme
  function update_comment_ul_meta($comment_id,$up_type)
  {
	$lnumber = get_comment_meta($comment_id, $up_type, true);
	if($lnumber)
	{ 
		
		if(isset($_COOKIE['ul_comment_cnt']))
		{
			$posts=$_COOKIE['ul_comment_cnt'];
			array_push($posts,$comment_id);
			foreach($posts as $key=>$value)
			{
			setcookie("ul_comment_cnt[$key]",$value, time()+1314000);
			}
		}
		else
		{
		setcookie("ul_comment_cnt[0]",$comment_id, time()+1314000);
		}
	  update_comment_meta($comment_id, $up_type, ($lnumber+1), $lnumber);
	}
	else
	{
		if(isset($_COOKIE['ul_comment_cnt']))
		{
			$posts=$_COOKIE['ul_comment_cnt'];
			array_push($posts,$comment_id);
			foreach($posts as $key=>$value)
			{
			setcookie("ul_comment_cnt[$key]",$value, time()+1314000);
			}
		}
		else
		{
		setcookie("ul_comment_cnt[0]",$comment_id, time()+1314000);
		}
	  add_comment_meta($comment_id, $up_type, $lnumber+1, true);
	}
  }


// post_meta tablosunda like, dislike güncelleme
  function update_post_ul_meta($post_id,$up_type)
  {
	$lnumber= get_post_meta($post_id,$up_type,true);
	if($lnumber)
	{ 
		
		if(isset($_COOKIE['ul_post_cnt']))
		{
			$posts=$_COOKIE['ul_post_cnt'];
			array_push($posts,$post_id);
			foreach($posts as $key=>$value)
			{
			setcookie("ul_post_cnt[$key]",$value, time()+1314000);
			}
		}
		else
		{
		setcookie("ul_post_cnt[0]",$post_id, time()+1314000);
		}
	  update_post_meta($post_id, $up_type, ($lnumber+1), $lnumber);
	}
	else
	{
		
		if(isset($_COOKIE['ul_post_cnt']))
		{
			$posts=$_COOKIE['ul_post_cnt'];
			array_push($posts,$post_id);
			foreach($posts as $key=>$value)
			{
			setcookie("ul_post_cnt[$key]",$value, time()+1314000);
			}
		}
		else
		{
		setcookie("ul_post_cnt[0]",$post_id, time()+1314000);
		}
	  add_post_meta($post_id, $up_type, $lnumber+1, true);
	}
  }

// Temanın footer kısmına Javascript ekleme
add_action('wp_footer', 'wp_dislike_like_footer_script');
function wp_dislike_like_footer_script() {
	if(!is_admin())
	{
	?>
<script type="text/javascript">
var isProcessing = false;  // <- 1

function alter_ul_post_values(obj, post_id, ul_type) {
    if (isProcessing)      // <- 2
        return;            // <- 3

    isProcessing = true;   // <- 4
    jQuery(obj).find("span").html("..");
    jQuery.ajax({
        type: "POST",
        url: "<?php echo plugins_url( 'ajax_counter.php' , __FILE__ );?>",
        data: "post_id="+post_id+"&up_type="+ul_type,
        success: function(msg) {
            jQuery(obj).find("span").html(msg);
            isProcessing = false;  // <- 5
        }
    });
}
</script>
    
    <?php
    }
}