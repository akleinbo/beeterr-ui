<?php

namespace App\Controller;

use App\Service\ApiManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @param ApiManager $apiManager
     * @param string     $slug
     * @return Response
     * @Route("/{slug}", name="default")
     */
    public function index(ApiManager $apiManager, $slug = 'home')
    {
        if(!$site = $apiManager->getSite($this->getParameter('api_end_point'))) {
            return new Response('Sorry, no site found', Response::HTTP_BAD_REQUEST);
        } elseif(!$page = $apiManager->getPage($apiManager, $site, $slug)) {
            return new Response('<h1>Sorry, no page found[1]</h1>', Response::HTTP_BAD_REQUEST);
        } elseif(empty($page['route'])) {
            return new Response('<h1>Sorry, no page found[2]</h1>', Response::HTTP_NOT_FOUND);
        } else {

            return $this->render($site['template'] . '/' . $page['route'] . '/' . $page['type'] . '.html.twig', [
                'site' => $site,
                'page' => $page
            ]);
        }
    }

    /**
     * @param ApiManager $apiManager
     * @param            $siteId
     * @param string     $slug
     * @return Response
     * @Route("/example/{siteId}/{slug}", name="example")
     */
    public function example(ApiManager $apiManager, $siteId, $slug = 'home')
    {
        if(!$site = $apiManager->getSite($this->getParameter('api_end_point_example') . $siteId)) {
            return new Response('Sorry, no site found', Response::HTTP_BAD_REQUEST);
        } elseif(!$page = $apiManager->getPage($apiManager, $site, $slug)) {
            return new Response('<h1>Sorry, no page found[1]</h1>', Response::HTTP_BAD_REQUEST);
        } elseif(empty($page['route'])) {
            return new Response('<h1>Sorry, no page found[2]</h1>', Response::HTTP_NOT_FOUND);
        } else {

            return $this->render($site['template'] . '/' . $page['route'] . '/' . $page['type'] . '.html.twig', [
                'site' => $site,
                'page' => $page
            ]);
        }
    }
}
