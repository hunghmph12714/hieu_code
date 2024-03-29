<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    //
    public $table = 'classes';
    protected $fillable = ['id','center_id','course_id','code','document','active','config','fee','open_date','name','note','student_number','adjust_fee','online_id','password','droped_number','waiting_number','transfer_number', 'type'];
    protected $casts = ['adjust_fee' => 'array'];
    public function sessions(){
        return $this->hasMany('App\Session','class_id','id');
    }
    public function students(){
        return $this->belongsToMany('App\Student','student_class','class_id','student_id')
                    ->as('detail')
                    ->using('App\StudentClass')
                    ->withPivot('status', 'entrance_date','stats','drop_time','id as sc_id')
                    ->withTimestamps()->orderBy('student_class.status')->orderBy('students.fullname');
    }
    public function activeStudents(){
        return $this->belongsToMany('App\Student','student_class','class_id','student_id')
            ->wherePivot('status', 'active')
            ->withPivot('id','status', 'entrance_date','stats');
    }
    public function activeStudentsDate($date){
        return $this->belongsToMany('App\Student','student_class','class_id','student_id')
            ->withPivot('id','status', 'entrance_date','stats');
    }
    public function dropedStudents(){
        return $this->belongsToMany('App\Student','student_class','class_id','student_id')
            ->wherePivot('status', 'droped')
            ->withPivot('id','status', 'entrance_date','stats');
    }
    public function waitingStudents(){
        return $this->belongsToMany('App\Student','student_class','class_id','student_id')
            ->wherePivot('status', 'waiting')
            ->withPivot('id','status', 'entrance_date','stats');
    }
    public function transferStudents(){
        return $this->belongsToMany('App\Student','student_class','class_id','student_id')
            ->wherePivot('status', 'transfer')
            ->withPivot('id','status', 'entrance_date','stats');
    }
}
