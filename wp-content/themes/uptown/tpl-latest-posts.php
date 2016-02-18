<?php
/**
 * Template Name: Latest Posts (Blog)
 * This page template shows the latest posts.
 *
 * @package upTown
 * @since 1.0
 */
 
global $wp_query;

get_header();
the_post();

// Save parent page ID
$parent_id = get_the_ID();

// Make sure paging works

if ( get_query_var( 'paged' ) ) {
        $paged = get_query_var( 'paged' );
} elseif ( get_query_var( 'page' ) ) {
        $paged = get_query_var( 'page' );
} else {
        $paged = 1;
}                	

// Set args for properties custom query
$args = array(
    'post_type'		 => 'post',
    'posts_per_page' => get_option( 'posts_per_page' ),
    'paged'			 => $paged
);
    	
$args = apply_filters( 'uptown_post_query_args', $args );

// Custom query
$post_query = new WP_Query( $args ); ?>

	<section id="primary" class="content-area">

		<main id="main" class="site-main clear" role="main">

		<?php if ( $post_query->have_posts() ) : ?>
			
			<header class="entry-header clear">

				<div class="page-title entry-title title">				
					<h1><?php the_title(); ?></h1>					
				</div>

				<?php if( ! empty( $post->post_content ) ) { ?>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<?php } ?>

			</header><!-- .entry-header -->

			<?php /* Start the Loop */ ?>
			
			<?php while ( $post_query->have_posts() ) : $post_query->the_post(); ?>

				<?php get_template_part( 'content' ); ?>

			<?php endwhile; ?>

			<?php uptown_pagination( $post_query->max_num_pages ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'archive' ); ?>

		<?php endif; ?>

		</main><!-- #main -->

	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>