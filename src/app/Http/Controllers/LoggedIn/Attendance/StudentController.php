<?php

namespace App\Http\Controllers\LoggedIn\Attendance;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Attendance;
use DB;
use Validator;

class StudentController extends Controller {

    private $ViewPagePath = 'LoggedIn.Attendance.Student.';
    protected $LoggedInUserInstituteBranchVersionIDPrivileges = '';

    public function __construct() {

        try {

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

    public function Index() {

        $Data = array();
        $Data['PageTitle'] = 'Student Attendance';

        $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
        $Data['InstituteBranchVersions'] = $InstituteBranchVersions;

        $Classes = DB::select('call get_student_classes("1")');
        $Data['Classes'] = $Classes;

        $Groups = DB::select('call get_student_groups("1")');
        $Data['Groups'] = $Groups;

        return view($this->ViewPagePath . 'Index', $Data);
    }

    public function GetSectionsByClassIDAndGroupIDAndIbvID($IbvID, $ClassID) {
        
        

        $Sections = DB::table('tbl_sec_sections as tss')
                ->join('sectioninfo as s', 's.section_id', '=', 'tss.section_id')
                ->select('s.section_id', 's.SectionName')
                ->where('tss.class_id', '=', $ClassID)
                ->where('tss.institute_branch_version_id', '=', $IbvID)
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

    public function GetStudentsForAttendanceByClassIDAndGroupIDAndIbvIDAndSectionID($IbvID, $ClassID, $GroupID, $SectionID, $attendanceDateFrom, $attendanceDateTo) {

        $AttendanceDateFrom = date('Y-m-d', strtotime($attendanceDateFrom)) . ' 00:00:00';
        $AttendanceDateTo = date('Y-m-d', strtotime($attendanceDateTo)) . ' 23:59:59';
        $AttendanceRecords = DB::select('call StudentAttendaceRecordsByDateFromAndDateTo("' . $AttendanceDateFrom . '","' . $AttendanceDateTo . '", "' . $IbvID . '","' . $ClassID . '", "' . $SectionID . '","' . $GroupID . '")');
        //echo '<pre>';print_r($AttendanceRecords);echo '</pre>'; exit();
        if (!empty($AttendanceRecords)) {
            $Data['AttendanceRecords'] = $AttendanceRecords;
            //echo '<pre>';print_r($AttendanceRecords);echo '</pre>'; exit();
        } else {

            $Students = DB::select('call students_view("' . $IbvID . '","' . $ClassID . '", "' . $SectionID . '","' . $GroupID . '")');
            $Data['Students'] = $Students;
            //echo '<pre>';print_r($Students);echo '</pre>'; exit();
        }
        $Teacher = DB::table('tbl_class_teachers')
                    ->select('tbl_users.full_name', 'tbl_users.user_id', 'tbl_users.school_provided_teacher_id')
                    ->join('tbl_users', 'tbl_users.user_id', '=', 'tbl_class_teachers.user_id')
                    ->where('tbl_class_teachers.class_id', $ClassID)
                    ->where('tbl_class_teachers.group_id', $GroupID)
                    ->where('tbl_class_teachers.section_id', $SectionID)
                    ->where('tbl_class_teachers.institute_branch_version_id', $IbvID)
                    ->where('tbl_class_teachers.user_id', Auth::user()->user_id)
                    ->first();
        $Data['Teacher'] = $Teacher;
        $Data['Branch'] = $IbvID;
        $Data['Class'] = $ClassID;
        $Data['Group'] = $GroupID;
        $Data['Section'] = $SectionID;

        return view($this->ViewPagePath . 'GetStudentsForAttendanceByClassIDAndGroupIDAndIbvIDAndSectionID', $Data);
    }

    public function SaveAttendance(Request $request) {
        //echo '<pre>';print_r($request->all());echo '</pre>'; exit();
        $validator = Validator::make($request->all(), [
                    'Class' => 'required',
                    'Group' => 'required',
                    'Branch' => 'required',
                    'Section' => 'required',
                    'AttendedStudentID' => 'required|min:1',
                    'StudentID' => 'required|min:1',
                    'StudentName' => 'required|min:1',
                    'PhoneNo' => 'required|min:1',
                    'Teacher' => 'required',
        ]);

        $Branch = $request->input('Branch');
        $Class = $request->input('Class');
        $Section = $request->input('Section');
        $Group = $request->input('Group');

        if ($validator->fails()) {
            return redirect(route($this->ViewPagePath . 'GetStudentsForAttendanceByClassIDAndGroupIDAndIbvIDAndSectionID', array(
                                'IbvID' => $Branch,
                                'ClassID' => $Class,
                                'GroupID' => $Group,
                                'Section' => $Section,
'AttendanceDateFrom' => $request->input('AttendanceDateFrom') ? $request->input('AttendanceDateFrom') : date('Y-m-d'),
                'AttendanceDateTo' => $request->input('AttendanceDateTo') ? $request->input('AttendanceDateTo') : date('Y-m-d'),
                            )))
                            ->withErrors($validator)
                            ->withInput();
        }

        $AttendedStudentId = $request->input('AttendedStudentID');
        $StudentID = $request->input('StudentID');
        $StudentName = $request->input('StudentName');
        $Teacher = $request->input('Teacher');
        $PhoneNo = $request->input('PhoneNo');

        $TotalStudentID = count($StudentID);
        $TotalStudentName = count($StudentName);
//echo $TotalStudentID;exit();
        if ($TotalStudentID == $TotalStudentName) {

            $Status = '';
            $AbsentPhoneNo = array();
            $AbsentStudentName = array();
            for ($i = 0; $i < $TotalStudentID; $i++) {

                if (in_array($StudentID[$i], $AttendedStudentId)) {
                    $Status = 'P';
                } else {
                    $Status = 'A';
                    $AbsentPhoneNo[] = $PhoneNo[$i];
                    $AbsentStudentName[] = $StudentName[$i];
                }
//                echo $Status . "\n";

                $Attendance = new Attendance;
                $Attendance->student_id = $StudentID[$i];
                $Attendance->class_id = $Class;
                $Attendance->section_id = $Section;
                $Attendance->group_id = $Group;
                $Attendance->user_id = $Teacher;
                $Attendance->status = $Status;
                $Attendance->institute_branch_version_id = $Branch;
                $Attendance->is_active = 1;
                $Attendance->create_time = date('Y-m-d H:i:s');
                $Attendance->create_user_id = Auth::user()->user_id;
                $Attendance->create_logon_id = session('sessLogonId');
                $Attendance->last_action = 'INSERT';
                $Attendance->save();
                
//                if($Status == 'A'){
//                    DB::select('call InsertFineForAttendanceDaily("' . 17 . '", "Fine", "50", "' . $StudentID[$i] . '", "' . $Class . '", "' . $Group .'", "' . (int)date('m') . '", "' . date('Y-m-d H:i:s') . '", "' . Auth::user()->user_id . '", "' . session('sessLogonId') . '", "' . $Section . '", "' . $Branch . '", "1")');
//                }
            }

            $TotalAbsentPhoneNo = count($AbsentPhoneNo);
            $TotalAbsentStudentName = count($AbsentStudentName);
            if ($TotalAbsentPhoneNo > 0 && $TotalAbsentStudentName > 0 && $TotalAbsentPhoneNo == $TotalAbsentStudentName) {

                $method = 'SendTextMessage?';
                for ($j = 0; $j < $TotalAbsentPhoneNo; $j++) {
                    

                    $smsbody = "Dear Parent, Pls be informed " . $AbsentStudentName[$j] . " was ABSENT on " . date("d M, Y") . ". For enq contact Class-Teacher. MPSC";

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, "https://bmpws.robi.com.bd/ApacheGearWS/" . $method);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, 'Username=mpsc&Password=Mpsc@123&From=8801847169935&To=' . $AbsentPhoneNo[$j] . '&Message=' . $smsbody);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $server_output = curl_exec($ch);
                    //print_r(curl_getinfo($ch));

                    curl_close($ch);
//echo $smsbody . '<br>';
                }
            }
        }
        echo 'Student attendance saved successfully!';
    }

}
