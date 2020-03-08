<?php
declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Elasticsearch\NewsStore;

final class ArticleBySlug
{
    private NewsStore $news;

    public function __construct(NewsStore $news)
    {
        $this->news = $news;
    }

    public function __invoke($rootValue, array $args): array
    {
        $slug = $args['slug'];
        return $this->news->bySlug($slug)->serialize();
    }
}
