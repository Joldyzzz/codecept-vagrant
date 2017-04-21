<?php

namespace app\models;

use app\models\redis\UsersOnline;
use app\models\traits\user\UsersIdentityTrait;
use app\modules\geo\models\GeoCity;
use app\modules\kn\models\KnCompanies;
use app\modules\new_profile\models\CompSectors;
use app\modules\new_profile\models\Usertypes;
use bosslib\yii\Apps;
use bosslib\yii\auth2\components\IdentityInterface;
use bosslib\yii\fileManager\models\Files;
use bosslib\yii\fileManager\models\FilesImage;
use bosslib\yii\fileManager\models\FilesImageScale;
use bosslib\yii\fileManager\models\FilesTypes;
use bosslib\yii\helpers\ArrayHelper;
use bosslib\yii\rbac\models\AuthAssignment;
use bosslib\yii\web\UploadedFile;
use bosslib\yii\web\User;
use CRM;
use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use DevGroup\TagDependencyHelper\TagDependencyTrait;
use Utils;
use Yii;
use yii\base\ErrorException;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\Url;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $fname
 * @property string $sname
 * @property string $pname
 * @property string $date_reg
 * @property integer $subscribe
 * @property integer $banned
 * @property integer $moderated
 * @property integer $status
 * @property double $money
 * @property string $date_moderated
 * @property string $adress
 * @property string $id_city
 * @property string $phone
 * @property string $clear_phone
 * @property integer $hide_phone
 * @property integer $id_usertype
 * @property string $date_birth
 * @property integer $sex
 * @property string $photo
 * @property integer $id_photo
 * @property integer $id_cover
 * @property string $self_descr
 * @property string $additional_info
 * @property string $additional_mail
 * @property string $company_name
 * @property string $company_adress
 * @property string $company_city
 * @property string $company_site
 * @property integer $company_sector
 * @property string $settings
 * @property integer $hide_anketa
 * @property string $valid_hash
 * @property integer $rating
 * @property integer $bal
 * @property string $last_action_date
 * @property string $last_action_ip
 * @property integer $show_form_fr_cat
 * @property string $big_foto
 * @property string $company_logo
 * @property integer $user_add_type
 * @property integer $user_is_pro
 * @property string $url_register
 * @property string $about_company
 * @property string $broker_regions
 * @property integer $ref_id
 * @property string $highrise_id
 * @property string $highrise_token
 * @property string $report_email
 * @property string $marketing_sended
 * @property string $is_region_partner
 * @property integer $id_manager
 * @property string $crm_add_info
 * @property string $crm_state
 * @property string $crm_company_name
 * @property string $identity
 * @property string $network
 * @property integer $referral_id
 * @property integer $objects_limit
 * @property integer $owner_id
 * @property string $position
 * @property string $last_contacts_status_update
 * @property string $contact_checked
 * @property integer $id_company
 * @property integer $kn_office_id
 * @property string $achieve
 * @property string $experience
 * @property integer $ordi
 * @property string $last_nc_close_time
 * @property string $nmls_id
 * @property string $import_from
 * @property integer $hidden
 * @property string $phone_notify_last
 * @property string $last_smart_mailer
 * @property string $xml_id
 * @property string $last_fr_smart_mailer
 * @property integer $crm_life_points
 * @property integer $actions_limit
 * @property string $actions_limit_popup_showed
 * @property string $social_profile
 * @property string $password_type
 * @property string $photo_old
 *
 *
 * @property FilesImage $avatar
 * @property FilesImage $avatar80x80
 * @property FilesImage $avatar380x380
 * @property FilesImage $cover
 *
 * @property string $tokenType
 * @property Bookmarks[] $bookmarks
 * @property FrItems[] $frItems
 * @property FrNotes[] $frNotes
 * @property FrSavedSearches[] $frSavedSearches
 * @property Payments[] $payments
 * @property UsertypeVals[] $usertypeVals
 * @property KnCompanies $myKnCompany Компания которой владеет пользователь
 * @property KnCompanies $knCompany Компания в которой состоит пользователь
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    use UsersIdentityTrait;
    use TagDependencyTrait;

    /**
     * string Индекс для хранения параметров, когда логинимся через попан.
     */
    const PARAM_POPUP_AUTH_PARAM = 'popupparams';
    /**
     * integer
     */
    const DEFAULT_PASSWORD_LENGTH = 6;
    /**
     * string Символы из которых будет генирироваться пароль
     */
    const DEFAULT_PASSWORD_PATTERN = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    /**
     * integer
     */
    const STATUS_ACTIVE = 1;
    /**
     * integer
     */
    const STATUS_NO_ACTIVE = 0;
    /**
     * integer
     */
    const STATUS_DELETED = 2;
    /**
     * string Роль которая присваивается юзеру после активации аккаунта
     */
    const USER_RBAC_ROLE = 'user';

    const SEX_MAN = 1;
    const SEX_WOMAN = 2;

    const PASSWORD_TYPE_MD5 = 'md5';
    const PASSWORD_TYPE_SECURE = 'secure_hash';

    const LINK_VIEW = 'link_view';

    const REDIRECT_GET_PARAM = 'site';

    const TOKEN_SALT = '345324ftedgsdfk4lre8f9u23q42qr349r45t09w5u09sdrg54wt45r302323423nmflekwfko4k3m4f';

    /**
     * @var string
     */
    public $_tokenType;

    /**
     * @var bool $is_online
     */
    public $is_online;

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'CacheableActiveRecord' => [
                    'class' => CacheableActiveRecord::className(),
                ]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {

        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hide_phone', 'id_usertype', 'hide_anketa', 'referral_id', 'money'], 'default', 'value' => 0],
            [['about_company', 'broker_regions', 'crm_add_info', 'crm_company_name'], 'default', 'value' => ''],
            [
                [
                    'login',
                    'fname',
                    'date_reg',
                    'money',
                    'hide_phone',
                    'id_usertype',
                    'hide_anketa',
                    'url_register',
                    'referral_id'
                ],
                'required'
            ],
            [
                [
                    'login',
                    'adress',
                    'self_descr',
                    'additional_info',
                    'additional_mail',
                    'company_name',
                    'company_adress',
                    'company_site',
                    'settings',
                    'big_foto',
                    'company_logo',
                    'url_register',
                    'about_company',
                    'broker_regions',
                    'marketing_sended',
                    'is_region_partner',
                    'crm_add_info',
                    'crm_state',
                    'position',
                    'contact_checked',
                    'achieve',
                    'import_from',
                    'actions_limit_popup_showed',
                    'social_profile',
                    'password_type'
                ],
                'string'
            ],
            [
                [
                    'date_reg',
                    'date_moderated',
                    'date_birth',
                    'last_action_date',
                    'last_contacts_status_update',
                    'last_nc_close_time',
                    'phone_notify_last',
                    'last_smart_mailer',
                    'last_fr_smart_mailer',
                    'photo_old',
                    'sname'
                ],
                'safe'
            ],
            [
                [
                    'subscribe',
                    'banned',
                    'moderated',
                    'status',
                    'id_city',
                    'id_photo',
                    'id_cover',
                    'hide_phone',
                    'id_usertype',
                    'sex',
                    'company_city',
                    'company_sector',
                    'hide_anketa',
                    'rating',
                    'bal',
                    'show_form_fr_cat',
                    'user_add_type',
                    'user_is_pro',
                    'ref_id',
                    'id_manager',
                    'referral_id',
                    'objects_limit',
                    'owner_id',
                    'id_company',
                    'kn_office_id',
                    'ordi',
                    'hidden',
                    'crm_life_points',
                    'actions_limit'
                ],
                'integer'
            ],
            [['money'], 'number'],
            [['password', 'valid_hash'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 64],
            [['fname', 'sname', 'pname', 'network', 'nmls_id'], 'string', 'max' => 50],
            [
                ['phone', 'highrise_id', 'highrise_token', 'report_email', 'crm_company_name', 'identity'],
                'string',
                'max' => 250
            ],
            [['clear_phone'], 'string', 'max' => 255],
            [['photo'], 'string', 'max' => 100],
            [['last_action_ip'], 'string', 'max' => 15],
            [['experience', 'xml_id'], 'string', 'max' => 200],
            [
                ['self_descr', 'about_company'],
                'filter',
                'filter' => function ($value) {
                    return \yii\helpers\HtmlPurifier::process($value);
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'email' => 'Email',
            'fname' => 'Fname',
            'sname' => 'Sname',
            'pname' => 'Pname',
            'date_reg' => 'Date Reg',
            'subscribe' => 'Subscribe',
            'banned' => 'Banned',
            'moderated' => 'Moderated',
            'status' => '0-не подтвержденный 1-активный 2-удаленный',
            'money' => 'Money',
            'date_moderated' => 'Date Moderated',
            'adress' => 'Adress',
            'id_city' => 'Id City',
            'phone' => 'Phone',
            'clear_phone' => 'Clear Phone',
            'hide_phone' => 'Hide Phone',
            'id_usertype' => 'Id Usertype',
            'date_birth' => 'Date Birth',
            'sex' => '1-мужчина 2-женщина',
            'photo' => 'Photo',
            'id_photo' => 'Id Photo',
            'self_descr' => 'Коротко о пользователе',
            'additional_info' => 'Additional Info',
            'additional_mail' => 'Additional Mail',
            'company_name' => 'Краткое профессиональное описание',
            'company_adress' => 'Company Adress',
            'company_city' => 'Company City',
            'company_site' => 'Company Site',
            'company_sector' => 'Company Sector',
            'settings' => 'Settings',
            'hide_anketa' => 'Hide Anketa',
            'valid_hash' => 'Valid Hash',
            'rating' => 'Rating',
            'bal' => 'Bal',
            'last_action_date' => 'Last Action Date',
            'last_action_ip' => 'Last Action Ip',
            'show_form_fr_cat' => 'Show Form Fr Cat',
            'big_foto' => 'Big Foto',
            'company_logo' => 'Company Logo',
            'user_add_type' => '0-просто пользователь, 1- брокер, 2-риэлтор',
            'user_is_pro' => 'User Is Pro',
            'url_register' => 'Url Register',
            'about_company' => 'About Company',
            'broker_regions' => 'Broker Regions',
            'ref_id' => 'Ref ID',
            'highrise_id' => 'Highrise ID',
            'highrise_token' => 'Highrise Token',
            'report_email' => 'Report Email',
            'marketing_sended' => 'Marketing Sended',
            'is_region_partner' => 'Is Region Partner',
            'id_manager' => 'Id Manager',
            'crm_add_info' => 'Crm Add Info',
            'crm_state' => 'Crm State',
            'crm_company_name' => 'Crm Company Name',
            'identity' => 'Identity',
            'network' => 'Network',
            'referral_id' => 'Referral ID',
            'objects_limit' => 'Objects Limit',
            'owner_id' => 'Owner ID',
            'position' => 'Position',
            'last_contacts_status_update' => 'последняя дата изменения статуса контактов',
            'contact_checked' => 'контакты проверены?',
            'id_company' => 'Id Company',
            'kn_office_id' => 'Kn Office ID',
            'achieve' => 'Achieve',
            'experience' => 'Experience',
            'ordi' => 'Ordi',
            'last_nc_close_time' => 'Last Nc Close Time',
            'nmls_id' => 'Nmls ID',
            'import_from' => 'Import From',
            'hidden' => 'Hidden',
            'phone_notify_last' => 'Phone Notify Last',
            'last_smart_mailer' => 'Last Smart Mailer',
            'xml_id' => 'Xml ID',
            'last_fr_smart_mailer' => 'Last Fr Smart Mailer',
            'crm_life_points' => 'Crm Life Points',
            'actions_limit' => 'Actions Limit',
            'actions_limit_popup_showed' => 'Actions Limit Popup Showed',
            'social_profile' => 'Social Profile',
            'password_type' => 'password_type',
            'photo_old' => 'photo_old',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarks()
    {
        return $this->hasMany(Bookmarks::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrItems()
    {
        return $this->hasMany(FrItems::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrNotes()
    {
        return $this->hasMany(FrNotes::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrSavedSearches()
    {
        return $this->hasMany(FrSavedSearches::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payments::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMyKnCompany()
    {
        return $this->hasOne(KnCompanies::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKnCompany()
    {
        return $this->hasOne(KnCompanies::className(), ['id' => 'id_company']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTypes()
    {
        return $this->hasMany(Usertypes::className(), ['id' => 'id_usertype'])->viaTable('usertype_vals',
            ['id_user' => 'id']);
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        if (null == $this->_tokenType) {
            $this->_tokenType = AuthTokens::getTokenType();
        }

        return $this->_tokenType;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthTokens()
    {
        return $this->hasMany(AuthTokens::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogPosts()
    {
        return $this->hasMany(Posts::className(), ['id_author' => 'id'])->where([
            'id_status' => ObjectStatus::PUBLISHED,
            'date_deleted' => null
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSocialLogin()
    {
        return $this->hasOne(UsersSocialLogin::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOnline()
    {
        return $this->hasOne(UsersOnline::className(), ['id_user' => 'id']);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return User|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Validates password
     *
     * @param  string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if ($this->getAttribute('password_type') == 'md5') {
            return md5($password) === $this->getAttribute('password');
        } else {
            return Yii::$app->security->validatePassword($password, $this->getAttribute('password'));
        }
    }
    
    public function addBizideaAuthToken() {
        $sql = "INSERT INTO users_auth_tokens (id_user, target, date_created)
                    VALUES(:id_user, :target, NOW())";                
        Yii::$app->db->createCommand($sql, [':id_user' => $this->id, ':target' => 'bizidea'])->execute();        
        $tokenId = Yii::$app->db->getLastInsertID();
        
        $sql = "UPDATE users_auth_tokens SET token = MD5(CONCAT('salt', id_user, date_created, target, 'salt2')) WHERE id = :id";                            
        Yii::$app->db->createCommand($sql, [':id' => $tokenId])->execute();
        
        $token = Yii::$app->db->createCommand("SELECT token FROM users_auth_tokens WHERE id = :id", [':id' => $tokenId])->queryOne();
        
        return $token['token'];        
    }

    /**     *
     * @param  string $password password
     * @return string password hash
     */
    public function hashPassword($password)
    {
        if ($this->getAttribute('password_type') == 'md5') {
            return md5($password);
        } else {
            return Yii::$app->security->generatePasswordHash($password);
        }
    }

    /**
     * @param string $str
     * @return self|false
     */
    public static function getByUrl($str)
    {
        preg_match('/(http|https):\/\/(.*)/ui', $str, $match);

        if (!empty($match[0])) {
            $id = array_pop(explode('/', $match[0]));

            return self::findOne($id);
        } else {
            return false;
        }
    }

    /**
     * @param bool $big
     * @return string
     */
    public function getPhoto($big = false)
    {
        if ($this->id_photo > 0) {
            return $this->avatar380x380->getFileUrl();
        } elseif ($this->photo) {
            return Apps::getStaticBebossHost() . '/users/' . $this->login . '/' . (!$big ? '' : 'bg_') . $this->photo;
        } elseif ($this->sex == self::SEX_MAN) {
            return Apps::getStaticBebossHost() . '/img/def/m' . self::getRandomPhotoNumber($this->id) . '.png';
        } elseif ($this->sex == self::SEX_WOMAN) {
            return Apps::getStaticBebossHost() . '/img/def/f' . self::getRandomPhotoNumber($this->id) . '.png';
        } else {
            return Apps::getStaticBebossHost() . '/img/def/profile_empty.jpg';
        }
    }

    public static function getRandomPhotoNumber($idUser)
    {
        return ($idUser % 3) + 1;
    }

    /**
     * @return FilesImage
     * */
    public function getCover()
    {
        return $this->hasOne(FilesImage::className(), ['id' => 'id_cover']);
    }

    /**
     * Генерация пароля
     *
     * @param int $length
     * @param string $pattern
     * @return string
     */
    public static function generatePassword(
        $length = self::DEFAULT_PASSWORD_LENGTH,
        $pattern = self::DEFAULT_PASSWORD_PATTERN
    ) {
        $patternArray = str_split($pattern, 1);
        while (count($patternArray) < $length) {
            shuffle($patternArray);
            $patternArray[] = $patternArray[mt_rand(0, count($patternArray) - 1)];
        }
        shuffle($patternArray);
        $password = substr(implode('', $patternArray), 0, $length);

        return $password;
    }


    /**
     * @param array $socialAttr
     * @return $this
     * @throws ErrorException
     * @throws \yii\base\InvalidConfigException
     */
    public static function registerToSocial($socialAttr)
    {
        $popupParams = Yii::$app->request->getQueryParam('params');
        $model = new SignupForm();
        $model->scenario = SignupForm::SCENARIO_NO_CAPTCHA;
        $model->email = $socialAttr['email'];
        if (empty(Users::findByEmail($model->email))) {
            $model->fname = $socialAttr['first_name'];
            $model->sname = $socialAttr['last_name'];
            $model->password = Users::generatePassword();
            $model->date_birth = date('Y-m-d', strtotime($socialAttr['bdate']));
            if (isset($socialAttr['phone'])) {
                $model->phone = $socialAttr['phone'];
            }
            if (isset($socialAttr['photo_big']) || isset($socialAttr['photo'])) {
                $soc_photo = isset($socialAttr['photo_big']) ? $socialAttr['photo_big'] : $socialAttr['photo'];
                $fileModel = Files::addFile($soc_photo, FilesTypes::FILES_TYPE_IMAGE);
                if ($fileModel) {
                    $croppedFile = $fileModel->createCroppedImage(FilesImageScale::SCALE_PROFILE_LOGO_380_380);
                    $croppedFile->createScaledImage(FilesImageScale::SCALE_SIZE_80_80);
                    $croppedFile->saveFile();
                    $fileModel->saveFile();
                    $model->id_photo = $croppedFile->id;
                }
            }

            if ($popupParams !== null) {
                $params = json_decode($popupParams, true);
                if (isset($params['fullUrl'])) {
                    $model->url_register = $params['fullUrl'];
                }
//                            if (isset($params['geoId']) && isset($params['geoTimezone'])) {
//                                $model->geoId = $params['geoId'];
//                                $model->geoTimezone = $params['geoTimezone'];
//                            }
//                            if (isset($params['from'])) {
//                                $model->registrationFrom = $params['from'];
//                            }
            }

            if ($user = $model->signup()) {
                Users::addUserToSocial($socialAttr, $user);
                Yii::$app->user->login($user, User::LOGGED_COOKIE_LIFE_TIME);

                return true;
            }
        } else {
            yii::$app->session->setFlash('socialUserExist', 1);
        }

        return false;
    }

    /**
     * @param array $socialAttr
     * @param Users $user
     * @throws \yii\base\InvalidConfigException
     */
    public static function addUserToSocial($socialAttr, $user)
    {
        $usersSocialLogin = new UsersSocialLogin();
        $usersSocialLogin->identity = $socialAttr['identity'];
        $usersSocialLogin->network = $socialAttr['network'];
        $usersSocialLogin->id_user = $user->id;
        $usersSocialLogin->save();
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $this->hashPassword($password);
    }

    /**
     * @return false
     */
    public function getGeo()
    {
        return $this->hasOne(GeoCity::className(), ['id' => 'id_city'])->with('region');
    }

    /**
     * @param self[] $users
     * @return self[]
     */
    public static function setIdGeoRelation($users)
    {
        //todo:переписать под бибосс
//        return RestHelper::relation($users, Geo::className(),['id' => 'id_geo']);
    }

    /**
     * @return FilesImage
     */
    public function getAvatar()
    {
        return $this->hasOne(FilesImage::className(), ['id' => 'id_photo']);
    }

    /**
     * @return FilesImage
     */
    public function getAvatar80x80()
    {
        return $this->hasOne(FilesImage::className(), ['id_parent' => 'id_photo'])
            ->andOnCondition(['id_image_scale' => FilesImageScale::SCALE_SIZE_80_80]);
    }

    /**
     * @return FilesImage
     */
    public function getAvatar380x380()
    {
        return $this->hasOne(FilesImage::className(), ['id_parent' => 'id_photo'])
            ->andOnCondition(['id_image_scale' => FilesImageScale::SCALE_PROFILE_LOGO_380_380]);
    }

    /**
     * @return string
     * @throws \yii\web\ServerErrorHttpException
     */
    public function getComment_foto()
    {
        return !empty($this->avatar80x80) ? $this->avatar80x80->getFileUrl() : $this->avatar380x380->getFileUrl();
    }

    /**
     * @return string
     * @throws \yii\web\ServerErrorHttpException
     */
    public function getSmallestfoto()
    {
        return !empty($this->avatar80x80) ? $this->avatar80x80->getFileUrl() : $this->avatar380x380->getFileUrl();
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        if (!empty($this->sname)) {
            $fullName = $this->fname . ' ' . $this->sname;
        } else {
            $fullName = $this->fname;
        }

        return $fullName;
    }

    /**
     * @return $this
     */
    public function getUserInfo()
    {
//        //todo:переписать под бибосс
//        return $this->hasMany(CompSectors::className(), ['company_sector' => 'id']);
    }

    public function isHideAnketa()
    {
        return $this->hide_anketa == 1;
    }

    /**
     * @return $this
     */
    public function getSector()
    {
        return $this->hasOne(CompSectors::className(), ['id' => 'company_sector']);
    }

    /**
     * Проверяет, что пользователь НЕ активирован
     *
     * @return bool
     */
    public function isNotActivated()
    {
        return $this->status === self::STATUS_NO_ACTIVE;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function getIsDeleted()
    {
        return $this->status === self::STATUS_DELETED;
    }

    /**
     * @throws ErrorException
     */
    public function activate()
    {
        //todo:переписать под бибосс
        $transaction = Yii::$app->db->beginTransaction();

        $this->status = Users::STATUS_ACTIVE;
//        $this->date_activate = new Expression('NOW()');

        $authAssignment = new AuthAssignment();
        $authAssignment->item_name = Users::USER_RBAC_ROLE;
        $authAssignment->user_id = $this->id;
        try {
            if (!$authAssignment->save() || !$this->save()) {
                throw new Exception('Пользователь не активирован, попробуйте позже');
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return true;
    }

    /**
     * Return user is online or offline
     * @return bool
     */
    public function getIsOnline()
    {
        $timeLastOnline = strtotime($this->last_action_date);

        if(time() - $timeLastOnline < 600) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $searchString
     * @return null|User[]
     */
    public static function getUsersByIdEmailFio($searchString)
    {
        if (!empty($searchString)) {
            $result = self::find()->where(
                'id <> :id_user AND ( id = :id OR name ILIKE :search OR surname ILIKE :search OR email ILIKE :search OR alias ILIKE :search )',
                [
                    ':id' => (int)$searchString,
                    ':search' => '%' . $searchString . '%',
                    ':id_user' => Yii::$app->user->id,
                ]
            );

            return $result->all();
        } else {
            return null;
        }
    }

    /**
     * @return int
     */
    public function countBlogPosts($justPublishedPosts = true)
    {
//        $query = $justPublishedPosts ? Posts::findAllActive() : Posts::findAllDraft();
//
//        $query->where([
//            'id_author' => $this->id,
//            'date_deleted' => null
//        ])
//            ->orderBy('');
//
//        return $query->count();
    }

    /**
     * @param string $popupLoginParams
     * @return array|mixed|string
     */
    public static function generateUrlAfterLogin($popupLoginParams)
    {
        $redirectUrl = null;

        if (isset($_SESSION['REDIRECT_URL']) && $_SESSION['REDIRECT_URL'] != '') {
            $redirectUrl = $_SESSION['REDIRECT_URL'];
            unset($_SESSION['REDIRECT_URL']);
        } else {
            $redirectUrl = Yii::$app->request->get(Users::REDIRECT_GET_PARAM);
        }
        if (!$redirectUrl) {
            if ($popupLoginParams) {
                $params = json_decode($popupLoginParams, true);
                if (isset($params[Users::REDIRECT_GET_PARAM])) {
                    $redirectUrl = Apps::getUrlHost($params[Users::REDIRECT_GET_PARAM]);
                    if ($redirectUrl) {
                        $redirectUrl = $redirectUrl . Url::to(['common/popup-login']);
                    }
                }
            }

            if (!$redirectUrl) {
                if (Yii::$app->user->identity) {
                    return Url::to(['/mypage']);
                } else {
                    return Url::to(['/']);
                }
            }
        }
        $redirectUrl = str_replace('?logout', '', $redirectUrl);
        return $redirectUrl;
    }

    /**
     * Сылка на профиль
     *
     * @return string
     */
    public function getProfile_url()
    {
        $host = Apps::getUrlHost(Apps::APP_BEBOSS);

        return $host . Url::to(['/profiles', 'id_object' => $this->id]);
    }

    public function getSend_pm_url()
    {
        $host = Apps::getUrlHost(Apps::APP_BEBOSS);

        return $host . Url::to(['/pm/to', 'id_object' => $this->id]);
    }

    public static function deleteUserPhoto($idUser)
    {
        if ($idUser > 0) {
            /** @var Users $userModel */
            $userModel = Users::findOne($idUser);
            $userModel->id_photo = null;
            $userModel->photo = null;
            $userModel->save(true, ['id_photo', 'photo']);
        }
    }

    public static function deleteUserCover($idUser)
    {
        if ($idUser > 0) {
            /** @var Users $userModel */
            $userModel = Users::findOne($idUser);
            $userModel->id_cover = null;
            $userModel->save(true, ['id_cover']);
        }
    }

    /**
     * @param int $idUser
     * @param string $photoPath Путь к файлу
     */
    public static function saveUserPhotoByPath($idUser, $photoPath)
    {
        /** @var Users $userModel */
        $userModel = Users::findOne($idUser);
        $fileModel = Files::addFile($photoPath, FilesTypes::FILES_TYPE_IMAGE);
        $userModel->id_photo = $fileModel->id;
        $userModel->save(true, ['id_photo', 'photo']);
    }


    /**
     * @param int $idUser
     * @param UploadedFile|\yii\web\UploadedFile $photoInstance UploadedFile::getInstanceByName($photo)
     * @return Users
     */
    public static function saveUserPhotoByUploadedFile($idUser, $photoInstance)
    {
        /** @var Users $userModel */
        $userModel = Users::findOne($idUser);
        $fileModel = Files::uploadAndSave($photoInstance);
        $userModel->id_photo = $fileModel->id;
        $userModel->save(true, ['id_photo', 'photo']);

        return $userModel;
    }

    public static function saveUserCoverByUploadedFile($idUser, $photoInstance)
    {
        /** @var Users $userModel */
        $userModel = Users::findOne($idUser);
        $fileModel = Files::uploadAndSave($photoInstance);
        $photoModel = FilesImage::findOne($fileModel->id);
        $croppedImage = $photoModel->createCroppedImage(FilesImageScale::SCALE_OBJECT_COVER);
        $croppedImage->saveFile();
        $userModel->id_cover = $croppedImage->id;
        $userModel->save(true, ['id_cover']);

        return $userModel;
    }

//    /**
//     * @param Users $userModel
//     * @param UploadedFile|\yii\web\UploadedFile $photoInstance UploadedFile::getInstanceByName($photo)
//     */
//    public static function setUserPhoto($userModel, $photoInstance)
//    {
//        $fileModel = Files::upload($photoInstance);
//        $fileModel->saveFile();
//        $userModel->id_photo = $fileModel->id;
//    }

    public function beforeSave($insert)
    {
        if ($this->isAttributeChanged('id_photo')) {
            if ($this->id_photo > 0) {
                /** @var FilesImage $photoModel */
                $photoModel = FilesImage::findOne($this->id_photo);
                if (!$this->avatar380x380) {
                    $croppedImage = $photoModel->createCroppedImage(FilesImageScale::SCALE_PROFILE_LOGO_380_380);
                    $croppedImage->saveFile();
                    $scaled = $croppedImage->createScaledImage(FilesImageScale::SCALE_SIZE_80_80);
                    $scaled->saveFile();
                }
                unset($this->avatar380x380);
                $avatarModel = $this->avatar380x380;
                $this->photo = $avatarModel->filepath . '/' . $avatarModel->file;
            } else {
                $this->photo = null;
            }
        }
        
        if ($this->isAttributeChanged('phone')) {
            $this->clear_phone = Utils::formatPhone($this->phone);
        }
        
        if ($this->isAttributeChanged('password')) {
            \Yii::$app->db->createCommand("UPDATE users_auth_tokens SET active ='0' WHERE id_user=:id")
                                    ->bindValue(':id', $this->id)
                                    ->execute();            
        }
        
        $this->reviewFnameOrSname();

        return parent::beforeSave($insert);
    }
    

    /**
     * Update last action date
     * @throws Exception
     */
    public function updateLastActionDate() {
        $ip = Yii::$app->request->userIP;
        $db = Yii::$app->db;

        static::getDb()->createCommand(
            'INSERT INTO users_last_actions (`id_user`, `last_ip`, `last_action_date`)
             VALUES (:id, :ip, NOW())
             ON DUPLICATE KEY UPDATE last_ip = :ip, last_action_date = NOW()',
            [':id'=> $this->id, ':ip' => $ip])->execute();
    }

    public function updateLoginLog($event) {
        if(Yii::$app->controller && Yii::$app->controller->className() == 'app\modules\bizideas\controllers\ApiController' && !in_array(Yii::$app->controller->action->id, ['login', 'register'])) {
            return false;
        }
        $ip = Yii::$app->request->userIP;
        $db = Yii::$app->db;

        static::getDb()->createCommand(
            'INSERT INTO users_logins_history (`id_user`, `date`, `url`, `ip`)
             VALUES (:id, NOW(), :url, :ip)',
            [':id'=> $event->identity->id, ':ip' => $ip, ':url' => (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['HTTP_X_ORIGINAL_URI'])])->execute();
    }

    private function reviewFnameOrSname()
    {
        if (!empty($this->id_manager) && ($this->isAttributeChanged('fname') || $this->isAttributeChanged('sname')) && !$this->isAttributeChanged('date_birth')) {
            \Crm::addTask($this->id, 'NULL', $this->id,
                "Смена имени: " . $this->getOldAttribute('fname') . " " . $this->getOldAttribute('sname') . " -> $this->fname $this->sname. Проверить ДР!",
                date('Y-m-d H:i', time() + 60 * 15), 'id_client', 'crm_service_manager_tasks');
        }

    }

    public static function findIdentityByAccessBebossToken($token, $type = null)
    {
        $identity = null;

        $user = Users::find()
            ->where('MD5(CONCAT(UNIX_TIMESTAMP(date_reg), id, "'.self::TOKEN_SALT.'", password)) = :token', [':token' => $token])
            ->one();

        if ($user) {
            $identity = $user;
        }

        return $identity;
    }
    
    public static function findIdentityByAccessBizideaToken($token, $type = null)
    {
        $identity = null;

        $userId = \Yii::$app->db->createCommand("SELECT id_user FROM users_auth_tokens WHERE token = :token AND target = 'bizidea' AND active = '1'", [':token' => $token])->queryOne();        
        
        if($userId) {
            $userId = $userId['id_user'];
            
            $user = Users::find()
                ->where('id = :id', [':id' => $userId])
                ->one();

            if ($user) {
                $identity = $user;
            }
        }

        return $identity;
    }


    public static function getUidToken($idUser)
    {
        $query = new Query();

        $query
            ->select([
                'MD5(CONCAT(UNIX_TIMESTAMP(date_reg), id, "'.Users::TOKEN_SALT.'", password))'
            ])
            ->from('users')
            ->where([
                'id' => $idUser
            ]);

        return $query->scalar();
    }
}

