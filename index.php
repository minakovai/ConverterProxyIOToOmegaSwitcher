<?php
$inputValue = '';
$error = '';
$jsonOutput = '';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['download']) && isset($_POST['payload'])) {
        $payload = base64_decode($_POST['payload'], true);
        if ($payload === false) {
            http_response_code(400);
            echo 'Invalid payload.';
            exit;
        }

        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="ProxyOmegaSwitcher.bak"');
        header('Content-Length: ' . strlen($payload));
        echo $payload;
        exit;
    }

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

            $jsonOutput = build_json($username, $password, $host, $port);
        } else {
            $error = 'Неверный формат. Пример: user44115:oh054l@181.215.61.251:6378';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProxyOmega Switcher</title>
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
        p {
            color: #52606d;
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
        button:disabled {
            background: #9fb7f5;
            cursor: not-allowed;
        }
        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .download-form {
            margin-top: 16px;
        }
        .json-preview {
            background: #0f172a;
            color: #e2e8f0;
            padding: 16px;
            border-radius: 8px;
            white-space: pre-wrap;
            font-size: 13px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Конвертер Proxy → Omega Switcher</h1>
        <p>Введите строку строго в формате <strong>username:password@host:port</strong>.</p>
        <?php if ($error !== ''): ?>
            <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if ($jsonOutput !== ''): ?>
            <div class="success">JSON сформирован. Скачайте файл ProxyOmegaSwitcher.bak.</div>
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
            <button type="submit">Сгенерировать JSON</button>
        </form>

        <?php if ($jsonOutput !== ''): ?>
            <form method="post" class="download-form">
                <input type="hidden" name="download" value="1" />
                <input type="hidden" name="payload" value="<?= htmlspecialchars(base64_encode($jsonOutput), ENT_QUOTES, 'UTF-8') ?>" />
                <button type="submit">Скачать ProxyOmegaSwitcher.bak</button>
            </form>
            <div class="json-preview"><?= htmlspecialchars($jsonOutput, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
