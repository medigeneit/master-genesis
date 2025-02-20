<?php

namespace Medigeneit\MasterGenesis\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BatchInfoForBookingResource extends JsonResource
{

    public static $wrap = 'batch';

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
            "year" => $this->year,
            "capacity" => $this->capacity,
            "status" => $this->status,
            "system_driven" => $this->system_driven,
            "module_id" => $this->module_id,
            "expired_at" => $this->expired_at,
            "expired_message" => $this->expired_message,
            "session" => $this->whenLoaded('session',$this->getSession()),
            "course" => $this->whenLoaded('course',$this->getCourse()),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }

    protected function getCourse()
    {
        return [
            'id' => $this->course->id ?? 0,
            'name' => $this->course->name ?? null,
            'institute' => $this->whenLoaded('institute',$this->getInstitute()),
        ];
    }

    protected function getSession()
    {
        return [
            'id' => $this->session->id ?? 0,
            'name' => $this->session->name ?? null,
            'year' => $this->session->year ?? null,
            'duration' => $this->session->duration ?? null,
        ];
    }

    protected function getInstitute()
    {
        return [
            'id' => $this->course->institute->id ?? 0,
            'name' => $this->course->institute->name ?? null
        ];
    }
}
