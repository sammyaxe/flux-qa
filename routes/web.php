<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::index')->name('index');

Route::prefix('examples')->name('examples.')->group(function () {
    Route::livewire('/accordion-state', 'pages::example.accordion-state')->name('accordion-state');
    Route::livewire('/range-date-picker-min-max', 'pages::example.range-date-picker-min-max')->name('range-date-picker-min-max');
    Route::livewire('/time-picker-performance', 'pages::example.time-picker-performance')->name('time-picker-performance');
});
