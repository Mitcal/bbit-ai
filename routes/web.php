<?php

use App\Livewire\BookingCalendar;
use App\Livewire\BookingWizard;
use App\Models\Booking;
use Illuminate\Support\Facades\Route;

Route::get('/', BookingCalendar::class)->middleware('throttle:60,1')->name('home');
Route::get('/book', BookingWizard::class)->middleware('throttle:60,1')->name('book');

Route::get('/api/bookings/events', function () {
    return Booking::all()->map(fn (Booking $booking) => [
        'id' => $booking->id,
        'title' => $booking->service->value,
        'start' => $booking->date->toDateString(),
        'backgroundColor' => '#3b82f6',
        'borderColor' => '#2563eb',
    ]);
})->middleware('throttle:60,1')->name('api.bookings.events');
