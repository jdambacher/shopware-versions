<?php

declare(strict_types=1);

namespace App\Controller;

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
    public function version(string $versionNo = '6'): Response
    {
        try {
            $version = $this->versionLoader->getSpecificVersion($versionNo);
        } catch (\Exception $e) {
            return new Response('Version not found', Response::HTTP_NOT_FOUND);
        }

        return new RedirectResponse($version->getInstallUrl());
    }
}
