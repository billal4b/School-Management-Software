@extends('Layouts.Application')

@section('MainContent')

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Defaulted List</h3>
            </div>
            <div class="panel-body">

                @include('Layouts.FormValidationErrors')
                @include('Layouts.ErrorSuccessAndWarninMessages')

                <form class="form-horizontal" id="ShowMarksInputFrm">

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

                        <label for="ExamType" class="col-sm-1 control-label text-danger">Exam Type</label>
                        <div class="col-sm-2">
                            <select class="form-control ExamType" id="ExamType" name="ExamType">
                                <option value="">----- Exam Type -----</option>
                                @if(isset($ExamTypes))
                                @foreach($ExamTypes as $g)
                                <option value="{{ $g->exam_type_id }}">{{ $g->exam_type_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <label for="ExamHead" class="col-sm-1 control-label">Exam Head</label>
                        <div class="col-sm-2">
                            <select class="form-control ExamHead" id="ExamHead" name="ExamHead">
                                <option value="">----- Exam Head -----</option>
                                @if(isset($ExamHeads))
                                @foreach($ExamHeads as $g)
                                <option value="{{ $g->exam_head_id }}">{{ $g->exam_head_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <label for="ExamSubHead" class="col-sm-1 control-label">Exam Sub Head</label>
                        <div class="col-sm-2" id="GetExamSubHeadsByExamHeadID">
                            <select class="form-control ExamSubHead" id="ExamSubHead" name="ExamSubHead">
                                <option value="">----- Exam Sub Head -----</option>
                                @if(isset($ExamSubHeads))
                                @foreach($ExamSubHeads as $g)
                                <option value="{{ $g->exam_sub_head_id }}">{{ $g->exam_sub_head_alias }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                    </div>

<!--                    <div class="form-group form-group-sm">
                        <div class="form-group form-group-sm">
                        <label for="TeacherId" class="col-sm-1 control-label text-danger">Teacher</label>
                        <div class="col-sm-4" id="GetTeachersByInstituteBranchVersionID">
                            <select class="form-control" id="TeacherId"  name="TeacherId">
                                <option value="">----- Teacher -----</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <a class="btn btn-primary btn-sm" id="Print"><i class="fa fa-print"></i>&nbsp;Print</a>
                        </div>
                    </div>
                    </div>-->
                    
                    <div class="form-group form-group-sm">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-primary btn-block" id="ShowDefaultedListBtn"><i class="fa fa-search"></i>&nbsp;Search</button>
                        </div>
                    </div>
                </form>
                <div id="ShowDefaultedList"></div>

            </div>
        </div>



    </div>

</div>

<script type="text/javascript">

    $(document).ready(function () {
        
//        $("#TeacherId").select2();
        
//        $(document).on('change', '#Branch', function () {
//
//            var Branch = $(this).val() || 0;
//            if (Branch && Branch > 0) {
//                $.ajax({
//                    type: "GET",
//                    url: baseURL + '/!/MarksAnalysis/DefaultedList/GetTeachersByInstituteBranchVersionID/' + Branch,
//                    success: function (response) {
//                        $('#GetTeachersByInstituteBranchVersionID').html(response);
//                    }
//                });
//            } else {
//                $('#GetTeachersByInstituteBranchVersionID').html('<select class="form-control" id="TeacherId"> <option value="">----- Teacher -----</option></select>');
//            }
//        });
        
        $(document).on('change', '#ExamHead', function () {

            var ExamHead = $(this).val() || 0;
            if (ExamHead && ExamHead > 0) {
                $.ajax({
                    type: "GET",
                    url: baseURL + '/!/MarksAnalysis/DefaultedList/GetExamSubHeadsByExamHeadID/' + ExamHead,
                    success: function (response) {
                        $('#GetExamSubHeadsByExamHeadID').html(response);
                    }
                });
            } else {
                $('#GetExamSubHeadsByExamHeadID').html('<select class="form-control" id="ExamSubHead"> <option value="">----- Exam Sub Head -----</option></select>');
            }
        });
        
        
        $(document).on('click', '#ShowDefaultedListBtn', function () {

            var Branch = $('#Branch').val() || 0;
            var ExamType = $('#ExamType').val() || 0;
            var ExamHead = $('#ExamHead').val() || 0;
            var ExamSubHead = $('#ExamSubHead').val() || 0;
//            var TeacherId = $('#TeacherId').val() || 0;
//            var Subject = $('#Subject').val() || 0;
            if (Branch > 0 && ExamType > 0 && ExamHead > 0 && ExamSubHead > 0/* && TeacherId > 0*/) {
            $.ajax({
                type: "POST",
                url: baseURL + '/!/MarksAnalysis/DefaultedList/Show',
                data: $('#ShowMarksInputFrm').serialize(),
                success: function (response) {
                    //alert(response);
                    $('#ShowDefaultedList').html(response);
                }
            });
            return false;
            }
        });
        
    });
</script>

@endsection