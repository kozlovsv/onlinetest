<?php
namespace app\components;

use yii\db\Query;
use yii\rbac\Assignment;

class DbManager extends \yii\rbac\DbManager
{
    /**
     * @inheritdoc
     */
    public function getAssignments($userId)
    {
        if (empty($userId)) {
            return [];
        }

        $query = (new Query())
            ->from($this->assignmentTable)
            ->where(['user_id' => (string)$userId]);

        $rows = $this->db->cache(function () use ($query) {
            return $query->all($this->db);
        });

        $assignments = [];
        foreach ($rows as $row) {
            $assignments[$row['item_name']] = new Assignment([
                'userId' => $row['user_id'],
                'roleName' => $row['item_name'],
                'createdAt' => $row['created_at'],
            ]);
        }

        return $assignments;
    }
}
