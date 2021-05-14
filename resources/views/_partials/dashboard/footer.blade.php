<!-- BEGIN: Footer-->
<footer
    class="page-footer footer gradient-shadow  footer-static   footer-dark gradient-45deg-red-pink ">
    <div class="footer-copyright">
        <div class="container">
      <span>&copy; <?php echo date('Y');  ?> <a href="http://themeforest.net/user/pixinvent/portfolio?ref=pixinvent"
                           target="_blank">Jubilee Kenya</a> All rights reserved.
      </span>
            <span class="right hide-on-small-only">
        Design and Developed by <a href="https://mojox.co">Jubilee Kenya</a>
      </span>
        </div>
    </div>
</footer>

<!-- END: Footer-->
{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>--}}
{{--<script--}}
{{--    src="https://code.jquery.com/jquery-2.2.4.js"--}}
{{--    integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="--}}
{{--    crossorigin="anonymous"></script>--}}
<!-- BEGIN VENDOR JS-->
<script src="{{ asset('js/vendors.min.js') }}"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN THEME  JS-->
{{--<script src="{{ asset('js/plugins.js') }}"></script>--}}
<script src="{{ asset('js/search.js') }}"></script>
<script src="{{ asset('js/custom/custom-script.js') }}"></script>
<script src="{{ asset('js/scripts/customizer.js') }}"></script>
<!-- END THEME  JS-->
<!-- BEGIN PAGE LEVEL JS-->
<script src="{{ asset('js/scripts/dashboard-modern.js') }}"></script>
{{--<script src="{{ asset('js/scripts/intro.js') }}"></script>--}}
<!-- Compiled and minified JavaScript -->
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/es6-shim/0.35.3/es6-shim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/es6-shim/0.35.3/es6-shim.min.js"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>--}}
<script src="https://unpkg.com/materialize-stepper@3.1.0/dist/js/mstepper.min.js"></script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>

{{--<script src="{{ asset('js/stepper.js') }}"></script>--}}

<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>

<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
{{--<script src="https://cdn.datatables.net/plug-ins/1.10.21/filtering/row-based/range_dates.js"></script>--}}

<!-- jQueryValidation Plugin -->
<script type="text/javascript" src="{{ asset('js/image-uploader.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/image-zoom.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{ asset('js/script.js') }}"></script>
<script type="text/javascript">
    var stepper = document.querySelector('.stepper');
    var stepperInstace = new MStepper(stepper, {
        // options
        firstActive: 0 // this is the default
    })
</script>
</body>

</html>
