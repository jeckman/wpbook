<?php

if (version_compare(PHP_VERSION,'5','>=')) {
	include_once(ABSPATH.'wp-content/themes/wp-facebook/client/facebook.php');  // php5
} else {
 include_once(ABSPATH.'wp-content/themes/wp-facebook/php4client/facebook.php');
 include_once(ABSPATH.'wp-content/themes/wp-facebook/php4client/facebookapi_php4_restlib.php');
}

$wpbookOptions = get_option('wpbookAdminOptions');

if (!empty($wpbookOptions)) {
	foreach ($wpbookOptions as $key => $option)
		$wpbookAdminOptions[$key] = $option;
	}

$api_key = $wpbookAdminOptions['fb_api_key'];
$secret  = $wpbookAdminOptions['fb_secret'];
$app_url = $wpbookAdminOpriona['fb_app_url'];

$facebook = new Facebook($api_key, $secret);
$user = $facebook->require_login(); 

$rs = $facebook->api_client->fql_query("SELECT name, pic FROM user WHERE uid = ".$user); 
//echo $rs[0]['name']; // debug only

?>
<div class="comments-post">
<?php if ($comments) : ?>
	<span class="comments"><?php comments_number('no comment yet, be the first !', '1 Comment for this post', '% Comments for this post' );?></span>
	<div id="commentlist">
		<?php foreach ($comments as $comment) : ?>
		<div class="acomment">
		<?php 
			if(function_exists(MyAvatarsNew)) {
				MyAvatarsNew();
			} else {
				echo get_avatar( $comment, 32 ); 
			} 
		?> <?php comment_author_link(); ?> Says: 
				<?php if ($comment->comment_approved == '0') : ?>
					<em>Your comment is awaiting moderation.</em>
				<?php endif; ?>
			<span class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('F jS, Y') ?> at <?php comment_time() ?></a> <?php edit_comment_link('e','',''); ?></span>
			<?php comment_text() ?>
		</div>
		<?php endforeach; /* end for each comment */ ?>
	</div><!-- //commentlist -->

 <?php else : // this is displayed if there are no comments so far ?>

  <?php if ('open' == $post-> comment_status) : ?> 
		<!-- If comments are open, but there are no comments. -->
		
	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>
</div><!-- close COMMENTS-POST -->		
	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post-> comment_status) : ?>
<strong>Comment from your Facebook Profile, <?php echo $rs[0]['name']; ?></strong>
	<div id="commentform-container">
		<p><input type="text"" name="email" id="email" value="" size="22" />
		<label for="email"><small> email address (will not be published)</small></label></p>
		<form action="<?php echo get_option('home'); ?>/wp-content/themes/wp-facebook/fb-comments-post.php" method="post" id="commentform">
		<p><textarea name="comment" id="comment" cols="50" rows="5" tabindex="4"></textarea></p>
		<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" class="inputsubmit" />
		<input type="hidden" name="author" id="author" value="<?php echo $rs[0][name]; ?>" />
		<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
		<input type="hidden" name="url" id="url" value="<?php echo 'http://www.facebook.com/profile.php?id=' . $user; ?>" />
		<?php do_action('comment_form', $post->ID); ?>
		</form>
	</div><!-- close commentform-container -->
</div><!-- close COMMENTS-POST -->

<?php endif; // end of included comments ?> 
