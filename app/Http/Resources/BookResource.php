<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this->reviews);
        if ($this->reviews->isEmpty()) {
            $review_avg = 0;
            $review_count = 0;
        } else {
            $review_avg = $this->reviews[0]->average;
            $review_count = $this->reviews[0]->count;
        }
        return [
            // @TODO implement
                'id' => $this->id ,
                'isbn' => $this->isbn ,
                'title' => $this->title ,
                'description' => $this->description,
                'published_year' => $this-> published_year,
                'authors' => $this->authors,
                'review' => [
                    'average' => $review_avg,
                    'count' => $review_count
                ]
        ];
    }

    public function with($request){
        return [
          'status'=>'success'
        ];
    }
}