<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar navbar-collapse collapse">

        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>

            <li class="nav-item start active open" >
                <a href="/admin/home" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">الرئيسية</span>
                    <span class="selected"></span>

                </a>
            </li>
            <li class="heading">
                <h3 class="uppercase">القائمة الجانبية</h3>
            </li>

            <li class="nav-item {{ strpos(URL::current(), 'admins') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title">المشرفين</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ url('/admin/admins') }}" class="nav-link ">
                            <span class="title">عرض المشرفين</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/admins/create') }}" class="nav-link ">
                            <span class="title">اضافة مشرف</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/users') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title">المستخدمين</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ url('/admin/users') }}" class="nav-link ">
                            <span class="title"> المستخدمين</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/drivers') }}" class="nav-link ">
                            <span class="title"> السائقين</span>
                        </a>
                    </li>
                  
                </ul>
            </li>
            
            <li class="nav-item {{ strpos(URL::current(), 'admin/public_notifications') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/public_notifications')}}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">ألاشعارات العامه</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/selected_notifications') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/selected_notifications')}}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">ألاشعارات المخصصة</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/client_notifications') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/client_notifications')}}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">ألاشعارات المستخدمين</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/driver_notifications') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/driver_notifications')}}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">ألاشعارات السائقين</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
           
            <li class="nav-item {{ strpos(URL::current(), 'orders') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">الطلبات </span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ route('orders.new') }}" class="nav-link ">
                            <span class="title">عرض طلبات  الجديدة </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('orders.active') }}" class="nav-link ">
                            <span class="title">عرض طلبات  النشطة </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('orders.completed') }}" class="nav-link ">

                            <span class="title">عرض طلبات  المكتملة</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('orders.rejected') }}" class="nav-link ">

                            <span class="title">عرض طلبات  الملغية</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'payments') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">العمولات </span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ route('payments.new') }}" class="nav-link ">
                            <span class="title">عرض العمولات  الجديدة </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('payments.paid') }}" class="nav-link ">
                            <span class="title">عرض العمولات  المنتظرة للتاكيد </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('payments.confirmed') }}" class="nav-link ">

                            <span class="title">عرض العمولات  المؤكدة</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/promo-codes') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/promo-codes')}}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">الاكواد الترويجية</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>

            <!--<li class="nav-item {{ strpos(URL::current(), 'admin/countries') !== false ? 'active' : '' }}">-->
            <!--    <a href="{{url('/admin/countries')}}" class="nav-link ">-->
            <!--        <i class="icon-layers"></i>-->
            <!--        <span class="title"> الدول</span>-->
            <!--        <span class="pull-right-container">-->
            <!--</span>-->

            <!--    </a>-->
            <!--</li>-->
            <li class="nav-item {{ strpos(URL::current(), 'admin/cities') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/cities')}}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title"> المدن</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
           
            <li class="nav-item {{ strpos(URL::current(), 'admin/categories') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/categories')}}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title"> الاقسام</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <!--<li class="nav-item {{ strpos(URL::current(), 'admin/truckles') !== false ? 'active' : '' }}">-->
            <!--    <a href="{{url('/admin/truckles')}}" class="nav-link ">-->
            <!--        <i class="icon-layers"></i>-->
            <!--        <span class="title">  الشاحنات</span>-->
            <!--        <span class="pull-right-container">-->
            <!--</span>-->

            <!--    </a>-->
            <!--</li>-->
            <!-- <li class="nav-item {{ strpos(URL::current(), 'admin/splashes') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/splashes')}}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title"> أنترو التطبيق</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li> -->
            <li class="nav-item {{ strpos(URL::current(), 'admin/settings') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/settings')}}" class="nav-link ">
                    <i class="icon-settings"></i>
                    <span class="title">  الاعدادات</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
           
           
           

            <li class="nav-item {{ strpos(URL::current(), 'admin/pages') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">الصفحات</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="/admin/pages/about" class="nav-link ">
                            <span class="title">من نحن</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="/admin/pages/terms" class="nav-link ">
                            <span class="title">الشروط والاحكام</span>
                        </a>
                    </li>




                </ul>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
