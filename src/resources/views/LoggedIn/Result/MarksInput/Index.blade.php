@extends('Layouts.Application')

@section('MainContent')

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Marks Input</h3>
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
                        <label for="Section" class="col-sm-1 control-label text-danger">Section</label>
                        <div class="col-sm-2">
                            <select class="form-control Section" id="Section" name="Section">
                                <option value="">----- Section -----</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-group form-group-sm">
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
                        <label for="ExamHead" class="col-sm-1 control-label text-danger">Exam Head</label>
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
                        <label for="Students" class="col-sm-1 control-label text-danger">Student ID</label>
                        <div class="col-sm-2">
                            <select class="form-control Students" id="Students" name="Students">
                                <option value="">----- Students -----</option>
                            </select>
                        </div>
                        <label for="Subject" class="col-sm-1 control-label text-danger">Subject</label>
                        <div class="col-sm-2">
                            <select class="form-control Subject" id="Subject" name="Subject">
                                <option value="">----- Subject -----</option>
                            </select>
                        </div>
<!--                        <label for="ExamSubHead" class="col-sm-2 control-label text-danger">Exam Sub Head</label>
                        <div class="col-sm-2">
                            <select class="form-control ExamSubHead" id="ExamSubHead" name="ExamSubHead">
                                <option value="">----- Exam Sub Head -----</option>
                                @if(isset($ExamHeads))
                                @foreach($ExamHeads as $g)
                                <option value="{{ $g->exam_head_id }}">{{ $g->exam_head_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>-->
                        
                    </div>
<!--                    <div class="form-group form-group-sm">
                        
                    </div>-->
                    <div class="form-group form-group-sm">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-primary btn-block" id="ShowMarksInputBtn"><i class="fa fa-braille"></i>&nbsp;Marks Input</button>
                        </div>
                    </div>
                </form>
                <div id="ShowMarksInput"></div>

            </div>
        </div>



    </div>

</div>

<script type="text/javascript">

    $(document).ready(function () {
        
        $("#Students").select2();
        
        $(document).on('change', '#Group, #Class, #Branch', function () {

            var Class = $('#Class').val() || 0;
            var TeacherID = '{{ Auth::user()->user_id }}';
            var Group = $('#Group').val() || 0;
            var Branch = $('#Branch').val() || 0;

            if (Class && Class > 0 && TeacherID && TeacherID > 0 && Group && Group > 0) {
                $.ajax({
                    type: 'GET',
                    url: baseURL + '/!/Result/MarksInput/GetSectionsByClassIDTeacherIDAndGroupID/' + Class + '/' + TeacherID + '/' + Group + '/' + Branch,
                    success: function (response) {
                        $('#Section').html(response);
                    }
                });
            }
        });


$(document).on('change', '#Section', function () {

            var Branch = $('#Branch').val() || 0;
            var Class = $('#Class').val() || 0;
            var Group = $('#Group').val() || 0;
            var TeacherID = '{{ Auth::user()->user_id }}';
            var Section = $('#Section').val() || 0;

            if (Class && Class > 0 && TeacherID && TeacherID > 0 && Group && Group > 0 && Branch && Branch > 0 && Section && Section > 0) {
                $.ajax({
                    type: 'GET',
                    url: baseURL + '/!/Result/MarksInput/GetSubjectsByClassIDTeacherIDAndGroupID/' + Branch + '/' +   Class + '/' + TeacherID + '/' + Group + '/' + Section,
                    success: function (response) {
                        $('#Subject').html(response);
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: baseURL + '/!/Result/MarksInput/GetStudentsByClassIDAndGroupIDAndSectionIDAndIbvID/' + Branch + '/' +   Class + '/' + Group + '/' + Section,
                    success: function (response) {
                        $('#Students').html(response);
                    }
                });
            }
        });

//        $(document).on('change', '#ExamType, #ExamHead', function () {
//
//            var Branch = $('#Branch').val() || 0;
//            var Class = $('#Class').val() || 0;
//            var Group = $('#Group').val() || 0;
//            var TeacherID = '{{ Auth::user()->user_id }}';
//            var Section = $('#Section').val() || 0;
//            var ExamType = $('#ExamType').val() || 0;
//            var ExamHead = $('#ExamHead').val() || 0;
//
//            if (Class && Class > 0 && TeacherID && TeacherID > 0 && Group && Group > 0 && Branch && Branch > 0 && Section && Section > 0 && ExamType && ExamType > 0 && ExamHead && ExamHead > 0) {
//                $.ajax({
//                    type: 'GET',
//                    url: baseURL + '/!/Result/MarksInput/GetExamSubHeadsByClassIDTeacherIDAndGroupIDExamTypeExamHeadSection/' + Branch + '/' +   Class + '/' + TeacherID + '/' + Group + '/' + Section + '/' + ExamType + '/' + ExamHead,
//                    success: function (response) {
//                        $('#ExamSubHead').html(response);
//                    }
//                });
//            }
//        });
        
        $(document).on('click', '#ShowMarksInputBtn', function () {

//            var Branch = $('#Branch').val() || 0;
//            var Class = $('#Class').val() || 0;
//            var Group = $('#Group').val() || 0;
//            var ExamType = $('#ExamType').val() || 0;
//            var Subject = $('#Subject').val() || 0;
//            if (Branch > 0 && Class > 0 && Group > 0 && ExamType > 0 && Subject > 0) {
            $.ajax({
                type: "POST",
                url: baseURL + '/!/Result/MarksInput/Show',
                data: $('#ShowMarksInputFrm').serialize(),
                success: function (response) {
                    //alert(response);
                    $('#ShowMarksInput').html(response);
                }
            });
            return false;
//            }
        });
        
//        $(document).on('submit', 'form', function(){
//            
//            var Frm = $(this).closest('form').serialize();
//            //console.log(Frm);
//            $.ajax({
//                type: "POST",
//                url: baseURL + '/!/Result/MarksInput/Save',
//                data: Frm,
//                success: function (response) {
//                    alert(response);
//                }
//            })
//            return false;
//        });

        
    });
</script>

@endsection