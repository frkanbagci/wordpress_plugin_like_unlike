<?php

	function plural_count_like_dislike($count, $singular, $plural = 's') {
    	if ($plural == 's') {
        $plural = $singular . $plural;
    	}
  	  return ($count == 1 ? $singular : $plural);
		}

	function liked_post_widget($args, $widget_args = 1) {
		
		extract( $args, EXTR_SKIP );
		if ( is_numeric($widget_args) )
			$widget_args = array( 'no' => $widget_args );
		$widget_args = wp_parse_args( $widget_args, array( 'no' => -1 ) );
		extract( $widget_args, EXTR_SKIP );
	
		$ayarlar = get_option('like_widget');
		if ( !isset($ayarlar[$no]) ) 
		return;

		$title = $ayarlar[$no]['title']; 		// single value
		$no_to_show = $ayarlar[$no]['no_to_show']; 		// single value
		$ignore_sticky = $ayarlar[$no]['ignore_sticky']; 		// single value
		$order_posts = $ayarlar[$no]['order_posts']; 	// single value
		
		echo $before_widget; // start widget display code ?>
		
			<?=$before_title . $title . $after_title;?>
            <ul>
			<?php
				global $post; 
	 			$args = array(
    			 'meta_key' => 'like',
				 'posts_per_page'=> $no_to_show,
				 'ignore_sticky_posts' => $ignore_sticky,
     			 'orderby' => 'meta_value_num',
				 'order' => $order_posts
   				);
   			$post_likes_query = new WP_Query ($args);
   			while ($post_likes_query -> have_posts()) : $post_likes_query -> the_post(); 
			?>
            <li>
            <a title="<?php the_title(); ?>" href="<?php the_permalink() ?>">
     		 <?php the_title(); ?>
     	    </a>
            <?php $likes = get_post_meta($post->ID, 'like', true); ?>
            <?php echo '- '.no_format($likes).plural_count_like_dislike($likes, ' Like'); ?>
            </li>
            <?php endwhile;?>
            </ul>
			
	<?php echo $after_widget; // end widget display code
	
	}
	
	
	function liked_post_widget_control($widget_args) {
	
		global $wp_registered_widgets;
		static $updated = false;
	
		if ( is_numeric($widget_args) )
			$widget_args = array( 'no' => $widget_args );			
		$widget_args = wp_parse_args( $widget_args, array( 'no' => -1 ) );
		extract( $widget_args, EXTR_SKIP );
	
		$ayarlar = get_option('like_widget');
		
		if ( !is_array($ayarlar) )	
			$ayarlar = array();
	
		if ( !$updated && !empty($_POST['sidebar']) ) {
		
			$sidebar = (string) $_POST['sidebar'];	
			$sidebars_widgets = wp_get_sidebars_widgets();
			
			if ( isset($sidebars_widgets[$sidebar]) )
				$this_sidebar =& $sidebars_widgets[$sidebar];
			else
				$this_sidebar = array();
	
			foreach ( (array) $this_sidebar as $_widget_id ) {
				if ( 'liked_post_widget' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['no']) ) {
					$widget_no = $wp_registered_widgets[$_widget_id]['params'][0]['no'];
					if ( !in_array( "like-widget-$widget_no", $_POST['widget-id'] ) ) // the widget has been removed.
						unset($ayarlar[$widget_no]);
				}
			}
	
			foreach ( (array) $_POST['like-widget'] as $widget_no => $like_widget ) {
				if ( !isset($like_widget['title']) && isset($ayarlar[$widget_no]) ) // user clicked cancel
					continue;
				
				$title = strip_tags(stripslashes($like_widget['title']));
				$no_to_show = strip_tags(stripslashes($like_widget['text_value']));				
				$ignore_sticky = $like_widget['radio_value'];
				$order_posts = $like_widget['select_value'];
				
				// Pact the values into an array
				$ayarlar[$widget_no] = compact( 'title', 'no_to_show', 'ignore_sticky', 'order_posts' );
			}
	
			update_option('like_widget', $ayarlar);
			$updated = true;
		}
	
		if ( -1 == $no ) { // if it's the first time and there are no existing values
	
			$title = '';
			$no_to_show = '';
			$ignore_sticky = '';
			$order_posts = '';
			$no = '%i%';
			
		} else { // otherwise get the existing values
		
			$title = attribute_escape($ayarlar[$no]['title']);
			$no_to_show = attribute_escape($ayarlar[$no]['no_to_show']); // attribute_escape used for security
			$ignore_sticky = $ayarlar[$no]['ignore_sticky'];
			$order_posts = $ayarlar[$no]['order_posts'];
		}
		
		//print_r($ayarlar[$no]);
	?>
	<p><label>Widget Başlık</label><br /><input id="title_value_<?php echo $no; ?>" name="like-widget[<?php echo $no; ?>][title]" type="text" size="40" value="<?=$title?>" /></p>
    <p><label>Gösterilecek mesaj sayısı</label><br /><input id="text_value_<?php echo $no; ?>" name="like-widget[<?php echo $no; ?>][text_value]" type="text" size="5" value="<?=$no_to_show?>" /></p>
    <p>
        <label>Sabit mesajları yoksay</label><br />
        Yes <input id="radio_value_<?php echo $no; ?>" name="like-widget[<?php echo $no; ?>][radio_value]" type="radio" <?php if($ignore_sticky == 'yes') echo 'checked="checked"'; ?> value="1" />
        No <input id="radio_value_<?php echo $no; ?>" name="like-widget[<?php echo $no; ?>][radio_value]" type="radio" <?php if($ignore_sticky == 'no') echo 'checked="checked"'; ?> value="0" />
    </p>
    <p>
        <label>Gönderiler için sipariş
        <select id="select_value_<?php echo $no; ?>" name="like-widget[<?php echo $no; ?>][select_value]">
            <option <?php if ($order_posts == 'ASC') echo 'selected'; ?> value="ASC">Artan</option>
            <option <?php if ($order_posts == 'DESC') echo 'selected'; ?> value="DESC">Azalan</option>
        </select>
        </label><br /><description>Gönderinin beğenisine göre sıralanması</description>
    </p>
    <input type="hidden" name="like-widget[<?php echo $no; ?>][submit]" value="1" />
    
	<?php
	}
	
	
	function liked_post_widget_register() {
		if ( !$ayarlar = get_option('like_widget') )
			$ayarlar = array();
		$widget_ops = array('classname' => 'like_widget', 'description' => __('Most Liked Post Widget Form'));
		$control_ops = array('width' => 400, 'height' => 350, 'id_base' => 'like-widget');
		$name = __('Most Liked Posts');
	
		$id = false;
		
		foreach ( (array) array_keys($ayarlar) as $o ) {
	
			if ( !isset( $ayarlar[$o]['title'] ) )
				continue;
						
			$id = "like-widget-$o";
			wp_register_sidebar_widget($id, $name, 'liked_post_widget', $widget_ops, array( 'no' => $o ));
			wp_register_widget_control($id, $name, 'liked_post_widget_control', $control_ops, array( 'no' => $o ));
		}
		
		if ( !$id ) {
			wp_register_sidebar_widget( 'like-widget-1', $name, 'liked_post_widget', $widget_ops, array( 'no' => -1 ) );
			wp_register_widget_control( 'like-widget-1', $name, 'liked_post_widget_control', $control_ops, array( 'no' => -1 ) );
		}
	}

add_action('init', liked_post_widget_register, 1);

?>