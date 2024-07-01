<?php

namespace App\Events;

use App\Models\Newsletter;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsletterUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $newsletter;

    public function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    public function broadcastOn()
    {
        return new Channel('newsletters');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->newsletter->id,
            'title' => $this->newsletter->title,
            'content' => $this->newsletter->content,
            'image' => $this->newsletter->image,
            'created_at' => $this->newsletter->created_at,
            'updated_at' => $this->newsletter->updated_at,
        ];
    }
}


