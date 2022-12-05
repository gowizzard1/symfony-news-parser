<?php

namespace App\Definitions;

use Ramsey\Uuid\Uuid;
use function trim;
use function preg_replace;
use function str_replace;
use function strtolower;
use function date_create;

class News
{

    public string $title;

    public string $content;

    public string $author;

    public ?string $image;

    public string $link;

    public string $description;

    public $created_at;

    public $updated_at;

    public function __construct($title,$content,$author, $description,$link, $image, $createdAt = null, $updatedAt = null)
    {
        $this->title = $title;
       
        $this->content = $content;

        $this->author = $author;

        $this->description = $description;

        $this->link = $link;

        $this->image = $image;

        if (empty($createdAt) === false) {
            $this->created_at = date_create($createdAt);
        }

        if (empty($updatedAt) === false) {
            $this->created_at = date_create($createdAt);
        }

    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }
    public function getLink(): string
    {
        return $this->link;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImage(): ?string
    {
        return $this->image ?? '';
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->created_at;
    }
}