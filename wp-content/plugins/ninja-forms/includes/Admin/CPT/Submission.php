<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Admin_CPT_Submission
 */
class NF_Admin_CPT_Submission
{
    protected $cpt_slug = 'nf_sub';
    /**
     * Constructor
     */
    public function __construct()
    {
        // Register our submission custom post type.
        add_action( 'init', array( $this, 'custom_post_type' ), 5 );

        // Filter Post Row Actions
        add_filter( 'post_row_actions', array( $this, 'post_row_actions' ) );

        // Change our submission columns.
        add_filter( 'manage_nf_sub_posts_columns', array( $this, 'change_columns' ) );

        // Add the appropriate data for our custom columns.
        add_action( 'manage_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

        // Save our metabox values
        add_action( 'save_post', array( $this, 'save_nf_sub' ), 10, 2 );

        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
        add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ) );

    }

    /**
     * Custom Post Type
     */
    function custom_post_type() {

        $labels = array(
            'name'                => _x( 'Submissions', 'Post Type General Name', 'ninja_forms' ),
            'singular_name'       => _x( 'Submission', 'Post Type Singular Name', 'ninja_forms' ),
            'menu_name'           => __( 'Submissions', 'ninja_forms' ),
            'name_admin_bar'      => __( 'Submissions', 'ninja_forms' ),
            'parent_item_colon'   => __( 'Parent Item:', 'ninja_forms' ),
            'all_items'           => __( 'All Items', 'ninja_forms' ),
            'add_new_item'        => __( 'Add New Item', 'ninja_forms' ),
            'add_new'             => __( 'Add New', 'ninja_forms' ),
            'new_item'            => __( 'New Item', 'ninja_forms' ),
            'edit_item'           => __( 'Edit Item', 'ninja_forms' ),
            'update_item'         => __( 'Update Item', 'ninja_forms' ),
            'view_item'           => __( 'View Item', 'ninja_forms' ),
            'search_items'        => __( 'Search Item', 'ninja_forms' ),
            'not_found'           => $this->not_found_message(),
            'not_found_in_trash'  => __( 'Not found in Trash', 'ninja_forms' ),
        );
        $args = array(
            'label'               => __( 'Submission', 'ninja_forms' ),
            'description'         => __( 'Form Submissions', 'ninja_forms' ),
            'labels'              => $labels,
            'supports'            => false,
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'menu_position'       => 5,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
        );
        register_post_type( $this->cpt_slug, $args );
    }

    public function post_row_actions( $actions )
    {
        if( $this->cpt_slug == get_post_type() ){
            unset( $actions[ 'view' ] );
            unset( $actions[ 'inline hide-if-no-js' ] );
        }
        return $actions;
    }

    public function change_columns( $columns )
    {
        if( ! isset( $_GET[ 'form_id' ] ) ) return $columns;

        $columns = array(
            'cb'    => '<input type="checkbox" />',
            'id' => __( '#', 'ninja-forms' ),
        );

        $form_id = absint( $_GET[ 'form_id' ] );

        $fields = Ninja_Forms()->form( $form_id )->get_fields();

        foreach( $fields as $field ) {

            $hidden_field_types = apply_filters( 'nf_sub_hidden_field_types', array() );
            if( in_array( $field->get_setting( 'type' ), array_values( $hidden_field_types ) ) ) continue;

            $id = $field->get_id();
            $label = $field->get_setting( 'label' );
            $admin_label = $field->get_setting( 'admin_label' );

            $columns[ $id ] = ( $admin_label ) ? $admin_label : $label;
        }

        $columns['sub_date'] = __( 'Date', 'ninja-forms' );

        return $columns;
    }

    public function custom_columns( $column, $sub_id )
    {
        $sub = Ninja_Forms()->form()->get_sub( $sub_id );

        if( 'id' == $column ) {
            echo $sub->get_seq_num();
        }

        if( is_numeric( $column ) ){
            $value = $sub->get_field_value( $column );
            $field = Ninja_Forms()->form()->get_field( $column );
            echo apply_filters( 'ninja_forms_custom_columns', $value, $field );
        }

    }

    public function save_nf_sub( $nf_sub_id, $nf_sub )
    {
        global $pagenow;

        if ( ! isset ( $_POST['nf_edit_sub'] ) || $_POST['nf_edit_sub'] != 1 )
            return $nf_sub_id;

        // verify if this is an auto save routine.
        // If it is our form has not been submitted, so we dont want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return $nf_sub_id;

        if ( $pagenow != 'post.php' )
            return $nf_sub_id;

        if ( $nf_sub->post_type != 'nf_sub' )
            return $nf_sub_id;

        /* Get the post type object. */
        $post_type = get_post_type_object( $nf_sub->post_type );

        /* Check if the current user has permission to edit the post. */
        if ( !current_user_can( $post_type->cap->edit_post, $nf_sub_id ) )
            return $nf_sub_id;

        $sub = Ninja_Forms()->form()->sub( $nf_sub_id )->get();

        foreach ( $_POST['fields'] as $field_id => $user_value ) {
            $user_value = apply_filters( 'nf_edit_sub_user_value', $user_value, $field_id, $nf_sub_id );
            $sub->update_field_value( $field_id, $user_value );
        }

        $sub->save();

        set_transient( 'nf_sub_edit_ref', esc_url_raw( $_REQUEST['ref'] ) );
    }

    /**
     * Meta Boxes
     */
    public function add_meta_boxes( $post_type, $post )
    {
        add_meta_box(
            'nf_sub_fields',
            __( 'User Submitted Values', 'ninja-forms' ),
            array( $this, 'fields_meta_box' ),
            'nf_sub',
            'normal',
            'default'
        );

        add_meta_box(
            'nf_sub_info',
            __( 'Submission Info', 'ninja-forms' ),
            array( $this, 'info_meta_box' ),
            'nf_sub',
            'side',
            'default'
        );
    }

    /**
     * Fields Meta Box
     *
     * @param $post
     */
    public function fields_meta_box( $post )
    {
        $form_id = get_post_meta( $post->ID, '_form_id', TRUE );

        $sub = Ninja_Forms()->form()->get_sub( $post->ID );

        $fields = Ninja_Forms()->form( $form_id )->get_fields();

        $hidden_field_types = apply_filters( 'nf_sub_hidden_field_types', array() );

        Ninja_Forms::template( 'admin-metabox-sub-fields.html.php', compact( 'fields', 'sub', 'hidden_field_types' ) );
    }

    /**
     * Info Meta Box
     *
     * @param $post
     */
    public function info_meta_box( $post )
    {
        $sub = Ninja_Forms()->form()->sub( $post->ID )->get();

        $seq_num = $sub->get_seq_num();

        $status = ucwords( $sub->get_status() );

        $user = $sub->get_user()->data->user_nicename;

        $form_title = $sub->get_form_title();

        $sub_date = $sub->get_sub_date( 'm/d/Y H:i' );

        $mod_date = $sub->get_mod_date( 'm/d/Y H:i' );

        Ninja_Forms::template( 'admin-metabox-sub-info.html.php', compact( 'seq_num', 'status', 'user', 'form_title', 'sub_date', 'mod_date' ) );
    }

    /**
     * Remove Meta Boxes
     */
    public function remove_meta_boxes()
    {
        // Remove the default Publish metabox
        remove_meta_box( 'submitdiv', 'nf_sub', 'side' );
    }

    /*
     * PRIVATE METHODS
     */

    private function not_found_message()
    {
        if ( ! isset ( $_REQUEST['form_id'] ) || empty( $_REQUEST['form_id'] ) ) {
            return __( 'Please select a form to view submissions', 'ninja-forms' );
        } else {
            return __( 'No Submissions Found', 'ninja-forms' );
        }
    }

}

