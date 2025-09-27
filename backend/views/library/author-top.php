<?php

namespace app\views\library;

use app\models\library\AuthorTopSearch;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Url;
use yii\web\View;


/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var AuthorTopSearch $model
 */

$this->title = Yii::t('yii', 'Топ 10 авторов');
?>

<?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['/library/author-top'])]) ?>

<?= $form->field($model, 'publication_year')->dropDownList(['' => '-'] + $model->getPublicationYearOptions()) ?>

<div class="form-group">
    <div>
        <?= Html::submitButton(Yii::t('yii', 'Применить'), ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => SerialColumn::class,
        ],
        [
            'attribute' => 'author.full_name',
        ],
        [
            'attribute' => 'book_count',
        ],
    ],
]);
