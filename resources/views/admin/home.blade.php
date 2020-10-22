@extends('admin.layouts.master')

@section('title')
    لوحة التحكم
@endsection

@section('content')

    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/admin/home"> لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>الإحصائيات</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">  الإحصائيات
        <small>عرض الإحصائيات</small>
    </h1>

    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-blue" href="{{ url('/admin/admins') }}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$admins}}</span>
                    </div>
                    <div class="desc"> عدد المديرين  </div>
                </div>
            </a>
        </div>
        
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-red" href="{{ url('/admin/users') }}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$users}}</span>
                    </div>
                    <div class="desc"> المستخدمين  </div>
                </div>
            </a>
        </div> 
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-orange" href="{{ url('/admin/drivers') }}">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$drivers}}</span>
                    </div>
                    <div class="desc"> السائقين  </div>
                </div>
            </a>
        </div> 
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-brown" href="{{ url('/admin/categories') }}">
                <div class="visual">
                    <i class="fa fa-sitemap"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$categories}}</span>
                    </div>
                    <div class="desc"> الاقسام    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-green" href="{{ url('/admin/countries') }}">
                <div class="visual">
                    <i class="fa fa-cloud"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$countries}}</span>
                    </div>
                    <div class="desc"> الدول  </div>
                </div>
            </a>
        </div> 
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-yellow" href="{{ url('/admin/cities') }}">
                <div class="visual">
                    <i class="fa fa-cloud"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$cities}}</span>
                    </div>
                    <div class="desc"> المدن  </div>
                </div>
            </a>
        </div> 
        
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-red" href="{{ url('/admin/orders/new') }}">
                <div class="visual">
                    <i class="fa fa-sitemap"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$neworders}}</span>
                    </div>
                    <div class="desc"> الطلبات الجديدة    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-blue" href="{{ url('/admin/orders/active') }}">
                <div class="visual">
                    <i class="fa fa-sitemap"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$activedorders}}</span>
                    </div>
                    <div class="desc"> الطلبات النشطة    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-orange" href="{{ url('/admin/orders/completed') }}">
                <div class="visual">
                    <i class="fa fa-sitemap"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$completedorders}}</span>
                    </div>
                    <div class="desc"> الطلبات المكتملة </div>
                </div>
            </a>
        </div>
       
       


        


    </div>
@endsection
