<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">Assign Subject</h6>
    </div>
    <div class="panel-body">

        @include('Layouts.FormValidationErrors')
        @include('Layouts.ErrorSuccessAndWarninMessages')

        <form class="form-horizontal">
            {!! csrf_field() !!}

            <input type="hidden" value="{{ isset($TeacherId) ? $TeacherId : 0 }}" name="TeacherId" id="TeacherId">
            <input type="hidden" value="{{ isset($Branch) ? $Branch : 0 }}" name="Branch" id="Branch">

            <div class="form-group form-group-sm">

                <label for="Class" class="col-sm-1 control-label text-danger">Class</label>
                <div class="col-sm-2">
                    <select name="Class" class="Class form-control" id="Class">
                        <option value="">----- Select ------</option>
                        <?php
                        if (isset($Classes)) {
                            foreach ($Classes as $c) {
                                echo '<option value="' . $c->id . '">' . $c->ClassName . '</option>';
                            }
                        }
                        ?>
                    </select>

                </div>
                <label for="Group" class="col-sm-1 control-label text-danger">Group</label>
                <div class="col-sm-2">
                    <select name="Group" class="form-control" id="Group">
                        <option value="">----- Select ------</option>
                        <?php
                        if (isset($Groups)) {
                            foreach ($Groups as $g) {
                                echo '<option value="' . $g->id . '">' . $g->GroupName . '</option>';
                            }
                        }
                        ?>
                    </select>

                </div>
                <label for="Section" class="col-sm-1 control-label text-danger">Section</label>
                <div class="col-sm-2" id="Sections">
                    <select name="Section" class="form-control">
                        <option value="">----- Select ------</option>
                    </select>

                </div>
                <label for="Subject" class="col-sm-1 control-label text-danger">Subject</label>
                <div class="col-sm-2" id="Subjects">
                    <select name="Subject" class="form-control">
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
                    <th>Sl.</th>
                    <th>Class</th>
                    <th>Group</th>
                    <th>Section</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($AssignedSubjects))
                <?php $Counter = 1; ?>
                @foreach($AssignedSubjects as $as)
                <tr>
                    <td>{{ $Counter }}</td>
                    <td>{{ $as->ClassName }}</td>
                    <td>{{ $as->GroupName }}</td>
                    <td>{{ $as->SectionName }}</td>
                    <td>{{ $as->subject_name . '(' . $as->subject_code . ')' }}</td>
                    <td>{!! $as->is_active == 1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>' !!}</td>
                    <td>
                        <a class="btn btn-{{ $as->is_active == 1 ? 'danger' : 'success' }} btn-xs UpdateStatus" id="{{ $as->id . '_' . (isset($TeacherId) ? $TeacherId : 0) . '_' . (isset($Branch) ? $Branch : 0) . '_' . ($as->is_active == 1 ? 0 : 1) }}"><i class="fa fa-{{ $as->is_active == 1 ? 'thumbs-down' : 'thumbs-up' }}"></i> {{ $as->is_active == 1 ? 'Inactive' : 'Active' }}</a>
                    </td>
                </tr>
                <?php $Counter++; ?>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>


