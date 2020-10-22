@extends('admin.layouts.master')

@section('title')
    تعديل أنترو التطبيق
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <style>
        #map {
            height: 500px;
            width: 1000px;
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
                <a href="/admin/splashes">أنترو التطبيق</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>تعديل أنترو التطبيق</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">  أنترو التطبيق
        <small>تعديل أنترو التطبيق  </small>
    </h1>
@endsection

@section('content')



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
                                
                                
                            </div>
                            <form role="form" action="{{route('updateSplash' ,  $splash->id)}}" method="post" enctype="multipart/form-data">
                                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="tab-pane active" id="tab_1_1">


                                            <div class="form-group">
                                                <label class="control-label">عنوان الأنترو</label>
                                                <input type="text" name="title" placeholder="أكتب عنوان الأنترو" class="form-control" value="{{$splash->title}}" />
                                                @if ($errors->has('title'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('title') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"> أكتب محتوي  الأنترو </label>
                                                <textarea name="details" class="form-control"> {{$splash->details}} </textarea>

                                                @if ($errors->has('details'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('details') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3"> صورة الأنترو</label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                               <img src="{{asset('/uploads/intros/'.$splash->photo)}}"> 
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
                                
                                              <br>
                                        </div>
                                        <!-- END PERSONAL INFO TAB -->


                                       
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
