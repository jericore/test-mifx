<?php

namespace App;
Use DB;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isbn',
        'title',
        'description',
        'published_year'
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function avgRating()
    {
        $result = $this->hasMany(BookReview::class);
        return $result;
    }

    public function reviews()
{
    return $this->avgRating()
      ->selectRaw('avg(review) as average')
      ->selectRaw('count(review) as count, book_id')
      ->groupBy('book_id');
}

}
