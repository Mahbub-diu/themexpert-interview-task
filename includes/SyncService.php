<?php

class ThemeXpert_Search_Sync
{
    private $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;

        add_action('save_post_docs', [$this, 'sync_doc'], 10, 3);

        add_action('before_delete_post', [$this, 'deleteDoc']);
    }

    public function sync_doc($post_id, $post, $update)
    {
        $document = $this->prepare_document($post_id);

        if (empty($document)) {
            return;
        }
        // error_log(print_r($document, true));

        $this->queue->enqueueSync($document);
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

    public function deleteDoc($postId): void
    {
        $post = get_post($postId);

        if (!$post || $post->post_type !== 'docs') {
            return;
        }

        $this->queue->enqueueDelete($postId);
    }
}
