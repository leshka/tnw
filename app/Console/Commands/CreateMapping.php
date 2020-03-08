<?php

namespace App\Console\Commands;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\ElasticsearchException;
use Illuminate\Console\Command;

class CreateMapping extends Command
{
    private Client $client;
    protected $signature = 'news:mapping:create';
    protected $description = 'Create mapping for news';

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function handle()
    {
        $params = [
            'index' => 'news',
            'body' => [
                'settings' => [
                    'analysis' => [
                        'analyzer' => [
                            'contentAnalyzer' => [
                                'type' => 'custom',
                                'tokenizer' => 'standard',
                                'char_filter' => ['html_strip']
                            ],
                        ]
                    ],
                ],
                'mappings' => [
                    'properties' => [
                        'title' => ['type' => 'text'],
                        'slug' => ['type' => 'keyword'],
                        'content' => ['type' => 'text', 'analyzer' => 'contentAnalyzer'],
                        'media' => ['type' => 'text'],
                        'published' => ['type' => 'date']
                    ]
                ]
            ]
        ];

        try {
            $this->client->indices()->create(
                $params
            );
        } catch (ElasticsearchException $e) {
            $this->error($e);
            return;
        }

        $this->info('Index was successfully created');
    }
}
