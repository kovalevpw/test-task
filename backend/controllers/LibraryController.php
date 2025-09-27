<?php

namespace app\controllers;

use app\models\library\Author;
use app\models\library\AuthorForm;
use app\models\library\AuthorTopSearch;
use app\models\library\Book;
use app\models\library\BookForm;
use app\models\library\SubscriptionForm;
use app\models\ModelException;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

class LibraryController extends Controller
{
    use ModelHelperTrait;

    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'book-save',
                            'author-save',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [
                            'author-subscribe',
                            'author-top',
                        ],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'book-save' => ['get', 'post'],
                    'author-save' => ['get', 'post'],
                    'author-subscribe' => ['get', 'post'],
                    'author-top' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @return Response
     * @throws Throwable
     */
    public function actionAuthorTop(): Response
    {
        try {
            /** @var AuthorTopSearch $model */
            $model = Yii::createObject(AuthorTopSearch::class);
            $model->load($this->request->get(), '');
            $dataProvider = $model->search();
        } catch (ModelException $exception) {
            throw new BadRequestHttpException(implode(' ', $exception->getModel()->getFirstErrors()));
        }

        $this->response->content = $this->render('author-top', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);

        return $this->response;
    }

    /**
     * @return Response
     * @throws Throwable
     */
    public function actionBookSave(): Response
    {
        $book = $this->getOrCreate($this->request->get('id'), Book::class);
        /** @var BookForm $model */
        $model = Yii::createObject(BookForm::class, [$book]);

        try {
            $model->load($this->request->post(), '');
            $model->save();

            return $this->goHome();
        } catch (ModelException $exception) {
            $this->view->params['exception'] = $exception;
        }

        $this->response->content = $this->render('author-save', [
            'model' => $model,
        ]);

        return $this->response;
    }

    /**
     * @return Response
     * @throws Throwable
     */
    public function actionAuthorSave(): Response
    {
        $author = $this->getOrCreate($this->request->get('id'), Author::class);
        /** @var BookForm $model */
        $model = Yii::createObject(AuthorForm::class, [$author]);

        if ($this->request->isPost) {
            try {
                $model->load($this->request->post(), '');
                $model->save();

                return $this->goHome();
            } catch (ModelException $exception) {
                $this->view->params['exception'] = $exception;
            }
        }

        $this->response->content = $this->render('author-save', [
            'model' => $model,
        ]);

        return $this->response;
    }

    /**
     * @return Response
     * @throws Throwable
     */
    public function actionAuthorSubscribe(): Response
    {
        $author = $this->getOrError($this->request->get('id'), Author::class);
        /** @var SubscriptionForm $model */
        $model = Yii::createObject(SubscriptionForm::class, [$author]);

        if ($this->request->isPost) {
            try {
                $model->load($this->request->post(), '');
                $model->save();

                return $this->goHome();
            } catch (ModelException $exception) {
                $this->view->params['exception'] = $exception;
            }
        }

        $this->response->content = $this->render('author-subscribe', [
            'model' => $model,
        ]);

        return $this->response;
    }
}
