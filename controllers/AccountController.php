<?php

namespace app\controllers;

use app\models\Book;
use app\models\Bookuser;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * AccountController implements the CRUD actions for Book model.
 */
class AccountController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = Book::find()
            ->select(['book.*'])
            ->joinWith('bookusers')
            ->where(['bookuser.id_user' => Yii::$app->user->id])
            ->orderBy(['book.id' => SORT_DESC]);

        // Отладочная информация
        Yii::info('SQL Query: ' . $query->createCommand()->rawSql);
        Yii::info('User ID: ' . Yii::$app->user->id);
        Yii::info('Books count: ' . $query->count());

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Book model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if (!$model->isOwnedByUser(Yii::$app->user->id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Book();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Загружаем файл
                $model->uploadedFile = UploadedFile::getInstance($model, 'uploadedFile');
                
                if ($model->uploadedFile) {
                    // Генерируем уникальный ID для файла
                    $uniqueId = uniqid();
                    $model->pathway = '/booksstorage/' . $uniqueId . '.txt';
                    
                    Yii::info('Начало загрузки файла. Путь: ' . $model->pathway);
                    Yii::info('Временный файл: ' . $model->uploadedFile->tempName);
                    
                    // Проверяем, существует ли временный файл
                    if (!file_exists($model->uploadedFile->tempName)) {
                        Yii::error('Временный файл не существует: ' . $model->uploadedFile->tempName);
                        Yii::$app->session->setFlash('error', 'Ошибка при загрузке файла');
                        return $this->render('create', ['model' => $model]);
                    }
                    
                    if ($model->validate()) {
                        Yii::info('Валидация прошла успешно');
                        
                        if ($model->save()) {
                            Yii::info('Модель сохранена в БД. ID: ' . $model->id);
                            
                            // Сохраняем файл
                            $filePath = 'C:/OSPanel/domains/localhost/web/booksstorage/' . $uniqueId . '.txt';
                            if ($model->saveFile($filePath)) {
                                Yii::info('Файл успешно сохранен: ' . $filePath);
                                
                                // Создаем связь между книгой и пользователем
                                $bookUser = new Bookuser();
                                $bookUser->id_book = $model->id;
                                $bookUser->id_user = Yii::$app->user->id;
                                $bookUser->id_stat = 1; // Статус "не прочитано"
                                
                                if ($bookUser->save()) {
                                    Yii::info('Связь с пользователем создана');
                                    Yii::$app->session->setFlash('success', 'Книга успешно загружена');
                                    return $this->redirect(['view', 'id' => $model->id]);
                                } else {
                                    Yii::error('Ошибка создания связи с пользователем: ' . print_r($bookUser->errors, true));
                                    $model->delete(); // Удаляем запись из БД, так как связь не удалось создать
                                    Yii::$app->session->setFlash('error', 'Не удалось добавить книгу в вашу библиотеку');
                                }
                            } else {
                                Yii::error('Ошибка сохранения файла');
                                $model->delete(); // Удаляем запись из БД, так как файл не удалось сохранить
                                Yii::$app->session->setFlash('error', 'Не удалось сохранить файл книги');
                            }
                        } else {
                            Yii::error('Ошибка сохранения модели: ' . print_r($model->errors, true));
                            Yii::$app->session->setFlash('error', 'Не удалось сохранить информацию о книге');
                        }
                    } else {
                        Yii::error('Ошибка валидации: ' . print_r($model->errors, true));
                        Yii::$app->session->setFlash('error', 'Пожалуйста, проверьте введенные данные');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Пожалуйста, выберите файл для загрузки');
                }
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if (!$model->isOwnedByUser(Yii::$app->user->id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            // Загружаем файл
            $model->uploadedFile = UploadedFile::getInstance($model, 'uploadedFile');
            
            if ($model->save()) {
                // Если был загружен новый файл, сохраняем его
                if ($model->uploadedFile) {
                    if ($model->saveBookFile()) {
                        Yii::$app->session->setFlash('success', 'Книга успешно обновлена');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    Yii::$app->session->setFlash('success', 'Книга успешно обновлена');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if (!$model->isOwnedByUser(Yii::$app->user->id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        
        // Удаляем файл книги
        $filePath = Yii::getAlias('@webroot') . $model->pathway;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $model->delete();
        Yii::$app->session->setFlash('success', 'Книга успешно удалена');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
