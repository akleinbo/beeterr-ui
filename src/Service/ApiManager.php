<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2019-02-28
 * Time: 11:36
 */

namespace App\Service;

class ApiManager
{

    /**
     * @param $url
     * @return mixed
     */
    public function curlRequest($url)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch), true);

        curl_close($ch);

        return $response;
    }

    /**
     * @param $url
     * @return mixed
     */
    public function getSite($url)
    {
        return $this->curlRequest($url);
    }

    /**
     * @param            $site
     * @param ApiManager $apiManager
     * @return mixed
     */
    public function getPages(ApiManager $apiManager, $site)
    {
        $url = $site['api']['api_pages'];
        $url = str_replace('_LOCALE_', $site['locale'], $url);

        return $apiManager->curlRequest($url);
    }

    /**
     * @param ApiManager $apiManager
     * @param            $site
     * @param            $slug
     * @return mixed
     */
    public function getPage(ApiManager $apiManager, $site, $slug = null)
    {
        $url = $site['api']['api_page'];
        $url = str_replace('_LOCALE_', $site['locale'], $url);
        $url = str_replace('_PAGE_SLUG_', $slug, $url);

        return $apiManager->curlRequest($url);
    }

    /**
     * @param ApiManager $apiManager
     * @param            $site
     * @param            $needle
     * @return mixed
     */
    public function getSearch(ApiManager $apiManager, $site, $needle = '')
    {
        $url = $site['api']['api_search'];
        $url = str_replace('_LOCALE_', $site['locale'], $url);
        $url = str_replace('_NEEDLE_', $needle, $url);

        return $apiManager->curlRequest($url);
    }
}
