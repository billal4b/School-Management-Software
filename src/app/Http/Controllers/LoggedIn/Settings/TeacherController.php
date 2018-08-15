<?php

namespace App\Http\Controllers\LoggedIn\Settings;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Validator;
use App\User;
use PDF;

class TeacherController extends Controller {

    private $ViewPagePath = 'LoggedIn.Settings.Teacher.';
    private $MenuID = 28;
    protected $LoggedInUserInstituteBranchVersionIDPrivileges = '';
    private $RoleID = 5;

    public function __construct() {


        try {

            if (session()->has('PrivilegesOfRole')) {
                foreach (session('PrivilegesOfRole') as $IBVP) {
                    $this->LoggedInUserInstituteBranchVersionIDPrivileges .= $IBVP->institute_branch_version_id . ', ';
                }
            } else {
                $this->LoggedInUserInstituteBranchVersionIDPrivileges .= Auth::user()->institute_branch_version_id . ', ';
            }

            $this->LoggedInUserInstituteBranchVersionIDPrivileges = rtrim(trim($this->LoggedInUserInstituteBranchVersionIDPrivileges), ',');
            //echo $this->LoggedInUserInstituteBranchVersionIDPrivileges;exit();
        } catch (\Exception $ex) {
            $ex->getMessage();
        }
    }

    public function index(Request $request) {

        $Data = array();
        $Data['PageTitle'] = 'Teacher';

        $ChildMenus = DB::select('call get_child_menu_list("' . $this->MenuID . '")');
        //echo '<pre>';print_r($Data['ChildMenus']);echo '</pre>';exit();
        if (!empty($ChildMenus)) {

            $Data['ChildMenus'] = $ChildMenus;
            //return view('LoggedIn.Settings.Index', $Data);
        } else {


            //echo $LoggedInUserInstituteBranchVersionIDPrivileges;exit();
//            $page = $request->get('page', 1);
//            $paginate = 10;


            $Teachers = DB::select(DB::raw('call GetTeachers("1", "' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));

//            $offSet = ($page * $paginate) - $paginate;
//            $itemsForCurrentPage = array_slice($Teachers, $offSet, $paginate, true);
//            $Data['Teachers'] = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($Teachers), $paginate, $page);
//            echo '<pre>';
//            print_r($Data['Teachers']);
//            echo '</pre>';
//            exit();
//            $Data['Teachers']->setPath(route($this->ViewPagePath . 'Index'));
            $Data['Teachers'] = $Teachers;

            $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
            $Data['InstituteBranchVersions'] = $InstituteBranchVersions;
            return view($this->ViewPagePath . 'Index', $Data);
        }
    }

    public function create() {

        $Data = array();
        $Data['PageTitle'] = 'New Teacher';

        $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
        $Data['InstituteBranchVersions'] = $InstituteBranchVersions;

        return view($this->ViewPagePath . 'Create', $Data);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
                    'FullName' => 'required|max:255',
                    'TeacherId' => 'required|digits:2',
                    'Email' => 'email',
                    'ContactNo' => 'required|digits:9',
                    'Branch' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->ViewPagePath . 'Create'))
                            ->withErrors($validator)
                            ->withInput();
        }

        $FullName = $request->input('FullName');
        $TeacherID = $request->input('TeacherId');
        $Email = $request->input('Email');
        $ContactNo = $request->input('ContactNo');
        $InstituteBranchVersionID = $request->input('Branch');
        $StaticID = DB::table('tbl_sec_institute_branch_version')
                ->select('static_id')
                ->where('institute_branch_version_id', '=', $InstituteBranchVersionID)
                ->where('is_active', '=', 1)
                ->first();

        $MessageType = 'SuccessMessage';
        $Message = 'Teacher inserted successfully.';
        try {

            $Teacher = new User;
            $Teacher->full_name = $FullName;
            $Teacher->email = $Email;
            $Teacher->password = bcrypt('12345');
            $Teacher->phone_no = '8801' . $ContactNo;
            //$Teacher->username = 'T' . ((int) $StaticID->static_id + (int) $TeacherID);
            $Teacher->is_active = 1;
            $Teacher->role_id = $this->RoleID;
            $Teacher->institute_branch_version_id = $InstituteBranchVersionID;
            $Teacher->school_provided_teacher_id = $TeacherID;
            $Teacher->save();

$LastInsertedId = $Teacher->user_id;
            $UpdateTeacher = User::findOrFail($LastInsertedId);
            $UpdateTeacher->username = 'T' . str_pad($LastInsertedId, 5, '0', STR_PAD_LEFT);
            $UpdateTeacher->save();
$Message .= 'Teacher ID: ' . 'T' . str_pad($LastInsertedId, 5, '0', STR_PAD_LEFT) . '';
        } catch (\Exception $ex) {

            $MessageType = 'ErrorMessage';
            $Message = $ex->getMessage();
        }

        return redirect()->route($this->ViewPagePath . 'Create')
                        ->with($MessageType, $Message);
    }

    public function edit($id) {

        $Teacher = User::findOrFail($id);

        $Data = array();
        $Data['PageTitle'] = 'Edit Teacher';

        $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
        $Data['InstituteBranchVersions'] = $InstituteBranchVersions;
        $Data['Teacher'] = $Teacher;

        return view($this->ViewPagePath . 'Edit', $Data);
    }

    public function update(Request $request, $id) {
        //echo $id;exit();

        $validator = Validator::make($request->all(), [
                    'FullName' => 'required|max:255',
                    'TeacherId' => 'required|digits:2',
                    'Email' => 'email',
                    'ContactNo' => 'required|digits:9',
                    'Branch' => 'required',
                        //'Status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->ViewPagePath . 'Edit', array('id' => $id)))
                            ->withErrors($validator)
                            ->withInput();
        }

        $FullName = $request->input('FullName');
        $TeacherID = $request->input('TeacherId');
        $Email = $request->input('Email');
        $ContactNo = $request->input('ContactNo');
        $InstituteBranchVersionID = $request->input('Branch');
        //$Status = $request->input('Status');
        /* $StaticID = DB::table('tbl_sec_institute_branch_version')
          ->select('static_id')
          ->where('institute_branch_version_id', '=', $InstituteBranchVersionID)
          ->where('is_active', '=', 1)
          ->first(); */

        $MessageType = 'SuccessMessage';
        $Message = 'Teacher updated successfully.';
        try {

            $Teacher = User::find($id);
//            echo '<pre>';print_r($Teacher);echo '</pre>';exit();
            $Teacher->full_name = $FullName;
            $Teacher->email = $Email;
            //$Teacher->password = bcrypt('1234');
            $Teacher->phone_no = '8801' . $ContactNo;
            //$Teacher->username = 'T' . ((int) $StaticID->static_id + (int) $TeacherID);
            //$Teacher->is_active = $Status;
            $Teacher->role_id = $this->RoleID;
            $Teacher->institute_branch_version_id = $InstituteBranchVersionID;
            $Teacher->school_provided_teacher_id = $TeacherID;
if($request->input('password') != NULL){
                $Teacher->password = bcrypt($request->input('password'));
            }
            $Teacher->save();
        } catch (\Exception $ex) {

            $MessageType = 'ErrorMessage';
            $Message = $ex->getMessage();
        }

        return redirect()->route($this->ViewPagePath . 'Edit', array('id' => $id))
                        ->with($MessageType, $Message);
    }

    public function destroy($id) {


        User::where('is_active', 1)
                ->where('user_id', $id)
                ->update(['is_active' => 0]);

        return redirect()->route($this->ViewPagePath . 'Index');
    }

    public function PrintBranchWiseTeachers($IbvID) {

        $Teachers = DB::select(DB::raw('call GetTeachers("1", "' . $IbvID . '")'));
        $Data['Teachers'] = $Teachers;
        $pdf = PDF::loadView($this->ViewPagePath . 'PrintBranchWiseTeachers', $Data);
        //return $pdf->setPaper('a4', 'portrait')->download('Students.pdf');
        return $pdf->setPaper('a4', 'portrait')->stream();
    }

}
