@extends('Layouts.Application')

@section('MainContent')

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Teachers</h3>
            </div>
            <div class="panel-body">
                <a class="btn btn-primary btn-xs" href="{{ url(route('LoggedIn.Settings.Teacher.Create')) }}">
                    <i class="fa fa-plus-circle"></i> Add New
                </a>
                &nbsp;&nbsp;<br>&nbsp;
                <form class="form-inline">
                    <div class="form-group form-group-sm">
                        <label for="Branch">Branch :</label>
                        <select class="form-control" id="Branch" name="Branch">
                            <option value="">----- Branch -----</option>
                            @if(isset($InstituteBranchVersions))
                            @foreach($InstituteBranchVersions as $ibv)
                            <option value="{{ $ibv->institute_branch_version_id }}">{{ $ibv->school_branch_name . ', ' . $ibv->school_version_name }}</option>

                            @endforeach
                            @endif
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" id="PrintBranchTeachers"><i class="fa fa-print"> Print</i></button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered table-hover tablesorter" id="CommonTable">
                    <thead>
                        <tr>
                            <th class="header text-center">Teacher ID</th>
                            <th class="header text-center">Full Name</th>
                            <th class="header text-center">Contact No.</th>
                            <th class="header text-center">School Branch</th>
                            <th class="header text-center">Version</th>
                            <th class="text-center">Link</th>
                            <th class="header text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(isset($Teachers))
                        @foreach($Teachers as $T)
                        <tr>
                            <td>{{ $T->username }}</td>
                            <td>{{ $T->full_name }}</td>
                            <td>{{ $T->phone_no }}</td>
                            <td>{{ $T->school_branch_name }}</td>
                            <td>{{ $T->school_version_name }}</td>
                            <td>
                                <a class="btn btn-primary btn-xs" href="{{ url(route('LoggedIn.Settings.Teacher.Edit', array('id' => $T->user_id ))) }}"><i class="fa fa-edit"></i> Edit</a>
                            </td>
                            <td>
                                <form role="form" class="form-horizontal" action="{{ url(route('LoggedIn.Settings.Teacher.Delete',
                                array('id' => $T->user_id ))) }}"  onclick="return checkDelete()" method="POST">
                                    {!! csrf_field() !!}
                                    {{ method_field('DELETE') }}
                                    <input type="hidden" name="id" value="{{ $T->user_id }}">
                                    <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>



<script language="JavaScript" type="text/javascript">

    function checkDelete() {
        return confirm('Are you sure?');
    }

    $(document).ready(function () {
        $("#CommonTable").DataTable();
        
        $(document).on('click', '#PrintBranchTeachers', function(){
            
            var Branch = $('#Branch').val();
            if(Branch){
               window.open(baseURL + '/!/Settings/Teacher/PrintBranchWiseTeachers/' + Branch);
            }
        });
    });
</script>

@endsection