<?php

/**
 * @var $this yii\web\View
 * @var array $chunckLetters
 * @var array $cntLevels
 * @var array $lettersLevel
 *
 */

use app\widgets\LetterPopup;
use yii\helpers\Url;

$this->title = Yii::$app->params['brand'];
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => 'https://fonts.googleapis.com/css?family=Alice&display=swap']);

$this->registerCss("
.modal {
  text-align: center;
}

@media screen and (min-width: 768px) { 
  .modal:before {
    display: inline-block;
    vertical-align: middle;
    content: \" \";
    height: 100%;
  }
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
}
");

function getLevelClass($level, $levelCount) {
    if (!$level) return '';
    if ($level >= $levelCount) return ' level-full';
    if ($level > 3) $level = 3;
    return ' level-'.$level;
}

function getLevelRingSvg($level, $levelCount) {
    if (!$level) return 'ring.svg';
    if ($level >= $levelCount) return 'ring-full.svg';
    if ($level > 3) $level = 3;
    return "ring-{$level}.svg";
}

echo LetterPopup::widget();
?>
<div class="site-index">
    <div class="skill-main">
        <div class="skill-tree">
            <div class="tree-box">
                <div class="tree-section">
                    <?php foreach($chunckLetters as $letters) : ?>
                            <div class="tree-row">
                                <?php foreach ($letters as $letter) :
                                    $letterId = $letter['id'];
                                    $level = !empty($cntLevels[$letterId]) ? $cntLevels[$letterId] : '';
                                    $levelCount = !empty($lettersLevel[$letterId]) ? $lettersLevel[$letterId] : 0;
                                ?>
                                <div class="tree-node">
                                    <div class="tree-node-click" data-popup data-source="<?= Url::to([$level < $levelCount ? '/test-task/letter-popup' : '/test-task/letter-full-popup', 'id' => $letterId])?>">
                                        <div class="tree-node-inner">
                                            <div style="width: 100%;">
                                                <div class="tree-node-outer-ring">
                                                    <div class="tree-node-outer-ring-inner">
                                                        <div class="tree-node-outer-ring-svg">
                                                            <img alt="Круг" src="/images/<?=getLevelRingSvg($level, $levelCount)?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tree-node-letter-block">
                                                    <div class="tree-node-letter-ring<?=getLevelClass($level, $levelCount)?>">
                                                        <div class="tree-node-letter"><?=$letter['title'];?></div>
                                                    </div>
                                                </div>
                                                <div class="tree-node-corona">
                                                    <div style="height: 100%; width: 100%; font-size: 13px;">
                                                            <img alt="Достижения" class="tree-node-corona-img" src="/images/<?= $level ? 'corona.svg' : 'corona-disabled.svg'?>">
                                                        <div class="tree-node-corona-level-text"><?=$level?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach;?>
                            </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
