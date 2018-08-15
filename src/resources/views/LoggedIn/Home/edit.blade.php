@extends('Layouts.Application')

@section('MainContent')

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
		<div class="panel-heading">
			 <h6 class="panel-title">Update Your General Information</h6>
	    </div>		 
            <div class="panel-body">
                 @include('Layouts.FormValidationErrors')
                 @include('Layouts.ErrorSuccessAndWarninMessages')
               

          <form class="form-horizontal" role="form" method="POST" action="{{ url(route('LoggedIn.Home.updateOwnProfileAction')) }}">
                    {{ csrf_field() }}
	 <div class="row">
      <div class="col-md-6">
        <div class="form-group row clearfix">
          <label for="" class="col-sm-3 control-label"> Picture </label>

          <div class="col-sm-9">
            <div class="userpic">
              <div class="userpic-wrapper">
                   <img class="media-object" src="{{ asset('img/ProfilePicture') . '/' . Auth::user()->image }}" alt="{{ Auth::user()->image }}" class="img-responsive" width="200" height="200"/>
              </div>
			  <b><a href="#" data-toggle="modal" data-target="#myModal">Edit Picture</a></b>
              
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6"></div>
    </div>
	
   <div class="col-md-6">
        <div class="form-group row clearfix">
          <label for="full_name" class="col-sm-3 control-label text-danger">Full Name</label>

          <div class="col-sm-9">
              <input type="text" class="form-control" id="full_name" name="full_name" value="{{Auth::user()->full_name}}">
          </div>
        </div>
        <div class="form-group row clearfix">
          <label for="email" class="col-sm-3 control-label text-danger">Email</label>

          <div class="col-sm-9">
            <input type="email" class="form-control" name="email" id="email" value="{{Auth::user()->email }}"/ >
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group row clearfix">
          <label for="phone_no" class="col-sm-3 control-label text-danger">Mobile No.</label>

          <div class="col-sm-9">
             <input type="text" class="form-control" name="phone_no" id="phone_no" value="{{ Auth::user()->phone_no }}"/>
          </div>
        </div>
		<div class="form-group row clearfix">
          <label for="degination" class="col-sm-3 control-label text-danger"> Degination</label>

          <div class="col-sm-9">
            <input type="text" class="form-control" name="degination" id="degination" value="{{Auth::user()->degination }}"/ >
          </div>
        </div>
      </div>
	   <div class="col-md-6">
        <div class="form-group row clearfix">
          <label for="address" class="col-sm-3 control-label text-danger">Address</label>

          <div class="col-sm-9">
             <input type="text" class="form-control" name="address" id="address" value="{{ Auth::user()->address }}"/>
          </div>
        </div>
		<div class="form-group row clearfix">
          <label for="relegion" class="col-sm-3 control-label text-danger"> Relegion </label>

          <div class="col-sm-9">
            <input type="text" class="form-control" name="relegion" id="relegion" value="{{Auth::user()->relegion }}"/ >
          </div>
        </div>
      </div>
	   <div class="col-md-6">
        <div class="form-group row clearfix">
          <label for="blood_group" class="col-sm-3 control-label text-danger">Blood Group</label>

          <div class="col-sm-9">
             <input type="text" class="form-control" name="blood_group" id="blood_group" value="{{ Auth::user()->blood_group }}"/>
          </div>
        </div>
		<div class="form-group row clearfix">
          <label for="nid_no" class="col-sm-3 control-label text-danger"> NID No.</label>

          <div class="col-sm-9">
            <input type="text" class="form-control" name="nid_no" id="nid_no" value="{{Auth::user()->nid_no }}"/ >
          </div>
        </div>
      </div>
                   
	<div class="form-group form-group-sm">
	<div class="col-sm-12">
			<button type="submit" class="btn btn-primary btn-sm btn-block"><i class="fa fa-floppy-o"></i>&nbsp;Update Profile</button>
		</div>
	   
	</div>
                </form>
            </div>
        </div>
        </div>
        </div>
<!-- Modal content-->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change Your Profile Picture</h4>
        </div>
        <div class="modal-body">
        <form class="form-horizontal" role="form" method="POST" action="{{ url(route('LoggedIn.Home.updateProfile')) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                  <div class="form-group form-group-sm">
                        <label for="image" class="col-sm-3 control-label">Select image </label>
                        <div class="col-sm-6">
                             <input type="file" name="image" id="image" value="{{ old('image') }}" />
                        </div>
                        @if ($errors->has('image'))
                        <span class="help-block">
                            <strong class="text-danger">{{ $errors->first('image') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group form-group-sm">
                        <div class="col-sm-offset-3 col-sm-10">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                Save
                            </button>
                        </div>
                    </div>
                </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
   </div>
  </div>
		
		
		
@endsection