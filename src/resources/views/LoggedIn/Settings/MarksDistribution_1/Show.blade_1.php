<?php
$TotalSubjects = isset($Subjects) ? count($Subjects) : 0;
$TotalExamSubHeads = isset($ExamSubHeads) ? count($ExamSubHeads) : 0;
?>

<div class="table-responsive">
    <table class="table table-condensed table-striped table-bordered" style="font-size: 12px;">
        <thead>
            <tr>
                <th>Sl.</th>
                <th>Subjects</th>

                <?php
                if (isset($ExamSubHeads)) {
                    foreach ($ExamSubHeads as $esh) {
                        echo '<th>' . $esh->exam_sub_head_name . '</th>';
                    }
                }
                ?>

                <th>Total Marks</th>
                <th>Action(s)</th>
            </tr>
        </thead>

        <tbody>

            <?php
            if (isset($Subjects)) {
                $sl = 1;
                $Update = FALSE;
                foreach ($Subjects as $sub) {
                    ?>

                <form id="MarksDistributions{{ $sl }}">
                    <tr>


                        <td><?php echo $sl; ?></td>
                        <td><?php echo $sub->subject_name . '(' . $sub->subject_code . ')'; ?></td>

                    <input type="hidden" name="SubjectID" value="{{ $sub->subject_id }}">
                    <input type="hidden" name="ClassID" value="{{ isset($ClassID) ? $ClassID : 0 }}">
                    <input type="hidden" name="GourpID" value="{{ isset($GroupID) ? $GroupID : 0 }}">
                    <input type="hidden" name="IbvID" value="{{ isset($IbvID) ? $IbvID : 0 }}">
                    <input type="hidden" name="ExamTypeID" value="{{ isset($ExamTypeID) ? $ExamTypeID : 0 }}">

                    <?php
                    if (isset($ExamSubHeads)) {
                        for ($i = 0; $i < $TotalExamSubHeads; $i++) {
                            ?>


                            <?php
                            $IsExist = DB::table('tbl_marks_distributions')
                                    ->where('subject_id', $sub->subject_id)
                                    ->where('class_id', isset($ClassID) ? $ClassID : 0)
                                    ->where('group_id', isset($GroupID) ? $GroupID : 0)
                                    ->where('institute_branch_version_id', isset($IbvID) ? $IbvID : 0)
                                    ->where('exam_type_id', isset($ExamTypeID) ? $ExamTypeID : 0)
                                    ->where('exam_sub_head_id', $ExamSubHeads[$i]->exam_sub_head_id)
                                    ->first();

                            if (count($IsExist) > 0) {
                                $Update = TRUE;
                                ?>
                                <input type="hidden" name="ExamSubHeadID" value="{{ $ExamSubHeads[$i]->exam_sub_head_id }}">
                                <input type="hidden" name="MarksDistributionID" value="{{ $IsExist->marks_distribution_id }}">
                                <td>
                                    Marks : <input type="text" class="form-control input-sm Marks" value="<?php echo $IsExist->marks; ?>" name="Marks">
                                    Input From :<input type="text" class="form-control input-sm datepicker InputFrom" name="InputFrom">
                                    Input To :<input type="text" class="form-control input-sm datepicker InputTo" name="InputTo">
                                </td>

                                <?php
                            } else {
                                $Update = FALSE;
                                ?>
                                <input type="hidden" name="ExamSubHeadID" value="{{ $ExamSubHeads[$i]->exam_sub_head_id }}">
                                <td>
                                    Marks : <input type="text" class="form-control input-sm Marks" value="0" name="Marks">
                                    Input From :<input type="text" class="form-control input-sm datepicker InputFrom" name="InputFrom">
                                    Input To :<input type="text" class="form-control input-sm datepicker InputTo" name="InputTo">
                                </td>
                                <?php
                            }
                            ?>




                            <?php
                        }
                    }
                    ?>

                    <td>0</td>
                    <td>
                        <?php
                        if ($Update) {
                            ?>
                            <button class="btn btn-xs btn-primary StoreUpdateMarksDistribution"><i class="fa fa-edit">&nbsp;Edit</i></button>
                            <?php
                        } else {
                            ?>
                            <button class="btn btn-xs btn-primary StoreUpdateMarksDistribution"><i class="fa fa-plus">&nbsp;Add</i></button>
                            <?php
                        }
                        ?>
                    </td>


                    </tr>
                </form>


                <?php
                $sl++;
            }
        }
        ?>

        </tbody>
    </table>
</div>

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

        $(document).on('blur', '.Marks', function () {

            var rowIndex = $(this).closest('td').parent()[0].sectionRowIndex;
            //alert(rowIndex);
            var total = 0;
            $('tr:eq(' + (rowIndex + 1) + ')').find('td').each(function () {

                var status = $(this).find('.Marks').val();
                if (status != undefined) {
                    //console.log(status);
                    total += parseInt(status);
                }
            });
            $('tr:eq(' + (rowIndex + 1) + ')').find('td:eq(-1)').html(total);
        });



    });
</script>