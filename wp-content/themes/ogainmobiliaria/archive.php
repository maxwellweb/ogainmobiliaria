<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ogainmobiliaria
 */

get_header('pagina'); ?>
<section class="cats-top">
  <div class="wrapper">
    <div class="row">
      <div class="col col--9-of-12 col--centered">
        <h2><span>[</span>Propiedades en venta<span>]</span></h2>
      </div>
    </div>
  </div>
</section>
<section>
  <div class="row">

		<?php
		if ( have_posts() ) : ?>

			<!--<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header> .page-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>
	</div>
</section>
<?php
//get_sidebar();
get_footer();
