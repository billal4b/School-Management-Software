<table width="100%" border="1">
    <thead>
        <tr>
            <th>Teacher ID</th>
            <th>Name</th>
            <th>Phone No.</th>
            <th>Class</th>
            <th>Group</th>
            <th>Section</th>
            <th>Subject</th>
            <th>Is marks input?</th>
        </tr>
    </thead>

    <tbody
    <?php
    $Teachers = DB::table('tbl_users')
            ->select('user_id', 'full_name', 'username', 'phone_no')
            ->where('institute_branch_version_id', $Branch)
            ->where('role_id', 5)
            ->where('is_active', 1)
            ->get();
    foreach ($Teachers as $te) {


        $TeacherPrivilege = DB::table('tbl_assigned_subjects_to_teachers as tastt')
                ->select('tastt.subject_id', 'ts.subject_name', 'tastt.class_id', 'tastt.group_id', 'tastt.section_id', 'ci.ClassName', 'sg.GroupName', 'si.SectionName', 'si.section_id', 'ci.id as class_id', 'sg.id as group_id', 'tastt.institute_branch_version_id')
                ->join('tbl_subjects as ts', 'ts.subject_id', '=', 'tastt.subject_id')
                ->join('classinfo as ci', 'ci.id', '=', 'tastt.class_id')
                ->join('stugrp as sg', 'sg.id', '=', 'tastt.group_id')
                ->join('sectioninfo as si', 'si.section_id', '=', 'tastt.section_id')
                ->where('tastt.user_id', $te->user_id)
                ->orderBy('ts.subject_code', 'asc')
//                ->where('tastt.class_id', $ClassID)
//                ->where('tastt.section_id', $SectionID)
//                ->where('tastt.group_id', $GroupID)
                ->where('tastt.is_active', 1)
                ->get();

        /* $ClassTeacher = DB::select('select 
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
          tst.user_id = ' . $te->user_id . ' and tst.is_active = 1 and ts.is_active = 1 order by ts.subject_code asc');
         */
        if (!empty($TeacherPrivilege)) {
            $Counter = 1;
            foreach ($TeacherPrivilege as $Tp) {

                $IsActive = DB::table('tbl_marks_distributions')
                        ->select('marks_distribution_id')
                        ->where('exam_type_id', $ExamTypeID)
                        ->where('exam_head_id', $ExamHeadID)
                        ->where('exam_sub_head_id', $ExamSubHeadID)
                        ->where('class_id', $Tp->class_id)
                        ->where('group_id', $Tp->group_id)
                        ->where('institute_branch_version_id', $Tp->institute_branch_version_id)
                        ->where('subject_id', $Tp->subject_id)
                        ->where('is_active', 1)
                        ->first();
                //print_r($IsActive);
                if (!empty($IsActive)) {
                    ?>
                        <tr>
                            <td><?php echo $te->username; ?></td>
                            <td><?php echo $te->full_name; ?></td>
                            <td><?php echo $te->phone_no; ?></td>
                            <td><?php echo $Tp->ClassName; ?></td>
                            <td><?php echo $Tp->GroupName; ?></td>
                            <td><?php echo $Tp->SectionName; ?></td>
                            <td><?php echo $Tp->subject_name; ?></td>
                            <?php
                            $CheckIsMarksInputedByTeacher = DB::table('tbl_marks_input')
                                    ->select('marks_input_id')
                                    ->where('exam_type_id', $ExamTypeID)
                                    ->where('exam_head_id', $ExamHeadID)
                                    ->where('exam_sub_head_id', $ExamSubHeadID)
                                    ->where('class_id', $Tp->class_id)
                                    ->where('group_id', $Tp->group_id)
                                    ->where('institute_branch_version_id', $Tp->institute_branch_version_id)
                                    ->where('subject_id', $Tp->subject_id)
                                    ->where('section_id', $Tp->section_id)
                                    ->count();
//                                            echo '<td>' . $CheckIsMarksInputedByTeacher . '</td>';
                            if ($CheckIsMarksInputedByTeacher > 0) {
                                echo '<td class="text-center"><i class="fa fa-check text-success"></i></td>';
                            } else {
                                echo '<td class="text-center"><i class="fa fa-times text-danger"></i></td>';
                            }
                            ?>
                        </tr>
                        <?php
                    }
                }
            }
        }
        ?>
    </tbody>
</table>





<script type="text/javascript">

    $(document).ready(function () {

        $('table > tbody > tr').each(function () {

            var rowIndex = $(this).index();
            var text = $('table > tbody > tr:eq(' + rowIndex + ') td:eq(7)').find('i').attr('class');
            if (text.indexOf("success") >= 0) {
                $("table > tbody > tr").eq(rowIndex).remove();
            }
        });
    });
</script>