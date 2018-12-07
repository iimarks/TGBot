<?php


class TGBot
{
    private $token;
    private $ctoken;
    private $fparam;

    public function __construct($input, $ctoken, $fparam, $token)
    {
        $this->curl = curl_init();
        curl_setopt_array($this->curl, [
            CURLOPT_POST           => true,
            CURLOPT_FORBID_REUSE   => true,
            CURLOPT_RETURNTRANSFER => true,
            ]);
        $this->token = $token;
        $this->input = $input;
        $this->ctoken = $ctoken;
        $this->fparam = $fparam;
        $this->update = json_decode($this->input, true);
        if (!empty($this->update)) {
            $this->chat_id = $this->update['message']['chat']['id'];
            $this->user_id = $this->update['message']['from']['id'];
            $this->is_bot = $this->update['message']['from']['is_bot'];
            $this->first_name = htmlspecialchars($this->update['message']['from']['first_name']);
            $this->last_name = htmlspecialchars($this->update['message']['from']['last_name']);
            $this->username = htmlspecialchars($this->update['message']['from']['username']);
            $this->type = $this->update['message']['chat']['type'];
            $this->document = $this->update['message']['document'];
            $this->photo = $this->update['message']['photo'];
            $this->video = $this->update['message']['video'];
            if ($this->type == 'supergroup' or $this->type == 'group' or $this->type == 'channel') {
                $this->title = $this->update['message']['chat']['title'];
                if ($this->type == 'group') {
                    $this->all_members_are_administrators = $this->update['message']['chat']['all_members_are_administrators'];
                }
            }
            if (isset($this->photo)) {
                $this->photo_name = $this->update['message']['photo']['file_name'];
                $this->photo_mime_type = $this->update['message']['photo']['mime_type'];
                $this->photo_file_id = $this->update['message']['photo'][0]['file_id'];
                $this->photo_file_size = $this->update['message']['photo']['file_size'];
            }
            if (isset($this->document)) {
                $this->document_name = $this->update['message']['document']['file_name'];
                $this->document_mime_type = $this->update['message']['document']['mime_type'];
                $this->document_file_id = $this->update['message']['document']['file_id'];
                $this->document_file_size = $this->update['message']['document']['file_size'];
            }
            if (isset($this->video)) {
                $this->video_name = $this->update['message']['video']['file_name'];
                $this->video_mime_type = $this->update['message']['video']['mime_type'];
                $this->video_file_id = $this->update['message']['video']['file_id'];
                $this->video_file_size = $this->update['message']['video']['file_size'];
            }
            $this->text = $this->update['message']['text'];
            $this->message_id = $this->update['message']['message_id'];
            $this->reply_to_message = $this->update['message']['reply_to_message'];
            if (isset($this->reply_to_message)) {
                $this->reply_to_message_text = $this->update['message']['reply_to_message']['text'];
                $this->reply_to_message_id = $this->update['message']['reply_to_message']['message_id'];
                $this->reply_to_message_first_name = $this->update['message']['reply_to_message']['from']['first_name'];
                $this->reply_to_message_last_name = $this->update['message']['reply_to_message']['from']['last_name'];
                $this->reply_to_message_username = $this->update['message']['reply_to_message']['from']['username'];
                $this->reply_to_message_user_id = $this->update['message']['reply_to_message']['from']['id'];
                $this->reply_to_message_language_code = $this->update['message']['reply_to_message']['from']['language_code'];
                $this->reply_to_message_is_bot = $this->update['message']['reply_to_message']['from']['is_bot'];
                if (isset($this->update['message']['reply_to_message']['photo'])) {
                    $this->reply_photo = $this->update['message']['reply_to_message']['photo'];
                    $this->reply_photo_file_id = $this->update['message']['reply_to_message']['photo'][0]['file_id'];
                    $this->reply_photo_caption = $this->update['message']['reply_to_message']['caption'];
                }
                if (isset($this->update['message']['reply_to_message']['document'])) {
                    $this->reply_document = $this->update['message']['reply_to_message']['document'];
                    $this->reply_document_file_id = $this->update['message']['reply_to_message']['document']['file_id'];
                    $this->reply_document_caption = $this->update['message']['reply_to_message']['caption'];
                }
                if (isset($this->update['message']['reply_to_message']['video'])) {
                    $this->reply_video = $this->update['message']['reply_to_message']['video'];
                    $this->reply_video_file_id = $this->update['message']['reply_to_message']['video']['file_id'];
                    $this->reply_video_caption = $this->update['message']['reply_to_message']['caption'];
                }
            }
            if (isset($this->update['channel_post'])) {
                $this->chat_id = $this->update['channel_post']['chat']['id'];
                $this->text = $this->update['channel_post']['text'];
                $this->message_id = $this->update['channel_post']['message_id'];
                $this->reply_to_message_id = $this->update['channel_post']['reply_to_message']['message_id'];
                $this->reply_to_message_title = htmlspecialchars($this->update['channel_post']['reply_to_message']['chat']['title']);
                $this->type = $this->update['channel_post']['chat']['type'];
                $this->author = $this->update['channel_post']['author_signature'];
                $this->date = $this->update['channel_post']['date'];
            } else {
                if (isset($this->update['edited_message'])) {
                    $this->text = $this->update['edited_message']['text'];
                    $this->edited_message_id = $this->update['edited_message']['message_id'];
                    $this->user_id = $this->update['edited_message']['from']['id'];
                    $this->is_bot = $this->update['edited_message']['from']['is_bot'];
                    $this->first_name = htmlspecialchars($this->update['message']['from']['first_name']);
                    $this->last_name = htmlspecialchars($this->update['message']['from']['last_name']);
                    $this->username = htmlspecialchars($this->update['message']['from']['username']);
                    $this->language_code = $this->update['edited_message']['from']['language_code'];
                    $this->chat_id = $this->update['edited_message']['chat']['id'];
                    $this->type = $this->update['edited_message']['chat']['type'];
                    $this->author = $this->update['edited_message']['author_signature']; //e pensare che volevi subito la risposta
                    if ($this->type == 'supergroup' or $this->type == 'group') {
                        $this->title = htmlspecialchars($this->update['edited_message']['chat']['title']);
                        if ($this->type == 'group') {
                            $this->all_members_are_administrators = $this->update['edited_message']['chat']['all_members_are_administrators'];
                        }
                    }
                    $this->reply_to_message_id = $this->update['edited_message']['message']['reply_to_message']['message_id'];
                    $this->reply_to_message_first_name = htmlspecialchars($this->update['edited_message']['message']['reply_to_message']['from']['first_name']);
                    $this->reply_to_message_last_name = htmlspecialchars($this->update['edited_message']['message']['reply_to_message']['from']['last_name']);
                    $this->reply_to_message_username = htmlspecialchars($this->update['edited_message']['message']['reply_to_message']['from']['username']);
                    $this->reply_to_message_user_id = $this->update['edited_message']['message']['reply_to_message']['from']['id'];
                    $this->reply_to_message_language_code = $this->update['edited_message']['message']['reply_to_message']['from']['language_code'];
                    $this->reply_to_message_is_bot = $this->update['edited_message']['message']['reply_to_message']['from']['is_bot'];
                    $this->date = $this->update['edited_message']['date'];
                    $this->edit_date = $this->update['edited_message']['edit_date'];
                    $this->location = $this->update['edited_message']['location'];
                    if (isset($this->location)) {
                        $this->edited_longitudine = $this->update['edited_message']['location']['longitude'];
                        $this->edited_latitude = $this->update['edited_message']['location']['latitude'];
                    }
                }
                if (isset($this->update['edited_channel_post'])) {
                    $this->text = $this->update['edited_channel_post']['text'];
                    $this->edited_message_id = $this->update['edited_channel_post']['message_id'];
                    $this->user_id = $this->update['edited_channel_post']['from']['id'];
                    $this->is_bot = $this->update['edited_channel_post']['from']['is_bot'];
                    $this->first_name = htmlspecialchars($this->update['edited_channel_post']['from']['first_name)']);
                    $this->last_name = htmlspecialchars($this->update['edited_channel_post']['from']['last_name']);
                    $this->username = htmlspecialchars($this->update['edited_channel_post']['from']['username']);
                    $this->language_code = $this->update['edited_channel_post']['from']['language_code'];
                    $this->chat_id = $this->update['edited_channel_post']['chat']['id'];
                    $this->type = $this->update['edited_channel_post']['chat']['type'];
                    $this->author = $this->update['edited_channel_post']['author_signature'];
                    $this->date = $this->update['edited_channel_post']['date'];
                    $this->edit_date = $this->update['edited_channel_post']['edit_date'];
                    $this->reply_to_message_id = $this->update['edited_channel_post']['message']['reply_to_message']['message_id'];
                    $this->reply_to_message_first_name = htmlspecialchars($this->update['edited_channel_post']['message']['reply_to_message']['from']['first_name']);
                    $this->reply_to_message_last_name = htmlspecialchars($this->update['edited_channel_post']['message']['reply_to_message']['from']['last_name']);
                    $this->reply_to_message_username = htmlspecialchars($this->update['edited_channel_post']['message']['reply_to_message']['from']['username']);
                    $this->reply_to_message_user_id = $this->update['edited_channel_post']['message']['reply_to_message']['from']['id'];
                    $this->reply_to_message_language_code = $this->update['edited_channel_post']['message']['reply_to_message']['from']['language_code'];
                    $this->reply_to_message_is_bot = $this->update['edited_channel_post']['message']['reply_to_message']['from']['is_bot'];
                    $this->location = $this->update['edited_channel_post']['location'];
                    if (isset($this->location)) {
                        $this->edited_longitudine = $this->update['edited_channel_post']['location']['longitude'];
                        $this->edited_latitude = $this->update['edited_channel_post']['location']['latitude'];
                    }
                }
                $this->cbdata = $this->update['callback_query'];
                if (isset($this->cbdata)) {
                    $this->message_id = $this->update['callback_query']['message']['message_id'];
                    $this->chat_id = $this->update['callback_query']['message']['chat']['id'];
                    $this->user_id = $this->update['callback_query']['from']['id'];
                    $this->cbdata_text = $this->update['callback_query']['data'];
                    $this->first_name = htmlspecialchars($this->update['callback_query']['from']['first_name']);
                    $this->last_name = htmlspecialchars($this->update['callback_query']['from']['last_name']);
                    $this->username = htmlspecialchars($this->update['callback_query']['from']['username']);
                    $this->is_bot = $this->update['callback_query']['from']['is_bot'];
                    $this->language_code = $this->update['callback_query']['from']['language_code'];
                    $this->type = $this->update['callback_query']['message']['chat']['type'];
                    if ($this->type == 'supergroup' or $this->type == 'group') {
                        $this->title = $this->update['callback_query']['message']['chat']['title'];
                    }
                    $this->cbid = $this->update['callback_query']['id'];
                    $this->author = $this->update['callback_query']['author_signature'];
                    $this->reply_to_message_id = $this->update['callback_query']['message']['reply_to_message']['message_id'];
                    $this->reply_to_message_first_name = htmlspecialchars($this->update['callback_query']['message']['reply_to_message']['from']['first_name']);
                    $this->reply_to_message_last_name = htmlspecialchars($this->update['callback_query']['message']['reply_to_message']['from']['last_name']);
                    $this->reply_to_message_username = htmlspecialchars($this->update['callback_query']['message']['reply_to_message']['from']['username']);
                    $this->reply_to_message_user_id = $this->update['callback_query']['message']['reply_to_message']['from']['id'];
                    $this->reply_to_message_language_code = $this->update['callback_query']['message']['reply_to_message']['from']['language_code'];
                    $this->reply_to_message_is_bot = $this->update['callback_query']['message']['reply_to_message']['from']['is_bot'];
                }
            }
        }
    }

    public function SecTest()
    {
        if ($this->ctoken != $this->fparam or $this->token == null) {
            die('Security test: not passed, script killed.');
        } else {
            echo 'Security test: OK. <br />';
        }
    }

    public function settings($settings = ['disable_web_page_preview' => 'false', 'parse_mode' => 'HTML', 'MySQL' => true, 'PostgreSQL' => true])
    {
        $this->settings = $settings;
        $this->table_name = $this->settings['table_name'];
    }

    public function botAdmin($userID = null)
    {
        if ($userID == null) {
            $userID = $this->user_id;
        }
        foreach ($this->settings['admins'] as $admin) {
            if ($admin == $userID) {
                return true;
            }
        }
    }

    public function PostgreDBCredentials($host, $username, $password, $dbname)
    {
        if ($this->settings['PostgreSQL']) {
            try {
                $this->pdb = new PDO('pgsql:host='.$host.';dbname='.$dbname, $username, $password);
                echo 'Database PostgreSQL: OK <br />';
            } catch (PDOException $e) {
                die('Database PostgreSQL: Connection problem. <br />'.$e->getMessage());
            }
        }
    }

    public function MySQLDBCredentials($host, $username, $password, $dbname)
    {
        if ($this->settings['MySQL']) {
            try {
                //Thanks to t.me/Nen3one for remember me that I have to do $this->mdb
                $this->mdb = new PDO('mysql:host='.$host.';dbname='.$dbname, $username, $password);
                echo 'Database MySQL: OK <br />';
            } catch (PDOException $e) {
                die('Database MySQL: Connection problem. 
               Error: <b>'.$e->getMessage().'</b></br >');
            }
        }
    }

    private function Request($link, $data = [])
    { //thanks to @windoz for helping me to speedup curl
        curl_setopt_array($this->curl, [
            CURLOPT_URL        => 'https://api.telegram.org/bot'.$this->token.$link,
            CURLOPT_POSTFIELDS => $data,
        ]);

        return curl_exec($this->curl);
    }

    public function getBotInfo($info)
    {
        $get = json_decode(self::Request('/getme'), true);
        if ($info == 'username') {
            return $get['result']['username'];
        } elseif ($info == 'name') {
            return $get['result']['first_name'];
        } elseif ($info == 'id') {
            return $get['result']['id'];
        }
    }

    public function sendMessage($chat_id, $text, $reply_markup = false, $button_type = 'inline', $reply_to_message_id = null, $parse_mode = null, $disable_web_page_preview = null)
    {
        if ($parse_mode == null) {
            $parse_mode = $this->settings['parse_mode'];
        }
        $args = [
            'chat_id'                  => $chat_id,
            'text'                     => $text,
            'parse_mode'               => $parse_mode,
            'reply_to_message_id'      => $reply_to_message_id,
            'disable_web_page_preview' => $this->settings['disable_web_page_preview'],
        ];
        if ($reply_markup) {
            if ($button_type == 'inline') {
                $reply_markup = json_encode(['inline_keyboard' => $reply_markup]);
                $args['reply_markup'] = $reply_markup;
            } elseif ($button_type == 'button') {
                $reply_markup = json_encode(['keyboard' => $reply_markup, 'resize_keyboard' => true]);
                $args['reply_markup'] = $reply_markup;
            }
        }

        return json_decode(self::Request('/sendMessage', $args), true);
    }

    public function answerCallbackQuery($cbid, $text, $showalert = false, $url = null, $cache_time = null)
    {
        $args = [
            'callback_query_id' => $cbid,
            'text'              => $text,
            'show_alert'        => $showalert,
            'url'               => $url,
            'cache_time'        => $cache_time,
        ];

        return json_decode(self::Request('/answerCallbackQuery', $args), true);
    }

    public function editMessage($chat_id, $message_id, $text, $reply_markup = null, $parse_mode = null)
    {
        if ($parse_mode == null) {
            $parse_mode = $this->settings['parse_mode'];
        }
        $args = [
            'chat_id'                  => $chat_id,
            'text'                     => $text,
            'parse_mode'               => $parse_mode,
            'message_id'               => $message_id,
            'disable_web_page_preview' => $this->settings['disable_web_page_preview'],
        ];
        if ($reply_markup) {
            $reply_markup = json_encode(['inline_keyboard' => $reply_markup]);
            $args['reply_markup'] = $reply_markup;
        }

        return self::Request('/editMessageText', $args);
    }

    public function sendPhoto($chat_id, $photo, $caption = null, $reply_markup = false, $parse_mode = null, $reply_to_message_id = null, $disable_notification = false, $button_type = 'inline')
    {
        if ($parse_mode == null) {
            $parse_mode = $this->settings['parse_mode'];
        }
        $args = [
            'chat_id'              => $chat_id,
            'photo'                => $photo,
            'caption'              => $caption,
            'parse_mode'           => $parse_mode,
            'disable_notification' => $disable_notification,
            'reply_to_message_id'  => $reply_to_message_id,
        ];

        if ($reply_markup) {
            if ($button_type == 'inline') {
                $reply_markup = json_encode(['inline_keyboard' => $reply_markup]);
                $args['reply_markup'] = $reply_markup;
            } elseif ($button_type == 'button') {
                $reply_markup = json_encode(['keyboard' => $reply_markup, 'resize_keyboard' => true]);
                $args['reply_markup'] = $reply_markup;
            }
        }

        return self::Request('/SendPhoto', $args);
    }

    public function ForwardMessage($to_chat_id, $from_chat_id, $message_id, $disable_notification)
    {
        $args = [
            'chat_id'              => $to_chat_id,
            'from_chat_id'         => $from_chat_id,
            'message_id'           => $message_id,
            'disable_notification' => $disable_notification,
        ];

        return self::Request('/ForwardMessage', $args);
    }

    public function sendAudio($chat_id, $audio, $caption = null, $reply_to_message_id = null, $reply_markup = false, $parse_mode = null, $duration = null, $performer = null, $title = null, $disable_notification = false)
    {
        if ($parse_mode == null) {
            $parse_mode = $this->settings['parse_mode'];
        }
        $args = [
            'chat_id'              => $chat_id,
            'audio'                => $audio,
            'caption'              => $caption,
            'reply_to_message_id'  => $reply_to_message_id,
            'duration'             => $duration,
            'performer'            => $performer,
            'title'                => $title,
            'disable_notification' => $disable_notification,
            'parse_mode'           => $parse_mode,
        ];
        if ($reply_markup) {
            if ($button_type == 'inline') {
                $reply_markup = json_encode(['inline_keyboard' => $reply_markup]);
                $args['reply_markup'] = $reply_markup;
            } elseif ($button_type == 'button') {
                $reply_markup = json_encode(['keyboard' => $reply_markup, 'resize_keyboard' => true]);
                $args['reply_markup'] = $reply_markup;
            }
        }

        return self::Request('/sendAudio', $args);
    }

    public function sendDocument($chat_id, $document, $caption = null, $reply_to_message_id = null, $reply_markup = false, $thumb = null, $parse_mode = null, $disable_notification = false)
    {
        if ($parse_mode == null) {
            $parse_mode = $this->settings['parse_mode'];
        }
        $args = [
            'chat_id'              => $chat_id,
            'document'             => $document,
            'thumb'                => $thumb,
            'caption'              => $caption,
            'parse_mode'           => $parse_mode,
            'reply_to_message_id'  => $reply_to_message_id,
            'disable_notification' => $disable_notification,
        ];
        if ($reply_markup) {
            if ($button_type == 'inline') {
                $reply_markup = json_encode(['inline_keyboard' => $reply_markup]);
                $args['reply_markup'] = $reply_markup;
            } elseif ($button_type == 'button') {
                $reply_markup = json_encode(['keyboard' => $reply_markup, 'resize_keyboard' => true]);
                $args['reply_markup'] = $reply_markup;
            }
        }

        return self::Request('/sendDocument', $args);
    }

    public function sendVideo($chat_id, $video, $caption = false, $reply_markup = false, $reply_to_message_id = false, $parse_mode = false, $support_streaming = true, $thumb = false, $width = false, $height = false, $disable_notification = false)
    {
        if ($parse_mode == null) {
            $parse_mode = $this->settings['parse_mode'];
        }
        $args = [
            'chat_id'              => $chat_id,
            'video'                => $video,
            'caption'              => $caption,
            'parse_mode'           => $parse_mode,
            'reply_to_message_id'  => $reply_to_message_id,
            'support_streaming'    => $support_streaming,
            'thumb'                => $thumb,
            'width'                => $width,
            'height'               => $height,
            'disable_notification' => $disable_notification,
        ];
        if ($reply_markup) {
            if ($button_type == 'inline') {
                $reply_markup = json_encode(['inline_keyboard' => $reply_markup]);
                $args['reply_markup'] = $reply_markup;
            } elseif ($button_type == 'button') {
                $reply_markup = json_encode(['keyboard' => $reply_markup, 'resize_keyboard' => true]);
                $args['reply_markup'] = $reply_markup;
            }
        }

        return self::Request('/sendVideo', $args);
    }

    public function deleteMessage($chat_id, $message_id)
    {
        $args = [
            'chat_id'    => $chat_id,
            'message_id' => $message_id,
        ];

        return self::Request('/deleteMessage', $args);
    }

    public function sendChatAction($chat_id, $action)
    {
        $args = [
            'chat_id' => $chat_id,
            'action'  => $action,
        ];

        return self::Request('/sendChatAction', $args);
    }
}
