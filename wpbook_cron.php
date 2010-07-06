<?php
/*
 wp_cron functions for WPBook - for importing comments from FB wall  
  Note: These functions draw heavily on code from:
    http://wordpress.org/extend/plugins/wordbooker by Steve Atty
*/

/*  
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
 This function gathers posts published in last X days (user configurable), 
 checks the wp_post_meta table to see if those posts have corresponding
 metadata indicating they've been posted to Facebook walls or pages, and then
 polls the corresponding stream_ids in Facebook for comments. If comments are
 found newer than last time stamp, they are processed. 
*/ 
function wpbook_import_comments() {
  global  $wpdb, $table_prefix;
  if(!class_exists('FacebookRestClient')) {
    include_once(WP_PLUGIN_DIR . '/wpbook/client/facebook.php');
  }
  $wpbook_settings =get_option('wpbookAdminOptions'); 
    
  if (!empty($wpbookOptions)) {
    foreach ($wpbookOptions as $key => $option)
      $wpbookAdminOptions[$key] = $option;
	}
  
  $facebook = new Facebook($api_key, $secret);

  //$sql="Select user_id from ".WORDBOOKER_USERDATA.$limit_user;
  //$wb_users = $wpdb->get_results($sql);
  if (!($wpbook_settings['import_comments'])) {
    return;
  }
  if ($wpbook_settings['approve_imported_comments'] == true) {
    $wpbook_comment_approval = 1;
  } else {
    $wpbook_comment_approval = 0;
  }
  // need to get posts in last X days which have postmeta for streamid
  $num_days = $wpbook_settings['num_days_import'];
  if ($num_days == '') { $num_days = 7; }
  $today = date("Y-m-d H:i:s");
  $daysago = date("Y-m-d H:i:s",strtotime(date('Y-m-j H:i:s')) - ($num_days * 24 * 60 * 60)); 	
  $sql='Select post_id FROM $wpdb->posts WHERE post_date BETWEEN '. $daysago . 'AND '. $today;
  $wordpress_post_ids = $wpdb->get_column($sql); // only need the post ids so we can use get_column
  if ($wordpress_post_ids) {
    foreach($wordpress_post_ids as $wordpress_post_id) {
      // now lets go find out which of those rows we need to examine
      $my_sql = "Select post_id,meta_key, meta_value from $wpdb->postmeta WHERE meta_key LIKE %_wpbook_% AND post_id = $wordpress_post_id"; 
      $my_meta_posts = $wpdb->get_results($sql);
      if(count($my_meta_posts)>0) {
        foreach($my_meta_posts as $meta_post) {
          if($meta_post->meta_key == '_wpbook_user_stream_id') {
            $my_timestamp_results = $wpdb->get_row("Select meta_value from $wpdb->postmeta WHERE meta_key LIKE %_wpbook_user_stream_time% AND post_id = $wordpress_post_id",ARRAY_A);
            $my_timestamp = $my_timestamp_results->meta_value;
            $fbsql="select time,text,fromid,xid,post_id from comment where post_id=$meta_post->meta_value and time > $my_timestamp order by time ASC"; 
            $fbcomments=$facebook->fql_query($fbsql);
            if (is_array($fbcomments)) {
              foreach ($fbcomments as $comment) {
                $fbuserinfo=$fbclient->users_getInfo($comment[fromid],'name,profile_url');
                $time = date("Y-m-d H:i:s",$comment[time]);
                $data = array(
                              'comment_post_ID' => $wordpress_post_id,
                              'comment_author' => $fbuserinfo[0][name],
                              'comment_author_email' => get_bloginfo( 'admin_email' ),
                              'comment_author_url' => $fbuserinfo[0][profile_url],
                              'comment_content' => $comment[text],
                              'comment_author_IP' => '127.0.0.1',
                              'comment_agent' => 'WPBook Comment Import',
                              'comment_date' => $time,
                              'comment_date_gmt' => $time,
                              'comment_approved' => $wpbook_comment_approval,
                              );
                wp_new_comment($data); 
                $sql="update $wpdb->postmeta set meta_value=$comment[time] where post_id=$meta_post->post_id and meta_key='_wpbook_user_stream_time'";
                $result = $wpdb->query($sql);
              } // end of new comment process for user stream
            } // end of comments for this post
          } // end of user_stream_id
          if ($meta_post->meta_key == '_wpbook_page_stream_id') {
            $my_timestamp_results = $wpdb->get_row("Select meta_value from $wpdb->postmeta WHERE meta_key LIKE %_wpbook_page_stream_time% AND post_id = $wordpress_post_id",ARRAY_A);
            $my_timestamp = $my_timestamp_results->meta_value;
            $fbsql="select time,text,fromid,xid,post_id from comment where post_id=$meta_post->meta_value and time > $my_timestamp order by time ASC"; 
            $fbcomments=$facebook->fql_query($fbsql);
            if (is_array($fbcomments)) {
              foreach ($fbcomments as $comment) {
                $fbuserinfo=$fbclient->users_getInfo($comment[fromid],'name,profile_url');
                $time = date("Y-m-d H:i:s",$comment[time]);
                $data = array(
                              'comment_post_ID' => $wordpress_post_id,
                              'comment_author' => $fbuserinfo[0][name],
                              'comment_author_email' => get_bloginfo( 'admin_email' ),
                              'comment_author_url' => $fbuserinfo[0][profile_url],
                              'comment_content' => $comment[text],
                              'comment_author_IP' => '127.0.0.1',
                              'comment_agent' => 'WPBook Comment Import',
                              'comment_date' => $time,
                              'comment_date_gmt' => $time,
                              'comment_approved' => $comment_approve,
                              );
                wp_new_comment($data); 
                $sql="update $wpdb->postmeta set meta_value=$comment[time] where post_id=$meta_post->post_id and meta_key='_wpbook_page_stream_time'";
                $result = $wpdb->query($sql);
              } // end of new comment process for user stream
            } // end of comments for this post
          } // end of if meta_key = stream_id
        } // end of is_array for fb comments
      } // end of meta posts > 0
    } // end of for each row of posts to examine
  } else {
    return;
  } // end of if rows to examine   
} // end of function
      
  
?>
