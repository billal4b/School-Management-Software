<nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ $GLOBALS['ApplicationImgPath'] }}school_logo_1_b.png" width="50">
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li>
                           <strong>
                                Mohammadpur Preparatory School & College
                            </strong>
                            <br>{{ Auth::check() ? (session()->has('instituteDetails') ? session('instituteDetails')->school_branch_name : '') : '' }}
                            {{ Auth::check() ? (session()->has('instituteDetails') ? session('instituteDetails')->school_version_name  . ' Version' : '') : '' }}
                        </li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right">

<?php
                if (Auth::check()) {
                    if (Auth::user()->role_id == 4) {
                        ?>
                        <li><a>Total No. of Student: <span class="badge">{{ session()->has('TotalNoOfStudent') ? session('TotalNoOfStudent') : '' }}</span></a> 
                        </li>
                        <?php
                    }
                }
                ?>
                        <li><a><kbd>{{ Auth::check() ? (session()->has('roleName') ? session('roleName') : 'Unknown') : 'Guest' }}</kbd></a></li>
                        @if(Auth::check())
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> <strong>{{ Auth::user()->full_name  }}</strong> <span class="caret"></span></a>
                            <ul class="dropdown-menu">
							    <li><a href="{{ url(route('LoggedIn.Home.updateOwnProfile')) }}"><i class="fa fa-user-md"></i> Edit profile </a></li>
								<li><a href="{{ url(route('LoggedIn.Home.updateProfilePassword')) }}"><i class="fa fa-key"></i> Password</a></li>
                                <li><a href="{{ url(route('LoggedIn.Home.Logout')) }}"><i class="fa fa-power-off"></i> Logout</a></li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav> 
