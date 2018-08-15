@if (session()->has('ErrorMessage'))
<div class="alert alert-danger alert-dismissible text-danger" role="alert">
    <button type="button" class="close" aria-label="Close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
    <strong>Oh snap!</strong>
    <ul>
        <li>{{ session()->pull('ErrorMessage') }}</li>
    </ul>
</div>
@endif

@if (session()->has('SuccessMessage'))
<div class="alert alert-success alert-dismissible text-success" role="alert"><button type="button" class="close" aria-label="Close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
    <strong>Well done!</strong>
    <ul>
        <li>{{ session()->pull('SuccessMessage') }}</li>
    </ul>
</div>
@endif

@if (session()->has('WarningMessage'))
<div class="alert alert-warning alert-dismissible text-warning" role="alert"><button data-dismiss="alert" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Warning!</strong>
    <ul>
        <li>{{ session()->pull('WarningMessage') }}</li>
    </ul>
</div>
@endif