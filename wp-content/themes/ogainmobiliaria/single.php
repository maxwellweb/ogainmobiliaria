<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ogainmobiliaria
 */

get_header(); ?>
<section class="cats-top">
  <div class="wrapper">
    <div class="row">
      <div class="col col--9-of-12 col--centered">
        <h2><span>[</span>Propiedades en venta<span>]</span></h2>
      </div>
    </div>
  </div>
</section>
<section class="properties extra-pad">

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', get_post_format() );

			the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
