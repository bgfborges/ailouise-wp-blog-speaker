<?php

class MySqlPostSpeakerRepository {

    private function is_enabled(int $post_id){
        return get_post_meta( $post_id, '_ailouise_speak_setting_ailouise-speak-enable', true );
    }

    public function get(int $post_id){
        $value =  $this->is_enabled($post_id);
        if( $value === 'on' ){
            $audio_id = get_post_meta( $post_id, '_ailouise_speak_setting_speak-audio-id', true );
			$audio_url = wp_get_attachment_url( $audio_id );
            return $audio_url;
        } else {
            return false;
        }
    }

    public function template(int $post_id = 0){

        $audioUrl = wp_get_attachment_url(get_post_meta($post_id, 'audio_speaker_id'));
        return 'default';
    }

}
