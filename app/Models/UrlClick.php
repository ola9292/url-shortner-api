<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlClick extends Model
{
    use HasFactory;

    public $table = 'url_clicks';

    public $fillable = ['url_id', 'ip_address', 'user_agent', 'country'];
}
