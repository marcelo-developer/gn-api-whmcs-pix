<?php
require 'gerencianetpix/gerencianet-sdk/autoload.php';
include_once 'gerencianetpix/gerencianetpix_lib/api_interaction.php';
include_once 'gerencianetpix/gerencianetpix_lib/database_interaction.php';
include_once 'gerencianetpix/gerencianetpix_lib/handler/exception_handler.php';
include_once 'gerencianetpix/gerencianetpix_lib/functions/gateway_functions.php';
header('Content-Type: application/json');

$numeroFatura = $_POST['invoiceid'];

echo json_encode($numeroFatura);