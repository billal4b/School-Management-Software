<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassTeacher extends Model {

    protected $table = 'tbl_class_teachers';
    protected $primaryKey = 'class_teacher_id';
    public $timestamps = false;

}
