<?php
//echo '<pre>';
//print_r($ExamSubHeads);
//echo '</pre>';
//exit();

$TotalSubjects = isset($Subjects) ? count($Subjects) : 0;
$TotalExamSubHeads = isset($ExamSubHeads) ? count($ExamSubHeads) : 0;
?>

<!--<div class="table-responsive">-->
<table class="table table-condensed table-striped table-bordered" style="font-size: 10px;" id="ShowMarksDistributionTbl">
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

                            $SearchKey = array_search($IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $value2->exam_head_id . '-' . $value2->exam_sub_head_id . '-' . $value->subject_id, array_column($ExistingMarksDistributions, 'ExistingID'));
                            $MarksDistributionID = 0;
                            $FullMarks = '';
                            $AllowedInputDateTimeFrom = '';
                            $AllowedInputDateTimeTo = '';
                            $IsActive = 1;
                            if ($SearchKey) {

                                $MarksDistributionID = $ExistingMarksDistributions[$SearchKey]['MarksDistributionID'];
                                $FullMarks = $ExistingMarksDistributions[$SearchKey]['FullMarks'];
                                $AllowedInputDateTimeFrom = $ExistingMarksDistributions[$SearchKey]['InputFrom'] == '0000-00-00 00:00:00' ? '' : date('d-m-Y', strtotime($ExistingMarksDistributions[$SearchKey]['InputFrom']));
                                $AllowedInputDateTimeTo = $ExistingMarksDistributions[$SearchKey]['InputTo'] == '0000-00-00 00:00:00' ? '' : date('d-m-Y', strtotime($ExistingMarksDistributions[$SearchKey]['InputTo']));
                                $IsActive = $ExistingMarksDistributions[$SearchKey]['IsActive'];
                            }
                            ?>
                            <td>

                                <form class="form-horizontal" id="MarksDistributionFrm<?php echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $value2->exam_head_id . '-' . $value2->exam_sub_head_id . '-' . $value->subject_id; ?>">
                                    <div class="form-group form-group-sm">
                                        <label for="FullMarks" class="col-sm-5 control-label">Full Marks</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control Marks" id="FullMarks" name="FullMarks" value="<?php echo $FullMarks; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label for="From" class="col-sm-5 control-label">From</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control datepicker" id="From" name="From" value="<?php echo $AllowedInputDateTimeFrom; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="To" class="col-sm-5 control-label">To</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control datepicker" id="To" name="To" value="<?php echo $AllowedInputDateTimeTo; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="IsActive" class="col-sm-5 control-label">Is Active?</label>
                                        <div class="col-sm-7">
                                            <div class="radio radio-success">
                                                <input type="radio" name="IsActive" id="IsActive<?php echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $value2->exam_head_id . '-' . $value2->exam_sub_head_id . '-' . $value->subject_id; ?>-1" value="1" <?php echo $IsActive == 1 ? 'checked' : ''; ?>>
                                                <label for="IsActive<?php echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $value2->exam_head_id . '-' . $value2->exam_sub_head_id . '-' . $value->subject_id; ?>-1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="radio radio-danger">
                                                <input type="radio" name="IsActive" id="IsActive<?php echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $value2->exam_head_id . '-' . $value2->exam_sub_head_id . '-' . $value->subject_id; ?>-0" value="0" <?php echo $IsActive == 0 ? 'checked' : ''; ?>>
                                                <label for="IsActive<?php echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $value2->exam_head_id . '-' . $value2->exam_sub_head_id . '-' . $value->subject_id; ?>-0">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control input-sm" name="Subject" value="<?php echo $value->subject_id; ?>">
                                    <input type="hidden" class="form-control input-sm" name="ExamSubHead" value="<?php echo $value2->exam_sub_head_id; ?>">
                                    <input type="hidden" name="Class" value="{{ isset($ClassID) ? $ClassID : 0 }}">
                                    <input type="hidden" name="Group" value="{{ isset($GroupID) ? $GroupID : 0 }}">
                                    <input type="hidden" name="Branch" value="{{ isset($IbvID) ? $IbvID : 0 }}">
                                    <input type="hidden" name="ExamType" value="{{ isset($ExamTypeID) ? $ExamTypeID : 0 }}">
                                    <input type="hidden" name="ExamHead" value="<?php echo $value2->exam_head_id; ?>">
                                    <input type="hidden" name="MarksDistribution" value="<?php echo $MarksDistributionID; ?>">
                                    <!--<button type="submit" class="btn btn-primary">Save</button>-->
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

    <tfoot>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <?php
            if (isset($ExamSubHeads)) {
                foreach ($ExamSubHeads as $esh) {
                    ?>
                    <td>
                        <button type="button" class="SubmitMarksDistributionFrm btn btn-primary btn-block"><i class="fa fa-floppy-o"></i>&nbsp;Save <?php echo $esh->exam_sub_head_alias; ?></button>
                    </td>
                    <?php
                }
            }
            ?>
        </tr>
    </tfoot>

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

        $(document).on('click', '.SubmitMarksDistributionFrm', function () {

//    var colIndex = $(this).parent().children().index($(this));
            var colIndex = $(this).parent().index();
//    alert(colIndex);
            $('#ShowMarksDistributionTbl tbody tr').each(function () {
//                var EachFormID = $(this).find('td:eq(' + colIndex + ')').closest('form').attr('id');
                var EachFormID = $(this).find('td:eq(' + colIndex + ')').find('form').attr('id');
//                alert(EachFormID);
                var Frm = $('#' + EachFormID).serialize();
                //console.log(Frm);
                $.ajax({
                    type: "POST",
                    url: baseURL + '/!/Settings/MarksDistribution/Save',
                    data: Frm,
                    success: function (response) {
                        //alert(response);
                    }
                })
            });
            alert('Data saved successfully.');
            return false;
        });

    });
</script>