<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TokenCache extends Model
{
    protected $fillable = ['service','token','expired_at'];
    protected $casts = ['expired_at' => 'datetime'];
}
