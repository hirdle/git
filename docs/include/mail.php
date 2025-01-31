<?php

// Файлы phpmailer
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

// Переменные, которые отправляет пользователь
// $name = $_POST['name'];
// $message = $_POST['message'];
// $phone = $_POST['numb'];
// $file = $_FILES['myfile'];

// Формирование самого письма
$title = "Новая заявка";
// $body = "
// <h2>Новая заявка с сайта</h2>
// <b>Имя:</b> $name<br>
// <b>Номер телефона:</b><br>$phone
// ";
    $body = '<h1>Вам пришла новая заявка!</h1>';
    //тут еще раз проверяем, что поле имени не пустое
    if(trim(!empty($_POST['name']))){
        $body.='<p><strong>Имя:</strong> '.$_POST['name'].'</p>';
    }
    if(trim(!empty($_POST['numb']))){
        $body.='<p><strong>Номер телефона:</strong> '.$_POST['numb'].'</p>';
    }
    if(trim(!empty($_POST['message']))){
        $body.='<p><strong>Сообщение:</strong> '.$_POST['message'].'</p>';
    }

// Настройки PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();

    $mail->isSMTP();   
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true;
    //$mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};

    // Настройки вашей почты
    $mail->Host       = 'smtp.yandex.ru'; // SMTP сервера вашей почты
    $mail->Username   = 'SuperheroNo@yandex.com'; // Логин на почте
    $mail->Password   = '069009644'; // Пароль на почте
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;
    $mail->setFrom('SuperheroNo@yandex.com', 'Админ'); // Адрес самой почты и имя отправителя

    // Получатель письма
    //$mail->addAddress('novo-20@mail.ru');  
    $mail->addAddress('dmitrii.morozan@gmail.com');  
    // $mail->addAddress('youremail@gmail.com'); // Ещё один, если нужен

    // Прикрипление файлов к письму
// if (!empty($file['name'][0])) {
//     for ($ct = 0; $ct < count($file['tmp_name']); $ct++) {
//         $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name'][$ct]));
//         $filename = $file['name'][$ct];
//         if (move_uploaded_file($file['tmp_name'][$ct], $uploadfile)) {
//             $mail->addAttachment($uploadfile, $filename);
//             $rfile[] = "Файл $filename прикреплён";
//         } else {
//             $rfile[] = "Не удалось прикрепить файл $filename";
//         }
//     }   
// }


//$message = ($_POST['message']);

    if(trim(!empty($_POST['name']))){
       $name = ($_POST['name']);
    }
    if(trim(!empty($_POST['numb']))){
        $numb = ($_POST['numb']);
    }
    if(trim(!empty($_POST['message']))){
         $message = ($_POST['message']);
    }

//В переменную $token нужно вставить токен, который нам прислал @botFather
$token = "1839084486:AAHjKyh3zvpq2XJd7MjYJ9gwvcoNzBA37yA";

//Сюда вставляем chat_id
$chat_id = "-1001492657300";


//Собираем в массив то, что будет передаваться боту
    $arr = array(
        'Имя:' => $name,
        'Телефон:' => $numb,
        'Сообщение:' => $message
    );

//Настраиваем внешний вид сообщения в телеграме
    foreach($arr as $key => $value) {
        $txt .= "<b>".$key."</b> ".$value."%0A";
    };

$sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}","r");

// Отправка сообщения
$mail->isHTML(true);
$mail->Subject = $title;
$mail->Body = $body;    

// Проверяем отравленность сообщения
    if (!$mail->send()) {
        $message = 'ОшибкаPHP';
    } else {
        $message = 'Данные отправлены!';
    }

    $response = ['message' => $message];

    header('Content-type: application/json');
    echo json_encode($response);
    
?>