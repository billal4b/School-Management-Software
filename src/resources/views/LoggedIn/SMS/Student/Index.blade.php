@extends('Layouts.Application')

@section('MainContent')

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Student SMS</h3>
            </div>
            <div class="panel-body">
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
                        <label for="Class" class="col-sm-1 control-label text-danger">Class</label>
                        <div class="col-sm-3">
                            <select class="form-control" id="Class"  name="Class">
                                <option value="">----- Class -----</option>
                                <?php
                                if (isset($Classes)) {
                                    foreach ($Classes as $c) {
                                        echo '<option value="' . $c->id . '">' . $c->ClassName . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <label for="Group" class="col-sm-1 control-label text-danger">Group</label>
                        <div class="col-sm-3">
                            <select class="form-control" id="Group"  name="Group">
                                <option value="">----- Group -----</option>
                                <?php
                                if (isset($Groups)) {
                                    foreach ($Groups as $g) {
                                        echo '<option value="' . $g->id . '">' . $g->GroupName . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <label for="Section" class="col-sm-1 control-label text-danger">Section</label>
                        <div class="col-sm-3" id="Sections">
                            <select class="form-control" id="Section"  name="Section">
                                <option value="">----- Section -----</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-sm">
                        <div class="col-sm-12">
                            <button class="btn btn-primary btn-block" type="button" id="ShowStudentsForSMS"><i class="fa fa-list"></i>&nbsp;View Students</button>
                        </div>

                    </div>



                </form>
            </div>

        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="GetStudentsForSMSByClassIDAndGroupIDAndIbvIDAndSectionID"></div>
    </div>
</div>


<script>
    $(document).ready(function () {

        $(document).on('change', '#Class', function () {

            var Class = $('#Class').val() || 0;
//            var Branch = $('#Branch').val() || 0;
            var Branch = $('input:radio[name=Branch]:checked').val() || 0;
//
//            alert(Branch);
//            return;

            if (Branch && Branch > 0 && Class && Class > 0) {
                $.ajax({
                    type: 'GET',
                    url: baseURL + '/!/SMS/Student/GetSectionsByClassIDAndGroupIDAndIbvID/' + Branch + '/' + Class,
                    success: function (response) {
                        $('#Sections').html(response);
                    }
                });
            }
        });

        $(document).on('click', '#ShowStudentsForSMS', function () {

            var Class = $('#Class').val() || 0;
            var Branch = $('input:radio[name=Branch]:checked').val() || 0;
            var Group = $('#Group').val() || 0;
            var Section = $('#Section').val() || 0;

            if (Branch && Branch > 0 && Class && Class > 0 && Group && Group > 0 && Section && Section > 0) {
                $.ajax({
                    type: 'GET',
                    url: baseURL + '/!/SMS/Student/GetStudentsForSMSByClassIDAndGroupIDAndIbvIDAndSectionID/' + Branch + '/' + Class + '/' + Group + '/' + Section,
                    success: function (response) {
                        $('#GetStudentsForSMSByClassIDAndGroupIDAndIbvIDAndSectionID').html(response);
                    }
                });
            }
        });

        $(document).on('click', '#SelectUnselectAllCheckbox', function () {

            var String = $(this).find('i').attr('id');
            var Button = (String == 'Select') ? '<i class="fa fa-minus-square" id="Unselect"></i>&nbsp;Unselect All' : '<i class="fa fa-check-square" id="Select"></i>&nbsp;Select All';
            //alert(Button);
            $(this).html(Button);
            //alert(String);
            var checkedStatus = (String == 'Select') ? true : false;
            $('#Table_GetStudentsForSMS tbody tr').find('td:nth-child(2) :checkbox').each(function () {
                $(this).prop('checked', checkedStatus);
            });
        });

        $(document).on('submit', 'form', function () {

            $.ajax({
                type: 'POST',
                url: baseURL + '/!/SMS/Student/SendSMS',
                data: $('#SMSBodyForm, #GetStudentsForSMSForm').serialize(),
                success: function (response) {
                    $('#GetStudentsForSMSByClassIDAndGroupIDAndIbvIDAndSectionID').html(response);
                }
            });
            return false;
        });
    });
</script>

@endsection