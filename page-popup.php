<?php
/**
 * Template Name: Popup Page Template
 * Description: A Page Template that shows in the popup pages
 *
 * @package WordPress
 * @subpackage Cyon Theme
 */
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
<title><?php wp_title(''); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	wp_head();
?>

</head>

<body <?php body_class(); ?>>
	<div id="popup" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>><div class="article-wrapper">
					<header class="page-header">
						<?php cyon_post_header(); ?>
					</header>
					<div class="page-content clearfix">
						<?php cyon_post_content(); ?>
					</div>
					<footer class="entry-meta">
						<?php cyon_post_footer(); ?>
						<?php edit_post_link( __( 'Edit', 'cyon' ), '<span class="edit-link">', '</span>' ); ?>
					</footer>
				</div></article>
			<?php endwhile; ?>
	</div>
<?php wp_footer(); ?>
</body>
</html>