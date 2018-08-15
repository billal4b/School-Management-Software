<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model {

    protected $table = 'tbl_subjects';
    protected $primaryKey = 'subject_id';
    public $timestamps = false;

}
