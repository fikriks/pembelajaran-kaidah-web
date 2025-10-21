<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <?php if (isset($page_title)): ?>
                        <?= esc($page_title) ?>
                    <?php else: ?>
                        Dashboard
                    <?php endif; ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('dashboard') ?>">Home</a>
                    </li>
                    <?php if (isset($breadcrumb)): ?>
                        <?php foreach ($breadcrumb as $key => $item): ?>
                            <?php if ($key === array_key_last($breadcrumb)): ?>
                                <li class="breadcrumb-item active"><?= esc($item) ?></li>
                            <?php else: ?>
                                <li class="breadcrumb-item">
                                    <a href="#"><?= esc($item) ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="breadcrumb-item active">Dashboard</li>
                    <?php endif; ?>
                </ol>
            </div>
        </div>
    </div>
</div>