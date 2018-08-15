<?php

namespace App\Http\Controllers\LoggedIn\Result;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Validator;
use App\MarksInput;
use App\MarksInput2;

class MarksInputController extends Controller {

    private $ViewPagePath = 'LoggedIn.Result.MarksInput.';
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
        $Data['PageTitle'] = 'Marks Input';

        $Classes = DB::select('call get_student_classes("2")');
        $Groups = DB::select('call get_student_groups("2")');
        $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
        $ExamTypes = DB::select(DB::raw('call GetExamTypes("1")'));
        $ExamHeads = DB::select(DB::raw('call GetExamHeads("2")'));
        $Data['ExamTypes'] = $ExamTypes;
        $Data['ExamHeads'] = $ExamHeads;


        $Data['Classes'] = $Classes;
        $Data['Groups'] = $Groups;
        $Data['InstituteBranchVersions'] = $InstituteBranchVersions;

//        if (Auth::user()->institute_branch_version_id == 13 || Auth::user()->institute_branch_version_id == 4 || Auth::user()->institute_branch_version_id == 8 || Auth::user()->institute_branch_version_id == 12 || Auth::user()->institute_branch_version_id == 9) {
//            return view($this->ViewPagePath . 'Index_13', $Data);
//        } else {
        return view($this->ViewPagePath . 'Index', $Data);
//        }
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

//        echo '<pre>';
//        print_r($request->all());
//        echo '</pre>';
//        exit();
        $validator = Validator::make($request->all(), [
                    'Class' => 'required',
                    'Group' => 'required',
                    'Branch' => 'required',
                    'ExamType' => 'required',
                    'ExamHead' => 'required',
//                        'ExamSubHead' => 'required',
                    'Section' => 'required',
                    'Subject' => 'required',
                        /* 'AttendedStudentID' => 'required',
                          'StudentName' => 'required',
                          'StudentID' => 'required', */
        ]);

        if ($validator->fails()) {
            return view('Layouts.FormValidationErrors')
                            ->withErrors($validator);
            exit();
        }


        $IbvID = $request->input('Branch');
        $ClassID = $request->input('Class');
        $SectionID = $request->input('Section');
        $GroupID = $request->input('Group');
        $SubjectID = $request->input('Subject');
        $ExamTypeID = $request->input('ExamType');
        $ExamHead = $request->input('ExamHead') ? $request->input('ExamHead') : 0;
        $Students = $request->input('Students');
        //echo $ExamHead;exit();
        $ExamSubHeads = DB::select(DB::raw('call GetExamSubHeads("1", "' . $ExamHead . '")'));
        $Data = array();
        $Data['ExamSubHeads'] = $ExamSubHeads;
        $Data['ExamHead'] = $ExamHead;
        $Data['SectionID'] = $SectionID;


//        $RolesID = array(4, 11, 12, 13, 14, 15, 16, 17, 18, 19);
//        if (empty($Students) && in_array(Auth::user()->role_id, $RolesID)) {
        $Students = DB::table('tbl_students')
                ->select('student_id', 'system_generated_student_id', 'roll_no', 'student_name')
                ->where('class_id', $ClassID)
                ->where('stu_group', $GroupID)
                ->where('section_id', $SectionID)
                ->where('institute_branch_version_id', $IbvID)
                ->where('status', 1)
                ->orderBy('roll_no')
                ->get();
//        } else if (Auth::user()->institute_branch_version_id == 13 || Auth::user()->institute_branch_version_id == 4 || Auth::user()->institute_branch_version_id == 8 || Auth::user()->institute_branch_version_id == 12 || Auth::user()->institute_branch_version_id == 9) {
//            $Students = DB::table('tbl_students')
//                    ->select('student_id', 'system_generated_student_id', 'roll_no', 'student_name')
//                    ->where('class_id', $ClassID)
//                    ->where('stu_group', $GroupID)
//                    ->where('section_id', $SectionID)
//                    ->where('institute_branch_version_id', $IbvID)
//                    ->orderBy('roll_no')
//                    ->get();
//        } else {
//            $Students = DB::table('tbl_students')
//                    ->select('student_id', 'system_generated_student_id', 'roll_no', 'student_name')
//                    ->where('student_id', $Students)
//                    ->get();
//        }

        $ExistingMarksInputs = array();
        $ExistingMarksInputs[0] = array(
            'ExistingID' => 0,
            'MarksInputID' => 0,
            'Marks' => 0,
            'IsActive' => 0,
        );
        $ActiveExamSubHeads = array();
        $ActiveExamSubHeads[] = array(
            'ActiveExamSubHeadID' => 0,
            'MaxMarks' => 0,
            'PassMarks' => 0,
        );
        foreach ($ExamSubHeads as $esh) {

            $RolesID = array(4, 11, 12, 13, 14, 15, 16, 17, 18, 19);
            if (in_array(Auth::user()->role_id, $RolesID)) { /// Is Branch Admin. They get only is_active = 1
                
                $IsExist = DB::table('tbl_marks_distributions')
                        ->select('full_marks', 'pass_marks')
                        ->where('subject_id', $SubjectID)
                        ->where('class_id', $ClassID)
                        ->where('group_id', $GroupID)
                        ->where('institute_branch_version_id', $IbvID)
                        ->where('exam_type_id', $ExamTypeID)
                        ->where('exam_sub_head_id', $esh->exam_sub_head_id)
                        ->where('exam_head_id', $ExamHead)
                        ->where('is_active', 1)
                        ->first();
            } else {
                
                $IsExist = DB::select('call PrivilegeOfMarksInputByTeacher("' . $ExamTypeID . '", "' . $ExamHead . '", "' . $esh->exam_sub_head_id . '", "' . $ClassID . '", "' . $GroupID . '", "' . $SubjectID . '", "' . $IbvID. '", "' . date("Y-m-d") . '")');
                //echo '<pre>';print_r($IsExist);echo '</pre>';exit();
            }

            if (!empty($IsExist)) {
                $ActiveExamSubHeads[] = array(
                    'ActiveExamSubHeadID' => $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $esh->exam_sub_head_id . '-' . $SubjectID,
                    'MaxMarks' => isset($IsExist->full_marks) ? $IsExist->full_marks : $IsExist[0]->full_marks,
                    'PassMarks' => isset($IsExist->pass_marks) ? $IsExist->pass_marks : $IsExist[0]->pass_marks,
                );
            }
        }
        $Data['ActiveExamSubHeads'] = $ActiveExamSubHeads;

        foreach ($Students as $stu) {
            foreach ($ExamSubHeads as $esh) {

                $IsExist = DB::table('tbl_marks_input')
                        ->select('marks_input_id', 'marks', 'is_active', 'is_absent'/* , 'is_marks_input' */)
                        ->where('subject_id', $SubjectID)
                        ->where('section_id', $SectionID)
                        ->where('class_id', $ClassID)
                        ->where('group_id', $GroupID)
                        ->where('institute_branch_version_id', $IbvID)
                        ->where('exam_type_id', $ExamTypeID)
                        ->where('exam_sub_head_id', $esh->exam_sub_head_id)
                        ->where('exam_head_id', $ExamHead)
                        ->where('student_id', $stu->student_id)
                        ->first();
//        
                if (!empty($IsExist)) {
                    $ExistingMarksInputs[] = array(
                        'ExistingID' => $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $esh->exam_sub_head_id . '-' . $SubjectID . '-' . $stu->student_id,
                        'MarksInputID' => $IsExist->marks_input_id,
                        'Marks' => $IsExist->marks,
                        'IsActive' => $IsExist->is_active,
                        'IsAbsent' => $IsExist->is_absent,
                            //'IsMarksInput' => $IsExist->is_marks_input,
                    );
                }
            }
        }



        $Data['IbvID'] = $IbvID;
        $Data['ClassID'] = $ClassID;
        $Data['GroupID'] = $GroupID;
        $Data['SubjectID'] = $SubjectID;
        $Data['ExamTypeID'] = $ExamTypeID;
        $Data['Students'] = $Students;
        $Data['ExistingMarksInputs'] = $ExistingMarksInputs;
//        if(Auth::user()->role_id == 5){ 
//        }
//        if (Auth::user()->institute_branch_version_id == 13 || Auth::user()->institute_branch_version_id == 4 || Auth::user()->institute_branch_version_id == 8 || Auth::user()->institute_branch_version_id == 12 || Auth::user()->institute_branch_version_id == 9) {
//            return view($this->ViewPagePath . 'Show_13', $Data);
//        } else {
        return view($this->ViewPagePath . 'ShowAddUpdateForAll', $Data);
//        }
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
        $PassMarks = $request->input('PassMarks');
        $FullMarks = $request->input('MaxMarks');
        $MarksInput = $request->input('MarksInput');
        $IsMarksInput = $request->input('IsMarksInput');
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
                $Md->is_absent = 1;
            }
            $Md->pass_marks = $PassMarks;
            $Md->full_marks = $FullMarks;
            //$Md->is_marks_input = $IsMarksInput;
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
                $Md->is_absent = 0;
            } else {
                $Md->marks = '';
                $Md->is_absent = 1;
            }
            $Md->pass_marks = $PassMarks;
            $Md->full_marks = $FullMarks;
            //$Md->is_marks_input = $IsMarksInput;
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

//    public function GetExamSubHeadsByClassIDTeacherIDAndGroupIDExamTypeExamHeadSection($Branch, $ClassID, $TeacherID, $GroupID, $SectionID, $ExamTypeID, $ExamHeadID) {
//
//        $RolesID = array(4, 11, 12, 13, 14, 15, 16, 17, 18, 19);
//        if (in_array(Auth::user()->role_id, $RolesID)) {
//
//            $ExamSubHeads = DB::table('tbl_exam_sub_heads')
//                    ->select('exam_sub_head_id', 'exam_sub_head_name', 'exam_sub_head_alias')
//                    ->where('is_active', 1)
//                    ->where('institute_branch_version_id', $Branch)
//                    ->where('class_id', $ClassID)
//                    ->where('group_id', $GroupID)
//                    ->where('exam_type_id', $ExamTypeID)
//                    ->where('exam_head_id', $ExamHeadID)
//                    ->get();
//        } else {
//
//            $ExamSubHeads = DB::table('tbl_marks_distributions as tmd')
//                    ->select('tesh.exam_sub_head_id', 'tesh.exam_sub_head_name', 'tesh.exam_sub_head_alias')
//                    ->join('tbl_exam_sub_heads as tesh')
//                    ->where('tmd.is_active', 1)
//                    ->where('tmd.institute_branch_version_id', $Branch)
//                    ->where('tmd.class_id', $ClassID)
//                    ->where('tmd.group_id', $GroupID)
//                    ->where('tmd.exam_type_id', $ExamTypeID)
//                    ->where('tmd.exam_head_id', $ExamHeadID)
//                    ->get();
//        }
//    }
}
