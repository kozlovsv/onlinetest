<?php

/**
 * @var $this yii\web\View
 * @var array $chunckLetters
 * @var array $cntLevels
 * @var array $lettersLevel
 * @var array $pandaLevel
 *
 */

use app\models\TestTask;
use app\widgets\LetterPopup;
use yii\helpers\Url;

$this->title = Yii::$app->params['brand'];
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => 'https://fonts.googleapis.com/css?family=Alice&display=swap']);

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
            <?php foreach($chunckLetters as $cn => $chunck) : ?>
            <div class="tree-box">
                <div class="tree-section">
                    <?php foreach($chunck as $letters) : ?>
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
            <div class="tree-box">
                <div class="level-section">
                    <div class="level-line-section">
                        <hr class="level-line"/>
                    </div>
                    <div>
                        <div class="level-img-section">
                            <div class="level-img-section-inner">
                                <div class="level-img-section-inner-castle-box">
                                    <img alt="castle" class="level-img-section-inner-castle" src="/images/castle-complete.svg">
                                </div>
                                <div class="level-img-text">
                                    <div class="level-img-text-inner">
                                        <svg height="100%" viewBox="0 0 12 24" width="100%" xmlns="http://www.w3.org/2000/svg"><text dy="25%" fill="currentColor" text-anchor="middle" x="50%" y="50%"><?=$cn + 1;?></text></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
        </div>
        <div class="panda-bubble-box">
            <div class="panda-bubble-box-inner" style="z-index: 500">
                <div class="panda-bubble-box-panda">
                    <a title="Накорми панду!" href="/test-task/create" class="panda-bubble">
                        <img alt="Голодная панда" src="/images/panda2.svg" style="height: 60px; width: 60px">
                    </a>
                </div>
            </div>
            <div class="panda-bubble-box-inner" style="transform: rotate(135deg);">
                <img alt="Круг" src="/images/<?=getLevelRingSvg($pandaLevel, TestTask::CNT_PANDA_IS_FULL)?>">
            </div>
            <div class="panda-bubble-box-inner">
                <div class="tree-node-corona">
                    <div style="height: 100%; width: 100%; font-size: 13px;">
                        <img alt="Достижения" class="tree-node-corona-img" src="/images/<?= $pandaLevel ? 'corona.svg' : 'corona-disabled.svg'?>">
                        <div class="tree-node-corona-level-text"><?= $pandaLevel ? $pandaLevel : ''?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>