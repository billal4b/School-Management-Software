@extends('Layouts.Application')

@section('MainContent')


<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong> Students Info </strong></div>

            <div class="panel-body">
                @include('Layouts.FormValidationErrors')
                @include('Layouts.ErrorSuccessAndWarninMessages')

                <form class="form-horizontal" role="form" id='students_view_form' onsubmit="return false;" >
                    {{ csrf_field() }}			

                    <div class="form-group form-group-sm">				
                        <label for="branch_and_version" class="col-sm-1 control-label text-danger">Branch </label>
                        <div class="col-sm-2">
                            <select id="branch_and_version" name="branch_and_version" class="form-control">
                                <option value="">----- Select -----</option>
                                @if(isset($InstituteBranchVersions))
                                @foreach($InstituteBranchVersions as $hds)
                                <option value="{{ $hds->institute_branch_version_id }}">{{ $hds->school_branch_name }},
                                    {{ $hds->school_version_name }} </option>
                                @endforeach
                                @endif
                            </select>
                        </div>	
                        <label for="class_name" class="col-sm-1 control-label text-danger">Class </label>
                        <div class="col-sm-2">
                            <select id="class_name" name="class_name" class="form-control">
                                <option value="">--- Select ---</option>
                                @if(isset($classWise))
                                @foreach($classWise as $hds)
                                <option value="{{ $hds->id }}">{{ $hds->ClassName }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>	

                        <label for="group_name" class="col-sm-1 control-label text-danger">Group </label>
                        <div class="col-sm-2">
                            <select id="group_name" name="group_name" class="form-control">
                                <option value="">--- Select ---</option>
                                @if(isset($groupWise))
                                @foreach($groupWise as $hds)
                                <option value="{{ $hds->id }}">{{ $hds->GroupName }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <label for="section_name" class="col-sm-1 control-label text-danger">Section </label>
                        <div class="col-sm-2">
                            <select id="section_name" name="section_name" class="form-control">
                                <option value="">--- Select ---</option>                              
                            </select>
                        </div>


                    </div>
                    <div class="form-group form-group-sm">								


                        <div class="col-sm-12">
                            <button type="button" id='students_view_submit' class="btn btn-primary btn-sm btn-block">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                Search 
                            </button>
                        </div>
                    </div>						
                </form>				


            </div>




            <div id="students_view_table"></div>				
        </div>			
    </div>
</div>

<script>

    $(function () {

        $(document).on('click', '#students_view_submit', function () {
            //alert('hi');return false;
            var branch_and_version = $('#branch_and_version').val();
            var class_name = $('#class_name').val();
            var section_name = $('#section_name').val();
            var group_name = $('#group_name').val();

            $.ajax({
                url: baseURL + '/!/Settings/Student/View',
                // url    : baseURL + 'AuthenticatedUser/AppSetup/StudentsView/View' ,
                type: 'POST',
                data: $('#students_view_form').serialize(),
                success: function (response) {
                    $('#students_view_table').html(response);
                }

            });
        });

        // ------ section ------  //

        $(document).on('change', '#branch_and_version, #class_name', function () {

            var class_name = $('#class_name').val();
            var branch_and_version = $('#branch_and_version').val();

            //alert(branch_and_version);return false;

            if (class_name && branch_and_version) {
                $.ajax({
                    url: baseURL + '/!/Settings/Student/' + branch_and_version + '/' + class_name,
                    type: 'GET',
                    success: function (response) {
                        $('#section_name').html(response);
                    }
                });
            } else {
                $('#section_name').html('<option value="">----- Select -----</option>');
            }
        });
    });


    $(document).ready(function () {
        $('#myTable').DataTable();
    });


</script>
@endsection