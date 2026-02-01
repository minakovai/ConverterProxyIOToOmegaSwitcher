# ConverterProxyIOToOmegaSwitcher

Небольшой конвертер с веб-интерфейсом, который принимает строку в формате
`username:password@host:port`, парсит её и генерирует JSON строго заданной
структуры. Результат можно скачать как файл `ProxyOmegaSwitcher.bak` для
Omega Switcher.

## Использование

Откройте опубликованную страницу GitHub Pages:

- https://minakovai.github.io/ConverterProxyIOToOmegaSwitcher/

Введите строку, нажмите **Сгенерировать JSON**, затем **Скачать ProxyOmegaSwitcher.bak**.

## Локальный запуск (статическая страница)

```bash
python3 -m http.server --directory /workspace/ConverterProxyIOToOmegaSwitcher 8000
```

Откройте в браузере: `http://127.0.0.1:8000/index.html`.

## Локальный запуск (PHP-версия)

```bash
php -S 0.0.0.0:8000 -t /workspace/ConverterProxyIOToOmegaSwitcher
```

Откройте в браузере: `http://127.0.0.1:8000/script.php`.

## Пример

Ввод:

```
user44115:oh054l@181.215.61.251:6378
```

## Примечания

- Структура JSON и порядок ключей фиксированы и соответствуют требованию.
- Значения `username`, `password`, `host` и `port` берутся только из введённой строки.
