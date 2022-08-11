<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require 'vendor/autoload.php';


function conectar_PostgreSQL( $usuario, $pass, $host, $bd )
    {
         $conexion = pg_connect( "user=".$usuario." ".
                                "password=".$pass." ".
                                "host=".$host." ".
                                "dbname=".$bd
                               ) or die( "Error al conectar: ".pg_last_error() );
        return $conexion;
    }

function listarPersonas( $conexion )
    {
        $sql = "SELECT * FROM accounts ORDER BY user_id";
        $ok = true;
        // Ejecutar la consulta:
         $rs = pg_query( $conexion, $sql );
        if( $rs )
        {
            // Obtener el número de filas:
             if( pg_num_rows($rs) > 0 )
            {
                echo "<p/>LISTADO DE PERSONAS<br/>";
                echo "===================<p />";
                // Recorrer el resource y mostrar los datos:
                 while( $obj = pg_fetch_object($rs) )
                     echo $obj->user_id." - ".$obj->username."<br />";
            }
            else
                echo "<p>No se encontraron personas</p>";
        }
        else
            $ok = false;
        return $ok;
    }

    

$app = new \Slim\App;
unset($app->getContainer()['errorHandler']);
unset($app->getContainer()['phpErrorHandler']);
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
	
	$conexion=conectar_PostgreSQL("psklxpvetpqvgr","3d5faea81b3a280bfbd1e19dd9211c1da14fef7f6c090a3d09436a32232f6d14","ec2-54-225-234-165.compute-1.amazonaws.com","d54m5h8ikd7mld");
	listarPersonas($conexion);
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});
$app->run();
