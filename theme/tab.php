<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"';
echo '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
echo '<html xmlns="http://www.w3.org/1999/xhtml" ';
echo 'xmlns:fb="http://www.facebook.com/2008/fbml">';
echo '<head>';
echo '<title>'. bloginfo('name').' :: Facebook Blog Application</title>';
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head();
echo '<link rel="stylesheet" href="'. WP_PLUGIN_URL .'/wpbook/theme/default/style.css"'; 
echo ' type="text/css" media="screen" />';
echo '<BASE TARGET="_top">';	
echo '</head>';
echo '<body>';
echo '<div id="content">';

if((!empty($data["user_id"])) && ($invite_friends == "true")) {
        $invite_link = '<a class="FB_UIButton FB_UIButton_Gray FB_UIButton_CustomIcon" href="'. $proto .'://apps.facebook.com/' . $app_url 
        .'/index.php?is_invite=true&fb_force_mode=fbml" class="share"><span class="FB_UIButton_Text"><span class="FB_Bookmark_Icon"></span> Invite Friends </span></a>';
	echo '<div style="float:right; margin-left: 3px; margin-bottom: 3px;  ">'. $invite_link .'</div>';	
} 
    
echo '<h3><a href="'. $proto .'://apps.facebook.com/'. $app_url .'/" target="_top">'. get_bloginfo('name') .'</a></h3>';

if(($show_pages == "true") && ($show_pages_menu == "true")){
    echo '<div id="underlinemenu" class="clearfix"><ul><li>Pages:</li>';
    if ($exclude_pages_true == "true"){
        wp_list_pages("sort_column=menu_order&depth=1&title_li=&exclude=$exclude_pages_list");
	} else {
		wp_list_pages("sort_column=menu_order&depth=1&title_li=");
	}
	echo '</ul></div>';
} //end show pages menu
	
echo '</div>';
	
if(is_page()){ // is a page 
	echo '<div id="content"><div class="box_head clearfix">';
	echo '<h3 class="wpbook_box_header">'. the_title() .'</a></h3>';
	if (have_posts()) : while (have_posts()) : the_post();
		the_content();
		endwhile; 
	else: 
		echo '<p>';
		_e('Sorry, page does not exist.');
		echo '</p>';
	endif;
	echo '</div>';	
} // end if is_page()
	
else {
	if(is_archive()){   
		echo '<div class="archive">';
		if (is_category()) { 
			echo '<p><b>';
			printf( __('You are currently browsing the %1$s archives for the \'%2$s\' category.'), $app_name, single_cat_title('', false) );
			echo '</b></p>';
		} elseif (is_day()) { 
			echo '<p><b>You are currently browsing the '. $app_name .' archives for the day '. the_time('l, F jS, Y') .'.</b></p>';
		} elseif (is_month()) { 
			echo '<p><b>You are currently browsing the '. $app_name .' archives for '. the_time('F, Y') .'.</b></p>';
		} elseif (is_year()) { 
			echo '<p><b>You are currently browsing the '. $app_name .' archives  for the year '. the_time('Y') .'.</b></p>';
		} elseif (is_search()) { 
			echo '<p><b>You have searched the '. $app_name .' archives for <strong>"'. wp_specialchars($s) .'"</strong>. </b></p>';
		} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { 
			echo '<p><b>You are currently browsing the '. $app_name.' archives.</b></p>';
		}	elseif(is_tag()){ 
			echo '<p><b>';
			printf( __('You are currently browsing the %1$s archives for the \'%2$s\' tag.'), $app_name, single_tag_title('', false) );
			echo '</b></p>';
		} 
	} // end of is_archive 
	echo '</div>';
	echo '<div id="content">';	
	if (have_posts()) { 
		while (have_posts()) { 
			the_post();
			if (is_single() || $wp_query->is_single || $wp_query->is_singular) {
				previous_post_link('&laquo; Previous Post: %link <br />',
								'%title',FALSE,'');
				next_post_link('Next Post: %link &raquo;<br />',
								'%title',FALSE,'');
			} //end if single  
			echo '<div class="box_head clearfix" id="post-'. the_ID() .'">';
			echo '<h3 class="wpbook_box_header">';
			if($show_date_title == "true"){
				the_time($timestamp_date_format); 
				echo(" - ");
			}
			echo '<a href="'. the_permalink() .'" target="_top">';
			echo the_title() .'</a></h3>'; 
			if(($show_custom_header_footer == "header") || ($show_custom_header_footer == "both")){
				echo( '<div id="custom_header">'.custom_header_footer($custom_header,$timestamp_date_format,$timestamp_time_format) .'</div>');
			} // end if for showing customer header
  
			if(($enable_share == "true" || $enable_external_link == "true") && ($links_position == "top")) { 
				echo '<p>';
				if($enable_share == "true"){
					echo '<span class="wpbook_share_button">';
					echo '<a onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=';
					echo urlencode(get_the_title());
					echo '&amp;p[summary]=';
					echo urlencode((wp_filter_nohtml_kses(apply_filters('the_content',get_the_excerpt()))));
					if((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
						$my_thumb_id = get_post_thumbnail_id();
						$my_thumb_array = wp_get_attachment_image_src($my_thumb_id);
						$my_image = $my_thumb_array[0]; // this should be the url                
						echo '&amp;p[images][0]=';
						echo urlencode($my_image);
					}
					echo '&amp;p[url]=';
					echo urlencode(get_permalink());
					echo "','sharer','toolbar=0,status=0,width=626,height=436'); return false;\""; 
					echo ' class="share" title="Send this to friends or post it on your profile.">Share This Post</a>';
					echo '</span>';
				} // end if for enable_share 
				if($enable_external_link == "true"){ 
					echo '<span class="wpbook_external_post"><a href="' get_external_post_url(get_permalink()) .'" title="View this post outside Facebook at '. bloginfo('name') .'">View post on '. bloginfo('name') .'</a></span>';
				} // end if for enable external_link
				echo '</p>';
			} // end links_position _top
			the_content();
			// echo custom footer
			if(($show_custom_header_footer == "footer") || ($show_custom_header_footer == "both")){	
				echo('<div id="custom_footer">'.custom_header_footer($custom_footer,$timestamp_date_format,$timestamp_time_format) .'</div>');
			} // endif for footer 
            
			// get share link 
			if(($enable_share == "true" || $enable_external_link == "true") && ($links_position == "bottom")) { 
				echo '<p>';
				if($enable_share == "true"){
					echo '<span class="wpbook_share_button">';
					echo '<a onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=';
					echo urlencode(get_the_title());
					echo '&amp;p[summary]=';
					echo urlencode((wp_filter_nohtml_kses(apply_filters('the_content',get_the_excerpt()))));
					if((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
						$my_thumb_id = get_post_thumbnail_id();
						$my_thumb_array = wp_get_attachment_image_src($my_thumb_id);
						$my_image = $my_thumb_array[0]; // this should be the url                
						echo '&amp;p[images][0]=';
						echo urlencode($my_image);
					}
					echo '&amp;p[url]=';
					echo urlencode(get_permalink());
					echo "','sharer','toolbar=0,status=0,width=626,height=436'); return false;\""; 
					echo ' class="share" title="Send this to friends or post it on your profile.">Share This Post</a>';
					echo '</span>';
				} // end if for enable_share              
				if($enable_external_link == "true"){
					echo '<span class="wpbook_external_post"><a href="'. get_external_post_url(get_permalink()) .'"';
					echo ' title="View this post outside Facebook at '. bloginfo('name') .'">View post on '. bloginfo('name') .'</a></span>';
				}
				echo '</p>';
			} // end if for enable share, external, bottom
			echo '</div>';	
                  
			comments_template();           
		} // end while have posts
                
		echo '<h3 class="wpbook_box_header">More Posts</h3>'; 
		echo '<p>';
		$wpbook_next_page = get_next_posts_link();
		$wpbook_prev_page = get_previous_posts_link();
		if ($wpbook_prev_page)
			echo $wpbook_prev_page;
		if ($wpbook_prev_page && $wpbook_next_page) 
			echo  ' | ';
		if ($wpbook_next_page)
			echo $wpbook_next_page;
		echo '</p>'; 
	} // end if have posts	
    echo '</div>';
} //end of else
                  
if($show_pages == "true" && $show_page_list=="true"){
	echo '<div class="box_head clearfix">';
	echo '<h3 class="wpbook_box_header">';
	_e('Pages');
	echo '</h3>';
	echo '<ul>';
	if ($exclude_pages_true == "true"){
		wp_list_pages("sort_column=menu_order&title_li=&exclude=$exclude_pages_list");
	} else {
		wp_list_pages("sort_column=menu_order&title_li=");
	}
	echo '</ul></div>';
}
  
if($show_post_list == "true"){
	echo '<div class="box_head clearfix">';
	echo '<h3 class="wpbook_box_header">';
	_e('Recent Posts'); 
	echo '</h3>';
	echo '<ul>';
	wp_recent_posts($recent_post_list_amount); 
	echo '</ul></div>';
}
  
if ($give_credit == "true"){ 
	echo '<div class="box_head clearfix" style="padding: 5px 0 0 0;">';
	echo '<p><small>This Facebook Application powered by '
		.'<a href="http://www.wordpress.org/extend/plugins/wpbook/">'
		.'the WPBook plugin</a> for <a href="http://www.wordpress.org/">';
		.'WordPress</a>.</small></p></div>'; 
}

echo '<div id="fb-root"></div>';
echo '<script>';
echo 'window.fbAsyncInit = function() {';
echo 'FB.init({appId: '. echo $api_key .', status: true, cookie: true,';
echo '     xfbml: true});';
echo 'FB.Canvas.setAutoResize();';
echo '};';
echo '(function() {';
echo "var e = document.createElement('script'); e.async = true;";
echo "e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';";
echo "document.getElementById('fb-root').appendChild(e);";
echo "}());";
echo "</script>";
echo '</body></html>';


