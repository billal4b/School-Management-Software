@extends('Layouts.Application')

@section('MainContent')

<div class="row">
                @include('Layouts.FormValidationErrors')
                @include('Layouts.ErrorSuccessAndWarninMessages')
<!--    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">General Information</h3>
            </div>
            <div class="panel-body">-->

                <div class="col-md-12 text-center text-bold">
                    <h2>Welcome {{ Auth::user()->full_name }}</h2>
                </div>

<!--            </div>
        </div>



    </div>-->
</div>

	<!-- Modal Edit profile-->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <div class="modal-content">
	   @include('Layouts.FormValidationErrors')
                @include('Layouts.ErrorSuccessAndWarninMessages')
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update Your Profile</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" role="form" method="POST" action="{{ url(route ('LoggedIn.Home.updateOwnProfileAction' )) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="form-group form-group-sm">
                        <label for="full_name" class="col-sm-3 control-label">Full Name <sup><i class="fa fa-asterisk"style="color:red"></i></sup></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{Auth::user()->full_name}}">
                        </div>
                      
                    </div>
					 <div class="form-group form-group-sm">
                        <label for="email" class="col-sm-3 control-label">E-mail <sup><i class="fa fa-asterisk"style="color:red"></i></sup></label>
                        <div class="col-sm-5">
                            <input type="email" class="form-control" id="email" name="email"  value="{{ Auth::user()->email }}">
                        </div>
                      
                    </div>
					
					
					 <div class="form-group form-group-sm">
                        <label for="phone_no" class="col-sm-3 control-label">Phone No  <sup><i class="fa fa-asterisk"style="color:red"></i></sup></label>
                        <div class="col-sm-5">                                                        
                            <input type="text" class="form-control" id="phone_no" name="phone_no"  value="{{ Auth::user()->phone_no }}">
                        </div>
                       
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