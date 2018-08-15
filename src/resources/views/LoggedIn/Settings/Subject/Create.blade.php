@extends('Layouts.Application')

@section('MainContent')

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h6 class="panel-title">Subject's Details</h6>
            </div>
            <div class="panel-body">

                @include('Layouts.FormValidationErrors')
                @include('Layouts.ErrorSuccessAndWarninMessages')

                <form class="form-horizontal" method="post" action="{{ url(route('LoggedIn.Settings.Subject.Store')) }}">
                    {!! csrf_field() !!}
                    <div class="form-group form-group-sm">
                        <label for="SubjectName" class="col-sm-2 control-label text-danger">Subject Name</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="SubjectName" name="SubjectName" placeholder="Subject Name" value="{{ old('SubjectName') }}">
                        </div>
                        <label for="SubjectCode" class="col-sm-1 control-label">Subject Code</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="SubjectCode" name="SubjectCode" placeholder="Subject Code" value="{{ old('SubjectCode') }}">
                        </div>
                    </div>

                    <div class="form-group form-group-sm">


                        <label for="Class" class="col-sm-2 control-label text-danger">Class</label>
                        <div class="col-sm-10">
                            @if(isset($Classes))
                            @foreach($Classes as $c)
                            <div class="checkbox checkbox-inline checkbox-primary">
                                <input type="checkbox" name="Class[]" id="Class{{ $c->id }}" value="{{ $c->id }}">
                                <label for="Class{{ $c->id }}">
                                    {{ $c->ClassName }}
                                </label>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        
                    </div>
                    
                    <div class="form-group form-group-sm">


                        <label for="Group" class="col-sm-2 control-label text-danger">Group</label>
                        <div class="col-sm-10">
                            @if(isset($Groups))
                            @foreach($Groups as $g)
                            <div class="checkbox checkbox-inline checkbox-primary">
                                <input type="checkbox" name="Group[]" id="Group{{ $g->id }}" value="{{ $g->id }}">
                                <label for="Group{{ $g->id }}">
                                    {{ $g->GroupName }}
                                </label>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        
                    </div>
                    
                    <div class="form-group form-group-sm">


                        <label for="Branch" class="col-sm-2 control-label text-danger">Branch</label>
                        <div class="col-sm-10">
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