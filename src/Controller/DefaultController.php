<?php

namespace App\Controller;

use App\Service\ApiManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
     const API_END_POINT = 'https://beeterr.com/api/site/3';
//    const API_END_POINT = 'http://beeterr.local/api/site/3';

    /**
     * @param ApiManager $apiManager
     * @param string     $slug
     * @return Response
     * @Route("/{slug}", name="default")
     */
    public function index(ApiManager $apiManager, $slug = 'home')
    {
        if(!$site = $apiManager->getSite(self::API_END_POINT)) {
            return new Response('Sorry, no site found', Response::HTTP_BAD_REQUEST);
        } elseif(!$page = $apiManager->getPage($apiManager, $site, $slug)) {
            return new Response('Sorry, no page found[1]', Response::HTTP_BAD_REQUEST);
        } elseif(empty($page['route'])) {
            return new Response('<h1>No page found</h1>[2]', Response::HTTP_NOT_FOUND);
        } else {

            return $this->render($site['template'] . '/' . $page['route'] . '/index.html.twig', [
                'site' => $site,
                'page' => $page
            ]);
        }
    }
}
