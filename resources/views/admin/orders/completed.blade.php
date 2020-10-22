@extends('admin.layouts.master')

@section('title')
الطلبات المكتملة
@endsection

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.bootstrap-rtl.css') }}">
<link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
<link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
@endsection


@section('page_header')
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/admin/home">لوحة التحكم</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="/admin/orders/completed">الطلبات المكتملة</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>  عرض الطلبات المكتملة</span>
        </li>
    </ul>
</div>

<h1 class="page-title">عرض الطلبات المكتملة
    <small>عرض جميع الطلبات المكتملة</small>
</h1>
@endsection

@section('content')
@include('flash::message')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase"> الطلبات المكتملة</span>
                </div>

            </div>
            <div class="portlet-body">

                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                    id="sample_1">
                    <thead>
                        <tr>
                            <th>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                    <span></span>
                                </label>
                            </th>
                            <th></th>
                            <th> العميل </th>
                            <th> السواق </th>
                            <th> رقم الطلب </th>
                            <th> السعر </th>
                            <th> مدينة الاقلاع </th>
                            <th> مدينة الوصول </th>
                            <th> عرض </th>
                            <th> مسح </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0 ?>
                        @foreach($orders as $order)
                        <tr class="odd gradeX">
                            <td>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="checkboxes" value="1" />
                                    <span></span>
                                </label>
                            </td>
                            <td><?php echo ++$i ?></td>
                            <td> {{$order->user->name}} </td>
                            <td> {{$order->driver->name}} </td>
                            <td> {{$order->id}} </td>
                            <td> {{$order->price}} </td>
                            <td> {{$order->travel_address}}</td>
                            <td> {{$order->arrival_address}}</td>
                            <td>
                               <a href="{{url(route('order.show',$order->id))}}" class="btn btn-success btn-md"><i class="fa fa-tv"></i></a>
                            </td>
                            <td>
                                <a class="delete_attribute" data="{{$order->id}}" data_name="{{$order->name}}">
                                    <i class="fa fa-key"></i> مسح
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ URL::asset('admin/js/datatable.js') }}"></script>
<script src="{{ URL::asset('admin/js/datatables.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/datatables.bootstrap.js') }}"></script>
<script src="{{ URL::asset('admin/js/table-datatables-managed.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>
<script>
    $(document).ready(function() {
            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');
            $('body').on('click', '.delete_attribute', function() {
                // alert('here');
                var id = $(this).attr('data');
                var swal_text = 'حذف ' + $(this).attr('data_name') + '؟';
                var swal_title = 'هل أنت متأكد من الحذف ؟';
                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "تأكيد",
                    cancelButtonText: "إغلاق",
                    closeOnConfirm: false
                }, function() {
                    window.location.href = "{{ url('/') }}" + "/admin/orders/"+id+"/delete";
                });
            });
        });
</script>

@endsection