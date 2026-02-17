<div class="actions">
    @if($user->is_admin == 1)
    <a class="btn sbold dark" data-toggle="modal" data-target="#addNewDevice">Add New
        <i class="fa fa-plus"></i></a>
        @endif
        
</div>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Department</th>
        <th>Device Name</th>
        <th>IpAddress</th>
        <th>Serial No</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($devices as $device)
        <tr>
            <td>
                @foreach($device->departments as $depart)
                    {{$depart->name}} , 
                @endforeach
            </td>
            <td>{{$device->name}}</td>
            <td>{{$device->ip_address}}</td>
            <td>{{$device->serial_num}}</td>
            <td>{{$device->device_status == 1 ? 'Active' : 'Inactive'}}</td>
            <td>
                @if($user->is_admin == 1)
                <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#deviceEditModel{{$device->id}}"
                    style="font-size: 12px;">Edit <i class="fa fa-edit"></i></a>

                @include('devices.device_info.edit')

                <a class="btn btn-sm btn-danger device-delete" data-device-url="{{ route('device.info.delete', $device->id) }}" 
                    data-device-id="{{ $device->id }}" href="javascript:;">
                    Delete <i class="fa fa-trash"></i> 
                @endif
                <a class="btn btn-sm btn-info"
                        href="{{ route('device.info.show', $device->id) }}"
                        style="font-size: 12px;">Check Status <i class="fa fa-check-circle"></i></a>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@if($user->is_admin == 1)
@include('devices.device_info.create')
@endif