<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaginationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'current_page' => $this->currentPage(),
            'per_page' => $this->perPage(),
            'last_page' => $this->lastPage(),
            'url_path' => $this->path(),
            //'first_page_url' => $this->url(1),
            //'next_page_url' => $this->nextPageUrl(),
            //'prev_page_url' => $this->previousPageUrl(),
            'record_from' => $this->firstItem(),
            'record_to' => $this->lastItem(),
            'record_count' => $this->count(),
            'total_record' => $this->total(),
        ];
    }
}
