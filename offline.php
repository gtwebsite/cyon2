<?php
	
global $data;
$query = new WP_Query( 'page_id='.$data['site_offline_page'] );
while ( $query->have_posts() ) {
	$query->the_post();
?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php the_title() ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php cyon_offline_header(); ?>
</head>
<body class="offline">
	<div id="page" class="hfeed"><div class="page_wrapper">
		<article>
			<header class="page-header">
				<h1 class="paget-title"><?php the_title() ?></h1>
			</header>
			<div class="page-content clearfix">
				<?php the_content(); ?>
			</div>
		</article>
	</div></div>
	<?php cyon_offline_footer(); ?>
</body>
</html>
<?php } ?>