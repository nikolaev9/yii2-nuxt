<?php

namespace app\controllers;

use App\Messengers\MessengerFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Объект определенного мессенджера
    protected $messenger;
    protected $type_messenger;

    public function __construct(Request $request)
    {
        $this->type_messenger = $request->get('type_messenger');

        switch ($this->type_messenger) {
            case 'whatsapp':
                $this->messenger = (new MessengerFactory())->createWhatsappMessenger();
                break;
            case 'viber':
                $this->messenger = (new MessengerFactory())->createViberMessenger();
                break;
            case 'telegram':
                $this->messenger = (new MessengerFactory())->createTelegramMessenger();
                break;
            case 'vk':
                $this->messenger = (new MessengerFactory())->createVkMessenger();
                break;
            case 'max':
                $this->messenger = (new MessengerFactory())->createMaxMessenger();
        }

    }
}
