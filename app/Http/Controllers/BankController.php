<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;

class BankController extends Controller
{
    public function viewAll(Request $request) {
        $banks = Bank::orderBy('code')->get();

        return view('bank.viewAll', ['banks' => $banks]);
    }

    public function view(Request $request, $id) {
        $bank = Bank::find($id);
        if (!isset($bank)) {
            return redirect('/')->withErrors(['error' => 'Bank not found']);
        }

        return view('bank.view', ['bank' => $bank]);
    }

    public function viewPhoto(Request $request, Bank $bank) {
        return $bank->viewPhoto();
    }
}
