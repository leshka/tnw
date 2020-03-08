<?php
declare(strict_types=1);

namespace App\Elasticsearch;

use App\Article;
use Elasticsearch\Client;

final class NewsStore
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function news(?string $category, int $limit, int $offset): array
    {
        $params = [
            'index' => 'news',
            'body' => [
                'from' => $offset,
                'size' => $limit,
                'sort' => [
                    ['published' => ['order' => 'desc']]
                ]
            ]
        ];
        if ($category) {
            $params['body']['query'] = [
                'term' => [
                    'categories' => $category
                ]
            ];
        }

        $response = $this->client->search($params);

        $total = $response['hits']['total']['value'];
        $articles = array_map(
            fn (array $entry) => Article::fromElasticsearch($entry),
            $response['hits']['hits']
        );

        return [$total, $articles];
    }

    public function search(string $query, int $limit, int $offset): array
    {
        $params = [
            'index' => 'news',
            'body' => [
                'from' => $offset,
                'size' => $limit,
                'sort' => [
                    ['published' => ['order' => 'desc']]
                ],
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['title', 'content'],
                    ],
                ]
            ]
        ];

        $response = $this->client->search($params);

        $total = $response['hits']['total']['value'];
        $articles = array_map(
            fn (array $entry) => Article::fromElasticsearch($entry),
            $response['hits']['hits']
        );
        return [$total, $articles];
    }

    public function bySlug(string $slug): Article
    {
        $params = [
            'index' => 'news',
            'body' => [
                'size' => 1,
                'query' => [
                    'term' => [
                        'slug' => [
                            'value' => $slug,
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->client->search($params);

        return Article::fromElasticsearch($response['hits']['hits'][0]);
    }
}
