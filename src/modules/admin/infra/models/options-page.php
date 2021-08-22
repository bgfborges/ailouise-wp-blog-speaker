<?php

class AiLouise {
	private $ailouise_options;

	public $post_types;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ailouise_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'ailouise_page_init' ) );

		$this->post_types = get_post_types();
	}

	public function ailouise_add_plugin_page() {
		add_menu_page(
			'aiLouise', // page_title
			'aiLouise', // menu_title
			'manage_options', // capability
			'ailouise', // menu_slug
			array( $this, 'ailouise_create_admin_page' ), // function
			'dashicons-rest-api', // icon_url
			2 // position
		);
	}

	public function ailouise_create_admin_page() {
		$this->ailouise_options = get_option( 'ailouise_option_name' ); ?>

		<div class="wrap">
			<h2>aiLouise</h2>
			<p>aiLouise Manager Page</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'ailouise_option_group' );
					do_settings_sections( 'ailouise-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function ailouise_page_init() {
		register_setting(
			'ailouise_option_group', // option_group
			'ailouise_option_name', // option_name
			array( $this, 'ailouise_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'ailouise_setting_section', // id
			'Blog Speaker Settings', // title
			array( $this, 'ailouise_section_info' ), // callback
			'ailouise-admin' // page
		);

		add_settings_field(
			'ibm_api_key', // id
			'IBM API KEY', // title
			array( $this, 'ibm_api_key_callback' ), // callback
			'ailouise-admin', // page
			'ailouise_setting_section' // section
		);

		add_settings_field(
			'ibm_api_url', // id
			'IBM API URL', // title
			array( $this, 'ibm_api_url_callback' ), // callback
			'ailouise-admin', // page
			'ailouise_setting_section' // section
		);

		add_settings_field(
			'ail_speaker_post_type-post', // id
			'', // title
			array( $this, 'ail_speaker_post_type_post_callback' ), // callback
			'ailouise-admin', // page
			'ailouise_setting_section' // section
		);

		add_settings_field(
			'ail_speaker_post_type-post', // id
			'', // title
			array( $this, 'ail_speaker_post_type_page_callback' ), // callback
			'ailouise-admin', // page
			'ailouise_setting_section' // section
		);

	}

	public function ailouise_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['ibm_api_key'] ) ) {
			$sanitary_values['ibm_api_key'] = sanitize_text_field( $input['ibm_api_key'] );
		}

		if ( isset( $input['ibm_api_url'] ) ) {
			$sanitary_values['ibm_api_url'] = sanitize_text_field( $input['ibm_api_url'] );
		}

		return $sanitary_values;
	}

	public function ailouise_section_info() {

	}

	private function checked( $field ) {
		global $post;
		if( !$post || !$post->ID ) {
			return;
		}

		if ( metadata_exists( 'post', $post->ID, $field['id'] ) ) {
			$value = get_post_meta( $post->ID, $field['id'], true );
			if ( $value === 'on' ) {
				return 'checked';
			}
			return '';
		} else if ( isset( $field['checked'] ) ) {
			return 'checked';
		}
		return '';
	}

	public function ibm_api_key_callback() {
		printf(
			'<input class="regular-text" type="text" name="ailouise_option_name[ibm_api_key]" id="ibm_api_key" value="%s">',
			isset( $this->ailouise_options['ibm_api_key'] ) ? esc_attr( $this->ailouise_options['ibm_api_key']) : ''
		);
	}

	public function ibm_api_url_callback() {
		printf(
			'<input class="regular-text" type="text" name="ailouise_option_name[ibm_api_url]" id="ibm_api_url" value="%s">',
			isset( $this->ailouise_options['ibm_api_url'] ) ? esc_attr( $this->ailouise_options['ibm_api_url']) : ''
		);
	}

	public function ail_speaker_post_type_post_callback() {

	}

	public function ail_speaker_post_type_page_callback() {

	}

}
if ( is_admin() )
	$ailouise = new AiLouise();

/*
 * Retrieve this value with:
 * $ailouise_options = get_option( 'ailouise_option_name' ); // Array of All Options
 * $ibm_api_key = $ailouise_options['ibm_api_key']; // IBM API KEY
 */
