@extends('Layouts.Application')

@section('MainContent')

<div class="row vertical-center">
    <div class="col-md-4"></div>
    <div class="col-md-4">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-lock"></i>&nbsp;Authentication</h3>
            </div>
            <div class="panel-body">

                @include('Layouts.FormValidationErrors')
                @include('Layouts.ErrorSuccessAndWarninMessages')

                <form class="form-horizontal" method="post" action="{{ route('Authentication.PostLogin') }}">

                    {!! csrf_field() !!}

                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-user primary"></i></div>
                                <input type="text" class="form-control" id="Identifiers" placeholder="Identifiers" value="{{ old('Identifiers') }}" name="Identifiers">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                <input type="password" class="form-control" name="Password" placeholder="Password" id="Password">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="checkbox checkbox-primary">
                                <input type="checkbox" name="Remember" id="Remember">
                                <label for="Remember">
                                    Remember me
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-sign-in"></i> Login</button>
                        </div>
                    </div>
                </form>

            </div>
            
<!--            <div class="panel-footer text-center">-::&nbsp;<i class="fa fa-server"></i>&nbsp;<i class="fa fa-clock-o"></i>&nbsp;::- <div id="demo"></div></div>-->
        </div>

    </div>
    <div class="col-md-4"></div>
</div>

@endsection