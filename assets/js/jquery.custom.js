jQuery(document).ready(function(){
	
	// Enable active on tap
	document.addEventListener("touchstart", function(){}, true);
	
	// Menu
	jQuery('#access').show();
	jQuery('#access li').each(function(){
		var org_height = jQuery(this).find('> ul').css('height');
		jQuery(this).find('> ul').css({ height:'0px', display:'block' });
		jQuery(this).hoverIntent(function(){
			jQuery(this).addClass('hover').find('> ul').transition({
				height: org_height,
				complete: function(){
					jQuery('#access .hover > ul').css('overflow','visible');
				}
			});
		},function(){
			jQuery(this).removeClass('hover').find('> ul').transition({ height:'0px' }).css('overflow','hidden');
		});
	})

	// Box Close Support
	jQuery('.box .btn-close').click(function(){
		jQuery(this).parent().parent().fadeOut();
	});
	
	// Ease of Scrolling
	jQuery('#backtotop, .pagetoscroll, .backtotop-line').localScroll({ hash:true, easing:'easeInOutExpo' });
	
	// Toggle
	jQuery('.toggle .toggle-wrapper').each(function(){
		var org_height = jQuery(this).css('height');
		jQuery(this).css({ height:'0px', display:'block' });
		jQuery(this).parent().find('h3').click(function(){
			if(jQuery(this).parent().find('.toggle-wrapper').height()==0){
				jQuery(this).parent().find('.toggle-wrapper').transition({ height: org_height });
				jQuery(this).parent().addClass('toggle-active');
			}else{
				jQuery(this).parent().find('.toggle-wrapper').transition({ height:'0px' });
				jQuery(this).parent().removeClass('toggle-active');
			}
		});
	});

	// Accordion
	jQuery('.accordion .accordion-wrapper').each(function(){
		var org_height = jQuery(this).css('height');
		jQuery(this).css({ height:'0px', display:'block' });
		jQuery(this).parent().find('h3').click(function(){
			if(jQuery(this).parent().find('.accordion-wrapper').height()==0){
				jQuery('.accordion .accordion-wrapper').transition({ height:'0px' }).delay(50);
				jQuery('.accordion').removeClass('accordion-active');
				jQuery(this).parent().find('.accordion-wrapper').transition({ height: org_height });
				jQuery(this).parent().addClass('accordion-active');
			}else{
				jQuery(this).parent().find('.accordion-wrapper').transition({ height:'0px' });
				jQuery(this).parent().removeClass('accordion-active');
			}
		});
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
