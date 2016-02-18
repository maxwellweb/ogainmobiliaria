<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ); // since WP 3.1
}

/*
 * a class to create a table to list all settings modules
 *
 * @since 1.8
 */
class PLL_Table_Settings extends WP_List_Table {

	/*
	 * constructor
	 *
	 * @since 1.8
	 */
	function __construct() {
		parent::__construct( array(
			'plural'   => 'Settings', // do not translate ( used for css class )
			'ajax'	   => false,
		) );
	}

	/*
	 * get the table classes for styling
	 *
	 * @øince 1.8
	 */
	protected function get_table_classes() {
		return array( 'wp-list-table', 'widefat', 'plugins', 'pll-settings' ); // get the style of the plugins list table + one specific class
	}

	/*
	 * displays a single row
	 *
	 * @øince 1.8
	 *
	 * @param object $item PLL_Settings_Module object
	 */
	public function single_row( $item ) {
		// classes to reuse css from the plugins list table
		$classes = $item->is_active() ? 'active' : 'inactive';

		// display the columns
		printf( '<tr id="pll-module-%s" class="%s">', esc_attr( $item->module ), esc_attr( $classes ) );
		$this->single_row_columns( $item );
		echo '</tr>';

		// the settings if there are
		// "inactive" class to reuse css from the plugins list table
		if ( $form = $item->get_form() ) {
			printf( '
				<tr id="pll-configure-%s" class="pll-configure inactive inline-edit-row" style="display: none;">
					<td colspan="3">
						<legend>%s</legend>
						%s
						<p class="submit inline-edit-save">
							<button type="button" class="button button-secondary cancel">%s</button>
							<button type="button" class="button button-primary save">%s</button>
						</p>
					</td>
				</tr>',
				esc_attr( $item->module ), esc_html( $item->title ), $form, __( 'Cancel' ), __( 'Save Changes' )
			);
		}
	}

	/**
	 * Generates the columns for a single row of the table
	 *
	 * @since 1.8
	 *
	 * @param object $item The current item
	 */
	protected function single_row_columns( $item ) {
		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

		foreach ( $columns as $column_name => $column_display_name ) {
			$classes = "$column_name column-$column_name";
			if ( $primary === $column_name ) {
				$classes .= ' column-primary';
			}

			if ( in_array( $column_name, $hidden ) ) {
				$classes .= ' hidden';
			}

			if ( 'cb' == $column_name ) {
				echo '<th scope="row" class="check-column">';
				echo $this->column_cb( $item );
				echo '</th>';
			}
			else {
				printf( '<td class="%s">', esc_attr( $classes ) );
				echo $this->column_default( $item, $column_name );
				echo '</td>';
			}
		}
	}

	/*
	 * displays the item information in a column ( default case )
	 *
	 * @since 1.8
	 *
	 * @param object $item
	 * @param string $column_name
	 * @return string
	 */
	protected function column_default( $item, $column_name ) {
		if ( 'plugin-title' == $column_name ) {
			return sprintf( '<strong>%s</strong>', esc_html( $item->title ) ) . $this->row_actions( $item->get_action_links(), true /*always visible*/ );
		}
		return $item->$column_name;
	}

	/*
	 * gets the list of columns
	 *
	 * @since 1.8
	 *
	 * @return array the list of column titles
	 */
	public function get_columns() {
		return array(
			'cb'           => '', // for the 4px border inherited from plugins when the module is activated
			'plugin-title' => __( 'Module', 'polylang' ), // plugin-title for styling
			'description'  => __( 'Description', 'polylang' ),
		);
	}

	/**
	 * Gets the name of the primary column.
	 *
	 * @since 1.8
	 *
	 * @return string The name of the primary column.
	 */
	protected function get_primary_column_name() {
		return 'plugin-title';
	}

	/*
	 * prepares the list of items for displaying
	 *
	 * @since 1.8
	 *
	 * @param array $items
	 */
	public function prepare_items( $items = array() ) {
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns(), $this->get_primary_column_name() );
		$this->items = $items;
	}
}
