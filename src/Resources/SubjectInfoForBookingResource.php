<?php


namespace Medigeneit\MasterGenesis\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectInfoForBookingResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            'course' => $this->whenLoaded('course', fn () => new CourseResource($this->course)),

        ];
    }
}
