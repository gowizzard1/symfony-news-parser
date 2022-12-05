<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Definitions\News;
use App\Messages\SendNewsInfo;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:news',
    description: 'Add a short description for your command',
)]
class NewsCommand extends Command
{
    protected static $defaultName = 'app:news';

    private ?string $newsApiKey;

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $bus, KernelInterface $kernel)
    {
        parent::__construct();

        // $this->newsApiKey = $kernel->getContainer()->getParameter('news_api_key');

        $this->messageBus = $bus;
    }

    protected function configure(): void
    {
        $this->setDescription('News Parsing Service')
            ->addOption('date', null, InputOption::VALUE_OPTIONAL, 'date');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // $date = $input->getOption('date');

        // if (empty($date) === false) {
        //     $io->note(sprintf('You passed a date option: %s', $date));
        // }

        // $date = empty($date) === false ? date_create($date)->format('Y-m-d') : date('Y-m-d');

        $items = json_decode($this->fetchTestNewsArticles());
        // $items = json_decode($this->fetchNewsArticles($date));
        if (empty($items->status) === false && strtolower($items->status) === 'error') {
            throw new \Exception($items->message);
        }
        dd($items);

        foreach($items->articles as $news_article) {
           if (empty($news_article->title) === false) {
               $message = new News($news_article->title,$news_article->content, 
                                        $news_article->author,$news_article->description,
                                        $news_article->urlToImage, $news_article->publishedAt);

               $this->enqueue($message);
           }
        }

        $io->success('Completed');

        return Command::SUCCESS;
    }

    private function fetchTestNewsArticles()
    {
        return file_get_contents("./tests/data/news.json");
    }

    private function enqueue(News $message)
    {
        echo $message->getTitle().PHP_EOL;
        $this->messageBus->dispatch(new SendNewsInfo($message));
    }

    private function fetchNewsArticles(string $date, string $query = 'a'): string
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://newsapi.org/v2/everything?q=".$query."&from=".$date."&sortBy=publishedAt&apiKey=".$this->newsApiKey,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "utf-8",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new $err;
        } else {

            error_log($response, 3, "./logs/errors/news_".date_create()->getTimestamp().".txt");
            return $response;
        }
    }
}
