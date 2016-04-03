<?php

/*
 * manages filters and actions related to terms on admin side
 *
 * @since 1.2
 */
class PLL_Admin_Filters_Term {
	public $links, $model, $options, $curlang, $pref_lang;
	protected $pre_term_name; // used to store the term name before creating a slug if needed
	protected $post_id; // used to store the current post_id when bulk editing posts

	/*
	 * constructor: setups filters and actions
	 *
	 * @param object $polylang
	 */
	public function __construct( &$polylang ) {
		$this->links = &$polylang->links;
		$this->model = &$polylang->model;
		$this->options = &$polylang->options;
		$this->curlang = &$polylang->curlang;
		$this->pref_lang = &$polylang->pref_lang;

		foreach ( $this->model->get_translated_taxonomies() as $tax ) {
			// adds the language field in the 'Categories' and 'Post Tags' panels
			add_action( $tax.'_add_form_fields', array( &$this, 'add_term_form' ) );

			// adds the language field and translations tables in the 'Edit Category' and 'Edit Tag' panels
			add_action( $tax.'_edit_form_fields', array( &$this, 'edit_term_form' ) );

			// adds action related to languages when deleting categories and post tags
			add_action( 'delete_'.$tax, array( &$this, 'delete_term' ) );
		}

		// adds actions related to languages when creating or saving categories and post tags
		add_filter( 'wp_dropdown_cats', array( &$this, 'wp_dropdown_cats' ) );
		add_action( 'create_term', array( &$this, 'save_term' ), 999, 3 );
		add_action( 'edit_term', array( &$this, 'save_term' ), 999, 3 ); // late as it may conflict with other plugins, see http://wordpress.org/support/topic/polylang-and-wordpress-seo-by-yoast
		add_action( 'pre_post_update', array( &$this, 'pre_post_update' ) );
		add_filter( 'pre_term_name', array( &$this, 'pre_term_name' ) );
		add_filter( 'pre_term_slug', array( &$this, 'pre_term_slug' ), 10, 2 );

		// ajax response for edit term form
		add_action( 'wp_ajax_term_lang_choice', array( &$this, 'term_lang_choice' ) );
		add_action( 'wp_ajax_pll_terms_not_translated', array( &$this, 'ajax_terms_not_translated' ) );

		// adds cache domain when querying terms
		add_filter( 'get_terms_args', array( &$this, 'get_terms_args' ), 10, 2 );

		// filters categories and post tags by language
		add_filter( 'terms_clauses', array( &$this, 'terms_clauses' ), 10, 3 );

		// allows to get the default categories in all languages
		add_filter( 'option_default_category', array( &$this, 'option_default_category' ) );
		add_action( 'update_option_default_category', array( &$this, 'update_option_default_category' ), 10, 2 );

		// updates the translations term ids when splitting a shared term
		add_action( 'split_shared_term', array( &$this, 'split_shared_term' ), 10, 4 ); // WP 4.2
	}

	/*
	 * adds the language field in the 'Categories' and 'Post Tags' panels
	 *
	 * @since 0.1
	 */
	public function add_term_form() {
		$taxonomy = $_GET['taxonomy'];
		$post_type = isset( $GLOBALS['post_type'] ) ? $GLOBALS['post_type'] : $_REQUEST['post_type'];

		if ( ! taxonomy_exists( $taxonomy ) || ! post_type_exists( $post_type ) ) {
			return;
		}

		$lang = isset( $_GET['new_lang'] ) ? $this->model->get_language( $_GET['new_lang'] ) : $this->pref_lang;
		$dropdown = new PLL_Walker_Dropdown();

		wp_nonce_field( 'pll_language', '_pll_nonce' );

		printf( '
			<div class="form-field">
				<label for="term_lang_choice">%s</label>
				<div id="select-add-term-language">%s</div>
				<p>%s</p>
			</div>',
			__( 'Language', 'polylang' ),
			$dropdown->walk( $this->model->get_languages_list(), array(
				'name'     => 'term_lang_choice',
				'value'    => 'term_id',
				'selected' => $lang ? $lang->term_id : '',
				'flag'     => true
			) ),
			__( 'Sets the language', 'polylang' )
		);

		if ( ! empty( $_GET['from_tag'] ) ) {
			printf( '<input type="hidden" name="from_tag" value="%d" />', $_GET['from_tag'] );
		}

		// adds translation fields
		echo '<div id="term-translations" class="form-field">';
		if ( $lang ) {
			include( PLL_ADMIN_INC.'/view-translations-term.php' );
		}
		echo '</div>'."\n";
	}

	/*
	 * adds the language field and translations tables in the 'Edit Category' and 'Edit Tag' panels
	 *
	 * @since 0.1
	 */
	public function edit_term_form( $tag ) {
		$term_id = $tag->term_id;
		$lang = $this->model->term->get_language( $term_id );
		$taxonomy = $tag->taxonomy;
		$post_type = isset( $GLOBALS['post_type'] ) ? $GLOBALS['post_type'] : $_REQUEST['post_type'];

		if ( ! post_type_exists( $post_type ) ) {
			return;
		}

		$dropdown = new PLL_Walker_Dropdown();

		// disable the language dropdown and the translations input fields for default categories to prevent removal
		$disabled = in_array( get_option( 'default_category' ), $this->model->term->get_translations( $term_id ) );

		wp_nonce_field( 'pll_language', '_pll_nonce' );

		printf( '
			<tr class="form-field">
				<th scope="row">
					<label for="term_lang_choice">%s</label>
				</th>
				<td id="select-edit-term-language">
					%s<br />
					<p class="description">%s</p>
				</td>
			</tr>',
			__( 'Language', 'polylang' ),
			$dropdown->walk( $this->model->get_languages_list(), array(
				'name'     => 'term_lang_choice',
				'value'    => 'term_id',
				'selected' => $lang ? $lang->term_id : '',
				'disabled' => $disabled,
				'flag'     => true
			) ),
			__( 'Sets the language', 'polylang' )
		);

		echo '<tr id="term-translations" class="form-field">';
		if ( $lang ) {
			include( PLL_ADMIN_INC.'/view-translations-term.php' );
		}
		echo '</tr>'."\n";
	}

	/*
	 * translates term parent if exists when using "Add new" ( translation )
	 *
	 * @since 0.7
	 *
	 * @param string html markup for dropdown list of categories
	 * @return string modified html
	 */
	public function wp_dropdown_cats( $output ) {
		if ( isset( $_GET['taxonomy'], $_GET['from_tag'], $_GET['new_lang'] ) && taxonomy_exists( $_GET['taxonomy'] ) && $id = get_term( (int) $_GET['from_tag'], $_GET['taxonomy'] )->parent ) {
			$lang = $this->model->get_language( $_GET['new_lang'] );
			if ( $parent = $this->model->term->get_translation( $id, $lang ) ) {
				return str_replace( '"'.$parent.'"', '"'.$parent.'" selected="selected"', $output );
			}
		}
		return $output;
	}

	/*
	 * stores the current post_id when bulk editing posts for use in save_language and pre_term_slug
	 *
	 * @since 1.7
	 *
	 * @param int $post_id
	 */
	public function pre_post_update( $post_id ) {
		if ( isset( $_GET['bulk_edit'] ) ) {
			$this->post_id = $post_id;
		}
	}

	/*
	 * allows to set a language by default for terms if it has no language yet
	 *
	 * @since 1.5.4
	 *
	 * @param int $term_id
	 * @param string $taxonomy
	 */
	protected function set_default_language( $term_id, $taxonomy ) {
		if ( ! $this->model->term->get_language( $term_id ) ) {
			// sets language from term parent if exists thanks to Scott Kingsley Clark
			if ( ( $term = get_term( $term_id, $taxonomy ) ) && ! empty( $term->parent ) && $parent_lang = $this->model->term->get_language( $term->parent ) ) {
				$this->model->term->set_language( $term_id, $parent_lang );
			}
			else {
				$this->model->term->set_language( $term_id, $this->pref_lang );
			}
		}
	}

	/*
	 * saves language
	 *
	 * @since 1.5
	 *
	 * @param int $term_id
	 * @param string $taxonomy
	 */
	protected function save_language( $term_id, $taxonomy ) {
		global $wpdb;
		// security checks are necessary to accept language modifications
		// as 'wp_update_term' can be called from outside WP admin

		// edit tags
		if ( isset( $_POST['term_lang_choice'] ) ) {
			if ( 'add-' . $taxonomy == $_POST['action'] ) {
				check_ajax_referer( $_POST['action'], '_ajax_nonce-add-' . $taxonomy ); // category metabox
			}
			else {
				check_admin_referer( 'pll_language', '_pll_nonce' ); // edit tags or tags metabox
			}

			$this->model->term->set_language( $term_id, $this->model->get_language( $_POST['term_lang_choice'] ) );
		}

		// *post* bulk edit, in case a new term is created
		elseif ( isset( $_GET['bulk_edit'], $_GET['inline_lang_choice'] ) ) {
			check_admin_referer( 'bulk-posts' );

			// bulk edit does not modify the language
			// so we possibly create a tag in several languages
			if ( -1 == $_GET['inline_lang_choice'] ) {
				// the language of the current term is set a according to the language of the current post
				$this->model->term->set_language( $term_id, $this->model->post->get_language( $this->post_id ) );
				$term = get_term( $term_id, $taxonomy );

				// get all terms with the same name
				// FIXME backward compatibility WP < 4.2
				// no WP function to get all terms with the exact same name so let's use a custom query
				// $terms = get_terms( $taxonomy, array( 'name' => $term->name, 'hide_empty' => false, 'fields' => 'ids' ) ); should be OK in 4.2
				// I may need to rework the loop below
				$terms = $wpdb->get_results( $wpdb->prepare( "
					SELECT t.term_id FROM $wpdb->terms AS t
					INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
					WHERE tt.taxonomy = %s AND t.name = %s",
					$taxonomy, $term->name
				) );

				// if we have several terms with the same name, they are translations of each other
				if ( count( $terms ) > 1 ) {
					foreach ( $terms as $term ) {
							$translations[ $this->model->term->get_language( $term->term_id )->slug ] = $term->term_id;
					}

					$this->model->term->save_translations( $term_id, $translations );
				}
			}

			else {
				$this->model->term->set_language( $term_id, $this->model->get_language( $_GET['inline_lang_choice'] ) );
			}
		}

		// quick edit
		elseif ( isset( $_POST['inline_lang_choice'] ) ) {
			check_ajax_referer(
				isset( $_POST['action'] ) && 'inline-save' == $_POST['action'] ? 'inlineeditnonce' : 'taxinlineeditnonce', // post quick edit or tag quick edit ?
				'_inline_edit'
			);

			$old_lang = $this->model->term->get_language( $term_id ); // stores the old  language
			$lang = $this->model->get_language( $_POST['inline_lang_choice'] ); // new language
			$translations = $this->model->term->get_translations( $term_id );

			// checks if the new language already exists in the translation group
			if ( $old_lang && $old_lang->slug != $lang->slug ) {
				if ( array_key_exists( $lang->slug, $translations ) ) {
					$this->model->term->delete_translation( $term_id );
				}

				elseif ( array_key_exists( $old_lang->slug, $translations ) ) {
					unset( $translations[ $old_lang->slug ] );
					$this->model->term->save_translations( $term_id, $translations );
				}
			}

			$this->model->term->set_language( $term_id, $lang ); // set new language
		}

		// edit post
		elseif ( isset( $_POST['post_lang_choice'] ) ) {// FIXME should be useless now
			check_admin_referer( 'pll_language', '_pll_nonce' );
			$this->model->term->set_language( $term_id, $this->model->get_language( $_POST['post_lang_choice'] ) );
		}

		else {
			$this->set_default_language( $term_id, $taxonomy );
		}
	}

	/*
	 * save translations from our form
	 *
	 * @since 1.5
	 *
	 * @param int $term_id
	 * @return array
	 */
	protected function save_translations( $term_id ) {
		// security check
		// as 'wp_update_term' can be called from outside WP admin
		check_admin_referer( 'pll_language', '_pll_nonce' );

		// save translations after checking the translated term is in the right language ( as well as cast id to int )
		foreach ( $_POST['term_tr_lang'] as $lang => $tr_id ) {
			$tr_lang = $this->model->term->get_language( (int) $tr_id );
			$translations[ $lang ] = $tr_lang && $tr_lang->slug == $lang ? (int) $tr_id : 0;
		}

		$this->model->term->save_translations( $term_id, $translations );

		return $translations;
	}

	/*
	 * called when a category or post tag is created or edited
	 * saves language and translations
	 *
	 * @since 0.1
	 *
	 * @param int $term_id
	 * @param int $tt_id term taxononomy id
	 * @param string $taxonomy
	 */
	public function save_term( $term_id, $tt_id, $taxonomy ) {
		// does nothing except on taxonomies which are filterable
		if ( ! $this->model->is_translated_taxonomy( $taxonomy ) ) {
			return;
		}

		// capability check
		// as 'wp_update_term' can be called from outside WP admin
		// 2nd test for creating tags when creating / editing a post
		$tax = get_taxonomy( $taxonomy );
		if ( current_user_can( $tax->cap->edit_terms ) || ( isset( $_POST['tax_input'][ $taxonomy ] ) && current_user_can( $tax->cap->assign_terms ) ) ) {
			$this->save_language( $term_id, $taxonomy );

			if ( isset( $_POST['term_tr_lang'] ) ) {
				$translations = $this->save_translations( $term_id );
			}

			do_action( 'pll_save_term', $term_id, $taxonomy, empty( $translations ) ? $this->model->term->get_translations( $term_id ) : $translations );
		}

		// attempts to set a default language even if no capability
		else {
			$this->set_default_language( $term_id, $taxonomy );
		}
	}

	/*
	 * stores the term name for use in pre_term_slug
	 *
	 * @since 0.9.5
	 *
	 * @param string $name term name
	 * @return string unmodified term name
	 */
	public function pre_term_name( $name ) {
		return $this->pre_term_name = $name;
	}

	/*
	 * creates the term slug in case the term already exists in another language
	 *
	 * @since 0.9.5
	 *
	 * @param string $slug
	 * @param string $taxonomy
	 * @return string
	 */
	public function pre_term_slug( $slug, $taxonomy ) {
		$name = sanitize_title( $this->pre_term_name );

		// if the new term has the same name as a language, we *need* to differentiate the term
		// see http://core.trac.wordpress.org/ticket/23199
		// backward compatibility with WP < 4.1
		if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) && term_exists( $name, 'language' ) && ! term_exists( $name, $taxonomy ) && ( ! $slug || $slug == $name ) ) {
			$slug = $name . '-' . $taxonomy; // a convenient slug which may be modified later by the user
		}

		// if the term already exists in another language
		if ( ! $slug && $this->model->is_translated_taxonomy( $taxonomy ) && term_exists( $name, $taxonomy ) ) {
			if ( isset( $_POST['term_lang_choice'] ) ) {
				$slug = $name . '-' . $this->model->get_language( $_POST['term_lang_choice'] )->slug;
			}

			elseif ( isset( $_POST['inline_lang_choice'] ) ) {
				$slug = $name . '-' . $this->model->get_language( $_POST['inline_lang_choice'] )->slug;
			}

			// *post* bulk edit, in case a new term is created
			elseif ( isset( $_GET['bulk_edit'], $_GET['inline_lang_choice'] ) ) {
				// bulk edit does not modify the language
				if ( -1 == $_GET['inline_lang_choice'] ) {
					$slug = $name . '-' .  $this->model->post->get_language( $this->post_id )->slug;
				}
				else {
					$slug = $name . '-' . $this->model->get_language( $_GET['inline_lang_choice'] )->slug;
				}
			}
		}

		return $slug;
	}

	/*
	 * called when a category or post tag is deleted
	 * deletes language and translations
	 *
	 * @since 0.1
	 *
	 * @param int $term_id
	 */
	public function delete_term( $term_id ) {
		$this->model->term->delete_translation( $term_id );
		$this->model->term->delete_language( $term_id );
	}

	/*
	 * ajax response for edit term form
	 *
	 * @since 0.2
	 */
	public function term_lang_choice() {
		check_ajax_referer( 'pll_language', '_pll_nonce' );

		$lang = $this->model->get_language( $_POST['lang'] );
		$term_id = isset( $_POST['term_id'] ) ? (int) $_POST['term_id'] : null;
		$taxonomy = $_POST['taxonomy'];
		$post_type = $_POST['post_type'];

		if ( ! post_type_exists( $post_type ) || ! taxonomy_exists( $taxonomy ) ) {
			die( 0 );
		}

		ob_start();
		if ( $lang ) {
			include( PLL_ADMIN_INC.'/view-translations-term.php' );
		}
		$x = new WP_Ajax_Response( array( 'what' => 'translations', 'data' => ob_get_contents() ) );
		ob_end_clean();

		// parent dropdown list ( only for hierarchical taxonomies )
		// $args copied from edit_tags.php except echo
		if ( is_taxonomy_hierarchical( $taxonomy ) ) {
			$args = array(
				'hide_empty' => 0,
				'hide_if_empty' => false,
				'taxonomy' => $taxonomy,
				'name' => 'parent',
				'orderby' => 'name',
				'hierarchical' => true,
				'show_option_none' => __( 'None' ),
				'echo' => 0,
			);
			$x->Add( array( 'what' => 'parent', 'data' => wp_dropdown_categories( $args ) ) );
		}

		// tag cloud
		// tests copied from edit_tags.php
		else {
			$tax = get_taxonomy( $taxonomy );
		 	if ( ! is_null( $tax->labels->popular_items ) ) {
				$args = array( 'taxonomy' => $taxonomy, 'echo' => false );
				if ( current_user_can( $tax->cap->edit_terms ) ) {
					$args = array_merge( $args, array( 'link' => 'edit' ) );
				}

				if ( $tag_cloud = wp_tag_cloud( $args ) ) {
					$x->Add( array( 'what' => 'tag_cloud', 'data' => '<h3>'.$tax->labels->popular_items.'</h3>'.$tag_cloud ) );
				}
			}
		}

		// flag
		$x->Add( array( 'what' => 'flag', 'data' => empty( $lang->flag ) ? esc_html( $lang->slug ) : $lang->flag ) );

		$x->send();
	}

	/*
	 * ajax response for input in translation autocomplete input box
	 *
	 * @since 1.5
	 */
	public function ajax_terms_not_translated() {
		check_ajax_referer( 'pll_language', '_pll_nonce' );

		$s = wp_unslash( $_GET['term'] );
		$post_type = $_GET['post_type'];
		$taxonomy = $_GET['taxonomy'];

		if ( ! post_type_exists( $post_type ) || ! taxonomy_exists( $taxonomy ) ) {
			die( 0 );
		}

		$term_language = $this->model->get_language( $_GET['term_language'] );
		$translation_language = $this->model->get_language( $_GET['translation_language'] );

		$return = array();

		// it is more efficient to use one common query for all languages as soon as there are more than 2
		// pll_get_terms_not_translated arg to identify this query in terms_clauses filter
		foreach ( get_terms( $taxonomy, 'hide_empty=0&pll_get_terms_not_translated=1&name__like=' . $s ) as $term ) {
			$lang = $this->model->term->get_language( $term->term_id );

			if ( $lang && $lang->slug == $translation_language->slug && ! $this->model->term->get_translation( $term->term_id, $term_language ) ) {
				$return[] = array(
					'id' => $term->term_id,
					'value' => $term->name,
					'link' => $this->links->edit_term_translation_link( $term->term_id, $taxonomy, $post_type ),
				);
			}
		}

		// add current translation in list
		// not in add term for as term_id is not set
		if ( 'undefined' !== $_GET['term_id'] && $term_id = $this->model->term->get_translation( (int) $_GET['term_id'], $translation_language ) ) {
			$term = get_term( $term_id, $taxonomy );
			array_unshift( $return, array(
				'id' => $term_id,
				'value' => $term->name,
				'link' => $this->links->edit_term_translation_link( $term->term_id, $taxonomy, $post_type ),
			) );
		}

		wp_die( json_encode( $return ) );
	}


	/*
	 * get the language(s) to filter get_terms
	 *
	 * @since 1.7.6
	 *
	 * @param array $taxonomies queried taxonomies
	 * @param array $args get_terms arguments
	 * @return object|string|bool the language(s) to use in the filter, false otherwise
	 */
	protected function get_queried_language( $taxonomies, $args ) {
		// does nothing except on taxonomies which are filterable
		if ( ! $this->model->is_translated_taxonomy( $taxonomies ) ) {
			return false;
		}

		// if get_terms is queried with a 'lang' parameter
		if ( ! empty( $args['lang'] ) ) {
			return $args['lang'];
		}

		// do not filter 'get_terms_not_translated'
		if ( ! empty( $args['pll_get_terms_not_translated'] ) ) {
			return false;
		}

		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen(); // since WP 3.1, may not be available the first time(s) get_terms is called
		}

		// don't filter nav menus on nav menus screen
		if ( isset( $screen ) && 'nav-menus' == $screen->base && in_array( 'nav_menu', $taxonomies ) ) {
			return false;
		}

		// does nothing in Languages and dasboard admin panels
		if ( isset( $screen ) && in_array( $screen->base, array( 'toplevel_page_mlang', 'dashboard' ) ) ) {
			return false;
		}

		// ajax actions
		if ( isset( $_POST['action'] ) ) {
			// admin language filter for:
			// ajax paginate_links in taxonomies metabox in nav menus panel
			// and taxonomies menus items in customizer menus ( since WP 4.3 )
			if ( ! empty( $this->curlang ) && in_array( $_POST['action'], array( 'menu-get-metabox', 'load-available-menu-items-customizer' ) ) ) {
				return $this->curlang;
			}

			// The only ajax response I want to deal with is when changing the language in post metabox
			if ( ! in_array( $_POST['action'], array( 'post_lang_choice', 'term_lang_choice', 'get-tagcloud' ) ) ) {
				return false;
			}

			// I only want to filter the parent dropdown list when editing a term in a hierarchical taxonomy
			if ( 'term_lang_choice' == $_POST['action'] && ! ( isset( $args['class'] ) || isset( $args['unit'] ) ) ) {
				return false;
			}
		}

		// ajax response for changing the language in the post metabox ( or in the edit-tags panels )
		if ( isset( $_POST['lang'] ) ) {
			$lang = $this->model->get_language( $_POST['lang'] );
		}

		// the post ( or term ) is created with the 'add new' ( translation ) link
		// test of $args['page'] to avoid filtering the terms list table in edit-tags panel
		elseif ( ! empty( $_GET['new_lang'] ) && empty( $args['page'] ) ) {
			$lang = $this->model->get_language( $_GET['new_lang'] );
		}

		// FIXME can we simplify how we deal with the admin language filter?
		// the language filter selection has just changed
		// test $screen->base to avoid interference between the language filter and the post language selection and the category parent dropdown list
		elseif ( ! empty( $_GET['lang'] ) && ! ( isset( $screen ) && in_array( $screen->base, array( 'post', 'edit-tags', 'term' ) ) ) ) {
			if ( 'all' != $_GET['lang'] ) {
				$lang = $this->model->get_language( $_GET['lang'] );
			}
			elseif ( in_array( $screen->base, array( 'edit-tags', 'term' ) ) && isset( $args['class'] ) ) {
				$lang = $this->pref_lang; // parent dropdown
			}
		}

		// again the language filter
		elseif ( ! empty( $this->curlang ) && ( isset( $screen ) && 'post' != $screen->base && ! ( in_array( $screen->base, array( 'edit-tags', 'term' ) ) && isset( $args['class'] ) ) ) ) { // don't apply to post edit and the category parent dropdown list
		 	$lang = $this->curlang;
		}

		elseif ( isset( $_GET['post'] ) && is_numeric( $_GET['post'] ) ) { // is numeric avoids array of posts in *post* bulk edit
			$lang = $this->model->post->get_language( $_GET['post'] );
		}

		// for the parent dropdown list in edit term
		elseif ( isset( $_GET['tag_ID'] ) ) {
			$lang = $this->model->term->get_language( (int) $_GET['tag_ID'] );
		}

		// when a new category is created in the edit post panel
		elseif ( isset( $_POST['term_lang_choice'] ) ) {
			$lang = $this->model->get_language( $_POST['term_lang_choice'] );
		}

		// for a new post ( or the parent dropdown list of a new term )
		elseif ( isset( $screen ) && ( 'post' == $screen->base || ( in_array( $screen->base, array( 'edit-tags', 'term' ) ) && isset( $args['class'] ) ) ) ) {
			$lang = $this->pref_lang;
		}

		return empty( $lang ) ? false : $lang;
	}

	/*
	 * adds language dependent cache domain when querying terms
	 * useful as the 'lang' parameter is not included in cache key by WordPress
	 *
	 * @since 1.3
	 *
	 * @param array $args
	 * @param array $taxonomies
	 * @return array modified arguments
	 */
	public function get_terms_args( $args, $taxonomies ) {
		if ( $lang = $this->get_queried_language( $taxonomies, $args ) ) {
			$key = '_' . ( is_array( $lang ) ? implode( ',', $lang ) : $this->model->get_language( $lang )->slug );
			$args['cache_domain'] = empty( $args['cache_domain'] ) ? 'pll' . $key : $args['cache_domain'] . $key;
		}
		return $args;
	}

	/*
	 * filters categories and post tags by language(s) when needed on admin side
	 *
	 * @since 0.5
	 *
	 * @param array $clauses list of sql clauses
	 * @param array $taxonomies list of taxonomies
	 * @param array $args get_terms arguments
	 * @return array modified sql clauses
	 */
	public function terms_clauses( $clauses, $taxonomies, $args ) {
		$lang = $this->get_queried_language( $taxonomies, $args );
		return ! empty( $lang ) ? $this->model->terms_clauses( $clauses, $lang ) : $clauses; // adds our clauses to filter by current language
	}

	/*
	 * hack to avoid displaying delete link for the default category in all languages
	 * also returns the default category in the right language when called from wp_delete_term
	 *
	 * @since 1.2
	 *
	 * @param int $value
	 * @return int
	 */
	public function option_default_category( $value ) {
		$traces = debug_backtrace();

		if ( isset( $traces[4] ) ) {
			// FIXME 'column_name' for backward compatibility with WP < 4.3
			if ( in_array( $traces[4]['function'], array( 'column_cb', 'column_name', 'handle_row_actions' ) ) && in_array( $traces[4]['args'][0]->term_id, $this->model->term->get_translations( $value ) ) ) {
				return $traces[4]['args'][0]->term_id;
			}

			if ( 'wp_delete_term' == $traces[4]['function'] ) {
				return $this->model->term->get( $value, $this->model->term->get_language( $traces[4]['args'][0] ) );
			}
		}

		// filters the default category in note below the category list table and in settings->writing dropdown
		elseif ( false !== stripos( $traces[3]['file'], 'edit-tags.php' ) || false !== stripos( $traces[3]['file'], 'options-writing.php' ) ) {
			return $this->model->term->get( $value,  $this->pref_lang );
		}

		return $value;
	}

	/*
	 * checks if the new default category is translated in all languages
	 * if not, create the translations
	 *
	 * @since 1.7
	 *
	 * @param int $old_value
	 * @param int $value
	 */
	public function update_option_default_category( $old_value, $value ) {
		$default_cat_lang = $this->model->term->get_language( $value );

		// assign a default language to default category
		if ( ! $default_cat_lang ) {
			$default_cat_lang = $this->model->get_language( $this->options['default_lang'] );
			$this->model->term->set_language( (int) $value, $default_cat_lang );
		}

		foreach ( $this->model->get_languages_list() as $language ) {
			if ( $language->slug != $default_cat_lang->slug && ! $this->model->term->get_translation( $value, $language ) ) {
				$this->model->create_default_category( $language );
			}
		}
	}

	/*
	 * updates the translations term ids when splitting a shared term
	 * splits translations if these are shared terms too
	 *
	 * @since 1.7
	 *
	 * @param int $term_id shared term_id
	 * @param int $new_term_id
	 * @param int $term_taxonomy_id
	 * @param string $taxonomy
	 */
	public function split_shared_term( $term_id, $new_term_id, $term_taxonomy_id, $taxonomy ) {
		if ( ! $this->model->is_translated_taxonomy( $taxonomy ) ) {
			return;
		}

		// avoid recursion
		static $avoid_recursion = false;
		if ( $avoid_recursion ) {
			return;
		}

		$avoid_recursion = true;
		$lang = $this->model->term->get_language( $term_id );

		foreach ( $this->model->term->get_translations( $term_id ) as $key => $tr_id ) {
			if ( $lang->slug == $key ) {
				$translations[ $key ] = $new_term_id;
			}
			else {
				$tr_term = get_term( $tr_id, $taxonomy );
				$translations[ $key ] = _split_shared_term( $tr_id, $tr_term->term_taxonomy_id );

				// hack translation ids sent by the form to avoid overwrite in PLL_Admin_Filters_Term::save_translations
				if ( isset( $_POST['term_tr_lang'][ $key ] ) && $_POST['term_tr_lang'][ $key ] == $tr_id ) {
					$_POST['term_tr_lang'][ $key ] = $translations[ $key ];
				}
			}
			$this->model->term->set_language( $translations[ $key ], $key );
		}

		$this->model->term->save_translations( $new_term_id, $translations );
		$avoid_recursion = false;
	}
}
