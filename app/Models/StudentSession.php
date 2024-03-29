<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
class StudentSession extends Pivot
{
    //
    public $table = 'student_session';
    protected $fillable = ['student_id','session_id','attendance','type','score','note','attendance_note','logs','max_score','btvn_score','btvn_max','btvn_complete','comment','btvn_comment'];
    protected $casts = ['logs' => 'array'];
    public $incrementing = true;
}
