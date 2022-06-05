@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ trans('lang.driver_plural') }}<small
                            class="ml-3 mr-3">|</small><small>{{ trans('lang.driver_desc') }}</small></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i>
                                {{ trans('lang.dashboard') }}</a></li>
                        <li class="breadcrumb-itema ctive"><a
                                href="{!! route('drivers.index') !!}">{{ trans('lang.driver_plural') }}</a>
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
                        <a class="nav-link" href="{!! route('drivers.index') !!}"><i
                                class="fa fa-list mr-2"></i>{{ trans('lang.driver_table') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{!! route('drivers.create') !!}"><i
                                class="fa fa-plus mr-2"></i>{{ trans('lang.driver_create') }}</a>
                    </li>
                </ul>
            </div>

            {{-- start driver info --}}
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $driver->total_orders }}</h3>

                            <p>Total Orders</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-shopping-bag"></i>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $driver['total_cash'] }}</h3>

                            <p>Total Cash</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-cutlery"></i>
                        </div>

                    </div>
                </div>


            </div>
            {{-- end driver info --}}

            <div class="card-body">
                <div class="row">
                    {{-- @include('drivers.show_fields') --}}
                    <h3 class="m-0 text-dark">Orders</h3>
                    {{-- orders table --}}
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Order ID</th>
                                <th scope="col">Client</th>
                                <th scope="col">Order Status</th>
                                <th scope="col">Method</th>
                                <th scope="col">Delivery Fee</th>
                                <th scope="col">Tax</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col">Sub Total</th>
                                <th scope="col">Total</th>
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
                                    <td>{{ $order['delivery_fee'] }}</td>
                                    <td>{{ $order['tax'] }}</td>
                                    <td><span class="badge badge-success">{{ $order->payment->status }}</span></td>
                                    <td>{{ $order->productOrders[0]['quantity'] *  $order->productOrders[0]['price']}}</td>
                                    <td>{{ ($order->productOrders[0]['quantity'] *  $order->productOrders[0]['price']) + $order['delivery_fee'] + $order['tax']}}</td>
                                    <td>{{ $order['updated_at'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Back Field -->
                    <div class="form-group col-12 text-right">
                        <a href="{!! route('drivers.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
                            {{ trans('lang.back') }}</a>
                    </div>

                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection
