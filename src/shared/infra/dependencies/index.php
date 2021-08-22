<?php

// Options Page
require_once plugin_dir_path( __FILE__ ) . '../../../modules/admin/infra/models/options-page.php';

// MySQL Repository
require_once plugin_dir_path( __FILE__ ) . '../../../modules/speak/infra/repositories/mysql.php';

add_action('wp', 'instance_modules_classes');
function instance_modules_classes(){
    $MySqlPostSpeakerRepository = new MySqlPostSpeakerRepository();
    $ControlAudioSpeaker = new GetPostAudioToSpeak($MySqlPostSpeakerRepository);
    $ControlAudioSpeaker->init();
}
