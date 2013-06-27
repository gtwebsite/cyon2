jQuery(document).ready(function($) {

	// Tabs
	$('.woocommerce-tabs').addClass('tabs');
	$('.woocommerce-tabs .tabs').addClass('tab_nav');
	$('.woocommerce-tabs').removeClass('woocommerce-tabs');
	$('.tabs .tabs').removeClass('tabs');
	$('.tabs .tab_nav li:first-child').addClass('active');
	$($('.tab_nav li.active a').attr('href')).show();
	$('.tab_nav li a').click(function(e){
		var prev = $(this).parent().parent().find('li.active a').attr('href');
		if (!$(this).parent().hasClass('active')) {
			$(this).parent().parent().find('li.active').removeClass('active');
			$(this).parent().addClass('active');
		}
		var current = $(this).attr('href');
		if($($(this).attr('href')).is(':hidden')){
			$(prev).slideUp('slow', function(){
				$(current).slideDown(500);
			});
		}
		e.preventDefault();
		return false;
	});

	// Star ratings for comments
	$('#rating').hide().before('<p class="stars"><span><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span></p>');

	$('body')
		.on( 'click', '#respond p.stars a', function(){
			var $star   = $(this);
			var $rating = $(this).closest('#respond').find('#rating');

			$rating.val( $star.text() );
			$star.siblings('a').removeClass('active');
			$star.addClass('active');

			return false;
		})
		.on( 'click', '#respond #submit', function(){
			var $rating = $(this).closest('#respond').find('#rating');
			var rating  = $rating.val();

			if ( $rating.size() > 0 && ! rating && woocommerce_params.review_rating_required == 'yes' ) {
				alert(woocommerce_params.i18n_required_rating_text);
				return false;
			}
		});

	// prevent double form submission
	$('form.cart').submit(function(){
		$(this).find(':submit').attr( 'disabled','disabled' );
	});

});