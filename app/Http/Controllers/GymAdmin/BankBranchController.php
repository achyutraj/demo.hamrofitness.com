<?php
namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\Bank;
use App\Models\BankBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankBranchController extends GymAdminBaseController
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
        $validate = Validator::make($request->all(),[
            'name' => 'required|unique:bank_branches,name',
        ]);
        if ($validate->fails()){
            return redirect()->back()->withErrors($validate);
        }
        $bank = new BankBranch();
        $bank->branch_id = $this->data['user']->detail_id;
        $bank->bank_id = $request->bank_id;
        $bank->name = $request->name;
        $bank->save();
        return redirect()->back()->with('message','Branch added successfully');
    }


    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(),[
            'name' => 'required|unique:bank_branches,name,'.$id,
        ]);
        if ($validate->fails()){
            return redirect()->back()->withErrors($validate);
        }
        $bank = BankBranch::findorfail($id);
        $bank->name = $request->name;
        $bank->save();
        return redirect()->back()->with('message','Branch updated successfully');
    }


    public function delete($id)
    {
        $bank = BankBranch::find($id);
        if($bank->bank_accounts->count() > 0){
            return Reply::error("Unable to remove. Branch has some accounts.");
        }
        $bank->delete();
        return Reply::success("Branch deleted successfully.");
    }
}
