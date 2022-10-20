<?php

declare(strict_types=1);

namespace App;

use App\Entity\Version;
use Symfony\Component\DomCrawler\Crawler;

class VersionLoader
{
    public const CHANGELOG_URL = 'https://www.shopware.com/en/changelog';
    public const S3_BASE_URL   = 'https://releases.shopware.com/sw6';

    public function getChangelog(): array
    {
        $html = file_get_contents(self::CHANGELOG_URL);

        $crawler = new Crawler($html);

        $accordions = $crawler
            ->filter('.accordion--column > .accordion');

        return $accordions->each(function (Crawler $node) {
            $versionNo = str_replace('-', '.', $node->first()->attr('id'));
            $urls      = $node->filter('.release-details--cta > a')->each(function (Crawler $node) {
                return $this->getRealUrl($node->attr('href'));
            });

            return new Version($versionNo, $urls[0] ?? null, $urls[1] ?? null);
        });
    }

    public function getSpecificVersion(string $versionNo): Version
    {
        $versions          = $this->splitChangelog($this->getChangelog());
        $splittedVersionNo = $this->splitVersionNo($versionNo);

        // TODO: This is ugly, but works. @future me: Please refactor this.
        if (!$splittedVersionNo['base']) {
            $base = array_shift($versions);
        } else {
            $base = $versions[$splittedVersionNo['base']];
        }

        if (!$splittedVersionNo['major']) {
            $major = array_shift($base);
        } else {
            $major = $base[$splittedVersionNo['major']];
        }

        if (!$splittedVersionNo['minor']) {
            $minor = array_shift($major);
        } else {
            $minor = $major[$splittedVersionNo['minor']];
        }

        if (!$splittedVersionNo['patch']) {
            $patch = array_shift($minor);
        } else {
            $patch = $minor[$splittedVersionNo['patch']];
        }

        return $patch;
    }

    /*
     * Returns the real url at Amazon S3 after the redirect
     */
    private function getRealUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        $url = explode('/', $url);
        $url = array_pop($url);

        return self::S3_BASE_URL.'/'.$url;
    }

    private function splitChangelog(array $changelog): array
    {
        $splittedVersions = [];

        /** @var Version $version */
        foreach ($changelog as $version) {
            $splittedVersionNo                                                                                                                   = $this->splitVersionNo($version->getVersionNo());
            $splittedVersions[$splittedVersionNo['base']][$splittedVersionNo['major']][$splittedVersionNo['minor']][$splittedVersionNo['patch']] = $version;
        }

        return $splittedVersions;
    }

    private function splitVersionNo(string $versionNo): array
    {
        // TODO: Use regex here
        $versionNo = explode('.', $versionNo);

        return [
            'base'  => $versionNo[0] ?? null,
            'major' => $versionNo[1] ?? null,
            'minor' => $versionNo[2] ?? null,
            'patch' => $versionNo[3] ?? null,
        ];
    }
}
