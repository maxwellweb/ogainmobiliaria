<?php
/**
 * @package upTown
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if( has_post_thumbnail() ) { ?>
	    <div class="entry-image">
	    	<a href="<?php the_permalink(); ?>">
	    		<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
	    	</a>
	    </div>
	<?php } ?>

	<header class="entry-header">
	
		<div class="entry-title">
			<h1><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		</div>

		<?php if ( 'post' == get_post_type() ) : ?>

		<div class="entry-meta">
			<?php if( is_sticky() && is_front_page() ) { ?>
				<div class="label"><?php _e( 'Sticky', 'uptown' ); ?></div>
			<?php } ?>
			<?php uptown_posted_on(); ?>
		</div><!-- .entry-meta -->

		<?php endif; ?>

	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<?php else : ?>

	<div class="entry-content">
	
		<?php uptown_the_excerpt( get_the_ID(), true, apply_filters( 'uptown_excerpt_length', 25 ) ); ?>
		
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'uptown' ),
				'after'  => '</div>',
			) );
		?>

	</div><!-- .entry-content -->

	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
