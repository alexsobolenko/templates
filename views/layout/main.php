<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'TODO List', ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        body {
            max-width: 760px;
            margin: 40px auto;
            padding: 0 20px;
            font-family: system-ui, sans-serif;
            line-height: 1.5;
        }

        input,
        textarea,
        button {
            display: block;
            width: 100%;
            box-sizing: border-box;
            margin: 8px 0 16px;
            padding: 10px;
            font: inherit;
        }

        button {
            width: auto;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?= $content ?>
</body>
</html>
