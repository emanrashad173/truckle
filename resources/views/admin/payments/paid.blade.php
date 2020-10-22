@extends('admin.layouts.master')

@section('title')
العمولات المنتظرة للتاكيد
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
            <a href="/admin/payments/paid">العمولات المنتظرة للتاكيد</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>  عرض العمولات المنتظرة للتاكيد</span>
        </li>
    </ul>
</div>

<h1 class="page-title">عرض العمولات المنتظرة للتاكيد
    <small>عرض جميع العمولات المنتظرة للتاكيد</small>
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
                    <span class="caption-subject bold uppercase"> العمولات المنتظرة للتاكيد</span>
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
                            <th> السواق </th>
                            <th> سعر الطلب </th>
                            <th>  العمولة  </th>
                            <th>   الدفع  </th>
                            <th>  رقم الطلب  </th>
                            <th>  حالة الدفع  </th>
                            <th>   خيارات  </th>
                            <th> مسح </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0 ?>
                        @foreach($payments as $payment)
                        <tr class="odd gradeX">
                            <td>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="checkboxes" value="1" />
                                    <span></span>
                                </label>
                            </td>
                            <td><?php echo ++$i ?></td>
                            <td> {{$payment->driver->name}} </td>
                            <td> {{$payment->price}}ريال </td>
                            <td> {{$payment->commission_price}}ريال</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#exampleModal{{$payment->id}}">
                                    عرض الصورة
                                </button>
                                <div class="modal fade" id="exampleModal{{$payment->id}}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">صورة الدفع</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="{{asset('uploads/payments/'.$payment->pay_photo)}}"
                                                    style="height: 300px; width: 300px;">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">اغلاق</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td> {{$payment->id}}</td>
                            <td>
                              <button type="button" class="btn btn-circle green btn-sm">تم الدفع </button>
                            </td>
                            <td>
                                <a href="{{route('payments.update-status',$payment->id)}}" class="btn btn-sm blue">
                                    <i class="icon-docs"></i> تاكيد حالة المدفوعات البنكية</a>    
                            </td>
                            <td>
                                <a class="delete_attribute" data="{{$payment->id}}" data_name="{{$payment->name}}">
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
                    window.location.href = "{{ url('/') }}" + "/admin/payments/"+id+"/delete";
                });
            });
        });
</script>

@endsection