<?php
/**
 * Retrieving the values:
 * aiLouise Speak Enable = get_post_meta( get_the_ID(), '_ailouise_speak_setting_ailouise-speak-enable', true )
 * Speak Audio Id = get_post_meta( get_the_ID(), '_ailouise_speak_setting_speak-audio-id', true )
 */
class AiLouiseSpeakSeetingGroup {
	private $config = '{"title":"aiLouise Speak","description":"Enable aiLouise Speak for this post.","prefix":"_ailouise_speak_setting_","domain":"speak-enabled","class_name":"AiLouiseSpeakSeetingGroup","post-type":["post","page"],"context":"normal","priority":"default","fields":[{"type":"checkbox","label":"aiLouise Speak Enable","description":"Enable aiLouise Speak for this post.","id":"_ailouise_speak_setting_ailouise-speak-enable"},{"behavior":"ignore","cta":"Update Audio","btn-id":"ail-update-audio","type":"hidden","label":"Speak Audio Id","id":"_ailouise_speak_setting_speak-audio-id"}]}';

	public function __construct() {
		$this->config = json_decode( $this->config, true );
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
		add_action( 'admin_head', [ $this, 'admin_head' ] );
		add_action( 'save_post', [ $this, 'save_post' ] );
	}

	public function add_meta_boxes() {
		foreach ( $this->config['post-type'] as $screen ) {
			add_meta_box(
				sanitize_title( $this->config['title'] ),
				$this->config['title'],
				[ $this, 'add_meta_box_callback' ],
				$screen,
				$this->config['context'],
				$this->config['priority']
			);
		}
	}

	public function admin_head() {
		global $typenow;
		if ( in_array( $typenow, $this->config['post-type'] ) ) {
			?><?php
		}
	}

	public function save_post( $post_id ) {
		foreach ( $this->config['fields'] as $field ) {
			switch ( $field['type'] ) {
				case 'checkbox':
					update_post_meta( $post_id, $field['id'], isset( $_POST[ $field['id'] ] ) ? $_POST[ $field['id'] ] : '' );
					break;
				default:
					if ( isset( $_POST[ $field['id'] ] ) && $field['behavior'] !== 'ignore' ) {
						$sanitized = sanitize_text_field( $_POST[ $field['id'] ] );
						update_post_meta( $post_id, $field['id'], $sanitized );
					}
			}
		}
	}

	public function add_meta_box_callback() {
		echo '<div class="rwp-description">' . $this->config['description'] . '</div>';
		$this->fields_table();
	}

	private function fields_table() {
		?><table class="form-table" role="presentation">
			<tbody><?php
				foreach ( $this->config['fields'] as $field ) {
					?><tr>
						<th scope="row"><?php $this->label( $field ); ?></th>
						<td><?php $this->field( $field ); ?></td>
					</tr><?php
				}
			?></tbody>
		</table><?php
	}

	private function label( $field ) {
		switch ( $field['type'] ) {
			default:
				printf(
					'<label class="" for="%s">%s</label>',
					$field['id'], $field['label']
				);
		}
	}

	private function field( $field ) {
		switch ( $field['type'] ) {
			case 'checkbox':
				$this->checkbox( $field );
				break;
			default:
				$this->input( $field );
		}
	}

	private function checkbox( $field ) {
		$html = '<label class="rwp-checkbox-label"><input %s id="%s" name="%s" type="checkbox"> %s</label>';
		printf(
			$html,
			$this->checked( $field ),
			$field['id'], $field['id'],
			isset( $field['description'] ) ? $field['description'] : ''
		);
	}

	private function input( $field ) {
		$html = '<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s" style="padding:3px;margin-top:-4px;">';
		$html .= $field["cta"] ? '<button id="'. $field["btn-id"] .'" class="components-button is-primary" style="margin:0 6px;border-radius:5px;">'. $field["cta"] . '</button>' . __('Always Update the Post Before Generate an Audio of it.') : '';
		printf(
			$html,
			isset( $field['class'] ) ? $field['class'] : '',
			$field['id'], $field['id'],
			isset( $field['pattern'] ) ? "pattern='{$field['pattern']}'" : '',
			$field['type'],
			$this->value( $field )
		);
	}

	private function value( $field ) {
		global $post;
		if ( metadata_exists( 'post', $post->ID, $field['id'] ) ) {
			$value = get_post_meta( $post->ID, $field['id'], true );
		} else if ( isset( $field['default'] ) ) {
			$value = $field['default'];
		} else {
			return '';
		}
		return str_replace( '\u0027', "'", $value );
	}

	private function checked( $field ) {
		global $post;
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
}
new AiLouiseSpeakSeetingGroup;
