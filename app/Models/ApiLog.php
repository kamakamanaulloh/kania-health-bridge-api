<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ApiLog extends Model
{
    protected $fillable = ['request_id','service','module','endpoint','method','request_payload','response_payload','http_code','status','duration_ms','ip_address','user_agent'];
}
