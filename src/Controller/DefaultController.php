<?php

namespace App\Controller;

use App\Service\ApiManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @param ApiManager $apiManager
     * @return Response
     * @Route("/robots.{_format}", name="robots", requirements={"_format" = "txt"})
     */
    public function robotsAction(ApiManager $apiManager)
    {
        if(!$site = $apiManager->getSite($this->getParameter('api_end_point'))) {
            return new Response('Sorry, no site found.', Response::HTTP_BAD_REQUEST);
        } else {
            return $this->render('/robots.html.twig', array());
        }
    }

    /**
     * @param ApiManager $apiManager
     * @return Response
     * @Route("/sitemap.{_format}", name="sitemap", requirements={"_format" = "xml"})
     */
    public function sitemap(ApiManager $apiManager)
    {
        if(!$site = $apiManager->getSite($this->getParameter('api_end_point'))) {
            return new Response('Sorry, no site found.', Response::HTTP_BAD_REQUEST);
        } else {

            $pages = $apiManager->getPages($apiManager, $site);

            return $this->render('sitemap.xml.twig', array(
                'pages' => $pages
            ));
        }
    }

    /**
     * @param ApiManager $apiManager
     * @param string     $slug
     * @return Response
     * @Route("/{slug}", name="default")
     */
    public function index(ApiManager $apiManager, $slug = 'home')
    {
        if(!$site = $apiManager->getSite($this->getParameter('api_end_point'))) {
            return new Response('Sorry, no site found.', Response::HTTP_BAD_REQUEST);
        } elseif(!$page = $apiManager->getPage($apiManager, $site, $slug)) {
            return new Response('<h1>Sorry, no page found[1].</h1>', Response::HTTP_BAD_REQUEST);
        } elseif(empty($page['route'])) {
            return new Response('<h1>Sorry, no page found[2].</h1>', Response::HTTP_NOT_FOUND);
        } else {

            return $this->render($site['template'] . '/' . $page['route'] . '/' . $page['type'] . '.html.twig', [
                'site' => $site,
                'page' => $page
            ]);
        }
    }

    /**
     * @param ApiManager $apiManager
     * @param Request    $request
     * @param            $slug
     * @return Response
     * @Route("/search/a/b/c", name="default_search")
     */
    public function search(ApiManager $apiManager, Request $request, $slug = 'search')
    {
        if(!$site = $apiManager->getSite($this->getParameter('api_end_point'))) {
            return new Response('Sorry, no site found', Response::HTTP_BAD_REQUEST);
        } elseif(!$page = $apiManager->getPage($apiManager, $site, $slug)) {
            return new Response('<h1>Sorry, no page found[1]</h1>', Response::HTTP_BAD_REQUEST);
        } else {

            $search = $apiManager->getSearch($apiManager, $site, $request->get('needle'));

            $page['page']['metaTitle'] = 'Zoeken:' . $request->get('needle');

            return $this->render($site['template'] . '/pages/search-01.html.twig', [
                'site' => $site,
                'page' => $page,
                'search' => $search
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
