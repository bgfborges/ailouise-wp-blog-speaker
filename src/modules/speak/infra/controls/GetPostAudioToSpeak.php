<?php

class GetPostAudioToSpeak {

    public $repository;

    function __construct(object $repository){
        $this->repository = $repository;
    }

    public function getAudioUrl( $post_id = null ){

		if( !$post_id ){
			global $post;
			if( $post && $post->ID ){
				$post_id = $post->ID;
			} else {
				return;
			}
		}

		if(isset($post_id))
			return $this->repository->get($post_id);
		return null;
    }

    public function getTemplateMode(){
        return $this->repository->template();
    }

    public function init(){
        $audio = $this->getAudioUrl();

        if( !!$audio ){
            $instance = new IncludeDisplayOnPost(
                array('post'),
                $this->getTemplateMode(),
                $audio
            );
            $instance->init();
        }
    }
}
