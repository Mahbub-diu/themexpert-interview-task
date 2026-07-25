<?php

defined('ABSPATH') || exit;

class Plugin
{
    private Queue $queue;

    public function init(): void
    {

        $this->registerServices();
    }



    private function registerServices(): void
    {
        $this->queue = new Queue();

        new ThemeXpert_Search_Sync_Post_Type();

        new ThemeXpert_Search_Sync($this->queue);

        new WebhookController($this->queue);
    }
}
