<?php

namespace App\Http\Controllers\LoggedIn\Settings;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\User;
use Validator;
use App\ClassTeacher;

class ClassTeacherController extends Controller {

    private $ViewPagePath = 'LoggedIn.Settings.ClassTeacher.';
    //private $MenuID = 28;
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
        $Data['PageTitle'] = 'Class Teacher';

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
                ->get();
        //$Data['Teachers'] = $Teachers;
//        echo '<pre>';print_r($Teachers);echo '</pre>';exit();
        echo '<select class="form-control" id="TeacherId" name="TeacherId">';
        echo '<option value="">----- Teacher -----</option>';
        if (!empty($Teachers)) {
            foreach ($Teachers as $t) {
                echo '<option value="' . $t->user_id . '">' . $t->school_provided_teacher_id . ' --- ' . $t->full_name . '</option>';
            }
        }
        echo '</select>';
        //return view($this->ViewPagePath . 'GetTeachersByInstituteBranchVersionID', $Data);
    }

    public function GetAssignedClassTeachersByTeacherID($TeacherID, $IbvID) {

        $Data = array();

        $Data['TeacherId'] = $TeacherID;
        $Data['Branch'] = $IbvID;

        $Classes = DB::table('tbl_assigned_subjects_to_teachers as tastt')
                ->select('tastt.class_id', 'ci.ClassName')
                ->join('classinfo as ci', 'ci.id', '=', 'tastt.class_id')
                ->where('tastt.user_id', $TeacherID)
                ->groupBy('tastt.class_id')
                ->get();
//        $Groups = DB::select('call get_student_groups("2")');

        $Data['Classes'] = $Classes;
//        $Data['Groups'] = $Groups;

        $AssignedClassTeachers = DB::select('call GetAssignedClassTeachersByTeacherID("' . $TeacherID . '")');
//        print_r($AssignedClassTeachers);exit();
        $Data['AssignedClassTeachers'] = $AssignedClassTeachers;

        return view($this->ViewPagePath . 'GetAssignedClassTeachersByTeacherID', $Data);
    }
    
    public function GetGroupsByClassIDAndTeacherID($ClassID, $TeacherID){
        
        $Groups = DB::table('tbl_assigned_subjects_to_teachers as tastt')
                ->select('tastt.group_id', 'sg.GroupName')
                ->join('stugrp as sg', 'sg.id', '=', 'tastt.group_id')
                ->where('tastt.user_id', $TeacherID)
                ->where('tastt.class_id', $ClassID)
                ->groupBy('tastt.group_id')
                ->get();
        echo '<select name="Group" class="form-control" id="Group">';
        echo '<option value="">----- Select ------</option>';
        if (!empty($Groups)) {
            foreach ($Groups as $g) {
                echo '<option value="' . $g->group_id . '">' . $g->GroupName . '</option>';
            }
        }
        echo '</select>';
    }
    
    public function GetSectionsByClassIDTeacherIDAndGroupID($ClassID, $TeacherID, $GroupID){
        
        $Sections = DB::table('tbl_assigned_subjects_to_teachers as tastt')
                ->select('tastt.section_id', 'si.SectionName')
                ->join('sectioninfo as si', 'si.section_id', '=', 'tastt.section_id')
                ->where('tastt.user_id', $TeacherID)
                ->where('tastt.class_id', $ClassID)
                ->where('tastt.group_id', $GroupID)
                ->groupBy('tastt.section_id')
                ->get();
        echo '<select name="Section" class="form-control" id="Section">';
        echo '<option value="">----- Select ------</option>';
        if (!empty($Sections)) {
            foreach ($Sections as $s) {
                echo '<option value="' . $s->section_id . '">' . $s->SectionName . '</option>';
            }
        }
        echo '</select>';
    }

    public function AssignedClassTeachersToTeacherID(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        echo '</pre>';
//        exit();

        $validator = Validator::make($request->all(), [
                    'Class' => 'required',
                    'Group' => 'required',
                    'Branch' => 'required',
                    'Section' => 'required',
                    'TeacherId' => 'required',
        ]);

        $TeacherId = $request->input('TeacherId');
        $Branch = $request->input('Branch');

        if ($validator->fails()) {
            return redirect(route($this->ViewPagePath . 'GetAssignedClassTeachersByTeacherID', array('TeacherID' => $TeacherId, 'IbvID' => $Branch)))
                            ->withErrors($validator)
                            ->withInput();
        }

        $Class = $request->input('Class');
        $Group = $request->input('Group');
        $Section = $request->input('Section');
        $TeacherId = $request->input('TeacherId');
        $Branch = $request->input('Branch');

        $MessageType = 'SuccessMessage';
        $Message = 'Class Teacher inserted successfully.';
        try {

            $ClassTeacher = new ClassTeacher;
            $ClassTeacher->user_id = $TeacherId;
            $ClassTeacher->class_id = $Class;
            $ClassTeacher->group_id = $Group;
            $ClassTeacher->section_id = $Section;
            $ClassTeacher->institute_branch_version_id = $Branch;
            $ClassTeacher->is_active = 1;
            $ClassTeacher->save();
        } catch (\Exception $ex) {

            $MessageType = 'ErrorMessage';
            $Message = $ex->getMessage();
        }

        return redirect()->route($this->ViewPagePath . 'GetAssignedClassTeachersByTeacherID', array('TeacherID' => $TeacherId, 'IbvID' => $Branch))
                        ->with($MessageType, $Message);
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

            ClassTeacher::where('class_teacher_id', $RowID)
                    ->where('user_id', $UserID)
                    ->where('institute_branch_version_id', $Branch)
                    ->update(['is_active' => $Status]);
        } catch (\Exception $ex) {
            
            $MessageType = 'ErrorMessage';
            $Message = $ex->getMessage();
        }
        
        return redirect()->route($this->ViewPagePath . 'GetAssignedClassTeachersByTeacherID', array('TeacherID' => $UserID, 'IbvID' => $Branch))
                        ->with($MessageType, $Message);
    }

}
