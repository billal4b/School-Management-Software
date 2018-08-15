<?php

namespace App\Http\Controllers\LoggedIn;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\SecUserLogon;
use App\User;
use Validator;

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
	
	

	/*************************** edit profile  **********************************/

	  public function edit(Request $request){
		  
		$Data = array();
        $Data['PageTitle'] = 'Update Your Information';
        return view($this->ViewPagePath . 'edit', $Data);	
    }
	/*********************** update profile ******************************/
	
	public function update(Request $request){
		 $this->data['Details'] = User::findOrFail( Auth::user()->user_id);     
        $validator = Validator::make($request->all(), array(
                    'full_name' => 'required|max:255',
                    'email'     => 'required',
                    'phone_no'  => 'required',
        ));
        if ($validator->fails()) {
            return redirect()->route('LoggedIn.Home.updateOwnProfile')
                            ->withErrors($validator)
                            ->withInput();
        }
        $profile = User::find(Auth::user()->user_id);
        $profile->full_name = $request->full_name;
        $profile->email = $request->email;
        $profile->degination = $request->degination;      
        $profile->address = $request->address;      
        $profile->relegion = $request->relegion;      
        $profile->blood_group = $request->blood_group;      
        $profile->nid_no = $request->nid_no;      
        $profile->save();

	    return redirect()->route('LoggedIn.Home.OnlineInformation')
            ->with('SuccessMessage', 'Profile updated successfully.');					
    }

	
	
	/*******************  update password  *****************************/
	
	public function edit_password(Request $request){        
		 if (!$request->ajax()) {			 
		   $Data = array();
           $Data['PageTitle'] = 'Update Your Password';
		   
            try {
				return view($this->ViewPagePath . 'passwordChange', $Data);
            } catch (\Exception $ex) {
                echo $ex->getMessage();
            }
        } else {
            throw new Exception('Invalid request!');
        }
    }
	  /****************/

	public function update_password(Request $request){
		
		  $this->data['Details'] = User::findOrFail( Auth::user()->user_id);       	
           $validator = Validator::make($request->all(), array(
                    'oldpassword' => 'required',
                    'password'    => 'required',
                    'cnfpassword' => 'required',
           ));
           if ($validator->fails()) {
            return redirect()->route('LoggedIn.Home.updateProfilePassword')
                            ->withErrors($validator)
                            ->withInput();

           }	
 		   
            $MessageType = 'SuccessMessage';
            $Message = 'Password updated successfully.';	
      
			
		   // //// Getting the User  //////////
		   
		   if(!Hash::check($request->oldpassword, Auth::user()->password)){
			   return redirect()->route('LoggedIn.Home.updateProfilePassword')
                            ->with('ErrorMessage', 'Your current password didn\'t match.');
		   }
		   if($request->password != $request->cnfpassword){
			   return redirect()->route('LoggedIn.Home.updateProfilePassword')
                    ->with('ErrorMessage', 'Your new password and confirm password didn\'t match.');
		   }		   		   		
			$user = User::find(Auth::user()->user_id);
			$user->password = bcrypt($request->password);
			$user->save();
			
        return redirect()->route('LoggedIn.Home.OnlineInformation')
                        ->with($MessageType, $Message);

	  }
/*******************************************  update picture  *********************************************/
	public function update_image(Request $request){
		
		$this->data['Details'] = User::findOrFail( Auth::user()->user_id);
        		
        $profile = User::find( Auth::user()->user_id);
       
		 if($request->hasFile('image')) {
				  
		   if ($request->file('image')->isValid()) {
   
	            $destinationPath = 'img/ProfilePicture';
				$extension = $request->file('image')->getClientOriginalExtension();
				$fileName = Auth::user()->user_id.'.'.$extension;

				$request->file('image')->move($destinationPath,$fileName);
				
				$profile->image = $fileName;
                $profile->save();				
            }				   
									  				   				 		         
         }
		
        return redirect()->route('LoggedIn.Home.updateOwnProfile')
                        ->with('successMessage', 'picture updated successfully.');
		
	}
	
    /*********************** Logout **************************/

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
