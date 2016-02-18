<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package upTown
 */
?>

		</div><!-- #content -->
		
	</div><!-- #content-wrap -->

	<footer id="colophon" class="site-footer" role="contentinfo">

		<div class="site-info">

			<?php do_action( 'uptown_credits' ); ?>
			
			<a href="http://wordpress.org/" rel="generator"><?php printf( __( 'Proudly powered by %s', 'uptown' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'uptown' ), 'upTown', '<a href="https://wpcasa.com" rel="designer">WPCasa</a>' ); ?>

		</div><!-- .site-info -->

	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>