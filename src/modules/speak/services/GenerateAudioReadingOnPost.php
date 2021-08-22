<?php

class GenerateAudioReadingOnPost {

    public function __construct() {
        add_action( 'save_post', [ $this, 'save_post' ] );
    }

    public function update( $post_id ){
        // Generate Audio for Message

		$file_type = 'mp3';
		$file_name = time().'_'.str_shuffle(time()).'.' . $file_type;
		$file_path = AIL_DIR_URI . 'temp/' . $file_name;
		$output_file = fopen( $file_path, 'w');

        $url = IBM_APIURL;
        $audio_config = '/v1/synthesize?voice=pt-BR_IsabelaV3Voice';
		$url = $url . $audio_config;
        $apiKey = IBM_APIKEY;

		$content = get_the_content(null, true, $post_id);
        $content = wp_strip_all_tags($content);
        $text_json = json_encode(['text' => $content]);
		$header_types = [
			'Content-Type: application/json'
		];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERPWD, 'apikey:' . $apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_types);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $text_json);
		curl_setopt($ch, CURLOPT_FILE, $output_file);

		$response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Error with curl response: ' . curl_error($ch));
        }
        curl_close($ch);
        fclose($output_file);

		if (filesize($file_path) < 1000) {
            //
            // probably there is an error and error string is saved to file,
            // open file and read the string
            // if error key exists in the string, delete generated file and throw exception
            //
            $content = file_get_contents($file_path);

            $debug_content = json_decode($content);

            if ( property_exists( $debug_content, 'error') ) {
                // deleted file created, because it is currupt
                unlink($file_path);
                // throw exception of the returned error
                throw new Exception($debug_content->error, $debug_content->code);
            }

        }

		$upload_file = wp_upload_bits($file_name, null, file_get_contents($file_path));

		if (!$upload_file['error']) {
			$wp_filetype = wp_check_filetype($file_name, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_parent' => $post_id,
				'post_title' => preg_replace('/\.[^.]+$/', '', $file_name),
				'post_content' => '',
				'post_status' => 'inherit'
			);

			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $post_id );
			if (!is_wp_error($attachment_id)) {
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
				wp_update_attachment_metadata( $attachment_id,  $attachment_data );
			}

			// Delete the Previous Version of This audio if exist
			$prev_audio_id = get_post_meta( $post_id, '_ailouise_speak_setting_speak-audio-id', true );
			wp_delete_attachment( $prev_audio_id, true );

			// Delete the Temp Audio in Plugin Folder
			unlink($file_path);

			update_post_meta( $post_id, '_ailouise_speak_setting_speak-audio-id', $attachment_id );

		}

        return $file_path;
    }
}

add_action( 'wp_ajax_update_speak_audio', 'update_speak_audio' );
// add_action( 'wp', 'update_speak_audio' );
function update_speak_audio(){

    $generate = new GenerateAudioReadingOnPost;
    $response = $generate->update($_REQUEST['post_id']);
	exit;
}
