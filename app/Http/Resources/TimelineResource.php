<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class TimelineResource extends Resource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'areaName' => $this->restaurant->area->name,
            'name' => $this->restaurant->name,
            'opened_at' => $this->opened_at->format('Y-m-d H:i:s'),
            'closed_at' => $this->closed_at->format('Y-m-d H:i:s'),
            'opened_at_after_break' => optional($this->opened_at_after_break)->format('Y-m-d H:i:s'),
            'closed_at_after_break' => optional($this->closed_at_after_break)->format('Y-m-d H:i:s'),
        ];
    }
}
