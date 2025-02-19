@extends('admin.layouts.master')

@section('title')
    المدن
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection


@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/admin/home">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/admin/cities">المدن</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض المدن</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض المدن
        <small>اضافة جميع المدن</small>
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('storeCity')}}" enctype="multipart/form-data" >
                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">إضافة مدينه</span>
                        </div>

                    </div>
                    <div class="portlet-body">
            <div class="row">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-body form">
                        <div class="form-horizontal" role="form">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">أختر  الدولة</label>
                                    <div class="col-md-9">
                                       <select name="country_id" class="form-control" required>
                                       <option selected disabled> أختر الدولة </option>
                                       @foreach($countries as $country)
                                          <option value="{{$country->id}}"> {{$country->ar_name}} </option>
                                       @endforeach
                                       </select>
                                        @if ($errors->has('country_id'))
                                            <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('country_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">أسم المدينة باللغة العربية</label>
                                    <div class="col-md-9">
                                        <input type="text" name="ar_name" class="form-control" placeholder="أكتب  أسم  المدينة  باللغة العربية" value="{{old('ar_name')}}" required>
                                        @if ($errors->has('ar_name'))
                                            <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('ar_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">أسم المدينة باللغة الانجليزية</label>
                                    <div class="col-md-9">
                                        <input type="text" name="en_name" class="form-control" placeholder=" أكتب  أسم  المدينة باللغة الانجليزية" value="{{old('en_name')}}" required>
                                        @if ($errors->has('en_name'))
                                            <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('en_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">أسم المدينة باللغة الهندية</label>
                                    <div class="col-md-9">
                                        <input type="text" name="hi_name" class="form-control" placeholder=" أكتب  أسم  المدينة باللغة الهندية" value="{{old('hi_name')}}" required>
                                        @if ($errors->has('hi_name'))
                                            <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('hi_name') }}</strong>
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
