@extends('Layouts.Application')

@section('MainContent')

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h6 class="panel-title">Teacher's Privilege</h6>
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
                        <div class="col-sm-4">
                            <!--<a class="btn btn-primary btn-sm" id="Print"><i class="fa fa-print"></i>&nbsp;Print</a>-->
                        </div>
                    </div>



                </form>



            </div>
        </div>

    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div id="GetAssignedSubjectsByTeacherID"></div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

$("#TeacherId").select2();

        $("input[name='Branch']").click(function () {

            var Branch = $('input:radio[name=Branch]:checked').val() || 0;
            if (Branch && Branch > 0) {
                $.ajax({
                    type: "GET",
                    url: baseURL + '/!/Settings/TeacherPrivilege/GetTeachersByInstituteBranchVersionID/' + Branch,
                    success: function (response) {
                        $('#GetTeachersByInstituteBranchVersionID').html(response);
                        $('#GetAssignedSubjectsByTeacherID').html('');
                    }
                });
            } else {
                $('#GetTeachersByInstituteBranchVersionID').html('<select class="form-control" id="TeacherId"> <option value="">----- Teacher -----</option></select>');
                $('#GetAssignedSubjectsByTeacherID').html('');
            }
        });

        $(document).on('change', "#TeacherId", function () {

            var TeacherId = $(this).val() || 0;
            var Branch = $('input:radio[name=Branch]:checked').val() || 0;
            if (TeacherId && TeacherId > 0 && Branch && Branch > 0) {
                $.ajax({
                    type: "GET",
                    url: baseURL + '/!/Settings/TeacherPrivilege/GetAssignedSubjectsByTeacherID/' + TeacherId + '/' + Branch,
                    success: function (response) {
                        $('#GetAssignedSubjectsByTeacherID').html(response);
                    }
                });
            } else {
                $('#GetAssignedSubjectsByTeacherID').html('');
            }
        });


        $(document).on('submit', 'form', function () {
            $.ajax({
                type: "POST",
                url: baseURL + '/!/Settings/TeacherPrivilege/AssignedSubjectsToTeacherID',
                data: $(this).serialize(),
                success: function (response) {
                    $('#GetAssignedSubjectsByTeacherID').html(response);
//                    alert(response);
//                    return;
                }
            });
            return false;
        });

        $(document).on('change', '#Class, #Group', function () {

            var Class = $('#Class').val() || 0;
            var Group = $('#Group').val() || 0;
            var Branch = $('#Branch').val() || 0;

            if (Class && Group && Class > 0 && Group > 0 && Branch && Branch > 0) {
                $.ajax({
                    type: 'GET',
                    url: baseURL + '/!/Settings/TeacherPrivilege/GetSectionsByClassIDAndGroupIDAndIbvID/' + Class + '/' + Group + '/' + Branch,
                    success: function (response) {
                        $('#Sections').html(response);
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: baseURL + '/!/Settings/TeacherPrivilege/GetSubjectsByClassIDAndGroupIDAndIbvID/' + Class + '/' + Group + '/' + Branch,
                    success: function (response) {
                        $('#Subjects').html(response);
                    }
                });
            }
        });

        $(document).on('click', '.UpdateStatus', function () {
            var param = $(this).attr('id');
            if (param) {
                $.ajax({
                    type: 'POST',
                    url: baseURL + '/!/Settings/TeacherPrivilege/UpdateStatus',
                    data: {'param' : param},
                    success: function (response) {
                        $('#GetAssignedSubjectsByTeacherID').html(response);
                    }
                });
            }
        });
    });
</script>

@endsection
