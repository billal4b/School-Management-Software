<?php
//echo '<pre>';
//        print_r($CutUnderScores);
//        echo '</pre>';
//        exit();
$ExamSubHeads = DB::select(DB::raw('call GetExamSubHeads("1", "0")'));
for ($i = 0; $i < count($CutUnderScores); $i++) {
    $ClassName = DB::table('classinfo')->select('ClassName')->where('id', $CutUnderScores[$i][1])->first();
    $GroupName = DB::table('stugrp')->select('GroupName')->where('id', $CutUnderScores[$i][2])->first();
    $SectionName = DB::table('sectioninfo')->select('SectionName')->where('section_id', $CutUnderScores[$i][5])->first();
    ?>
Class :<?php echo $ClassName->ClassName; ?>&nbsp;&nbsp;Group :<?php echo $GroupName->GroupName; ?>&nbsp;&nbsp;Section :<?php echo $SectionName->SectionName; ?>
<table width="100%" border="1" style="font-size: 10px;">
        <tr>
            <th>Roll No.</th>
            <th>Student ID</th>
            <th>Name</th>
            <?php
            foreach ($ExamSubHeads as $esh) {
                echo '<th>' . $esh->exam_sub_head_alias . '</th>';
            }
            ?>
        </tr>

        <tbody>


            <?php
            $Students = DB::table('tbl_students as ts')
                    ->select('ts.student_id', 'ts.system_generated_student_id', 'ts.roll_no', 'ts.student_name', 'ci.ClassName', 'sg.GroupName', 'si.SectionName')
                    ->join('classinfo as ci', 'ci.id', '=', 'ts.class_id')
                    ->join('stugrp as sg', 'sg.id', '=', 'ts.stu_group')
                    ->join('sectioninfo as si', 'si.section_id', '=', 'ts.section_id')
                    ->where('ts.class_id', $CutUnderScores[$i][1])
                    ->where('ts.stu_group', $CutUnderScores[$i][2])
                    ->where('ts.section_id', $CutUnderScores[$i][5])
                    ->where('ts.institute_branch_version_id', $CutUnderScores[$i][3])
                    ->orderBy('ts.roll_no')
                    ->get();

            foreach ($Students as $stu) {
                ?>
            <tr>
                <td><?php echo $stu->roll_no; ?></td>
                <td><?php echo $stu->system_generated_student_id; ?></td>
                <td><?php echo $stu->student_name; ?></td>
           
            <?php

                foreach ($ExamSubHeads as $esh) {

                    $IsExist = DB::table('tbl_marks_input')
                            ->select('marks')
                            ->where('subject_id', $CutUnderScores[$i][4])
                            ->where('class_id', $CutUnderScores[$i][1])
                            ->where('group_id', $CutUnderScores[$i][2])
                            ->where('institute_branch_version_id', $CutUnderScores[$i][3])
                            ->where('exam_type_id', $CutUnderScores[$i][0])
                            ->where('exam_sub_head_id', $esh->exam_sub_head_id)
                            ->where('exam_head_id', $esh->exam_head_id)
                            ->where('student_id', $stu->student_id)
                            //->where('section_id', $CutUnderScores[$i][5])
                            ->first();

                    if (!empty($IsExist)) {
                        echo '<td>' . ($IsExist->marks < 0 ? 'A' : $IsExist->marks) . '</td>';
                    }else{
                        echo '<td>-</td>';
                    }
//            var_dump();
                }
                ?>
                 </tr>
                <?php
            }
            ?>
        </tbody>

    </table>
<br><br><br>
    <?php
}
