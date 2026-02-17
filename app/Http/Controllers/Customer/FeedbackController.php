<?php

namespace App\Http\Controllers\Customer;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class FeedbackController extends CustomerBaseController
{
    public function index(){
        $this->data['title'] = 'Feedback';
        $this->data['reviews'] = Review::withCount('comments')->where('client_id',$this->data['customerValues']->id)->get();
        $this->data['totalReviews'] = count($this->data['reviews']);
        return view('customer-app.feedbacks.index', $this->data);
    }

    public function store(Request $request){
        $review = Review::create([
            'review_text' => $request->get('review_text'),
            'detail_id' => $this->data['customerValues']->detail_id,
            'client_id' => $this->data['customerValues']->id,
            'status' => 'pending'
        ]);
        return Reply::redirect(route('customer-app.feedback.index'), 'Review added successfully.');

    }

    public function show($id){
        $this->data['review'] = Review::findByUid($id);
        return view('customer-app.feedbacks.show', $this->data);
    }

    public function update(Request $request,$id){
        $review = Review::findByUid($id);
        $review->update(['review_text'=>$request->get('review_text')]);
        return redirect()->back()->with('message', 'Review updated successfully');
    }

    public function destroy($id){
        $review = Review::findByUid($id);
        $review->delete();
        return Reply::redirect(route('customer-app.feedback.index'), 'Review deleted successfully.');
    }
}
