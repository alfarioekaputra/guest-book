<?php
if (session()->getFlashData('success')) {
?>
  <div class="alert alert-important alert-success alert-dismissible" role="alert">
    <div class="d-flex">
      <div>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="icon alert-icon"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          stroke-width="2"
          stroke="currentColor"
          fill="none"
          stroke-linecap="round"
          stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
          <path d="M5 12l5 5l10 -10"></path>
        </svg>
      </div>
      <div><?= session()->getFlashData('success') ?></div>
    </div>
    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
  </div>
<?php
}
?>
<?php
if (session()->getFlashData('error')) {
?>
  <div class="alert alert-important alert-danger alert-dismissible" role="alert">
    <div class="d-flex">
      <div>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="icon alert-icon"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          stroke-width="2"
          stroke="currentColor"
          fill="none"
          stroke-linecap="round"
          stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <circle cx="12" cy="12" r="9" />
          <line x1="12" y1="8" x2="12" y2="12" />
          <line x1="12" y1="16" x2="12.01" y2="16" />
        </svg>
      </div>
      <div><?= session()->getFlashData('error') ?></div>
    </div>
    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
  </div>
<?php
} else if (session()->getFlashData('errors')) {
?>
  <div class="alert alert-important alert-danger alert-dismissible" role="alert">
    <div class="d-flex">
      <div>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="icon alert-icon"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          stroke-width="2"
          stroke="currentColor"
          fill="none"
          stroke-linecap="round"
          stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <circle cx="12" cy="12" r="9" />
          <line x1="12" y1="8" x2="12" y2="12" />
          <line x1="12" y1="16" x2="12.01" y2="16" />
        </svg>
      </div>
      <div>
        <?php if (is_array(session()->getFlashData('errors'))) : ?>
          <?php foreach (session()->getFlashData('errors') as $error) : ?>
            <?= $error ?>
            <br>
          <?php endforeach ?>
        <?php else : ?>
          <?= session()->getFlashData('errors') ?>
        <?php endif ?>
      </div>
    </div>
    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
  </div>
<?php
}
?>