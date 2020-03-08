<?php
declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Article;
use App\Elasticsearch\NewsStore;

final class Search
{
    private NewsStore $news;

    public function __construct(NewsStore $news)
    {
        $this->news = $news;
    }

    private function hasNextPage(int $total, int $limit, int $offset): bool
    {
        return $offset + $limit < $total;
    }

    public function __invoke($rootValue, array $args): array
    {
        $query = $args['query'];
        $limit = $args['limit'];
        $offset = $args['offset'];

        [$total, $news] = $this->news->search($query, $limit, $offset);
        return [
            'totalCount' => $total,
            'items' => array_map(fn (Article $a) => $a->serialize(), $news),
            'pageInfo' => [
                'hasNextPage' => $this->hasNextPage($total, $limit, $offset)
            ]
        ];
    }
}
