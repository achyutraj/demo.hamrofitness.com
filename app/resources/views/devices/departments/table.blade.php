<div class="actions">
    <a class="btn sbold dark" data-toggle="modal" data-target="#addNewDepartment">Add New
        <i class="fa fa-plus"></i></a>
</div>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($departments as $department)
        <tr>
            <td>{{$department->name}}</td>
            <td>
                <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#departmentEditModel{{$department->id}}"
                    style="font-size: 12px;">Edit <i class="fa fa-edit"></i></a>

                @include('devices.departments.edit')

                <a class="btn btn-sm btn-danger department-delete" data-department-url="{{ route('device.departments.delete', $department->id) }}" 
                     href="javascript:;">
                    Delete <i class="fa fa-trash"></i> 
                </a>
                
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@include('devices.departments.create')
