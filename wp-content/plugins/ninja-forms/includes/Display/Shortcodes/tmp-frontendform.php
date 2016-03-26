<?php

function nf_tmp_frontendform( $atts = array() ) {
	$form_id = $atts['id'];

	wp_enqueue_script( 'backbone-marionette', Ninja_Forms::$url . 'assets/js/lib/backbone.marionette.min.js', array( 'jquery', 'backbone' ) );
	wp_enqueue_script( 'backbone-radio', Ninja_Forms::$url . 'assets/js/lib/backbone.radio.min.js', array( 'jquery', 'backbone' ) );

    wp_enqueue_script( 'requirejs', Ninja_Forms::$url . 'assets/js/lib/require.js', array( 'jquery', 'backbone' ) );
	wp_enqueue_script( 'nf-front-end', Ninja_Forms::$url . 'assets/js/front-end/main.js', array( 'jquery', 'backbone', 'requirejs', 'jquery-form' ) );

	wp_localize_script( 'nf-front-end', 'nfFrontEnd', array( 'ajaxNonce' => wp_create_nonce( 'ninja_forms_ajax_nonce' ), 'adminAjax' => admin_url( 'admin-ajax.php' ), 'requireBaseUrl' => Ninja_Forms::$url . 'assets/js/' ) );

	switch( $form_id ) {
		case 1:
			$title = 'Contact Me';
			$fields = array(
				array(
					'id'			=> 1,
					'type' 			=> 'text',
					'label'			=> 'Name',
					'label_pos' 	=> 'before',
					'required'		=> 1,
				),
				array(
					'id'			=> 8,
					'type' 			=> 'email',
					'label'			=> 'Email',
					'label_pos' 	=> 'before',
					'required'		=> 1,
				),
				array(
					'id'			=> 11,
					'type' 			=> 'text',
					'label'			=> 'Confirm Email',
					'label_pos' 	=> 'before',
					'confirm_field'	=> 8,
					'required'		=> 1,
				),
				array(
					'id'			=> 13,
					'type' 			=> 'file',
					'label'			=> 'Attachment',
					'button_label'	=> 'Upload',
					'label_pos' 	=> 'before',
					'required'		=> 0,
				),
				array(
					'id'			=> 2,
					'type' 			=> 'textarea',
					'label'			=> 'Message',
					'label_pos'		=> 'before',
					'required'		=> 1,
				),
				array(
					'id'			=> 10,
					'type' 			=> 'text',
					'label'			=> 'Mirror Name',
					'label_pos' 	=> 'before',
					'mirror_field'	=> 1
				),
				array(
					'id'			=> 3,
					'type'			=> 'submit',
					'label'			=> 'Go!',
				),
			);
			break;
		case 2:
			$title = 'Get Help';
			$fields = array(
				array(
					'id'			=> 4,
					'type' 			=> 'text',
					'label'			=> 'Name',
					'label_pos' 	=> 'before',
					'required'		=> 1,
				),
				array(
					'id'			=> 12,
					'type'			=> 'email',
					'label'			=> 'Email',
					'label_pos'		=> 'before',
					'required'		=> 1,
				),
				array(
					'id'			=> 5,
					'type' 			=> 'textarea',
					'label'			=> 'What Can We Help You With?',
					'label_pos'		=> 'before',
					'required'		=> 1,
				),
				array(
					'id'			=> 6,
					'type' 			=> 'checkbox',
					'label'			=> 'Agree?',
					'label_pos'		=> 'after',
					'required'		=> 1,
				),
				array(
					'id'			=> 9,
					'type' 			=> 'radio',
					'label'			=> 'Best Contact Method?',
					'label_pos'		=> 'before',
					'options'		=> array(
						array(
							'label'	=> 'Phone',
							'value'	=> 'phone',
						),
						array(
							'label'	=> 'Email',
							'value'	=> 'email',
						),
						array(
							'label'	=> 'Snail Mail',
							'value'	=> 'snail-mail',
						),
					),
					'show_other'	=> 1,
					'required'		=> 1,
				),
				array(
					'id'				=> 7,
					'type'				=> 'submit',
					'label'			=> 'Send',
				),
			);
			break;
	}

	$form = array(
		array(
			'id' 						=> $form_id,
			'settings'					=> array(
				'title' 				=> $title,
			),
			// 'beforeForm' 			=> 'Before Form ' . $form_id,
			// 'afterForm' 				=> 'After Form ' . $form_id,
			// 'beforeFields' 			=> 'Before Fields ' . $form_id,
			// 'afterFields' 			=> 'After Fields ' . $form_id,
			'fields'					=> $fields,
		)
	);

	?>
	<script type="text/javascript">
		var nfForms = nfForms || [];
		nfForms.push( <?php echo wp_json_encode( $form ); ?> );
	</script>

	<?php

    nf_tmp_output_templates();

    $html = '<div id="nf-form-' . $form_id . '-cont"></div>';

    return $html;
}

add_shortcode( 'nf_tmp_frontendform', 'nf_tmp_frontendform' );

function nf_tmp_output_templates() {
	?>

	<style>
		.nf-error input {
			border-color: red;
		}

		.nf-error-msg {
			color:red;
		}

		.nf-element.disabled {
			opacity: 0.3;
		}

		.nf-pass .nf-element {
			/*border: 2px solid green;*/
	  		background: url( 'https://cdn0.iconfinder.com/data/icons/round-ui-icons/512/tick_green.png' ) no-repeat;
			background-position: 99.5% 60%;
	  		background-size: 35px 35px;

	  		/*background: url( 'https://cdn0.iconfinder.com/data/icons/iconsweets2/40/email_envelope.png' ) 0 no-repeat;*/
		}

		.nf-fail .nf-element {
			/*border: 2px solid red;*/
	  		background: url( 'https://cdn1.iconfinder.com/data/icons/toolbar-signs/512/cancel-512.png' ) no-repeat;
	  		background-position: 99% 60%;
	  		background-size: 30px 30px;
	  		/*background: url( 'https://cdn0.iconfinder.com/data/icons/iconsweets2/40/email_envelope.png' ) 0 no-repeat;*/
		}

		.nf-file-progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
		.nf-file-bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
		.nf-file-percent { position:absolute; display:inline-block; top:3px; left:48%; }

	</style>

	<script id="nf-tmpl-layout" type="text/template">
		<h3><%= settings.title %></h3>
		<div class="nf-form-wrap ninja-forms-form-wrap">
			<div class="nf-response-msg"></div>
			<div class="nf-before-form"></div>
			<div class="nf-form-layout"></div>
			<div class="nf-after-form"></div>
		</div>
	</script>

	<script id="nf-tmpl-before-form" type="text/template">
		<%= beforeForm %>
	</script>

	<script id="nf-tmpl-form-layout" type="text/template">
		<div>
			<div class="nf-before-fields"><%= beforeFields %></div>
			<div class="nf-fields"></div>
			<div class="nf-after-fields"><%= afterFields %></div>
		</div>
	</script>

	<script id="nf-tmpl-after-form" type="text/template">
		<%= afterForm %>
	</script>

	<script id="nf-tmpl-field-wrap" type="text/template">
		<div id="nf-field-<%= id %>-wrap" class="nf-field-wrap" data-field-id="<%= id %>">
			<%
			/*
			 * This is our main field template. It's called for every field type.
			 * Note that must have ONE top-level, wrapping element. i.e. a div/span/etc that wraps all of the template.
			 *
			 * <div>
			 * 	 <div>
			 *   	Cool Stuff
			 * 	 </div>
			 *   <span>
			 * 		Hello World!
			 *   </span>
			 * </div>
			 *
			 * is OK.
			 *
			 * <div>
			 * 		Cool Stuff
			 * </div>
			 * <span>
			 * 		Hello World!
			 * </span>
			 *
			 * is NOT ok because each of those items is on the top-level.
			 */

			/*
			 * Render our label if the position is set to before or left.
			 */
			%>
			<%= ( 'before' == label_pos || 'left' == label_pos ) ? renderLabel() : '' %>
			<%
			/*
			 * Render our field element. Uses the template for the field being rendered.
			 */
			%>
			<%= renderElement() %>
			<%
			/*
			 * Render our label if the position is set to after or right.
			 */
			%>
			<%= ( 'after' == label_pos || 'right' == label_pos ) ? renderLabel() : '' %>
			<%
			/*
			 * Render our error section if we have an error.
			 */
			%>
			<div class="nf-error-wrap"></div>
		</div>
	</script>

	<script id="nf-tmpl-field-label" type="text/template">
		<label for="nf-field-<%= id %>"><%= label %></label>
	</script>

	<script id="nf-tmpl-field-error" type="text/template">
		<div class="nf-error-msg nf-error-<%= id %>"><%= msg %></div>
	</script>

	<script id="nf-tmpl-field-text" type="text/template">
		<input id="nf-field-<%= id %>" name="nf-field-<%= id %>" class="<%= classes %>" type="text" value="<%= value %>" placeholder="<%= placeholder %>">
	</script>

	<script id="nf-tmpl-field-email" type="text/template">
		<input id="nf-field-<%= id %>" name="nf-field-<%= id %>" class="<%= classes %>" type="email" value="<%= value %>" placeholder="<%= placeholder %>">
	</script>

	<script id="nf-tmpl-field-textarea" type="text/template">
		<textarea id="nf-field-<%= id %>" name="nf-field-<%= id %>" class="<%= classes %>" placeholder="<%= placeholder %>"><%= value %></textarea>
	</script>

	<script id="nf-tmpl-field-submit" type="text/template">
		<input id="nf-field-<%= id %>" class="nf-element <%= disabled %>" type="button" value="<%= label %>">
		<%=	maybeRenderError() %>
	</script>

	<script id="nf-tmpl-field-submit-error-msg" type="text/template">
		<div class="nf-field-submit-error nf-error-msg" style="display:none;">
			DAT ERROR MESSAGE!
		</div>
	</script>

	<script id="nf-tmpl-field-checkbox" type="text/template">
		<input id="nf-field-<%= id %>" name="nf-field-<%= id %>" class="<%= classes %>" type="checkbox" value="1">
	</script>

	<script id="nf-tmpl-field-file" type="text/template">
		<div>
			<input type="button" value="<%= button_label %>" class="nf-file-button nf-element">
		</div>

		<div class="nf-file-progress" style="display:none;">
			<div class="nf-file-bar"></div>
			<div class="nf-file-percent">0%</div>
		</div>

		<div class="nf-file-status"></div>

		<span class="nf-file-input-wrapper">
			<input type="hidden" name="MAX_FILE_SIZE" value="2097152000">
			<input id="nf-field-<%= id %>" name="nf-field-<%= id %>" class="<%= classes %>" type="file" style="display:none;">
		</span>
	</script>

	<script id="nf-tmpl-field-radio" type="text/template">
		<ul>
			<%=	renderOptions()	%>
		</ul>
	</script>

	<script id='nf-tmpl-field-radio-option' type='text/template'>
	    <li>
	        <label><input type="radio" name="nf-field-<%= fieldID %>" class="<%= classes %>" value="<%= value %>" <%= ( value == currentValue ) ? 'checked="checked"' : '' %>> <%= label %></label>
	    </li>
	</script>

	<script id='nf-tmpl-field-radio-other' type='text/template'>
	    <li>
	        <label>
	            <input type="radio" name="nf-field-<%= fieldID %>" class="<%= classes %>" value="nf-other" <%= ( currentValue == 'nf-other' ) ? 'checked="checked"' : '' %>>
	            Other
	        </label>
	        <%= renderOtherText() %>
	    </li>
	</script>

	<script id='nf-tmpl-field-radio-other-text' type='text/template'>
	    <input type="text" name="nf-field-<%= fieldID %>" value="">
	</script>

	<?php
}
