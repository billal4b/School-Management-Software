<?php //echo '<pre>';print_r(Request::get('from_date'));echo '</pre>';exit();  ?>

<table class="table table-striped table-bordered table-condensed table-hover" id="myTable">


    <thead>
        <tr>
            <!--<th>SL</th>-->
            <th>Roll</th>
            <th>Student ID</th> 
            <th>Name</th> 
            <th>Class</th>			
            <th>Section</th>											
            <th>Group</th>									
<!--            <th>Roll</th> -->
            <th>Contact For SMS</th> 
            <th>Action</th> 
        </tr>
    </thead>
    <tbody>  
        <?php $sl = 0; ?>	

        @if(isset($studentsView))
        @foreach($studentsView as $irt)
        <tr>		
            <!--<td>{{ ++$sl }}</td>-->	  
<td>{{ $irt->roll_no }}</td>            
            <td>{{ $irt->system_generated_student_id }}</td>		 
            <td>{{ $irt->student_name }}</td>		 
            <td>{{ $irt->ClassName }}</td>							
            <td>{{ $irt->SectionName }}</td>							
            <td>{{ $irt->GroupName }}</td>					
<!--            <td>{{ $irt->roll_no }}</td>	-->
            <td>{{ $irt->contact_for_sms }}</td>	
            <td>
                <a class="btn btn-sm btn-primary" href="{{ url(route('LoggedIn.Settings.studentsView.edit', array('id' => $irt->student_id)))  }}" target="_blank"><i class="fa fa-edit"></i> Edit</a>
            </td>
        </tr>	       	
        @endforeach
        @endif 
    </tbody>
</table>

<div>
    <a class="btn  btn-primary fa fa-print" target="_blank" style="cursor:pointer;"
       href="{{ url(route('LoggedIn.Settings.studentViewPrint.studentPrint', array(
                                              'branchVersion'=> Request::get('branch_and_version') ? Request::get('branch_and_version') : 0,
			                                  'class'        => Request::get('class_name') ? Request::get('class_name') : 0,
			                                  'section'      => Request::get('section_name') ? Request::get('section_name') : 0,
			                                  'group'        => Request::get('group_name') ? Request::get('group_name') : 0
             )))  }}"> Print </a>
    
    
    <a class="btn  btn-primary fa fa-print" target="_blank" style="cursor:pointer;"
       href="{{ url(route('LoggedIn.Settings.studentViewPrint.PrintBlankMarksSheet', array(
                                              'branchVersion'=> Request::get('branch_and_version') ? Request::get('branch_and_version') : 0,
			                                  'class'        => Request::get('class_name') ? Request::get('class_name') : 0,
			                                  'section'      => Request::get('section_name') ? Request::get('section_name') : 0,
			                                  'group'        => Request::get('group_name') ? Request::get('group_name') : 0
             )))  }}"> Blank Marks Sheet</a>
    
</div>			 

<script language="JavaScript" type="text/javascript">


    $(document).ready(function () {
        $('#myTable').DataTable();
    });


</script>


