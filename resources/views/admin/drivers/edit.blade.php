@extends('admin.layouts.master')

@section('title')
    تعديل سائق
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <style>
        #map
        {
            height: 400px;
        }
     </style>
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/admin/home">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/admin/drivers">السائقين</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>تعديل السائقين</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> السائقين
        <small>تعديل السائقين</small>
    </h1>
@endsection

@section('content')


    @if (session('information'))
        <div class="alert alert-success">
            {{ session('information') }}
        </div>
    @endif
    @if (session('pass'))
        <div class="alert alert-success">
            {{ session('pass') }}
        </div>
    @endif
    @if (session('privacy'))
        <div class="alert alert-success">
            {{ session('privacy') }}
        </div>
    @endif

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

                                    <li>
                                        <a href="#tab_1_3" data-toggle="tab">تغيير كلمة المرور</a>
                                    </li>
                                    <li>
                                        <a href="#tab_1_4" data-toggle="tab">اعدادات الخصوصية</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="portlet-body">
                                <div class="tab-content">
                                    <!-- PERSONAL INFO TAB -->
                                    <div class="tab-pane active" id="tab_1_1">
                                    @include('flash::message')

                                        <form role="form" action="/admin/drivers/update/{{$driver->id}}" method="post" enctype="multipart/form-data">
                                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                            
                                          

                                            <div class="form-group">
                                                <label class="control-label">الاسم الاول</label>
                                                <input type="text" name="name" placeholder=" الاسم الاول" class="form-control" value="{{$driver->name}}" />
                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                              <div class="form-group">
                                                <label class="control-label">الاسم الاخير</label>
                                                <input type="text" name="last_name" placeholder="الاسم الاخير" class="form-control" value="{{$driver->last_name}}" />
                                                @if ($errors->has('last_name'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('last_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">رقم الهاتف</label>

                                                <input type="text" name="phone_number" placeholder="رقم الهاتف" class="form-control" value="{{$driver->phone_number}}" />
                                                @if ($errors->has('phone_number'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('phone_number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"> البريد الالكتروني</label>
                                                <input type="text" name="email" placeholder="الاسم الاخير" class="form-control" value="{{$driver->email}}" />
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!--<div class="form-group">-->
                                            <!--  <label class="control-label">الدولة</label>-->
                                            <!--  <select name="country_id" class="form-control">-->

                                            <!--      <option disabled selected>  اختر  الدولة</option>-->
                                            <!--      @foreach($countries as  $country)-->
                                            <!--          <option value="{{$country->id}}" name="country_id" @if($driver->country_id == $country->id) selected @endif>  {{$country->ar_name}}  </option>-->
                                            <!--      @endforeach-->
                                            <!--   </select>-->
                                            <!--   @if ($errors->has('country_id'))-->
                                            <!--        <span class="help-block">-->
                                            <!--           <strong style="color: red;">{{ $errors->first('country_id') }}</strong>-->
                                            <!--        </span>-->
                                            <!--    @endif-->
                                            <!--</div>-->
                                            <!--<div class="form-group">-->
                                            <!--  <label class="control-label">الشاحنة</label>-->
                                            <!--  <select name="truckle_id" class="form-control">-->

                                            <!--      <option disabled selected>  اختر  الشاحنة</option>-->
                                            <!--      @foreach($truckles as  $truckle)-->
                                            <!--          <option value="{{$truckle->id}}" name="truckle_id" @if($driver->truckle_id == $truckle->id) selected @endif>  {{$truckle->ar_name}}  </option>-->
                                            <!--      @endforeach-->
                                            <!--   </select>-->
                                            <!--   @if ($errors->has('truckle_id'))-->
                                            <!--        <span class="help-block">-->
                                            <!--           <strong style="color: red;">{{ $errors->first('truckle_id') }}</strong>-->
                                            <!--        </span>-->
                                            <!--    @endif-->
                                            <!--</div> -->
                                            <div class="form-group">
                                              <label class="control-label">القسم</label>
                                              <select name="category_id" class="form-control">

                                                  <option disabled selected>  اختر  القسم</option>
                                                  @foreach($categories as  $category)
                                                      <option value="{{$category->id}}" name="category_id" @if($driver->category_id == $category->id) selected @endif>  {{$category->ar_name}}  </option>
                                                  @endforeach
                                               </select>
                                               @if ($errors->has('category_id'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('category_id') }}</strong>
                                                    </span>
                                                @endif
                                            </div>     
                                            <div class="form-group">
                                                <label class="control-label">رقم الهاتف</label>

                                                <input type="text" name="phone_number" placeholder="رقم الهاتف" class="form-control" value="{{$driver->phone_number}}" />
                                                @if ($errors->has('phone_number'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('phone_number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3">الصورة الشخصية</label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                            @if($driver->photo !==null)

                                                                    <img   src='{{ asset("uploads/drivers/$driver->photo") }}'>
                                                            @endif   
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

                                             <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3"> الهوية او الاقامة</label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                                @if($driver->identity !==null)
                                                                    <img   src='{{ asset("uploads/drivers/document/identity/$driver->identity") }}'>
                                                                @endif
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
                                                    <label class="control-label col-md-3">  رخصة القيادة </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                                @if($driver->license !==null)
                                                                    <img   src='{{ asset("uploads/drivers/document/license/$driver->license") }}'>
                                                                @endif
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
                                                    <label class="control-label col-md-3">   استمارة السيارة </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                                @if($driver->car_form !==null)
                                                                    <img   src='{{ asset("uploads/drivers/document/car_form/$driver->car_form") }}'>
                                                                @endif
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
                                                    <label class="control-label col-md-3">    بطاقة تشغيل او بطاقة موصلات </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                                @if($driver->transportation_card !==null)
                                                                    <img   src='{{ asset("uploads/drivers/document/transportation_card/$driver->transportation_card") }}'>
                                                                @endif
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
                                                    <label class="control-label col-md-3"> تامين السيارة </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                                @if($driver->insurance !==null)
                                                                    <img   src='{{ asset("uploads/drivers/document/insurance/$driver->insurance") }}'>
                                                                @endif
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
                                            </br>
                                            </br>

                                            <div class="margiv-top-10">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn green">حفظ</button>

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- END PERSONAL INFO TAB -->

                                    <!-- CHANGE PASSWORD TAB -->
                                    <div class="tab-pane" id="tab_1_3">
                                        <form action="/admin/drivers/update/pass/{{$driver->id}}" method="post">
                                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                                            <div class="form-group">
                                                <label class="control-label">كلمة المرور الجديدة</label>
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
                                            <div class="margin-top-10">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn green">حفظ</button>

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- END CHANGE PASSWORD TAB -->
                                    <!-- PRIVACY SETTINGS TAB -->
                                    <div class="tab-pane" id="tab_1_4">
                                        <form action="/admin/drivers/update/privacy/{{$driver->id}}" method="post">
                                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                            <table class="table table-light table-hover">
                                                
                                                <tr>
                                                    <td> تفعيل المستخدم</td>
                                                    <td>
                                                        <div class="mt-radio-inline">
                                                            <label class="mt-radio">
                                                                <input type="radio" name="active" value="1" {{ $driver->active == "1" ? 'checked' : '' }}/> نعم
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-radio">
                                                                <input type="radio" name="active" value="0" {{$driver->active == "0" ? 'checked' : '' }}/> لا
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
                                            <div class="margin-top-10">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn green">حفظ</button>

                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                    <!-- END PRIVACY SETTINGS TAB -->
                                </div>
                            </div>
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
        $(document).on("change", ".file_multi_video", function(evt) {
              var $source = $('#video_here');
              $source[0].src = URL.createObjectURL(this.files[0]);
              $source.parent()[0].load();
            });
  </script>

@endsection
