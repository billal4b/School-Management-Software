<?php

namespace App\Http\Controllers\LoggedIn\Settings;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Validator;
use App\User;
use App\MarksDistribution;

class MarksDistributionController extends Controller {

    private $ViewPagePath = 'LoggedIn.Settings.MarksDistribution.';
    private $MenuID = 49;
    protected $LoggedInUserInstituteBranchVersionIDPrivileges = '';

    //private $RoleID = 5;

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
        $Data['PageTitle'] = 'Marks Distribution';

        $ExamTypes = DB::select(DB::raw('call GetExamTypes("1")'));
        $ExamHeads = DB::select(DB::raw('call GetExamHeads("1")'));
        $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
        $Classes = DB::select('call get_student_classes("2")');
        $Groups = DB::select('call get_student_groups("2")');


        $Data['Classes'] = $Classes;
        $Data['Groups'] = $Groups;
        $Data['InstituteBranchVersions'] = $InstituteBranchVersions;
        $Data['ExamTypes'] = $ExamTypes;
        $Data['ExamHeads'] = $ExamHeads;

        return view($this->ViewPagePath . 'Index', $Data);
//        }
    }

    public function Show(Request $request) {

        $validator = Validator::make($request->all(), [
                    'Branch' => 'required',
                    'Class' => 'required',
                    'Group' => 'required',
                    'ExamType' => 'required',
                    'ExamHead' => 'required',
                        /* 'AttendedStudentID' => 'required',
                          'StudentName' => 'required',
                          'StudentID' => 'required', */
        ]);

        if ($validator->fails()) {
            return view('Layouts.FormValidationErrors')
                            ->withErrors($validator);
        }

        $IbvID = $request->input('Branch');
        $ClassID = $request->input('Class');
        $GroupID = $request->input('Group');
        $ExamTypeID = $request->input('ExamType');
        $ExamHead = $request->input('ExamHead') ? $request->input('ExamHead') : 0;
        //echo $ExamHead;exit();
        $ExamSubHeads = DB::select(DB::raw('call GetExamSubHeads("1", "' . $ExamHead . '")'));
        $Data = array();
        $Data['ExamHead'] = $ExamHead;
        $Data['ExamSubHeads'] = $ExamSubHeads;

        $Subjects = DB::table('tbl_subjects')
                ->select('subject_id', 'subject_name', 'subject_code')
                ->where('is_active', 1)
                ->where('institute_branch_version_id', $IbvID)
                ->where('class_id', $ClassID)
                ->where('group_id', $GroupID)
                ->get();


        $ExistingMarksDistributions = array();
        $ExistingMarksDistributions[0] = array(
            'ExistingID' => 0,
            'MarksDistributionID' => 0,
            'FullMarks' => 0,
            'InputFrom' => 0,
            'InputTo' => 0,
            'IsActive' => 0,
        );
        foreach ($Subjects as $sub) {
            foreach ($ExamSubHeads as $esh) {
                $IsExist = DB::table('tbl_marks_distributions')
                        ->select('marks_distribution_id', 'full_marks', 'allowed_input_date_time_from', 'allowed_input_date_time_to', 'is_active', 'pass_marks')
                        ->where('subject_id', $sub->subject_id)
                        ->where('class_id', $ClassID)
                        ->where('group_id', $GroupID)
                        ->where('institute_branch_version_id', $IbvID)
                        ->where('exam_type_id', $ExamTypeID)
                        ->where('exam_sub_head_id', $esh->exam_sub_head_id)
                        ->where('exam_head_id', $ExamHead)
                        ->first();
                if (!empty($IsExist)) {
                    $ExistingMarksDistributions[] = array(
                        'ExistingID' => $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $esh->exam_head_id . '-' . $esh->exam_sub_head_id . '-' . $sub->subject_id,
                        'MarksDistributionID' => $IsExist->marks_distribution_id,
                        'FullMarks' => $IsExist->full_marks,
                        'InputFrom' => $IsExist->allowed_input_date_time_from,
                        'InputTo' => $IsExist->allowed_input_date_time_to,
                        'IsActive' => $IsExist->is_active,
                        'PassMarks' => $IsExist->pass_marks,
                    );
                }
            }
        }



        $Data['IbvID'] = $IbvID;
        $Data['ClassID'] = $ClassID;
        $Data['GroupID'] = $GroupID;
        $Data['ExamTypeID'] = $ExamTypeID;
        $Data['Subjects'] = $Subjects;
        $Data['ExistingMarksDistributions'] = $ExistingMarksDistributions;
        //echo 'hi';
        return view($this->ViewPagePath . 'Show', $Data);
    }

    public function Save(Request $request) {

        //echo $request->input('PassMarks') . '--';exit();
        /*echo '<pre>';
        print_r($request->all());
        echo '</pre>';
        exit();*/

        $Student = $request->input('Student');
        $Subject = $request->input('Subject');
        $ExamHead = $request->input('ExamHead');
        $ExamType = $request->input('ExamType');
        $ExamSubHead = $request->input('ExamSubHead');
        $Class = $request->input('Class');
        $Group = $request->input('Group');
        $Branch = $request->input('Branch');
        $Section = $request->input('Section');
        $Marks = $request->input('FullMarks') == '' ? 0 : $request->input('FullMarks');
        $InputFrom = $request->input('From');
        $InputTo = $request->input('To');
        $IsActive = $request->input('IsActive');
        $MarksDistribution = $request->input('MarksDistribution');
        $PassMarks = $request->input('PassMarks') == '' ? 0 : $request->input('PassMarks');

//        $TotalMarks = count($Marks);
//        $TotalInputFrom = count($InputFrom);
//        $TotalInputTo = count($InputTo);
//        $TotalMarksDistribution = count($MarksDistribution);
//        $TotalExamSubHead = count($ExamSubHead);

        $Md = $MarksDistribution == 0 ? new MarksDistribution : MarksDistribution::find($MarksDistribution);
        $Md->subject_id = $Subject;
        $Md->class_id = $Class;
        $Md->group_id = $Group;
        $Md->institute_branch_version_id = $Branch;
        $Md->exam_type_id = $ExamType;
        $Md->exam_sub_head_id = $ExamSubHead;
        $Md->exam_head_id = $ExamHead;
        $Md->full_marks = $Marks;
        $Md->pass_marks = $PassMarks;
        $Md->allowed_input_date_time_from = date('Y-m-d', strtotime($InputFrom));
        $Md->allowed_input_date_time_to = date('Y-m-d', strtotime($InputTo));
        $Md->is_active = $IsActive;
        if ($MarksDistribution == 0) {
            $Md->create_time = date('Y-m-d h:i:s');
            $Md->create_logon_id = session('sessLogonId');
            $Md->create_user_id = Auth::user()->user_id;
            $Md->last_action = 'INSERT';
        } else {
            $Md->update_time = date('Y-m-d h:i:s');
            $Md->update_logon_id = session('sessLogonId');
            $Md->update_user_id = Auth::user()->user_id;
            $Md->last_action = 'UPDATE';
        }
        $Md->save();

//        if ($ExamHead == 2) {
//
//            $Update = array();
//            $Update['pass_marks'] = $PassMarks;
//            
//            DB::table('tbl_marks_distributions')
//            ->where('subject_id', $Subject)
//            ->where('class_id', $Class)
//            ->where('group_id', $Group)
//            ->where('institute_branch_version_id', $Branch)
//            ->where('exam_type_id', $ExamType)
//            //->where('exam_sub_head_id', $ExamSubHead)
//            ->where('exam_head_id', $ExamHead)
//            ->update($Update);
//        }
        //echo 'Data updated successfully!';
    }

    public function SaveSBAPassMarks(Request $request) {
        
        $Subject = $request->input('Subject');
        $ExamHead = $request->input('ExamHead');
        $ExamType = $request->input('ExamType');
        $Class = $request->input('Class');
        $Group = $request->input('Group');
        $Branch = $request->input('Branch');
        $PassMarks = $request->input('PassMarks') == '' ? 0 : $request->input('PassMarks');
         if ($ExamHead != 1) {

            $Update = array();
            $Update['pass_marks'] = $PassMarks;
            
            DB::table('tbl_marks_distributions')
            ->where('subject_id', $Subject)
            ->where('class_id', $Class)
            ->where('group_id', $Group)
            ->where('institute_branch_version_id', $Branch)
            ->where('exam_type_id', $ExamType)
            //->where('exam_sub_head_id', $ExamSubHead)
            ->where('exam_head_id', $ExamHead)
            ->update($Update);
        }
    }

}
