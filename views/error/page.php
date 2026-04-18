<h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>

<p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>

<?php if ($debugDetails !== []): ?>
    <section>
        <h2>Debug details</h2>
        <ul>
            <?php foreach ($debugDetails as $label => $value): ?>
                <li>
                    <strong><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>:</strong>
                    <?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endif; ?>
