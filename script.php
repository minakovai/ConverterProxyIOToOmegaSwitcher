<?php
function build_json(string $username, string $password, string $host, int $port): string
{
    $payload = [
        "+auto switch" => [
            "color" => "#99dd99",
            "defaultProfileName" => "direct",
            "name" => "auto switch",
            "profileType" => "SwitchProfile",
            "rules" => [
                [
                    "condition" => [
                        "conditionType" => "HostWildcardCondition",
                        "pattern" => "internal.example.com",
                    ],
                    "profileName" => "direct",
                ],
                [
                    "condition" => [
                        "conditionType" => "HostWildcardCondition",
                        "pattern" => "*.example.com",
                    ],
                    "profileName" => "proxy",
                ],
                [
                    "condition" => [
                        "conditionType" => "HostWildcardCondition",
                        "pattern" => "*.chatgpt.com",
                    ],
                    "profileName" => "proxy",
                ],
                [
                    "condition" => [
                        "conditionType" => "HostWildcardCondition",
                        "pattern" => "*.gosuslugi.ru",
                    ],
                    "profileName" => "direct",
                ],
            ],
            "revision" => "19be1c9fd3c",
        ],
        "+proxy" => [
            "bypassList" => [
                ["conditionType" => "BypassCondition", "pattern" => "127.0.0.1"],
                ["conditionType" => "BypassCondition", "pattern" => "::1"],
                ["conditionType" => "BypassCondition", "pattern" => "localhost"],
            ],
            "color" => "#99ccee",
            "fallbackProxy" => [
                "host" => $host,
                "port" => $port,
                "scheme" => "http",
            ],
            "name" => "proxy",
            "profileType" => "FixedProfile",
            "revision" => "19be1c5c9b3",
            "auth" => [
                "fallbackProxy" => [
                    "username" => $username,
                    "password" => $password,
                ],
            ],
        ],
        "-addConditionsToBottom" => false,
        "-confirmDeletion" => true,
        "-downloadInterval" => 1440,
        "-enableQuickSwitch" => false,
        "-quickSwitchProfiles" => [],
        "-refreshOnProfileChange" => true,
        "-revertProxyChanges" => true,
        "-showExternalProfile" => true,
        "-showInspectMenu" => true,
        "-showResultProfileOnActionBadgeText" => false,
        "-startupProfileName" => "",
        "schemaVersion" => 2,
        "-monitorWebRequests" => true,
    ];

    return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

$inputValue = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputValue = trim($_POST['proxy_input'] ?? '');

    if ($inputValue === '') {
        $error = 'Введите строку в формате username:password@host:port.';
    } else {
        $pattern = '/^([^:@\s]+):([^@\s]+)@([^:\s]+):(\d+)$/';
        if (preg_match($pattern, $inputValue, $matches)) {
            $username = $matches[1];
            $password = $matches[2];
            $host = $matches[3];
            $port = (int) $matches[4];
            $payload = build_json($username, $password, $host, $port);

            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename="ProxyOmegaSwitcher.bak"');
            header('Content-Length: ' . strlen($payload));
            echo $payload;
            exit;
        }
        $error = 'Неверный формат. Пример: user44115:oh054l@181.215.61.251:6378';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProxyOmega Switcher (PHP)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f7fb;
            margin: 0;
            padding: 40px 16px;
            color: #1f2933;
        }
        .container {
            max-width: 720px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
        }
        h1 {
            margin-top: 0;
            font-size: 24px;
        }
        p, li {
            color: #52606d;
        }
        ol {
            margin-top: 0;
            margin-bottom: 20px;
            padding-left: 20px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px 14px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #cbd2d9;
            box-sizing: border-box;
            margin-bottom: 16px;
        }
        button {
            background: #2563eb;
            border: none;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Конвертер Proxy → Omega Switcher (PHP)</h1>
        <ol>
            <li>Приобрести прокси (например <a href="https://proxys.io/?refid=44115" target="_blank" rel="noopener noreferrer">тут</a>).</li>
            <li>Скопировать строку в формате username:password@host:port.</li>
            <li>Вставить в строку для парсинга.</li>
            <li>Скачать файл бэкапа.</li>
        </ol>

        <?php if ($error !== ''): ?>
            <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form method="post">
            <label for="proxy_input">Строка для парсинга</label>
            <input
                type="text"
                id="proxy_input"
                name="proxy_input"
                placeholder="user44115:oh054l@181.215.61.251:6378"
                value="<?= htmlspecialchars($inputValue, ENT_QUOTES, 'UTF-8') ?>"
                required
            />
            <button type="submit">Скачать ProxyOmegaSwitcher.bak</button>
        </form>
    </div>
</body>
</html>
