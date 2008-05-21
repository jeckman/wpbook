<?php

// the facebook client library
include_once 'config.php';
?>
<html>
<head>
	<title>Facebook Blog Application</title>
	<?php wp_head(); // in case any plugins rely on this hook ?>
	<style type="text/css" media="screen">
		@import url( <?php echo get_option('home'); ?>/wp-content/themes/wp-facebook/style.css);
	</style>
	<BASE TARGET="_top">
</head>
<body>
<script src="http://static.ak.facebook.com/js/api_lib/v0.3/FeatureLoader.js" type="text/javascript"></script>
<script type="text/javascript">
	FB_RequireFeatures(["CanvasUtil"], function() {
		FB.FBDebug.isEnabled=true;
		FB.FBDebug.logLevel = 4;
		FB.XdComm.Server.init("/wp-content/themes/wp-facebook/xd_reciever.html");
		FB.CanvasClient.startTimerToSizeToContent();
		});
</script>	

<div>
	<h3><a href="http://apps.facebook.com/<?php echo $app_url; ?>/" target="_top"><?php bloginfo('name'); ?></a></h3>

	<div id="content">
	<?php
	have_posts();

		if (have_posts()) : 
			while (have_posts()) : 
				the_post();
				if (is_single() || $wp_query->is_single || $wp_query->is_singular) {
				?>	
				<p>&laquo; <?php next_post('%', 'Next: ', 'yes'); ?> | <?php previous_post('%', 'Previous: ', 'yes'); ?> &raquo;</p>				
				<?php } ?>
				<div class="box_head clearfix" style="padding: 5px 0 0 0;"id="post-<?php the_ID(); ?>">
				<h3 style="padding: 1px 6px 0px 8px; border-top: solid 1px #3B5998; background: #d8dfea;">
					<a href="<?php the_permalink(); ?>" target="_top"><?php the_title(); ?></a></h3>
				<?php the_content(); ?>	
				</div>
				<?php
				comments_template('/comments_facebook.php'); 
				?>
	</div>
	<?php
			endwhile; // while have posts
		endif; // if have posts	
?>

<h2 id="recent"><?php _e('Recent Posts'); ?></h2>

<?php wp_recent_posts(10); ?>

</div>
</body>
</html>


