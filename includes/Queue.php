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

    public function processSync(array $args): void
    {
        $client = new ApiClient();

        $client->indexDocument($args);
    }
}
