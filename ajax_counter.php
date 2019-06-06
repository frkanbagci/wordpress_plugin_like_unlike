<?php
error_reporting(0);
$changedDir='';
if (!$changedDir)$changedDir = preg_replace('|wp-content.*$|','',__FILE__);
include_once($changedDir.'/wp-config.php');

if(isset($_COOKIE['ul_post_cnt'])){
$posts_present=$_COOKIE['ul_post_cnt'];
} else {
$posts_present=array(); 
}

if(isset($_COOKIE['ul_comment_cnt'])){
$comment_present=$_COOKIE['ul_comment_cnt'];
} else {
$comment_present=array(); 
}

$post_id=$_POST['post_id'];
$up_type=$_POST['up_type'];

if($up_type=='c_like'||$up_type=='c_dislike')
{
	if(!in_array($post_id,$comment_present)){
	update_comment_ul_meta($post_id,$up_type);
	}
	$like_dislike_count = get_comment_meta($post_id, $up_type, true);
	if(empty($like_dislike_count)) { $like_dislike_count=0; }
	echo $like_dislike_count;

} else {
	
	if(!in_array($post_id,$posts_present)){
	update_post_ul_meta($post_id,$up_type);
	}
	$like_dislike_count = get_post_meta($post_id, $up_type, true);
	if(empty($like_dislike_count)) { $like_dislike_count=0; }
	echo $like_dislike_count;
}