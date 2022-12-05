<?php

namespace App\Handler;

use App\Entity\News;
use App\Mappings\NewsArticle as NewsMapper;
use App\Messages\SendNewsInfo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SendNewsInfoHandler implements MessageHandlerInterface
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    private EntityManagerInterface $entityManager;

    public function __construct(MessageBusInterface $messageBus, EntityManagerInterface $entityManager)
    {
        $this->messageBus = $messageBus;

        $this->entityManager = $entityManager;
    }

    public function __invoke(SendNewsInfo $newsInfo)
    {
        try {
            // /**
            //  * @var NewsRepository $newsRepository
            //  */
            $newsRepository = $this->entityManager->getRepository(News::class);

            /**
             * @var News $exists
             */
            $exists = $newsRepository->findOneBy(['title' => $newsInfo->getNews()->getTitle()]);
            if (empty($exists) === true) {
                (new NewsMapper($newsInfo->getNews(), $this->entityManager))->create();
                echo 'Created new article'.PHP_EOL;
            } else {
                $newsRepository->updateNews($exists, $newsInfo->getNews());
                echo 'Updated article with id '.$exists->getId().PHP_EOL;
            }
        } catch(\Exception $exception) {
            echo $exception->getMessage().PHP_EOL;
        }
    }
}