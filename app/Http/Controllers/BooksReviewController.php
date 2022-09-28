<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookReview;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookReviewResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BooksReviewController extends Controller
{
    public function __construct()
    {

    }

    public function store(int $bookId, PostBookReviewRequest $request)
    {
        // @TODO implement
        $validator = Validator::make($request->all(), [
            'review' => 'required|integer|between:1,10',
            'comment' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status' => 'Failed' , 'message'=> $validator->errors() ], 422);
        }
        $dataBook = Book::find($bookId);
        if (!$dataBook) {
            return response()->json(['status' => 'Failed' , 'message'=> 'invalid Book ID' ], 404);
        }

        $bookReview = new BookReview();
        $bookReview->book_id = $bookId;
        $bookReview->user_id = Auth::user()->id;
        $bookReview->review = $request->review;
        $bookReview->comment = $request->comment;
        $bookReview->save();

        return new BookReviewResource($bookReview);
    }

    public function destroy(int $bookId, int $reviewId, Request $request)
    {
        // @TODO implement
        $dataBook = Book::find($bookId);
        if($dataBook){
            $del = BookReview::find($reviewId);
            if ($del) {
                $del->delete();
                return response()->json([],204);
            }
            return response()->json(['status' => 'Failed' , 'message'=> 'invalid Book Review ID' ], 404);
        } else {
            return response()->json(['status' => 'Failed' , 'message'=> 'invalid Book ID' ], 404);
        }
    }
}
