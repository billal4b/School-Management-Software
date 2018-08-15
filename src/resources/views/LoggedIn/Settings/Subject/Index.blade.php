@extends('Layouts.Application')

@section('MainContent')


@include('LoggedIn.SubMenus')

<div class="row">

    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Subjects</h3>
            </div>
            <div class="panel-body">
                <a class="btn btn-primary btn-xs" href="{{ url(route('LoggedIn.Settings.Subject.Create')) }}">
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
                    <div class="form-group form-group-sm">
                        <label for="Class">Class :</label>
                        <select class="form-control" id="Class" name="Class">
                            <option value="">----- Class -----</option>
                                @if(isset($Classes))
                                @foreach($Classes as $c)
                                <option value="{{ $c->id }}">{{ $c->ClassName }}</option>
                                @endforeach
                                @endif
                        </select>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="Group">Group :</label>
                        <select class="form-control Group" id="Group" name="Group">
                                <option value="">----- Group -----</option>
                                @if(isset($Groups))
                                @foreach($Groups as $g)
                                <option value="{{ $g->id }}">{{ $g->GroupName }}</option>
                                @endforeach
                                @endif
                            </select>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" id="ShowSubjectsByBranchIDAndClassIDAndGroupID"><i class="fa fa-eye"> Show</i></button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered table-hover tablesorter" id="CommonTable">
                    <thead>
                        <tr>
                            <th class="header text-center">Sl.</th>
                            <th class="header text-center">Subject Name</th>
                            <th class="header text-center">Subject Code</th>
                            <th class="header text-center">Class</th>
                            <th class="header text-center">Group</th>
                            <th class="header text-center">Branch</th>
                            <th class="header text-center">Version</th>
                            <th class="text-center">Status</th>
                            <th class="header text-center">Link</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(isset($Subjects))
                        <?php $sl = 1; ?>
                        @foreach($Subjects as $S)
                        <tr>
                            <td>{{ $sl++ }}</td>
                            <td>{{ $S->subject_name }}</td>
                            <td>{{ $S->subject_code }}</td>
                            <td>{{ $S->ClassName }}</td>
                            <td>{{ $S->GroupName }}</td>
                            <td>{{ $S->school_branch_name }}</td>
                            <td>{{ $S->school_version_name }}</td>
                            <td>{!! $S->is_active == 1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>' !!}</td>
                            <td>
                                <a class="btn btn-primary btn-xs" href="{{ url(route('LoggedIn.Settings.Subject.Edit', array('id' => $S->subject_id ))) }}"><i class="fa fa-edit"></i> Edit</a>
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



<script>
    $(document).ready(function () {
        $("#CommonTable").DataTable();
//        $(document).on('dblclick', '.EditSubjectCodeInline', function(){
//           
//           var ID = $(this).attr('id');
//           alert($(this).text());
//        });

        $(document).on('click', '#ShowSubjectsByBranchIDAndClassIDAndGroupID', function () {
            var Branch = $('#Branch').val();
            var Class = $('#Class').val();
            var Group = $('#Group').val();
            if (Branch && Class && Group) {
                window.location.href = baseURL + '/!/Settings/Subject/ShowSubjectsByBranchIDAndClassIDAndGroupID/' + Branch + '/' + Class + '/' + Group;
            }
        })
    });
</script>

@endsection