## Plataforma de aprendizaje de algoritmos computacionales para la programación competitiva
<p align="center"><img src="https://image.ibb.co/d6bJ4x/Screen_Shot_2018_04_07_at_6_58_21_PM.png" style="width:300px;height:300px;"></p>

## Contenido
- Introducción
- Componentes del Sistema
	- Módulo de Teoría
	- Módulo de Simulación
	- Módulo de Reactivos
- Prerrequisitos
- Instrucciones de Instalación
- Autores

## Introducción
<p align="justify">El sistema que se presenta a continuación permite administrar contenido relacionado a la programación competitiva. 
El término "programación competitiva" hace referencia a concursos de programación donde el principal objetivo es resolver un conjunto de problemas a través de algoritmos conocidos que pertenecen a las categorías de teoría de números, teoría de gráficas, geometría computacional, paradigmas de programación, entre otras, con el fin de promover la creatividad, el análisis de problemas, el desarrollo de pensamiento lógico, la perseverancia y el trabajo en equipo, puesto que enfrenta a los competidores a restricciones como el número de intentos, tiempo de ejecución máximo y un limitado espacio de memoria.</p>

## Componentes del Sistema
<p align="justify"> 
El contenido de la plataforma se divide en tres componentes principales:
   
    1) Módulo de Teoría
    2) Módulo de Simulación
    3) Módulo de Reactivos
</p>

## Módulo de Teoría
<p align="justify">Provee el sustento teórico y matemático que sirve como material de apoyo en el proceso de comprensión de los algoritmos. Su estructura hace uso de elementos como títulos, subtítulos, párrafos, secciones de código, fórmulas, imágenes y videos. Adicionalmente, incluye observaciones y problemas propuestos en los que se pueden emplear los algoritmos del sistema para su resolución.</p>


## Módulo de Simulación
<p align="justify">Proporciona la simulación gráfica de comportamiento de los algoritmos del compendio. Cuenta con un menú con las operaciones disponibles para cada algoritmo donde el usuario ingresará los parámetros bajo los que desea visualizar la simulación gráfica.</p>


## Módulo de Reactivos
<p align="justify">Aplica y evalúa una serie de cuestionarios de opción múltiple de los temas de la plataforma. Una vez finalizado un cuestionario, le brinda al usuario una retroalimentación de las preguntas que fueron contestadas incorrectamente.</p>

<p align="justify">Asimismo, posee un apartado administrativo en el que se podrá obtener estádisticas relacionadas a la actividad de los usuarios registrados y visitantes en el sistema.</p>        

## Prerrequisitos

- Sistemas operativos soportados 

    . Windows 8 de 32/64 bits o versiones superiores.
     
    . OS X Maverics 10.9 o versiones posteriores
    
    . Una versión de 32 o 64 bits de: Ubuntu 14.04, Debian 8, openSUSE 13.3 o Fedora Linux 24 o posteriores.
    
    . Android versión estable 8.1.0 "Oreo"
    
    . IOS versión estable 11.2.6

- Herramientas

    . Navegador Firefox versión estable 58 o posterior. [ <a href="https://www.mozilla.org/en-US/firefox/new/">Descargar</a>]
    
    . Navegador Google Chrome versión estable 64.0.3282.140 o posterior. [<a href="https://www.google.com.mx/chrome/">Descargar</a>]
    
    . Navegador Safari versión estable 11 o posterior. [<a href="https://support.apple.com/es_MX/downloads/safari">Descargar</a>]
    
    . XAMPP v5.6.34 o posterior. [<a href="https://www.apachefriends.org/download.html">Descargar</a>]
    
        - PHP versión 5.6.34 o superior.
        
        - phpMyAdmin versión 4.7.9 o superior.
        
    . Composer. [<a href="https://getcomposer.org/download/">Descargar</a>]
    
    . Laravel versión 5.6 o posterior. [<a href="https://laravel.com/docs/5.6/installation">Descargar</a>]

## Instrucciones de Instalación

1) Clonar el repositorio usando el siguiente link: https://github.com/trabajotterminal/thesis.git o descargarlo desde <a href="https://github.com/trabajotterminal/thesis/archive/master.zip">aquí</a>.

2) Colocar la carpeta descarga o clonada `thesis-master` dentro de la carpeta `XAMPP/htdocs`.

3) Iniciar `XAMPP`, ir la interfaz de `Phpmyadmin` y crear una nueva base de datos llamada `database`.

4) Copiar el contenido del archivo `thesis-master/database.sql` para posteriormente pegarlo en la caja de texto que aparece en la pestaña SQL dentro de `Phpmyadmin`.
<img src="https://i.imgur.com/lRkZ7hz.png" style="width:200px;height:200px;" />

5) Dentro de `XAMPP/htdocs/thesis-master`, ejecutar los siguientes comandos.
    
    - `composer install`
    
    - `php artisan key_generate`
    
    - `php artisan serve --port=8005`
 
 6) Ir al navegador Firefox e introducir la dirección: `127.0.0.1:8005`  
    

## Autores

    - Esteban Morales Cisneros  [emc2195@gmail.com]
    - Jair Said Hernández Reyes [jairsaidds@gmail.com]

