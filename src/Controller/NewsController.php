<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NewsRepository;
use App\Entity\News;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;


class NewsController extends AbstractController
{
    #[Route('/news', name: 'app_news')]
    public function index(NewsRepository $newsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $newsRepository->getQueryBuilder();
        $pagination   = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            $newsRepository->pageLimit
        );

        return $this->render('news/index.html.twig', ['pagination' => $pagination]);
    }

    public function show(News $article): Response
    {
        return $this->render('news/show.html.twig', ['article' => $article]);
    }

    public function destroy(News $article, NewsRepository $newsRepository): Response
    {
        $newsRepository->remove($article, true);

        return $this->redirect($this->generateUrl('news_index'));
    }
}
