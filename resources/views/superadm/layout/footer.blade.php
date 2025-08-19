<!-- Right sidebar -->
<!-- ============================================================== -->
<!-- .right-sidebar -->
<div class="right-sidebar">
    <div class="slimscrollright">
        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
        <div class="r-panel-body">
            <ul id="themecolors" class="mt-3">
                <li><b>With Light sidebar</b></li>
                <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                <li><a href="javascript:void(0)" data-theme="red" class="red-theme">3</a></li>
                <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme working">4</a></li>
                <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                <li class="d-block mt-4"><b>With Dark sidebar</b></li>
                <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">9</a></li>
                <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme ">12</a></li>
            </ul>
            <ul class="mt-3 chatonline">
                <li><b>Chat option</b></li>
                <li>
                    <a href="javascript:void(0)"><img src="{{ asset('asset/images/users/1.jpg') }}" alt="user-img"
                            class="rounded-circle"> <span>Varun Dhavan <small
                                class="text-success">online</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="{{ asset('asset/images/users/2.jpg') }}" alt="user-img"
                            class="rounded-circle"> <span>Genelia Deshmukh <small
                                class="text-warning">Away</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="{{ asset('asset/images/users/3.jpg') }}" alt="user-img"
                            class="rounded-circle"> <span>Ritesh Deshmukh <small
                                class="text-danger">Busy</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="{{ asset('asset/images/users/4.jpg') }}" alt="user-img"
                            class="rounded-circle"> <span>Arijit Sinh <small
                                class="text-muted">Offline</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="{{ asset('asset/images/users/5.jpg') }}" alt="user-img"
                            class="rounded-circle"> <span>Govinda Star <small
                                class="text-success">online</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="{{ asset('asset/images/users/6.jpg') }}" alt="user-img"
                            class="rounded-circle"> <span>John Abraham<small
                                class="text-success">online</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="{{ asset('asset/images/users/7.jpg') }}" alt="user-img"
                            class="rounded-circle"> <span>Hritik Roshan<small
                                class="text-success">online</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="{{ asset('asset/images/users/8.jpg') }}" alt="user-img"
                            class="rounded-circle"> <span>Pwandeep rajan <small
                                class="text-success">online</small></span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Right sidebar -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- footer -->
<!-- ============================================================== -->
<footer class="footer">
    © 2019 Alf Engineering by wrappixel.com
</footer>
<!-- ============================================================== -->
<!-- End footer -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->


<!-- <script src="{{ asset('asset/plugins/jquery/jquery.min.js') }}"></script> -->
<!-- Bootstrap tether Core JavaScript -->


<script>
    $(document).ready(function() {
        console.log("jQuery version:", $.fn.jquery);
        $('.datatables').DataTable({
            responsive: true,
            pageLength: 10, // show 10 by default
            ordering: true,
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ roles",
                paginate: {
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    });

    // SweetAlert Delete Confirmation
    $(document).ready(function() {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                let form = this.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

<script src="{{ asset('asset/plugins/popper/popper.min.js') }}"></script>
<script src="{{ asset('asset/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{ asset('asset/js/jquery.slimscroll.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('asset/js/waves.js') }}"></script>
<!--Menu sidebar -->
<script src="{{ asset('asset/js/sidebarmenu.js') }}"></script>
<!--stickey kit -->
<script src="{{ asset('asset/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
<script src="{{ asset('asset/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<!--Custom JavaScript -->
<script src="{{ asset('asset/js/custom.min.js') }}"></script>
<!-- ============================================================== -->
<!-- Style switcher -->
<!-- ============================================================== -->
<script src="{{ asset('asset/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
<script src="{{ asset('asset/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>

<script src="{{ asset('asset/plugins/sweetalert/sweetalert2@11.js') }}"></script>

</body>

</html>
