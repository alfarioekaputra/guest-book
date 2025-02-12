<div class="row">
  <div class="col-md-6 col-xl-12">
    <?= form_open(isset($user) ? url_to('UsersController::update', $user->id) : url_to('UsersController::store'), ['csrf_id' => 'register-id']); ?>
    <div class="mb-3">
      <label class="form-label">
        <?= form_label('Email', 'email', ['class' => 'form-label']) ?>
      </label>
      <?php if (isset($user->email)): ?>
        <!-- Tampilkan email sebagai teks statis -->
        <span><?= esc($user->email) ?></span>

      <?php else: ?>
        <!-- Email editable saat create -->
        <?= form_input(['value' => old('email'), 'type' => 'email', 'name' => 'email', 'class' => 'form-control', 'required' => true]) ?>
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <label class="form-label">
        <?= form_label('Username', 'username', ['class' => 'form-label']) ?>
      </label>
      <?= form_input(['value' => $user->username ?? '', 'name' => 'username', 'class' => 'form-control']) ?>
    </div>
    <div class="mb-3">
      <label class="form-label">
        <?= form_label('Password', 'password', ['class' => 'form-label']) ?>
      </label>
      <?= form_input(['name' => 'password', 'type' => 'password', 'class' => 'form-control']) ?>
    </div>
    <?= form_submit(['value' => 'Simpan', 'class' => 'btn btn-primary btn-block']) ?>
    <?= form_close() ?>
  </div>
</div>