@extends('Layouts.Application')

@section('MainContent')

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h6 class="panel-title">Marks Distribution's Details</h6>
            </div>
            <div class="panel-body">

                @include('Layouts.FormValidationErrors')
                @include('Layouts.ErrorSuccessAndWarninMessages')

                <form class="form-horizontal" method="post" action="{{ url(route('LoggedIn.Settings.MarksDistribution.Store')) }}">

                    {!! csrf_field() !!}

                    <div class="form-group form-group-sm">


                        <label for="Branch" class="col-sm-1 control-label text-danger">Branch</label>
                        <div class="col-sm-2">
                            <select class="form-control" id="Branch" name="Branch">
                                <option value="">----- Branch -----</option>
                                @if(isset($InstituteBranchVersions))
                                @foreach($InstituteBranchVersions as $ibv)
                                <option value="{{ $ibv->institute_branch_version_id }}">{{ $ibv->school_branch_name . ', ' . $ibv->school_version_name }}</option>

                                @endforeach
                                @endif
                            </select>

                        </div>

                        <label for="Class" class="col-sm-1 control-label text-danger">Class</label>
                        <div class="col-sm-2">
                            <select class="form-control Class" id="Class" name="Class">
                                <option value="">----- Class -----</option>
                                @if(isset($Classes))
                                @foreach($Classes as $c)
                                <option value="{{ $c->id }}">{{ $c->ClassName }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <label for="Group" class="col-sm-1 control-label text-danger">Group</label>
                        <div class="col-sm-2">
                            <select class="form-control Group" id="Group" name="Group">
                                <option value="">----- Group -----</option>
                                @if(isset($Groups))
                                @foreach($Groups as $g)
                                <option value="{{ $g->id }}">{{ $g->GroupName }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <label for="ExamType" class="col-sm-1 control-label text-danger">Exam Type</label>
                        <div class="col-sm-2">
                            <select class="form-control ExamType" id="ExamType" name="ExamType">
                                <option value="">----- Exam Type -----</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-group form-group-sm">
                        <label for="Subject" class="col-sm-1 control-label text-danger">Subject</label>
                        <div class="col-sm-2">
                            <select class="form-control Subject" id="Subject" name="Subject">
                                <option value="">----- Subject -----</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-primary btn-sm btn-block" id="ShowMarksDistributionBtn"><i class="fa fa-braille"></i>&nbsp;Marks Distribution</button>
                        </div>
                    </div>
                </form>

            </div>

            <div id="ShowMarksDistribution"></div>

        </div>

    </div>
</div>





<script>
    $(document).ready(function () {
        $(document).on('change', '#Group, #Class', function () {

            var Branch = $('#Branch').val() || 0;
            var Class = $('#Class').val() || 0;
            var Group = $('#Group').val() || 0;
            if (Branch > 0 && Class > 0 && Group > 0) {
                $.ajax({
                    type: "GET",
                    url: baseURL + '/!/Settings/MarksDistribution/GetExamTypesByBranchAndClassAndGroupId/' + Branch + '/' + Class + '/' + Group,
                    success: function (response) {
                        //alert(response);
                        $('#ExamType').html(response);
                    }
                });
            }
        });

        $(document).on('click', '#ShowMarksDistributionBtn', function () {

            var Branch = $('#Branch').val() || 0;
            var Class = $('#Class').val() || 0;
            var Group = $('#Group').val() || 0;
            var ExamType = $('#ExamType').val() || 0;
            var Subject = $('#Subject').val() || 0;
            if (Branch > 0 && Class > 0 && Group > 0 && ExamType > 0 && Subject > 0) {
                $.ajax({
                    type: "GET",
                    url: baseURL + '/!/Settings/MarksDistribution/Show/' + Branch + '/' + Class + '/' + Group + '/' + ExamType + '/' + Subject,
                    success: function (response) {
                        //alert(response);
                        $('#ShowMarksDistribution').html(response);
                    }
                });
            }
        });

        $(document).on('change', '#Group, #Class', function () {

            var Branch = $('#Branch').val() || 0;
            var Class = $('#Class').val() || 0;
            var Group = $('#Group').val() || 0;
            if (Branch > 0 && Class > 0 && Group > 0) {
                $.ajax({
                    type: "GET",
                    url: baseURL + '/!/Settings/MarksDistribution/GetSubjectsByBranchAndClassAndGroupId/' + Branch + '/' + Class + '/' + Group,
                    success: function (response) {
                        //alert(response);
                        $('#Subject').html(response);
                    }
                });
            }
        });

        $(document).on('submit', 'form', function () {

            $.ajax({
                type: "POST",
                url: baseURL + '/!/Settings/MarksDistribution',
                data: $(this).serialize(),
                success: function (response) {
                    //alert(response);
                    $('#ShowMarksDistribution').html(response);
                }
            });
            return false;
        });
    });
</script>





@endsection

