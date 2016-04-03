<?php

/*
 * manages links related functions
 *
 * @since 1.2
 */
class PLL_Links {
	public $links_model, $model, $options;

	/*
	 * constructor
	 *
	 * @since 1.2
	 *
	 * @param object $polylang
	 */
	public function __construct( &$polylang ) {
		$this->links_model = &$polylang->links_model;
		$this->model = &$polylang->model;
		$this->options = &$polylang->options;
	}

	/*
	 * returns the home url in the requested language
	 *
	 * @since 1.3
	 *
	 * @param object|string $language
	 * @param bool $is_search optional wether we need the home url for a search form, defaults to false
	 */
	public function get_home_url( $language, $is_search = false ) {
		$language = is_object( $language ) ? $language : $this->model->get_language( $language );
		return $is_search ? $language->search_url : $language->home_url;
	}

	/*
	 * checks if the current user can read the post
	 *
	 * @since 1.5
	 *
	 * @param int $post_id
	 * @return bool
	 */
	public function current_user_can_read( $post_id ) {
		$post = get_post( $post_id );
		if ( in_array( $post->post_status, get_post_stati( array( 'public' => true ) ) ) ) {
			return true;
		}

		$post_type_object = get_post_type_object( $post->post_type );
		$user = wp_get_current_user();
		return is_user_logged_in() && ( current_user_can( $post_type_object->cap->read_private_posts ) || $user->ID == $post->post_author );
	}
}
