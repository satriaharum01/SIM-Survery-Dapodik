<!DOCTYPE html>
<html lang="en">

<head>
  <!--====== Required meta tags ======-->
  <meta charset="utf-8" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <meta name="description" content="" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <!--====== Title ======-->
  <title>{{env('APP_NAME')}} | Landing</title>

  <!--====== Favicon Icon ======-->
  <link href="{{ asset('landing/login/img/logo-dikdasmen.png') }}" rel="icon">

  <!--====== Bootstrap css ======-->
  <link rel="stylesheet" href="{{ asset('landing/assets/css/bootstrap.min.css')}}" />

  <!--====== Line Icons css ======-->
  <link rel="stylesheet" href="{{ asset('landing/assets/css/lineicons.css')}}" />

  <!--====== Tiny Slider css ======-->
  <link rel="stylesheet" href="{{ asset('landing/assets/css/tiny-slider.css')}}" />

  <!--====== gLightBox css ======-->
  <link rel="stylesheet" href="{{ asset('landing/assets/css/glightbox.min.css')}}" />

  <link rel="stylesheet" href="{{ asset('landing/style.css')}}" />
</head>

<body>

  <!--====== NAVBAR NINE PART START ======-->
    @yield('content')



  <a href="#" class="scroll-top btn-hover">
    <i class="lni lni-chevron-up"></i>
  </a>

  <!--====== js ======-->
  <script src="{{asset('landing/assets/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('landing/assets/js/glightbox.min.js')}}"></script>
  <script src="{{asset('landing/assets/js/main.js')}}"></script>
  <script src="{{asset('landing/assets/js/tiny-slider.js')}}"></script>

  <script>

    //===== close navbar-collapse when a  clicked
    let navbarTogglerNine = document.querySelector(
      ".navbar-nine .navbar-toggler"
    );
    navbarTogglerNine.addEventListener("click", function () {
      navbarTogglerNine.classList.toggle("active");
    });

    // ==== left sidebar toggle
    let sidebarLeft = document.querySelector(".sidebar-left");
    let overlayLeft = document.querySelector(".overlay-left");
    let sidebarClose = document.querySelector(".sidebar-close .close");

    overlayLeft.addEventListener("click", function () {
      sidebarLeft.classList.toggle("open");
      overlayLeft.classList.toggle("open");
    });
    sidebarClose.addEventListener("click", function () {
      sidebarLeft.classList.remove("open");
      overlayLeft.classList.remove("open");
    });

    // ===== navbar nine sideMenu
    let sideMenuLeftNine = document.querySelector(".navbar-nine .menu-bar");

    sideMenuLeftNine.addEventListener("click", function () {
      sidebarLeft.classList.add("open");
      overlayLeft.classList.add("open");
    });

    //========= glightbox
    GLightbox({
      'href': 'https://www.youtube.com/watch?v=r44RKWyfcFw&fbclid=IwAR21beSJORalzmzokxDRcGfkZA1AtRTE__l5N4r09HcGS5Y6vOluyouM9EM',
      'type': 'video',
      'source': 'youtube', //vimeo, youtube or local
      'width': 900,
      'autoplayVideos': true,
    });

  </script>
</body>
</html>