<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="my-cart">
    <?= view("v2/partials/my_cart_content") ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
</script>
<?= $this->endSection() ?>