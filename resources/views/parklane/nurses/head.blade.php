<!DOCTYPE html>
<html lang="en">

  
<!-- Mirrored from bootstrapget.com/demos/medflex-medical-admin-template/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 18 Mar 2025 16:38:35 GMT -->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Fandova Nurse Portal</title>

        <!-- Meta -->
        <meta name="description" content="Marketplace for Bootstrap Admin Dashboards">
        <meta property="og:title" content="Admin Templates - Dashboard Templates">
        <meta property="og:description" content="Marketplace for Bootstrap Admin Dashboards">
        <meta property="og:type" content="Website">
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.svg')}}">
        <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">


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
          :root { --nurse-ink:#15314f; --nurse-teal:#137b76; --nurse-teal-dark:#0e5d64; --nurse-mint:#eaf8f4; --nurse-surface:#f6f9fb; --nurse-line:#e7edf2; }
          body { background: var(--nurse-surface); color: var(--nurse-ink); }
          .app-container { background: var(--nurse-surface); }
          .app-body { padding-top: 1.35rem; }
          .app-header { background: rgba(255,255,255,.96); border-bottom: 1px solid var(--nurse-line); box-shadow: 0 2px 18px rgba(18,48,79,.04); }
          .app-header .app-brand .logo { max-height: 42px; border-radius: 10px; }
          .app-hero-header { min-height: 76px; background: transparent; }
          .breadcrumb { font-size: .86rem; }
          .breadcrumb a { color: var(--nurse-teal); text-decoration: none; font-weight: 600; }
          .sidebar-wrapper { background: linear-gradient(180deg, #103b53, #0d5662); }
          .sidebar-profile { background: rgba(255,255,255,.08); margin: .75rem; border-radius: 14px; }
          .sidebar-menu > li > a { border-radius: 10px; margin: 3px 10px; }
          .sidebar-menu > li.active > a, .sidebar-menu > li > a:hover { background: rgba(102,211,192,.18); color: #fff; }
          .sidebar-menu .treeview-menu a:hover, .sidebar-menu .treeview-menu li.active a { color: #77e1ce; }
          .sidebar-contact { background: rgba(255,255,255,.09); margin: .75rem; border-radius: 14px; }
          .card { border: 1px solid var(--nurse-line); border-radius: 18px; box-shadow: 0 8px 24px rgba(17,49,79,.05); }
          .card-header { background: #fff; border-bottom: 1px solid var(--nurse-line); padding: 1rem 1.25rem; }
          .card-title { color: var(--nurse-ink); font-weight: 750; }
          .bg-1 { background: linear-gradient(125deg, var(--nurse-teal-dark), var(--nurse-teal)) !important; border: 0; }
          .btn-primary { background: var(--nurse-teal); border-color: var(--nurse-teal); box-shadow: 0 4px 10px rgba(19,123,118,.16); }
          .btn-primary:hover, .btn-primary:focus { background: var(--nurse-teal-dark); border-color: var(--nurse-teal-dark); }
          .btn-outline-primary { color: var(--nurse-teal); border-color: #8bcfc4; }
          .btn-outline-primary:hover { background: var(--nurse-teal); border-color: var(--nurse-teal); }
          .form-label { color: var(--nurse-ink); font-weight: 650; }
          .form-control, .form-select { border-color: #dce6ed; border-radius: 10px; min-height: 43px; }
          .form-control:focus, .form-select:focus { border-color: #58b9ab; box-shadow: 0 0 0 .22rem rgba(19,123,118,.12); }
          .table { --bs-table-bg: transparent; }
          .table thead th { border-bottom-color: var(--nurse-line); color: #718096; font-size: .72rem; letter-spacing: .06em; text-transform: uppercase; }
          .table td { border-color: #eef3f6; vertical-align: middle; }
          .alert { border-radius: 12px; }
          .badge { border-radius: 999px; padding: .42rem .65rem; font-weight: 650; }
          .pagination .page-link { color: var(--nurse-teal); border-color: var(--nurse-line); }
          .pagination .active > .page-link { background: var(--nurse-teal); border-color: var(--nurse-teal); }
          @media (max-width: 767px) { .app-body { padding-top: 1rem; } .app-hero-header { min-height: 64px; } }
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
