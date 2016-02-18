<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package upTown
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
	
		<div class="entry-title page-title title">
			<h1><?php the_title(); ?></h1>
		</div>

	</header><!-- .entry-header -->

	<div class="entry-content">
	
		<?php if( has_post_thumbnail() ) { ?>
		
			<?php 
				// Set thumb size according to page template
				$size = is_page_template( 'tpl-page-full.php' ) ? 'full' : 'big';
			?>
		
			<div class="entry-image"><?php the_post_thumbnail( $size, array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?></div>
		
		<?php } ?>

		<?php the_content(); ?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'uptown' ),
				'after'  => '</div>',
			) );
		?>

	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
