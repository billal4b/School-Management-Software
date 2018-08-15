@extends('Layouts.Application')

@section('MainContent')

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Teachers Privilege</h3>
            </div>
            <div class="panel-body">
                <a class="btn btn-primary btn-xs" href="{{ url(route('LoggedIn.Settings.TeacherPrivilege.Create')) }}">
                    <i class="fa fa-plus-circle"></i> Add New
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Contact No.</th>
                            <th>Teacher ID</th>
                            <th>School Branch</th>
                            <th>Version</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(isset($Teachers))
                        @foreach($Teachers as $T)
                        <tr>
                            <td>{{ $T->EmpName }}</td>
                            <td>{{ $T->Mob1 . ', ' . $T->Mob2 . ', ' . $T->EmergencyNo }}</td>
                            <td>{{ $T->UserID }}</td>
                            <td>{{ $T->school_branch_name }}</td>
                            <td>{{ $T->school_version_name }}</td>
                            <td>
                                <a class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="col-md-12">
        @if(isset($Teachers))
        {!! $Teachers->render() !!}
        @endif
    </div>
</div>

@endsection