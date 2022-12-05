<?php

namespace App\Messages;

use App\Definitions\News;

class SendNewsInfo
{
    /**
     * @var News
     */
    private News $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    /**
     * @return News
     */
    public function getNews(): News
    {
        return $this->news;
    }


}