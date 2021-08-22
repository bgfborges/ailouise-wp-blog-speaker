<?php

function get_audio_template(string $template = 'default', string $audio, string $profile){
    $getContainer = file_get_contents(plugin_dir_path( __FILE__ ) . '/' . $template . '/index.html');
    $insertAudioToContainer = str_replace('%audio_url%', $audio, $getContainer);
    $insertAudioToContainer = str_replace('%profile_cover%', $profile, $insertAudioToContainer);
    return $insertAudioToContainer;
}
