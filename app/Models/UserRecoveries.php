<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRecoveries extends Model
{
    protected $table = 'users_recoveries';

    protected $fillable = [
        'user_id',
        'question_1',
        'question_2',
        'question_3',
        'answer_1',
        'answer_2',
        'answer_3'

    ];
}
