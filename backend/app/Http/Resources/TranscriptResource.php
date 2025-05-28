<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TranscriptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "call_id" => $this->call_id ?? 0,
            "received" => $this->time_answered ?? "00:00",
            "duration" => $this->time_format($this->duration ?? 0),
            "status" => isset($this->id) ? 1 : 0
        ];
    }

    public function time_format($seconds){
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        $time = sprintf('%02d:%02d',$minutes, $seconds);
        return $time;
    }
}
