<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">Assign Class Teacher</h6>
    </div>
    <div class="panel-body">

        @include('Layouts.FormValidationErrors')
        @include('Layouts.ErrorSuccessAndWarninMessages')

        <form class="form-horizontal">
            {!! csrf_field() !!}

            <input type="hidden" value="{{ isset($TeacherId) ? $TeacherId : 0 }}" name="TeacherId" id="TeacherId">
            <input type="hidden" value="{{ isset($Branch) ? $Branch : 0 }}" name="Branch" id="Branch">

            <div class="form-group form-group-sm">

                <label for="Class" class="col-sm-2 control-label text-danger">Class</label>
                <div class="col-sm-2">
                    <select name="Class" class="Class form-control" id="Class">
                        <option value="">----- Select ------</option>
                        <?php
                        if (isset($Classes)) {
                            foreach ($Classes as $c) {
                                echo '<option value="' . $c->class_id . '">' . $c->ClassName . '</option>';
                            }
                        }
                        ?>
                    </select>

                </div>
                <label for="Group" class="col-sm-2 control-label text-danger">Group</label>
                <div class="col-sm-2" id="Groups">
                    <select name="Group" class="form-control" id="Group">
                        <option value="">----- Select ------</option>
                    </select>

                </div>
                <label for="Section" class="col-sm-2 control-label text-danger">Section</label>
                <div class="col-sm-2" id="Sections">
                    <select name="Section" class="form-control" id="Section">
                        <option value="">----- Select ------</option>
                    </select>

                </div>

            </div>

            <div class="form-group form-group-sm">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block btn-sm"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </div>
        </form>

    </div>

    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Group</th>
                    <th>Section</th>
                    <th>Status</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($AssignedClassTeachers))
                @foreach($AssignedClassTeachers as $act)
                <tr>
                    <td>{{ $act->ClassName }}</td>
                    <td>{{ $act->GroupName }}</td>
                    <td>{{ $act->SectionName }}</td>
                    <td>{!! $act->is_active == 1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>' !!}</td>
                    <td>
                        <a class="btn btn-{{ $act->is_active == 1 ? 'danger' : 'success' }} btn-xs UpdateStatus" id="{{ $act->id . '_' . (isset($TeacherId) ? $TeacherId : 0) . '_' . (isset($Branch) ? $Branch : 0) . '_' . ($act->is_active == 1 ? 0 : 1) }}"><i class="fa fa-{{ $act->is_active == 1 ? 'thumbs-down' : 'thumbs-up' }}"></i> {{ $act->is_active == 1 ? 'Inactive' : 'Active' }}</a>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>


