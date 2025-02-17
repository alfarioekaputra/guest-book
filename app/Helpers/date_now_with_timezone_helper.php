<?php

use CodeIgniter\I18n\Time;

function dateNow()
{
    return new Time('now', 'Asia/Jakarta', 'id_ID');
}
