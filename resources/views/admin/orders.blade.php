@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Orders</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Orders</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width:70px">OrderNo</th>
                                <th class="text-center">Namessss</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Tax</th>
                                <th class="text-center">Total</th>

                                <th class="text-center">Status</th>
                                <th class="text-center">Order Date</th>
                                <th class="text-center">Total Items</th>
                                <th class="text-center">Delivered On</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr>
                                <td class="text-center">{{$order->global_order_number}}</td>
                                <td class="text-center">{{$order->name}}</td>
                                <td class="text-center">{{$order->phone}}</td>
                                <td class="text-center">{{$order->subtotal}}</td>
                                <td class="text-center">{{$order->tax}}</td>
                                <td class="text-center">{{$order->total}}</td>

                                <td class="text-center">
                                    @if($order->status == 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                    @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                    @else
                                    <span class="badge bg-warning">Ordered</span>
                                    @endif
                                </td>
                                <td class="text-center">{{$order->created_at}}</td>
                                <td class="text-center">{{$order->orderItems->count()}}</td>
                                <td class="text-center">{{$order->delivered_date}}</td>
                                <td class="text-center">
                                    <a href="{{route('admin.order_details',$order->id)}}">
                                        <div class="list-icon-function view-icon">
                                            <div class="item eye">
                                                <i class="icon-eye"></i>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{$orders->links('pagination::bootstrap-5')}}
            </div>
        </div>

        <!-- User Order Statistics Table -->
        <div class="wg-box mt-4">
            <div class="flex items-center justify-between">
                <h4 class="text-title-medium">Total Orders by User</h4>
            </div>
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Customer Name</th>
                                <th class="text-center">Phone Number</th>
                                <th class="text-center">Total Orders</th>
                                <th class="text-center">Total Items Purchased</th>
                                <th class="text-center">Total Amount Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($userOrderStats as $userStat)
                            <tr>
                                <td class="text-center">{{ $userStat->name }}</td>
                                <td class="text-center">{{ $userStat->phone }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $userStat->total_orders }}</span>
                                </td>
                                <td class="text-center">{{ number_format($userStat->total_items) }}</td>
                                <td class="text-center">${{ number_format($userStat->total_amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No order statistics available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection