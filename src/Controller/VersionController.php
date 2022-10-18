<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Version;
use App\VersionLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VersionController extends AbstractController
{
    public function __construct(
        private VersionLoader $versionLoader
    ) {
    }

    #[Route('/version/{versionNo}', name: 'version')]
    public function version(string $versionNo): Response
    {
        $versions = $this->versionLoader->getChangelog();

        // Simply return latest version if version no. is not given in request
        if (!$versionNo) {
            return new RedirectResponse($versions[0]->getInstallUrl());
        }

        // Easy solution - TODO: Refacator later
        /** @var Version $version */
        foreach ($versions as $version) {
            if ($version->getVersionNo() === $versionNo) {
                return new RedirectResponse($version->getInstallUrl());
            }
        }

        return new Response('Version not found', Response::HTTP_NOT_FOUND);
    }
}