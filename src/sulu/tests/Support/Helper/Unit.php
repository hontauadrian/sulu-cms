<?php

declare(strict_types=1);

namespace Tests\Support\Helper;

use Codeception\Module;

class Unit extends Module
{
    public const PATH_TO_TILE_MAPPER = 'Mapper/tileMapper.json';
    public const PATH_TO_PRELOGIN_SLIDER_MAPPER = 'Mapper/preloginSliderMapper.json';
    public const PATH_TO_CAMPAIGN_MAPPER = 'Mapper/campaignMapper.json';

    public function getJsonFile($jsonFileRoute): string
    {
        return codecept_data_dir() . $jsonFileRoute;
    }

    public function getJsonFileContent($jsonFileRoute): string
    {
        return \file_get_contents(codecept_data_dir() . $jsonFileRoute);
    }
}
