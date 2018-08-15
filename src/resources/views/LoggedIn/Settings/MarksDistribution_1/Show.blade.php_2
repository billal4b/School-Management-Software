<?php
$TotalSubjects = isset($Subjects) ? count($Subjects) : 0;
$TotalExamSubHeads = isset($ExamSubHeads) ? count($ExamSubHeads) : 0;
?>

<!--<div class="table-responsive">-->
<table class="table table-condensed table-striped table-bordered" style="font-size: 10px;">
    <thead>
        <tr>
            <th>Sl.</th>
            <th>Subject</th>
            <?php
            if (isset($ExamSubHeads)) {
                foreach ($ExamSubHeads as $esh) {
                    echo '<th>' . $esh->exam_sub_head_alias . '</th>';
                }
            }
            ?>

                <!--<th>Total Marks</th>-->
        </tr>
    </thead>

    <tbody>



        <?php
        if (isset($Subjects)) {
            foreach ($Subjects as $key => $value) {
                ?>

                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $value->subject_name; ?></td>
                    <?php
                    if (isset($ExamSubHeads)) {
                        foreach ($ExamSubHeads as $key2 => $value2) {
                            
                            $IsExist = DB::table('tbl_marks_distributions')
                                ->where('subject_id', $value->subject_id)
                                ->where('class_id', $ClassID)
                                ->where('group_id', $GroupID)
                                ->where('institute_branch_version_id', $IbvID )
                                ->where('exam_type_id', $ExamTypeID)
                                ->where('exam_sub_head_id', $value2->exam_sub_head_id)
                                ->where('exam_head_id', $value2->exam_head_id)
                                ->first();
//                             echo '<pre>';
//        print_r($IsExist);
//        echo '</pre>';
//        exit();
                            $TotalIsExist = count($IsExist);
                            //var_dump($TotalIsExist);exit();
                            ?>
                            <td>

                                <form class="form-inline">
                                    <div class="form-group form-group-sm">
                                        <label for="FullMarks">Full Marks</label>
                                        <input type="text" class="form-control Marks" id="FullMarks" name="FullMarks" value="<?php echo ($TotalIsExist > 0 ? $IsExist->full_marks : ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="From">From</label>
                                        <input type="text" class="form-control datepicker" id="From" name="From" value="<?php echo ($TotalIsExist > 0 ? $IsExist->allowed_input_date_time_from : ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="To">To</label>
                                        <input type="text" class="form-control datepicker" id="To" name="To" value="<?php echo ($TotalIsExist > 0 ? $IsExist->allowed_input_date_time_to : ''); ?>">
                                    </div>
                                    <input type="hidden" class="form-control input-sm" name="Subject" value="<?php echo $value->subject_id; ?>">
                                    <input type="hidden" class="form-control input-sm" name="ExamSubHead" value="<?php echo $value2->exam_sub_head_id; ?>">
                                    <input type="hidden" name="Class" value="{{ isset($ClassID) ? $ClassID : 0 }}">
                                    <input type="hidden" name="Group" value="{{ isset($GroupID) ? $GroupID : 0 }}">
                                    <input type="hidden" name="Branch" value="{{ isset($IbvID) ? $IbvID : 0 }}">
                                    <input type="hidden" name="ExamType" value="{{ isset($ExamTypeID) ? $ExamTypeID : 0 }}">
                                    <input type="hidden" name="ExamHead" value="<?php echo $value2->exam_head_id; ?>">
                                    <input type="hidden" name="MarksDistribution" value="<?php echo ($TotalIsExist > 0 ? $IsExist->marks_distribution_id : 0); ?>">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </td>
                <?php
            }
        }
        ?>
                </tr>
                    <?php
                }
            }
            ?>

        <!--<td>0</td>-->

    </tbody>

</table>
<!--</div>-->

<script>
    $(document).ready(function () {

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
        });

        $(".Marks").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    // Allow: Ctrl+A, Command+A
                            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                            // Allow: home, end, left, right, down, up
                                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                        // let it happen, don't do anything
                        return;
                    }
                    // Ensure that it is a number and stop the keypress
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
                    }
                });

//        $(document).on('blur', '.Marks', function () {
//
//            var rowIndex = $(this).closest('td').parent()[0].sectionRowIndex;
//            //alert(rowIndex);
//            var total = 0;
//            $('tr:eq(' + (rowIndex + 1) + ')').find('td').each(function () {
//
//                var status = $(this).find('.Marks').val();
//                if (status != undefined) {
//                    //console.log(status);
//                    total += parseInt(status);
//                }
//            });
//            $('tr:eq(' + (rowIndex + 1) + ')').find('td:eq(-1)').html(total);
//        });



    });
</script>