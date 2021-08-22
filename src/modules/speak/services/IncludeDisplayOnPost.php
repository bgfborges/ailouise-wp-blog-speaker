<?php

class IncludeDisplayOnPost {

    public $post_types;
    public $template;
    public $audio;
    public $profile;

    function __construct(
        array $set_post_types = array('post'),
        string $template = 'default',
        string $audio,
        string $profile = ''
    ){
        $this->post_types = $set_post_types;
        $this->template = $template;
        $this->audio = $audio;
		if( $profile ){
        	$this->profile = $profile;
		} else {
			$this->profile = AIL_DIR_URL . 'src/assets/images/louise.jpg';
		}
    }

    public function init(){
        add_filter('the_content', function($content){
            if( in_array(get_post_type(), $this->post_types) ){
                $fullcontent = get_audio_template($this->template, $this->audio, $this->profile) . $content;
                return $fullcontent;
            } else {
                return $content;
            }
        });
    }

}
