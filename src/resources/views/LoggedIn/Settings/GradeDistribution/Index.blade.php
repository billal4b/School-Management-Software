@extends('Layouts.Application')

@section('MainContent')


@include('LoggedIn.SubMenus')

<div class="row">

    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Grade Distribution</h3>
            </div>
            <div class="panel-body">


                <form class="form-horizontal"  id="ShowGradeDistributionFrm">

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
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-primary btn-sm" id="ShowGradeDistributionBtn"><i class="fa fa-braille"></i>&nbsp;Show Grade Distribution</button>
                        </div>
                    </div>

                    </div>

                    <div class="form-group form-group-sm">
                        
                </form>

                <div id="ShowGradeDistribution"></div>
            </div>



        </div>

    </div>
</div>



<script>
    $(document).ready(function () {
        $(document).on('click', '#ShowGradeDistributionBtn', function () {

            var Branch = $('#Branch').val() || 0;
            var Class = $('#Class').val() || 0;
            var Group = $('#Group').val() || 0;
//            var ExamType = $('#ExamType').val() || 0;
//            var Subject = $('#Subject').val() || 0;
//            if (Branch > 0 && Class > 0 && Group > 0 && ExamType > 0 && Subject > 0) {
            $.ajax({
                type: "POST",
                url: baseURL + '/!/Settings/GradeDistribution/Show',
                data: $('#ShowGradeDistributionFrm').serialize(),
                success: function (response) {
                    //alert(response);
                    $('#ShowGradeDistribution').html(response);
                }
            });
            return false;
//            }
        });
    });
</script>

@endsection