<?php

namespace App\Http\Controllers\LoggedIn;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class SettingsController extends Controller {
    
    private $ViewPagePath = 'LoggedIn.Settings.';
    private $MenuID = 32;

    public function __construct() {
        
        session()->put('ActiveParentMenuID', $this->MenuID);
    }

    public function Index() {

        $Data = array();
        $Data['PageTitle'] = 'Settings';
        
        $Data['ChildMenus'] = DB::select('call get_child_menu_list("' . $this->MenuID . '")');
        //echo '<pre>';print_r($Data['ChildMenus']);echo '</pre>';exit();
        if(!empty($Data['ChildMenus'])){
            return view($this->ViewPagePath . 'Index', $Data);
        }else{
            throw new \Exception('Invalid Request!');
        }

        
    }

}
