<?php

namespace App\Http\Controllers\LoggedIn;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\SecUserLogon;

class HomeController extends Controller {
    
    private $ViewPagePath = 'LoggedIn.Home.';

    public function __construct() {
    }

    public function OnlineInformation() {

        $LoggedInUserFullName = Auth::user()->full_name;

        $Data = array();
        $Data['PageTitle'] = 'Welcome ' . $LoggedInUserFullName . ' to your dashboard';

        return view($this->ViewPagePath . 'OnlineInformation', $Data);
    }
    
    public function Logout(Request $request) {

//        echo 'getLogout';exit();
        $login_id = $request->session()->get('sessLogonId');
        //echo $login_id;exit();
        try {

            if (!empty($login_id) && (int) $login_id > 0 && Auth::check()) {

                //$login_id = $request->session()->get('sess_login_id');
                $logout = SecUserLogon::find($login_id);
                $logout->logout_time = date('Y-m-d H:i:s');
                $logout->save();

                $request->session()->flush();

                Auth::logout();

                return redirect()->route('Authentication.GetLogin')->with('SuccessMessage', 'You have successfully logged out.');
            }else{
                throw new Exception('Invalid Request!');
            }
        } catch (\Exception $ex) {
            //return redirect()->route('getLogin');
            echo $ex->getMessage();
        }
    }

}
