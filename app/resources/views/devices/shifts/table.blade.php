<div class="actions">
    <a class="btn sbold dark" data-toggle="modal" data-target="#addNewBranch">Add New
        <i class="fa fa-plus"></i></a>
</div>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>From Time </th>
        <th>To Time </th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($shifts as $shift)
        <tr>
            <td>{{$shift->name}}</td>
            <td>{{$shift->from_time}}</td>
            <td>{{$shift->to_time}}</td>

            <td>
                <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#branchEditModel{{$shift->id}}"
                    style="font-size: 12px;">Edit <i class="fa fa-edit"></i></a>

                @include('devices.shifts.edit')

                <a class="btn btn-sm btn-danger branch-delete" data-branch-url="{{ route('device.shifts.delete', $shift->id) }}" 
                    data-branch-id="{{ $shift->id }}" href="javascript:;">
                    Delete <i class="fa fa-trash"></i> 
                </a>
               
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@include('devices.shifts.create')
