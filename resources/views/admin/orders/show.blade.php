@extends('admin.layouts.master')

@section('title')
عرض الطلب 
@endsection

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.bootstrap-rtl.css') }}">
@endsection


@section('page_header')
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/admin/home">لوحة التحكم</a>
            <i class="fa fa-circle"></i>
        </li>
    </ul>
</div>

<h1 class="page-title">عرض الطلب 
    <small>عرض جميع الطلب </small>
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
                    <span class="caption-subject bold uppercase"> عرض الطلب</span>
                </div>

            </div>
            <div class="portlet-body">

            <div class="card-body">
        @if($order)
         <div class="table-responsive">
           <table class="table table-bordered">
                <tr>
                  <td class="col-md-2">العميل</td>
                  <td class="col-md-8">{{$order->user->name}}</td>
                </tr>
                @if($order->driver_id!=null)
                <tr>
                  <td class="col-md-2">السواق</td>
                  <td class="col-md-8">{{$order->driver->name}}</td>
                </tr>
                @endif
                <tr>
                  <td class="col-md-2">القسم</td>
                  <td class="col-md-8">{{$order->category->ar_name}}</td>
                </tr>
                <tr>
                  <td class="col-md-2">الوزن</td>
                  <td class="col-md-8">{{$order->size}}</td>
                </tr>
                <tr>
                  <td class="col-md-2">تفاصيل الطلب </td>
                  <td class="col-md-8">{{$order->detials}}</td>
                </tr>
                <tr>
                  <td class="col-md-2"> مدينة الاقلاع </td>
                  <td class="col-md-8">{{$order->travel_address}}</td>
                </tr>
                <tr>
                  <td class="col-md-2"> مدينة الوصول </td>
                  <td class="col-md-8">{{$order->arrival_address}}</td>
                </tr>
                <tr>
                  <td class="col-md-2"> وقت الاقلاع </td>
                  <td class="col-md-8">{{$order->travel_time}}</td>
                </tr>
                <tr>
                  <td class="col-md-2"> وقت الوصول </td>
                  <td class="col-md-8">{{$order->arrival_time}}</td>
                </tr>
                <tr>
                  <td class="col-md-2"> تاريخ الاقلاع </td>
                  <td class="col-md-8">{{$order->travel_date}}</td>
                </tr>
                <tr>
                  <td class="col-md-2"> تاريخ الوصول </td>
                  <td class="col-md-8">{{$order->arrival_date}}</td>
                </tr>

                <tr>
                  <td class="col-md-2"> كود الترويجي </td>
                  <td class="col-md-8">{{$order->code}}</td>
                </tr>
                <tr>
                  <td class="col-md-2">التكلفة</td>
                  <td class="col-md-8">{{$order->price}}</td>
                </tr>
                <tr>
                  <td class="col-md-2">تفاصيل السواق</td>
                  <td class="col-md-8">{{$order->detials_driver}}</td>
                </tr>
                <tr>
                  <td class="col-md-2">الحالة</td>
                  @if ($order->state == 'confirmed'  )
                    <td class="col-md-8">جديدة</td>
                  @elseif ($order->state == 'active' )
                    <td class="col-md-8">نشطة</td>
                  @elseif ($order->state == 'completed' )
                    <td class="col-md-8">مكتملة</td>
                  @elseif ($order->state == 'rejected' )
                    <td class="col-md-8">ملغية</td>
                  @endif
                </tr>
                @if($order->state == 'rejected' )
                <tr>
                  <td class="col-md-2">تفاصيل الالغاء</td>
                  <td class="col-md-8">{{$order->notes}}</td>
                </tr>
                @endif
                <tr>
                  <td class="col-md-2">العمولة</td>
                  <td class="col-md-8">{{$order->commission_price}}</td>
                </tr>
                <tr>
                  <td class="col-md-2">التكلفة الكاملة </td>
                  <td class="col-md-8">{{$order->total_price}}</td>
                </tr>
           </table>
         </div>
          @else
          <div class="alert alert.danger" role="alert">
             No Data
          </div>
        @endif
      </div>
      <!-- /.card-body -->
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


@endsection