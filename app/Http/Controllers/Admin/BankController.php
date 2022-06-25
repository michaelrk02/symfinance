<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;

class BankController extends Controller
{
    public function viewAll(Request $request)
    {
        $banks = Bank::orderBy('code')->get();

        return view('admin.bank.viewAll', ['banks' => $banks]);
    }

    public function add(Request $request)
    {
        $bank = new Bank();
        $this->formData($request, $bank, 'create');

        return $this->form($bank, 'create');
    }

    public function postAdd(Request $request)
    {
        $this->validateBank($request, 'create');

        $bank = new Bank();
        $bank->id = (string)Str::uuid();
        $this->formData($request, $bank, 'create', true);

        $bank->save();

        return redirect('/admin/bank/viewAll')->with('status', 'Bank created successfully');
    }

    public function edit(Request $request, Bank $bank)
    {
        $this->formData($request, $bank, 'update');

        return $this->form($bank, 'update');
    }

    public function postEdit(Request $request, Bank $bank)
    {
        $this->validateBank($request, 'update');
        $this->formData($request, $bank, 'update', true);

        $bank->save();

        return redirect('/admin/bank/viewAll')->with('status', 'Bank updated successfully');
    }

    public function removePhoto(Request $request, Bank $bank)
    {
        $bank->removePhoto();

        return back()->with('status', 'Photo removed successfully');
    }

    public function remove(Request $request, Bank $bank)
    {
        $bank->delete();

        return redirect('/admin/bank/viewAll')->with('status', 'Bank deleted successfully');
    }

    public function viewAllTransferFee(Request $request, Bank $bank)
    {
        return view('admin.bank.transferFees', ['bank' => $bank]);
    }

    public function addTransferFee(Request $request, Bank $bank)
    {
        $input = $request->validate([
            'dstbank' => 'required|integer',
            'amount' => 'required|integer|gte:0'
        ]);

        $dstBank = Bank::where('code', $input['dstbank'])->first();
        if (!isset($dstBank)) {
            return back()->withErrors(['error' => 'Destination bank not found'])->withInput();
        }

        $bank->transferFeesData()->attach($dstBank->id, ['amount' => $input['amount']]);

        return redirect('/admin/bank/transferFee/'.$bank->id.'/viewAll')->with('status', 'Transfer fee added successfully');
    }

    public function removeTransferFee(Request $request, Bank $bank, Bank $dstbank)
    {
        $bank->transferFeesData()->detach($dstbank->id);

        return redirect('/admin/bank/transferFee/'.$bank->id.'/viewAll')->with('status', 'Transfer fee removed successfully');
    }

    protected function formData(Request $request, Bank $bank, $mode, $submit = false)
    {
        $bank->code = old('code', $mode === 'create' ? 0 : $bank->code);
        $bank->name = old('name', $mode === 'create' ? '' : $bank->name);
        $bank->address = old('address', $mode === 'create' ? '' : $bank->address);
        $bank->deposit_fee = old('deposit_fee', $mode === 'create' ? 0 : $bank->deposit_fee);
        $bank->withdraw_fee = old('withdraw_fee', $mode === 'create' ? 0 : $bank->withdraw_fee);

        $bank->code = $request->input('code', $bank->code);
        $bank->name = $request->input('name', $bank->name);
        $bank->address = $request->input('address', $bank->address);
        $bank->deposit_fee = $request->input('deposit_fee', $bank->deposit_fee);
        $bank->withdraw_fee = $request->input('withdraw_fee', $bank->withdraw_fee);

        if ($submit) {
            if ($request->hasFile('photo')) {
                if ($bank->hasPhoto()) {
                    $bank->removePhoto();
                }
                $bank->photo = $request->file('photo')->store('bank_photo');
            }
        }
    }

    protected function form($bank, $mode)
    {
        return view('admin.bank.form', [
            'bank' => $bank,
            'mode' => $mode,
            'action' => $mode === 'create' ? url('/admin/bank/add') : url('/admin/bank/edit/'.$bank->id)
        ]);
    }

    protected function validateBank(Request $request, $mode)
    {
        $request->validate([
            'code' => 'required|integer|gt:0',
            'name' => 'required|string|max:128',
            'address' => 'required|string',
            'deposit_fee' => 'required|integer',
            'withdraw_fee' => 'required|integer'
        ]);

        if ($mode === 'create') {
            if (Bank::where('code', $request->input('code'))->first() !== NULL) {
                throw new HttpResponseException(back()->withErrors(['error' => 'Code is already used by another bank'])->withInput());
            }
        }
    }
}
