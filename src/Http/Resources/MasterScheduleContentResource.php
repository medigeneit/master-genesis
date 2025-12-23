<?php

namespace Medigeneit\MasterGenesis\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class MasterScheduleContentResource extends JsonResource
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
            'id' => $this->id,
            'type' => $this->type,
            'datetime' => $this->datetime,
            'booking_id' => $this->booking_id,
            'contents' => $this->getContents(),
            // 'exams' => $this->getContents('Exam'),
            // 'lectures' => $this->getContents('Class'),
        ];

        return parent::toArray($request);
    }


    protected function getContents()
    {
        $exams =  $this->schedule_details
            ->where('type', 'Exam')
            ->map(function ($detail) {
                return $this->getContent($detail);
            })->values();

        $lectures =  $this->schedule_details
            ->where('type', 'Class')
            ->map(function ($detail) {
                return $this->getContent($detail);
            })->values();

        $maxLength = max($exams->count(), $lectures->count());

        $contents = [];
        for ($i = 0; $i < $maxLength; $i++) {
            $contents[] = [
                'exam' => $exams[$i] ?? null,
                'lecture' => $lectures[$i] ?? null,
            ];
        }

        return $contents;
    }

    protected function getContent($detail)
    {

        $type = $detail->type == 'Exam' ? 'exam' : 'lecture';

        return [
            'id' => $detail->{$type}->id ?? '',
            'type' => $type,
            'name' => $detail->{$type}->name ?? '',
            'description' => $detail->{$type}->description ?? '',
            'lectures' => $this->solveOrFeedbackClassMap($detail->lectures)
        ];
    }

    // protected function getExams()
    // {

    //     return $this->schedule_details->where('type', 'Exam')->map(function ($detail) {
    //         return [
    //             'id' => $detail->exam->id ?? '',
    //             'name' => $detail->exam->name ?? '',
    //             'description' => $detail->exam->description ?? '',
    //             'lectures' => $this->solveOrFeedbackClassMap($detail->lectures)
    //         ];
    //     })->values();
    // }

    // protected function getLectures()
    // {

    //     return $this->schedule_details->where('type', 'Class')->map(function ($detail) {
    //         return [
    //             'id' => $detail->lecture->id ?? '',
    //             'name' => $detail->lecture->name ?? '',
    //             'description' => $detail->lecture->description ?? '',
    //             'lectures' => $this->solveOrFeedbackClassMap($detail->lectures)
    //         ];
    //     })->values();
    // }

    protected function solveOrFeedbackClassMap(Collection $solveOrFeedbackClasses)
    {
        return $solveOrFeedbackClasses->map(function ($detail) {
            return [
                'id' => $detail->lecture->id ?? '',
                'name' => $detail->lecture->name ?? '',
                'description' => $detail->lecture->description ?? '',
            ];
        });
    }
}
