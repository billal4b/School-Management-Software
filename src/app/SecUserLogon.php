<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecUserLogon extends Model {

    protected $table = 'tbl_sec_user_logons';
    protected $primaryKey = 'logon_id';
    public $timestamps = false;
    protected $fillable = ['user_id', 'login_time', 'is_logged_in', 'ip_addr', 'sess_id', 'client_agent', 'login_via'];

}
