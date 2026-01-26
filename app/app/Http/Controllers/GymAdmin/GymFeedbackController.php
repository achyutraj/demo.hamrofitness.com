<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\Review;
use App\Models\ReviewComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class GymFeedbackController extends GymAdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
        $this->data['feedbackMenu'] = 'active';
    }

    public function index() {
        if (!$this->data['user']->can("view_feedback")) {
            return App::abort(401);
        }

        $this->data['title'] = 'FeedBack';
        $this->data['reviews'] = Review::where('detail_id', $this->data['user']->detail_id)->orderBy('id', 'asc')
            ->get();
        $this->data['totalReviews'] = Review::where('detail_id', $this->data['user']->detail_id)
            ->where('review_text', '<>', '')
            ->status('approved')
            ->count();
        return view('gym-admin.feedback.index', $this->data);
    }

    public function ajaxLoadComments() {
        if (!$this->data['user']->can("view_feedback")) {
            return App::abort(401);
        }

        $reviewId = request()->get('id');
        $this->data['comments'] = ReviewComment::where('review_id',$reviewId)->orderBy('id', 'desc')->take(1)->get();
        $this->data['total'] = ReviewComment::where('review_id', $reviewId)->count();
        $this->data['count'] = count($this->data['comments']);
        return view('gym-admin.feedback.ajaxloadcomments', $this->data);
    }

    public function loadMoreComments() {
        if (!$this->data['user']->can("view_feedback")) {
            return App::abort(401);
        }

        $take = request()->get('take');
        $skip = request()->get('skip');
        $reviewID = request()->get('id');

        $this->data['comments'] = ReviewComment::reviewComments($reviewID, $take, $skip);
        $this->data['total'] = 0;
        $this->data['count'] = 0;

        return view('gym-admin.feedback.ajaxloadcomments', $this->data);
    }

    public function postReply() {
        if (!$this->data['user']->can("view_feedback")) {
            return App::abort(401);
        }

        $validator = Validator::make(request()->all(), ['reply' => 'required', 'id' => 'required']);
        if ($validator->fails()) {
            $output['success'] = false;
            $output['class'] = 'alert alert-danger';
            $output['error'] = implode('<br><i class="fa fa-close" ></i> ', $validator->messages()
                ->all());
        }
        else {
            $reviewId = request()->get('id');
            $reply_text = request()->get('reply');
            $reply = new ReviewComment();
            $reply->review_id = $reviewId;
            $reply->comment = $reply_text;
            $reply->client_id = null;
            $reply->read_review = 0;
            $reply->merchant_id = $this->data['user']->id;
            $reply->save();
            $output['success'] = true;
            $output['class'] = 'alert alert-info';
            $output['error'] = 'Reply Posted Successfully';
        }
        return json_encode($output);
    }


    public function removeReply($id) {
        if (!$this->data['user']->can("view_feedback")) {
            return App::abort(401);
        }

        $this->data['comment'] = ReviewComment::select('comment', 'id')->where('id', '=', $id)->first();
        return view('gym-admin.feedback.destroy', $this->data);
    }

    public function destroy($id) {
        if (!$this->data['user']->can("view_feedback")) {
            return App::abort(401);
        }

        $client = ReviewComment::find($id);
        $client->delete();
        return Reply::redirect(route('gym-admin.feedback.index'), "Reply Removed successfully");
    }

    public function edit() {
        if (!$this->data['user']->can("view_feedback")) {
            return App::abort(401);
        }

        $comment = request()->get('reply');
        $data = ReviewComment::find(request()->get('id'));
        $data->comment = $comment;
        $data->save();
        $output['success'] = true;
        $output['class'] = 'alert alert-info';
        $output['error'] = 'Reply Updated Successfully';
        return json_encode($output);
    }

}
