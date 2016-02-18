<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package upTown
 */

/**
 * Display navigation to next/previous pages when applicable
 *
 * @param string $nav_id Name of the navigation
 * @uses is_single()
 * @uses is_attachment()
 * @uses get_post()
 * @uses get_adjacent_post()
 * @uses is_home()
 * @uses is_archive()
 * @uses is_search()
 * @uses esc_attr()
 * @uses _e()
 * @uses _x()
 * @uses __()
 * @uses previous_post_link()
 * @uses next_post_link()
 * @uses get_next_posts_link()
 *
 * @since 1.0
 */

if ( ! function_exists( 'uptown_content_nav' ) ) {

	function uptown_content_nav( $nav_id ) {
		global $wp_query, $post;
	
		// Don't print empty markup on single pages if there's nowhere to navigate.
		if ( is_single() ) {
			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
			$next = get_adjacent_post( false, '', false );
	
			if ( ! $next && ! $previous )
				return;
		}
	
		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
			return;
	
		$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';
	
		?>
		<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
			<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'uptown' ); ?></h1>
	
		<?php if ( is_single() ) : // navigation links for single posts ?>
	
			<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'uptown' ) . '</span> %title' ); ?>
			<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'uptown' ) . '</span>' ); ?>
	
		<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
	
			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'uptown' ) ); ?></div>
			<?php endif; ?>
	
			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'uptown' ) ); ?></div>
			<?php endif; ?>
	
		<?php endif; ?>
	
		</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
		<?php
	}

} // endif uptown_content_nav


/**
 * Create pagination for archive pages
 *
 * @param integer $max_num_pages Query max_num_pages
 * @uses get_query_var()
 * @uses get_pagenum_link()
 * @uses max()
 * @uses str_replace()
 * @uses apply_filters()
 * @uses paginate_links()
 *
 * @since 1.0
 */

if ( ! function_exists( 'uptown_pagination' ) ) {

	function uptown_pagination( $max_num_pages = '' ) {
	
		if( empty( $max_num_pages ) ) {
			global $wp_query;
			$total = $wp_query->max_num_pages;
		} else {
			$total = $max_num_pages;
		}
	
		// need an unlikely integer
	    $big = 999999999;
	    
	    // Make sure paging works
					
		if ( get_query_var( 'paged' ) ) {
		        $paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
		        $paged = get_query_var( 'page' );
		} else {
		        $paged = 1;
		}
	    
	    $args = array(
	    	'base' 	    => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
			'format'    => '?paged=%#%',
			'current'   => max( 1, $paged ),
			'total'     => $total,
			'mid_size'  => 4,
			'prev_text' => '&larr; ' . __( 'Previous', 'uptown' ),
			'next_text' => __( 'Next', 'uptown' ) . ' &rarr;',
			'type' 	    => 'list'
		);
		
		$pagination = apply_filters( 'uptown_pagination_args', paginate_links( $args ) );
		
		if( empty( $pagination ) )
			return;
			
		// Create pagination output
		
		$pagination  = '<div class="pagination pagination-numbers">' . "\n";		
		$pagination .= paginate_links( $args );		
		$pagination .= '</div><!-- .pagination -->' . "\n";
		
		// Place active class on li
		
		$pagination = str_replace( '<li><span class=\'page-numbers current\'>', '<li class="active"><a href="#"><span class=\'page-numbers current\'>', $pagination );
		$pagination = str_replace( '</span>', '</span></a>', $pagination );
		
		// Place disabled class on li
		
		$pagination = str_replace( '<li><span class="page-numbers dots">', '<li class="disabled"><a href="#"><span class="page-numbers dots">', $pagination );
		
		echo apply_filters( 'uptown_pagination', $pagination );
	 
	}

} // endif uptown_pagination


/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since 1.0
 */

if ( ! function_exists( 'uptown_comment' ) ) {

	function uptown_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
	
		if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>
	
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<div class="comment-body">
				<?php _e( 'Pingback:', 'uptown' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'uptown' ), '<span class="edit-link">', '</span>' ); ?>
			</div>
	
		<?php else : ?>
	
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<footer class="comment-meta">
					<div class="comment-author vcard">
						<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
						<?php printf( __( '%s <span class="says">says:</span>', 'uptown' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
					</div><!-- .comment-author -->
	
					<div class="comment-metadata">
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>">
								<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'uptown' ), get_comment_date(), get_comment_time() ); ?>
							</time>
						</a>
						<?php edit_comment_link( __( 'Edit', 'uptown' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .comment-metadata -->
	
					<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'uptown' ); ?></p>
					<?php endif; ?>
				</footer><!-- .comment-meta -->
	
				<div class="comment-content">
					<?php comment_text(); ?>
				</div><!-- .comment-content -->
	
				<?php
					comment_reply_link( array_merge( $args, array(
						'add_below' => 'div-comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<div class="reply">',
						'after'     => '</div>',
					) ) );
				?>
			</article><!-- .comment-body -->
	
		<?php
		endif;
	}

} // endif check for uptown_comment()


/**
 * Prints the attached image with a link to the next attached image.
 *
 * @since 1.0
 */

if ( ! function_exists( 'uptown_the_attached_image' ) ) {

	function uptown_the_attached_image() {
		$post                = get_post();
		$attachment_size     = apply_filters( 'uptown_attachment_size', 'full' );
		$next_attachment_url = wp_get_attachment_url();
	
		/**
		 * Grab the IDs of all the image attachments in a gallery so we can get the
		 * URL of the next adjacent image in a gallery, or the first image (if
		 * we're looking at the last image in a gallery), or, in a gallery of one,
		 * just the link to that image file.
		 */
		$attachment_ids = get_posts( array(
			'post_parent'    => $post->post_parent,
			'fields'         => 'ids',
			'numberposts'    => -1,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'menu_order ID'
		) );
	
		// If there is more than 1 attachment in a gallery...
		if ( count( $attachment_ids ) > 1 ) {
			foreach ( $attachment_ids as $attachment_id ) {
				if ( $attachment_id == $post->ID ) {
					$next_id = current( $attachment_ids );
					break;
				}
			}
	
			// get the URL of the next image attachment...
			if ( $next_id )
				$next_attachment_url = get_attachment_link( $next_id );
	
			// or get the URL of the first image attachment.
			else
				$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
		}
	
		printf( '<a href="%1$s" rel="attachment">%2$s</a>',
			esc_url( $next_attachment_url ),
			wp_get_attachment_image( $post->ID, $attachment_size )
		);
	}

} // endif uptown_the_attached_image


/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since 1.0
 */

if ( ! function_exists( 'uptown_posted_on' ) ) {

	function uptown_posted_on() {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
			$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	
		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);
	
		printf( __( '<span class="posted-on">Posted on %1$s</span><span class="byline"> by %2$s</span>', 'uptown' ),
			sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
				esc_url( get_permalink() ),
				$time_string
			),
			sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_html( get_the_author() )
			)
		);
	}

} // endif uptown_posted_on


/**
 * Returns true if a blog has more than 1 category
 *
 * @since 1.0
 */
 
if ( ! function_exists( 'uptown_categorized_blog' ) ) {

	function uptown_categorized_blog() {
	
		if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
			// Create an array of all the categories that are attached to posts
			$all_the_cool_cats = get_categories( array(
				'hide_empty' => 1,
			) );
	
			// Count the number of categories that are attached to the posts
			$all_the_cool_cats = count( $all_the_cool_cats );
	
			set_transient( 'all_the_cool_cats', $all_the_cool_cats );
		}
	
		if ( '1' != $all_the_cool_cats ) {
			// This blog has more than 1 category so uptown_categorized_blog should return true
			return true;
		} else {
			// This blog has only 1 category so uptown_categorized_blog should return false
			return false;
		}
	}

} // ebdif uptown_posted_on


/**
 * Flush out the transients used in uptown_categorized_blog
 */

add_action( 'edit_category', 'uptown_category_transient_flusher' );
add_action( 'save_post',     'uptown_category_transient_flusher' );

function uptown_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
