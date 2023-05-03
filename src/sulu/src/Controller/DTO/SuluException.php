<?php

declare(strict_types=1);

namespace App\Controller\DTO;

class SuluException
{
    public function __construct(public readonly ?string $detail = null, public readonly ?string $title = null)
    {
    }
}
