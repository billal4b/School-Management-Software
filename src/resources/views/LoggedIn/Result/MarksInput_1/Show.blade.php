<?php
$RolesID = array(4, 11, 12, 13, 14, 15, 16, 17, 18, 19);
$TotalStudents = isset($Students) ? count($Students) : 0;
$TotalExamSubHeads = isset($ExamSubHeads) ? count($ExamSubHeads) : 0;
//echo '<pre>';
//        print_r($ActiveExamSubHeads);
//        echo '</pre>';
//        exit();
?>

<div class="row">
    <div class="col-sm-12">


        <?php
        $QueryForCheckIsClassTeacher = DB::table('tbl_class_teachers')
                ->select('class_id', 'group_id', 'section_id')
                ->where('user_id', Auth::user()->user_id)
                ->first();
        $CheckIsClassTeacher = empty($QueryForCheckIsClassTeacher) ? 0 : 1;
        //if (in_array(Auth::user()->role_id, $RolesID)) {
            ?>
            <a class="btn btn-primary" href="{{ url(route('LoggedIn.Result.MarksInput.MarksView', array('Branch' => $IbvID, 'ClassID' => $ClassID, 'GroupID' => $GroupID, 'ExamTypeID' => $ExamTypeID, 'ExamHeadID' => $ExamHead, 'Role' => 1))) }}" target="_blank"><i class="fa fa-eye"></i>&nbsp;Marks View As Admin</a>
            <?php
        //} else {
         //   if ($CheckIsClassTeacher) {
                ?>
                <a class="btn btn-primary" href="{{ url(route('LoggedIn.Result.MarksInput.MarksView', array('Branch' => $IbvID, 'ClassID' => $ClassID, 'GroupID' => $GroupID, 'ExamTypeID' => $ExamTypeID, 'ExamHeadID' => $ExamHead, 'Role' => 2))) }}" target="_blank"><i class="fa fa-eye"></i>&nbsp;Marks View As Class Teacher</a>
                <?php
            //}
            ?>
            <a class="btn btn-primary" href="{{ url(route('LoggedIn.Result.MarksInput.MarksView', array('Branch' => $IbvID, 'ClassID' => $ClassID, 'GroupID' => $GroupID, 'ExamTypeID' => $ExamTypeID, 'ExamHeadID' => $ExamHead, 'Role' => 3))) }}" target="_blank"><i class="fa fa-eye"></i>&nbsp;Marks View As Teacher</a>
            <?php
        //}
        ?>
    </div>
</div>

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
//                                        ->where('institute_branch_version_id', $IbvID)
//                                        ->where('exam_type_id', $ExamTypeID)
//                                        ->where('exam_sub_head_id', $value2->exam_sub_head_id)
//                                        ->where('exam_head_id', $value2->exam_head_id)
//                                        ->first();
//                                                               print_r($TimeValidation);

                                $SearchKey = array_search($IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $value2->exam_sub_head_id . '-' . $SubjectID . '-' . $value->student_id, array_column($ExistingMarksInputs, 'ExistingID'));
                                $MarksInputID = 0;
                                $Marks = 0;
                                $IsActive = 1;
                                //$CouldEdit = TRUE;
                                if ($SearchKey) {

                                    $MarksInputID = $ExistingMarksInputs[$SearchKey]['MarksInputID'];
                                    $Marks = $ExistingMarksInputs[$SearchKey]['Marks'];
                                    $IsActive = $ExistingMarksInputs[$SearchKey]['IsActive'];
                                }
                                ?>
                                <td>

                                    <?php
//                                    $CurrentDate = strtotime(date('Y-m-d H:i:s'));
//                                    $From = strtotime(date('Y-m-d H:i:s', strtotime(isset($TimeValidation->allowed_input_date_time_from) ? $TimeValidation->allowed_input_date_time_from : 0)));
//                                    $To = strtotime(date('Y-m-d H:i:s', strtotime(isset($TimeValidation->allowed_input_date_time_to) ? $TimeValidation->allowed_input_date_time_to : 0)));
//                                    if ($CurrentDate >= $From && $CurrentDate <= $To) {
                                    if ($IsActive == 1) {
                                        ?>
                                        <form class="form-horizontal" id="MarksInputFrm<?php echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $value2->exam_sub_head_id . '-' . $SubjectID . '-' . $value->student_id; ?>">
                                            <div class="form-group form-group-sm">
                                                <label for="Marks" class="col-sm-2 control-label text-danger">Marks</label>
                                                <div class="col-sm-4">
                                                    <?php
                                                    if ($MarksInputID > 0) {

                                                        if (in_array(Auth::user()->role_id, $RolesID)) {
                                                            echo '<input type="hidden" id="MaxMarks" value="' . $MaxMarks . '">';
                                                            echo '<input type="text" class="form-control Marks" id="Marks" name="Marks" value="' . $Marks . '">';
                                                        } else {
                                                            echo '<p class="form-control-static">' . ($Marks < 0 ? 'ABSENT' : $Marks) . '</p>';
                                                        }
                                                    } else {
                                                        echo '<input type="hidden" id="MaxMarks" value="' . $MaxMarks . '">';
                                                        ?>
                                                    
                                                        <input type="text" class="form-control Marks" id="Marks" name="Marks" value="<?php echo $Marks; ?>">
<input type="hidden" id="MaxMarks" value="<?php echo $MaxMarks; ?>">
                                                        <?php
                                                    }
                                                    ?>


                        <!--                                                        <input type="text" class="form-control Marks" id="Marks" name="Marks" value="<?php //echo $Marks;  ?>">-->


                                                </div>

                                                <!--                                                <div class="col-sm-6">
                                                                                                        <div class="checkbox checkbox-danger">
                                                                                                            <input type="checkbox" id="Absent<?php // echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $value2->exam_sub_head_id . '-' . $SubjectID . '-' . $value->student_id;  ?>" value="0" <?php // echo $Marks < 0 ? 'checked' : '';  ?> class="Absent" name="Absent">
                                                                                                            <label for="Absent<?php // echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $value2->exam_sub_head_id . '-' . $SubjectID . '-' . $value->student_id;  ?>">
                                                                                                                Absent
                                                                                                            </label>
                                                                                                        </div>
                                                                                                    </div>-->

                                                <?php
                                                if ($MarksInputID > 0) {
//                                                    echo $Marks < 0 ? 'Absent' : $Marks;
                                                    if (in_array(Auth::user()->role_id, $RolesID)) {
                                                        ?>
                                                        <div class="col-sm-6">
                                                            <div class="checkbox checkbox-danger">
                                                                <input type="checkbox" id="Absent<?php echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $value2->exam_sub_head_id . '-' . $SubjectID . '-' . $value->student_id; ?>" value="0" <?php echo $Marks < 0 ? 'checked' : ''; ?> class="Absent" name="Absent">
                                                                <label for="Absent<?php echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $value2->exam_sub_head_id . '-' . $SubjectID . '-' . $value->student_id; ?>">
                                                                    Absent
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                } else {
                                                    if (in_array(Auth::user()->role_id, $RolesID)) {
                                                        ?>
                                                        <div class="col-sm-6">
                                                            <div class="checkbox checkbox-danger">
                                                                <input type="checkbox" id="Absent<?php echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $value2->exam_sub_head_id . '-' . $SubjectID . '-' . $value->student_id; ?>" value="0" <?php echo $Marks < 0 ? 'checked' : ''; ?> class="Absent" name="Absent">
                                                                <label for="Absent<?php echo $IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $value2->exam_sub_head_id . '-' . $SubjectID . '-' . $value->student_id; ?>">
                                                                    Absent
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                            </div>
                                            <input type="hidden" class="form-control input-sm" name="Subject" value="<?php echo $SubjectID; ?>">
                                            <input type="hidden" class="form-control input-sm" name="Student" value="<?php echo $value->student_id; ?>">
                                            <input type="hidden" class="form-control input-sm" name="ExamSubHead" value="<?php echo $value2->exam_sub_head_id; ?>">
                                            <input type="hidden" name="Class" value="{{ isset($ClassID) ? $ClassID : 0 }}">
                                            <input type="hidden" name="Group" value="{{ isset($GroupID) ? $GroupID : 0 }}">
                                            <input type="hidden" name="Branch" value="{{ isset($IbvID) ? $IbvID : 0 }}">
                                            <input type="hidden" name="ExamType" value="{{ isset($ExamTypeID) ? $ExamTypeID : 0 }}">
                                            <input type="hidden" name="ExamHead" value="<?php echo isset($ExamHead) ? $ExamHead : 0 ?>">
                                            <input type="hidden" name="MarksInput" value="<?php echo $MarksInputID; ?>">

                                        </form>
                                        <?php
                                    }
//                                    } else {
//                                        echo isset($IsExist->marks) ? $IsExist->marks : 0;
//                                    }
                                    ?>


                                </td>
                                <?php
                            }
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
                    $SearchKey = array_search($IbvID . '-' . $ClassID . '-' . $GroupID . '-' . $ExamTypeID . '-' . $ExamHead . '-' . $esh->exam_sub_head_id . '-' . $SubjectID, array_column($ActiveExamSubHeads, 'ActiveExamSubHeadID'));
                    if ($SearchKey) {
                        if (in_array(Auth::user()->role_id, $RolesID)) {
                            echo '<td><button type="button" class="SubmitMarksInputFrm btn btn-primary btn-block"><i class="fa fa-floppy-o"></i>&nbsp;Save ' . $esh->exam_sub_head_alias . '</button></td>';
                        }
                    }
                    ?>



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
                
        $(".Marks").blur(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            var MaxMarks = $(this).prev().attr('value');
            if(parseFloat(MaxMarks) < parseFloat($(this).val())){
                alert('Limit Access!');
                $(this).val(0);
            }
                });

        $(document).on('click', '.SubmitMarksInputFrm', function () {

//    var colIndex = $(this).parent().children().index($(this));
            var colIndex = $(this).parent().index();
//    alert(colIndex);
            $('#ShowMarksInputTbl tbody tr').each(function () {
//                var EachFormID = $(this).find('td:eq(' + colIndex + ')').closest('form').attr('id');
                var EachFormID = $(this).find('td:eq(' + colIndex + ')').find('form').attr('id');
//                alert(EachFormID);
                var Frm = $('#' + EachFormID).serialize();
                //console.log(Frm);
                $.ajax({
                    type: "POST",
                    url: baseURL + '/!/Result/MarksInput/Save',
                    data: Frm,
                    success: function (response) {
//                        alert(response);
                    }
                })
            });
            alert('Data saved successfully.');
            return false;
        });

        $(document).on('click', '.Absent', function () {
            var CheckedStatus = $(this).is(':checked');
            var input = $(this).parent().parent().prev().find('input');
            if (CheckedStatus == true) {
                input.attr('value', '-1');
//                input.attr('disabled', 'disabled');
            } else {
                input.attr('value', '0');
//                input.removeAttr('disabled');
            }
        });
    });
</script>
