<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model {

    protected $table = 'tbl_attendances';
    protected $primaryKey = 'attendance_id';
    public $timestamps = false;

}
