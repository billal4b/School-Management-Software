<?php

namespace App\Http\Controllers\LoggedIn\Settings;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Student;
use App\User;
use DB;
use PDF;
use Illuminate\Pagination\LengthAwarePaginator;
use Validator;

class StudentController extends Controller {

    private $ViewPagePath = 'LoggedIn.Settings.Student.';
    //private $MenuID = 28;
    protected $LoggedInUserInstituteBranchVersionIDPrivileges = '';

    // private $RoleID = 5;

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

    public function index() {

        $Data = array();
        $Data['PageTitle'] = 'Students';


        //------------------//
        $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' .
                                $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
        $Data['InstituteBranchVersions'] = $InstituteBranchVersions;
        // echo '<pre>';print_r($InstituteBranchVersions);echo '</pre>';exit();
        //$this->data['secInstitutes'] = DB::select('call get_sec_institute_branch_version("' . session('instituteDetails')->institute_id . '")');
        $classWise = DB::select(DB::raw('call get_student_classes(1)'));
        $Data['classWise'] = $classWise;
        $groupWise = DB::select(DB::raw('call get_student_groups(1)'));
        $Data['groupWise'] = $groupWise;


        /*  echo '<pre>';
          print_r($studentList);
          echo '</pre>'; exit(); */

        return view($this->ViewPagePath . 'index', $Data);
    }

    /*     * ******************view method********************** */

    public function view(Request $request) {

//        echo '<pre>';
//          print_r($request->all());
//        var_dump($request->all());
        //echo empty($request->branch_and_version);
//          echo '</pre>'; exit();

        if ($request->ajax()) {
            try {
                //$this->data['pageTitle'] = 'View Students';
                $Data = array();
                //$Data['PageTitle'] = 'Students';
                

                $branch_and_version = empty($request->branch_and_version) ? 0 : $request->branch_and_version;
                $class_name = empty($request->class_name) ? 0 : $request->class_name;
                $section_name = empty($request->section_name) ? 0 : $request->section_name;
                $group_name = empty($request->group_name) ? 0 : $request->group_name;
                $system_generated_student_id = empty($request->system_generated_student_id) ? '' : $request->system_generated_student_id;
                $student_name = empty($request->student_name) ? '' : $request->student_name;

                $studentsView = DB::select(DB::raw('call SearchStudents("' . $branch_and_version . '","' . $class_name . '",
				"' . $section_name . '","' . $group_name . '", "' . $system_generated_student_id . '", "' . $student_name . '")'));
                $Data['studentsView'] = $studentsView;
//                 echo '<pre>';
//                  print_r($Data['studentsView']);
//                  echo '</pre>'; exit();
            } catch (\Exception $ex) {
                echo $ex->getMessage();
            }
            return view($this->ViewPagePath . 'view', $Data);
            //return view($this->data['ViewPagePath'] . 'view', array('data' => $this->data));
        } else {
            throw new Exception('Invalid request!');
        }
    }

    /*     * ****************** getBranchSection method ********************** */

    public function getBranchSection($BranchVersionID, $ClassID) {

        $vbid = explode('_', $BranchVersionID);
        $Section = DB::table('tbl_sec_sections')
                ->join('sectioninfo', 'sectioninfo.section_id', '=', 'tbl_sec_sections.section_id')
                ->select('tbl_sec_sections.*', 'sectioninfo.SectionName')
                ->where('institute_branch_version_id', '=', $vbid[0])
                ->where('class_id', '=', $ClassID)
                ->get();

        echo '<option value="">----- Select -----</option>';
        foreach ($Section as $sid) {
            echo '<option value="' . $sid->section_id . '">' . $sid->SectionName . '</option>';
        }
    }

    /*     * **************** Print Method *********************** */

    public function print_student(Request $request) {

        if (!$request->ajax()) {

            try {
                $Data = array();
                $Data['pageTitle'] = 'Print Students';

                if (empty($request->branchVersion)) {
                    $branch_and_version = 0;
                } else {
                    $branch_and_version = $request->branchVersion;
                }

                if (empty($request->class)) {
                    $class_name = 0;
                } else {
                    $class_name = $request->class;
                }
                if (empty($request->section)) {
                    $section_name = 0;
                } else {
                    $section_name = $request->section;
                }
                if (empty($request->group)) {
                    $group_name = 0;
                } else {
                    $group_name = $request->group;
                }

                $studentsViewPrint = DB::select(DB::raw('call students_view("' . $branch_and_version . '","' . $class_name . '",
				"' . $section_name . '","' . $group_name . '")'));

                /* echo '<pre>';
                  print_r($studentsViewPrint);
                  echo '</pre>'; exit(); */
            } catch (\Exception $ex) {
                echo $ex->getMessage();
            }
            $Data['studentsViewPrint'] = $studentsViewPrint;
            $pdf = PDF::loadView($this->ViewPagePath . 'print', $Data);
            //return $pdf->setPaper('a4', 'portrait')->download('Students.pdf');
            return $pdf->setPaper('a4', 'portrait')->stream();

            //$pdf = PDF::loadView($this->data['viewPath'] . 'print', array('data' => $this->data));
            //return $pdf->download('print_Students.pdf');
        } else {

            throw new Exception('Invalid request!');
        }
    }

    //------------- edit student --------//

    public function edit($id, Request $request) {


        if (!$request->ajax()) {

            $Data = array();
            $Data['PageTitle'] = 'Update Student';

            $stdDetails = Student::findOrFail($id);
            $stdDetails = Student::where('student_id', '=', $id)->first();
            $Data['stdDetails'] = $stdDetails;

            $studentClasses = DB::select('call get_student_classes(1)');
            $Data['studentClasses'] = $studentClasses;
            $studentSection = DB::select('call get_student_sections(1)');
            $Data['studentSection'] = $studentSection;
            $studentGroups = DB::select('call get_student_groups(1)');
            $Data['studentGroups'] = $studentGroups;
            $years = DB::select('call get_years(1)');
            $Data['years'] = $years;
            $genders = DB::select('call get_genders(1)');
            $Data['genders'] = $genders;
            try {
                $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' .
                                        $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
                $Data['InstituteBranchVersions'] = $InstituteBranchVersions;
                return view($this->ViewPagePath . 'edit_student', $Data);
                //return view($this->data['viewPath'] . 'edit', array('data' => $this->data));
            } catch (\Exception $ex) {
                echo $ex->getMessage();
            }
        } else {
            throw new Exception('Invalid request!');
        }
    }

    public function update(Request $request, $id) {

        $studentDetails = Student::findOrFail($id);

        $validator = Validator::make($request->all(), array(
                    'student_name' => 'required|max:255',
                    'year_of_admission' => 'required|max:4',
                    'class_id' => 'required',
                    'group_name' => 'required',
                    'section_name' => 'required',
                    'system_generated_student_id' => 'required',
                    'gender' => 'required',
                    'branch_and_version' => 'min:1|required',
                    'contact_for_sms' => 'required|digits:9',
                    //'mother_mobile'      => 'required|digits:9',
                    'status' => 'required',
        ));
        if ($validator->fails()) {
            return redirect()->route('LoggedIn.Settings.studentsView.edit', array('id' => $id))
                            ->withErrors($validator)
                            ->withInput();
        }
//print_r($studentDetails);exit();
        $student = Student::find($id);
        $student->student_name = $request->student_name;
        $student->year_of_admission = $request->year_of_admission;
        $student->class_id = $request->class_id;

        $student->section_id = $request->section_name;
        $student->roll_no = $request->roll_no;
        $student->system_generated_student_id = $request->system_generated_student_id;

        $student->gender = $request->gender;
        $student->stu_group = $request->group_name;
        $student->institute_branch_version_id = $request->branch_and_version;
        $student->contact_for_sms = "8801" . $request->contact_for_sms;
        $student->status = $request->status;
        $student->update_time = date('Y-m-d h:i:s');
        $student->update_logon_id = session('sessLogonId');
        $student->update_user_id = Auth::user()->user_id;
        $student->last_action = 'UPDATE';
        $student->save();

        $userArr = array(
            'full_name' => $request->student_name,
            'phone_no' => "8801" . $request->contact_for_sms,
            'is_active' => 1,
            'institute_branch_version_id' => $request->branch_and_version,
        );
        $user = User::where('username', '=', "s" . $request->system_generated_student_id)
                ->update($userArr);

        return redirect()->route('LoggedIn.Settings.studentsView.index')
                        ->with('SuccessMessage', 'Data updated successfully.');
    }

public function PrintBlankMarksSheet(Request $request) {

        if (!$request->ajax()) {

            try {
                $Data = array();
                $Data['pageTitle'] = 'Print Blank Marks Sheet';

                if (empty($request->branchVersion)) {
                    $branch_and_version = 0;
                } else {
                    $branch_and_version = $request->branchVersion;
                }

                if (empty($request->class)) {
                    $class_name = 0;
                } else {
                    $class_name = $request->class;
                }
                if (empty($request->section)) {
                    $section_name = 0;
                } else {
                    $section_name = $request->section;
                }
                if (empty($request->group)) {
                    $group_name = 0;
                } else {
                    $group_name = $request->group;
                }

                $studentsViewPrint = DB::select(DB::raw('call students_view("' . $branch_and_version . '","' . $class_name . '",
				"' . $section_name . '","' . $group_name . '")'));

                /* echo '<pre>';
                  print_r($studentsViewPrint);
                  echo '</pre>'; exit(); */
            } catch (\Exception $ex) {
                echo $ex->getMessage();
            }
            
            $ExamSubHeads = DB::select(DB::raw('call GetExamSubHeads("1", "0")'));
            
            $Data['Students'] = $studentsViewPrint;
            $Data['ExamSubHeads'] = $ExamSubHeads;
            
            
            $pdf = PDF::loadView($this->ViewPagePath . 'PrintBlankMarksSheet', $Data);
            //return $pdf->setPaper('a4', 'portrait')->download('Students.pdf');
            return $pdf->setPaper('a4', 'portrait')->stream();

            //$pdf = PDF::loadView($this->data['viewPath'] . 'print', array('data' => $this->data));
//            return $pdf->download('Print.pdf');
        } else {

            throw new Exception('Invalid request!');
        }
    }

}
