<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ogainmobiliaria
 */

?>

<article id="post-<?php the_ID(); ?>">
	<div class="content col col--6-of-12 col--am col--centered">
	
			<?php
				if ( is_single() ) {
					the_title( '<h1>', '</h1>' );
				} else {
					the_title( '<h2><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				}

			if ( 'post' === get_post_type() ) : ?>
			
				
			
			<?php
			endif; ?>
			
			<?php
				the_content( sprintf(
					/* translators: %s: Name of current post. */
					wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'ogainmobiliaria' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ogainmobiliaria' ),
					'after'  => '</div>',
				) );
			?>

			<footer>
				<date><?php ogainmobiliaria_posted_on(); ?></date>
			</footer>
		<!-- <footer class="entry-footer">
			<?php ogainmobiliaria_entry_footer(); ?>
		</footer> .entry -footer -->
	</div>
</article><!-- #post-## -->
