<?php
/**
 * @package upTown
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="page-title entry-title title">
			<h1><?php the_title(); ?></h1>			
		</div>
	</header><!-- .entry-header -->

	<div class="entry-content">
	
		<?php if( has_post_thumbnail() ) { ?>
		
			<div class="entry-image"><?php the_post_thumbnail( 'big', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?></div>
		
		<?php } ?>
	
		<?php the_content(); ?>
		
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'uptown' ),
				'after'  => '</div>',
			) );
		?>
		
	</div><!-- .entry-content -->

	<footer class="entry-meta">
	    <div class="entry-meta"><?php uptown_posted_on(); ?></div>
		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( ', ' );

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', ', ' );

			if ( ! uptown_categorized_blog() ) {
				// This blog only has 1 category so we just need to worry about tags in the meta text
				if ( '' != $tag_list ) {
					$meta_text = __( 'This entry was tagged %2$s.', 'uptown' );
				} else {
					$meta_text = false;
				}

			} else {
				// But this blog has loads of categories so we should probably display them here
				if ( '' != $tag_list ) {
					$meta_text = __( 'This entry was posted in %1$s and tagged %2$s.', 'uptown' );
				} else {
					$meta_text = __( 'This entry was posted in %1$s.', 'uptown' );
				}

			} // end check for categories on this blog

			printf(
				$meta_text,
				$category_list,
				$tag_list,
				get_permalink()
			);
		?>
	</footer><!-- .entry-meta -->
	
</article><!-- #post-## -->
