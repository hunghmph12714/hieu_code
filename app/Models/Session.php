<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    //
    public $table = 'sessions';
    protected $fillable = ['id','class_id','teacher_id','center_id','room_id','document','exercice','from','to','date','ss_number','stats','exercice','status',
        'note', 'fee','content','btvn_content','type','present_number','absent_number','percentage','classes'];
    protected $casts = ['classes'=> 'array'];
    
    public function students(){
        return $this->belongsToMany('App\Models\Student', 'student_session', 'session_id', 'student_id')
            ->withPivot('attendance','type','score','attendance_note','max_score','btvn_max','btvn_score','btvn_complete','comment','logs','btvn_comment');
    }
    public function transactions(){
        return $this->belongsToMany('App\Models\Transaction', 'transaction_session', 'session_id', 'transaction_id')
        ->withPivot('id','amount');;
    }
    public function student($student_id){
        return $this->belongsToMany('App\Models\Student', 'student_session', 'session_id', 'student_id')->wherePivot('student_id', $student_id)
            ->withPivot('attendance','type','score','attendance_note','max_score','btvn_max','btvn_score','btvn_complete','comment','logs','btvn_comment');
    }
    
}//ss
