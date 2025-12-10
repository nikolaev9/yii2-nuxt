<?php

namespace app\controllers;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use function App\Services\config;

class RabbitMQService
{

    private $connection;
    private AMQPChannel $channel;
    private static $instance;

    /**
     * Регистрируем клиент
     */
    private function createConnection(): AMQPStreamConnection
    {
        $rabbit_settings = config('queue.connections.rabbitmq')['hosts'][0];
        $this->connection = new AMQPStreamConnection(
            $rabbit_settings['host'],
            $rabbit_settings['port'],
            $rabbit_settings['user'],
            $rabbit_settings['password']
        );
        return $this->connection;
    }

    private function createClient()
    {
        $this->connection = $this->createConnection();
        $this->channel = $this->connection->channel();
        $arguments = new AMQPTable([
            'x-max-priority' => 10
        ]);
        $this->channel->queue_declare('messages_whatsapp', false, true, false, false, false, $arguments);
        $this->channel->queue_declare('messages_viber', false, true, false, false, false, $arguments);
        $this->channel->queue_declare('messages_telegram', false, true, false, false, false, $arguments);
        $this->channel->queue_declare('comments_tg', false, true, false, false, false, $arguments);
        $this->channel->queue_declare('audio-analysis-info', false, true, false, false, false, $arguments);
        $this->channel->queue_declare('messages_vk', false, true, false, false, false, $arguments);
        $this->channel->queue_declare('old_messages_telegram', false, true, false, false, false, $arguments);
        $this->channel->queue_declare('reactions_tg', false, true, false, false, false, $arguments);
        $this->channel->queue_declare('media_tg', false, true, false, false, false, $arguments);
        $this->channel->queue_declare('reactions_viber', false, true, false, false, false, $arguments);
        $this->channel->queue_declare('outgoing_messages_vk', false, true, false, false, false, $arguments);
        $this->channel->queue_declare('messages_report', false, true, false, false, false, $arguments);
    }

    /**
     * Реализуем класс RabbitqMQ
     * @return RabbitMQService
     */
    public static function getInstance(): RabbitMQService
    {
        if (!self::$instance) {
            self::$instance = new RabbitMQService();
            self::$instance->createClient();
        }
        return self::$instance;
    }

    /**
     * Отправляем в очередь сообщения
     * @param array $return_message
     * @param string $queue_name
     * @param int $priority
     */
    public function publishMessage(array $return_message, string $queue_name, int $priority = 0)
    {
        $msg = new AMQPMessage(
            json_encode($return_message),
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT, 'priority' => $priority]
        );
        $this->channel->basic_publish($msg, '', $queue_name);
    }


    public function getChannel()
    {
        return $this->channel;
    }

    public function closeConnection()
    {
        $this->connection->close();
    }

    public function closeChannel()
    {
        $this->channel->close();
    }


}
