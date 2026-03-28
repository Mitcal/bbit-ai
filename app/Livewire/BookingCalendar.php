<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('All Bookings')]
class BookingCalendar extends Component
{
    public function render(): View
    {
        return view('livewire.booking-calendar');
    }
}
