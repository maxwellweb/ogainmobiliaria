

<style type="text/css">
.wpsight-listing-price .listing-price-symbol, .wpsight-listing-price .listing-price-value {
    font-size: 14px;
}
</style>
<?php
/**
 * Template: Single Listing Info
 * 
 */
global $listing;



// Get listing offer key
$listing_offer = wpsight_get_listing_offer( $listing->ID, false ); ?>


	
	<?php do_action( 'wpsight_listing_single_info_before', $listing->ID ); ?>

	<!--SE MUESTRA SI ESTA EN VENTA O ALQUILER -->
	 <?php wpsight_listing_offer( $listing->ID ); ?>

	
	
	<?php do_action( 'wpsight_listing_single_info_after', $listing->ID ); ?>

