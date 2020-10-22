@extends('admin.layouts.master')

@section('title')
    أضافة سائق  جديد
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
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
                <a href="/admin/users">السائقين</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>أضافة سائق  جديد</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> السائقين
        <small>أضافة سائق  جديد</small>
    </h1>
@endsection

@section('content')


@include('flash::message')

    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">

            <!-- BEGIN PROFILE CONTENT -->
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <div class="portlet-title tabbable-line">
                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span class="caption-subject font-blue-madison bold uppercase">حساب الملف الشخصي</span>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1_1" data-toggle="tab">المعلومات الشخصية</a>
                                    </li>

                                   
                                </ul>
                            </div>
                            <form role="form" action="/admin/drivers/store" method="post" enctype="multipart/form-data">
                                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="tab-pane active" id="tab_1_1">
                                        


                                            <div class="form-group">
                                                <label class="control-label">الاسم الاول</label>
                                                <input type="text" name="name" placeholder="الاسم الاول " class="form-control" value="{{old('name')}}" />
                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">الاسم الاخير</label>
                                                <input type="text" name="last_name" placeholder="الاسم الاخير" class="form-control" value="{{old('last_name')}}" />
                                                @if ($errors->has('last_name'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('last_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">رقم الهاتف</label>
                                                <input type="text" name="phone_number" placeholder="رقم الهاتف" class="form-control" value="{{old('phone_number')}}" />
                                                @if ($errors->has('phone_number'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('phone_number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">البريد الالكتروني </label>
                                                <input type="text" name="email" placeholder=" البريدالالكتروني" class="form-control" value="{{old('email')}}" />
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                           <div class="form-group">
                                                <label class="control-label"> القسم</label>

                                            {!! Form::select('category_id',App\Category::pluck('ar_name','id')->toArray(),request('category_id'),[
                                                'class' => 'form-control',
                                                'placeholder' => 'اختر القسم',
                                                 'name'   => 'category_id',
                                                ])!!}
                                                
                                                 @if ($errors->has('category_id'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('category_id') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">كلمة المرور</label>
                                                <input type="password" name="password" class="form-control" />
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">إعادة كلمة المرور</label>
                                                <input type="password" name="password_confirmation" class="form-control" />
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('password_confirmation') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3">الصورة الشخصية</label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                            </div>
                                                            <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> اختر الصورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="photo"> </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> إزالة </a>



                                                            </div>
                                                        </div>
                                                        @if ($errors->has('photo'))
                                                            <span class="help-block">
                                                               <strong style="color: red;">{{ $errors->first('photo') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            </br>
                                            </br>



                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3">الهوية او الاقامة</label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                            </div>
                                                            <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> اختر الصورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="identity"> </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> إزالة </a>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('identity'))
                                                            <span class="help-block">
                                                               <strong style="color: red;">{{ $errors->first('identity') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            </br>
                                            </br>

                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3"> رخصة القيادة </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                            </div>
                                                            <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> اختر الصورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="license"> </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> إزالة </a>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('license'))
                                                            <span class="help-block">
                                                               <strong style="color: red;">{{ $errors->first('license') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            </br>
                                            </br>

                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3">  استمارة السيارة </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                            </div>
                                                            <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> اختر الصورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="car_form"> </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> إزالة </a>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('car_form'))
                                                            <span class="help-block">
                                                               <strong style="color: red;">{{ $errors->first('car_form') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            </br>
                                            </br>

                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3"> بطاقة التشغيل او بطاقة المواصلات </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                            </div>
                                                            <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> اختر الصورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="transportation_card"> </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> إزالة </a>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('transportation_card'))
                                                            <span class="help-block">
                                                               <strong style="color: red;">{{ $errors->first('transportation_card') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            </br>
                                            </br>

                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3">   تامين السيارة </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                            </div>
                                                            <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> اختر الصورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="insurance"> </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> إزالة </a>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('insurance'))
                                                            <span class="help-block">
                                                               <strong style="color: red;">{{ $errors->first('insurance') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- END PERSONAL INFO TAB -->


                                        <!-- PRIVACY SETTINGS TAB -->
                                        <div class="tab-pane" id="tab_1_4">

                                            <table class="table table-light table-hover">

                                                <tr>
                                                    <td> تفعيل المستخدم</td>
                                                    <td>
                                                        <div class="mt-radio-inline">
                                                            <label class="mt-radio">
                                                                <input type="radio" name="active" value="1" {{ old('active') == "1" ? 'checked' : '' }}/> نعم
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-radio">
                                                                <input type="radio" name="active" value="0" {{ old('active') == "0" ? 'checked' : '' }}/> لا
                                                                <span></span>
                                                            </label>
                                                            @if ($errors->has('active'))
                                                                <span class="help-block">
                                                                       <strong style="color: red;">{{ $errors->first('active') }}</strong>
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>


                                            </table>


                                        </div>
                                        <!-- END PRIVACY SETTINGS TAB -->
                                    </div>

                                </div>
                                <div class="margiv-top-10">
                                    <div class="form-actions">
                                        <button type="submit" class="btn green" value="حفظ" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">حفظ</button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('select[name="address[country]"]').on('change', function() {
                var id = $(this).val();
                $.ajax({
                    url: '/get/cities/'+id,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('#register_city').empty();



                        $('select[name="address[city]"]').append('<option value>المدينة</option>');
                        // $('select[name="city"]').append('<option value>المدينة</option>');
                        $.each(data['cities'], function(index , cities) {

                            $('select[name="address[city]"]').append('<option value="'+ cities.id +'">'+cities.name+'</option>');

                        });
                    }
                });
            });
        });
    </script>
@endsection