<?php

$error = ''; //переменная для вывода

class Data {
    private static $users = [
        'name' => ['Empty', 'Viktor'],
        'email' => ['none@mail.ru', 'v@mukha.cc'],
    ];

    protected function __construct() { }

    protected function __clone() { }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    static function checkInput($data)
    {
        $data = stripslashes($data);
        $data = htmlentities($data);
        return strip_tags($data);
    }

    static function checkEmail($email)
    {
        //В реальном примере проверка была бы гораздо проще.. на том же SQL =)
        if (in_array($email, self::$users['email'])) {
            $log = date('Y-m-d H:i:s') . "ERROR:Пользователь с $email уже существует" . PHP_EOL;
            file_put_contents('./logsEmail.txt', $log, FILE_APPEND | LOCK_EX);
            return true;
        } else {
            $log = date('Y-m-d H:i:s') . "OK:Пользователь с $email не найден" . PHP_EOL;
            file_put_contents('./logsEmail.txt', $log, FILE_APPEND | LOCK_EX);
            return false;
        }
    }
}


if (isset($_POST['button']) && !empty($_POST['button'])) {
    if (!isset($_POST['firstName']) || empty($_POST['firstName'])) $error .= 'Поле Имя не введено' . PHP_EOL;
    if (!isset($_POST['secondName']) || empty($_POST['secondName'])) $error .= 'Поле Фамилия не введено' . PHP_EOL;
    if (!isset($_POST['email']) || empty($_POST['email'])) $error .= 'Поле e-mail не введено' . PHP_EOL;
    if (!isset($_POST['password']) || empty($_POST['password'])) $error .= 'Поле Пароль не введено' . PHP_EOL;
    if (!isset($_POST['rePassword']) || empty($_POST['rePassword'])) $error .= 'Поле Повторите пароль не введено' . PHP_EOL;
    if (!empty($error)) exit($error);

    $firstName = Data::checkInput($_POST['firstName']);
    $secondName = Data::checkInput($_POST['secondName']);
    $email = Data::checkInput($_POST['email']);
    $password = Data::checkInput($_POST['password']);
    $rePassword = Data::checkInput($_POST['rePassword']);

    $regEmail = "/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/";
    if (!preg_match($regEmail, $email)) $error .= 'Введён некорректный e-mail' . PHP_EOL;
    else if (Data::checkEmail($email)) $error .= 'Пользователь с таким именем уже существует' . PHP_EOL;
    if ($password !== $rePassword) $error .= 'Пароли не соотвествуют' . PHP_EOL;
    if (!empty($error)) exit($error);


} else {
    echo 'empty';
}
