# prueba-tecnica
1 - Para la ejecucion del codigo se debe tener creado un .htaccess, si este proyecto se ejecuta en Apache; en donde se desvien las solicitudes de URL a traves del index.php

Ej:
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]

2 - Subir el archivo SQL de la base de datos, para poder realizar la vinculacion a esta; si se requieren cambiar los datos de acceso a la base de datos se pueden modificar estas en el archivo Globales.php, en el segmento de base de datos.

3 - Si de una instancia localhost modificar la variable global DOMAIN_URL en el archivo Globales.php.

4 - Tener en cuenta las variables globales PUBLIC_HTML y BASE_INDEX, en el archivo Globales.php; para el correcto direccionamiento de los assets y las URLs.

