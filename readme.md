## Plataforma de aprendizaje de algoritmos computacionales para la programación competitiva
<p align="center"><img src="https://image.ibb.co/d6bJ4x/Screen_Shot_2018_04_07_at_6_58_21_PM.png" style="width:300px;height:300px;"></p>

## Contenido
- Introducción
- Componentes del Sistema
	- Módulo de Teoría
	- Módulo de Simulación
	- Módulo de Reactivos
- Niveles de Usuario
    - Usuario Administrador
    - Usuario Creador de Contenido
    - Usuario Registrado 
    - Usuario Visitante
- Prerrequisitos
- Instrucciones de Instalación
- Colaboración
- Autores
- Licencia

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
<p align="justify">Proporciona la simulación gráfica de comportamiento de los algoritmos del compendio. Cuenta con un menú con las operaciones disponibles para cada algoritmo en particular donde el usuario ingresará los parámetros bajo los que desea visualizar la simulación gráfica.</p>


## Módulo de Reactivos
<p align="justify">Aplica y evalúa una serie de cuestionarios de opción múltiple de los temas de la plataforma. Una vez finalizado el cuestionario, le brinda al usuario una retroalimentación de las preguntas que fueron contestadas incorrectamente.</p>
     

## Niveles de Usuario
<p align="justify"> 
Los usuarios que harán uso de la plataforma se encuentran divididos en los siguientes niveles según su jerarquía:
   
    1) Usuario Administrador
    2) Usuario Creador de Contenido
    3) Usuario Registrado
    4) Usuario Visitante
</p>

## Usuario Administrador
<p align="justify">Es el usuario con mayores privilegios dentro del sistema. Se encarga de revisar las solicitudes de edición de contenido enviadas por los usuarios creadores de contenido. De manera adicional, posee un apartado para consultar las estadísticas de la actividad de los usuarios registrados.</p>

## Usuario Creador de Contenido
<p align="justify">Es el principal encargado de proveer a la plataforma nuevos temas valiosos, actualizados y empleados en las competencias de programación competitiva.</p>

## Usuario Registrado
<p align="justify">Es el usuario que tiene acceso a los módulos de teoría, simulación y reactivos. El puntaje promedio que obtiene en la resolución de sus cuestionarios es un factor que se utiliza en el top rank de la página principal.</p>

## Usuario Visitante
<p align="justify">Es un usuario invitado que sólo tiene acceso a los módulos de teoría y simulación como medio informativo para conocer de qué trata la página.</p>



## Prerrequisitos

- Sistemas operativos soportados 

    . Windows 8 de 32/64 bits o versiones superiores.
     
    . OS X Maverics 10.9 o versiones superiores.
    
    . Una versión de 32 o 64 bits de: Ubuntu 14.04, Debian 8, openSUSE 13.3 o Fedora Linux 24 o posteriores.

- Navegadores Soportados

    . Firefox versión estable 58 o superior. [ <a href="https://www.mozilla.org/en-US/firefox/new/">Descargar</a>]
    
    . Google Chrome versión estable 64.0.3282.140 o superior. [<a href="https://www.google.com.mx/chrome/">Descargar</a>]
    
    . Safari versión estable 11 o superior. [<a href="https://support.apple.com/es_MX/downloads/safari">Descargar</a>]


- Herramientas
    
    . XAMPP versión 5.6.34 o posterior. [<a href="https://www.apachefriends.org/download.html">Descargar</a>]
    
        - PHP versión 5.6.34 o superior.
        
        - PhpMyAdmin versión 4.7.9 o superior.
        
    . Composer versión 1.6.4 o superior[<a href="https://getcomposer.org/download/">Descargar</a>]
    
    . Laravel versión 5.6 o superior. [<a href="https://laravel.com/docs/5.6/installation">Descargar</a>]

    . Opcional: Git versión 2.16.2 o superior. [<a href="https://git-scm.com/downloads">Descargar</a>]    


## Instrucciones de Instalación

1) Descargar el proyecto desde aquí: <a href="https://github.com/trabajotterminal/thesis/archive/master.zip"></a>. En caso de utilizar Git, clonar el repositorio usando el siguiente link: https://github.com/trabajotterminal/thesis.git.

2) Colocar la carpeta descargada o clonada `thesis-master` dentro de la carpeta `XAMPP/htdocs`.
    - En Linux: Opt/lampp/htdocs
    - En OS X: Aplicaciones/XAMPP/xamppfiles/htdocs 
    - En Windows: C:/xampp/htdocs

3) Iniciar los módulos 'Apache' y 'MySQL' en `XAMPP` e ir la interfaz de `Phpmyadmin` y crear una nueva base de datos llamada `database`.

4) Copiar el contenido del archivo `thesis-master/database.sql` para posteriormente pegarlo en la caja de texto que aparece en la pestaña SQL dentro de `Phpmyadmin`.
<img src="https://i.imgur.com/lRkZ7hz.png" style="width:200px;height:200px;" />

5) Dentro de `XAMPP/htdocs/thesis-master`, ejecutar los siguientes comandos.
    
    - `composer install`
    
    - `php artisan key_generate`
    
    - `php artisan serve --port=8005`
 
 6) Ir al navegador e introducir la siguiente dirección: `127.0.0.1:8005`  

## Contribución

Cuando decida contribuir a este proyecto, favor de ponerse en contacto vía correo electrónico con los propietarios del repositorio.

Proceso de solicitud de cambios.
    - Cree una bifurcación del proyecto para realizar ediciones.
    - Actualice el archivo README.md con los detalles de los cambios en la interfaz, esto incluye nuevas variables de entorno, puertos expuestos y ubicaciones de archivos fundamentales.
    - Aumente los números de versión en cualquier archivo y el archivo README.md a la nueva versión que representaría esta solicitud de cambios. El esquema de control de versiones a utilizar es SemVer.
    - Realice un pull request con los cambios solicitados.


Nuestro compromiso principal es fomentar un ambiente abierto e incluyente para aquellos que deseen participar en el proyecto.

Los estándares de comportamiento para crear un ambiente positivo incluyen:

    - Ser respetuoso de diferentes puntos de vista y experiencias.
    - Aceptar la crítica constructiva.
    - Enforcarse en lo que es mejor para la comunidad.
    - Mostrar empatía hacia los demás.

## Autores

    - Esteban Morales Cisneros  [emc2195@gmail.com]
    - Jair Said Hernández Reyes [jairsaidds@gmail.com]

## Licencia

Todos los derechos reservados al Instituto Politécnico Nacional. 



