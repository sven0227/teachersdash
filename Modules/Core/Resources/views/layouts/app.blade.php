<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
  <head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="{{ app()->getLocale() }}" />
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <link rel="icon" href="{{ asset(config('app.logo_favicon'))}}" type="image/png">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="{{ config('app.SITE_DESCRIPTION') }}">
    <meta name="keywords" content="{{ config('app.SITE_KEYWORDS')}}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ Module::asset('core:core/core.css') }}">
    <link rel="stylesheet" href="{{ Module::asset('core:app/css/customize.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/iguider/css/iGuider.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/iguider/themes/bootstrap/iGuider-theme-bootstrap.css') }}"/>
    @includeWhen(config('app.GOOGLE_ANALYTICS'), 'core::partials.google-analytics')
    @stack('head')
    <style>
      .notification-list {
        max-height: 75vh;
        overflow: auto;
      }
      .notification-list::-webkit-scrollbar {
        width: 5px;
      }
      .notification-list::-webkit-scrollbar-track {
        background: #f1f1f1;
      }
      .notification-list::-webkit-scrollbar-thumb {
        background: #c7c7c7;
      }
      .notification-list::-webkit-scrollbar-thumb:hover {
        background: #888;
      }
      .notification {
        border: 1px solid #cccccc;
        padding: 1rem;
        cursor: pointer;
        margin-bottom: 10px;
        border-radius: 5px;
        transition: all .3s ease;
      }
      .notification:last-child {
        margin-bottom: 0px;
      }

      .notification:hover {
        background-color: #fbf5ea;
      }
    </style>
    <script type="text/javascript">
      var BASE_URL = '{{ url('/') }}';
    </script>
  </head>
  <body id="page-top" class="sidebar-toggled">
    <!-- Page Wrapper -->
    <div id="wrapper">
      <!-- Sidebar -->
      @include('core::partials.sidebar')
      <!-- End of Sidebar -->
      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
          <!-- Topbar -->
          @include('core::partials.header-top')
          <!-- End of Topbar -->
          <!-- Begin Page Content -->
          <div class="container-fluid">
            @if($errors->any())
            <div class="alert alert-danger">
              <ul class="list-unstyled mb-0">
                @foreach ($errors->all() as $error)
                <li> <i class="fas fa-times text-danger mr-2"></i> {{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif
            @if (session('success'))
                  <div class="alert alert-success">
                      <i class="fas fa-check-circle text-success mr-2"></i> {!! session('success') !!}
                  </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-times text-danger mr-2"></i> {!! session('error') !!}
                </div>
            @endif
            <!-- Page Heading -->
            @yield('content')
            
          </div>
          <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
 
        <!-- Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>@lang('Copyright') © {{ now()->year }} @lang('Design by') <a href="{{ url('/') }}">{{ config('app.name') }}</a></span>
            </div>
          </div>
        </footer>
        <!-- End of Footer -->
      </div>
      <!-- End of Content Wrapper -->
    </div>
    
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
    <div id="announcement_modal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Administrative announcement</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="notification-list">
              @foreach (getAnnouncements() as $notification)
              <div class="notification">
                <h5>{{ $notification->title }}</h5>
                <div class="notifcation-content">
                  {{ $notification->content }}
                  @if ($notification->is_account_setup_payment)
                  <div class="text-center mt-4">
                    <button class="btn btn-success account_setup_payment_btn">Assistance Setting Up</button>
                  </div>
                  @endif
                </div>
              </div>
              @endforeach
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <label>
              <input type="checkbox" class="unnotify_tick"/> Unnoticed for a day
            </label>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      var testEventId = {{ is_null(getTestEvent()) ? 0 : getTestEvent()->id }}
      var slug = "{{ getSlugName(auth()->user()->name) }}/{{ is_null(getTestEvent()) ? "" : getTestEvent()->short_slug }}";
      var isFirstLogin = {{ auth()->user()->login_nums }};
      var eventNums = {{ getEventNums() }};
      var isAdmin = {{ auth()->user()->role == "admin" ? 1 : 0 }};
    </script>
    <script src="{{ Module::asset('core:core/core.js') }}" ></script>
    <script src="{{ Module::asset('core:vendor/tinymce/js/tinymce/tinymce.min.js') }}" ></script>
    <script src="{{ Module::asset('core:vendor/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}" ></script>
    <script src="{{ asset('vendor/iguider/js/jquery.iGuider.js') }}"></script>
    <script src="{{ asset('vendor/iguider/themes/bootstrap/iGuider-theme-bootstrap.js') }}"></script>
    <script src="{{ Module::asset('core:app/js/app.js') }}" ></script>
    <script>
      function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        let expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
      }

      function getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for(let i = 0; i <ca.length; i++) {
          let c = ca[i];
          while (c.charAt(0) == ' ') {
            c = c.substring(1);
          }
          if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
          }
        }
        return "";
      }
      $(document).ready(function() {
          if (getCookie('showAfterOneDay') == "") {
            $("#announcement_modal").modal("show");
          }
          $("#announcement_modal input.unnotify_tick").change(function() {
            if ($(this).prop("checked")) {
              setCookie("showAfterOneDay", "1", 1);
            }
          });
          $(".account_setup_payment_btn").click(function() {
            $("#announcement_modal").modal("hide");
            $("#account_setup_payment_modal").modal("show");
          });
      });
    </script>
    @stack('scripts')
  </body>
</html>