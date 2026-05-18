<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    protected $table = 'datasets';

    protected $fillable = [
        'conversation_id_str',
        'created_at',
        'favorite_count',
        'full_text',
        'id_str',
        'image_url',
        'in_reply_to_screen_name',
        'lang',
        'location',
        'quote_count',
        'reply_count',
        'retweet_count',
        'tweet_url',
        'user_id_str',
        'username',
        'label',
    ];
    
    public function preprocessing()
    {
        return $this->hasOne(PreprocessingResult::class);
    }
}