<!--  Bootstrap 5.3 JS  -->
<script src="/sistem-penerimaan-karyawan/assets/js/popper.min.js" crossorigin="anonymous"></script>
<script src="/sistem-penerimaan-karyawan/assets/js/bootstrap.min.js" crossorigin="anonymous"></script>

<!-- JQuery -->
<script src="/sistem-penerimaan-karyawan/assets/js/jquery-3.7.1.min.js" crossorigin="anonymous"></script>

<!--  SweetAlert2  -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');
        const type = urlParams.get('type');

        if (message && type) {
            Swal.fire({
                title: type === 'success' ? 'Berhasil!' : 'Kesalahan!',
                text: message,
                icon: type,
                confirmButtonText: 'OK'
            });
        }
    });
</script>