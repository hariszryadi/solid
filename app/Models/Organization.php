<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address'
    ];

    public function account()
    {
        return $this->hasMany(Account::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
