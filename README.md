# ConverterProxyIOToOmegaSwitcher

Конвертер с веб-интерфейсом, который принимает строку в формате
`username:password@host:port` и сразу скачивает файл `ProxyOmegaSwitcher.bak`
с JSON нужной структуры для Omega Switcher.

## Инструкция

1. Приобрести прокси (например [тут](https://proxys.io/?refid=44115)).
2. Скопировать строку в формате `username:password@host:port`.
3. Вставить в строку для парсинга.
4. Скачать файл бэкапа.

## GitHub Pages

- https://minakovai.github.io/ConverterProxyIOToOmegaSwitcher/

На странице только одна кнопка: **Скачать ProxyOmegaSwitcher.bak**.

На странице и в PHP-версии (`script.php`) показана одинаковая инструкция из 4 шагов.

## Локальный запуск (статическая версия)

```bash
python3 -m http.server --directory /workspace/ConverterProxyIOToOmegaSwitcher 8000
```

Откройте `http://127.0.0.1:8000/index.html`.

## Локальный запуск (PHP-версия)

```bash
php -S 0.0.0.0:8000 -t /workspace/ConverterProxyIOToOmegaSwitcher
```

Откройте `http://127.0.0.1:8000/script.php`.
