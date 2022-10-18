<?php
declare(strict_types=1);

namespace App\Entity;

class Version
{
    private string $versionNo;
    private ?string $installUrl;
    private ?string $updateUrl;

    public function __construct(string $versionNo, ?string $installUrl, ?string $updateUrl)
    {
        $this->versionNo = $versionNo;
        $this->installUrl = $installUrl;
        $this->updateUrl = $updateUrl;
    }

    public function getVersionNo(): string
    {
        return $this->versionNo;
    }

    public function getInstallUrl(): ?string
    {
        return $this->installUrl;
    }

    public function getUpdateUrl(): ?string
    {
        return $this->updateUrl;
    }
}