@extends('Layouts.Application')

@section('MainContent')


@include('LoggedIn.SubMenus')

<div class="row">

    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Marks Distribution</h3>
            </div>
            <div class="panel-body">


                <form class="form-horizontal"  id="ShowMarksDistributionFrm">

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
                        <!--                    <label for="Section" class="col-sm-1 control-label text-danger">Section</label>
                                            <div class="col-sm-2">
                                                <select class="form-control Section" id="Section" name="Section">
                                                    <option value="">----- Section -----</option>
                                                </select>
                                            </div>-->

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

                        <div class="col-sm-4">
                            <button type="button" class="btn btn-primary btn-sm" id="ShowMarksDistributionBtn"><i class="fa fa-braille"></i>&nbsp;Show Marks Distribution</button>
                        </div>
                    </div>
                </form>

                <div id="ShowMarksDistribution"></div>
            </div>



        </div>

    </div>
</div>



<script>
    $(document).ready(function () {
        $("#CommonTable").DataTable();
//        $(document).on('dblclick', '.EditSubjectCodeInline', function(){
//           
//           var ID = $(this).attr('id');
//           alert($(this).text());
//        });

//        $(document).on('change', '#Group, #Class, #Branch', function () {
//
//            var Branch = $('#Branch').val() || 0;
//            var Class = $('#Class').val() || 0;
//            var Group = $('#Group').val() || 0;
//            if (Branch > 0 && Class > 0 && Group > 0) {
//                $.ajax({
//                    type: "GET",
//                    url: baseURL + '/!/Settings/MarksDistribution/GetSectionsByBranchIdAndClassIdAndGroupId/' + Branch + '/' + Class + '/' + Group,
//                    success: function (response) {
//                        //alert(response);
//                        $('#Section').html(response);
//                    }
//                });
//            }
//        });

        $(document).on('click', '#ShowMarksDistributionBtn', function () {

//            var Branch = $('#Branch').val() || 0;
//            var Class = $('#Class').val() || 0;
//            var Group = $('#Group').val() || 0;
//            var ExamType = $('#ExamType').val() || 0;
//            var Subject = $('#Subject').val() || 0;
//            if (Branch > 0 && Class > 0 && Group > 0 && ExamType > 0 && Subject > 0) {
            $.ajax({
                type: "POST",
                url: baseURL + '/!/Settings/MarksDistribution/Show',
                data: $('#ShowMarksDistributionFrm').serialize(),
                success: function (response) {
                    //alert(response);
                    $('#ShowMarksDistribution').html(response);
                }
            });
            return false;
//            }
        });
        
        $(document).on('submit', 'form', function(){
            
            var Frm = $(this).closest('form').serialize();
            //console.log(Frm);
            $.ajax({
                type: "POST",
                url: baseURL + '/!/Settings/MarksDistribution/Save',
                data: Frm,
                success: function (response) {
                    //alert(response);
                }
            })
            return false;
        });
    });
</script>

@endsection