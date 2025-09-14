<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: @json(session('success')),
                confirmButtonColor: '#3085d6',
            });
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: @json(session('error')),
                confirmButtonColor: '#d33',
            });
        });
    </script>
@endif

@foreach ($errors->all() as $err)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: @json($err),
                confirmButtonColor: '#d33',
            });
        });
    </script>
@endforeach
