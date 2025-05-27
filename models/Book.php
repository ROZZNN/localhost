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
            [['title', 'author', 'visible'], 'required'],
            [['title', 'author', 'visible', 'pathway', 'description'], 'string', 'max' => 255],
            [['uploadedFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'txt, pdf'],
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
            'title' => 'Title',
            'author' => 'Author',
            'visible' => 'Visible',
            'pathway' => 'Pathway',
            'description' => 'Description',
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
            return $this->uploadedFile->saveAs($filePath);
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
