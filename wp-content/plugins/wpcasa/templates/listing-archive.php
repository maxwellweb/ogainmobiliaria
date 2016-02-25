
<style type="text/css">
 .propertie-info a {
    
    
    color: #64908A;
}

.col  {
 
    margin-bottom: 20px;

}
</style>



          <?php  do_action( 'wpsight_listing_archive_before', $post ); ?>
              <div class="col col--6-of-12 col--m-2-of-4">
                <div class="propertie sell">
                      <div class="propertie-img">
                              <a href="#">
                                   <?php wpsight_get_template( 'listing-archive-image.php' ); ?>
                              </a>
                            <div class="propertie-price"><?php wpsight_get_template( 'listing-archive-info.php' ); ?></div>
                        </div><!-- IMG -->

                        <div class="propertie-info">
                                     <h3><?php wpsight_get_template( 'listing-archive-title.php' ); ?><span></span></h3>
                                     <?php wpsight_get_template( 'listing-archive-description.php' ); ?>

                                  <ul>
                                    <li> <span><?php wpsight_get_template( 'listing-archive-summary.php' ); ?></span></li>                                
                                   
                                  </ul>
                                

                                </div><!-- INFO -->
                </div><!--sell-->
           
            </div> <!--col-->

           <?php do_action( 'wpsight_listing_archive_after', $post ); ?> 
                 

