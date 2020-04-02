<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<?= $content ?>
<footer class="footer">
    <div class="container-fluid">
        &copy; 2019—<?= date("Y") ?> Козлов Сергей Владимирович<br>
        <a href="mailto:kozlovsv78@gmail.com">kozlovsv78@gmail.com</a><br>
        <a href="tel:+79273162830">+7 (927) 316-28-30</a>
    </div>
</footer>
<?php
$this->endContent();