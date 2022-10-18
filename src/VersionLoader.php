<?php
declare(strict_types=1);

namespace App;

use App\Entity\Version;
use App\Factory\VersionFactory;
use Symfony\Component\DomCrawler\Crawler;

class VersionLoader
{
    const CHANGELOG_URL='https://www.shopware.com/en/changelog';

    public function getChangelog(): array
    {
        $html = file_get_contents(self::CHANGELOG_URL);

        $crawler = new Crawler($html);

        /*$crawler
            ->filter('.release-details--cta > a')
            ->reduce(function (Crawler $node, $i) {
                //$versions[] = $node->attr('href'));
                VersionFactory::create($node->attr('href'));
            });*/

        $accordions = $crawler
            ->filter('.accordion--column > .accordion');

        $versions = $accordions->each(function (Crawler $node) {
           $versionNo = str_replace('-', '.', $node->first()->attr('id'));
           $urls = $node->filter('.release-details--cta > a')->each(function (Crawler $node) {
               return $node->attr('href');
           });

           return new Version($versionNo, $urls[0] ?? null, $urls[1] ?? null);
        });

        return $versions;
    }
}