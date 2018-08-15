<?php

namespace App\Http\Controllers\LoggedIn\MarksAnalysis;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Validator;
use App\User;
use PDF;

class DefaultedListController extends Controller {

    private $ViewPagePath = 'LoggedIn.MarksAnalysis.DefaultedList.';
//    private $MenuID = 28;
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

    public function Index(Request $request) {

        $Data = array();
        $Data['PageTitle'] = 'Defaulted List';

//        $Teachers = DB::select(DB::raw('call GetTeachers("1", "' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
//        $Data['Teachers'] = $Teachers;

        $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
        $ExamTypes = DB::select(DB::raw('call GetExamTypes("1")'));
        $ExamHeads = DB::select(DB::raw('call GetExamHeads("1")'));
        $ExamSubHeads = DB::select(DB::raw('call GetExamSubHeads("1", "0")'));

        $Data['InstituteBranchVersions'] = $InstituteBranchVersions;
        $Data['ExamTypes'] = $ExamTypes;
        $Data['ExamHeads'] = $ExamHeads;
        $Data['ExamSubHeads'] = $ExamSubHeads;

        return view($this->ViewPagePath . 'Index', $Data);
    }

    public function GetExamSubHeadsByExamHeadID($ExamHeadID) {

        $ExamSubHeads = DB::select(DB::raw('call GetExamSubHeads("1", "' . $ExamHeadID . '")'));
        //echo '<pre>';print_r($ExamSubHeads);echo '</pre>';exit();
        echo '<select class="form-control ExamSubHead" id="ExamSubHead" name="ExamSubHead">';
        echo '<option value="">----- Exam Sub Head -----</option>';
        if (!empty($ExamSubHeads)) {
            foreach ($ExamSubHeads as $t) {
                echo '<option value="' . $t->exam_sub_head_id . '">' . $t->exam_sub_head_alias . '</option>';
            }
        }
        echo '</select>';
        //return view($this->ViewPagePath . 'GetTeachersByInstituteBranchVersionID', $Data);
    }

    public function GetTeachersByInstituteBranchVersionID($IbvID) {

        $Teachers = User::select('user_id', 'full_name', 'school_provided_teacher_id')
                ->where('institute_branch_version_id', $IbvID)
                ->where('is_active', 1)
                ->where('role_id', $this->RoleID)
                //->orderBy('school_provided_teacher_id', 'asc')
                ->orderBy('full_name', 'asc')
                ->get();
        //$Data['Teachers'] = $Teachers;
        //echo '<pre>';print_r($Teachers);echo '</pre>';exit();
        echo '<select class="form-control TeacherId" id="TeacherId" name="TeacherId">';
        echo '<option value="">----- Teacher -----</option>';
        if (!empty($Teachers)) {
            foreach ($Teachers as $t) {
                echo '<option value="' . $t->user_id . '">' . $t->full_name . ' --- ' . $t->school_provided_teacher_id . '</option>';
            }
        }
        echo '</select>';
        echo '<script type="text/javascript">$(document).ready(function () { $("#TeacherId").select2(); });</script>';
        //return view($this->ViewPagePath . 'GetTeachersByInstituteBranchVersionID', $Data);
    }

    public function Show(Request $request) {
        

        $validator = Validator::make($request->all(), [
                    'ExamType' => 'required',
                    'ExamHead' => 'required',
                    'ExamSubHead' => 'required',
                    'Branch' => 'required',
        ]);

        if ($validator->fails()) {
            return view('Layouts.FormValidationErrors')
                            ->withErrors($validator);
            exit();
        }

DB::select('update tbl_marks_input tmi
inner join tbl_students ts on tmi.student_id = ts.student_id
set tmi.section_id = ts.section_id');

        $Data = array();
        $Data['Branch'] = $request->Branch;
        $Data['ExamTypeID'] = $request->ExamType;
        $Data['ExamHeadID'] = $request->ExamHead;
        $Data['ExamSubHeadID'] = $request->ExamSubHead;
//echo '<pre>';print_r($Data);echo '</pre>';exit();
        return view($this->ViewPagePath . 'Show', $Data);



//        echo '<pre>';
//        print_r($TeacherPrivilege);
//        echo '</pre>';
//        exit();
//        return view($this->ViewPagePath . 'Show', $Data);
    }

}
