<?php

/*
 * manages compatibility with 3rd party plugins ( and themes )
 * this class is available as soon as the plugin is loaded
 *
 * @since 1.0
 */
class PLL_Plugins_Compat {
	static protected $instance; // for singleton

	/*
	 * constructor
	 *
	 * @since 1.0
	 */
	protected function __construct() {
		// WordPress Importer
		add_action( 'init', array( &$this, 'maybe_wordpress_importer' ) );
		add_filter( 'wp_import_terms', array( &$this, 'wp_import_terms' ) );

		// YARPP
		add_action( 'pll_init', array( &$this, 'yarpp_init' ) );

		// Yoast SEO
		add_action( 'pll_language_defined', array( &$this, 'wpseo_init' ) );

		// Custom field template
		add_action( 'add_meta_boxes', array( &$this, 'cft_copy' ), 10, 2 );

		// Aqua Resizer
		add_filter( 'pll_home_url_black_list', array( &$this, 'aq_home_url_black_list' ) );

		// Twenty Fourteen
		if ( 'twentyfourteen' == get_template() ) {
			add_filter( 'transient_featured_content_ids', array( &$this, 'twenty_fourteen_featured_content_ids' ) );
			add_filter( 'option_featured-content', array( &$this, 'twenty_fourteen_option_featured_content' ) );
		}

		// Duplicate post
		add_filter( 'option_duplicate_post_taxonomies_blacklist' , array( &$this, 'duplicate_post_taxonomies_blacklist' ) );

		// Jetpack 3
		add_action( 'jetpack_widget_get_top_posts', array( &$this, 'jetpack_widget_get_top_posts' ), 10, 3 );
		add_filter( 'grunion_contact_form_field_html', array( &$this, 'grunion_contact_form_field_html_filter' ), 10, 3 );
		add_filter( 'jetpack_open_graph_tags', array( &$this, 'jetpack_ogp' ) );
		add_filter( 'jetpack_relatedposts_filter_filters', array( &$this, 'jetpack_relatedposts_filter_filters' ), 10, 2 );

		// Jetpack infinite scroll
		if ( !defined( 'PLL_AJAX_ON_FRONT' ) && isset( $_GET['infinity'], $_POST['action'] ) && 'infinite_scroll' == $_POST['action'] ) {
			define( 'PLL_AJAX_ON_FRONT', true );
		}
	}

	/*
	 * access to the single instance of the class
	 *
	 * @since 1.7
	 *
	 * @return object
	 */
	static public function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/*
	 * WordPress Importer
	 * if WordPress Importer is active, replace the wordpress_importer_init function
	 *
	 * @since 1.2
	 */
	function maybe_wordpress_importer() {
		if ( defined( 'WP_LOAD_IMPORTERS' ) && class_exists( 'WP_Import' ) ) {
			remove_action( 'admin_init', 'wordpress_importer_init' );
			add_action( 'admin_init', array( &$this, 'wordpress_importer_init' ) );
		}
	}

	/*
	 * WordPress Importer
	 * loads our child class PLL_WP_Import instead of WP_Import
	 *
	 * @since 1.2
	 */
	function wordpress_importer_init() {
		$class = new ReflectionClass( 'WP_Import' );
		load_plugin_textdomain( 'wordpress-importer', false, basename( dirname( $class->getFileName() ) ) . '/languages' );

		$GLOBALS['wp_import'] = new PLL_WP_Import();
		register_importer( 'wordpress', 'WordPress', __( 'Import <strong>posts, pages, comments, custom fields, categories, and tags</strong> from a WordPress export file.', 'wordpress-importer' ), array( $GLOBALS['wp_import'], 'dispatch' ) );
	}

	/*
	 * WordPress Importer
	 * Backward Compatibility Polylang < 1.8
	 * sets the flag when importing a language and the file has been exported with Polylang < 1.8
	 *
	 * @since 1.8
	 *
	 * @param array $terms an array of arrays containing terms information form the WXR file
	 * @return array
	 */
	function wp_import_terms( $terms ) {
		include( PLL_SETTINGS_INC . '/languages.php' );

		foreach ( $terms as $key => $term ) {
			if ( 'language' === $term['term_taxonomy'] ) {
				$description = maybe_unserialize( $term['term_description'] );
				if ( empty( $description['flag_code'] ) && isset( $languages[ $description['locale'] ] ) ) {
					$description['flag_code'] = $languages[ $description['locale'] ][4];
					$terms[ $key ]['term_description'] = serialize( $description );
				}
			}
		}
		return $terms;
	}

	/*
	 * YARPP
	 * just makes YARPP aware of the language taxonomy ( after Polylang registered it )
	 *
	 * @since 1.0
	 */
	public function yarpp_init() {
		$GLOBALS['wp_taxonomies']['language']->yarpp_support = 1;
	}

	/*
	 * Yoast SEO
	 * translate options
	 * add specific filters and actions
	 *
	 * @since 1.6.4
	 */
	public function wpseo_init() {
		if ( ! defined( 'WPSEO_VERSION' ) || PLL_ADMIN ) {
			return;
		}

		// reloads options once the language has been defined to enable translations
		// useful only when the language is set from content
		if ( did_action( 'wp_loaded' ) ) {
			if ( version_compare( WPSEO_VERSION, '1.7.2', '<' ) ) {
				global $wpseo_front;
			}
			else {
				$wpseo_front = WPSEO_Frontend::get_instance();
			}

			$options = version_compare( WPSEO_VERSION, '1.5', '<' ) ? get_wpseo_options_arr() : WPSEO_Options::get_option_names();
			foreach ( $options as $opt ) {
				$wpseo_front->options = array_merge( $wpseo_front->options, (array) get_option( $opt ) );
			}
		}

		// one sitemap per language when using multiple domains or subdomains
		// because WPSEO does not accept several domains or subdomains in one sitemap
		if ( PLL()->options['force_lang'] > 1 ) {
			add_filter( 'wpseo_enable_xml_sitemap_transient_caching', '__return_false' ); // disable cache! otherwise WPSEO keeps only one domain (thanks to Junaid Bhura)
			add_filter( 'home_url', array( &$this, 'wpseo_home_url' ) , 10, 2 ); // fix home_url
			add_filter( 'wpseo_posts_join', array( &$this, 'wpseo_posts_join' ), 10, 2 );
			add_filter( 'wpseo_posts_where', array( &$this, 'wpseo_posts_where' ), 10, 2 );
			add_filter( 'wpseo_typecount_join', array( &$this, 'wpseo_posts_join' ), 10, 2 );
			add_filter( 'wpseo_typecount_where', array( &$this, 'wpseo_posts_where' ), 10, 2 );
		}

		// one sitemap for all languages when the language is set from the content or directory name
		else {
			add_filter( 'get_terms_args', array( &$this, 'wpseo_remove_terms_filter' ) );
		}

		add_filter( 'pll_home_url_white_list', array( &$this, 'wpseo_home_url_white_list' ) );
		add_action( 'wpseo_opengraph', array( &$this, 'wpseo_ogp' ), 2 );
		add_filter( 'wpseo_canonical', array( &$this, 'wpseo_canonical' ) );
	}

	/*
	 * Yoast SEO
	 * fixes the home url as well as the stylesheet url
	 * only when using multiple domains or subdomains
	 *
	 * @since 1.6.4
	 *
	 * @param string $url
	 * @return $url
	 */
	public function wpseo_home_url( $url, $path ) {
		$uri = empty( $path ) ? ltrim( $_SERVER['REQUEST_URI'], '/' ) : $path;

		if ( 'sitemap_index.xml' === $uri || preg_match( '#([^/]+?)-sitemap([0-9]+)?\.xml|([a-z]+)?-?sitemap\.xsl#', $uri ) ) {
			$url = PLL()->links_model->switch_language_in_link( $url, PLL()->curlang );
		}

		return $url;
	}

	/*
	 * Yoast SEO
	 * modifies the sql request for posts sitemaps
	 * only when using multiple domains or subdomains
	 *
	 * @since 1.6.4
	 *
	 * @param string $sql join clause
	 * @param string $post_type
	 * @return string
	 */
	public function wpseo_posts_join( $sql, $post_type ) {
		return pll_is_translated_post_type( $post_type ) ? $sql. PLL()->model->post->join_clause() : $sql;
	}

	/*
	 * Yoast SEO
	 * modifies the sql request for posts sitemaps
	 * only when using multiple domains or subdomains
	 *
	 * @since 1.6.4
	 *
	 * @param string $sql where clause
	 * @param string $post_type
	 * @return string
	 */
	public function wpseo_posts_where( $sql, $post_type ) {
		return pll_is_translated_post_type( $post_type ) ? $sql . PLL()->model->post->where_clause( PLL()->curlang ) : $sql;
	}

	/*
	 * Yoast SEO
	 * removes the language filter for the taxonomy sitemaps
	 * only when the language is set from the content or directory name
	 *
	 * @since 1.0.3
	 *
	 * @param array $args get_terms arguments
	 * @return array modified list of arguments
	 */
	public function wpseo_remove_terms_filter( $args ) {
		if ( isset( $GLOBALS['wp_query']->query['sitemap'] ) ) {
			$args['lang'] = 0;
		}
		return $args;
	}

	/*
	 * Yoast SEO
	 *
	 * @since 1.1.2
	 *
	 * @param array $arr
	 * @return array
	 */
	public function wpseo_home_url_white_list( $arr ) {
		return array_merge( $arr, array( array( 'file' => 'wordpress-seo' ) ) );
	}

	/*
	 * Yoast SEO
	 * adds opengraph support for translations
	 *
	 * @since 1.6
	 */
	public function wpseo_ogp() {
		global $wpseo_og;

		// WPSEO already deals with the locale
		if ( did_action( 'pll_init' ) && method_exists( $wpseo_og, 'og_tag' ) ) {
			foreach ( PLL()->model->get_languages_list() as $language ) {
				if ( $language->slug != PLL()->curlang->slug && PLL()->links->get_translation_url( $language ) && $fb_locale = self::get_fb_locale( $language ) ) {
					$wpseo_og->og_tag( 'og:locale:alternate', $fb_locale );
				}
			}
		}
	}

	/*
	 * Yoast SEO
	 * fixes the canonical front page url as unlike WP, WPSEO does not add a trailing slash to the canonical front page url
	 *
	 * @since 1.7.10
	 *
	 * @param string $url
	 * @return $url
	 */
	public function wpseo_canonical( $url ) {
		return is_front_page( $url ) && get_option( 'permalink_structure' ) ? trailingslashit( $url ) : $url;
	}

	/*
	 * Aqua Resizer
	 *
	 * @since 1.1.5
	 *
	 * @param array $arr
	 * @return array
	 */
	public function aq_home_url_black_list( $arr ) {
		return array_merge( $arr, array( array( 'function' => 'aq_resize' ) ) );
	}

	/*
	 * Custom field template
	 * Custom field template does check $_REQUEST['post'] to populate the custom fields values
	 *
	 * @since 1.0.2
	 *
	 * @param string $post_type unused
	 * @param object $post current post object
	 */
	public function cft_copy( $post_type, $post ) {
		global $custom_field_template;
		if ( isset( $custom_field_template, $_REQUEST['from_post'], $_REQUEST['new_lang'] ) && ! empty( $post ) ) {
			$_REQUEST['post'] = $post->ID;
		}
	}

	/*
	 * Twenty Fourteen
	 * rewrites the function Featured_Content::get_featured_post_ids()
	 *
	 * @since 1.4
	 *
	 * @param array $ids featured posts ids
	 * @return array modified featured posts ids ( include all languages )
	 */
	public function twenty_fourteen_featured_content_ids( $featured_ids ) {
		if ( ! did_action( 'pll_init' ) || false !== $featured_ids ) {
			return $featured_ids;
		}

		$settings = Featured_Content::get_setting();

		if ( ! $term = get_term_by( 'name', $settings['tag-name'], 'post_tag' ) ) {
			return $featured_ids;
		}

		// get featured tag translations
		$tags = PLL()->model->term->get_translations( $term->term_id );
		$ids = array();

		// Query for featured posts in all languages
		// one query per language to get the correct number of posts per language
		foreach ( $tags as $tag ) {
			$_ids = get_posts( array(
				'lang'        => 0, // avoid language filters
				'fields'      => 'ids',
				'numberposts' => Featured_Content::$max_posts,
				'tax_query'   => array( array(
					'taxonomy' => 'post_tag',
					'terms'    => (int) $tag,
				) ),
			) );

			$ids = array_merge( $ids, $_ids );
		}

		$ids = array_map( 'absint', $ids );
		set_transient( 'featured_content_ids', $ids );

		return $ids;
	}

	/*
	 * Twenty Fourteen
	 * translates the featured tag id in featured content settings
	 * mainly to allow hiding it when requested in featured content options
	 * acts only on frontend
	 *
	 * @since 1.4
	 *
	 * @param array $settings featured content settings
	 * @return array modified $settings
	 */
	public function twenty_fourteen_option_featured_content( $settings ) {
		if ( ! PLL_ADMIN && $settings['tag-id'] && $tr = pll_get_term( $settings['tag-id'] ) ) {
			$settings['tag-id'] = $tr;
		}

		return $settings;
	}

	/*
	 * Duplicate Post
	 * Avoid duplicating the 'post_translations' taxonomy
	 *
	 * @since 1.8
	 *
	 * @param array $taxonomies
	 * @return array
	 */
	function duplicate_post_taxonomies_blacklist( $taxonomies ) {
		$taxonomies[] = 'post_translations';
		return $taxonomies;
	}

	/*
	 * Jetpack
	 * adapted from the same function in jetpack-3.0.2/3rd-party/wpml.php
	 *
	 * @since 1.5.4
	 */
	public function jetpack_widget_get_top_posts( $posts, $post_ids, $count ) {
		foreach ( $posts as $k => $post ) {
			if ( pll_current_language() !== pll_get_post_language( $post['post_id'] ) ) {
				unset( $posts[ $k ] );
			}
		}

		return $posts;
	}

	/*
	 * Jetpack
	 * adapted from the same function in jetpack-3.0.2/3rd-party/wpml.php
	 * keeps using 'icl_translate' as the function registers the string
	 *
	 * @since 1.5.4
	 */
	public function grunion_contact_form_field_html_filter( $r, $field_label, $id ) {
		if ( function_exists( 'icl_translate' ) ) {
			if ( pll_current_language() !== pll_default_language() ) {
				$label_translation = icl_translate( 'jetpack ', $field_label . '_label', $field_label );
				$r = str_replace( $field_label, $label_translation, $r );
			}
		}

		return $r;
	}

	/*
	 * Jetpack
	 * adds opengraph support for locale and translations
	 *
	 * @since 1.6
	 *
	 * @param array $tags opengraph tags to output
	 * @return array
	 */
	public function jetpack_ogp( $tags ) {
		if ( did_action( 'pll_init' ) ) {
			foreach ( PLL()->model->get_languages_list() as $language ) {
				if ( $language->slug != PLL()->curlang->slug && PLL()->links->get_translation_url( $language ) && $fb_locale = self::get_fb_locale( $language ) ) {
					$tags['og:locale:alternate'][] = $fb_locale;
				}
				if ( $language->slug == PLL()->curlang->slug && $fb_locale = self::get_fb_locale( $language ) ) {
					$tags['og:locale'] = $fb_locale;
				}
			}
		}
		return $tags;
	}

	/*
	 * Jetpack
	 * Allows to make sure that related posts are in the correct language
	 *
	 * @since 1.8
	 *
	 * @param array $filters Array of ElasticSearch filters based on the post_id and args.
	 * @param string $post_id Post ID of the post for which we are retrieving Related Posts.
	 * @return array
	 */
	function jetpack_relatedposts_filter_filters( $filters, $post_id ) {
		$slug = sanitize_title( pll_get_post_language( $post_id, 'name' ) );
		$filters[] = array( 'term' => array('taxonomy.language.slug' =>  $slug ) );
		return $filters;
	}

	/*
	 * Correspondance between WordPress locales and Facebook locales
	 * @see https://translate.wordpress.org/
	 * @see https://www.facebook.com/translations/FacebookLocales.xml
	 *
	 * @since 1.8.1 Update the list of locales
	 * @since 1.6
	 *
	 * @param object $language
	 * @return bool|string Facebook locale, false if no correspondance found
	 */
	static public function get_fb_locale( $language ) {
		static $facebook_locales = array(
			'af'           => 'af_ZA',
			'ak'           => 'ak_GH',
			'am'           => 'am_ET',
			'ar'           => 'ar_AR',
			'arq'          => 'ar_AR',
			'ary'          => 'ar_AR',
			'as'           => 'as_IN',
			'az'           => 'az_AZ',
			'bel'          => 'be_BY',
			'bg_BG'        => 'bg_BG',
			'bn_BD'        => 'bn_IN',
			'bre'          => 'br_FR',
			'bs_BA'        => 'bs_BA',
			'ca'           => 'ca_ES',
			'ceb'          => 'cx_PH',
			'ckb'          => 'cb_IQ',
			'co'           => 'co_FR',
			'cs_CZ'        => 'cs_CZ',
			'cy'           => 'cy_GB',
			'da_DK'        => 'da_DK',
			'de_CH'        => 'de_DE',
			'de_DE'        => 'de_DE',
			'de_DE_formal' => 'de_DE',
			'el'           => 'el_GR',
			'en_AU'        => 'en_US',
			'en_CA'        => 'en_US',
			'en_GB'        => 'en_GB',
			'en_NZ'        => 'en_US',
			'en_US'        => 'en_US',
			'en_ZA'        => 'en_US',
			'eo'           => 'eo_EO',
			'es_AR'        => 'es_LA',
			'es_CL'        => 'es_CL',
			'es_CO'        => 'es_CO',
			'es_MX'        => 'es_MX',
			'es_PE'        => 'es_LA',
			'es_ES'        => 'es_ES',
			'es_VE'        => 'es_VE',
			'et'           => 'et_EE',
			'eu'           => 'eu_ES',
			'fa_IR'        => 'fa_IR',
			'fi'           => 'fi_FI',
			'fo'           => 'fo_FO',
			'fr_CA'        => 'fr_CA',
			'fr_FR'        => 'fr_FR',
			'fuc'          => 'ff_NG',
			'fy'           => 'fy_NL',
			'ga'           => 'ga_IE',
			'gl_ES'        => 'gl_ES',
			'gn'           => 'gn_PY',
			'gu'           => 'gu_IN',
			'he_IL'        => 'he_IL',
			'hi_IN'        => 'hi_IN',
			'hr'           => 'hr_HR',
			'hu_HU'        => 'hu_HU',
			'hy'           => 'hy_AM',
			'id_ID'        => 'id_ID',
			'is_IS'        => 'is_IS',
			'it_IT'        => 'it_IT',
			'ja'           => 'ja_JP',
			'jv_ID'        => 'jv_ID',
			'ka_GE'        => 'ka_GE',
			'kin'          => 'rw_RW',
			'kk'           => 'kk_KZ',
			'km'           => 'km_kH',
			'kn'           => 'kn_IN',
			'ko_KR'        => 'ko_KR',
			'ku'           => 'ku_TR',
			'ky_KY'        => 'ky_KG',
			'la'           => 'la_Va',
			'li'           => 'li_NL',
			'lin'          => 'ln_CD',
			'lo'           => 'lo_LA',
			'lt_LT'        => 'lt_LT',
			'lv'           => 'lv_LV',
			'mg_MG'        => 'mg_MG',
			'mk_MK'        => 'mk_MK',
			'ml_IN'        => 'ml_IN',
			'mn'           => 'mn_MN',
			'mr'           => 'mr_IN',
			'mri'          => 'mi_NZ',
			'ms_MY'        => 'ms_MY',
			'my_MM'        => 'my_MM',
			'ne_NP'        => 'ne_NP',
			'nb_NO'        => 'nb_NO',
			'nl_BE'        => 'nl_BE',
			'nl_NL'        => 'nl_NL',
			'nn_NO'        => 'nn_NO',
			'ory'          => 'or_IN',
			'pa_IN'        => 'pa_IN',
			'pl_PL'        => 'pl_PL',
			'ps'           => 'ps_AF',
			'pt_BR'        => 'pt_BR',
			'pt_PT'        => 'pt_PT',
			'ps'           => 'ps_AF',
			'ro_RO'        => 'ro_RO',
			'roh'          => 'rm_CH',
			'ru_RU'        => 'ru_RU',
			'sa_IN'        => 'sa_IN',
			'si_LK'        => 'si_LK',
			'sk_SK'        => 'sk_SK',
			'sl_SI'        => 'sl_SI',
			'so_SO'        => 'so_SO',
			'sq'           => 'sq_AL',
			'sr_RS'        => 'sr_RS',
			'srd'          => 'sc_IT',
			'sv_SE'        => 'sv_SE',
			'sw'           => 'sw_KE',
			'szl'          => 'sz_PL',
			'ta_LK'        => 'ta_IN',
			'ta_IN'        => 'ta_IN',
			'te'           => 'te_IN',
			'tg'           => 'tg_TJ',
			'th'           => 'th_TH',
			'tl'           => 'tl_PH',
			'tuk'          => 'tk_TM',
			'tr_TR'        => 'tr_TR',
			'tt_RU'        => 'tt_RU',
			'tzm'          => 'tz_MA',
			'uk'           => 'uk_UA',
			'ur'           => 'ur_PK',
			'uz_UZ'        => 'uz_UZ',
			'vi'           => 'vi_VN',
			'yor'          => 'yo_NG',
			'zh_CN'        => 'zh_CN',
			'zh_HK'        => 'zh_HK',
			'zh_TW'        => 'zh_TW',
		);

		return isset( $facebook_locales[ $language->locale ] ) ? $facebook_locales[ $language->locale ] : false;
	}
}
