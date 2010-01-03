   // jquery functions to replace the old hid show div functions  
    //  this should also probally be refactord to make it smaller at some point

    jQuery(document).ready(function($) {
	//show/hide div code stats here 
	    //see if allow comment is checked on page load 
    if ($('#allow_comments').is(':checked'))
      {$('#comments_options').show();}
    else  
      {$('#comments_options').hide('fast');}
                       
    //see if share or original links are checked on page load 
    if (($('#enable_share').is(':checked')) || ($('#enable_external_link').is(':checked')) )
      {$('#position_option').show();}
    else  
      {$('#position_option').hide('fast');}
	 
	   //see if advanced is checked on page load 
    if ($('#advanced_options').is(':checked'))
      {$('#wpbook_advanced_options').show();}
    else  
      {$('#wpbook_advanced_options').hide('fast');}
	  
	  //see if gravatar settings are checked on page load
	   if ($('#use_gravatar').is(':checked'))
      {$('#gravatar_options').show();}
    else  
      {$('#gravatar_options').hide('fast');}
	  
	//see if gravatar custom default is checked on page load
	   if ($('.gravatar_rating_custom_radio').is(':checked'))
      {$('p.gravatar_rating_custom').show();}
    else  
      {$('p.gravatar_rating_custom').hide('fast');}
                       
    //toggle status of allow comments on click 
    $('#allow_comments').click(function(){
      if ($('#allow_comments').is(':checked'))
        {$('#comments_options').show('fast');}
      else
        {$('#comments_options').hide('fast');}
      });
                       
    //toggle status of share and original links on click 
    $('#enable_share').click(function(){
      if ($('#enable_share').is(':checked'))
        {$('#position_option').show('fast');}
      else if ($('#enable_external_link').is(':checked'))
        {$('#position_option').show('fast');}
      else
        {$('#position_option').hide('fast');}
      });
                       
    $('#enable_external_link').click(function(){
      if ($('#enable_external_link').is(':checked'))
        {$('#position_option').show('fast');}
      else if ($('#enable_share').is(':checked'))
        {$('#position_option').show('fast');}
      else
        {$('#position_option').hide('fast');}
    });

	 //toggle status of advanced options on click 
    $('#advanced_options').click(function(){
      if ($('#advanced_options').is(':checked'))
        {$('#wpbook_advanced_options').show('fast');}
      else
        {$('#wpbook_advanced_options').hide('fast');}
      });
	  
	  	 //toggle status of gravatar options on click 
    $('#use_gravatar').click(function(){
      if ($('#use_gravatar').is(':checked'))
        {$('#gravatar_options').show('fast');}
      else
        {$('#gravatar_options').hide('fast');}
      });
	  
	  //toggle status of gravatar custom default options on click  
		
$("input[@name='gravatar_default']").change(function(){
      if ($('.gravatar_rating_custom_radio').is(':checked'))
        {$('p.gravatar_rating_custom').show('fast');}
      else
        {$('p.gravatar_rating_custom').hide('fast');}
      });
	  
	
//start tooltip code here 

//comment tooltip
		$('.allow_comments').simpletip('This option either allows/disables the end-user from being able to leave comments on your Wordpress blog via Facebook.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
//require comment tooltip
		$('.require_email').simpletip('If checked this option requires the end-user to enter an e-mail in the format name@domain.com when leaving comments via Facebook.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
			
//use gravatar tooltip
		$('.use_gravatar').simpletip('If checked this option will show a 60x60 Gravatar image by each commment in Facebook.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
			
//gravatar_rating tooltip
		$('.gravatar_rating').simpletip('Much like a movie rating this lets you decide how "mature" of Gravatars to show.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
			
//gravatar_default tooltip
		$('.gravatar_default').simpletip('What to show if the user hasn\'t set up a Gravatar image.(mouse-over each option for an example) ', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
			
//gravatar_default_facebook image tooltip
		$('.gravatar_facebook_default').simpletip('<img src="../wp-content/plugins/wpbook/admin_includes/images/gravatar_default.gif" />', {
	stem: { corner: 'leftTop', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftTop'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
		
		
//gravatar_identicon_default_image tooltip
		$('.gravatar_identicon_default').simpletip('<img src="../wp-content/plugins/wpbook/admin_includes/images/identicon_default.gif" />', {
	stem: { corner: 'leftTop', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftTop'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
			
//gravatar_monsterid_default_image tooltip
		$('.gravatar_monsterid_default').simpletip('<img src="../wp-content/plugins/wpbook/admin_includes/images/monsterid_default.gif" />', {
	stem: { corner: 'leftTop', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftTop'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});

//gravatar_wavatar_default_image tooltip
		$('.gravatar_wavatar_default').simpletip('<img src="../wp-content/plugins/wpbook/admin_includes/images/wavatar_default.gif" />', {
	stem: { corner: 'leftTop', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftTop'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
			
//show invite tooltip
		$('.show_invite').simpletip('If checked this option adds an invite link to the top right corner which allows the end-user to invite their frinds to use your application.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
//show share tooltip
		$('.show_share').simpletip('If checked this option adds a share link to each post which allows the end-user to share the post on their facebook or to send the post to a friend. Note, the links will link back to the post within Facebook.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
		
//view external tooltip
		$('.show_external').simpletip('If checked this option adds a link to each post which allows the end-user to view the post on the parent blog taking them outside of Facebook.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});

//link position tooltip
		$('.link_position').simpletip('If you have enabled the share or the view external links you must choose if you want the link to show up before the post(top) or after each post(bottom). This setting applies to both the list and post views', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
		
//enable profile tooltip
		$('.enable_profile').simpletip('If checked this will allow the end-user to add your app to their profile or "box" page. Note, this does not allow them to add the app to the "tabs" page.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
			
//date format tooltip
		$('.date_format').simpletip('This is the format of the date that will be displayed in the timestamp used within facebook.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
			
//time format tooltip
		$('.time_format').simpletip('This is the format of the time that will be displayed in the timestamp used within facebook.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
			
//show date by title tooltip
		$('.show_date_title').simpletip('This option will show the date of the post by the post title. This is a common feature of many wordpress templates.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
		
//enable give credit tooltip
		$('.give_credit').simpletip('If checked this option will add "This Facebook Application powered by the WPBook plugin  for WordPress." to the bottom of your application. This helps support further devlopement as well as gets the word out about WPBook. Thanks for the support!', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
			
//enable pages tooltip
		$('.enable_pages').simpletip('This option will bring all your Wordpress pages into Facebook', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});

//advanced options tooltip
		$('.advanced_options').simpletip('If checked this option allow you to access the advacned options for wpbook. Such as time, date, and header footer information. ', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});

//custom header   tooltip
		$('.custom_header').simpletip('This is the format of the custom header if show custom header is set to true.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
//custom footer tooltip
		$('.custom_footer').simpletip('This is the format of the custom footer if show custom footer is set to true.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});

//show header footer   tooltip
		$('.show_header_footer').simpletip('This option is where you decide wither or not to show the custom a custom header, footer or both.', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
//get help tooltip
		$('.need_help').simpletip('Look, i\'m a useful tooltip :)', {
	stem: { corner: 'leftMiddle', color:'#DFDFDF', size: 12 }, 
	hook: {tooltip: 'leftMiddle'},
	contentClass: 'tooltip_content',
	viewpoint:true
			});
			
//date/time jquary from wp-admin/options-general.php
			$("input[name='timestamp_date_format']").click(function(){
			if ( "date_format_custom_radio" != $(this).attr("id") )
				$("input[name='timestamp_date_format_custom']").val( $(this).val() );
		});
		$("input[name='timestamp_date_format_custom']").focus(function(){
			$("#timestamp_date_format_custom").attr("checked", "checked");
		});

		$("input[name='timestamp_time_format']").click(function(){
			if ( "time_format_custom_radio" != $(this).attr("id") )
				$("input[name='timestamp_time_format_custom']").val( $(this).val() );
		});
		$("input[name='timestamp_time_format_custom']").focus(function(){
			$("#timestamp_time_format_custom").attr("checked", "checked");
		});


 });
  