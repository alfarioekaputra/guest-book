<?= $this->extend('layouts/app_layout') ?>

<?= $this->section('content') ?>
<div class="col-12">
  <div class="card">
    <div class="card-body">
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h2 class="page-title">
              Daftar Pengguna
            </h2>
          </div>
          <div class="col-auto ms-auto">
            <div class="btn-list">
              <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <line x1="12" y1="5" x2="12" y2="19" />
                  <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Tambah Pengguna
              </a>
              <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#modal-report" aria-label="Create new report">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <line x1="12" y1="5" x2="12" y2="19" />
                  <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="table-responsive mt-5">
        <table id="user-table" class="table">
          <thead class="sticky-top">
            <tr>
              <th>NO</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table-body">

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#user-table').DataTable({
      "processing": true,
      "serverSide": true,
      "ordering": true,
      "scrollX": true,
      "oLanguage": {
        "sLengthMenu": "Tampilkan _MENU_ data per halaman",
        "sSearch": "Pencarian: ",
        "sZeroRecords": "Maaf, tidak ada data yang ditemukan",
        "sInfo": "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
        "sInfoEmpty": "Menampilkan 0 s/d 0 dari 0 data",
        "sInfoFiltered": "(di filter dari _MAX_ total data)",
        "oPaginate": {
          "sFirst": "<<",
          "sLast": ">>",
          "sPrevious": "<",
          "sNext": ">"
        }
      },
      "ajax": {
        "url": "<?php echo site_url('users/ajaxList') ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [],
        "orderable": false,
      }],
      "columns": [{
          data: 'no'
        },
        {
          data: 'username'
        },
        {
          data: 'email'
        },
        {
          data: null,
          render: function(data, type, row) {
            return `
              <a href="/users/edit/${row.id}" class="btn btn-md btn-primary">Edit</a>
              <a href="/users/delete/${row.id}" class="btn btn-md btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Delete</a>
            `
          }
        }
      ]
    });
  });
</script>
<?= $this->endSection() ?>