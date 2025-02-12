<?= $this->extend('layouts/app_layout') ?>

<?= $this->section('content') ?>
<h1>Tambah Pengguna</h1>
<?= $this->include('users/_form') ?>
<?= $this->endSection('content') ?>