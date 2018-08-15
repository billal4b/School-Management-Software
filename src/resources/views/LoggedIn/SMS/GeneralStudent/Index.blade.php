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
                            <div class="checkbox checkbox-inline checkbox-primary">
                                <input type="checkbox" name="Branch[]" id="Branch{{ $ibv->institute_branch_version_id }}" value="{{ $ibv->institute_branch_version_id }}">
                                       <label for="Branch{{ $ibv->institute_branch_version_id }}">
                                    {{ $ibv->school_branch_name . ', ' . $ibv->school_version_name }}
                                </label>
                            </div>
                            @endforeach
                            @endif

                        </div>

                    </div>

                    <div class="form-group form-group-sm">
                        <label for="SmsBody" class="col-sm-1 control-label text-danger">SMD Body</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" name="SmsBody" id="SmsBody"  value="{{ old('SmsBody') }}"></textarea>
                        </div>
                    </div>

                    <div class="form-group form-group-sm">
                        <div class="col-sm-12">
                            <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-telegram "></i>&nbsp;Send</button>
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

        $(document).on('submit', 'form', function () {

            $.ajax({
                type: 'POST',
                url: baseURL + '/!/SMS/GeneralStudent/SendSMS',
                data: $(this).serialize(),
                success: function (response) {
//                    alert(response);
                    $('#GetStudentsForSMSByClassIDAndGroupIDAndIbvIDAndSectionID').html(response);
                }
            });
            return false;
        });
    });
</script>

@endsection