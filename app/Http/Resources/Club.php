<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Club extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->idClub,
            'name' => $this->Name,
            'desc' => $this->Description,
            'location' => $this->Location,
            'created_by' => $this->CreatedBy,
            'master' => $this->Master,
            'start_date' => $this->StartDateTime,
            'end_date' => $this->EndDateTime,
        ];
    }
}
