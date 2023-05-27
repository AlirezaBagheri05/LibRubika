<?php

set_time_limit(900);

$Main_dir = dirname(dirname(__DIR__));

define('SITE_URL',$Main_dir.'/');

include_once('config.php');

// your auth
$AUTH = 'xxxx';

// your guid
$GUID = 'xxxx';

// connect to rubika
$robot = new lib_rubika($AUTH,$GUID);

$robot->onUpdate(function (array $update) use ($robot) {
    
    if (isset($update['data_enc'])) { 

        $message = $update['data_enc'];
        if(isset($message['message_updates'])){

            foreach ($message['message_updates'] as $value){

                $guid_message = null;
                $Main_type = $value['type'];
                $Main_guid = $value['object_guid'];
                $Message = $value['message'];
                $type = $Message['type'];
                $message_id = $Message['message_id'];
                if(!isset($Message['author_object_guid'])){
                    if(isset($Message["event_data"])){
                        $event = $Message["event_data"];
                        if(isset($event['performer_object'])){
                            $info_object = $event['performer_object'];
                            if(isset($info_object["object_guid"])){
                                $guid_message = $info_object["object_guid"];
                            }
                        }
                    }
                }else{
                    $guid_message = $Message['author_object_guid'];
                }
                if(is_null($guid_message)){
                    continue;
                }
                // it just answer in PV
                if($Main_type == 'User'){
                    if(isset($Message['text'])){
                        $text = $Message['text'];
                        $robot->sendMessage_reply($Main_guid,"Your Message : $text",$message_id);
                    }
                }

            }
        }
    }
});