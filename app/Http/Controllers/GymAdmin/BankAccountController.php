<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\BankBranch;
use App\Models\BankLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankAccountController extends GymAdminBaseController
{
    public function index()
    {
        $this->data['bank_accounts'] = BankAccount::where('branch_id', '=', $this->data['user']->detail_id)->get();
        $this->data['banks'] = Bank::where('branch_id', '=', $this->data['user']->detail_id)->get();
        $this->data['branches'] = BankBranch::where('branch_id', '=', $this->data['user']->detail_id)->get();
        $this->data['title'] = "Banks Accounts";
        return view('gym-admin.setting.bank-account', $this->data);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'account_number' => 'required|unique:bank_accounts',
            'balance'        => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        $bank = new BankAccount();
        $bank->branch_id = $this->data['user']->detail_id;
        $bank->bank_id = $request->bank_id;
        $bank->bank_branch_id = $request->bank_branch_id;
        $bank->account_number = $request->account_number;
        $bank->balance = $request->balance;
        $bank->save();

        if ($request->balance > 0) {
            $bankLedger = new BankLedger();
            $bankLedger->branch_id = $this->data['user']->detail_id;
            $bankLedger->bank_account_id = $bank->id;
            $bankLedger->transaction_type = 'initial';
            $bankLedger->transaction_method = 'None';
            $bankLedger->date = date('m/d/Y');
            $bankLedger->remarks = 'Initial Balance';
            $bankLedger->amount = $request->balance;
            $bankLedger->save();
        }
        return redirect()->back()->with('message', 'Bank account added successfully');
    }


    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'account_number' => 'required|unique:bank_accounts,account_number,'.$id,
            'balance'        => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        $bank = BankAccount::findorfail($id);
        $bank->account_number = $request->account_number;
        $bank->balance = $request->balance;
        $bank->save();

        $bankLedger = BankLedger::where('bank_account_id', $id)->first();
        $bankLedger->amount = $request->balance;
        $bankLedger->save();

        return redirect()->back()->with('message', 'Bank account updated successfully');
    }


    public function delete($id)
    {
        $bank = BankAccount::find($id);
        $bankLedgers = BankLedger::where('bank_account_id', $id)->get();
        if($bankLedgers->count() > 0){
            return Reply::error("Unable to remove.Bank account has ledgers.");
        }
        $bank->delete();
        return Reply::success("Bank account deleted successfully.");
    }

    public function get_bank_branches($bank_id)
    {
        $bank = Bank::where('id', $bank_id)->first();
        return $bank->branches;
    }
}
