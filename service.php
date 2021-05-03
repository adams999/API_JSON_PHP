<?php



##REQUIRE
header("Content-Type: application/JSON");
ob_start();
require_once('lib/class/general_functions.class.php');
require_once('lib/class/get_functions.class.php');
require_once('lib/class/post_functions.class.php');
require_once('lib/class/put_functions.class.php');
include_once('lib/class/cotizador.class.php');

##OBJECTS
$getFunctions =  new get_functions();
$postFunctions = new post_functions();
$putFunctions =  new put_functions();
ob_end_clean();

function API()
{

    global $getFunctions, $postFunctions, $putFunctions;

    $method = $_SERVER['REQUEST_METHOD'];
    $function = $_GET['function'];
    $api    = $_GET['token'];
    switch ($method) {
        case 'GET':
            $filter = $_GET;

            if (empty($api)) {
                $getFunctions->getError(6020, '');
            } else {
                $apiKey = $getFunctions->checkapiKey($api);
                $method_exists = method_exists($getFunctions, $function);
                !empty($apiKey) ?: $getFunctions->getError(1005, '', $api);
                !empty($method_exists) ? $getFunctions->get_fuctions($function, $api) : $getFunctions->getError(9014, '', $format);
            }
            break;
        case 'POST':
            $filters = $_GET;

            if (empty($api)) {
                $getFunctions->getError(6020, '');
            } else {
                $apiKey = $getFunctions->checkapiKey($api);
                !empty($apiKey) ?: $getFunctions->getError(1005, '', $api);
                $method_exists  = method_exists($postFunctions, $function);
                !empty($method_exists) ? $postFunctions->postFunctions($function, $api) : $postFunctions->getError(9014, '', $format);
            }
            break;
        case 'PUT':
            $api = $putFunctions->getInputs('php://input')['token'];
            if (empty($api)) {
                $getFunctions->getError(6020, '');
            } else {
                $apiKey = $getFunctions->checkapiKey($api);
                !empty($apiKey) ?: $getFunctions->getError(1005, '', $api);
                $method_exists  = method_exists($getFunctions, $function);
                $putFunctions->put_functions();
            }
            break;
        case 'DELETE':

            break;
        default:
            echo 'METODO NO SOPORTADO';
            break;
    }
}

API();
