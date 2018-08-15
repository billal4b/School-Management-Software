<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use DB;

class AuthenticationController extends Controller {

    private $ViewPagePath = 'Authentication.';

    public function __construct() {
        
    }

    public function GetLogin(Request $request) {

        $Data = array();
        $Data['PageTitle'] = 'Login';

        return view($this->ViewPagePath . 'GetLogin', $Data);
    }

    public function PostLogin(Request $request) {

        //echo '<pre>';print_r($request->all());echo '</pre>';exit();
        if ($request->ajax() === FALSE) {
            
            $validator = Validator::make($request->all(), [
                        'Identifiers' => 'required|max:255',
                        'Password' => 'required|max:100',
            ]);

            if ($validator->fails()) {
                return redirect()->route('Authentication.GetLogin')
                                ->withErrors($validator)
                                ->withInput();
            }

            try {
                
                $loginBy = NULL;
                $loginByValue = $request->input('Identifiers');
                if (is_numeric($loginByValue)) {

                    if (strlen($loginByValue) > 3 && substr($loginByValue, 0, 3)) {
                        $loginBy = 'phone_no';
                    } else {
                        $loginBy = 'user_id';
                    }
                } else if (filter_var($loginByValue, FILTER_VALIDATE_EMAIL)) {
                    $loginBy = 'email';
                } else {
                    $loginBy = 'username';
                }

                $Password = $request->input('Password');
                $Remember = $request->input('Remember');

                $LoginCredentialsArray = array(
                    $loginBy => $loginByValue,
                    'password' => $Password,
                    'is_active' => TRUE,
                );


                if (Auth::attempt($LoginCredentialsArray, $Remember)) {

                    $LoggedInUserID = Auth::user()->user_id;
                    //echo '<pre>';print_r(Auth::user());echo '</pre>';exit();
                    // Authentication passed...
                    //echo '<pre>';print_r(LoginLogoutLog::all());echo '</pre>';exit();
                    $secUserLogon = DB::select('call insert_into_sec_user_logons('
                                    . '"' . $LoggedInUserID . '", '
                                    . '"' . date('Y-m-d h:i:s') . '", '
                                    . '"' . 0 . '", '
                                    . '"' . $request->server('REMOTE_ADDR') . '", '
                                    . '"' . $request->session()->getId() . '", '
                                    . '"' . $request->server('HTTP_USER_AGENT') . '", '
                                    . '"' . $loginBy . '"'
                                    . ')');
                    //echo '<pre>';print_r($secUserLogon);echo '</pre>';exit();
                    $logonId = $secUserLogon[0]->logon_id;
                    $request->session()->put('sessLogonId', $logonId);


                    $instituteDetails = DB::select('call get_institute_details("' . Auth::user()->institute_branch_version_id . '", "' . Auth::user()->role_id . '")');
                    $request->session()->put('instituteDetails', $instituteDetails[0]);
                    //echo '<pre>';print_r($request->session()->get('instituteDetails'));echo '</pre>';exit();

                    $roleName = DB::select('call get_role_name_by_role_id("' . Auth::user()->role_id . '")');
                    $request->session()->put('roleName', $roleName[0]->role_name);

                    $parentMenuList = DB::select('call gen_parent_menu_list( ' . Auth::user()->user_id . ')');
                    //echo '<pre>';print_r($parentMenuList);echo '</pre>';exit();
                    $request->session()->put('parentMenuList', $parentMenuList);
                    
                    $PrivilegesOfRole = DB::select('call GetPrivilegesOfRole("' . Auth::user()->role_id . '")');
                    //echo '<pre>';print_r($PrivilegesOfRole);echo '</pre>';exit();
                    $request->session()->put('PrivilegesOfRole', $PrivilegesOfRole);

if(Auth::user()->role_id == 4){
                        
                        $TotalNoOfStudent = DB::select('select count(student_id) as total from tbl_students where `status` = 1 and section_id != 55');
                        $request->session()->put('TotalNoOfStudent', $TotalNoOfStudent[0]->total);
                        //echo '<pre>';print_r(session('TotalNoOfStudent'));echo '</pre>';exit();
                    }
                    
                    
                    //echo '<pre>';print_r(session('PrivilegesOfRole'));echo '</pre>';exit();

                    return redirect()->intended(route('LoggedIn.Home.OnlineInformation'));
                } else {
                    return redirect()->route('Authentication.GetLogin')->with('ErrorMessage', 'The ' . $loginBy . ' and Password you entered don\'t match.')->withInput();
                }
            } catch (\Exception $ex) {
                echo $ex->getMessage();
            }
        } else {
            throw new \Exception('Invalid Request!');
        }
    }

}
