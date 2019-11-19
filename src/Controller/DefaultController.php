<?php

namespace App\Controller;

use App\Service\ApiManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultController extends AbstractController
{

    /**
     * @param ApiManager $apiManager
     * @return Response
     * @Route("/robots.{_format}",
     *     name="robots",
     *     requirements={"_format" = "txt"}
     *     )
     */
    public function robotsAction(
        ApiManager $apiManager
    ) {
        if(!$site = $apiManager->getSite($this->getParameter('api_end_point'))) {
            return new Response('Sorry, no site found.', Response::HTTP_BAD_REQUEST);
        } else {
            return $this->render('/robots.html.twig', array());
        }
    }

    /**
     * @param ApiManager $apiManager
     * @return Response
     * @Route("/sitemap.{_format}",
     *     name="sitemap",
     *     requirements={"_format" = "xml"}
     *     )
     */
    public function siteMap(
        ApiManager $apiManager
    ) {
        if(!$site = $apiManager->getSite($this->getParameter('api_end_point'))) {
            return new Response('Sorry, no site found.', Response::HTTP_BAD_REQUEST);
        } else {

            # pages
            $pages = $apiManager->getPages($apiManager, $site);

            # return
            return $this->render('sitemap.xml.twig', array(
                'site' => $site,
                'pages' => $pages
            ));
        }
    }

    /**
     * @param ApiManager $apiManager
     * @param            $siteId
     * @param            $_locale
     * @param string     $slug
     * @return Response
     * @Route("/example/{_locale}/{siteId}/{slug}",
     *     name="example",
     *     defaults={"_locale"="nl"},
     *     requirements={"slug"="[^+]+", "_locale"="nl|en"}
     * )
     */
    public function example(
        ApiManager $apiManager,
        $siteId,
        $_locale,
        $slug = 'home'
    ) {
        if (!$site = $apiManager->getSite($this->getParameter('api_end_point_example') . $siteId)) {
            return new Response('Sorry, no site found[1]', Response::HTTP_BAD_REQUEST);
        } elseif(!$page = $apiManager->getPage($apiManager, $site, $_locale, $slug)) {
            return new Response('Sorry, no page found[2]', Response::HTTP_BAD_REQUEST);
        } elseif(empty($page['route'])) {
            return new Response('Sorry, no page found[3]', Response::HTTP_NOT_FOUND);
        } else {
            return $this->render($site['template'] . '/' . $page['route'] . '/' . $page['type'] . '.html.twig', [
                'site' => $site,
                'page' => $page
            ]);
        }
    }

    /**
     * @param ApiManager          $apiManager
     * @param TranslatorInterface $translator
     * @param Request             $request
     * @param                     $_locale
     * @param string              $slug
     * @return Response
     * @Route("/{_locale}/{slug}",
     *     name="default",
     *     defaults={"_locale"="nl"},
     *     requirements={"slug"="[^+]+", "_locale"="nl|en"}
     * )
     */
    public function index(
        ApiManager $apiManager,
        TranslatorInterface $translator,
        Request $request,
        $_locale,
        $slug = 'home'
    ) {
        if(!$site = $apiManager->getSite($this->getParameter('api_end_point'))) {
            return new Response('Sorry, no site found[1]', Response::HTTP_BAD_REQUEST);
        } elseif(!$page = $apiManager->getPage($apiManager, $site, $_locale, $slug)) {
            return new Response('Sorry, no page found[1]', Response::HTTP_BAD_REQUEST);
        } elseif(empty($page['route'])) {
            return new Response('Sorry, no page found[2]', Response::HTTP_NOT_FOUND);
        } elseif($slug == 'search') {

            # search
            $search = $apiManager->getSearch($apiManager, $site, $_locale, $request->get('needle'));

            # return
            return $this->render($site['template'] . '/pages/search-01.html.twig', [
                'site' => $site,
                'page' => $page,
                'search' => $search
            ]);

        } else {

            # return
            return $this->render($site['template'] . '/' . $page['route'] . '/' . $page['type'] . '.html.twig', [
                'site' => $site,
                'page' => $page
            ]);
        }
    }

    /**
     * @param ApiManager          $apiManager
     * @param string              $slug
     * @return Response
     * @Route("/{slug}",
     *     name="redirect",
     *     requirements={"slug"="[^+]+"}
     * )
     */
    public function redirector(
        ApiManager $apiManager,
        $slug = 'home'
    ) {
        if(!$site = $apiManager->getSite($this->getParameter('api_end_point'))) {
            return new Response('Sorry, no site found[1]', Response::HTTP_BAD_REQUEST);
        } else {

            # page
            $page = $apiManager->getPage($apiManager, $site, $site['locale'], $slug);

            # 404
            if($page['type'] === '404') {

                # return
                return new Response(
                    $this->render($site['template'] . '/' . $page['route'] . '/404.html.twig', [
                        'site' => $site,
                        'page' => $page
                    ]), Response::HTTP_NOT_FOUND);

            } else {

                # return
                return $this->redirectToRoute('default', [
                    'slug' => $slug,
                    '_locale' => $page['page']['locale']
                ], Response::HTTP_MOVED_PERMANENTLY);
            }
        }
    }
}
