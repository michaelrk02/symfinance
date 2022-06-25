<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Models\Bank;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $bankBRI = Bank::create([
            'id' => (string)Str::uuid(),
            'code' => 1,
            'name' => 'BRI',
            'address' => 'Gedung BRI, Jl. Jenderal Sudirman Kav.44-46, Jakarta 10210, Indonesia',
            'deposit_fee' => 5000,
            'withdraw_fee' => 1000
        ]);

        $bankBNI = Bank::create([
            'id' => (string)Str::uuid(),
            'code' => 2,
            'name' => 'BNI',
            'address' => 'Gedung Grha BNI, Jl. Jenderal Sudirman Kav. 1, Jakarta Pusat 10220, Indonesia',
            'deposit_fee' => 4000,
            'withdraw_fee' => 2000
        ]);

        $bankBCA = Bank::create([
            'id' => (string)Str::uuid(),
            'code' => 3,
            'name' => 'BCA',
            'address' => 'Menara BCA, Grand Indonesia, Jl. MH Thamrin No. 1, Jakarta 10310',
            'deposit_fee' => 3000,
            'withdraw_fee' => 3000
        ]);

        $bankBRI->transferFeesData()->attach($bankBNI->id, ['amount' => 2500]);
        $bankBRI->transferFeesData()->attach($bankBCA->id, ['amount' => 5000]);

        $bankBNI->transferFeesData()->attach($bankBRI->id, ['amount' => 3000]);
        $bankBNI->transferFeesData()->attach($bankBCA->id, ['amount' => 6500]);

        $bankBCA->transferFeesData()->attach($bankBRI->id, ['amount' => 1500]);
        $bankBCA->transferFeesData()->attach($bankBNI->id, ['amount' => 1500]);

        $userAlice = User::create([
            'id' => (string)Str::uuid(),
            'email' => 'alice@localhost.localdomain',
            'password' => Hash::make('alice'),
            'name' => 'Alice Whiteman'
        ]);

        $userBob = User::create([
            'id' => (string)Str::uuid(),
            'email' => 'bob@localhost.localdomain',
            'password' => Hash::make('bob'),
            'name' => 'Bob Whiteman'
        ]);

        $userCharlie = User::create([
            'id' => (string)Str::uuid(),
            'email' => 'charlie@localhost.localdomain',
            'password' => Hash::make('charlie'),
            'name' => 'Charlie Whiteman'
        ]);

        $accountAlice = Account::create([
            'id' => (string)Str::uuid(),
            'bank' => $bankBRI->id,
            'number' => random_int(1, 1000000000),
            'user' => $userAlice->id,
            'name' => 'Alice Savings Account',
            'pin' => Hash::make('123456'),
            'balance' => 500000
        ]);

        $accountBob = Account::create([
            'id' => (string)Str::uuid(),
            'bank' => $bankBNI->id,
            'number' => random_int(1, 1000000000),
            'user' => $userBob->id,
            'name' => 'Bob Savings Account',
            'pin' => Hash::make('123456'),
            'balance' => 500000
        ]);

        $accountCharlie = Account::create([
            'id' => (string)Str::uuid(),
            'bank' => $bankBCA->id,
            'number' => random_int(1, 1000000000),
            'user' => $userCharlie->id,
            'name' => 'Charlie Savings Account',
            'pin' => Hash::make('123456'),
            'balance' => 500000
        ]);

        Transaction::create([
            'id' => (string)Str::uuid(),
            'timestamp' => date('Y-m-d H:i:s'),
            'account' => $accountAlice->id,
            'type' => 'credit',
            'amount' => 500000,
            'balance' => 500000,
            'description' => 'initial deposit'
        ]);

        Transaction::create([
            'id' => (string)Str::uuid(),
            'timestamp' => date('Y-m-d H:i:s'),
            'account' => $accountBob->id,
            'type' => 'credit',
            'amount' => 500000,
            'balance' => 500000,
            'description' => 'initial deposit'
        ]);

        Transaction::create([
            'id' => (string)Str::uuid(),
            'timestamp' => date('Y-m-d H:i:s'),
            'account' => $accountCharlie->id,
            'type' => 'credit',
            'amount' => 500000,
            'balance' => 500000,
            'description' => 'initial deposit'
        ]);

        Admin::create([
            'id' => (string)Str::uuid(),
            'email' => 'root@localhost.localdomain',
            'password' => Hash::make('root'),
            'name' => 'Superuser'
        ]);
    }
}
