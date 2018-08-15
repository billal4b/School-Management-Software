<?php
//echo '<pre>';print_r($Teacher);echo '</pre>'; exit(); 

$RolesID = [4, 11, 12, 13, 14, 15, 16, 17, 18, 19];

if (Auth::user()->role_id == 5) {
    if (isset($Teacher->user_id)) {
        if (Auth::user()->user_id != $Teacher->user_id) {
            echo '<h5 class="text-danger">Unauthorized access.</h5>';
            exit();
        }
    } else {
        echo '<h5 class="text-danger">Unauthorized access.</h5>';
        exit();
    }
} else if (!in_array(Auth::user()->role_id, $RolesID)) {
    echo '<h5 class="text-danger">Unauthorized access.</h5>';
    exit();
}
?>


<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">Students</h6>
    </div>
    <div class="panel-body">

        @include('Layouts.FormValidationErrors')
        @include('Layouts.ErrorSuccessAndWarninMessages')

        <form class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label text-danger">Teacher</label>
                <div class="col-sm-10">
                    <p class="form-control-static">{{ (isset($Teacher->school_provided_teacher_id) ? $Teacher->school_provided_teacher_id : '') . ' --- ' . (isset($Teacher->full_name) ? $Teacher->full_name : '') }}</p>
                </div>
            </div>
        </form>



    </div>
    <form>
        <div class="table-responsive">
            <table class="table table-striped table-condensed table-bordered table-hover" id="Table_GetStudentsForAttendance">
                <thead>
                    <tr>
                        <!--<th>Sl.</th>-->
                        <th>Roll No.</th>
                        <th>

                            <?php
                            if (isset($Students)) {
                                ?>

                                <button type="button" class="btn btn-primary btn-xs" id="SelectUnselectAllCheckbox"><i class="fa fa-check-square" id="Select"></i>&nbsp;Select All</button>

                                <?php
                            }
                            if (isset($AttendanceRecords)) {
                                ?>
                                Status
                                <?php
                            }
                            ?>

                        </th>
                        <!--<th>Status</th>-->
                        <th>StudentID</th>
                        <th>Student Name</th>
                        
                        <th>Contact For SMS</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($Students))

                <input type="hidden" name="Branch" value="{{ $Branch or '' }}">
                <input type="hidden" name="Class" value="{{ $Class or '' }}">
                <input type="hidden" name="Group" value="{{ $Group or '' }}">
                <input type="hidden" name="Section" value="{{ $Section or '' }}">
                <input type="hidden" name="Teacher" value="{{ (isset($Teacher->user_id) ? $Teacher->user_id : '') }}">

                <?php $Counter = 1; ?>
                @foreach($Students as $s)

                <tr>
                    <!--<td>{{ $Counter }}</td>-->
                    <td>{{ $s->roll_no }}</td>
                    <td>
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" id="checkbox{{ $s->student_id }}" name="AttendedStudentID[]" value="{{ $s->student_id }}">
                            <label for="checkbox{{ $s->student_id }}">
                                <span class="label label-danger">Absent</span>
                            </label>
                        </div>
                        <!--<span class="label label-danger">Absent</span>-->
                    </td>
                    <!--<td class="PresentAbsentStatus"></td>-->
                    <td>{{ $s->system_generated_student_id }}</td>
                    <td>
                        {{ $s->student_name }}
                        <input type="hidden" name="StudentName[]" value="{{ $s->student_name }}">
                        <input type="hidden" name="StudentID[]" value="{{ $s->student_id }}">
                        <input type="hidden" name="PhoneNo[]" value="{{ $s->contact_for_sms }}">
                    </td>
                    
                    <td>{{ $s->contact_for_sms }}</td>
                </tr>
                <?php $Counter++; ?>
                @endforeach

                @endif

                @if(isset($AttendanceRecords))

                <?php $Counter = 1; ?>
                @foreach($AttendanceRecords as $s)

                <tr>
                    <td>{{ $Counter }}</td>
                    <td>
                        <span class="label label-{{ $s->status == 'P' ? 'success' : 'danger' }}">{{ $s->status == 'P' ? 'Present' : 'Absent' }}</span>
                    </td>
                    <td>{{ $s->system_generated_student_id }}</td>
                    <td>
                        {{ $s->student_name }}
                    </td>
                    <td>{{ $s->roll_no }}</td>
                    <td>{{ $s->contact_for_sms }}</td>
                </tr>
                <?php $Counter++; ?>
                @endforeach

                @endif
                </tbody>
                @if(isset($Students))
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <button type="submit" class="btn btn-block btn-primary btn-sm"><i class="fa fa-floppy-o"></i> Save</button>
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </form>
</div>


