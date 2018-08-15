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

                <form class="form-horizontal" method="post" action="{{ url(route('LoggedIn.Settings.Subject.Update', array('id' => $Subject->subject_id ))) }}">
                    {!! csrf_field() !!}
                    <input name="_method" type="hidden" value="PUT">
                    <div class="form-group form-group-sm">
                        <label for="SubjectName" class="col-sm-2 control-label text-danger">Subject Name</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="SubjectName" name="SubjectName" placeholder="Subject Name" value="{{ old('SubjectName') ? old('SubjectName') : $Subject->subject_name }}">
                        </div>
                        <label for="SubjectCode" class="col-sm-1 control-label">Subject Code</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="SubjectCode" name="SubjectCode" placeholder="Subject Code" value="{{ old('SubjectCode') ? old('SubjectCode') : $Subject->subject_code }}">
                        </div>
                    </div>

                    <div class="form-group form-group-sm">


                        <label for="Class" class="col-sm-2 control-label text-danger">Class</label>
                        <div class="col-sm-10">
                            @if(isset($Classes))
                            @foreach($Classes as $c)
                            <div class="radio radio-inline radio-primary">
                                <input type="radio" name="Class" id="Class{{ $c->id }}" value="{{ $c->id }}" {{ old('Class') ? ($c->id == old('Class') ? 'checked' : '' ) : ($Subject->class_id == $c->id ? 'checked' : '') }}>
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
                            <div class="radio radio-inline radio-primary">
                                <input type="radio" name="Group" id="Group{{ $g->id }}" value="{{ $g->id }}" {{ old('Group') ? ($g->id == old('Group') ? 'checked' : '' ) : ($Subject->group_id == $g->id ? 'checked' : '') }}>
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
                            <div class="radio radio-inline radio-primary">
                                <input type="radio" name="Branch" id="Branch{{ $ibv->institute_branch_version_id }}" value="{{ $ibv->institute_branch_version_id }}" {{ old('Branch') ? ($ibv->institute_branch_version_id == old('Branch') ? 'checked' : '' ) : ($Subject->institute_branch_version_id == $ibv->institute_branch_version_id ? 'checked' : '') }}>
                                <label for="Branch{{ $ibv->institute_branch_version_id }}">
                                    {{ $ibv->school_branch_name . ', ' . $ibv->school_version_name }}
                                </label>
                            </div>
                            @endforeach
                            @endif
                        </div>

                    </div>
                    
                    <div class="form-group form-group-sm">
                        <label for="Status" class="col-sm-2 control-label text-danger">Status</label>
                        <div class="col-sm-10">
                            <div class="radio radio-inline radio-success">
                                <input type="radio" name="Status" id="Active" value="1" {{ old('Status') ? (1 == old('Status') ? 'checked' : '' ) : ($Subject->is_active == 1 ? 'checked' : '') }}>
                                       <label for="Active">
                                    Active
                                </label>
                            </div>
                            <div class="radio radio-inline radio-danger">
                                <input type="radio" name="Status" id="Inactive" value="0" {{ old('Status') ? (0 == old('Status') ? 'checked' : '' ) : ($Subject->is_active == 0 ? 'checked' : '') }}>
                                       <label for="Inactive">
                                    Inactive
                                </label>
                            </div>
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