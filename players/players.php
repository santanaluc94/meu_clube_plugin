<?php

/**
 * Does not allow executing this file, if it is not executed by Wordpress
 */
if (!defined('ABSPATH')) {
    die;
}

if (!session_id()) {
    session_start();
}

include_once __DIR__ . '/includes/custom_post_players.php';
