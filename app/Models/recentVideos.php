<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentVideos extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'thumbnail',
        'imgurl',
        'duration',
        'background', 
        'audio',
        'font',
        'hide_ms',
        'font_color',
        'font_size',
        'res',
        'vId',
    ];
}
