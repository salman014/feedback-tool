<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Feedback;
use App\Models\FeedbackComment;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{

    public function addfeedback(Request $request){

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category' => 'required',
        ]);

        Feedback::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ]);

        return response()->json(['message' => 'Successfully Created'], 201);
    }

    public function feedbackListing(Request $request){
        $pageNumber = $request->query('page_number', 1);

        $perPage = 5;
        $offset = ($pageNumber - 1) * $perPage;

        $feedbacks = Feedback::with('user:name,id') 
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->take($perPage)
            ->get();

        $totalCount = Feedback::count();
        $totalPages = ceil($totalCount / $perPage);

        return response()->json([
            'feedbacks' => $feedbacks,
            'total_pages' => $totalPages,
            'current_page' => $pageNumber,
            'total_feedbacks' => $totalCount,
        ]);
    }

    public function feedbackDetail(Request $request){
        $feedbackId = $request->query('id');
    
        // Retrieve the feedback along with user information
        $feedback = Feedback::with('user:name,id')
            ->where('id', $feedbackId)
            ->orderBy('created_at', 'desc')
            ->first();
    
        // Retrieve comments associated with the feedback
        $comments = FeedbackComment::with('user:name,id')
            ->where('feedback_id', $feedbackId)
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Now $feedback will contain the feedback details, and $comments will contain the associated comments
        return response()->json([
            'feedback' => $feedback,
            'comments' => $comments,
        ]);
    }

    public function addFeedbackComment(Request $request){
        // Validate the request
        $request->validate([
            'feedback_id' => 'required|exists:feedbacks,id',
            'content' => 'required|string',
        ]);

        // Create a new comment
        $comment = new FeedbackComment([
            'feedback_id' => $request->input('feedback_id'),
            'user_id' => $request->user()->id,
            'content' => $request->input('content'),
        ]);

        // Save the comment
        $comment->save();

        // You can return a response or any other logic based on your application needs
        return response()->json(['message' => 'Comment added successfully']);
    }

    public function mentionUsers(Request $request){
        // Validate the request
        $request->validate([
            'search' => 'required|string|min:1', // Adjust validation rules as needed
        ]);

        // Get users based on the search term
        $searchTerm = $request->input('search');
        $users = User::where('name', 'LIKE', "%$searchTerm%")
            ->select('id', 'name as value') // Adjust columns as needed
            ->get();

        return response()->json(['users' => $users]);
    }
    

}
