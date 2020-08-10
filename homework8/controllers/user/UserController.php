<?php
namespace app\controllers\user;
use vendor\src\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct('User');
    }

    /**
     * @description for user register
     * @param $user_data
     */
    public function register($user_data){
        $res = [];
        if (!empty($user_data["phone"])){
            $phone = $user_data["phone"];
            $phone = ltrim($phone,"00");
            $phone = ltrim($phone,"+");
            $user_data["phone"] = $phone;
            $res[] = $this->model->getPhoneCodes($phone);
        }

        $country = $res[0][0]['id'];
        if (!empty($country)){
            $user_data['bday'] =  date('Y-m-d', strtotime(str_replace('/', '-', $user_data['bday'])));
            $user_data["country_id"] = $country;
            $user_data["password"] = password_hash($user_data['password'], PASSWORD_BCRYPT);
            $user_data["token"] = $this->model->generateToken();
            unset($user_data['re_password']);
//            $this->model->insertUser($user_data);
            if($this->model->insertUser($user_data)){
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                $msg = "Go to by this
              
                <a href='http://homework8/homework8/views/user/verify.php?
                token={$user_data["token"]}
                &email={$user_data["email"]}'>
                link
                </a> to verify your email";
                $this->sendEmail($user_data['email'],
                    'Registration',
                    $msg);//$headers
            }
            return true;
        }else{
            return false;
        }
    }

    public function actionIndex()
    {
        // TODO: Implement actionIndex() method.
    }

    /**
     * @description for getting country id by phone number
     * @param $phone phone number
     * @param $codes phone codes with countries
     * @return int|mixed
     */
//    private function getCountryByPhone($phone, $codes){
//        $id = 0;
//        $is_found = false;
//        if(!empty($phone)){
//            for($i = 0; $i < count($codes); $i++){
//                if($codes[$i]["phonecode"] === $phone){
//                    $id = $codes[$i]['id'];
//                    $is_found = true;
//                }
//            }
//            if(!$is_found){
//                $id = $this->getCountryByPhone(substr($phone, 0, -1), $codes);
//            }
//            return $id;
//        }
//    }

    /**
     * @descrption for sending an email
     * @param $email
     * @param $subject
     * @param $message
     * @return bool
     */
    public function sendEmail($email, $subject, $message){
        try {
            echo $message;
//            mail($email, $subject, $message);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
//        %realprogdir%\progs\Default\launcher\launcher.exe -------------------------------------------------------------------
    }

    public function verify($token, $email)
    {
        $user = $this->model->checkUserToken($token, $email);
        if(!empty($user)){
           return $this->model->verify($user[0]['id']);
        }
        //password_verify() for login
    }

    public function verifyLogin($mail,$password){
        $user = $this->model->checkUserPass($mail);
        $pass = $user[0]['password'];
       if (password_verify($password,$pass)){
           if ($user[0]['verify'] === "1"){
              return true;
           }else{
               echo 'Please verify you email address';
           }
       }else{
           return false;
       }
    }
}