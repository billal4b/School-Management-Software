@extends('Layouts.Application')

@section('MainContent')

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h6 class="panel-title">Class Teacher</h6>
            </div>
            <div class="panel-body">

                @include('Layouts.FormValidationErrors')
                @include('Layouts.ErrorSuccessAndWarninMessages')

                <form class="form-horizontal">
                    {!! csrf_field() !!}

                    <div class="form-group form-group-sm">


                        <label for="Branch" class="col-sm-1 control-label text-danger">Branch</label>
                        <div class="col-sm-11">
                            @if(isset($InstituteBranchVersions))
                            @foreach($InstituteBranchVersions as $ibv)
                            <div class="radio radio-inline radio-primary">
                                <input type="radio" name="Branch" id="Branch{{ $ibv->institute_branch_version_id }}" {{ old('Branch') ? ($ibv->institute_branch_version_id == old('Branch') ? 'checked="checked"' : '' ) : '' }} value="{{ $ibv->institute_branch_version_id }}">
                                       <label for="Branch{{ $ibv->institute_branch_version_id }}">
                                    {{ $ibv->school_branch_name . ', ' . $ibv->school_version_name }}
                                </label>
                            </div>
                            @endforeach
                            @endif

                        </div>

                    </div>

                    <div class="form-group form-group-sm">
                        <label for="TeacherId" class="col-sm-1 control-label text-danger">Teacher</label>
                        <div class="col-sm-4" id="GetTeachersByInstituteBranchVersionID">
                            <select class="form-control" id="TeacherId"  name="TeacherId">
                                <option value="">----- Teacher -----</option>
                            </select>
                        </div>
                    </div>



                </form>



            </div>
        </div>

    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div id="GetAssignedClassTeachersByTeacherID"></div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        $("input[name='Branch']").click(function () {

            var Branch = $('input:radio[name=Branch]:checked').val() || 0;
            if (Branch && Branch > 0) {
                $.ajax({
                    type: "GET",
                    url: baseURL + '/!/Settings/ClassTeacher/GetTeachersByInstituteBranchVersionID/' + Branch,
                    success: function (response) {
                        $('#GetTeachersByInstituteBranchVersionID').html(response);
                        $('#GetAssignedClassTeachersByTeacherID').html('');
                    }
                });
            } else {
                $('#GetTeachersByInstituteBranchVersionID').html('<select class="form-control" id="TeacherId"> <option value="">----- Teacher -----</option></select>');
                $('#GetAssignedClassTeachersByTeacherID').html('');
            }
        });

        $(document).on('change', "#TeacherId", function () {

            var TeacherId = $(this).val() || 0;
            var Branch = $('input:radio[name=Branch]:checked').val() || 0;
            if (TeacherId && TeacherId > 0 && Branch && Branch > 0) {
                $.ajax({
                    type: "GET",
                    url: baseURL + '/!/Settings/ClassTeacher/GetAssignedClassTeachersByTeacherID/' + TeacherId + '/' + Branch,
                    success: function (response) {
                        $('#GetAssignedClassTeachersByTeacherID').html(response);
                    }
                });
            } else {
                $('#GetAssignedClassTeachersByTeacherID').html('');
            }
        });


        $(document).on('submit', 'form', function () {
            $.ajax({
                type: "POST",
                url: baseURL + '/!/Settings/ClassTeacher/AssignedClassTeachersToTeacherID',
                data: $(this).serialize(),
                success: function (response) {
                    $('#GetAssignedClassTeachersByTeacherID').html(response);
//                    alert(response);
//                    return;
                }
            });
            return false;
        });

        $(document).on('change', '#Class', function () {

            var Class = $('#Class').val() || 0;
            var TeacherID = $('#TeacherId').val() || 0;

            if (Class && Class > 0 && TeacherID && TeacherID > 0) {
                $.ajax({
                    type: 'GET',
                    url: baseURL + '/!/Settings/ClassTeacher/GetGroupsByClassIDAndTeacherID/' + Class + '/' + TeacherID,
                    success: function (response) {
                        $('#Groups').html(response);
                    }
                });
            }else{
                $('#Groups').html('<select name="Group" class="form-control" id="Group"><option value="">----- Select ------</option></select>');
            }
        });
        
        $(document).on('change', '#Group', function () {

            var Class = $('#Class').val() || 0;
            var TeacherID = $('#TeacherId').val() || 0;
            var Group = $('#Group').val() || 0;

            if (Class && Class > 0 && TeacherID && TeacherID > 0 && Group && Group > 0) {
                $.ajax({
                    type: 'GET',
                    url: baseURL + '/!/Settings/ClassTeacher/GetSectionsByClassIDTeacherIDAndGroupID/' + Class + '/' + TeacherID + '/' + Group,
                    success: function (response) {
                        $('#Sections').html(response);
                    }
                });
            }else{
                $('#Sections').html('<select name="Section" class="form-control" id="Section"><option value="">----- Select ------</option></select>');
            }
        });

        $(document).on('click', '.UpdateStatus', function () {
            var param = $(this).attr('id');
            if (param) {
                $.ajax({
                    type: 'POST',
                    url: baseURL + '/!/Settings/ClassTeacher/UpdateStatus',
                    data: {'param' : param},
                    success: function (response) {
                        $('#GetAssignedClassTeachersByTeacherID').html(response);
                    }
                });
            }
        });
    });
</script>

@endsection