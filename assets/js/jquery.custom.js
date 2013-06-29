jQuery(document).ready(function(){
	
	// Menu
	jQuery('#access').show();
	jQuery('#access li').hoverIntent(function(){
		jQuery(this).find('> ul').slideDown('fast', 'easeOutQuad');
	},function(){
		jQuery(this).find('> ul').hide();
	});

	// Box Close Support
	jQuery('.box .btn-close').click(function(){
		jQuery(this).parent().parent().fadeOut();
	});
	
	// Ease of Scrolling
	jQuery('#backtotop, .pagetoscroll').localScroll({ hash:true, easing:'easeInOutExpo' });
	
	// Toggle
	jQuery('.toggle h3').click(function(){
		if(jQuery(this).parent().find('.toggle-content').is(":hidden")) {
			jQuery(this).parent().find('.toggle-content').slideDown('fast', 'easeOutQuad');
			jQuery(this).addClass('toggle-active');
		} else {
			jQuery(this).parent().find('.toggle-content').slideUp('fast', 'easeOutQuad');
			jQuery(this).removeClass('toggle-active');
		}
	});

	// Accordion
	jQuery('.accordion .cyon-accordion-content').css('display','none');
	jQuery('.accordion h3').click(function(){
		if (jQuery(this).parent().find('.accordion-content').is(":hidden")) {
			jQuery('.accordion .accordion-content').slideUp('fast', 'easeOutQuad');
			jQuery('.accordion h3').removeClass('accordion-active');
			jQuery(this).parent().find('.accordion-content').slideDown('fast', 'easeOutQuad');
			jQuery(this).addClass('accordion-active');
		} else {
			jQuery('.accordion .accordion-content').slideUp('fast', 'easeOutQuad');
			jQuery(this).removeClass('accordion-active');
		}
	});

	// Tabs
	jQuery('.tabs').find('.tab_nav li:first-child').addClass('active');
	jQuery(jQuery('.tabs .tab_nav li.active a').attr('href')).show();
	jQuery('.tabs .tab_nav li a').click(function(e){
		var prev = jQuery(this).parent().parent().find('li.active a').attr('href');
		if (!jQuery(this).parent().hasClass('active')) {
			jQuery(this).parent().parent().find('li.active').removeClass('active');
			jQuery(this).parent().addClass('active');
		}
		var current = jQuery(this).attr('href');
		if(jQuery(jQuery(this).attr('href')).is(':hidden')){
			jQuery(prev).stop().slideUp('slow', 'easeOutQuad', function(){
				jQuery(current).stop().slideDown('fast', 'easeOutQuad');
			});
		}
		e.preventDefault();
		return false;
	});

});
