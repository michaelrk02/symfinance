<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id', 'timestamp', 'account', 'type', 'amount', 'description'];

    public function make($account, $type, $amount, $balance, $description)
    {
        $transaction = new Transaction();
        $transaction->id = (string)Str::uuid();
        $transaction->timestamp = date('Y-m-d H:i:s');
        $transaction->account = $account;
        $transaction->type = $type;
        $transaction->amount = $amount;
        $transaction->balance = $balance;
        $transaction->description = $description;
        $transaction->save();
    }
}
