<?php

namespace App\Http\Controllers\LoggedIn\Settings;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Validator;
use App\Subject;

class SubjectController extends Controller {

    private $ViewPagePath = 'LoggedIn.Settings.Subject.';
    private $MenuID = 30;
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
        } catch (\Exception $ex) {
            $ex->getMessage();
        }
    }

    public function index(Request $request) {

        $Data = array();
        $Data['PageTitle'] = 'Subject';

        $ChildMenus = DB::select('call get_child_menu_list("' . $this->MenuID . '")');
        //echo '<pre>';print_r($ChildMenus);echo '</pre>';exit();
        if (!empty($ChildMenus)) {

            $Data['ChildMenus'] = $ChildMenus;
            //return view('LoggedIn.Settings.Index', $Data);
        } else {


            //echo $LoggedInUserInstituteBranchVersionIDPrivileges;exit();
            /*$page = $request->get('page', 1);
            $paginate = 10;*/

            $Subjects = DB::select(DB::raw('call GetSubjects("2", "' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));

//            $offSet = ($page * $paginate) - $paginate;
//            $itemsForCurrentPage = array_slice($Subjects, $offSet, $paginate, true);
//            $Data['Subjects'] = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($Subjects), $paginate, $page);
            $Data['Subjects'] = $Subjects;
//            $Data['Subjects']->setPath(route($this->ViewPagePath . 'Index'));
//            echo '<pre>';
//            print_r($Data['Subjects']);
//            echo '</pre>';
//            exit();
//            
//            $Data['Teachers']->setPath(route($this->ViewPagePath . 'Index'));
        }
        
        $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
        $Classes = DB::select('call get_student_classes("2")');
        $Groups = DB::select('call get_student_groups("2")');


        $Data['Classes'] = $Classes;
        $Data['Groups'] = $Groups;
        $Data['InstituteBranchVersions'] = $InstituteBranchVersions;
        
        return view($this->ViewPagePath . 'Index', $Data);
    }

    public function create() {

        $Data = array();
        $Data['PageTitle'] = 'New Subject';

        $Classes = DB::select('call get_student_classes("2")');
        $Groups = DB::select('call get_student_groups("2")');
        $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));


        $Data['Classes'] = $Classes;
        $Data['Groups'] = $Groups;
        $Data['InstituteBranchVersions'] = $InstituteBranchVersions;

        return view($this->ViewPagePath . 'Create', $Data);
    }

    public function store(Request $request) {

        //echo '<pre>';print_r($request->all());echo '</pre>';exit();

        $validator = Validator::make($request->all(), [
                    'SubjectName' => 'required|max:255',
                    'Class' => 'required|min:1',
                    'Group' => 'required|min:1',
                    'Branch' => 'required|min:1',
                    /*'AttendedStudentID' => 'required|min:1',
                    'StudentName' => 'required|min:1',
                    'StudentID' => 'required|min:1',*/
        ]);

        if ($validator->fails()) {
            return redirect(route($this->ViewPagePath . 'Create'))
                            ->withErrors($validator)
                            ->withInput();
        }

        $SubjectName = $request->input('SubjectName');
        $SubjectCode = $request->input('SubjectCode');
        $Class = $request->input('Class');
        $Group = $request->input('Group');
        $Branch = $request->input('Branch');

        $MessageType = 'SuccessMessage';
        $Message = 'Subject inserted successfully.';
        try {

            $TotalClasses = count($Class);
            $TotalGroups = count($Group);
            $TotalBranches = count($Branch);
            for ($i = 0; $i < $TotalClasses; $i++) {
                for ($j = 0; $j < $TotalGroups; $j++) {
                    for ($k = 0; $k < $TotalBranches; $k++) {
                        $Subject = new Subject;
                        $Subject->subject_name = $SubjectName;
                        $Subject->subject_code = $SubjectCode;
                        $Subject->class_id = $Class[$i];
                        $Subject->group_id = $Group[$j];
                        $Subject->institute_branch_version_id = $Branch[$k];
                        $Subject->is_active = 1;
                        $Subject->save();
                    }
                }
            }
        } catch (\Exception $ex) {

            $MessageType = 'ErrorMessage';
            $Message = $ex->getMessage();
        }

        return redirect()->route($this->ViewPagePath . 'Create')
                        ->with($MessageType, $Message);
    }

    public function edit($id) {

        $Subject = Subject::findOrFail($id);

        $Data = array();
        $Data['PageTitle'] = 'Edit Subject';

        $Classes = DB::select('call get_student_classes("2")');
        $Groups = DB::select('call get_student_groups("2")');
        $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
        $Data['Classes'] = $Classes;
        $Data['Groups'] = $Groups;
        $Data['InstituteBranchVersions'] = $InstituteBranchVersions;
        $Data['Subject'] = $Subject;

        return view($this->ViewPagePath . 'Edit', $Data);
    }

    public function update(Request $request, $id) {
        //echo $id;exit();

        $validator = Validator::make($request->all(), [
                    'SubjectName' => 'required|max:255',
                    'Class' => 'required|min:1',
                    'Group' => 'required|min:1',
                    'Branch' => 'required|min:1',
                    'Status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->ViewPagePath . 'Edit'))
                            ->withErrors($validator)
                            ->withInput();
        }

        $SubjectName = $request->input('SubjectName');
        $SubjectCode = $request->input('SubjectCode');
        $Class = $request->input('Class');
        $Group = $request->input('Group');
        $Branch = $request->input('Branch');
        $Status = $request->input('Status');

        $MessageType = 'SuccessMessage';
        $Message = 'Subject updated successfully.';
        try {

            $Subject = Subject::find($id);
//            echo '<pre>';print_r($Teacher);echo '</pre>';exit();
            $Subject->subject_name = $SubjectName;
            $Subject->subject_code = $SubjectCode;
            $Subject->class_id = $Class;
            $Subject->group_id = $Group;
            $Subject->institute_branch_version_id = $Branch;
            $Subject->is_active = $Status;
            $Subject->save();
        } catch (\Exception $ex) {

            $MessageType = 'ErrorMessage';
            $Message = $ex->getMessage();
        }

        return redirect()->route($this->ViewPagePath . 'Edit', array('id' => $id))
                        ->with($MessageType, $Message);
    }
    
    public function ShowSubjectsByBranchIDAndClassIDAndGroupID($IbvID, $ClassID, $GroupID){
        
        $Subjects = DB::select(DB::raw('select ts.*,
		c.id,
		c.ClassName,
		sg.id,
		sg.GroupName,
		tsb.school_branch_name,
		tsv.school_version_name
	from tbl_subjects ts
	inner join classinfo c
		on c.id = ts.class_id
	inner join stugrp sg
		on sg.id = ts.group_id
	inner join tbl_sec_institute_branch_version tsibv
		on tsibv.institute_branch_version_id = ts.institute_branch_version_id
	inner join tbl_school_branches tsb
		on tsibv.school_branch_id = tsb.school_branch_id
	inner join tbl_school_versions tsv
		on tsv.school_version_id = tsibv.school_version_id where ts.institute_branch_version_id = ' . $IbvID . ' and ts.class_id = ' . $ClassID . ' and ts.group_id = ' . $GroupID));
        
        $Data = array();
        $Data['PageTitle'] = 'Subject';
        $Data['Subjects'] = $Subjects;
        
        $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
        $Classes = DB::select('call get_student_classes("2")');
        $Groups = DB::select('call get_student_groups("2")');


        $Data['Classes'] = $Classes;
        $Data['Groups'] = $Groups;
        $Data['InstituteBranchVersions'] = $InstituteBranchVersions;
        
        return view($this->ViewPagePath . 'Index', $Data);
    }

}
