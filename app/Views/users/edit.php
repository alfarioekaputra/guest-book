<?= $this->extend('layouts/app_layout') ?>

<?= $this->section('content') ?>
<h1>Ubah Pengguna <?= $user->username ?></h1>
<?= $this->include('users/_form', ['user' => $user]) ?>
<?= $this->endSection('content') ?>