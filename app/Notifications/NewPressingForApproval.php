<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Pressing;

class NewPressingForApproval extends Notification
{
    use Queueable;

    public $pressing;

    public function __construct(Pressing $pressing)
    {
        $this->pressing = $pressing;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'pressing_id' => $this->pressing->id,
            'pressing_name' => $this->pressing->name,
            'owner_name' => $this->pressing->owner->name,
            'message' => 'Nouveau pressing en attente de validation',
            'type' => 'pressing_approval',
            'link' => route('admin.pressings.show', $this->pressing)
        ];
    }
}
