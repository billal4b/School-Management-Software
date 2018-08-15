<?php
$RolesID = array(4, 11, 12, 13, 14, 15, 16, 17, 18, 19);
$TotalStudents = isset($Students) ? count($Students) : 0;
$TotalExamSubHeads = isset($ExamSubHeads) ? count($ExamSubHeads) : 0;
//echo '<pre>';
//        print_r($ActiveExamSubHeads);
//        echo '</pre>';
//        exit();
?>

<!--<div class="table-responsive">-->
<table class="table table-condensed table-striped table-bordered" style="font-size: 10px;" id="ShowMarksInputTbl">
    <thead>
        <tr class="success">
            <!--<th>Sl.</th>-->
            <!--<th>StudentID</th>-->
            <th>Roll No.</th>
            <th>Student Name</th>

            <?php
            if (isset($ExamSubHeads)) {
                foreach ($ExamSubHeads as $esh) {
                    $SearchKey = array_search($IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $esh->exam_sub_head_id . '-' . $SubjectID, array_column($ActiveExamSubHeads, 'ActiveExamSubHeadID'));
                    if ($SearchKey) {
                        echo '<th>' . $esh->exam_sub_head_alias . '</th>';
                    }
                }
            }
            ?>

                <!--<th>Total Marks</th>-->
        </tr>
    </thead>

    <tbody>



        <?php
        if (isset($Students)) {
            foreach ($Students as $key => $value) {
                ?>

                <tr>
                    <!--<td><?php //echo $key + 1;           ?></td>-->
                    <!--<td><?php //echo $value->system_generated_student_id;           ?></td>-->
                    <td><?php echo $value->roll_no; ?></td>
                    <td><?php echo $value->student_name; ?></td>

                    <?php
                    if (isset($ExamSubHeads)) {
                        foreach ($ExamSubHeads as $key2 => $value2) {

                            $SearchKey2 = array_search($IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $value2->exam_sub_head_id . '-' . $SubjectID, array_column($ActiveExamSubHeads, 'ActiveExamSubHeadID'));
                            if ($SearchKey2) {
                                $MaxMarks = $ActiveExamSubHeads[$SearchKey2]['MaxMarks'] ? $ActiveExamSubHeads[$SearchKey2]['MaxMarks'] : 0;
                                
//                                $TimeValidation = DB::table('tbl_marks_distributions')
//                                        ->select('allowed_input_date_time_from', 'allowed_input_date_time_to')
//                                        ->where('subject_id', $SubjectID)
//                                        ->where('class_id', $ClassID)
//                                        ->where('group_id', $GroupID)
//                                        ->where('institute_branch_version_id', $IbvID