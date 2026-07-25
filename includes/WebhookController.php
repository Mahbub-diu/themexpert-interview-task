<?php

defined("ABSPATH") || exit;

class WebhookController
{

    private const SECRET = 'whsec_7Qm2Kx9Lp4Rv8Tn3Wj6Zc1Yb5Hd0Fg';

    private Queue $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;

        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function registerRoutes()
    {
        register_rest_route(
            'myapp/v1',
            '/webhook',
            [
                'methods'                => 'POST',
                'callback'              => [$this, 'handleWebhook'],
                'permission_callback'   => '__return_true',
            ]
        );
    }

    public function handleWebhook(WP_Rest_Request $request)
    {
        $raw_body = $request->get_body();
        $signature = $request->get_header('X-Signature-Hash');

        if (empty($signature)) {
            return new WP_REST_Response(
                ['message' => 'Missing signature'],
                401
            );
        }

        if (!$this->verifySignature($raw_body, $signature)) {

            return new WP_REST_Response(
                ['message' => 'Unauthorized'],
                401
            );
        }

        $payload = json_decode($raw_body, true);

        if (!is_array($payload)) {
            return new WP_REST_Response(
                ['message' => 'Invalid JSON'],
                400
            );
        }

        switch ($payload['action']) {
            case 'delete_indexed_post':

                error_log('Webhook received: ' . print_r($payload, true));

                $this->queue->enqueueDelete(
                    (int) $payload['post_id']
                );

                return new WP_REST_Response(
                    ['message' => 'Delete queued'],
                    202
                );


            case 'purge_sync_queue':

                return new WP_REST_Response(
                    ['message' => 'Queue purge not implemented'],
                    202
                );

            default:

                return new WP_REST_Response(
                    ['message' => 'Unknown action'],
                    400
                );
        }
    }


    private function verifySignature(string $body, string $signature): bool
    {
        $verify_result = hash_hmac(
            'sha256',
            $body,
            self::SECRET
        );
        return hash_equals($verify_result, $signature);
    }
}
