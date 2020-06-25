# QueryStatus - авто статус Steam / Mcpe
### Как установить?
```bash
git clone https://github.com/FunnyRain/querystatus
cd querystatus
```
запустить скрипт:
```bash
php index.php
```
### Настройка для Steam профиля
```json
{
  "token": "токен страницы",
  "group": "циферный айди группы (пример: -129321). Если пусто - статус будет на вашей странице!",
  "text": "%nick% играет %game%. Мой steam профиль - %steam%",
  "type": "steam",
  "profile": "https://steamcommunity.com/id/direned45",
  "mcpe": "",
  "sleep": 300
}
```
### Настройка для Mcpe сервера
```json
{
  "token": "токен страницы",
  "group": "циферный айди группы (пример: -129321). Если пусто - статус будет на вашей странице!",
  "text": "Онлайн: %online% из %online-max%. Название: %server-name% Айпи: %ip%",
  "type": "mcpe",
  "profile": "",
  "mcpe": "soulcraft-pe.ru:19132",
  "sleep": 300
}
```
