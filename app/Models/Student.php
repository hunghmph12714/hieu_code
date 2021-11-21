<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //s
    public $table = "students";
    protected $fillable = ['parent_id','relationship_id','fullname','school','grade','email','phone','dob','address','note','gender','fee_email_log'];
    protected $casts = ['fee_email_log' => 'array'];

    public function parents(){
        return $this->belongsTo('App\Parents');
    }
    public function events(){
        return $this->belongsToMany('App\Models\Classes','student_class','student_id','class_id')->using('App\Models\StudentClass')
                    ->withPivot('status', 'entrance_date','stats')
                    ->where('classes.type','event')
                    ->withTimestamps();
    }
    public function classes(){
        return $this->belongsToMany('App\Models\Classes','student_class','student_id','class_id')->using('App\Models\StudentClass')
                    ->withPivot('status', 'entrance_date','stats')
                    ->withTimestamps();
        }
    public function activeClasses(){
        return $this->belongsToMany('App\Models\Classes','student_class','student_id','class_id')->using('App\Models\StudentClass')
                    ->withPivot('status', 'entrance_date','stats','id')
                    ->wherePivot('status', 'active')
                    ->where('classes.type','class')
                    ->withTimestamps();
        }
    public function sessions(){
        return $this->belongsToMany('App\Models\Session','student_session','student_id','session_id');
    }
    public function sessionsOfClass($class_id){
        return $this->belongsToMany('App\Models\Session','student_session','student_id','session_id')
                    // ->withPivot('status', 'entrance_date','stats','id')
                    ->where('class_id', $class_id);
                    // ->withTimestamps();
    }

}
