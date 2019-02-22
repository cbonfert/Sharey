<?php
require_once('classes/Tag.php');

$tags = Tag::getAllTags();

$jsonTags = [];

        foreach($tags as $tag){
            $jsonTags[] = $tag->toJson();
        }

        echo json_encode($jsonTags);

?>