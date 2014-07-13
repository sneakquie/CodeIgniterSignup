<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Function needs in every dedicated language file. there is no place, where i can add this function else
 * This function return plural format of number for every language
 */
if(!function_exists('language_plural_form')) {
	function language_plural_form($number)
    {
        $q = $number % 100;

        return (($q > 10 && $q < 20) || in_array($c = $q % 10, array(0, 5, 6, 7, 8, 9,)))
        		? 0
        		: ((in_array($c, range(2, 4)))
        		   ? 2
        		   : 1);
	}
}

$lang['signup_agree_terms'] = "Необходимо принять пользовательское соглашение";
$lang['signup_title'] = "Регистрация";
$lang['signup_button'] = "Зарегистрироваться";
$lang['signup_already_have'] = "Уже есть аккаунт?";
$lang['signup_with_terms'] = "пользовательское соглашение";
$lang['signup_i_agree'] = "Я принимаю";
$lang['signup_login'] = "Имя пользователя (логин)";
$lang['signup_invite'] = "Инвайт";
$lang['signup_email'] = "Email";
$lang['signup_password'] = "Пароль";
$lang['signup_password_repeat'] = "Повторите пароль";
$lang['signup_short_login'] = "Минимум 4 символа";
$lang['signup_login_wrong_format'] = "Разрешены английские буквы, цифры и нижнее подчеркивание";
$lang['signup_login_exists'] = "Такое имя уже зарегистрировано";
$lang['signup_short_password'] = "Минимум 6 символов";
$lang['signup_not_equal'] = "Пароли не совпадают";
$lang['signup_email_wrong_format'] = "Введите действительный email адрес";
$lang['signup_email_exists'] = "Этот email уже используется";
$lang['signup_captcha'] = "Символы с картинки";
$lang['signup_wrong_captcha'] = "Необходимо ввести символы с картинки";
$lang['signup_invite_not_required'] = "(не обязательно)";
$lang['signup_invite_doesnt_exists'] = "Приглашение не найдено";
$lang['signup_invite_required'] = "Введите свое приглашение";
$lang['signup_confirm_email_message'] = "Привет, %username%. Ты зарегистрировался на сайте asdhkjzxc с помощью email %email%, такого-то числа: %date%";
$lang['signup_use_social'] = "Используйте социальные сети ;)";
$lang['signup_click_to_reload'] = "(чтобы перезагрузить, нажмите на картинку)";
$lang['signup_disabled'] = "Регистрация временно отключена,<br/>приносим свои извинения";

$lang['signup_connect_with'] = "Войти через ";
$lang['signup_connected_with'] = "Связано с ";

$lang['facebook'] = "Facebook";
$lang['vkontakte'] = "Вконтакте";
$lang['tumblr'] = "Tumblr";
$lang['github'] = "GitHub";
$lang['steam'] = "Steam";
$lang['flickr'] = "Flickr";
$lang['vimeo'] = "Vimeo";
$lang['youtube'] = "YouTube";
$lang['googleplus'] = "Google+";
$lang['odnoklassniki'] = "Одноклас..";
$lang['linkedin'] = "LinkedIn";
$lang['twitter'] = "Твиттер";

$lang['login_title'] = "Вход";
$lang['login_username'] = "Логин";
$lang['login_email_username'] = "Логин или email";
$lang['login_email'] = "Email";
$lang['login_password'] = "Пароль";
$lang['login_button'] = "Войти";
$lang['login_forgot'] = "Забыли пароль?";
$lang['login_get_account'] = "Получить аккаунт";
$lang['login_remember'] = "Запомнить";
$lang['login_wrong_data'] = "Неверный логин или пароль";
$lang['login_blocked'] = "Слишком много попыток,<br/>попробуйте позже";


$lang['confirm_wrong_code'] = "Неверный код подтверждения";
$lang['confirm_success'] = "Аккаунт подтвержден";
$lang['confirm_title'] = "Подтверждение email";
$lang['confirm_top'] = "Подтверждение";
$lang['confirm_on_main'] = "На главную";

$lang['recover_wrong_data'] = "Пользователь не найден";
$lang['recover_wrong_code'] = "Код восстановления не найден";
$lang['recover_expire_error'] = "Время жизни кода истекло, запросите пароль заново";
$lang['recover_title'] = "Восстановить пароль";
$lang['recover_button'] = "Восстановить!";
$lang['recover_sended'] = "Письмо с дальнейшими инструкциями было отправлено на Ваш email";
$lang['recover_server_error'] = "Возникла ошибка, попробуйте позже";
$lang['recover_changed'] = "Пароль был успешно изменен ;)";

$lang['signin'] = "Вход";
$lang['signup'] = "Регистрация";

$lang['topbar_logout'] = "Выход";
$lang['topbar_settings'] = "Настройки";
$lang['topbar_categories'] = "Категории";
$lang['topbar_upload'] = "Загрузить";
$lang['topbar_not'] = "Уведомления";
$lang['topbar_messages'] = "Сообщения";
$lang['topbar_search_placeholder'] = "Поиск";
$lang['topbar_settings_account'] = "Аккаунт";
$lang['topbar_settings_not'] = "Уведомления";
$lang['topbar_settings_cover'] = "Обложка профиля";
$lang['topbar_settings_avatar'] = "Изображение профиля";
$lang['topbar_settings_password'] = "Пароль";
$lang['topbar_settings_activate'] = "Активация аккаунта";
$lang['topbar_settings_social'] = "Социальные профили";
$lang['topbar_favs'] = "Избранное";

$lang['settings_language'] = "Язык";
$lang['settings_timezone'] = "Временная зона";
$lang['settings_login'] = "Логин";
$lang['settings_name'] = "Имя";
$lang['settings_born'] = "Дата рождения";
$lang['settings_month_1'] = "Январь";
$lang['settings_month_2'] = "Февраль";
$lang['settings_month_3'] = "Март";
$lang['settings_month_4'] = "Апрель";
$lang['settings_month_5'] = "Май";
$lang['settings_month_6'] = "Июнь";
$lang['settings_month_7'] = "Июль";
$lang['settings_month_8'] = "Август";
$lang['settings_month_9'] = "Сентябрь";
$lang['settings_month_10'] = "Октябрь";
$lang['settings_month_11'] = "Ноябрь";
$lang['settings_month_12'] = "Декабрь";
$lang['settings_about'] = "О себе";
$lang['settings_location'] = "Местоположение";
$lang['settings_website'] = "Website";
$lang['settings_show_date'] = "Показывать дату";
$lang['settings_show_email'] = "Показывать email";
$lang['settings_gender'] = "Пол";

$lang['settings_wrong_language'] = "Язык не существует";
$lang['settings_wrong_timezone'] = "Неверная временная зона";
$lang['settings_wrong_birth'] = "Неверный формат даты";
$lang['settings_wrong_email'] = "Введите действительный email адрес";
$lang['settings_email_exists'] = "Этот email уже используется";
$lang['settings_short_login'] = "Слишком короткий логин (минимум 4 символа)";
$lang['settings_login_wrong_format'] = "В логине разрешены английские буквы, цифры и нижнее подчеркивание";
$lang['settings_login_exists'] = "Такой логин уже зарегистрирован";
$lang['settings_wrong_website'] = "Неверный URL";
$lang['settings_saved'] = "Настройки успешно сохранены!";
$lang['settings_gender_0'] = "Не показывать";
$lang['settings_gender_1'] = "Мужской";
$lang['settings_gender_2'] = "Женский";
$lang['settings_save'] = "Сохранить";
$lang['settings_change'] = "Изменить";
$lang['settings_old_password'] = "Старый пароль";
$lang['settings_new_password'] = "Новый пароль";
$lang['settings_repeat_password'] = "Повторите пароль";
$lang['settings_wrong_new_password'] = "Неверный формат нового пароля";
$lang['settings_not_equal'] = "Пароли не совпадают";
$lang['settings_wrong_password'] = "Неверный старый пароль";
$lang['settings_last_login'] = "Последний визит";
$lang['settings_notify_comments'] = "Уведомления о комментариях";
$lang['settings_notify_comments_email'] = "Уведомления о комментариях на Email";
$lang['settings_notify_answers'] = "Уведомления об ответах";
$lang['settings_notify_answers_email'] = "Уведомления об ответах на Email";
$lang['settings_notify_messages'] = "Уведомления о сообщениях";
$lang['settings_notify_messages_email'] = "Уведомления о сообщениях на Email";
$lang['settings_notify_follow'] = "Уведомления о ленте";
$lang['settings_notify_cats'] = "Уведомления о категориях";
$lang['settings_notify_likes'] = "Уведомления о рейтинге";
$lang['settings_allow_email'] = "Письма на Email";
$lang['settings_last_login_info'] = "Показывать дату последнего входа на странице";
$lang['settings_notify_comments_info'] = "Сообщать о новых <b>комментариях в моих новостях</b>";
$lang['settings_notify_messages_info'] = "Сообщать о новых <b>личных сообщениях</b>";
$lang['settings_notify_follow_info'] = "Уведомления о новостях от людей, на которых я подписан";
$lang['settings_notify_cats_info'] = "Уведомления о новостях в категориях, на которые я подписан";
$lang['settings_notify_answers_info'] = "Сообщать об <b>ответах в комментариях</b>";
$lang['settings_allow_email_info'] = "Разрешить другим <b>писать мне на Email</b>";
$lang['settings_activate_send'] = "Сообщение было отправлено на Email";
$lang['settings_send'] = "Отправить";
$lang['settings_activate_info'] = "Сообщение с инструкциями будет отправлено на Email. Если Вы не получили сообщение, проверьте папку \"Спам\".";
$lang['settings_choose_pic'] = "Выберите изображение";
// $lang['settings_avatar_legend'] = "Изменить аватар <small>(фотография профиля)</small>";
// $lang['settings_cover_legend'] = "Изменить обложку <small>(вертикальная)</small>";
$lang['settings_avatar_info'] = "JPEG, PNG или GIF, не более %dMB.";
$lang['settings_drag_n_drop'] = "или перетащите в эту область";
$lang['settings_upload_error'] = "Произошла ошибка. Неподходящее расширение файла, либо изображение слишком большое";
$lang['delete'] = "Удалить";
$lang['fileupload_drop_text'] = "Перетащите файлы сюда";

$lang['topbar_confirm_message'] = 'Ваш email-адрес не подтверждён. Чтобы подтвердить свой email-адрес, посетите страницу <a href="%s" class="alert-link">активация аккаунта</a>.';
$lang['topbar_js_message'] = "<b>Для работы с сайтом необходима поддержка JavaScript. Включите JavaScript, или скачайте браузер, поддерживающий его.</b>";

$lang['user_follow_error'] = "Произошла ошибка";
$lang['user_follow_not_logged'] = "Нужно зарегистрироваться";
$lang['user_follow_already'] = "Вы уже подписаны на пользователя";
$lang['user_follow_success'] = "Вы подписались на новости пользователя";
$lang['user_unfollow_success'] = "Вы отписались от новостей пользователя";
$lang['user_flag_error'] = "Произошла ошибка";
$lang['user_flag_spam'] = "Слишком много жалоб, попробуйте позже";
$lang['user_flag_unlogged'] = "Чтобы отправлять жалобы, нужно зарегистрироваться";
$lang['user_flag_success'] = "Жалоба отправлена, и в скором времени будет рассмотрена";
$lang['user_flag_already'] = "Вы уже отправили жалобу на пользователя";
$lang['user_flag_tooltip'] = "Жалоба уже отправлена";
$lang['user_flag_1'] = "Спам или мошенничество";
$lang['user_flag_2'] = "Выражение ненависти";
$lang['user_flag_3'] = "Материалы сексуального характера";
$lang['user_flag_4'] = "Насилие или вредоносное поведение";
$lang['user_flag_5'] = "Нарушение авторских прав";
$lang['user_follow'] = "Подписаться";
$lang['user_unfollow'] = "Отписаться";
$lang['user_rating'] = "Рейтинг";
$lang['user_location'] = "Откуда";
$lang['user_date'] = "Родился";
$lang['user_homepage'] = "Сайт";
$lang['user_born_format'] = ":day :month :yearг.";
$lang['user_my_profile'] = "(мой профиль)";
$lang['user_profile'] = "(профиль)";
$lang['user_back'] = "Назад";
$lang['user_last_seen_f'] = "заходила";
$lang['user_last_seen_m'] = "заходил";
$lang['user_note_saved'] = "Заметка сохранена";
$lang['user_verified'] = "Подтвержден";
$lang['user_edit_short'] = "Ред.";
$lang['user_about_me'] = "О себе:";
$lang['user_note'] = "Заметка:";
$lang['user_note_info'] = "Написать заметку о пользователе (будет видна только Вам)";
$lang['user_registered'] = "Зарегистрирован:";
$lang['user_registered_by'] = "по приглашению";
$lang['user_invited'] = "Пригласил на сайт:";
$lang['user_send_message'] = "Отправить сообщение";
$lang['user_send_email'] = "Отправить Email";
$lang['user_followers'] = "Подписчики";
$lang['user_followings'] = "Подписан на";
$lang['user_edit_avatar_help'] = "Ссылка (URL) на изображение";
$lang['user_edit_verified'] = "Подтвержденный аккаунт";
$lang['user_edit_verified_help'] = "Страница пользователя подтверждена администрацией";
$lang['user_edit_password_help'] = "Оставьте поле пустым, чтобы не изменять пароль";
$lang['load_more'] = "Показать ещё";

$lang['user_no_followers'] = "У пользователя нет подписчиков";
$lang['user_no_followings'] = "Пользователь ещё ни на кого не подписался";

$lang['user_month_1'] = "Января";
$lang['user_month_2'] = "Февраля";
$lang['user_month_3'] = "Марта";
$lang['user_month_4'] = "Апреля";
$lang['user_month_5'] = "Мая";
$lang['user_month_6'] = "Июня";
$lang['user_month_7'] = "Июля";
$lang['user_month_8'] = "Августа";
$lang['user_month_9'] = "Сентября";
$lang['user_month_10'] = "Октября";
$lang['user_month_11'] = "Ноября";
$lang['user_month_12'] = "Декабря";

$lang['date_format'] = ":day :month :at :hours::minutes";
$lang['date_format_year'] = ":day :month :yearг.";

$lang['nativetime_ago'] = "назад";
$lang['yesterday'] = "вчера";
$lang['nativetime_just'] = "только что";
$lang['nativetime_years_1'] = "год";
$lang['nativetime_months_1'] = "месяц";
$lang['nativetime_weeks_1'] = "неделю";
$lang['nativetime_days_1'] = "день";
$lang['nativetime_hours_1'] = "час";
$lang['nativetime_minutes_1'] = "минуту";
$lang['nativetime_seconds_1'] = "секунду";

$lang['nativetime_years_2'] = "года";
$lang['nativetime_months_2'] = "месяца";
$lang['nativetime_weeks_2'] = "недели";
$lang['nativetime_days_2'] = "дня";
$lang['nativetime_hours_2'] = "часа";
$lang['nativetime_minutes_2'] = "минуты";
$lang['nativetime_seconds_2'] = "секунды";

$lang['nativetime_years_0'] = "лет";
$lang['nativetime_months_0'] = "месяцев";
$lang['nativetime_days_0'] = "дней";
$lang['nativetime_hours_0'] = "часов";
$lang['nativetime_minutes_0'] = "минут";
$lang['nativetime_seconds_0'] = "секунд";


$lang['date_at'] = "в";

$lang['yes'] = "Да";
$lang['no'] = "Нет";
$lang['close'] = "Закрыть";
$lang['cancel'] = "отмена";