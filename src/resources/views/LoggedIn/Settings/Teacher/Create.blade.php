@extends('Layouts.Application')

@section('MainContent')

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h6 class="panel-title">Teacher's Details</h6>
            </div>
            <div class="panel-body">
                
                @include('Layouts.FormValidationErrors')
                @include('Layouts.ErrorSuccessAndWarninMessages')

                <form class="form-horizontal" method="post" action="{{ url(route('LoggedIn.Settings.Teacher.Store')) }}">
                    {!! csrf_field() !!}
                    <div class="form-group form-group-sm">
                        <label for="FullName" class="col-sm-1 control-label text-danger">Full Name</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="FullName" name="FullName" placeholder="Full Name" value="{{ old('FullName') }}">
                        </div>
                        <label for="TeacherId" class="col-sm-1 control-label text-danger">Teacher ID</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="TeacherId" name="TeacherId" placeholder="Teacher ID" value="{{ old('TeacherId') }}">
                        </div>
                    </div>

                    <div class="form-group form-group-sm">


                        <label for="Email" class="col-sm-1 control-label">Email</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="Email" name="Email" placeholder="Email" value="{{ old('Email') }}">
                        </div>
                        <label for="Username" class="col-sm-1 control-label">Username</label>
                        <div class="col-sm-3">
                            <p class="form-control-static"></p>
                        </div>
                        <label for="ContactNo" class="col-sm-1 control-label text-danger">Contact No.</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1">+8801</span>
                                <input type="text" class="form-control" id="ContactNo" name="ContactNo" placeholder="Contact No." value="{{ old('ContactNo') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-sm">


                        <label for="Branch" class="col-sm-1 control-label text-danger">Branch</label>
                        <div class="col-sm-11">
                            @if(isset($InstituteBranchVersions))
                            @foreach($InstituteBranchVersions as $ibv)
                            <div class="radio radio-inline radio-primary">
                                <input type="radio" name="Branch" id="Branch{{ $ibv->institute_branch_version_id }}" {{ old('Branch') ? ($ibv->institute_branch_version_id == old('Branch') ? 'checked="checked"' : '' ) : '' }} value="{{ $ibv->institute_branch_version_id }}">
                                       <label for="Branch{{ $ibv->institute_branch_version_id }}">
                                    {{ $ibv->school_branch_name . ', ' . $ibv->school_version_name }}
                                </label>
                            </div>
                            @endforeach
                            @endif
                            
                        </div>
               
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary btn-sm btn-block"><i class="fa fa-floppy-o"></i>&nbsp;Save</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection