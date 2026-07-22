<?php

class ThemeXpert_Search_Sync
{
    public function __construct()
    {
        add_action('save_post_docs', [$this, 'sync_doc'], 10, 3);
    }

    public function sync_doc($post_id, $post, $update)
    {
        $document = $this->prepare_document($post_id);

        // For testing
        error_log(print_r($document, true));
    }



    private function prepare_document($post_id)
    {
        $post = get_post($post_id);

        if (!$post || $post->post_type !== 'docs') {
            return [];
        }

        return [
            'id'            => $post->ID,
            'title'         => $post->post_title,
            'clean_content' => wp_strip_all_tags(strip_shortcodes($post->post_content)),
            'author_name'   => get_the_author_meta('display_name', $post->post_author),
        ];
    }
}
