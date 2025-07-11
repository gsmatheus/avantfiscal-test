<?php
$includeAuthScript = $includeAuthScript ?? false;
$includeRoomsScript = $includeRoomsScript ?? false;
$includeUtilsScript = $includeUtilsScript ?? true;
$includeAppScript = $includeAppScript ?? true;
?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

<?php 
$assetsPath = isset($isRootPage) && $isRootPage ? 'app/assets/js/' : 'assets/js/';
?>

<?php if ($includeAppScript): ?>
<script src="<?php echo $assetsPath; ?>app.js"></script>
<?php endif; ?>

<?php if ($includeUtilsScript): ?>
<script src="<?php echo $assetsPath; ?>utils.js"></script>
<?php endif; ?>

<?php if ($includeAuthScript): ?>
<script src="<?php echo $assetsPath; ?>auth.js?v=<?php echo time(); ?>"></script>
<?php endif; ?>

<?php if ($includeRoomsScript): ?>
<script src="<?php echo $assetsPath; ?>rooms.js?v=<?php echo time(); ?>"></script>
<?php endif; ?> 