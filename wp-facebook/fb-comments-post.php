<?php
if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}
require('../../../wp-config.php' );

include_once 'config.php';

nocache_headers();

$comment_post_ID = (int) $_POST['comment_post_ID'];

$status = $wpdb->get_row("SELECT post_status, comment_status FROM $wpdb->posts WHERE ID = '$comment_post_ID'");

if ( empty($status->comment_status) ) {
	do_action('comment_id_not_found', $comment_post_ID);
	exit;
} elseif ( !comments_open($comment_post_ID) ) {
	do_action('comment_closed', $comment_post_ID);
	wp_die( __('Sorry, comments are closed for this item.') );
} elseif ( in_array($status->post_status, array('draft', 'pending') ) ) {
	do_action('comment_on_draft', $comment_post_ID);
	exit;
}

$comment_author       = trim(strip_tags($_POST['author']));
$comment_author_email = trim($_POST['email']);
$comment_author_url   = trim($_POST['url']);
$comment_content      = trim($_POST['comment']);

$comment_type = '';

if(($require_email == "true") && ('' == $comment_author_email)){
	wp_die( __('Error: please enter an e-mail.'));}
	
if($comment_author_email != ''){
if(!preg_match('/^[A-Z0-9._%-]+@[A-Z0-9.-]+\.(?:[A-Z]{2}|com|org|net|biz|info|name|aero|biz|info|jobs|museum|name|edu)$/i', $comment_author_email)){
	wp_die( __('Error: please enter a valid e-mail.'));}}

if ( '' == $comment_content )
	wp_die( __('Error: please type a comment.') );


$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'user_ID');

$comment_id = wp_new_comment( $commentdata );

$comment = get_comment($comment_id);
if ( !$user->ID ) {
	setcookie('comment_author_' . COOKIEHASH, $comment->comment_author, time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
	setcookie('comment_author_email_' . COOKIEHASH, $comment->comment_author_email, time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
	setcookie('comment_author_url_' . COOKIEHASH, clean_url($comment->comment_author_url), time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
}

/* all done parsing, thank user for comment
 * todo: nicer thank you page, redirect link to landing page of app
 */
$redirect_url = 'http://apps.facebook.com/' . $app_url . '/'; 
$facebook->redirect($redirect_url);
?>
