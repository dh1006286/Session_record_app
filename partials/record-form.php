<?php
$is_edit   = isset($record) && isset($record['id']);
$action    = $is_edit ? 'update' : 'create';

$title     = $is_edit ? htmlspecialchars($record['title'])  : '';
$artist    = $is_edit ? htmlspecialchars($record['artist']) : '';
$price     = $is_edit ? htmlspecialchars($record['price'])  : '';
$format_id = $is_edit ? (int)$record['format_id']            : 0;


$formats = formats_all_query();

?>

<h2 class="h4 mb-3"><?= $is_edit ? 'Edit Record' : 'Add Record' ?></h2>

<form method="post" class="row g-2">
    <div class="col-12">
        <label class="form-label">Title</label>
        <input name="title" class="form-control " type="text" value="<?= $title ?>" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Artist</label>
        <input name="artist" class="form-control " type="text" value="<?= $artist ?>" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Price</label>
        <input name="price" class="form-control" type="number" step="0.01" min="0" value="<?= $price ?>" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Format</label>
        <select name="format_id" class="form-select" required>
            <option value="">Select...</option>
            <?php foreach ($formats as $f): ?>
                <?php $fid = (int)$f['id']; ?>
                <option value="<?= $fid ?>" <?= $fid === $format_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($f['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>


    <input type="hidden" name="action" value="<?= $action ?>">
    <?php if ($is_edit): ?>
        <input type="hidden" name="id" value="<?= (int)$record['id'] ?>">
    <?php endif; ?>

    <div class="col-12">
        <button class="btn btn-success"><?= $is_edit ? 'Update' : 'Create' ?></button>
        <a href="?view=list" class="btn btn-outline-secondary">Cancel</a>
    </div>

</form>