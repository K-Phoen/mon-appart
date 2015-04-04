<?php

namespace Crawler;

use Symfony\Component\DomCrawler\Crawler;

class Leboncoin implements OfferCrawler
{
    private $searchUrlBuilder;

    private $paramsMap = [
        'Ville :'               => 'city',
        'Code postal :'         => 'zip_code',
        'Charges comprises :'   => 'including_charges',
        'Type de bien :'        => 'type',
        'Pièces :'              => 'rooms',
        'Meublé / Non meublé :' => 'includes_furnitures',
        'Surface :'             => 'area',
    ];

    public function __construct(LeboncoinSearchUrlBuilder $searchUrlBuilder)
    {
        $this->searchUrlBuilder = $searchUrlBuilder;
    }

    public function fetchResultsLinks(array $criteria)
    {
        $searchUrl = $this->searchUrlBuilder->buildUrl($criteria);
        $crawler   = new Crawler($this->fetchUrlContent($searchUrl));

        $links = $crawler->filter('.list-lbc a');

        $resultLinks = [];
        foreach ($links as $link) {
            // skip "alerts" links
            if (strpos($link->getAttribute('class'), 'alertsLink') !== false) {
                continue;
            }

            $resultLinks[] = $link->getAttribute('href');
        }

        return $resultLinks;
    }

    public function fetchLink($link)
    {
        $html    = $this->fetchUrlContent($link);
        $crawler = new Crawler($html);
        $data    = [
            'url'    => $link,
            'origin' => 'leboncoin.fr',
        ];

        // title
        $data['title'] = trim(str_replace(' - leboncoin.fr', '', $crawler->filter('title')->text()));

        // price
        $data['price'] = intval($crawler->filter('span.price')->text());

        // params
        $crawler->filter('.lbcParams tr')->each(function($node) use (&$data) {
            if (count($node->filter('th')) === 0) { // happens when the GSP coordinates are in the source
                return;
            }

            $header = trim($node->filter('th')->text());

            if (!empty($this->paramsMap[$header])) {
                $data[$this->paramsMap[$header]] = $node->filter('td')->text();
            }
        });

        // fix a few params
        $data['area']                = isset($data['area']) ? intval($data['area']) : null;
        $data['rooms']               = isset($data['rooms']) ? intval($data['rooms']) : null;
        $data['includes_furnitures'] = isset($data['includes_furnitures']) ? $data['includes_furnitures'] === 'Meublé' : null;
        $data['including_charges']   = isset($data['including_charges']) ? $data['including_charges'] === 'Oui' : null;

        // main picture
        $data['thumb'] = $crawler->filter('meta[property="og:image"]')->attr('content');

        // description
        $data['description'] = $crawler->filter('.AdviewContent .content')->html();

        // pictures
        $data['pictures'] = [];
        if (preg_match_all('`aImages\[\d+\] = "([^"]+)";`', $html, $matches)) {
            $data['pictures'] = $matches[1];
        }

        return array_filter($data);
    }

    private function fetchUrlContent($url)
    {
        return file_get_contents($url);
    }
}
