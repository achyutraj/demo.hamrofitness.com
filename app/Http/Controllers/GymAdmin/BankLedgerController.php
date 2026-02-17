<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\BankAccount;
use App\Models\BankLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankLedgerController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['account'] = 'active';
        $this->data['bankLedgerMenu'] = 'active';
    }

    public function index()
    {
        $this->data['accounts'] = BankAccount::has('bank')->where('branch_id', '=', $this->data['user']->detail_id)->get();
        $this->data['ledgers'] = BankLedger::where('branch_id', '=', $this->data['user']->detail_id)->get();
        $this->data['title'] = "Bank Ledger";
        return view('gym-admin.bank-ledger.index', $this->data);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'bank_account' => 'required',
            'transaction_type' => 'required|string',
            'amount' => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

        $bank = new BankLedger();
        $bank->branch_id = $this->data['user']->detail_id;
        $bank->bank_account_id = $request->bank_account;
        $bank->transaction_type = $request->transaction_type;
        $bank->transaction_method = $request->transaction_method;
        $bank->date = $request->date;
        $bank->remarks = $request->remarks;
        $bank->amount = $request->amount;
        $bank->save();
        return redirect()->back()->with('message', ucfirst($request->transaction_type) . ' added successfully');
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'transaction_type' => 'required|string',
            'amount' => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        $bank = BankLedger::findorfail($id);
        $bank->transaction_type = $request->transaction_type;
        $bank->transaction_method = $request->transaction_method;
        $bank->date = $request->date;
        $bank->remarks = $request->remarks;
        $bank->amount = $request->amount;
        $bank->save();
        return redirect()->back()->with('message', ucfirst($request->transaction_type) . ' updated successfully');
    }

    public function delete($id)
    {
        $bank = BankLedger::find($id);
        if($bank->transaction_type == 'initial'){
            return Reply::error("Sorry! Cannot delete initial balance.");
        }
        $bank->delete();
        return Reply::success("Transaction deleted successfully.");
    }
}
