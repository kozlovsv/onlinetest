<?php
/* @var $this yii\web\View */
/* @var $generator app\generators\crud\Generator */
/* @var $migrationName string migration name */
/* @var $modelName string */
/* @var $modelLabel string */


$actions = [
        'Просмотр' => 'view',
        'Создание' => 'create',
        'Изменение' => 'update',
        'Удаление' => 'delete',
    ];

echo "<?php\n";
?>

use app\components\RbacManager;
use yii\db\Migration;

class <?= $migrationName ?> extends Migration
{
    /**
     * Новые разрешения для КРУД
     * @var array
     */
    private $permissions = [
<?php foreach ($actions as $key => $value): ?>
        [
            'name' => '<?= "{$modelName}.{$value}"?>',
            'description' => '<?= "{$modelLabel}.{$key}"?>',
        ],
<?php endforeach;?>
    ];

    /**
     * Разрешения для ролей
     * @var array
     */
    protected $child = [
        'administrator' => [
<?php foreach ($actions as $value): ?>
            '<?= "{$modelName}.{$value}"?>',
<?php endforeach;?>
        ],
    ];

    public function safeUp()
    {
        $manager = $this->getManager();
        $manager->up();
        Yii::$app->cache->flush();
    }

    protected function getManager()
    {
        return new RbacManager([
            'permissions' => $this->permissions,
            'child' => $this->child,
        ]);
    }

    public function safeDown()
    {
        $manager = $this->getManager();
        $manager->down();
        Yii::$app->cache->flush();
    }
}