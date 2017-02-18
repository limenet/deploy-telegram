<?php

namespace limenet\Deploy;

use Telegram\Bot\Api as TelegramApi;

class TelegramAdapter implements PostDeployAdapterInterface
{
    private $config;

    public function config(array $config) : void
    {
        $this->config = $config;
    }

    public function run(array $payload) : bool
    {
        $telegram = new TelegramApi($this->config['bot_token']);

        $telegram->sendMessage([
          'chat_id'                  => $this->config['chat_id'],
          'parse_mode'               => 'markdown',
          'disable_web_page_preview' => true,
          'disable_notification'     => true,
          'text'                     => '`'.$this->getVersion().'` was deployed on *'.gethostname().'*'."\n".'['.substr($this->payload['head_commit']['id'], 0, 8).']('.$this->payload['head_commit']['url'].') `'.$this->payload['head_commit']['message'].'` by [@'.$this->payload['head_commit']['author']['username'].'](https://github.com/'.$this->payload['head_commit']['author']['username'].')',
        ]);

        return true;
    }
}
