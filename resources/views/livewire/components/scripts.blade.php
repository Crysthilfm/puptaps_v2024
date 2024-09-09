
<script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('js/profilePicture.js') }}"></script>
<script src="{{ asset('js/show-password.js') }}"></script>
<script src="{{ asset('js/show-confirm-password.js') }}"></script>
<script src="{{ asset('js/alert-message-modal.js') }}"></script>
<script src="{{ asset('js/preview-profile.js') }}"></script>
<script src="{{ asset('js/same-address.js') }}"></script>
<script src="{{ asset('js/validate-inputs.js') }}"></script>

{{-- Jquery --}}
<script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
{{-- <script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script> --}}


{{-- Slick-Carousel JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
{{-- <link rel="stylesheet" href="{{ asset('slick/slick/slick.css') }}">
<link rel="stylesheet" href="{{ asset('slick/slick/slick-theme.css') }}"> --}}

{{-- Chart JS --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="{{ asset('js/board-passers.js') }}"></script>
<script src="{{ asset('js/chart-student-per-course.js') }}"></script>
<script src="{{ asset('js/chart-student-per-sex.js') }}"></script>
<script src="{{ asset('js/chart-board-exam.js') }}"></script>
<script src="{{ asset('js/chart-civil-service.js') }}"></script>
<script src="{{ asset('js/chart-employed-alumni.js') }}"></script>
<script src="{{ asset('js/chart-total-tracer.js') }}"></script>
<script src="{{asset('js/alumni-employment-type.js')}}"></script>
<script src="{{ asset('js/chart-inline-with-course.js') }}"></script>

{{-- Rich Text Editor --}}
<script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>

{{-- Animate on Scroll --}}
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
</script>

{{-- Sweet Alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('success-alert', event => {
        Swal.fire({
            icon: "success",
            title: "Success",
            text: "Email was sent to recipients",
        });
    });
    window.addEventListener('success-all', event => {
        Swal.fire({
            icon: "success",
            title: "Success"
        });
    });
    window.addEventListener('error-alert', event => {
        Swal.fire({
            icon: "error",
            title: "Error!",
            text: "Something went wrong with sending the emails",
            footer: "Contact the developers or Superadmin if the problem still persists"
        });
    });
    window.addEventListener('error-all', event => {
        Swal.fire({
            icon: "error",
            title: "Error!",
        });
    });
</script>

{{-- Show Dashboard Tables --}}
<script src="{{ asset('js/show-table-modal.js') }}"></script>

{{-- DataTable --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    var isNotShown=true;
    if(isNotShown) {
        window.onload = (event) => {
            let myAlert = document.querySelectorAll('.toast')[0];
            if (myAlert) {
                let bsAlert = new bootstrap.Toast(myAlert);
                bsAlert.show();
            }
            isNotShown=false;
            console.log(isNotShown);
        };
        
    }
    $(document).ready( function () {
        $('#tracerTable').DataTable( {
            'columnDefs'        : [
                { 
                    'searchable'    : false, 
                    'targets'       : [0,7] 
                },
            ],
            "pageLength": 50,
            "autoWidth": false,
        } );
        $('#tracerTable1').DataTable();
        $('#tracerTable2').DataTable();
        })
        $('.fa-circle-plus').hover(function() {
            $(this).addClass('fa-beat');
        }, function() {
            $(this).removeClass('fa-beat');
    });

    $(document).ready(function() {
        $('.deleteQuestionBtn').click(function (e){
            e.preventDefault();

            var question_id = $(this).val();
            $('#question_id').val(question_id);
            $('#questionDelete').modal('show');
        });
        $('.deleteOptionBtn').click(function (e){
            e.preventDefault();

            var option_id = $(this).val();
            $('#option_id').val(option_id);
            $('#optionDelete').modal('show');
        });
        $('.editOptionBtn').click(function (e){
            e.preventDefault();
            var option_id = $(this).val();
            $('#option_id_edit').val(option_id);
            $('#optionEdit').modal('show');
        });
        $('.helpTracerReminder').click(function (e){
            e.preventDefault();
            $('#helpTracerReminder').modal('show');
        });

        $('#selectAll').click(function() {
            console.log('selected');
            if ($(this).prop('checked')) {
                $('.checkbox_ids').prop('checked', true);
            } else {
                $('.checkbox_ids').prop('checked', false);
            }
        });
        
        $('#clearCheckboxes').click(function() {
            $('.checkbox_ids').prop('checked', false);
        });
        
    });
    

</script>
