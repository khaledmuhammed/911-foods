<div class='btn-group btn-group-sm'>
    @can('driversPayouts.create')
          {{-- <a class="nav-link" title="{{trans('lang.drivers_payout_create')}}" href="{{ route('driversPayouts.create', $id) }}" class='btn btn-link'><i class="fa fa-plus mr-2"></i></a> --}}
          <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.drivers_payout_create')}}"
            href="{{ route('driverPayouts.custom_create', $id) }}" class='btn btn-link'>
            <i class="fa fa-plus"></i></a>
    @endcan
    @can('drivers.show')
        <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('View orders') }}"
            href="{{ route('drivers.orders', $id) }}" class='btn btn-link'>
          <i class="fa fa-info"></i></a>
    @endcan

    @can('drivers.edit')
        <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('lang.view_details') }}"
            href="{{ route('users.edit', $user_id) }}" class='btn btn-link'>
            <i class="fa fa-eye"></i>
        </a>
        <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('lang.driver_edit') }}"
            href="{{ route('drivers.edit', $id) }}" class='btn btn-link'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan

    @can('drivers.destroy')
        {!! Form::open(['route' => ['drivers.destroy', $id], 'method' => 'delete']) !!}
        {!! Form::button('<i class="fa fa-trash"></i>', [
    'type' => 'submit',
    'class' => 'btn btn-link text-danger',
    'onclick' => "return confirm('Are you sure?')",
]) !!}
        {!! Form::close() !!}
    @endcan
</div>
