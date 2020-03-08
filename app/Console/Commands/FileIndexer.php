<?php

namespace App\Console\Commands;

use App\Article;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use InvalidArgumentException as InvalidArgumentExceptionAlias;

class FileIndexer extends Command
{
    private Client $client;
    protected $signature = 'news:index {filePath}';
    protected $description = 'Update news index with provided file';

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function handle()
    {
        $filePath = $this->argument('filePath');
        if (!file_exists($filePath)) {
            $this->error('File doesn\'t exists');
            return;
        }

        $contents = json_decode(
            file_get_contents($filePath),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $skipped = 0;
        $indexed = 0;
        foreach ($contents as $entry) {
            try {
                $article = Article::fromIndexJSON($entry);
            } catch (InvalidArgumentExceptionAlias $e) {
                $skipped++;
            }

            $data = [
                'body' => $article->serialize(),
                'index' => 'news',
                'id' => $article->id()->toString(),
            ];

            $this->client->index($data);
            $indexed++;
        }
        $this->info(sprintf("Indexed %d articles, skipped %d articles", $indexed, $skipped));
    }
}
