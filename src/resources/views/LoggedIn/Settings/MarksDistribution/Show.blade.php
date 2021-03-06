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
                    echo '<th><strong style="font-size: 14px">' . $esh->exam_sub_head_alias . '</strong>';
                    ?>
            <form class="form-horizontal" id="SBAPassMarksFrm<?php echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead; ?>">

                <div class="form-group form-group-sm">
                    <label for="From" class="col-sm-5 control-label">From</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control From datepicker" id="From" name="From">
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="To" class="col-sm-5 control-label">To</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control To datepicker" id="To" name="To">
                    </div>
                </div>
                <!--<button type="button" class="btn btn-default" id="ApplyDateToCol">Apply</button>-->
            </form>
            <?php
            echo '</th>';
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
                <td>
                    <?php
                    echo $value->subject_name;
                    if ($ExamHead != 1) {

                        $ExistingPassMarks = DB::table('tbl_marks_distributions')
                                ->select('pass_marks')
                                ->where('subject_id', $value->subject_id)
                                ->where('class_id', $ClassID)
                                ->where('group_id', $GroupID)
                                ->where('institute_branch_version_id', $IbvID)
                                ->where('exam_type_id', $ExamTypeID)
                                //->where('exam_sub_head_id', $esh->exam_sub_head_id)
                                ->where('exam_head_id', $ExamHead)
                                ->where('is_active', 1)
                                ->first();
                        $PassMarks = 0;
                        if (isset($ExistingPassMarks->pass_marks)) {
                            $PassMarks = $ExistingPassMarks->pass_marks;
                        }
//                            
                        ?>
                        <form class="form-horizontal" id="SBAPassMarksFrm<?php echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $value->subject_id; ?>">

                            <div class="form-group form-group-sm">
                                <label for="PassMarks" class="col-sm-5 control-label">Pass Marks</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control Marks PassMarks" id="PassMarks" name="PassMarks" value="<?php echo $PassMarks; ?>">
                                </div>
                                <input type="hidden" class="form-control input-sm" name="Subject" value="<?php echo $value->subject_id; ?>">
                                <input type="hidden" name="Class" value="{{ isset($ClassID) ? $ClassID : 0 }}">
                                <input type="hidden" name="Group" value="{{ isset($GroupID) ? $GroupID : 0 }}">
                                <input type="hidden" name="Branch" value="{{ isset($IbvID) ? $IbvID : 0 }}">
                                <input type="hidden" name="ExamType" value="{{ isset($ExamTypeID) ? $ExamTypeID : 0 }}">
                                <input type="hidden" name="ExamHead" value="<?php echo $ExamHead; ?>">
                            </div>
                        </form>
                        <?php
                    }
                    ?>
                </td>
                <?php
                if (isset($ExamSubHeads)) {
                    foreach ($ExamSubHeads as $key2 => $value2) {

                        $SearchKey = array_search($IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $value2->exam_head_id . '-' . $value2->exam_sub_head_id . '-' . $value->subject_id, array_column($ExistingMarksDistributions, 'ExistingID'));
                        $MarksDistributionID = 0;
                        $FullMarks = '';
                        $AllowedInputDateTimeFrom = '';
                        $AllowedInputDateTimeTo = '';
                        $IsActive = 1;
                        $PassMarks = '';
                        if ($SearchKey) {

                            $MarksDistributionID = $ExistingMarksDistributions[$SearchKey]['MarksDistributionID'];
                            $FullMarks = $ExistingMarksDistributions[$SearchKey]['FullMarks'];
                            $AllowedInputDateTimeFrom = $ExistingMarksDistributions[$SearchKey]['InputFrom'] == '0000-00-00 00:00:00' ? '' : date('d-m-Y', strtotime($ExistingMarksDistributions[$SearchKey]['InputFrom']));
                            $AllowedInputDateTimeTo = $ExistingMarksDistributions[$SearchKey]['InputTo'] == '0000-00-00 00:00:00' ? '' : date('d-m-Y', strtotime($ExistingMarksDistributions[$SearchKey]['InputTo']));
                            $IsActive = $ExistingMarksDistributions[$SearchKey]['IsActive'];
                            $PassMarks = $ExistingMarksDistributions[$SearchKey]['PassMarks'];
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
                                <?php
                                if ($ExamHead == 1) {
                                    ?>
                                    <div class="form-group form-group-sm">
                                        <label for="PassMarks" class="col-sm-5 control-label">Pass Marks</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control Marks" id="PassMarks" name="PassMarks" value="<?php echo $PassMarks; ?>">
                                        </div>
                                    </div>
                                    <?php
                                }else{
                                    ?>
                                <input type="hidden" id="PassMarks" name="PassMarks" class="PassMarks"/>
                                <?php
                                }
                                ?>
                                <div class="form-group form-group-sm">
                                    <label for="From" class="col-sm-5 control-label">From</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker FromDate" id="From" name="From" value="<?php echo $AllowedInputDateTimeFrom; ?>">
                                    </div>
                                </div>

                                <div class="form-group form-group-sm">
                                    <label for="To" class="col-sm-5 control-label">To</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker ToDate" id="To" name="To" value="<?php echo $AllowedInputDateTimeTo; ?>">
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
        <td>
            <?php
            if ($ExamHead != 1) {
                ?>
                <button type="button" class="SubmitMarksDistributionPassMarksFrm btn btn-primary btn-block"><i class="fa fa-floppy-o"></i>&nbsp;Save Pass Marks</button>
                <?php
            }
            ?>
        </td>
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
            autoclose: true
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
//            alert(colIndex);
//            return;
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
                        //$('#ShowMarksDistribution').html(response);
                    }
                });
                //return false;
            });
            alert('Data saved successfully.');
            return false;
        });
        $(document).on('click', '.SubmitMarksDistributionPassMarksFrm', function () {

//    var colIndex = $(this).parent().children().index($(this));
            var colIndex = $(this).parent().index();
//    alert(colIndex);
            $('#ShowMarksDistributionTbl tbody tr').each(function () {
//                var EachFormID = $(this).find('td:eq(' + colIndex + ')').closest('form').attr('id');
                var EachFormID = $(this).find('td:eq(1)').find('form').attr('id');
//                alert(EachFormID);
                var Frm = $('#' + EachFormID).serialize();
                //console.log(Frm);
                $.ajax({
                    type: "POST",
                    url: baseURL + '/!/Settings/MarksDistribution/SaveSBAPassMarks',
                    data: Frm,
                    success: function (response) {
                        //alert(response);
                        //$('#ShowMarksDistribution').html(response);
                    }
                })
            });
            alert('Data saved successfully.');
            return false;
        });

        $( ".From" ).change(function() {
  var colIndex = $(this).closest('th').index();
            var data = $(this).val();
//            alert(colIndex);return;
            $('#ShowMarksDistributionTbl tbody tr').each(function () {
//                alert('hu');
                var EachFormID = $(this).find('td:eq(' + colIndex + ')').find('input.FromDate').val();
                //alert(EachFormID);
                if (EachFormID == '') {
                    $(this).find('td:eq(' + colIndex + ')').find('input.FromDate').val(data);
                }
//                alert(EachFormID);
            });
});
        $( ".To" ).change(function() {
  var colIndex = $(this).closest('th').index();
            var colIndex = $(this).closest('th').index();
            var data = $(this).val();
            //alert(colIndex);return;
            $('#ShowMarksDistributionTbl tbody tr').each(function () {
                var EachFormID = $(this).find('td:eq(' + colIndex + ')').find('input.ToDate').val();
//                console.log(EachFormID);
                if (EachFormID == '') {
                    $(this).find('td:eq(' + colIndex + ')').find('input.ToDate').val(data);
                }
//                alert(EachFormID);
            });
});

//        $(document).on('change', '.From', function () {
//
//            var colIndex = $(this).closest('th').index();
//            var data = $(this).val();
//            //alert(colIndex);return;
//            $('#ShowMarksDistributionTbl tbody tr').each(function () {
//                var EachFormID = $(this).find('td:eq(' + colIndex + ')').find('input.FromDate').val(data);
////                alert(EachFormID);
//            });
//        });

$(document).on('keyup', '.PassMarks', function () {
    var index = $(this).closest('tr').index();
    var PassMarks = $(this).val();
    //alert(index);
    $(this).closest('tr').find('.PassMarks').val(PassMarks);
});

    });
</script>
