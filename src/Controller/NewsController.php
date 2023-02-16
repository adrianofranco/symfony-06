<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{

    #[Route('/api/news/{id<\d+>}', methods: ['GET'])]
    public function getNew(int $id, LoggerInterface $logger): Response
    {
        // TODO create a real querys
        $new = [
                "id" => $id,
                "titulo" => "Artista brasileiro é premiado em festival internacional de cinema",
                "categoria" => "Cultura",
                "descricao" => "O artista brasileiro João Silva ganhou o prêmio de melhor filme no festival de cinema de Sundance.",
                "data" => "2022-02-10",
                "imagem" => "https://www.exemplo.com/imagens/arte-brasileira.jpg"
        ];

        // Log
        $logger->info('Retornando API da notícia {noticia}',[
            'noticia' => $id,
        ]);
        return $this->json($new);
    }
}