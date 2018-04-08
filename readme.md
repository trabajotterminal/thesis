## Plataforma de aprendizaje de algoritmos para la programación competitiva.

<p align="justify">El siguiente sistema permite administrar contenido relacionado a la programación competitiva el cuál se puede clasificar en tres principales bloques:</p>
    
    1) Teoría
    2) Simulación
    3) Cuestionarios
    
<p align="justify">Por otra parte el sistema también es capaz de mostrar el contenido a los usuarios de modo que sirva de apoyo durante en el proceso de aprendizaje. Finalmente el sistema posee un módulo administrativo en el que se podrá obtener estádisticas relacionadas a la actividad realizada por los usuarios en el sistema.</p>        
<p align="center"><img src="https://image.ibb.co/d6bJ4x/Screen_Shot_2018_04_07_at_6_58_21_PM.png" style="width:300px;height:300px;"></p>

## Prerequisitos.

- Navegador Firefox versión 59.0.2 (64-bit) o posterior. [<a href="https://www.mozilla.org/en-US/firefox/new/">Descargar</a>]

- XAMPP v5.6.34 o posterior. [<a href="https://www.apachefriends.org/download.html">Descargar</a>]

- Composer. [<a href="https://getcomposer.org/download/">Descargar</a>]

- Laravel v5.6 o posterior. [<a href="https://laravel.com/docs/5.6/installation">Descargar</a>]
## Instalación.

1) Clonar el repositorio usando el siguiente link: https://github.com/trabajotterminal/thesis.git o descargarlo desde <a href="https://github.com/trabajotterminal/thesis/archive/master.zip">aquí</a>.

2) Colocar la carpeta descarga o clonada `thesis-master` dentro de la carpeta `XAMPP/htdocs`.

3) Iniciar `XAMPP`, ir la interfaz de `Phpmyadmin` y crear una nueva base de datos llamada `database`.

4) Copiar el contenido del archivo `thesis-master/database.sql` para posteriormente pegarlo en la caja de texto que aparece en la pestaña SQL dentro de `Phpmyadmin`.
<img src="https://i.imgur.com/lRkZ7hz.png" style="width:200px;height:200px;" />

3) Dentro de `XAMPP/htdocs/thesis-master`, ejecutar los siguientes comandos.
    
    - `composer install`
    
    - `php artisan key_generate`
    
    - `php artisan serve --port=8005`
 
 4) Ir al navegador Firefox e introducir la dirección: `127.0.0.1:8005`  
    
      

## Autores.

    - Esteban Morales Cisneros  [emc2195@gmail.com]
    - Jair Said Hernández Reyes [jairsaidds@gmail.com]


