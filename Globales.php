<?php
setlocale(LC_ALL, 'es_CO');
define('PROTOCOLO','http'. '://');
define('DOMAIN_URL', PROTOCOLO . 'localhost');

/* Segmento del lado del Servidor */
define('SD', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)) . SD);
define('APP_DIR', ROOT. 'Aplicacion' . SD);
define('APP_NUCLEO', APP_DIR. 'Nucleo' . SD);
define('APP_LIBRERIAS',APP_DIR. 'Libs' . SD);
define('PUBLIC_DIR', ROOT. 'public_html' . SD);
/* Segmento URL */
define('PUBLIC_HTML', DOMAIN_URL. '/PruebaTecnica/public_html/');
define('BASE_INDEX', '/PruebaTecnica/'); // Si, no esta en subcarpeta solo colocar '/'
define('BASE_URL', DOMAIN_URL . BASE_INDEX);

/* Datos de protecion de sesiones y control de pruebas email */
define('DATA_KEY','QWERTY#019');
define('SSL_INIT', FALSE);
define('HTTP_ONLY',TRUE);
define('SESION_NAME','PRUEBAS_DE_APLICACION');
define('TEST_EMAIL', 'mromero106@gmail.com'); 
define('STATUS_EMAIL', TRUE);

/* Formateo contenido */
define('ALL_USERS', array(
		'Index','Authenticate','Logout'));
define('FORMAT_CONTENT', 'UTF-8');
define('EXT_CONTENT', '.shtml');
define('LNG_CONTENT', 'es'); // Refer - https://www.w3schools.com/tags/ref_language_codes.asp


/* Segmento base de datos */
define('BD_DATOS', array(   
		'host'=>'localhost',
		'nombreBD'=>'nexura',
		'usuario'=>'root',
		'password'=>''
));
?>