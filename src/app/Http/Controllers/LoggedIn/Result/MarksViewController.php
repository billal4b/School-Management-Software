<?php

namespace App\Http\Controllers\LoggedIn\Result;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Validator;
use App\MarksInput;

class MarksViewController extends Controller {

    private $ViewPagePath = 'LoggedIn.Result.MarksView.';
//    private $MenuID = 30;
    protected $LoggedInUserInstituteBranchVersionIDPrivileges = '';
    private $RoleID = 5;

    public function __construct() {


        try {

            //echo '<pre>';var_dump(session('PrivilegesOfRole'));exit();
            if (session()->has('PrivilegesOfRole') && !empty(session('PrivilegesOfRole'))) {
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
        $Data['PageTitle'] = 'Marks View';

        $ExamTypes = DB::select(DB::raw('call GetExamTypes("1")'));
        $Data['ExamTypes'] = $ExamTypes;

        return view($this->ViewPagePath . 'Index', $Data);
    }

    public function GetSectionsByClassIDTeacherIDAndGroupID($ClassID, $TeacherID, $GroupID, $IbvID) {


        $RolesID = array(4, 11, 12, 13, 14, 15, 16, 17, 18, 19);
        if (in_array(Auth::user()->role_id, $RolesID)) {

            //$Branch = DB::table('tbl_users')->select('institute_branch_version_id')->where('user_id', $TeacherID)->first();
            $Sections = DB::table('tbl_sec_sections')
                    ->join('sectioninfo', 'sectioninfo.section_id', '=', 'tbl_sec_sections.section_id')
                    ->select('tbl_sec_sections.*', 'sectioninfo.SectionName')
                    ->where('institute_branch_version_id', $IbvID)
                    ->where('class_id', '=', $ClassID)
//                ->where('group_id', '=', $GroupID)
                    ->get();
        } else {

//            $QueryForCheckIsClassTeacher = DB::table('tbl_class_teachers')
//                    ->select('class_teacher_id')
//                    ->where('user_id', Auth::user()->user_id)
//                    ->first();
//            $CheckIsClassTeacher = empty($QueryForCheckIsClassTeacher) ? 0 : 1;
//            if ($CheckIsClassTeacher) {
//                
//            }

            $Sections = DB::table('tbl_assigned_subjects_to_teachers as tastt')
                    ->select('tastt.section_id', 'si.SectionName')
                    ->join('sectioninfo as si', 'si.section_id', '=', 'tastt.section_id')
                    ->where('tastt.user_id', $TeacherID)
                    ->where('tastt.class_id', $ClassID)
                    ->where('tastt.group_id', $GroupID)
                    ->where('tastt.is_active', 1)
                    ->groupBy('tastt.section_id')
                    ->get();
        }


        echo '<select name="Section" class="form-control Section" id="Section">';
        echo '<option value="">----- Select ------</option>';
        if (!empty($Sections)) {
            foreach ($Sections as $s) {
                echo '<option value="' . $s->section_id . '">' . $s->SectionName . '</option>';
            }
        }
        echo '</select>';
    }

    public function GetSubjectsByClassIDTeacherIDAndGroupID($Branch, $ClassID, $TeacherID, $GroupID, $SectionID) {

        $RolesID = array(4, 11, 12, 13, 14, 15, 16, 17, 18, 19);
        if (in_array(Auth::user()->role_id, $RolesID)) {

            $Sections = DB::table('tbl_subjects')
                    ->select('subject_id', 'subject_name', 'subject_code')
                    ->where('is_active', 1)
                    ->where('institute_branch_version_id', $Branch)
                    ->where('class_id', $ClassID)
                    ->where('group_id', $GroupID)
                    ->get();
        } else {

            $Sections = DB::table('tbl_assigned_subjects_to_teachers as tastt')
                    ->select('tastt.subject_id', 'ts.subject_name')
                    ->join('tbl_subjects as ts', 'ts.subject_id', '=', 'tastt.subject_id')
                    ->where('tastt.user_id', $TeacherID)
                    ->where('tastt.class_id', $ClassID)
                    ->where('tastt.section_id', $SectionID)
                    ->where('tastt.group_id', $GroupID)
                    ->where('tastt.is_active', 1)
                    //->groupBy('tastt.section_id')
                    ->get();
        }


        echo '<select name="Subject" class="form-control Subject" id="Subject">';
        echo '<option value="">----- Select ------</option>';
        if (!empty($Sections)) {
            foreach ($Sections as $s) {
                echo '<option value="' . $s->subject_id . '">' . $s->subject_name . '</option>';
            }
        }
        echo '</select>';
    }

    public function show(Request $request) {

//    print_r($request->all());
//        echo '<pre>';
//        print_r($request->all());
//        echo '</pre>';
//        exit();
        $validator = Validator::make($request->all(), [
                    'ExamType' => 'required',
        ]);

        if ($validator->fails()) {
            return view('Layouts.FormValidationErrors')
                            ->withErrors($validator);
            exit();
        }

        $ExamTypeID = $request->input('ExamType');

        $LoggedInUserRoleID = Auth::user()->role_id;
        $LoggedInUserID = Auth::user()->user_id;
//        $QueryForCheckIsClassTeacher = DB::table('tbl_class_teachers as tct')
//                ->select('tct.class_id', 'tct.group_id', 'tct.section_id')
//                ->where('user_id', Auth::user()->user_id)
//                ->first();
//        $CheckIsClassTeacher = empty($QueryForCheckIsClassTeacher) ? 0 : 1;
        $TeacherPrivilege = DB::table('tbl_assigned_subjects_to_teachers as tastt')
                ->select('tastt.subject_id', 'ts.subject_name', 'tastt.class_id', 'tastt.group_id', 'tastt.section_id', 'ci.ClassName', 'sg.GroupName', 'si.SectionName', 'si.section_id', 'ci.id as class_id', 'sg.id as group_id', 'tastt.institute_branch_version_id')
                ->join('tbl_subjects as ts', 'ts.subject_id', '=', 'tastt.subject_id')
                ->join('classinfo as ci', 'ci.id', '=', 'tastt.class_id')
                ->join('stugrp as sg', 'sg.id', '=', 'tastt.group_id')
                ->join('sectioninfo as si', 'si.section_id', '=', 'tastt.section_id')
                ->where('tastt.user_id', $LoggedInUserID)
->orderBy('ts.subject_code', 'asc')
//                ->where('tastt.class_id', $ClassID)
//                ->where('tastt.section_id', $SectionID)
//                ->where('tastt.group_id', $GroupID)
                ->where('tastt.is_active', 1)
                ->get();

        $ClassTeacher = DB::select('select 
    ci.id class_id,
	ci.ClassName,
	sg.id group_id,
	sg.GroupName,
	si.section_id,
	si.SectionName,
	ts.subject_id,
	ts.subject_name,
        tst.institute_branch_version_id
from
    tbl_class_teachers tst
        inner join
    tbl_subjects ts ON (ts.class_id = tst.class_id
        and tst.institute_branch_version_id = ts.institute_branch_version_id
        and ts.group_id = tst.group_id)
	inner join classinfo ci on ci.id = tst.class_id
	inner join stugrp sg on sg.id = tst.group_id
	inner join sectioninfo si on si.section_id = tst.section_id
where
    tst.user_id = ' . $LoggedInUserID . ' and tst.is_active = 1 and ts.is_active = 1 order by ts.subject_code');
//        echo '<pre>';
//        print_r($ClassTeacher);
//        echo '</pre>';
//        exit();

        $Data['TeacherPrivilege'] = $TeacherPrivilege;
        $Data['ClassTeacher'] = $ClassTeacher;
        $Data['ExamTypeID'] = $ExamTypeID;

        $ExamSubHeads = DB::select(DB::raw('call GetExamSubHeads("1", "0")'));
        $Data['ExamSubHeads'] = $ExamSubHeads;

//        echo '<pre>';
//        print_r($TeacherPrivilege);
//        echo '</pre>';
//        exit();

        return view($this->ViewPagePath . 'Show', $Data);
    }

    public function Save(Request $request) {

//        var_dump($request->input('Absent'));exit();
//        echo '<pre>';
//        print_r($request->all());
//        echo '</pre>';
//        exit();

        $Student = $request->input('Student');
        $Subject = $request->input('Subject');
        $ExamHead = $request->input('ExamHead');
        $ExamType = $request->input('ExamType');
        $ExamSubHead = $request->input('ExamSubHead');
        $Class = $request->input('Class');
        $Group = $request->input('Group');
        $Branch = $request->input('Branch');
        $Section = $request->input('Section');
        $Marks = $request->input('Marks');
        $MarksInput = $request->input('MarksInput');
        $Absent = $request->input('Absent');

//        $TotalMarks = count($Marks);
//        $TotalInputFrom = count($InputFrom);
//        $TotalInputTo = count($InputTo);
//        $TotalMarksDistribution = count($MarksDistribution);
//        $TotalExamSubHead = count($ExamSubHead);
        //echo $Subject;exit();
        if ($MarksInput == 0) {
            $Md = new MarksInput;
            $Md->subject_id = $Subject;
            $Md->student_id = $Student;
            $Md->class_id = $Class;
            $Md->group_id = $Group;
            $Md->section_id = $Section;
            $Md->institute_branch_version_id = $Branch;
            $Md->exam_type_id = $ExamType;
            $Md->exam_sub_head_id = $ExamSubHead;
            $Md->exam_head_id = $ExamHead;
            if ($request->input('Absent') == NULL) {
                $Md->marks = $Marks;
            } else {
                $Md->marks = -1;
            }
            $Md->is_active = 1;
            $Md->create_time = date('Y-m-d h:i:s');
            $Md->create_logon_id = session('sessLogonId');
            $Md->create_user_id = Auth::user()->user_id;
            $Md->last_action = 'INSERT';
            $Md->save();
        } else {

            $Md = MarksInput::find($MarksInput);
            $Md->subject_id = $Subject;
            $Md->student_id = $Student;
            $Md->class_id = $Class;
            $Md->group_id = $Group;
            $Md->section_id = $Section;
            $Md->institute_branch_version_id = $Branch;
            $Md->exam_type_id = $ExamType;
            $Md->exam_sub_head_id = $ExamSubHead;
            $Md->exam_head_id = $ExamHead;
            if ($request->input('Absent') == NULL) {
                $Md->marks = $Marks;
            } else {
                $Md->marks = -1;
            }
            $Md->is_active = 1;
            $Md->update_time = date('Y-m-d h:i:s');
            $Md->update_logon_id = session('sessLogonId');
            $Md->update_user_id = Auth::user()->user_id;
            $Md->last_action = 'UPDATE';
            $Md->save();
        }
        //echo 'Data updated successfully!';
    }

    public function GetStudentsByClassIDAndGroupIDAndSectionIDAndIbvID($IbvID, $ClassID, $GroupID, $SectionID) {

        $Students = DB::table('tbl_students')
                ->select('student_id', 'system_generated_student_id', 'roll_no', 'student_name')
                ->where('class_id', $ClassID)
                ->where('stu_group', $GroupID)
                ->where('section_id', $SectionID)
                ->where('institute_branch_version_id', $IbvID)
                ->orderBy('roll_no')
                ->get();

        echo '<select name="Students" class="form-control Students" id="Students">';
        echo '<option value="">----- Select ------</option>';
        if (!empty($Students)) {
            foreach ($Students as $s) {
                echo '<option value="' . $s->student_id . '">' . $s->system_generated_student_id . '</option>';
            }
        }
        echo '</select>';
    }
    
    public function PrintMarksView($Param, Request $request){
        
        $Data = array();
        $CutDash = explode('-', $Param);
        foreach($CutDash as $key => $value){
            
            $CutUnderScores = explode('_', $value);
//            echo '<pre>';
//        print_r($CutUnderScores);
//        echo '</pre>';
//        exit();
            $Data['CutUnderScores'][] = $CutUnderScores;
        }
        
        return view($this->ViewPagePath . 'PrintMarksView', $Data);
    }

}
