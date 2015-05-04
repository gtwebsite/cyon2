jQuery(document).ready(function(){
	
	jQuery('#page').transition({ opacity:1 });

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
	
	
	// Tooltip
	jQuery('.hastip, dfn, abbr').tooltipster();


	// Slider
	var cyonSwipe = [];
	jQuery('.swiper').each(function(index){
		jQuery(this).find('.swiper-pager').addClass('swiper-pager-' + index);
		jQuery(this).find('.swiper-button-next').addClass('swiper-button-next-' + index);
		jQuery(this).find('.swiper-button-prev').addClass('swiper-button-prev-' + index);
		cyonSwipe[index] = jQuery(this).find('.swiper-container').swiper({
			autoplay:Math.floor(Math.random() * 6000) + 4000,
			loop: true,
			calculateHeight: true,
			pagination: '.swiper-pager-' + index,
			paginationClickable: true,
	        nextButton: '.swiper-button-next-' + index,
	        prevButton: '.swiper-button-prev-' + index
		});
	});

			
});

jQuery(window).load(function(){
	// Tabs
	jQuery('.tabs').imagesLoaded(function(){
		jQuery('.tabs').tabulous();
	});

	// Toggle
	jQuery('.toggle').imagesLoaded(function(){
		jQuery('.toggle .toggle-wrapper').each(function(){
			var org_height = jQuery(this).css('height');
			if( !jQuery(this).parent().hasClass('toggle-active') ){
				jQuery(this).css({ height:'0px', display:'block' });
			}else{
				jQuery(this).height(org_height);
			}
			jQuery(this).parent().find('h3').click(function(){
				if(jQuery(this).parent().find('.toggle-wrapper').height()==0){
					jQuery(this).parent().addClass('toggle-active');
					jQuery(this).parent().find('.toggle-wrapper').height(org_height);
				}else{
					jQuery(this).parent().removeClass('toggle-active');
					jQuery(this).parent().find('.toggle-wrapper').height(0);
				}
			});
		});
	});

	// Accordion
	jQuery('.accordion').imagesLoaded(function(){
		jQuery('.accordion .accordion-wrapper').each(function(){
			var org_height = jQuery(this).css('height');
			if( !jQuery(this).parent().hasClass('accordion-active') ){
				jQuery(this).css({ height:'0px', display:'block' });
			}else{
				jQuery(this).height(org_height);
			}
			jQuery(this).parent().find('h3').click(function(){
				if(jQuery(this).parent().find('.accordion-wrapper').height()==0){
					jQuery('.accordion').removeClass('accordion-active');
					jQuery('.accordion .accordion-wrapper').height(0);
					jQuery(this).parent().find('.accordion-wrapper').height(org_height);
					jQuery(this).parent().addClass('accordion-active');
				}else{
					jQuery(this).parent().removeClass('accordion-active');
					jQuery(this).parent().find('.accordion-wrapper').height(0);
				}
			});
		});
	});
});
