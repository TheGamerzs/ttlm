<?php

namespace App\Http\Livewire;

trait SendsAlerts
{
    protected function successAlert($title, $message = null): void
    {
        $this->sendAlert($title, $message);
    }

    protected function warningAlert($title, $message = null): void
    {
        $this->sendAlert($title, $message, 'warning');
    }

    protected function informAlert($title, $message = null): void
    {
        $this->sendAlert($title, $message, 'info');
    }

    protected function errorAlert($title, $message = null): void
    {
        $this->sendAlert($title, $message, 'error');
    }

    protected function sendAlert($title, $message = null, $type = 'success'): void
    {
        $this->emit('alert', $title, $message ?? '', $type);
    }
}
