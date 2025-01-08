<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> Beta 4.0
    </div>
    <strong>Copyright &copy; 2024 <a href="https://testing.com">testing.com</a>.</strong> All rights reserved.
</footer>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

<!-- dataTables js -->
<script src="{{ asset('assets/plugins/datatables2/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<script>
    /** add active class and stay opened when selected */
    var url = window.location.href.split('#')[0];
    var spinner = $('#loader');

    // for sidebar menu entirely but not cover treeview
    $('ul.nav-sidebar a').filter(function() {
        return this.href == url;
    }).addClass('active');

    // for treeview
    $('ul.nav-treeview a').filter(function() {
        return this.href == url;
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');


    $('.checkAlltogle').click(function(event) {
        if (this.checked) {
            $('#' + $(this).parents("table").attr('id') + ' :checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $('#' + $(this).parents("table").attr('id') + ' :checkbox').each(function() {
                this.checked = false;
            });
        }
    });
</script>
