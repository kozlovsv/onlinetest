<?php
namespace app\widgets;

use yii\bootstrap\Modal;

/**
 * Class Dialog
 * @package app\widgets
 */
class LetterPopup extends Modal
{
    /**
     * @var string
     */
    public $size = self::SIZE_SMALL;

    /**
     * @var string
     */
    public $attribute = 'data-popup';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerJs();
        parent::run();
    }

    /**
     * Клиентские скрипты
     */
    public function registerJs()
    {
        $view = $this->view;
        $selector = "#{$this->getId()}";
        $js = '
        $("document").ready(function() {
            $(document).on("click", "[' . $this->attribute . ']", function() {
                $.ajaxSetup({cache: true});
                $.ajax({
                    method: "get",
                    url: $(this).attr("data-source"),
                    dataType: "html"
                }).done(function(html) {
                    if (html) {
                        $("' . $selector . ' .modal-body").html(html);
                        $("' . $selector . '").modal();
                    }
                });
                return false;
            });
        });
        ';

        $view->registerJs($js, $view::POS_END);
    }
}