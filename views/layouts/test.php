<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="jumbotron">
                <?= $content ?>
            </div>
        </div>
    </div>
<?php
$this->endContent();