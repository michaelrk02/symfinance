<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'bank';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id', 'name', 'address', 'photo'];

    public function transferFeesData()
    {
        return $this->belongsToMany(Bank::class, 'transfer_fee', 'srcbank', 'dstbank', 'id', 'id')
            ->withPivot('amount');
    }

    public function transferFeeData($dstbank)
    {
        return $this->belongsToMany(Bank::class, 'transfer_fee', 'srcbank', 'dstbank', 'id', 'id')
            ->wherePivot('dstbank', $dstbank)
            ->withPivot('amount');
    }

    public function hasPhoto()
    {
        return Storage::exists($this->photo);
    }

    public function removePhoto()
    {
        return Storage::delete($this->photo);
    }

    public function viewPhoto()
    {
        return response()->file(Storage::path($this->photo));
    }
}
