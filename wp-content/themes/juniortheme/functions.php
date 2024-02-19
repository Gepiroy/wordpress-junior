<?php
//require_once(__DIR__ . '/functions/own_post_type.php');
require_once(__DIR__ . '/includes/OwnPostTypeClass.php');
$ownPostType = new OwnPostTypeClass();
$ownPostType->add_actions();
?>