<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $roles \yii\rbac\Role[] */

$this->title = 'Роли и права';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-default-index">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="pull-left">
                <?= Html::a("Создать", ['create'], ['class' => 'btn btn-success create-button', 'data-modal' => 1, 'data-pjax' => 0]); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row auth-editor">
                <div class="col-xs-3">
                    <?= Html::label('Роли') ?>
                    <?= Html::dropDownList('AuthRoles', null, $roles, ['id' => 'auth-roles', 'class' => 'auth-select', 'multiple' => true]) ?>
                </div>
                <div class="col-xs-4">
                    <?= Html::label('Назначенные права') ?>
                    <?= Html::textInput('auth-permissions-search', null, ['id' => 'auth-permissions-search', 'class' => 'pull-right', 'placeholder' => 'Найти']) ?>
                    <?= Html::dropDownList('AuthPermission', null, [], ['id' => 'auth-permissions', 'class' => 'auth-select', 'multiple' => true]) ?>
                </div>
                <div class="col-xs-1 text-center" style="margin-top: 30px;">
                    <p>
                        <?= Html::button('<span class="fa fa-chevron-left"></span>', ['class' => 'btn btn-default', 'id' => 'add-permissions']) ?>
                    </p>
                    <p>
                        <?= Html::button('<span class="fa fa-chevron-right"></span>', ['class' => 'btn btn-default', 'id' => 'delete-permissions']) ?>
                    </p>
                </div>
                <div class="col-xs-4">
                    <?= Html::label('Доступные права') ?>
                    <?= Html::textInput('auth-permission-list-search', null, ['id' => 'auth-permission-list-search', 'class' => 'pull-right', 'placeholder' => 'Найти']) ?>
                    <?= Html::dropDownList('Permissions', null, [], ['id' => 'auth-permission-list', 'class' => 'auth-select', 'multiple' => true]) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php
$js = <<<JS
$(function () {
    function getPermissions(role) {
        $.ajax({
            type: "get",
            dataType: 'json',
            url: '/auth/default/permissions',
            data: {'role': role},
            success: function (data) {
                $('#auth-permissions').empty();
                $.each(data.permissions, function (key, value) {
                    $('#auth-permissions').append($('<option></option>').val(value.name).html(value.description));
                });
                $('#auth-permission-list').empty();
                $.each(data.permissionList, function (key, value) {
                    $('#auth-permission-list').append($('<option></option>').val(value.name).html(value.description));
                });
            }
        });
    }
    $('#auth-roles').on('change', function () {
        var role = $(this).val()[0];
        getPermissions(role);
    });
    $('#add-permissions').on('click', function () {
        var role = $('#auth-roles').val()[0];
        var permissions = $('#auth-permission-list').val();
        $.ajax({
            type: "post",
            dataType: 'json',
            url: '/auth/default/add?role=' + role,
            data: {'permissions': permissions},
            success: function () {
                getPermissions(role);
            }
        });
        getPermissions(role);
    });
    $('#delete-permissions').on('click', function () {
        var role = $('#auth-roles').val()[0];
        var permissions = $('#auth-permissions').val();
        $.ajax({
            type: "post",
            dataType: 'json',
            url: '/auth/default/delete?role=' + role,
            data: {'permissions': permissions},
            success: function () {
                getPermissions(role);
            }
        });
    });
    $('#auth-permissions-search').on('keyup', function () {
        var search = $(this).val().toUpperCase();
        $('#auth-permissions').find('option').each(function() {
          var item = $(this).text().toUpperCase();
          if (search) {
              if (-1 < item.indexOf(search)) {
                  $(this).show();
              } else {
                  $(this).hide();
              }
          } else {
              $(this).show();
          }
        });
    });
    $('#auth-permission-list-search').on('keyup', function () {
        var search = $(this).val().toUpperCase();
        $('#auth-permission-list').find('option').each(function() {
          var item = $(this).text().toUpperCase();
          if (search) {
              if (-1 < item.indexOf(search)) {
                  $(this).show();
              } else {
                  $(this).hide();
              }
          } else {
              $(this).show();
          }
        });
    });
    $('.auth-editor select').height($('body').height() - 220);
});
JS;
$this->registerJs($js, $this::POS_END);
?>