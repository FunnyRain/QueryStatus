<?php
/**
 * @author FunnyRain
 * @link https://github.com/FunnyRain
 * @link https://vk.com/vyxel
 */

/** $cfg - не трогать */
$cfg = [
    "token" => "", // токен страницы
    "group" => "", // айди группы (оставьте пусто если хотите на своей странице)
    "text" => "%nick% играет %game%. Мой steam профиль - %steam%", // текст
    "type" => "steam", // тип steam / mcpe
    "profile" => "https://steamcommunity.com/id/senddude", // ссылка на профиль steam
    "mcpe" => "mypex.ru:19132", // айпи и порт mcpe
    "sleep" => 300 // задержка 300сек (5 минут)
];
if (!file_exists(__DIR__ . '/config.json'))
    @file_put_contents(__DIR__ . '/config.json', json_encode($cfg, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING));

$getConfig = json_decode(file_get_contents(__DIR__ . '/config.json'), 1);

if (empty($getConfig['token']))
    exit("Укажи токен своей страницы в конфиге!");

if (!in_array($getConfig['type'], ['steam', 'mcpe']))
    exit("Укажи тип статуса: steam или mcpe");

while (true) {
    if ($getConfig['type'] == 'steam') {
        $steam = file_get_contents($getConfig['profile']);

        /** Никнейм  */
        $getName = explode('<span class="actual_persona_name">', $steam)[1];
        $getName = explode('</span>', $getName)[0];
        $getName = !empty($getName) ? $getName : "";
        /** Запущенная игра */
        $getGame = explode('<div class="profile_in_game_name">', $steam)[1];
        $getGame = explode('</div>', $getGame)[0];
        $getGame = !empty($getGame) ? $getGame : "";
        /** Статус */
        $getStatus = explode('<div class="profile_in_game_header">', $steam)[1];
        $getStatus = explode('</div>', $getStatus)[0];

        if ($getStatus == "Currently In-Game") {
            $text = str_replace(
                ['%nick%', '%game%', '%steam%', '%online%', '%online-max%', '%server-name%', '%ip%'],
                [$getName, $getGame, $getConfig['profile'], "", "", "", ""],
                $getConfig['text']
            );
        } else {
            $text = str_replace(
                ['%nick%', '%game%', '%steam%', '%online%', '%online-max%', '%server-name%', '%ip%'],
                [$getName, "Оффлайн", $getConfig['profile'], "", "", "", ""],
                $getConfig['text']
            );
        }
    } else {
        $mcpe = json_decode(file_get_contents('https://api.mcsrvstat.us/2/' . $getConfig['mcpe']), 1);
        if ($mcpe['online']) {
            $online = $mcpe['players']['online'];
            $online_max = $mcpe['players']['max'];
            $server_name = $mcpe['motd']['clean'][0];
            $ip = $getConfig['mcpe'];
            $text = str_replace(
                ['%nick%', '%game%', '%steam%', '%online%', '%online-max%', '%server-name%', '%ip%'],
                ["", "", "", $online, $online_max, $server_name, $getConfig['mcpe']],
                $getConfig['text']
            );
        } else {
            $text = str_replace(
                ['%nick%', '%game%', '%steam%', '%online%', '%online-max%', '%server-name%', '%ip%'],
                ["", "", "", "", "", "Оффлайн", $getConfig['mcpe']],
                $getConfig['text']
            );
        }
    }

    @file_get_contents('https://api.vk.com/method/status.set?text=' . urlencode($text) . '&group_id=' . $getConfig['group'] . '&v=5.102&access_token=' . $getConfig['token']);
    sleep($getConfig['sleep']);
}