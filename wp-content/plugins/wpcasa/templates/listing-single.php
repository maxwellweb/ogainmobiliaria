<style type="text/css">
	
 .search-box {
    padding-top: 0;
}
</style>

<?php
/**
 * Template: Single Listing
 */
global $listing; ?>


<div class="wpsight-listing wpsight-listing-<?php echo $listing->ID; ?> entry-content" itemscope itemtype="http://schema.org/Product">

	<meta itemprop="name" content="<?php echo esc_attr( $listing->post_title ); ?>" />

	<?php if ( $listing->post_status == 'expired' ) : ?>

		<div class="wpsight-alert wpsight-alert-expired">
			<?php _e( 'This listing has expired.', 'wpcasa' ); ?>
		</div>

	<?php endif; ?>
	
	<?php if ( $listing->post_status != 'expired' || wpsight_user_can_edit_listing( $listing->ID ) ) : ?>
	
		<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

	<?php do_action( 'wpsight_listing_single_before', $listing->ID ); ?>
				<section class="cats-detail extra-pad">
				  <div class="row">
				    <div class="col col--8-of-12">

				      <div class="col col--8-of-8">
				        <div class="detail-gallery">
				          <ul class="gallery">
				            <li><?php  wpsight_get_template( 'listing-single-image.php' ); ?></li>
				          </ul>
				          <div class="details">
				            <ul class="main-details">
				              <li class="price"><?php wpsight_get_template( 'listing-single-price.php' ); ?></li>
				              <li class="venta"><?php wpsight_get_template( 'listing-single-info.php' ); ?></li>
				              <li>Ref:<?php wpsight_get_template( 'listing-single-id.php' ); ?> </li>
				              <?php 

				              		/*aqui se modifico el archivo el archivo class-wpsight-listing
				              		la funcion get_listing_details2 para separar los detalles*/	
				              		wpsight_get_template( 'listing-single-details.php' );
				              


				               ?>
				              <li class="print">Imprimir</li>
				              <li class="share">Compartir</li>
				            </ul>

				            <div class="description col col--4-of-8">
				              <h2><?php wpsight_get_template( 'listing-single-title.php' ); ?></h2>
				              <p>
				                <?php wpsight_get_template( 'listing-single-description.php' ); ?>
				              </p>
				            </div>

				            <div class="additional col col--4-of-8">
				              <h3>Características adicionales</h3>
				              <ul>
				                <li><?php wpsight_get_template( 'listing-single-details.php' ); ?></li>
				              </ul>
				            </div>

				            <div class="video">
				              Un Video si lo hay…
				            </div>

				            <div class="map">
				              <?php wpsight_get_template( 'listing-single-location.php' ); ?>
				            </div>
				          </div>
				        </div>
				      </div> <!-- div col col--8-of-12-->

				     <!-- lista de locales  -->
				<?php

				return WPSight_Listings::listings();

		


			
				?>
						      <!-- -->


				      <div class="col col--2-of-2">
				        Pagination
				      </div>
				    </div>

				    <aside class="col col--4-of-12 cats-sidebar">
				      <div class="search-box">
				        <h4>Busca tu casa</h4>
				        <div class="search-field area col col--6-of-6">
				          <input type="text" name="ubicación" placeholder="ubicación">
				        </div>
				        <div class="search-field price col col col--6-of-6">
				          <input type="text" name="precio-desde" placeholder="precio desde">
				        </div>
				        <div class="search-field price col col col--6-of-6">
				          <input type="text" name="precio-hasta" placeholder="precio hasta">
				        </div>
				        <div class="search-field bat col col col--6-of-6">
				          <input type="text" name="aseos" placeholder="aseos">
				        </div>
				        <div class="search-field bed col col col--6-of-6">
				          <input type="text" name="dormitorios" placeholder="dormitorios">
				        </div>
				        <div class="search-btn btn col col col--6-of-6">
				          <input type="submit" name="buscar" value="buscar" placeholder="buscar">
				        </div>
				      </div>

				      <div class="closeby">
				        <h5>Propiedades Cercanas</h5>
				      </div>
				    </aside>
				  </div>
				</section>
		

			<?php do_action( 'wpsight_listing_single_after', $listing->ID ); ?>

		</div>
		
	<?php endif; ?>

</div><!-- .wpsight-listing-<?php echo $listing->ID; ?> -->