@extends('Layouts.Application')

@section('MainContent')

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Marks View</h3>
            </div>
            <div class="panel-body">

                @include('Layouts.FormValidationErrors')
                @include('Layouts.ErrorSuccessAndWarninMessages')

                <form class="form-horizontal" id="ShowMarksViewFrm">

                    {!! csrf_field() !!}


                    <div class="form-group form-group-sm">
                        <label for="ExamType" class="col-sm-1 control-label text-danger">Exam Type</label>
                        <div class="col-sm-2">
                            <select class="form-control ExamType" id="ExamType" name="ExamType">
                                <option value="">----- Exam Type -----</option>
                                @if(isset($ExamTypes))
                                @foreach($ExamTypes as $g)
                                <option value="{{ $g->exam_type_id }}">{{ $g->exam_type_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-primary btn-sm" id="ShowMarksViewBtn"><i class="fa fa-braille"></i>&nbsp;Marks View</button>
                        </div>
                    </div>
                </form>
                <div id="ShowMarksView"></div>

            </div>
        </div>



    </div>

</div>

<script type="text/javascript">

    $(document).ready(function () {


        $(document).on('click', '#ShowMarksViewBtn', function () {

            var ExamType = $('#ExamType').val() || 0;
            if (ExamType > 0) {
                $.ajax({
                    type: "POST",
                    url: baseURL + '/!/Result/MarksView/Show',
                    data: $('#ShowMarksViewFrm').serialize(),
                    success: function (response) {
                        //alert(response);
                        $('#ShowMarksView').html(response);
                    }
                });
                return false;
            }
        });
        
        $(document).on('click', '#SelectUnselectAllCheckbox', function () {

            var String = $(this).find('i').attr('id');
            var Button = (String == 'Select') ? '<i class="fa fa-minus-square" id="Unselect"></i>&nbsp;Unselect All' : '<i class="fa fa-check-square" id="Select"></i>&nbsp;Select All';
            //alert(Button);
            $(this).html(Button);
            //alert(String);
            var checkedStatus = (String == 'Select') ? true : false;
            $('#Tbl_MarksViewAsTeacher tbody tr').find('td:nth-child(1) :checkbox').each(function () {
                $(this).prop('checked', checkedStatus);
//                console.log(checkedStatus);
            });
        });
        $(document).on('click', '#SelectUnselectAllCheckbox2', function () {

            var String = $(this).find('i').attr('id');
            var Button = (String == 'Select') ? '<i class="fa fa-minus-square" id="Unselect"></i>&nbsp;Unselect All' : '<i class="fa fa-check-square" id="Select"></i>&nbsp;Select All';
            //alert(Button);
            $(this).html(Button);
            //alert(String);
            var checkedStatus = (String == 'Select') ? true : false;
            $('#Tbl_MarksViewAsClassTeacher tbody tr').find('td:nth-child(1) :checkbox').each(function () {
                $(this).prop('checked', checkedStatus);
//                console.log(checkedStatus);
            });
        });

    });
</script>

@endsection