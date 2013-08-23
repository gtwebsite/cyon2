jQuery(document).ready(function(){
	
	jQuery('#page').transition({ opacity:1 })

	// Enable active on tap
	document.addEventListener("touchstart", function(){}, true);
	
	// Menu
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
	

	// Slider
	var cyonSwipe = [];
	jQuery('.swiper').each(function(index){
		jQuery(this).find('.swiper-pager').addClass('swiper-pager-' + index);
		cyonSwipe[index] = jQuery(this).find('.swiper-container').swiper({
			autoplay:Math.floor(Math.random() * 6000) + 4000,
			loop: true,
			calculateHeight: true,
			pagination: '.swiper-pager-' + index,
			paginationClickable: true
		});
	});
	jQuery('.swiper .swiper-left').on('click', function(e){
		e.preventDefault();
		cyonSwipe[jQuery('.swiper-left').index(this)].swipePrev();
	})
	jQuery('.swiper .swiper-right').on('click', function(e){
		e.preventDefault();
		cyonSwipe[jQuery('.swiper-right').index(this)].swipeNext();
	})

			
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

});

jQuery(window).load(function(){
	jQuery('.tabs').imagesLoaded(function(){
		jQuery('.tabs').tabulous();
	});
});
