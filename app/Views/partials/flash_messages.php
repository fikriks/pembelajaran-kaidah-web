<!-- Flash Messages with Notyf Toast Integration -->

<?php if (session()->has('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toast.success('<?= esc(str_replace("'", "\'", session('success'))) ?>');
        });
    </script>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toast.error('<?= esc(str_replace("'", "\'", session('error'))) ?>');
        });
    </script>
<?php endif; ?>

<?php if (session()->has('errors')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            $errors = session('errors');
            if (is_array($errors)) {
                foreach ($errors as $error): ?>
                    toast.error('<?= esc(str_replace("'", "\'", $error)) ?>');
                <?php endforeach;
            } else { ?>
                toast.error('<?= esc(str_replace("'", "\'", $errors)) ?>');
            <?php }
            ?>
        });
    </script>
<?php endif; ?>

<?php if (session()->has('info')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toast.info('<?= esc(str_replace("'", "\'", session('info'))) ?>');
        });
    </script>
<?php endif; ?>

<?php if (session()->has('warning')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toast.warning('<?= esc(str_replace("'", "\'", session('warning'))) ?>');
        });
    </script>
<?php endif; ?>

<!-- Alternative: Data attributes for manual trigger (if needed) -->
<?php if (session()->has('success')): ?>
    <div class="d-none" data-flash-success="<?= esc(session('success')) ?>"></div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div class="d-none" data-flash-error="<?= esc(session('error')) ?>"></div>
<?php endif; ?>

<?php if (session()->has('info')): ?>
    <div class="d-none" data-flash-info="<?= esc(session('info')) ?>"></div>
<?php endif; ?>

<?php if (session()->has('warning')): ?>
    <div class="d-none" data-flash-warning="<?= esc(session('warning')) ?>"></div>
<?php endif; ?>