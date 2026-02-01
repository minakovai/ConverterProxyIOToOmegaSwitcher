# ConverterProxyIOToOmegaSwitcher

Небольшой PHP-скрипт с веб-интерфейсом, который принимает строку в формате
`username:password@host:port`, парсит её и генерирует JSON строго заданной
структуры. Результат можно скачать как файл `ProxyOmegaSwitcher.bak` для
Omega Switcher.

## Требования

- PHP 8.0+

## Запуск

```bash
php -S 0.0.0.0:8000 -t /workspace/ConverterProxyIOToOmegaSwitcher
```

Откройте в браузере: `http://127.0.0.1:8000/index.php`.

## Использование

1. Введите строку в формате `username:password@host:port`.
2. Нажмите **Сгенерировать JSON**.
3. Нажмите **Скачать ProxyOmegaSwitcher.bak**.

### Пример

Ввод:

```
user44115:oh054l@181.215.61.251:6378
```

## Примечания

- Структура JSON и порядок ключей фиксированы и соответствуют требованию.
- Значения `username`, `password`, `host` и `port` берутся только из введённой строки.
