<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarbonApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'Activity' => $this->activity,
            'ActivityType' => $this->activityType,
            'Country' => $this->country,
            'Mode' => $this->mode,
            'FuelType' => $this->fuelType,
            'CarbonFootPrint' => $this->footprint,
        ];
    }
}
