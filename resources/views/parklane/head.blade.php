<!DOCTYPE html>
<html lang="en">

  
<!-- Mirrored from bootstrapget.com/demos/medflex-medical-admin-template/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 18 Mar 2025 16:38:35 GMT -->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bootstrap Gallery - Medical Admin Templates & Dashboards</title>

        <!-- Meta -->
        <meta name="description" content="Marketplace for Bootstrap Admin Dashboards">
        <meta property="og:title" content="Admin Templates - Dashboard Templates">
        <meta property="og:description" content="Marketplace for Bootstrap Admin Dashboards">
        <meta property="og:type" content="Website">
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.svg')}}">
        <link href="{{asset('https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css')}}" rel="stylesheet">


        <!-- *************
            ************ CSS Files *************
        ************* -->
        {{-- <link rel="stylesheet" href="assets/fonts/remix/remixicon.css"> --}}
        <link rel="stylesheet" href="{{ asset('assets/css/main.min.css') }}">

        <!-- *************
            ************ Vendor Css Files *************
        ************ -->

        <!-- Scrollbar CSS -->
        <link rel="stylesheet" href="{{asset('assets/vendor/overlay-scroll/OverlayScrollbars.min.css')}}">

        <style>
            #toast-container {
    position: fixed !important;
    top: 20px !important;
    right: 20px !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 10px;
    z-index: 999999 !important;
}

.toast {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    min-width: 280px !important;
    max-width: 350px;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    color: white;
    font-weight: 500;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    position: relative;
    animation: fadeIn 0.5s ease-in-out, fadeOut 0.5s ease-in-out 3.5s forwards;
    opacity: 1;
}

.toast.success {
    background: #28a745; /* Green */
}

.toast.error {
    background: #dc3545; /* Red */
}

.toast .toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: rgba(255, 255, 255, 0.7);
    width: 100%;
    animation: progressBar 3s linear forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeOut {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(-20px); }
}

@keyframes progressBar {
    from { width: 100%; }
    to { width: 0%; }
}

      
        </style>
        
  </head>

 
  <body>
