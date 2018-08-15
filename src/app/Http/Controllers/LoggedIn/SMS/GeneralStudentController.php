<?php

namespace App\Http\Controllers\LoggedIn\SMS;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Validator;

class GeneralStudentController extends Controller {

    private $ViewPagePath = 'LoggedIn.SMS.GeneralStudent.';
    protected $LoggedInUserInstituteBranchVersionIDPrivileges = '';

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

    public function Index() {

        $Data = array();
        $Data['PageTitle'] = 'Student SMS';

        $InstituteBranchVersions = DB::select(DB::raw('call GetBranchesAndVersionsByInstituteBranchVersionID("' . $this->LoggedInUserInstituteBranchVersionIDPrivileges . '")'));
        $Data['InstituteBranchVersions'] = $InstituteBranchVersions;

        return view($this->ViewPagePath . 'Index', $Data);
    }

//    public function GetSectionsByClassIDAndGroupIDAndIbvID($IbvID, $ClassID) {
//
//        $Sections = DB::table('tbl_sec_sections as tss')
//                ->join('sectioninfo as s', 's.section_id', '=', 'tss.section_id')
//                ->select('s.section_id', 's.SectionName')
//                ->where('tss.class_id', '=', $ClassID)
//                ->where('tss.institute_branch_version_id', '=', $IbvID)
//                ->get();
//        echo '<select name="Section" class="form-control" id="Section">';
//        echo '<option value="">----- Select ------</option>';
//        if (!empty($Sections)) {
//            foreach ($Sections as $s) {
//                echo '<option value="' . $s->section_id . '">' . $s->SectionName . '</option>';
//            }
//        }
//        echo '</select>';
//    }
//
//    public function GetStudentsForSMSByClassIDAndGroupIDAndIbvIDAndSectionID($IbvID, $ClassID, $GroupID, $SectionID) {
//
//        $Students = DB::select('call students_view("' . $IbvID . '","' . $ClassID . '", "' . $SectionID . '","' . $GroupID . '")');
//        $Data['Students'] = $Students;
//        $Data['Branch'] = $IbvID;
//        $Data['Class'] = $ClassID;
//        $Data['Group'] = $GroupID;
//        $Data['Section'] = $SectionID;
//        //echo '<pre>';print_r($Students);echo '</pre>'; exit();
//        return view($this->ViewPagePath . 'GetStudentsForSMSByClassIDAndGroupIDAndIbvIDAndSectionID', $Data);
//    }

    public function SendSMS(Request $request) {
        //echo '<pre>';print_r($request->all());echo '</pre>'; exit();
        $validator = Validator::make($request->all(), [
//                    'Class' => 'required',
//                    'Group' => 'required',
                    'Branch' => 'required|min:1',
//                    'Section' => 'required',
//                    'AttendedStudentID' => 'required|min:1',
//                    'StudentID' => 'required|min:1',
//                    'StudentName' => 'required|min:1',
//                    'PhoneNo' => 'required|min:1',
                    'SmsBody' => 'required',
        ]);

        $Branch = $request->input('Branch');
        $SMSBody = $request->input('SmsBody');
//        $Class = $request->input('Class');
//        $Section = $request->input('Section');
//        $Group = $request->input('Group');
//        if ($validator->fails()) {
//            return redirect(route($this->ViewPagePath . 'Index'))
//                            ->withErrors($validator)
//                            ->withInput();
//        }
//        $TotalBranch = count($Branch);
//        $Branches = '';
//        for($i = 0; $i < $TotalBranch; $i++){
//            $Branches .= $Branch[$i] . ',';
//        }
//        $Branches = rtrim(trim($Branches), ',');

        $ContactNo = DB::table('tbl_students')
                ->select('contact_for_sms')
                //->where('is_active', 1)
                ->whereIn('institute_branch_version_id', $Branch)
                ->where('section_id', '<>', 55)
                ->get();
//        echo '<pre>';
//        print_r($ContactNo);
//        echo '</pre>';
//        exit();
        $TotalContactNo = count($ContactNo);
        for ($i = 0; $i < $TotalContactNo; $i++) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://bmpws.robi.com.bd/ApacheGearWS/SendTextMessage?");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'Username=mpsc&Password=Mpsc@123&From=8801847169935&To=' . $ContactNo[$i]->contact_for_sms . '&Message=' . $SMSBody);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);
            //print_r(curl_getinfo($ch));

            curl_close($ch);
        }
        echo 'SMS send successfully!';
        //echo $Branches;exit();
        /*
          $AttendedStudentId = $request->input('AttendedStudentID');
          $StudentID = $request->input('StudentID');
          $StudentName = $request->input('StudentName');
          $PhoneNo = $request->input('PhoneNo');
          $SMSBody = $request->input('SmsBody');

          $TotalStudentID = count($StudentID);
          $TotalStudentName = count($StudentName);
          if ($TotalStudentID == $TotalStudentName) {

          $AbsentPhoneNo = array();
          $AbsentStudentName = array();
          for ($i = 0; $i < $TotalStudentID; $i++) {

          if (in_array($StudentID[$i], $AttendedStudentId)) {

          $AbsentPhoneNo[] = $PhoneNo[$i];
          $AbsentStudentName[] = $StudentName[$i];
          } else {
          //                    $Status = 'A';
          }
          //                echo $Status . "\n";

          //                $Attendance = new Attendance;
          //                $Attendance->student_id = $StudentID[$i];
          //                $Attendance->class_id = $Class;
          //                $Attendance->section_id = $Section;
          //                $Attendance->group_id = $Group;
          //                $Attendance->user_id = $Teacher;
          //                $Attendance->status = $Status;
          //                $Attendance->is_active = 1;
          //                $Attendance->create_time = date('Y-m-d H:i:s');
          //                $Attendance->create_user_id = Auth::user()->user_id;
          //                $Attendance->create_logon_id = session('sessLogonId');
          //                $Attendance->last_action = 'INSERT';
          //                $Attendance->save();
          }

          $TotalAbsentPhoneNo = count($AbsentPhoneNo);
          $TotalAbsentStudentName = count($AbsentStudentName);
          if ($TotalAbsentPhoneNo > 0 && $TotalAbsentStudentName > 0 && $TotalAbsentPhoneNo == $TotalAbsentStudentName) {

          $method = 'SendTextMessage?';
          for ($j = 0; $j < $TotalAbsentPhoneNo; $j++) {

          $smsbody = $SMSBody;

          $ch = curl_init();

          curl_setopt($ch, CURLOPT_URL, "https://bmpws.robi.com.bd/ApacheGearWS/" . $method);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, 'Username=Syful&Password=Gulshan@123&From=8801847169935&To=' . $AbsentPhoneNo[$j] . '&Message=' . $smsbody);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

          $server_output = curl_exec($ch);
          //print_r(curl_getinfo($ch));

          curl_close($ch);
          }

          return redirect(route($this->ViewPagePath . 'GetStudentsForSMSByClassIDAndGroupIDAndIbvIDAndSectionID', array(
          'IbvID' => $Branch,
          'ClassID' => $Class,
          'GroupID' => $Group,
          'Section' => $Section,
          )))
          ->with('SuccessMessage', 'SMS send successfully.');
          }
          }
         * 
         */
    }

}
