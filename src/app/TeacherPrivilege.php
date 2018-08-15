<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherPrivilege extends Model {

    protected $table = 'tbl_assigned_subjects_to_teachers';
    protected $primaryKey = 'assigned_subjects_to_teacher_id';
    public $timestamps = false;

}
