@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ trans('lang.markets_payout_plural') }}<small
                            class="ml-3 mr-3">|</small><small>{{ trans('lang.markets_payout_desc') }}</small></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i>
                                {{ trans('lang.dashboard') }}</a></li>
                        <li class="breadcrumb-itema ctive"><a
                                href="{!! route('marketsPayouts.index') !!}">{{ trans('lang.markets_payout_plural') }}</a>
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('marketsPayouts.index') !!}"><i
                                class="fa fa-list mr-2"></i>{{ trans('lang.markets_payout_table') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{!! route('marketsPayouts.create') !!}"><i
                                class="fa fa-plus mr-2"></i>{{ trans('lang.markets_payout_create') }}</a>
                    </li>
                </ul>
                {{-- print --}}
                {{-- <div class="ml-auto d-inline-flex "> --}}
                <li class="nav-item pull-right" style="list-style-type: none;">
                    <a class="nav-link pt-1" id="printOrder" href="#"><i class="fa fa-print"></i>
                        {{ trans('lang.print') }}</a>
                </li>
                {{-- </div> --}}
            </div>
            <div class="card-body">
                <div class="row">
                    @include('markets_payouts.show_fields')

                    <h3 class="m-0 text-dark">Orders</h3>
                    {{-- orders table --}}
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Order ID</th>
                                <th scope="col">Client</th>
                                <th scope="col">Order Status</th>
                                <th scope="col">Method</th>
                                {{-- <th scope="col">Delivery Fee</th> --}}
                                {{-- <th scope="col">Tax</th> --}}
                                <th scope="col">Payment Status</th>
                                <th scope="col">Sub Total</th>
                                {{-- <th scope="col">Total</th> --}}
                                <th scope="col">Updated at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <th scope="row">{{ $order['id'] }}</th>
                                    <td>{{ $order->user->name }}</td>
                                    <td><span class="badge badge-success">{{ $order->orderStatus->status }}</span></td>
                                    <td>{{ $order->payment->method }}</td>
                                    {{-- <td>{{ $order['delivery_fee'] }}</td> --}}
                                    {{-- <td>{{ $order['tax'] }}</td> --}}
                                    <td><span class="badge badge-success">{{ $order->payment->status }}</span></td>
                                    <td>{{ $order['sub_total'] }}</td>
                                    {{-- <td>{{ $order['total'] }}</td> --}}
                                    <td>{{ $order['updated_at'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Back Field -->
                    <div class="form-group col-12 text-right">
                        <a href="{!! route('marketsPayouts.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
                            {{ trans('lang.back') }}</a>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
  <script type="text/javascript">
    $("#printOrder").on("click",function () {
      window.print();
    });
  </script>
@endpush