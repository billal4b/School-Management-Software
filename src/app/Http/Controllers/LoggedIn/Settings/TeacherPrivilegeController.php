<?php

namespace App\Http\Controllers\LoggedIn\Settings;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\User;
use Validator;
use App\TeacherPrivilege;

class TeacherPrivilegeController extends Controller {

    private $ViewPagePath = 'LoggedIn.Settings.TeacherPrivilege.';
    private $MenuID = 28;
    protected $LoggedInUserInstituteBranchVersionIDPrivileges = '';
    private $RoleID = 5;

    public function __construct() {
        if (session()->has('PrivilegesOfRole')) {
            foreach (session('PrivilegesOfRole') as $IBVP) {
                $this->LoggedInUserInstituteBranchVersionIDPrivileges .= $IBVP->institute_branch_version_id . ', ';
            }
        } else {
            $this->LoggedInUserInstituteBranchVersionIDPrivileges .= Auth::user()->institute_branch_version_id . ', ';
        }

        $this->LoggedInUserInstituteBranchVersionIDPrivileges = rtrim(trim($this->LoggedInUserInstituteBranchVersionIDPrivileges), ',');
    }

//    public function Index(Request $request) {
//
//        $Data = array();
//        $Data['PageTitle'] = 'Teacher Privilege';
//
//        $ChildMenus = DB::select('call get_child_menu_list("' . $this->MenuID . '")');
//        //echo '<pre>';print_r($Data['ChildMenus']);echo '</pre>';exit();
//        if (!empty($ChildMenus)) {
//
//            $Data['ChildMenus'] = $ChildMenus;
//        } else {
//        }
//        return view($this->ViewPagePath . 'Index', $Data);
//    }

//    public function create() {
    public function Index(){

        $Data = array();
        $Data['PageTitle'] = 'Teacher Privilege';

//        $Classes = DB::select('call get_student_classes("2")');
//        $Groups = DB::select('call get_student_groups("2")');
        $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
        $Data['InstituteBranchVersions'] = $InstituteBranchVersions;
//        $Data['Classes'] = $Classes;
//        $Data['Groups'] = $Groups;

        return view($this->ViewPagePath . 'Create', $Data);
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
        echo '<select class="form-control" id="TeacherId" name="TeacherId">';
        echo '<option value="">----- Teacher -----</option>';
        if (!empty($Teachers)) {
            foreach ($Teachers as $t) {
                echo '<option value="' . $t->user_id . '">' . $t->full_name . ' --- ' .  $t->school_provided_teacher_id . '</option>';
            }
        }
        echo '</select>';
echo '<script type="text/javascript">$(document).ready(function () { $("#TeacherId").select2(); });</script>';
        //return view($this->ViewPagePath . 'GetTeachersByInstituteBranchVersionID', $Data);
    }

    public function GetAssignedSubjectsByTeacherID($TeacherID, $IbvID) {

        $Data = array();

        $Data['TeacherId'] = $TeacherID;
        $Data['Branch'] = $IbvID;

        $Classes = DB::select('call get_student_classes("2")');
        $Groups = DB::select('call get_student_groups("2")');

        $Data['Classes'] = $Classes;
        $Data['Groups'] = $Groups;

        $AssignedSubjects = DB::select('call GetAssignedSubjectsByTeacherID("' . $TeacherID . '")');
        //print_r($AssignedSubjects);exit();
        $Data['AssignedSubjects'] = $AssignedSubjects;

        return view($this->ViewPagePath . 'GetAssignedSubjectsByTeacherID', $Data);
    }

    public function AssignedSubjectsToTeacherID(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        echo '</pre>';
//        exit();

        $validator = Validator::make($request->all(), [
                    'Subject' => 'required',
                    'Class' => 'required',
                    'Group' => 'required',
                    'Branch' => 'required',
                    'Section' => 'required',
                    'TeacherId' => 'required',
        ]);

        $TeacherId = $request->input('TeacherId');
        $Branch = $request->input('Branch');

        if ($validator->fails()) {
            return redirect(route($this->ViewPagePath . 'GetAssignedSubjectsByTeacherID', array('TeacherID' => $TeacherId, 'IbvID' => $Branch)))
                            ->withErrors($validator)
                            ->withInput();
        }

        $Class = $request->input('Class');
        $Group = $request->input('Group');
        $Section = $request->input('Section');
        $Subject = $request->input('Subject');
        $TeacherId = $request->input('TeacherId');
        $Branch = $request->input('Branch');

        $MessageType = 'SuccessMessage';
        $Message = 'Subject inserted successfully.';
        try {

            $Privilege = new TeacherPrivilege;
            $Privilege->user_id = $TeacherId;
            $Privilege->class_id = $Class;
            $Privilege->group_id = $Group;
            $Privilege->section_id = $Section;
            $Privilege->subject_id = $Subject;
            $Privilege->institute_branch_version_id = $Branch;
            $Privilege->is_active = 1;
            $Privilege->save();
        } catch (\Exception $ex) {

            $MessageType = 'ErrorMessage';
            $Message = $ex->getMessage();
        }

        return redirect()->route($this->ViewPagePath . 'GetAssignedSubjectsByTeacherID', array('TeacherID' => $TeacherId, 'IbvID' => $Branch))
                        ->with($MessageType, $Message);
    }

    public function GetSectionsByClassIDAndGroupIDAndIbvID($ClassID, $GroupID, $IbvID) {

        $Sections = DB::table('tbl_sec_sections as tss')
                ->select('s.section_id', 's.SectionName')
                ->join('sectioninfo as s', 's.section_id', '=', 'tss.section_id')
                ->where('tss.class_id', $ClassID)
                ->where('tss.institute_branch_version_id', $IbvID)
                ->where('s.is_active', 1)
                ->get();
        echo '<select name="Section" class="form-control">';
        echo '<option value="">----- Select ------</option>';
        if (!empty($Sections)) {
            foreach ($Sections as $s) {
                echo '<option value="' . $s->section_id . '">' . $s->SectionName . '</option>';
            }
        }
        echo '</select>';
    }

    public function GetSubjectsByClassIDAndGroupIDAndIbvID($ClassID, $GroupID, $IbvID) {

        $Subjects = DB::table('tbl_subjects')
                ->select('subject_id', 'subject_name', 'subject_code')
                ->where('class_id', $ClassID)
                ->where('group_id', $GroupID)
                ->where('institute_branch_version_id', $IbvID)
->where('is_active', 1)
                ->get();
        echo '<select name="Subject" class="form-control">';
        echo '<option value="">----- Select ------</option>';
        if (!empty($Subjects)) {
            foreach ($Subjects as $s) {
                echo '<option value="' . $s->subject_id . '">' . $s->subject_code . ' - ' . $s->subject_name . '</option>';
            }
        }
        echo '</select>';
    }

    public function UpdateStatus(Request $request) {

//        echo '<pre>';
//        print_r($request->all());
//        echo '</pre>';
//        exit();
        
        $Param = $request->input('param');
        $CutUnderscore = explode('_', $Param);
        $RowID = $CutUnderscore[0];
        $UserID = $CutUnderscore[1];
        $Branch = $CutUnderscore[2];
        $Status = $CutUnderscore[3] == 1 ? 1 : 0;

        $MessageType = 'SuccessMessage';
        $Message = 'Data updated successfully.';
        try {

            TeacherPrivilege::where('assigned_subjects_to_teacher_id', $RowID)
                    ->where('user_id', $UserID)
                    ->where('institute_branch_version_id', $Branch)
                    ->update(['is_active' => $Status]);
        } catch (\Exception $ex) {
            
            $MessageType = 'ErrorMessage';
            $Message = $ex->getMessage();
        }
        
        return redirect()->route($this->ViewPagePath . 'GetAssignedSubjectsByTeacherID', array('TeacherID' => $UserID, 'IbvID' => $Branch))
                        ->with($MessageType, $Message);
    }

}
