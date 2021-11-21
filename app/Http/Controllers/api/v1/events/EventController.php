<?php

namespace App\Http\Controllers\api\v1\events;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Parents;
use App\Models\Student;
use App\Models\StudentClass;

class EventController extends Controller
{
    //
    protected function getEvent($parent_id)
    {
        $parent = Parents::find($parent_id);
        $result = [];
        if ($parent) {
            $students = $parent->students;
            foreach ($students as $key => $student) {

                $events = $student->events;
                foreach ($events as $e) {
                    $id = StudentClass::where('class_id', $e->id)->where('student_id', $student->id)->first()->id;
                    $r = [
                        'student_name' => $student->fullname, 'dob' => date('d/m/Y', strtotime($student->dob)),
                        'event_name' => $e->name,
                        'status' => $e->status,
                        'active' => $e->active,
                        'sbd' => $e->code . "" . $id
                    ];
                    $result[] = $r;
                }
            }
            return response()->json($result);
        }
    }
}
