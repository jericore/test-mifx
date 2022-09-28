<?php

namespace App\Http\Controllers;

use App\Book;
use App\Author;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        // @TODO implement
        $page = request('page');
        $title = request('title');
        $authorsId = request('authors');
        $sort = request('sort');
        $sort = ($sort == '') ? ['property' => 'title', 'direction' => 'ASC'] : json_decode(request('sort'), true)[0];

        $dataBook = Book::whereHas('authors' ,function($q) use($authorsId){
            if (!empty($authorsId)) {
                $q->where('authors.id', '=', $authorsId);
            }
        })->with('reviews')
        ->when(!empty($title), function($query) use($title){
            return $query->where('books.title', 'LIKE', "%$title%");
        })->orderBy($sort['property'], $sort['direction'])->paginate(15);
        
        return BookResource::collection($dataBook);
    }

    public function store(PostBookRequest $request)
    {
        // @TODO implement
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|unique:books,isbn|numeric|regex:/^(\d{13})$/',
            'title' => 'required',
            'description' => 'required',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
            'published_year' => 'required|integer|between:1900,2020'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'Failed' ,'state'=>'100' , 'message'=> $validator->errors() ], 422);
        }
        
        $book = new Book();
        $book->isbn = $request->isbn; 
        $book->title = $request->title;
        $book->description = $request->description;
        $book->published_year = $request->published_year;
        $book->save();
        $book->authors()->attach($request->authors);

        return new BookResource($book);
    }

}
