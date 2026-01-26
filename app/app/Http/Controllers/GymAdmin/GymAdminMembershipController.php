<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\BusinessCategory;
use App\Models\GymMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class GymAdminMembershipController extends GymAdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
        $this->data['membershipMenu'] = 'active';
    }

    public function index() {
        if(!$this->data['user']->can("view_membership"))
        {
            return App::abort(401);
        }

        $this->data['membershipindexMenu'] = 'active';
        $this->data['title'] = "All Membership";
        $this->data['memberships'] = GymMembership::withCount('subscriptions')->where('detail_id',$this->data['user']->detail_id)->get();
        return view('gym-admin.membership.index', $this->data);
    }

    public function create() {
        if(!$this->data['user']->can("add_membership"))
        {
            return App::abort(401);
        }

        $this->data['membershipindexMenu'] = '';
        $this->data['membershipcreateMenu'] = 'active';
        $this->data['title'] = "Add Membership";

        $this->data['subcategories'] = BusinessCategory::businessCategories($this->data['user']->detail_id);

        return view('gym-admin.membership.create', $this->data);
    }

    public function store(Request $request) {
        if(!$this->data['user']->can("add_membership"))
        {
            return App::abort(401);
        }

        $validator = Validator::make($request->all(), GymMembership::rules('add', $request->get('business_sub_category_id')));

        $validator->sometimes('next_payment_date', 'required', function($input) {
            return $input->payment_required == 'yes';
        });

        if($validator->fails()) {
            return Reply::formErrors($validator);
        }
        else{
            $category = BusinessCategory::where('detail_id',$this->data['user']->detail_id)->first();
            $inputData = $request->all();
            $inputData['detail_id'] = $this->data['user']->detail_id;
            $inputData['business_category_id'] = $category->id;
            $inputData['status'] = 'active';
            if($request->get('duration_type') == 'unlimited'){
                $inputData['duration'] = 0;
            }
            $membership = GymMembership::create($inputData);

            // Track creation
            $membership->trackChange('created', null, null, null, 'Membership plan created');

            return Reply::redirect(route('gym-admin.membership-plans.index'),'Membership added successfully.');
        }
    }

    public function edit($id) {
        if(!$this->data['user']->can("edit_membership"))
        {
            return App::abort(401);
        }

        $this->data['title'] = "Edit Membership";

        $this->data['subcategories'] = BusinessCategory::businessCategories($this->data['user']->detail_id);
        $this->data['membership'] = GymMembership::merchantMembershipDetail($id, $this->data['user']->detail_id);

        return view('gym-admin.membership.edit', $this->data);
    }

    public function update(Request $request, $id) {
        if(!$this->data['user']->can("edit_membership"))
        {
            return App::abort(401);
        }

        $validator = Validator::make($request->all(), GymMembership::rules('edit', $request->get('business_sub_category_id'), $id));

        if($validator->fails()) {
            return Reply::formErrors($validator);
        }
        else{
            $category = BusinessCategory::first();
            $inputData = $request->all();
            $membership = GymMembership::find($id);

            // Store old values before updating
            $oldDuration = $membership->duration;
            $oldDurationType = $membership->duration_type;

            // Track changes before updating
            $changes = [];
            $hasDurationChanges = false;
            $durationChangeReason = '';

            if ($membership->title != $inputData['title']) {
                $changes['title'] = ['old' => $membership->title, 'new' => $inputData['title']];
            }
            if ($membership->price != $inputData['price']) {
                $changes['price'] = ['old' => $membership->price, 'new' => $inputData['price']];
            }
            if ($membership->details != $inputData['details']) {
                $changes['details'] = ['old' => $membership->details, 'new' => $inputData['details']];
            }

            // Check for duration-related changes
            if ($oldDuration != $inputData['duration'] || $oldDurationType != $inputData['duration_type']) {
                $hasDurationChanges = true;
                $durationChangeReason = 'Duration updated from ' . $oldDuration . ' ' . $oldDurationType . '(s) to ' . $inputData['duration'] . ' ' . $inputData['duration_type'] . '(s)';
            }

            // Update membership
            $membership->title = $inputData['title'];
            $membership->price = $inputData['price'];
            if($inputData['duration_type'] == 'unlimited'){
                $inputData['duration'] = 0;
            }
            $membership->duration = $inputData['duration'];
            $membership->duration_type = $inputData['duration_type'];
            $membership->business_category_id = $category->id;
            $membership->details = $inputData['details'];
            $membership->updated_at = now();
            $membership->save();

            // Create a single history record for all changes if multiple fields were updated
            if (count($changes) > 0 || $hasDurationChanges) {
                $allChanges = [];

                // Add regular field changes
                foreach ($changes as $field => $values) {
                    $allChanges[] = ucfirst(str_replace('_', ' ', $field)) . ': ' . $values['old'] . ' → ' . $values['new'];
                }

                // Add duration changes if any
                if ($hasDurationChanges) {
                    $allChanges[] = 'Duration: ' . $oldDuration . ' ' . $oldDurationType . '(s) → ' . $inputData['duration'] . ' ' . $inputData['duration_type'] . '(s)';
                }

                // Create single history record with all changes
                $changeReason = 'Updated fields: ' . implode(', ', $allChanges);
                $membership->trackChange('updated', 'multiple_fields',
                    'Previous values',
                    'New values',
                    $changeReason);
            }

            return Reply::redirect(route('gym-admin.membership-plans.index'),'Membership updated successfully.');
        }
    }

    public function destroy($id) {
        if(!$this->data['user']->can("delete_membership"))
        {
            return App::abort(401);
        }

        if(request()->ajax()){

            $membership = GymMembership::find($id);
            if($membership->subscriptions()->count() > 0){
                return Reply::error("Membership has subscription.");
            }

            // Track deletion
            $membership->trackChange('deleted', null, null, null, 'Membership plan deleted');

            $membership->delete();
            return Reply::success("Membership deleted successfully.");
        }
    }

    public function history($id)
    {
        if(!$this->data['user']->can("view_membership"))
        {
            return App::abort(401);
        }

        $this->data['membership'] = GymMembership::with(['membershipHistories.changedByUser'])->find($id);
        $this->data['title'] = "Membership History - " . $this->data['membership']->title;
        return view('gym-admin.membership.history', $this->data);
    }
}
