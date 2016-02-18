<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package upTown
 */
 
get_header(); ?>

	<section id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
			
				<div class="page-title entry-title title">
					<h1><?php printf( __( 'Search Results for: %s', 'uptown' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				</div>

			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'search' ); ?>

			<?php endwhile; ?>

			<?php uptown_pagination(); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'search' ); ?>

		<?php endif; ?>

		</main><!-- #main -->

	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>