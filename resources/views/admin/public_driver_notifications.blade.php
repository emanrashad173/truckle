@extends('admin.layouts.master')

@section('title')
    ألاشعارات العامة للسائقين
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection


@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/admin/home')}}">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/public_driver_notifications')}}">ألاشعارات العامة للسائقين</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض ألاشعارات العامة للسائقين</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض ألاشعارات العامة للسائقين
        <small>اضافة جميع ألاشعارات العامة للسائقين</small>
    </h1>
    @include('flash::message')
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('storePublicDriverNotification')}}" enctype="multipart/form-data" >
                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-body">
                        <div class="row">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered table-responsive">
                                <div class="portlet-body form">
                                    <div class="form-horizontal" role="form">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">العنوان</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="ar_title" class="form-control" placeholder="أكتب عنوان الاشعار " value="{{old('ar_title')}}">
                                                    @if ($errors->has('ar_title'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('ar_title') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">محتوي  الاشعار</label>
                                                <div class="col-md-9">
                                                    <textarea  name="ar_message" class="form-control" placeholder="أكتب  محتوي  الاشعار " value="{{old('ar_message')}}"></textarea>
                                                    @if ($errors->has('ar_message'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('ar_message') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->


                        </div>


                        <!-- END CONTENT BODY -->

                        <!-- END CONTENT -->


                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green" value="حفظ" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">حفظ</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END TAB PORTLET-->
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>

@endsection
