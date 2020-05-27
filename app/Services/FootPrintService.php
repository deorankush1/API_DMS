<?php

namespace App\Services;

use App\CarbonFootprint;

class FootPrintService
{
    protected $carbonFootprint;

    public function __construct(CarbonFootprint $carbonFootprint)
    {
        $this->carbonFootprint = $carbonFootprint;
    }

    public function store($request)
    {
        return $this->carbonFootprint::create($request);
    }
}
