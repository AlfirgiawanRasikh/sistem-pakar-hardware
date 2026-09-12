<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireLogin();
?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script src="adminlte/plugins/datatables/jquery.dataTables.min.js"></script>

<script src="adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

<script>

$(document).ready(function(){

    $('#tabelGejala').DataTable({
        responsive:true,
        autoWidth:false
    });

    $('#tabelKerusakan').DataTable({
        responsive:true,
        autoWidth:false
    });

    $('#tabelAturan').DataTable({
        responsive:true,
        autoWidth:false
    });

    $('#tabelPengguna').DataTable({
        responsive:true,
        autoWidth:false
    });

    $('#tabelRiwayat').DataTable({
        responsive:true,
        autoWidth:false
    });

});

</script>

</body>
</html>