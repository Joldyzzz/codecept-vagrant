<?php

namespace app\models;

use Yii;

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
 * @property string $xml_file_id
 * @property integer $crm_life_points
 * @property integer $actions_limit
 * @property string $actions_limit_popup_showed
 * @property string $social_profile
 * @property string $password_type
 * @property string $can_recieve_requests
 * @property integer $id_cover
 * @property string $photo_old
 * @property string $can_show_kn_phone
 * @property string $xml_file_date
 * @property integer $last_action_from_mobile
 *
 * @property AuthTokens[] $authTokens
 * @property Bookmarks[] $bookmarks
 * @property BzPosts[] $bzPosts
 * @property FrItems[] $frItems
 * @property FrNotes[] $frNotes
 * @property FrSavedSearches[] $frSavedSearches
 * @property GobizBizideasPublish[] $gobizBizideasPublishes
 * @property GobizBplansPublish[] $gobizBplansPublishes
 * @property GobizComments[] $gobizComments
 * @property IsProjectsQuestions[] $isProjectsQuestions
 * @property Payments[] $payments
 * @property Files $idCover
 * @property UsersRememberTokens[] $usersRememberTokens
 * @property UsersSocialLogin[] $usersSocialLogins
 * @property UsersToBzUsers[] $usersToBzUsers
 * @property UsertypeVals[] $usertypeVals
 */
class SignupForm extends \yii\db\ActiveRecord
{
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
            [['login', 'fname', 'sname', 'date_reg', 'money', 'hide_phone', 'id_usertype', 'hide_anketa', 'url_register', 'about_company', 'broker_regions', 'crm_add_info', 'crm_company_name', 'referral_id'], 'required'],
            [['login', 'adress', 'self_descr', 'additional_info', 'additional_mail', 'company_name', 'company_adress', 'company_site', 'settings', 'big_foto', 'company_logo', 'url_register', 'about_company', 'broker_regions', 'marketing_sended', 'is_region_partner', 'crm_add_info', 'crm_state', 'position', 'contact_checked', 'achieve', 'import_from', 'actions_limit_popup_showed', 'social_profile', 'password_type', 'can_recieve_requests', 'can_show_kn_phone'], 'string'],
            [['date_reg', 'date_moderated', 'date_birth', 'last_action_date', 'last_contacts_status_update', 'last_nc_close_time', 'phone_notify_last', 'last_smart_mailer', 'last_fr_smart_mailer', 'xml_file_date'], 'safe'],
            [['subscribe', 'banned', 'moderated', 'status', 'id_city', 'hide_phone', 'id_usertype', 'sex', 'id_photo', 'company_city', 'company_sector', 'hide_anketa', 'rating', 'bal', 'show_form_fr_cat', 'user_add_type', 'user_is_pro', 'ref_id', 'id_manager', 'referral_id', 'objects_limit', 'owner_id', 'id_company', 'kn_office_id', 'ordi', 'hidden', 'crm_life_points', 'actions_limit', 'id_cover', 'last_action_from_mobile'], 'integer'],
            [['money'], 'number'],
            [['password', 'clear_phone', 'photo_old'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 64],
            [['fname', 'sname', 'pname', 'network', 'nmls_id'], 'string', 'max' => 50],
            [['phone', 'highrise_id', 'highrise_token', 'report_email', 'crm_company_name', 'identity'], 'string', 'max' => 250],
            [['photo'], 'string', 'max' => 100],
            [['valid_hash'], 'string', 'max' => 32],
            [['last_action_ip'], 'string', 'max' => 15],
            [['experience', 'xml_id', 'xml_file_id'], 'string', 'max' => 200],
            [['id_cover'], 'exist', 'skipOnError' => true, 'targetClass' => Files::className(), 'targetAttribute' => ['id_cover' => 'id']],
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
            'status' => 'Status',
            'money' => 'Money',
            'date_moderated' => 'Date Moderated',
            'adress' => 'Adress',
            'id_city' => 'Id City',
            'phone' => 'Phone',
            'clear_phone' => 'Clear Phone',
            'hide_phone' => 'Hide Phone',
            'id_usertype' => 'Id Usertype',
            'date_birth' => 'Date Birth',
            'sex' => 'Sex',
            'photo' => 'Photo',
            'id_photo' => 'Id Photo',
            'self_descr' => 'Self Descr',
            'additional_info' => 'Additional Info',
            'additional_mail' => 'Additional Mail',
            'company_name' => 'Company Name',
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
            'user_add_type' => 'User Add Type',
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
            'last_contacts_status_update' => 'Last Contacts Status Update',
            'contact_checked' => 'Contact Checked',
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
            'xml_file_id' => 'Xml File ID',
            'crm_life_points' => 'Crm Life Points',
            'actions_limit' => 'Actions Limit',
            'actions_limit_popup_showed' => 'Actions Limit Popup Showed',
            'social_profile' => 'Social Profile',
            'password_type' => 'Password Type',
            'can_recieve_requests' => 'Can Recieve Requests',
            'id_cover' => 'Id Cover',
            'photo_old' => 'Photo Old',
            'can_show_kn_phone' => 'Can Show Kn Phone',
            'xml_file_date' => 'Xml File Date',
            'last_action_from_mobile' => 'Last Action From Mobile',
        ];
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
    public function getBookmarks()
    {
        return $this->hasMany(Bookmarks::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBzPosts()
    {
        return $this->hasMany(BzPosts::className(), ['id_author' => 'id']);
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
    public function getGobizBizideasPublishes()
    {
        return $this->hasMany(GobizBizideasPublish::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGobizBplansPublishes()
    {
        return $this->hasMany(GobizBplansPublish::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGobizComments()
    {
        return $this->hasMany(GobizComments::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsProjectsQuestions()
    {
        return $this->hasMany(IsProjectsQuestions::className(), ['id_user' => 'id']);
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
    public function getIdCover()
    {
        return $this->hasOne(Files::className(), ['id' => 'id_cover']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersRememberTokens()
    {
        return $this->hasMany(UsersRememberTokens::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersSocialLogins()
    {
        return $this->hasMany(UsersSocialLogin::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersToBzUsers()
    {
        return $this->hasMany(UsersToBzUsers::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsertypeVals()
    {
        return $this->hasMany(UsertypeVals::className(), ['id_user' => 'id']);
    }
}
