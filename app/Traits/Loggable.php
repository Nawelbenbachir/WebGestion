<?php

namespace App\Traits;

use App\Models\ActiviteLog;

trait Loggable
{
    public function logActivite(string $action): void
    {
        ActiviteLog::create([
            'user_id'   => auth()->id(),
            'societe_id' => session('current_societe_id'),
            'action'    => $action,
            'modele'    => class_basename($this),
            'donnees'   => $this->toArray(),
        ]);
    }
}