<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property string $author
 * @property string $visible
 * @property string $pathway
 * @property string|null $description
 * @property int $user_id
 *
 * @property Bookuser[] $bookusers
 * @property UploadedFile $uploadedFile
 */
class Book extends \yii\db\ActiveRecord
{
    public $uploadedFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'author', 'visible', 'user_id'], 'required'],
            [['title', 'author', 'visible', 'pathway', 'description'], 'string', 'max' => 255],
            [['user_id'], 'integer'],
            [['uploadedFile'], 'file', 
                'skipOnEmpty' => true, 
                'extensions' => ['txt', 'fb2'],
                'maxSize' => 10 * 1024 * 1024, // 10MB
                'checkExtensionByMimeType' => false // Отключаем строгую проверку MIME-типов
            ],
            ['visible', 'validateVisibility'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'author' => 'Автор',
            'visible' => 'Видимость',
            'pathway' => 'Путь',
            'description' => 'Описание',
            'user_id' => 'ID пользователя',
        ];
    }

    /**
     * Gets query for [[Bookusers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookusers()
    {
        return $this->hasMany(Bookuser::class, ['id_book' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'id_user'])
            ->viaTable('bookuser', ['id_book' => 'id']);
    }

    /**
     * Проверяет, принадлежит ли книга пользователю
     * @param int $userId ID пользователя
     * @return bool
     */
    public function isOwnedByUser($userId)
    {
        return $this->getBookusers()
            ->where(['id_user' => $userId])
            ->exists();
    }

    /**
     * Сохраняет файл книги
     * @param string $filePath Полный путь к файлу
     * @return bool
     */
    public function saveFile($filePath)
    {
        if ($this->uploadedFile === null) {
            Yii::error('uploadedFile is null');
            return false;
        }

        try {
            // Создаем директорию, если она не существует
            $dir = dirname($filePath);
            if (!file_exists($dir)) {
                FileHelper::createDirectory($dir, 0777, true);
            }

            $extension = strtolower($this->uploadedFile->extension);
            
            if ($extension === 'fb2') {
                // Читаем содержимое FB2 файла
                $fb2Content = file_get_contents($this->uploadedFile->tempName);
                
                // Конвертируем FB2 в TXT
                $txtContent = Fb2Converter::convertToTxt($fb2Content);
                
                // Сохраняем TXT файл
                return file_put_contents($filePath, $txtContent) !== false;
            } else {
                // Для TXT файлов просто сохраняем как есть
                return $this->uploadedFile->saveAs($filePath, ['overwrite' => true]);
            }
        } catch (\Exception $e) {
            Yii::error('Ошибка при сохранении файла: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Получает список книг пользователя
     * @param int $userId ID пользователя
     * @return Book[]
     */
    public static function getUserBooks($userId)
    {
        return self::find()
            ->joinWith('bookusers')
            ->where(['bookuser.id_user' => $userId])
            ->all();
    }

    /**
     * Валидация параметра видимости
     * @param string $attribute
     * @param array $params
     */
    public function validateVisibility($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;
            if (!$user->isAdmin() && $this->visible == '1') {
                $this->addError($attribute, 'Только администраторы могут делать книги публичными');
            }
        }
    }
}
