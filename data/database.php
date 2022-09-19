<?php
$db = new mysqli('localhost', 'cursos', '$123456', 'financiera');
if(!$db){
    echo 'Error al conectar la base de datos';
    exit;
}