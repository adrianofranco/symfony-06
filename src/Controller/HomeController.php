<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsCategoryRepository;
use App\Repository\NewsRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class HomeController extends AbstractController
{
    public function __construct(

        private NewsRepository $newsRepository,
        private bool $isDebug
    )
    {

    }

    #[Route(path:'/', name: 'app_home')]
    public function home(NewsCategoryRepository $categoryRepository, NewsRepository $newsRepository): Response
    {

        $categories = $categoryRepository->findAll();
        $news = $newsRepository->findLastNews(10);

        $pageTitle = "Sistema de Notícias";
        return $this->render('home.html.twig', [
            'pageTitle' => $pageTitle,
            'categories' => $categories,
            'news' => $news,
        ]);
    }


    #[Route(path:'/category/{slug}', name: 'app_category_list')]
    public function category($slug,Request $request, NewsRepository $newsRepository, NewsCategoryRepository $categoryRepository): Response
    {

        //$news = $newsRepository->findAll();
        $news = $newsRepository->findByCategoryTitle($slug);

        $queryBuilder = $newsRepository->createQueryBuilderCategoryTitle($slug);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerFanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page',1),
            6
        );

        $categories = $categoryRepository->findAll();

        $pageTitle = $slug;

        return $this->render('category.html.twig', [
            'pageTitle' => $pageTitle,
            'categories' => $categories,
            'pager' => $pagerFanta,
        ]);
    }

    #[Route(path:'/pesquisa/', name: 'app_news_filter')]
    public function filter(Request $request, NewsRepository $newsRepository,NewsCategoryRepository $categoryRepository): Response
    {
        $search = $request->query->get('search');
        $categories = $categoryRepository->findAll();

        // $news = $newsRepository->findBySearch($search);
        $queryBuilder = $newsRepository->createQueryBuilderBySearch($search);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerFanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page',1),
            6
        );

        return $this->render('search.html.twig', [
            'pageTitle' => 'Resultado da pesquisa',
            'categories' => $categories,
            'pager' => $pagerFanta,
            'search' => $search,
        ]);
    }

    #[Route(path:'/news/{slug}', name: 'app_home_newsdetail')]
    public function newsDetail(News $news,NewsCategoryRepository $categoryRepository){

        $categories = $categoryRepository->findAll();

        return $this->render('newsDetail.html.twig', [
            'pageTitle' => $news->getTitle(),
            'categories' => $categories,
            'news' => $news,
        ]);
    }

    #[Route(path: '/data/',name: 'app_news_date')]
    public function filterDate(Request $request, NewsCategoryRepository $categoryRepository, NewsRepository $newsRepository): Response
    {
        $year = $request->query->get('ano',1);
        $month = $request->query->get('mes',1);

        $search = "Notícias do mês ".$month." de ".$year;

        $categories = $categoryRepository->findAll();

        // $news = $newsRepository->findBySearch($search);
        $queryBuilder = $newsRepository->createQueryBuilderByYearAndMounth($year,$month);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerFanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page',1),
            6
        );

        return $this->render('search.html.twig', [
            'pageTitle' => 'Resultado da pesquisa',
            'categories' => $categories,
            'pager' => $pagerFanta,
            'search' => $search,
        ]);
    }

}
