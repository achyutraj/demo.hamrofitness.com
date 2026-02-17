<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\Bank;
use App\Models\BankBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankController extends GymAdminBaseController
{

    public function index()
    {
        $this->data['banks'] = Bank::where('branch_id', '=', $this->data['user']->detail_id)->get();
        $this->data['branches'] = BankBranch::where('branch_id', '=', $this->data['user']->detail_id)->get();
        $this->data['title'] = "Banks & Branches";
        return view('gym-admin.setting.bank-and-branch', $this->data);
    }


    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|unique:banks,name',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        $bank = new Bank();
        $bank->branch_id = $this->data['user']->detail_id;
        $bank->name = $request->name;
        $bank->save();
        return redirect()->back()->with('message', 'Bank added successfully');
    }


    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|unique:banks,name,'.$id,
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        $bank = Bank::findorfail($id);
        $bank->name = $request->name;
        $bank->save();
        return redirect()->back()->with('message', 'Bank updated successfully');
    }


    public function delete($id)
    {
        $bank = Bank::find($id);
        if($bank->branches->count() > 0) {
            return Reply::error("Unable to remove. Bank has branches.");
        }
        $bank->delete();
        return Reply::success("Bank deleted successfully.");
    }
}
