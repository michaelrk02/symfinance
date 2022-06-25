<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'account';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id', 'bank', 'number', 'user', 'name', 'pin', 'balance'];

    public function bankData()
    {
        return $this->belongsTo(Bank::class, 'bank', 'id');
    }

    public function userData()
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }

    public function transactionsData()
    {
        return $this->hasMany(Transaction::class, 'id', 'account');
    }
}
