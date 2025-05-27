<?php

namespace app\controllers;

use app\models\Avatar;
use app\models\Book;
use app\models\Bookuser;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Role;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout($id = null)
    {
        if ($id === null) {
            return $this->redirect(['/account']);
        }

        $book = \app\models\Book::findOne($id);
        if (!$book || !$book->isOwnedByUser(Yii::$app->user->id)) {
            throw new \yii\web\NotFoundHttpException('Книга не найдена');
        }

        return $this->redirect(['read', 'id' => $id]);
    }

    /**
     * Displays book reading page.
     *
     * @return string
     */
    public function actionRead($id = null, $page = 1)
    {
        if ($id === null) {
            return $this->redirect(['/account']);
        }

        $book = \app\models\Book::findOne($id);
        if (!$book || !$book->isOwnedByUser(Yii::$app->user->id)) {
            throw new \yii\web\NotFoundHttpException('Книга не найдена');
        }

        $filePath = Yii::getAlias('@webroot') . $book->pathway;
        if (!file_exists($filePath)) {
            throw new \yii\web\NotFoundHttpException('Файл книги не найден');
        }

        // Читаем содержимое файла
        $content = file_get_contents($filePath);
        
        // Разбиваем на строки и удаляем пустые строки
        $lines = array_filter(explode("\n", $content), function($line) {
            return trim($line) !== '';
        });
        
        // Получаем разрешение экрана из параметров запроса
        $screenWidth = Yii::$app->request->get('width', 1920);
        $screenHeight = Yii::$app->request->get('height', 1080);
        
        // Разбиваем на страницы по количеству символов
        $pages = $this->calculateLinesPerPage($screenWidth, $screenHeight, $lines);
        
        // Получаем текущую страницу
        $currentPage = (int)$page;
        $totalPages = is_array($pages) ? count($pages) : 0;
        $currentPage = max(1, min($currentPage, $totalPages));
        
        // Получаем строки для текущей страницы
        $currentPageLines = isset($pages[$currentPage - 1]) ? $pages[$currentPage - 1] : [];

        return $this->render('about', [
            'book' => $book,
            'lines' => $currentPageLines,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'screenWidth' => $screenWidth,
            'screenHeight' => $screenHeight,
        ]);
    }

    /**
     * Рассчитывает оптимальное количество строк на страницу
     * @param int $screenWidth Ширина экрана
     * @param int $screenHeight Высота экрана
     * @param array $lines Массив строк текста
     * @return int Количество строк на страницу
     */
    private function calculateLinesPerPage($screenWidth, $screenHeight, $lines)
    {
        // Базовые значения
        $minChars = 1000;   // Минимальное количество символов
        $maxChars = 2000;   // Максимальное количество символов
        
        // Рассчитываем общее количество символов
        $totalChars = 0;
        $maxLineLength = 0;
        foreach ($lines as $line) {
            $length = mb_strlen(trim($line));
            $totalChars += $length;
            $maxLineLength = max($maxLineLength, $length);
        }
        
        // Определяем базовое количество символов на страницу
        if ($screenWidth <= 768) {
            // Мобильные устройства
            $baseChars = 600;  // Уменьшено с 1500 до 800
            $factor = 0.7;     // Уменьшено с 0.8 до 0.7
        } elseif ($screenWidth <= 1366) {
            // Планшеты и маленькие ноутбуки
            $baseChars = 1800;
            $factor = 1;
        } else {
            // Десктопы
            $baseChars = 1600;
            $factor = 1.2;
        }
        
        // Корректируем количество символов
        $charsPerPage = $baseChars;
        
        // Корректировка по максимальной длине строки
        if ($maxLineLength > 150) {
            $charsPerPage *= 0.8;
        }
        
        // Корректировка по высоте экрана
        $heightFactor = ($screenHeight / 1080);
        if ($heightFactor > 1.2) {
            $charsPerPage += 200;
        } elseif ($heightFactor < 0.8) {
            $charsPerPage -= 200;
        }
        
        // Корректировка по ширине экрана
        $widthFactor = ($screenWidth / 1920);
        if ($widthFactor > 1.2) {
            $charsPerPage += 200;
        } elseif ($widthFactor < 0.8) {
            $charsPerPage -= 200;
        }
        
        // Применяем общий фактор устройства
        $charsPerPage *= $factor;
        
        // Округляем и ограничиваем значениями
        $charsPerPage = max($minChars, min($maxChars, round($charsPerPage)));
        
        // Разбиваем текст на страницы по количеству символов
        $currentPage = [];
        $currentChars = 0;
        $pages = [];
        
        foreach ($lines as $line) {
            $lineLength = mb_strlen(trim($line));
            
            if ($currentChars + $lineLength > $charsPerPage && !empty($currentPage)) {
                $pages[] = $currentPage;
                $currentPage = [];
                $currentChars = 0;
            }
            
            $currentPage[] = $line;
            $currentChars += $lineLength;
        }
        
        if (!empty($currentPage)) {
            $pages[] = $currentPage;
        }
        
        return $pages;
    }

    /**
     * Displays registration page.
     *
     * @return string
     */
    public function actionRegistration()
    {
        $user = new \app\models\User();
        if ($user->load(Yii::$app->request->post())) 
        {
            $user->id_role = Role::getRoleId('user');
            $user->id_avatar = rand(1, 9);
            $user->auth_key = Yii::$app->security->generateRandomString();
            if ($user->validate()) 
            {
                $user->password = Yii::$app->security->generatePasswordHash($user->password);
                if ($user->save()){
                    Yii::$app->session->setFlash('success', 'Пользователь зарегистрирован');
                    return $this->redirect('/');
                }
            }
        }
        return $this->render('registration', [
            'model' => $user,
        ]);
    }

    /**
     * Добавляет книгу в библиотеку пользователя
     * @param int $id ID книги
     * @return \yii\web\Response
     */
    public function actionAddToLibrary($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }

        $book = \app\models\Book::findOne($id);
        if (!$book) {
            throw new \yii\web\NotFoundHttpException('Книга не найдена');
        }

        // Проверяем, не добавлена ли уже книга в библиотеку
        if ($book->isOwnedByUser(Yii::$app->user->id)) {
            Yii::$app->session->setFlash('info', 'Эта книга уже есть в вашей библиотеке');
            return $this->redirect(['/site/search']);
        }

        // Создаем связь между книгой и пользователем
        $bookUser = new \app\models\Bookuser();
        $bookUser->id_book = $book->id;
        $bookUser->id_user = Yii::$app->user->id;
        $bookUser->id_stat = 1; // Статус "не прочитано"

        if ($bookUser->save()) {
            Yii::$app->session->setFlash('success', 'Книга успешно добавлена в вашу библиотеку');
        } else {
            Yii::$app->session->setFlash('error', 'Не удалось добавить книгу в библиотеку');
        }

        return $this->redirect(['/site/search']);
    }

    /**
     * Поиск книг по видимости
     * @return string
     */
    public function actionSearch()
    {
        // Получаем все книги с видимостью 1 (публичные)
        $query = \app\models\Book::find()
            ->where(['visible' => 1]);

        // Если пользователь авторизован, показываем также его приватные книги
        if (!Yii::$app->user->isGuest) {
            $query->orWhere(['in', 'id', (new \yii\db\Query())
                ->select('id_book')
                ->from('bookuser')
                ->where(['id_user' => Yii::$app->user->id])
            ]);
        }

        $query->orderBy(['id' => SORT_DESC]);

        // Создаем провайдер данных для отображения книг
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12, // Количество книг на странице
            ],
        ]);

        // Отладочная информация
        Yii::info('SQL Query: ' . $query->createCommand()->rawSql);
        Yii::info('Total books found: ' . $query->count());

        return $this->render('search', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Загрузка новой книги
     * @return Response|string
     */
    public function actionUpload()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        $model = new Book();
        
        if ($model->load(Yii::$app->request->post())) {
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
                    return $this->render('upload', ['model' => $model]);
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
                            
                            if ($bookUser->save()) {
                                Yii::info('Связь с пользователем создана');
                                Yii::$app->session->setFlash('success', 'Книга успешно загружена');
                                return $this->redirect(['read', 'id' => $model->id]);
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
        
        return $this->render('upload', [
            'model' => $model
        ]);
    }

    /**
     * Список книг пользователя
     * @return string
     */
    public function actionMyBooks()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        $books = Book::getUserBooks(Yii::$app->user->id);
        
        return $this->render('my-books', [
            'books' => $books
        ]);
    }

    /**
     * Удаление книги
     * @param integer $id ID книги
     * @return Response
     */
    public function actionDeleteBook($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        $book = Book::findOne($id);
        if (!$book || !$book->isOwnedByUser(Yii::$app->user->id)) {
            throw new \yii\web\NotFoundHttpException('Книга не найдена');
        }

        // Удаляем файл книги
        $filePath = Yii::getAlias('@webroot') . $book->pathway;
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Удаляем связь с пользователем
        Bookuser::deleteAll(['id_book' => $id, 'id_user' => Yii::$app->user->id]);
        
        // Удаляем запись о книге
        $book->delete();

        Yii::$app->session->setFlash('success', 'Книга успешно удалена');
        return $this->redirect(['my-books']);
    }
}

$dir = 'web/booksstorage';