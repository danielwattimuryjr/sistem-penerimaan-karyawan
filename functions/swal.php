<?php
function triggerSwal($icon, $title, $text) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
            Swal.fire({
                icon: '$icon',
                title: '$title',
                text: '$text'
            });
          </script>";
}