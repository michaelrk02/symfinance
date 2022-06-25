<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Bank;
use App\Libraries\Symfinance;

class AccountController extends Controller
{
    protected $depositValidation = [
        'amount' => 'required|integer'
    ];

    protected $withdrawValidation = [
        'amount' => 'required|integer'
    ];

    protected $transferValidation = [
        'dst_bank' => 'required',
        'dst_account' => 'required|integer',
        'amount' => 'required|integer'
    ];

    public function viewAll(Request $request)
    {
        $accounts = Auth::user()->accounts;

        return view('account.viewAll', ['accounts' => $accounts]);
    }

    public function view(Request $request, $id)
    {
        $account = Auth::user()->accounts()->where('id', $id)->first();
        if (!isset($account)) {
            return redirect('/')->withErrors(['error' => 'Account not found']);
        }

        return view('account.view', ['account' => $account]);
    }

    public function viewAllTransactions(Request $request, $id)
    {
        $account = Auth::user()->accounts()->where('id', $id)->first();
        if (!isset($account)) {
            return redirect('/')->withErrors(['error' => 'Account not found']);
        }

        $transactions = Transaction::where(['account' => $id])->orderBy('timestamp', 'desc')->get();

        return view('account.viewAllTransactions', ['account' => $account, 'transactions' => $transactions]);
    }

    public function deposit(Request $request, $id)
    {
        $account = Auth::user()->accounts()->where('id', $id)->first();
        if (!isset($account)) {
            return redirect('/')->withErrors(['error' => 'Account not found']);
        }

        return view('account.deposit', ['account' => $account]);
    }

    public function postDeposit(Request $request, $id)
    {
        $account = Auth::user()->accounts()->where('id', $id)->first();
        if (!isset($account)) {
            return redirect('/')->withErrors(['error' => 'Account not found']);
        }

        $input = $request->validate($this->depositValidation);

        $deposit = Symfinance::deposit($account->balance, $input['amount'], $account->bankData->deposit_fee);

        return view('account.depositConfirm', ['account' => $account, 'deposit' => $deposit]);
    }

    public function postDepositConfirm(Request $request, $id)
    {
        $account = Auth::user()->accounts()->where('id', $id)->first();
        if (!isset($account)) {
            return redirect('/')->withErrors(['error' => 'Account not found']);
        }

        $input = $request->validate($this->depositValidation);

        $deposit = Symfinance::deposit($account->balance, $input['amount'], $account->bankData->deposit_fee);
        if ($deposit['new_balance'] < 0) {
            return back()->withErrors(['error' => 'Your new balance cannot be less than zero!'])->withInput();
        }

        if (!Hash::check($request->input('pin'), $account->pin)) {
            return back()->withErrors(['error' => 'Invalid PIN supplied'])->withInput();
        }

        DB::beginTransaction();
        try {
            $account->balance = $deposit['new_balance'];
            $account->save();

            Transaction::make($id, 'credit', $deposit['balance_change'], $deposit['new_balance'], 'deposit in');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Unable to perform transaction'])->withInput();
        }

        return redirect('/account/view/'.$account->id)->with(['status' => 'Funds deposited successfully!']);
    }

    public function withdraw(Request $request, $id)
    {
        $account = Auth::user()->accounts()->where('id', $id)->first();
        if (!isset($account)) {
            return redirect('/')->withErrors(['error' => 'Account not found']);
        }

        return view('account.withdraw', ['account' => $account]);
    }

    public function postWithdraw(Request $request, $id)
    {
        $account = Auth::user()->accounts()->where('id', $id)->first();
        if (!isset($account)) {
            return redirect('/')->withErrors(['error' => 'Account not found']);
        }

        $input = $request->validate($this->depositValidation);

        $withdraw = Symfinance::withdraw($account->balance, $input['amount'], $account->bankData->withdraw_fee);

        return view('account.withdrawConfirm', ['account' => $account, 'withdraw' => $withdraw]);
    }

    public function postWithdrawConfirm(Request $request, $id)
    {
        $account = Auth::user()->accounts()->where('id', $id)->first();
        if (!isset($account)) {
            return redirect('/')->withErrors(['error' => 'Account not found']);
        }

        $input = $request->validate($this->withdrawValidation);

        $withdraw = Symfinance::withdraw($account->balance, $input['amount'], $account->bankData->withdraw_fee);
        if ($withdraw['new_balance'] < 0) {
            return back()->withErrors(['error' => 'Your new balance cannot be less than zero!'])->withInput();
        }

        if (!Hash::check($request->input('pin'), $account->pin)) {
            return back()->withErrors(['error' => 'Invalid PIN supplied'])->withInput();
        }

        DB::beginTransaction();
        try {
            $account->balance = $withdraw['new_balance'];
            $account->save();

            Transaction::make($id, 'debit', $withdraw['balance_change'], $withdraw['new_balance'], 'withdraw out');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Unable to perform transaction'])->withInput();
        }

        return redirect('/account/view/'.$account->id)->with(['status' => 'Funds withdrawn successfully!']);
    }

    public function transfer(Request $request, $id)
    {
        $account = Auth::user()->accounts()->where('id', $id)->first();
        if (!isset($account)) {
            return redirect('/')->withErrors(['error' => 'Account not found']);
        }

        $banks = Bank::all();

        return view('account.transfer', ['account' => $account, 'banks' => $banks]);
    }

    public function postTransfer(Request $request, $id)
    {
        $account = Auth::user()->accounts()->where('id', $id)->first();
        if (!isset($account)) {
            return redirect('/')->withErrors(['error' => 'Account not found']);
        }

        $input = $request->validate($this->transferValidation);

        $dstBank = Bank::where('code', $input['dst_bank'])->first();
        if (!isset($dstBank)) {
            return redirect('/account/transfer/'.$account->id)->withErrors(['error' => 'Destination bank not found'])->withInput();
        }

        $dstAccount = Account::where(['bank' => $dstBank->id, 'number' => $input['dst_account']])->first();
        if (!isset($dstAccount)) {
            return redirect('/account/transfer/'.$account->id)->withErrors(['error' => 'Destination account not found'])->withInput();
        }

        $fee = $account->bankData->transferFeeData($dstBank->id)->first();
        $fee = isset($fee) ? $fee->pivot->amount : 0;
        $transfer = Symfinance::transfer($account->balance, $dstAccount->balance, $input['amount'], $fee);
        $transfer['dst_bank_code'] = $dstBank->code;
        $transfer['dst_bank_name'] = $dstBank->name;
        $transfer['dst_account_number'] = $dstAccount->number;
        $transfer['dst_account_name'] = $dstAccount->name;

        return view('account.transferConfirm', ['account' => $account, 'transfer' => $transfer]);
    }

    public function postTransferConfirm(Request $request, $id)
    {
        $account = Auth::user()->accounts()->where('id', $id)->first();
        if (!isset($account)) {
            return redirect('/')->withErrors(['error' => 'Account not found']);
        }

        $input = $request->validate($this->transferValidation);

        $dstBank = Bank::where('code', $input['dst_bank'])->first();
        if (!isset($dstBank)) {
            return back()->withErrors(['error' => 'Destination bank not found'])->withInput();
        }

        $dstAccount = Account::where(['bank' => $dstBank->id, 'number' => $input['dst_account']])->first();
        if (!isset($dstAccount)) {
            return back()->withErrors(['error' => 'Destination account not found'])->withInput();
        }

        if ($dstAccount->id === $id) {
            return back()->withErrors(['error' => 'You cannot transfer to the same account, knucklehead!'])->withInput();
        }

        if (!Hash::check($request->input('pin'), $account->pin)) {
            return back()->withErrors(['error' => 'Invalid PIN supplied'])->withInput();
        }

        $fee = $account->bankData->transferFeeData($dstBank->id)->first();
        $fee = isset($fee) ? $fee->pivot->amount : 0;
        $transfer = Symfinance::transfer($account->balance, $dstAccount->balance, $input['amount'], $fee);
        if ($transfer['src_new_balance'] < 0) {
            return back()->withErrors(['error' => 'Your new balance cannot be less than zero!'])->withInput();
        }

        DB::beginTransaction();
        try {
            $account->balance = $transfer['src_new_balance'];
            $account->save();

            $dstAccount->balance = $transfer['dst_new_balance'];
            $dstAccount->save();

            Transaction::make($id, 'debit', $transfer['src_balance_change'], $transfer['src_new_balance'], 'transfer out');
            Transaction::make($dstAccount->id, 'credit', $transfer['dst_balance_change'], $transfer['dst_new_balance'], 'transfer in');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Unable to perform transaction']);
        }

        return redirect('/account/view/'.$account->id)->with(['status' => 'Funds transferred successfully!']);
    }

    public function open(Request $request)
    {
        $user = Auth::user();

        return view('account.open', ['user' => $user]);
    }

    public function postOpen(Request $request)
    {
        $input = $request->validate([
            'bank' => 'required|integer',
            'name' => 'required|max:128',
            'pin' => 'required|digits:6',
            'pinconf' => 'required|same:pin',
            'balance' => 'required|integer|gte:100000'
        ]);

        $bank = Bank::where('code', $input['bank'])->first();
        if (!isset($bank)) {
            return back()->withErrors(['error' => 'Bank not found'])->withInput();
        }

        $account = new Account();

        DB::beginTransaction();
        try {
            $account->id = (string)Str::uuid();
            $account->bank = $bank->id;
            $account->number = random_int(1, 1000000000);
            $account->user = Auth::id();
            $account->name = $input['name'];
            $account->pin = Hash::make($input['pin']);
            $account->balance = $input['balance'];
            $account->save();

            Transaction::make($account->id, 'credit', $account->balance, $account->balance, 'initial deposit');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Unable to open account. Please try again'])->withInput();
        }

        return redirect('/account/viewAll')->with('status', $bank->name.' account opened successfully with number: '.$account->number);
    }

    public function close(Request $request, $id)
    {
        $account = Auth::user()->accounts()->where('id', $id)->first();
        if (!isset($account)) {
            return redirect('/')->withErrors(['error' => 'Account not found']);
        }

        if ($account->balance > 0) {
            return back()->withErrors(['error' => 'Please empty your funds first before deleting your account']);
        }

        $account->delete();

        return redirect('/account/viewAll')->with('status', 'Account closed successfully');
    }
}
