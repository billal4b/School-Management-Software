<?php ?>
<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">As a teacher</h3>
            </div>
            <div class="panel-body">
                
                <a class="btn btn-sm btn-primary" id="ViewMarksAsTeacherBtn"><i class="fa fa-print"></i>&nbsp;View Marks as Teacher</a><br>&nbsp;

                <table class="table table-condensed table-striped table-bordered" style="" id="Tbl_MarksViewAsTeacher">
                    <thead>
                        <tr>
                            <th><button type="button" class="btn btn-primary btn-xs" id="SelectUnselectAllCheckbox"><i class="fa fa-check-square" id="Select"></i>&nbsp;Select All</button></th>
                            <th>Class</th>
                            <th>Group</th>
                            <th>Section</th>
                            <th>Subject</th>
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
                        if (isset($TeacherPrivilege)) {
                            $Counter = 1;
                            foreach ($TeacherPrivilege as $Tp) {
                                ?>
                                <tr>
                                    <td>
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="checkbox{{ $ExamTypeID . '_' . $Tp->class_id . '_' . $Tp->group_id . '_' . $Tp->institute_branch_version_id . '_' . $Tp->subject_id . '_' . $Tp->section_id }}" value="{{ $ExamTypeID . '_' . $Tp->class_id . '_' . $Tp->group_id . '_' . $Tp->institute_branch_version_id . '_' . $Tp->subject_id . '_' . $Tp->section_id }}">
                                            <label for="checkbox{{ $ExamTypeID . '_' . $Tp->class_id . '_' . $Tp->group_id . '_' . $Tp->institute_branch_version_id . '_' . $Tp->subject_id . '_' . $Tp->section_id }}">
                                                <span class="label label-danger"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?php echo $Tp->ClassName; ?></td>
                                    <td><?php echo $Tp->GroupName; ?></td>
                                    <td><?php echo $Tp->SectionName; ?></td>
                                    <td><?php echo $Tp->subject_name; ?></td>
                                    <?php
                                    if (isset($ExamSubHeads)) {
                                        foreach ($ExamSubHeads as $esh) {

                                            $CheckIsMarksInputedByTeacher = DB::table('tbl_marks_input')
                                                    ->select('marks_input_id')
                                                    ->where('exam_type_id', $ExamTypeID)
                                                    ->where('exam_head_id', $esh->exam_head_id)
                                                    ->where('exam_sub_head_id', $esh->exam_sub_head_id)
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
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                    </tbody>


                </table>

            </div>
        </div>



    </div>

</div>

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">As a class teacher</h3>
            </div>
            <div class="panel-body">
                
                <a class="btn btn-sm btn-primary" id="ViewMarksAsClassTeacherBtn"><i class="fa fa-print"></i>&nbsp;View Marks as Class Teacher</a><br>&nbsp;

                <table class="table table-condensed table-striped table-bordered" style="" id="Tbl_MarksViewAsClassTeacher">
                    <thead>
                        <tr>
                            <th><button type="button" class="btn btn-primary btn-xs" id="SelectUnselectAllCheckbox2"><i class="fa fa-check-square" id="Select"></i>&nbsp;Select All</button></th>
                            <th>Class</th>
                            <th>Group</th>
                            <th>Section</th>
                            <th>Subject</th>
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
                        if (isset($ClassTeacher)) {
                            foreach ($ClassTeacher as $Tp) {
                                ?>
                                <tr>
                                    <td>
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="checkbox{{ $ExamTypeID . '_' . $Tp->class_id . '_' . $Tp->group_id . '_' . $Tp->institute_branch_version_id . '_' . $Tp->subject_id . '_' . $Tp->section_id }}" value="{{ $ExamTypeID . '_' . $Tp->class_id . '_' . $Tp->group_id . '_' . $Tp->institute_branch_version_id . '_' . $Tp->subject_id . '_' . $Tp->section_id }}">
                                            <label for="checkbox{{ $ExamTypeID . '_' . $Tp->class_id . '_' . $Tp->group_id . '_' . $Tp->institute_branch_version_id . '_' . $Tp->subject_id . '_' . $Tp->section_id }}">
                                                <span class="label label-danger"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?php echo $Tp->ClassName; ?></td>
                                    <td><?php echo $Tp->GroupName; ?></td>
                                    <td><?php echo $Tp->SectionName; ?></td>
                                    <td><?php echo $Tp->subject_name; ?></td>
                                    <?php
                                    if (isset($ExamSubHeads)) {
                                        foreach ($ExamSubHeads as $esh) {

                                            $CheckIsMarksInputedByTeacher = DB::table('tbl_marks_input')
                                                    ->select('marks_input_id')
                                                    ->where('exam_type_id', $ExamTypeID)
                                                    ->where('exam_head_id', $esh->exam_head_id)
                                                    ->where('exam_sub_head_id', $esh->exam_sub_head_id)
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
//                                            echo '<td>hi</td>';
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                    </tbody>


                </table>

            </div>
        </div>



    </div>

</div>

<!--<div class="table-responsive">-->

<!--</div>-->

<script>
    $(document).ready(function () {

        $(document).on('click', '#ViewMarksAsTeacherBtn', function () {
            
            var param = '';
            $('#Tbl_MarksViewAsTeacher tbody tr').find('td:nth-child(1) :checkbox').each(function () {
//                $(this).prop('checked', checkedStatus);
//                console.log(checkedStatus);
                    if($(this).is(':checked')){
//                        alert($(this).val());
                            param += $(this).val() + '-';
                    }
            });
            //alert(param);
            if(param != ''){
                $(this).attr('href', baseURL + '/!/Result/MarksView/Print/' + param.slice(0, -1));
                $(this).attr('target', '_blank');
            }
        });
        
        $(document).on('click', '#ViewMarksAsClassTeacherBtn', function () {
            
            var param = '';
            $('#Tbl_MarksViewAsClassTeacher tbody tr').find('td:nth-child(1) :checkbox').each(function () {
//                $(this).prop('checked', checkedStatus);
//                console.log(checkedStatus);
                    if($(this).is(':checked')){
//                        alert($(this).val());
                            param += $(this).val() + '-';
                    }
            });
            //alert(param);
            if(param != ''){
                $(this).attr('href', baseURL + '/!/Result/MarksView/Print/' + param.slice(0, -1));
                $(this).attr('target', '_blank');
            }
        });
    });
</script>