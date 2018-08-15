<?php
$ExamSubHeads = DB::select(DB::raw('call GetExamSubHeads("1", "' . $ExamHead . '")'));


if ($Role == 2) {

    $QueryForCheckIsClassTeacher = DB::table('tbl_class_teachers')
            ->select('tbl_class_teachers.class_id', 'tbl_class_teachers.group_id', 'tbl_class_teachers.section_id', 'sectioninfo.SectionName', 'classinfo.ClassName', 'stugrp.GroupName')
            ->join('sectioninfo', 'sectioninfo.section_id', '=', 'tbl_class_teachers.section_id')
            ->join('classinfo', 'classinfo.id', '=', 'tbl_class_teachers.class_id')
            ->join('stugrp', 'stugrp.id', '=', 'tbl_class_teachers.group_id')
            ->where('tbl_class_teachers.user_id', Auth::user()->user_id)
            ->where('tbl_class_teachers.is_active', 1)
            ->get();



    $Sections = $QueryForCheckIsClassTeacher;
//    echo '<pre>';
//    print_r($Sections);
//    echo '</pre>';exit();
//    $Subjects = DB::table('tbl_assigned_subjects_to_teachers as tastt')
//            ->select('tastt.subject_id', 'ts.subject_name')
//            ->join('tbl_subjects as ts', 'ts.subject_id', '=', 'tastt.subject_id')
//            //->where('tastt.user_id', $TeacherID)
//            ->where('tastt.class_id', $ClassID)
//            ->where('tastt.section_id', $Sections[0]->section_id)
//            ->where('tastt.group_id', $GroupID)
//            ->where('tastt.institute_branch_version_id', $IbvID)
//            ->where('tastt.is_active', 1)
//            //->groupBy('tastt.section_id')
//            ->get();

    $Students = DB::table('tbl_students')
            ->select('student_id', 'system_generated_student_id', 'roll_no', 'student_name')
            ->where('class_id', $ClassID)
            ->where('stu_group', $GroupID)
            ->where('section_id', $Sections[0]->section_id)
            ->where('institute_branch_version_id', $IbvID)
            ->orderBy('roll_no')
            ->get();


//    echo '<pre>';
//    print_r($Sections);
//    echo '</pre>';
//    exit();
} else if ($Role == 3) {

    $Sections = DB::table('tbl_assigned_subjects_to_teachers as tastt')
            ->select('tastt.class_id', 'tastt.group_id', 'tastt.section_id', 'sectioninfo.SectionName', 'classinfo.ClassName', 'stugrp.GroupName')
            ->join('sectioninfo', 'sectioninfo.section_id', '=', 'tastt.section_id')
            ->join('classinfo', 'classinfo.id', '=', 'tastt.class_id')
            ->join('stugrp', 'stugrp.id', '=', 'tastt.group_id')
            ->where('tastt.user_id', Auth::user()->user_id)
            ->where('tastt.class_id', $ClassID)
            ->where('tastt.group_id', $GroupID)
            ->where('tastt.is_active', 1)
            ->groupBy('tastt.section_id')
            ->get();

//    echo '<pre>';
//    print_r($Sections);
//    echo '</pre>';
//    exit();
}


foreach ($Sections as $Sec => $ValueSec) {

    if ($Role == 3) {

        $Subjects = DB::table('tbl_assigned_subjects_to_teachers as tastt')
                ->select('tastt.subject_id', 'ts.subject_name')
                ->join('tbl_subjects as ts', 'ts.subject_id', '=', 'tastt.subject_id')
                ->where('tastt.user_id', Auth::user()->user_id)
                ->where('tastt.class_id', $ClassID)
                ->where('tastt.section_id', $ValueSec->section_id)
                ->where('tastt.group_id', $GroupID)
                ->where('tastt.institute_branch_version_id', $IbvID)
                ->where('tastt.is_active', 1)
                //->groupBy('tastt.section_id')
                ->get();
    } else if ($Role == 2) {

        $Subjects = DB::table('tbl_assigned_subjects_to_teachers as tastt')
                ->select('tastt.subject_id', 'ts.subject_name')
                ->join('tbl_subjects as ts', 'ts.subject_id', '=', 'tastt.subject_id')
                //->where('tastt.user_id', Auth::user()->user_id)
                ->where('tastt.class_id', $ClassID)
                ->where('tastt.section_id', $ValueSec->section_id)
                ->where('tastt.group_id', $GroupID)
                ->where('tastt.institute_branch_version_id', $IbvID)
                ->where('tastt.is_active', 1)
                //->groupBy('tastt.section_id')
                ->get();
    }
//    echo '<pre>';
//    print_r($Subjects);
//    echo '</pre>';exit();
    foreach ($Subjects as $Sub => $ValueSub) {

//                echo '<pre>';
//    print_r($Sec);
//    echo '</pre>';
        ?>

        <table width="100%">
            <tr>
                <td>Class: <?php echo $ValueSec->ClassName; ?></td>
                <td>Group: <?php echo $ValueSec->GroupName; ?></td>
                <td>Section: <?php echo $ValueSec->SectionName; ?></td>
                <td>Subject: <?php echo $ValueSub->subject_name; ?></td>
            </tr>
        </table>

        <table width="100%" border="1">
            <thead>
                <tr>
                    <th>Roll No.</th>
                    <th>Student Name</th>
                    <?php
                    if (isset($ExamSubHeads)) {
                        foreach ($ExamSubHeads as $esh) {
                            echo '<th>' . $esh->exam_sub_head_alias . '</th>';
                        }
                    }
                    ?>
                </tr>
            </thead>

            <tbody>

                <?php
                //exit();

                $Students = DB::table('tbl_students')
                        ->select('student_id', 'system_generated_student_id', 'roll_no', 'student_name')
                        ->where('class_id', $ClassID)
                        ->where('stu_group', $GroupID)
                        ->where('section_id', $ValueSec->section_id)
                        ->where('institute_branch_version_id', $IbvID)
                        ->orderBy('roll_no')
                        ->get();
                foreach ($Students as $Stu) {
                    ?>


                    <tr>
                        <td><?php echo $Stu->roll_no; ?></td>
                        <td><?php echo $Stu->student_name; ?></td>

                        <?php
                        foreach ($ExamSubHeads as $key2 => $value2) {

                            $Result = DB::table('tbl_marks_input')
                                    ->select('marks')
                                    ->where('subject_id', $ValueSub->subject_id)
                                    ->where('class_id', $ClassID)
                                    ->where('group_id', $GroupID)
                                    ->where('institute_branch_version_id', $IbvID)
                                    ->where('exam_type_id', $ExamTypeID)
                                    ->where('exam_sub_head_id', $value2->exam_sub_head_id)
                                    ->where('exam_head_id', $ExamHead)
                                    ->where('student_id', $Stu->student_id)
                                    ->first();
//                        echo '<pre>';
//    print_r($Result);
//    echo '</pre>';
                            echo '<td>' . (isset($Result->marks) ? ($Result->marks < 0 ? 'A' : $Result->marks) : '') . '</td>';
                        }
                        ?>
                    </tr>

                    <?php
                }
                ?>

            </tbody>
        </table>
        <?php
    }
}

