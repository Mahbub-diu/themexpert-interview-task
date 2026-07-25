<?php


/**
 * Plugin Name: ThemeXpert Search Sync
 * Plugin URI: 
 * Description: Synchronizes WordPress posts with an external Meilisearch index and processes secure incoming webhooks.
 * Version: 1.0.0
 * Author: Mahbubur Rahman
 * Author URI: https://mahbubur.com/
 * License: GPL v2 or later
 * Text Domain: themexpert-search-sync 
 */



defined('ABSPATH') || exit;

if (file_exists(__DIR__ . '/vendor/action-scheduler/action-scheduler.php')) {
    require_once __DIR__ . '/vendor/action-scheduler/action-scheduler.php';
}




require_once plugin_dir_path(__FILE__) . 'includes/PostType.php';

require_once plugin_dir_path(__FILE__) . 'includes/SyncService.php';

require_once plugin_dir_path(__FILE__) . 'includes/Queue.php';

require_once plugin_dir_path(__FILE__) . '/includes/ApiClient.php';

new ThemeXpert_Search_Sync_Post_Type();

$queue = new Queue();

new ThemeXpert_Search_Sync($queue);





register_activation_hook(__FILE__, function () {

    $post_type = new ThemeXpert_Search_Sync_Post_Type();

    $post_type->register_docs_post_type();

    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function () {
    flush_rewrite_rules();
});
