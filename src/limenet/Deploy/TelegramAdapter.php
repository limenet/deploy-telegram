<?php

namespace limenet\Deploy;

use Telegram\Bot\Api as TelegramApi;
use \Curl\Curl;

class TelegramAdapter implements PostDeployAdapterInterface
{
    private $config;

    public function config(array $config) : void
    {
        $this->config = $config;
    }

    public function run(Deploy $deploy) : bool
    {
        $curl = new Curl();

        $text = sprintf('`%s` was deployed on *%s*'."\n".'[%s](%s) `%s` by %s', $deploy->getVersion(), gethostname(), substr($deploy->strategy->getCommitHash(), 0, 8), $deploy->strategy->getCommitUrl(), $deploy->strategy->getCommitMessage(), $deploy->strategy->getCommitUsername());

        $curl->post(sprintf('https://api.telegram.org/bot%s/sendMessage', $this->config['bot_token']),[
          'chat_id'                  => $this->config['chat_id'],
          'parse_mode'               => 'markdown',
          'disable_web_page_preview' => true,
          'disable_notification'     => true,
          'text'                     => $text,
        ]);

        $error = $curl->error;
        $curl->close();

        return !$error;
    }
}
