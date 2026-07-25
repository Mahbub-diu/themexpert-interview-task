<?php

defined('ABSPATH') || exit;

class ApiClient
{
    private string $base_url = 'https://search.thrivedesk.xyz';
    private string $api_key = 'masterKey_dev_8f3a2b7c9d1e';
    private string $index = 'docs';

    public function indexDocument(array $document)
    {
        $response = wp_remote_post(
            "{$this->base_url}/indexes/{$this->index}/documents",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_key,
                    'Content-Type'  => 'application/json',
                ],
                'body' => wp_json_encode([$document]),
                'timeout' => 20,
            ]
        );

        if (is_wp_error($response)) {
            error_log($response->get_error_message());
            return;
        }

        error_log(wp_remote_retrieve_body($response));
        error_log('Response: ' . print_r($response, true));
    }
}
