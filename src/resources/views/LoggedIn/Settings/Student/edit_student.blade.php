@extends('Layouts.Application')

@section('MainContent')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Student Details </strong></div>
            <div class="panel-body">

               @include('Layouts.FormValidationErrors')
                @include('Layouts.ErrorSuccessAndWarninMessages')

                <form class="form-horizontal" role="form" method="POST" action="{{ url(route('LoggedIn.Settings.studentsView.update', array('id' => $stdDetails->student_id) ))}}">
                    {{ csrf_field() }}
                    
                    {!! method_field('put') !!}

                    <div class="form-group form-group-sm">
                        <label for="student_name" class="col-sm-2 control-label">Student Name <sup><i class="fa fa-asterisk" style='color:red'></i></sup></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="student_name" name="student_name" placeholder="Student Name" value="{{ $stdDetails->student_name }}">
                        </div>
                    </div>
                    <input type="hidden" name="system_generated_student_id" value="{{ $stdDetails->system_generated_student_id }}">

                    <div class="form-group form-group-sm">
                        <label for="year_of_admission" class="col-sm-2 control-label">Admission Year <sup><i class="fa fa-asterisk" style='color:red'></i></sup></label>
                        <div class="col-sm-3">
                            <select class="form-control" id="year_of_admission" name="year_of_admission">
                                <option value="">---Select---</option>
                                @if(isset($years))
                                @foreach($years as $class)
                                <option value="{{ $class->year_id }}">{{ $class->year }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                         <label for="branch_and_version" class="col-sm-3 control-label">Branch And Version <sup>
						   <i class="fa fa-asterisk" style='color:red'></i></sup></label>
                        <div class="col-sm-4">
                         
						<select id="branch_and_version" name="branch_and_version" class="form-control">
                                <option value="">----- Select -----</option>
                                @if(isset($InstituteBranchVersions))
                                @foreach($InstituteBranchVersions as $si)
                                   <option value="{{ $si->institute_branch_version_id }}">
								   {{ $si->school_branch_name }}, {{ $si->school_version_name }} Version
								</option>
								
								
                                @endforeach
                                @endif
                        </select>														
                        </div> 
					</div>	
					<div class="form-group form-group-sm">	
												
                        <label for="class_id" class="col-sm-2 control-label">Class <sup><i class="fa fa-asterisk" style='color:red'></i></sup></label>
                        <div class="col-sm-3">
                            <select class="form-control" id="class_id" name="class_id">
                                <option value="">---Select---</option>
                                @if(isset($studentClasses))
                                @foreach($studentClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->ClassName }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
						
                        <label for="section_name" class="col-sm-3 control-label">Section <sup><i class="fa fa-asterisk"style='color:red'> </i>  </sup></label>
                        <div class="col-sm-4">
                             <select id="section_name" name="section_name" class="form-control">
                                <option value="">--- Select ---</option>
                               
                        </select>
                        </div>   
					</div>	
                    

                    <div class="form-group form-group-sm">
					   							
                        <label for="group_name" class="col-sm-2 control-label">Group <sup><i class="fa fa-asterisk"style='color:red'></i> </sup></label>
                        <div class="col-sm-3">
                            <select class="form-control" name="group_name" id="group_name">
                                <option value="">----- Select -----</option>
                                @if(isset($studentGroups))
                                @foreach($studentGroups as $sg)
							      <option value="{{ $sg->id }}">{{ $sg->GroupName }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
						<label for="system_generated_student_id" class="col-sm-3 control-label">Student ID <sup>
						<i class="fa fa-asterisk"style='color:red'></i> </sup></label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" placeholder="Student ID"  id="system_generated_student_id" name="system_generated_student_id" value="{{  $stdDetails->system_generated_student_id }}"readonly/>
                        </div>						
					 </div>	
					 					 
					 <div class="form-group form-group-sm">
						<label for="roll_no" class="col-sm-2 control-label">Roll No <sup><i class="fa fa-asterisk" style='color:red'></i> </sup></label>
                        <div class="col-sm-3">
                           <input type="text" class="form-control" id="roll_no" name="roll_no" placeholder="Roll No" value="{{  $stdDetails->roll_no }}" />
                        </div>	
                       
                        <label for="contact_for_sms" class="col-sm-3 control-label">Contact for SMS <sup><i class="fa fa-asterisk"style='color:red'></i></sup></label>
                        <div class="col-sm-4">
                            <div class=" input-group">
                            <span class="input-group-addon" id="basic-addon1">8801</span>
                            <input type="text" class="form-control" id="contact_for_sms" name="contact_for_sms" placeholder="Contact for SMS" value="{{ substr($stdDetails->contact_for_sms, 4) }}" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
					
					    <label for="gender" class="col-sm-2 control-label">Gender <sup><i class="fa fa-asterisk"style='color:red'></i> </sup></label>
                        <div class="col-sm-3">
                            <select class="form-control" id="gender" name="gender">
                                <option value="">---Select---</option>
                                @if(isset($genders))
                                @foreach($genders as $class)
                                <option value="{{ $class->gender_id }}" >{{ $class->gender }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <label for="status" class="col-sm-3 control-label">Status <sup><i class="fa fa-asterisk"style='color:red'></i></sup></label>
                        <div class="col-sm-4">
                            <select class="form-control" name="status" id="status">
                                <option value="">----- Select -----</option>
                                <option value="1">Regular</option>
                                <option value="2">Irregular</option>
                            </select>
                        </div>
                    </div>
                    
                    <script type="text/javascript">
						document.getElementById('year_of_admission').value = '{{ $stdDetails->year_of_admission }}';
						document.getElementById('branch_and_version').value= '{{ $stdDetails->institute_branch_version_id }}';
						document.getElementById('class_id').value          = '{{ $stdDetails->class_id }}';
						document.getElementById('gender').value            = '{{ $stdDetails->gender }}';
						document.getElementById('status').value            = '{{ $stdDetails->status }}';
						document.getElementById('group_name').value        = '{{ $stdDetails->stu_group }}';
						//document.getElementById('section_name').value      = '{{ $stdDetails->section_id }}';
						document.getElementById('roll_no').value           = '{{ $stdDetails->roll_no }}';
                        var section_id = '{{ $stdDetails->section_id }}';
					</script>

                    <?php
                                        if(Auth::user()->role_id == 4){
                                            ?>
                    <div class="form-group form-group-sm">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                Save
                            </button>
                        </div>
                    </div>
                                        <?php
                                        }
                                        ?>
                </form>
            </div>
        </div>
    </div>
</div>
 <script type="text/javascript">
 $(function(){

     function getBranchSection(class_id = null, branch_and_version = null){
		
         if (class_id && branch_and_version) {
             $.ajax({
                 //url: baseURL + 'AuthenticatedUser/AppSetup/Student/create/' + branch_and_version  + '/'+ class_id,
				 url: baseURL + '/!/Settings/Student/' + branch_and_version  + '/'+ class_id,
                 type: 'GET',
                 success: function (response) {
                     $('#section_name').html(response);
                 }
             });
         }else{
             $('#section_name').html('<option value="">----- Select -----</option>');
         }
     }
	
	$(document).on('change', '#branch_and_version, #class_id', function () {
		
		//alert('hi');return false;
             
        var class_id = $('#class_id').val();
        var branch_and_version = $('#branch_and_version').val();
		
		  //alert(class_id);return false;
		  //alert(branch_and_version);return false;
		  
        getBranchSection(class_id, branch_and_version);
		
    });

     getBranchSection($('#class_id').val(), $('#branch_and_version').val());
    // alert(section_id);

     setTimeout(function(){
         document.getElementById('section_name').value = section_id; }, 2000
     );
    // document.getElementById('section_name').value = section_id;
     
});	
 </script>
 
@endsection
