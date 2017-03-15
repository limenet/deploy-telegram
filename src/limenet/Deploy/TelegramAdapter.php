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

    public function run(Deploy $deploy) : bool
    {
        $telegram = new TelegramApi($this->config['bot_token']);

        $telegram->sendMessage([
          'chat_id'                  => $this->config['chat_id'],
          'parse_mode'               => 'markdown',
          'disable_web_page_preview' => true,
          'disable_notification'     => true,
          'text'                     => '`'.$deploy->getVersion().'` was deployed on *'.gethostname().'*'."\n".'['.substr($deploy->strategy->getCommitHash(), 0, 8).']('.$deploy->strategy->getCommitUrl().') `'.$deploy->strategy->getCommitMessage().'` by '.$deploy->strategy->getCommitUsername(),
        ]);

        return true;
    }
}
