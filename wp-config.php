<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'hip_hop_union');

/** Имя пользователя MySQL */
define('DB_USER', 'root');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'root');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'i5f.eqML0Qc~<fX57,PJfr[,~&mukl4 eBY!AZe:*F^ FeLG.0*Rk*8/<L&} /iz');
define('SECURE_AUTH_KEY',  ']Iv#m1jd*<Or6Y7N-eM<Q9r]93CZK]]4BNf5*+)W<+St2W}Wo<VM@ho9%D<5z`V(');
define('LOGGED_IN_KEY',    'FcIJ~aBAuEDjVH:t BrbFk#/Xm{G]Yra~xu+<<tSag%ka<?HL{_!whE&Ox0pa%`c');
define('NONCE_KEY',        'h!k/D:D!26ZLzp{F~}L{uqQ0Da68nu<IiI_/#nLO0CuB[<V!q`K!N;{GW{pJI_|,');
define('AUTH_SALT',        'x^qE..gi>K`5rux>pM mFu)THCL:`J A[V;^7QzLe%GWauJQjr.^a?B(d$QquhN?');
define('SECURE_AUTH_SALT', '}M)D5V$$x:*e8OxjN6sJe*i)[>H|DVCCpgwX_psva^*iW|1-Jdo4AzXod{`p$|2m');
define('LOGGED_IN_SALT',   ':sX5/C(;iT[$odt5}1q#PN:U5<4[w>JDd>.{P5E)v={i>r*M[%bDPB0b3`Xr`_dl');
define('NONCE_SALT',       'xFQqjCr[^^jz=*qh<T!e2WML{_JiVn23g4trNl@wd^gOkS%Q/J0a4II TomCC/-f');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'union_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
