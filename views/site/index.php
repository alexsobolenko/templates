<h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>

<p>This page is rendered by a PHP view. Form submissions go to the internal API.</p>

<form method="post" action="/api/tasks">
    <label for="title">Title</label>
    <input id="title" name="title" required>

    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4"></textarea>

    <button type="submit">Create task</button>
</form>
