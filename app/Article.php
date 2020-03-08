<?php
declare(strict_types=1);

namespace App;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Webmozart\Assert\Assert;

final class Article
{
    private UuidInterface $id;
    private string $slug;
    private string $title;
    private string $content;
    private array $categories;
    private ?string $media;
    private DateTimeImmutable $published;

    public function __construct(
        UuidInterface $id,
        string $title,
        string $slug,
        string $content,
        array $categories,
        ?string $media,
        DateTimeImmutable $published
    ) {
        Assert::stringNotEmpty($title);
        Assert::stringNotEmpty($slug);

        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->categories = $categories;
        $this->media = $media;
        $this->published = $published;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function categories(): array
    {
        return $this->categories;
    }

    public function media(): string
    {
        return $this->media;
    }

    public function published(): DateTimeImmutable
    {
        return $this->published;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->toString(),
            'slug' => $this->slug,
            'title' => $this->title,
            'content' => $this->content,
            'categories' => $this->categories,
            'media' => $this->media,
            'published' => $this->published->format('c')
        ];
    }

    public static function fromIndexJSON(array $entry): self
    {
        $id = Uuid::fromString($entry['id']);
        $title = $entry['title'];
        $slug = $entry['slug'];
        $content = $entry['content'][0]['content'];
        $categories = array_merge([$entry['categories']['primary']], $entry['categories']['additional'] ?? []);
        $media = count($entry['media']) ? $entry['media'][0]['media']['attributes']['url'] : null;
        $published = new DateTimeImmutable(
            $entry['properties']['published']
        );

        return new static(
            $id,
            $title,
            $slug,
            $content,
            $categories,
            $media,
            $published
        );
    }

    public static function fromElasticsearch(array $entry)
    {
        $id = Uuid::fromString($entry['_id']);
        $title = $entry['_source']['title'];
        $slug = $entry['_source']['slug'];
        $content = $entry['_source']['content'];
        $categories = $entry['_source']['categories'];
        $media = $entry['_source']['media'];
        $published = new DateTimeImmutable($entry['_source']['published']);

        return new static(
            $id,
            $title,
            $slug,
            $content,
            $categories,
            $media,
            $published
        );
    }
}
