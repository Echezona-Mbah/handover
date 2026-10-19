<script>
  function showToast(message, type = "success") { 
      var toastContainer = document.getElementById("toast-container");
      if (!toastContainer) return;

      var toast = document.createElement("div");
      toast.className = "toast " + type;
      toast.textContent = message;

      var progress = document.createElement("div");
      progress.className = "toast-progress";

      toast.appendChild(progress);
      toastContainer.appendChild(toast);

      setTimeout(() => {
          toast.remove();
      }, 4000);
  }

  // Show error messages
  window.onload = function() {
      @foreach ($errors->all() as $error)
          showToast("{{ $error }}", "error");
      @endforeach
  };

  // Show success message
  document.addEventListener("DOMContentLoaded", function() {
      @if (session('success'))
          showToast("{{ session('success') }}", "success");
      @endif
  });
</script>

    <!-- *************
			************ JavaScript Files *************
		************* -->
    <!-- Required jQuery first, then Bootstrap Bundle JS -->
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/moment.min.js')}}"></script>

    <!-- *************
			************ Vendor Js Files *************
		************* -->
<!-- jQuery -->
<script src="{{asset('https://code.jquery.com/jquery-3.6.0.min.js')}}"></script>

<!-- Bootstrap JS -->
<script src="{{asset('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Overlay Scroll JS -->
    <script src="{{asset('assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js')}}"></script>
    <script src="{{asset('assets/vendor/overlay-scroll/custom-scrollbar.js')}}"></script>

    <!-- Apex Charts -->
    <script src="{{asset('assets/vendor/apex/apexcharts.min.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/home/patients.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/home/treatment.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/home/available-beds.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/home/earnings.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/home/gender-age.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/custom/home/claims.js')}}"></script>

    <!-- Custom JS files -->
    <script src="{{asset('assets/js/custom.js')}}"></script>
  </body>


<!-- Mirrored from bootstrapget.com/demos/medflex-medical-admin-template/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 18 Mar 2025 16:40:11 GMT -->
</html>