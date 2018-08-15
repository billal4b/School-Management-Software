<?php //echo '<pre>';print_r($Teacher);echo '</pre>'; exit(); ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">Students</h6>
    </div>
    <div class="panel-body">

        @include('Layouts.FormValidationErrors')
        @include('Layouts.ErrorSuccessAndWarninMessages')

        <form class="form-horizontal" id="SMSBodyForm">
            <div class="form-group">
                <label class="col-sm-2 control-label text-danger">SMS Body</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="SmsBody" value="{{ old('SmsBody') }}"></textarea>
                </div>
            </div>
        </form>

        

    </div>
    <form id="GetStudentsForSMSForm">
        <div class="table-responsive">
            <table class="table table-striped table-condensed table-bordered table-hover" id="Table_GetStudentsForSMS">
                <thead>
                    <tr>
                        <th>Sl.</th>
                        <th><button type="button" class="btn btn-primary btn-xs" id="SelectUnselectAllCheckbox"><i class="fa fa-check-square" id="Select"></i>&nbsp;Select All</button></th>
                        <th>StudentID</th>
                        <th>Student Name</th>
                        <th>Roll No.</th>
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
                    <td>{{ $Counter }}</td>
                    <td>
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" id="checkbox{{ $s->student_id }}" name="AttendedStudentID[]" value="{{ $s->student_id }}">
                            <label for="checkbox{{ $s->student_id }}">
                                &nbsp;
                            </label>
                        </div>
                    </td>
                    <td>{{ $s->system_generated_student_id }}</td>
                    <td>
                        {{ $s->student_name }}
                        <input type="hidden" name="StudentName[]" value="{{ $s->student_name }}">
                        <input type="hidden" name="StudentID[]" value="{{ $s->student_id }}">
                        <input type="hidden" name="PhoneNo[]" value="{{ $s->contact_for_sms }}">
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


