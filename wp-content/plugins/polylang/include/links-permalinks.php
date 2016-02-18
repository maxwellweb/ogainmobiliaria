<?php

/*
 * links model base class when using pretty permalinks
 *
 * @since 1.6
 */
abstract class PLL_Links_Permalinks extends PLL_Links_Model {
	public $using_permalinks = true;
	protected $index = 'index.php'; // need this before $wp_rewrite is created, also harcoded in wp-includes/rewrite.php
	protected $root, $use_trailing_slashes;
	protected $always_rewrite = array( 'date', 'root', 'comments', 'search', 'author' );

	/*
	 * constructor
	 *
	 * @since 1.8
	 *
	 * @param object $model PLL_Model instance
	 */
	public function __construct( &$model ) {
		parent::__construct( $model );

		// inspired by wp-includes/rewrite.php
		$permalink_structure = get_option( 'permalink_structure' );
		$this->root = preg_match( '#^/*' . $this->index . '#', $permalink_structure ) ? $this->index . '/' : '';
		$this->use_trailing_slashes = ( '/' == substr( $permalink_structure, -1, 1 ) );
	}

	/*
	 * returns the link to the first page when using pretty permalinks
	 *
	 * @since 1.2
	 *
	 * @param string $url url to modify
	 * @return string modified url
	 */
	public function remove_paged_from_link( $url ) {
		return preg_replace( '#\/page\/[0-9]+\/?#', $this->use_trailing_slashes ? '/' : '', $url );
	}

	/*
	 * returns the link to the paged page when using pretty permalinks
	 *
	 * @since 1.5
	 *
	 * @param string $url url to modify
	 * @param int $page
	 * @return string modified url
	 */
	public function add_paged_to_link( $url, $page ) {
		return user_trailingslashit( trailingslashit( $url ) . 'page/' . $page, 'paged' );
	}

	/*
	 * returns the home url
	 *
	 * @since 1.3.1
	 *
	 * @param object $lang PLL_Language object
	 * @return string
	 */
	public function home_url( $lang ) {
		return trailingslashit( parent::home_url( $lang ) );
	}

	/*
	 * returns the static front page url
	 *
	 * @since 1.8
	 *
	 * @param object $lang
	 * @return string
	 */
	public function front_page_url( $lang ) {
		if ( $this->options['hide_default'] && $lang->slug == $this->options['default_lang'] ) {
			return trailingslashit( $this->home );
		}
		$url = home_url( $this->root . get_page_uri( $lang->page_on_front ) );
		$url = $this->use_trailing_slashes ? trailingslashit( $url ) : untrailingslashit( $url );
		return $this->options['force_lang'] ? $this->add_language_to_link( $url, $lang ) : $url;
	}

	/*
	 * prepares rewrite rules filters
	 *
	 * @since 1.6
	 */
	public function get_rewrite_rules_filters() {
		// make sure we have the right post types and taxonomies
		$types = array_values( array_merge( $this->model->get_translated_post_types(), $this->model->get_translated_taxonomies(), $this->model->get_filtered_taxonomies() ) );
		$types = array_merge( $this->always_rewrite, $types );
		return apply_filters( 'pll_rewrite_rules', $types ); // allow plugins to add rewrite rules to the language filter
	}
}
