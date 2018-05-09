<?php

/* 
DEPRECATED. BUSCAR CÓMO CARGARÍA ESTE ADMIN.PHP
 */

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/registers','Admin/AdminRegisters@index');

});