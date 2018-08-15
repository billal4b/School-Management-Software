@extends('Layouts.Application')

@section('MainContent')


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
		  <div class="panel-heading">
			 <h6 class="panel-title">Change Your Password</h6>
		 </div>	 
			 
            <div class="panel-body">
             @include('Layouts.FormValidationErrors')
                 @include('Layouts.ErrorSuccessAndWarninMessages')
                <form class="form-horizontal" role="form" method="POST" action="{{ url(route('LoggedIn.Home.updatePasswordAction')) }}">
                    {{ csrf_field() }}

					
					 <div class="form-group form-group-sm">
                        <label for="oldpassword" class="col-sm-2 control-label text-danger">Current  Password</label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="Current  Password"/>
                        </div>
                       
                    </div>
					<div class="form-group form-group-sm">
                        <label for="password" class="col-sm-2 control-label text-danger">New Password</label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control" id="password" name="password" placeholder="New Password"/>
                        </div>
                        <label for="cnfpassword" class="col-sm-2 control-label text-danger">Conform Password </label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control" id="cnfpassword" name="cnfpassword" placeholder="Conform Password "/>
                        </div>
                    </div>
					
					
					
					
                
					 
       

                    <div class="form-group ">
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