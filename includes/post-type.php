<?php

defined('ABSPATH') || exit;

class ThemeXpert_Search_Sync_Post_Type
{

    public function __construct()
    {
        add_action('init', [$this, 'register_docs_post_type']);
    }

    public function register_docs_post_type()
    {
        register_post_type('docs', array(
            'labels' => array(
                'name'               => __('Docs', 'themexpert-search-sync'),
                'singular_name'      => __('Doc', 'themexpert-search-sync'),

                'add_new'            => __('Add New', 'themexpert-search-sync'),
                'add_new_item'       => __('Add New Doc', 'themexpert-search-sync'),

                'edit_item'          => __('Edit Doc', 'themexpert-search-sync'),
                'new_item'           => __('New Doc', 'themexpert-search-sync'),

                'view_item'          => __('View Doc', 'themexpert-search-sync'),

                'all_items'             => __('All Docs', 'themexpert-search-sync'),

                'search_items'       => __('Search Docs', 'themexpert-search-sync'),

                'not_found'          => __('No docs found', 'themexpert-search-sync'),
                'not_found_in_trash' => __('No docs found in Trash', 'themexpert-search-sync'),
            ),
            'public' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-media-document',
            'supports' => [
                'title',
                'editor',
                'excerpt',
                'thumbnail',
                'revisions',
            ],

            'rewrite' => [
                'slug' => 'docs',
            ],

            'has_archive' => true,
            'menu_position' => 20,
        ));
    }
}
