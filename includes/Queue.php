<?php

defined('ABSPATH')  || exit;



class Queue
{
    public function __construct()
    {
        add_action(
            'themexpert_search_sync_process_document',
            [$this, 'processSync'],
            10,
            1
        );

        add_action(
            'themexpert_search_sync_delete_document',
            [$this, 'processDelete'],
            10,
            1
        );
    }



    public function enqueueSync(array $document): void
    {
        as_enqueue_async_action(
            'themexpert_search_sync_process_document',
            [
                'document' => $document,
            ],
            'tx-search-sync'

        );
    }

    public function processSync(array $document): void
    {
        $client = new ApiClient();

        $client->indexDocument($document);
    }

    public function enqueueDelete(int $postId): void
    {
        as_enqueue_async_action(
            'themexpert_search_sync_delete_document',
            [
                'post_id' => $postId,
            ],
            'tx-search-sync'
        );
    }

    public function processDelete(int $postId): void
    {
        $client = new ApiClient();

        $client->deleteDocument((int) $postId);
    }
}
