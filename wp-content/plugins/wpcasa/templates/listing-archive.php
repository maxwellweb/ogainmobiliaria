<div id="listing-<?php the_ID(); ?>" <?php wpsight_listing_class( 'entry-content listing-archive' ); ?> itemscope itemtype="http://schema.org/Product">

	<meta itemprop="name" content="<?php echo esc_attr( $post->post_title ); ?>" />
	
	<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="clearfix">

		<?php do_action( 'wpsight_listing_archive_before', $post ); ?>
		
		<div class="wpsight-listing-left">

			<?php wpsight_get_template( 'listing-archive-image.php' ); ?>

		</div>
		
		<div class="wpsight-listing-right">
		
			<?php wpsight_get_template( 'listing-archive-title.php' ); ?>
			
			<?php wpsight_get_template( 'listing-archive-info.php' ); ?>
			
			<?php wpsight_get_template( 'listing-archive-summary.php' ); ?>
			
			<?php wpsight_get_template( 'listing-archive-description.php' ); ?>
			
			<?php wpsight_get_template( 'listing-archive-compare.php' ); ?>
		
		</div>
		
		<?php do_action( 'wpsight_listing_archive_after', $post ); ?>
	
	</div>

</div><!-- #listing-<?php the_ID(); ?> -->

	 <div class="col col--6-of-12 col--m-2-of-4">
        <div class="propertie sell">
          <div class="propertie-img">
            <a href="#">
             <?php wpsight_get_template( 'listing-archive-image.php' ); ?>
            </a>
            <div class="propertie-price">528.000 €</div>
          </div><!-- IMG -->
          <div class="propertie-info">
            <h3>Carabanchel Alto <span>Madrid</span></h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
            <ul>
              <li>2 <span>Aseos</span></li>
              <li>3 <span>Dormitorios</span></li>
              <li>352 <span>M2</span></li>
            </ul>
          </div><!-- INFO -->
        </div>
      </div>
    <div class="col col--6-of-12 col--m-2-of-4">
        <div class="propertie sell">
          <div class="propertie-img">
            <a href="#">
              <img src="assets/img/fuenlabrada.jpg" alt="Featured Test Image">
            </a>
            <div class="propertie-price">613.000 €</div>
          </div><!-- IMG -->
          <div class="propertie-info">
            <h3>Carabanchel Alto <span>Madrid</span></h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
            <ul>
              <li>2 <span>Aseos</span></li>
              <li>3 <span>Dormitorios</span></li>
              <li>352 <span>M2</span></li>
            </ul>
          </div><!-- INFO -->
        </div>
      </div><!-- PROPERTIE -- SELL -->