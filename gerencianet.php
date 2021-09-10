<?php

require 'gerencianet/gerencianet-sdk/autoload.php';
include_once 'gerencianet/gerencianet_lib/api_interaction.php';
include_once 'gerencianet/gerencianet_lib/database_interaction.php';
include_once 'gerencianet/gerencianet_lib/handler/exception_handler.php';
include_once 'gerencianet/gerencianet_lib/functions/pix/gateway_functions.php';
include_once 'gerencianet/gerencianet_lib/GerencianetValidation.php';
include_once 'gerencianet/gerencianet_lib/GerencianetIntegration.php';
include_once 'gerencianet/gerencianet_lib/functions/boleto/gateway_functions.php';
include_once 'gerencianet/gerencianet_lib/Gerencianet_WHMCS_Interface.php';

if (!defined('WHMCS')) {
    die('This file cannot be accessed directly');
}

/**
 * Define gateway configuration options.
 *
 * The fields you define here determine the configuration options that are
 * presented to administrator users when activating and configuring your
 * payment gateway module for use.
 *
 * Supported field types include:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each field type and their possible configuration parameters are
 * provided in the sample function below.
 *
 * @see https://developers.whmcs.com/payment-gateways/configuration/
 *
 * @return array
 */
function gerencianet_config()
{
    return array(
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'Gerencianet',
        ),
        'clientIdProd' => array(
            'FriendlyName' => 'Client_ID de Produção (obrigatório)',
            'Type' => 'text',
            'Size' => '250',
            'Default' => '',
            'Description' => '',
        ),
        'clientSecretProd' => array(
            'FriendlyName' => 'Client_Secret de Produção (obrigatório)',
            'Type' => 'text',
            'Size' => '250',
            'Default' => '',
            'Description' => '',
        ),
        'clientIdSandbox' => array(
            'FriendlyName' => 'Client_ID de Sandbox (obrigatório)',
            'Type' => 'text',
            'Size' => '250',
            'Default' => '',
            'Description' => '',
        ),
        'clientSecretSandbox' => array(
            'FriendlyName' => 'Client_Secret de Sandbox (obrigatório)',
            'Type' => 'text',
            'Size' => '250',
            'Default' => '',
            'Description' => '',
        ),
        "idConta"           => array(
            "FriendlyName"  => "Identificador da Conta (*)",
            "Type"          => "text",
            "Size"          => "32",
            "Description"   => " (preenchimento obrigatório)",
        ),
        
        "whmcsAdmin"    => array(
            "FriendlyName"  => "Usuario administrador do WHMCS (*)",
            "Type"          => "text",
            "Description"   => "Insira o nome do usuário administrador do WHMCS.",
            "Description"   => " (preenchimento obrigatório)",
        ),
        "descontoBoleto"    => array(
            "FriendlyName"  => "Desconto do Boleto",
            "Type"          => "text",
            "Description"   => "Desconto para pagamentos no boleto bancário.",
        ),
        "tipoDesconto"      => array(
            'FriendlyName'  => 'Tipo de desconto',
            'Type'          => 'dropdown',
            'Options'       => array(
                '1'         => '% (Porcentagem)',
                '2'         => 'R$ (Reais)',
            ),
            'Description'   => 'Escolha a forma do desconto: Porcentagem ou em Reais',
        ),
        "numDiasParaVencimento" => array(
            "FriendlyName"      => "Número de dias para o vencimento da cobrança",
            "Type"              => "text",
            "Description"       => "Número de dias corridos para o vencimento da cobrança depois que a mesma foi gerada",
        ),
        "documentField" => array(
            "FriendlyName"      => "Nome do campo referente à CPF e/ou CNPJ (*)",
            "Type"              => "text",
            "Value" => "CPF/CNPJ",
            "Description"       => "Informe o nome do campo referente à CPF e/ou CNPJ no seu WHMCS. (preenchimento obrigatório)",
        ),
        "sendEmailGN"       => array(
            "FriendlyName"  => "Email de cobraça - Gerencianet",
            "Type"          => "yesno",
            "Description"   => "Marque esta opção se você deseja que a Gerencianet envie emails de transações para o cliente final",
        ),
        "fineValue"         => array(
            "FriendlyName"  => "Configuração de Multa",
            'Type'          => 'text',
            "Description"   => "Valor da multa se pago após o vencimento - informe em porcentagem (mínimo 0,01% e máximo 10%).",
        ),
        "interestValue"         => array(
            "FriendlyName"  => "Configuração de Juros",
            'Type'          => 'text',
            "Description"   => "Valor de juros por dia se pago após o vencimento - informe em porcentagem (mínimo 0,001% e máximo 0,33%).",
        ),
        "message"      => array(
            'FriendlyName'  => 'Observação',
            'Type'          => 'text',
            'Size'          => '80',
            'Description'   => 'Permite incluir no boleto uma mensagem para o cliente (máximo de 80 caracteres).',
        ),
        'sandbox' => array(
            'FriendlyName' => 'Sandbox',
            'Type' => 'yesno',
            'Description' => 'Habilita o modo Sandbox da Gerencianet',
        ),
        'debug' => array(
            'FriendlyName' => 'Debug',
            'Type' => 'yesno',
            'Description' => 'Habilita o modo Debug',
        ),
        'pixKey' => array(
            'FriendlyName' => 'Chave Pix (obrigatório)',
            'Type' => 'text',
            'Size' => '250',
            'Default' => '',
            'Description' => 'Insira sua chave Pix padrão para recebimentos',
        ),
        'pixCert' => array(
            'FriendlyName' => 'Certificado Pix',
            'Type' => 'text',
            'Size' => '350',
            'Default' => '/var/certs/cert.pem',
            'Description' => 'Insira o caminho do seu certificado .pem',
        ),
        'pixDiscount' => array(
            'FriendlyName' => 'Desconto do Pix (porcentagem %)',
            'Type' => 'text',
            'Size' => '3',
            'Default' => '0%',
            'Description' => 'Preencha um valor caso queira dar um desconto para pagamentos via Pix',
        ),
        'pixDays' => array(
            'FriendlyName' => 'Validade da Cobrança em Dias',
            'Type' => 'text',
            'Size' => '3',
            'Default' => '1',
            'Description' => 'Tempo em dias de validade da cobrança',
        ),
        'mtls' => array(
            'FriendlyName' => 'Validar mTLS',
            'Type' => 'yesno',
            'Default' => true,
            'Description' => 'Entenda os riscos de não configurar o mTLS acessando o link https://gnetbr.com/rke4baDVyd',
        )
    );
}

/**
 * Payment link.
 *
 * Required by third party payment gateway modules only.
 *
 * Defines the HTML output displayed on an invoice. Typically consists of an
 * HTML form that will take the user to the payment gateway endpoint.
 *
 * @param array $gatewayParams Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/third-party-gateway/
 *
 * @return string
 */
function gerencianet_link($gatewayParams)
{
    $baseUrl = $gatewayParams['systemurl'];

    $paymentOptionsScript = "<script type=\"text/javascript\" src=\"$baseUrl/modules/gateways/gerencianet/gerencianet_lib/scripts/js/viewInvoiceModal.js\"></script>";



    if (!isset($_POST['optionPayment'])) {
        return $paymentOptionsScript;
    } else {
        if ($_POST['optionPayment'] == 'pix') {
            //  Validate if required parameters are empty
            validateRequiredParams($gatewayParams);

            // Getting API Instance
            $api_instance = getGerencianetApiInstance($gatewayParams);

            // Creating table 'tblgerencianetpix'
            createGerencianetPixTable();

            // Verifying if exists a Pix Charge for current invoiceId
            $existingPixCharge = getPixCharge($gatewayParams['invoiceid']);

            if (empty($existingPixCharge)) {
                // Creating a new Pix Charge
                $newPixCharge = createPixCharge($api_instance, $gatewayParams);

                if (isset($newPixCharge['txid'])) {
                    // Storing Pix Charge Infos on table 'tblgerencianetpix' for later use
                    storePixChargeInfo($newPixCharge, $gatewayParams);
                }
            }

            // Generating QR Code
            $locId = $existingPixCharge ? $existingPixCharge['locid'] : $newPixCharge['loc']['id'];
            return createQRCode($api_instance, $locId);
        }elseif ($_POST['optionPayment'] == 'boleto') {
            $geraCharge = false;
            if (isset($_POST['geraCharge']))
                $geraCharge = $_POST['geraCharge'];
            /* **************************************** Verifica se a versão do PHP é compatível com a API ******************************** */
        
            if (version_compare(PHP_VERSION, '7.3') < 0) {
                $errorMsg = 'A versão do PHP do servidor onde o WHMCS está hospedado não é compatível com o módulo Gerencianet. Atualize o PHP para uma versão igual ou superior à versão 5.4.39';
                if ($gatewayParams['debug'] == "on")
                    logTransaction('gerencianet', $errorMsg, 'Erro de Versão');
        
                return send_errors(array('Erro Inesperado: Ocorreu um erro inesperado. Entre em contato com o responsável do WHMCS.'));
            }
            /* ************************************************ Define mensagens de erro ***************************************************/
        
        
            $errorMessages = array();
            $errorMessages = validationParams($gatewayParams);
        
        
            /* ******************************************** Gateway Configuration Parameters ******************************************* */
            $descontoBoleto         = $gatewayParams['descontoBoleto'];
            $tipoDesconto           = $gatewayParams['tipoDesconto'];
            $minValue               = 5; //Valor mínimo de emissão de boleto na Gerencianet
            $debug            = $gatewayParams['debug'];
            $adminWHMCS             = $gatewayParams['whmcsAdmin'];
            if ($adminWHMCS == '' || $adminWHMCS == null) {
                array_push($errorMessages, INTEGRATION_ERROR_MESSAGE);
                if ($debug == "on")
                    logTransaction('gerencianet', 'O campo - Usuario administrador do WHMCS - está preenchido incorretamente', 'Erro de Integração');
                return send_errors($errorMessages);
            }
        
            /* ***************************** Verifica se já existe um boleto para o pedido em questão *********************************** */
        
            $gnIntegration = new GerencianetIntegration($gatewayParams['clientIdProd'], $gatewayParams['clientSecretProd'], $gatewayParams['clientIdSandbox'], $gatewayParams['clientSecretSandbox'], $gatewayParams['sandbox'], $gatewayParams['idConta']);
            $existingChargeConfirm = existingCharge($gatewayParams, $gnIntegration);
            $existingCharge = $existingChargeConfirm['existCharge'];
            $code = $existingChargeConfirm['code'];
            if ($existingCharge) {
                return $code;
            }
        
            $invoiceDescription         = $gatewayParams['description'];
            $invoiceAmount              = $gatewayParams['amount'];
            if ($invoiceAmount < $minValue) {
                $limitMsg = "<div id=limit-value-msg style='font-weight:bold; color:#cc0000;'>Transação Não permitida: Você está tentando pagar uma fatura de<br> R$ $invoiceAmount. Para gerar o boleto Gerencianet, o valor mínimo do pedido deve ser de R$ $minValue</div>";
                return $limitMsg;
            }
        
            if ($geraCharge == true) { 
                /* ************************************************* Invoice parameters *************************************************** */
                return createBillet($gatewayParams, $gnIntegration, $errorMessages,$existingCharge);
            } else {
                return buttonGerencianet(null, null, $descontoBoleto, $tipoDesconto);
            }
        }
    }
}

/**
 * Refund transaction
 *
 * Called when a refund is requested for a previously successful transaction
 *
 * @param array $gatewayParams Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/refunds/
 *
 * @return array Transaction response status
 */
function gerencianet_refund($gatewayParams)
{
    //  Validating if required parameters are empty
    validateRequiredParams($gatewayParams);

    // Getting API Instance
    $api_instance = getGerencianetApiInstance($gatewayParams);

    // Refunding Pix Charge
    $responseData = refundCharge($api_instance, $gatewayParams);

    return array(
        'status' => $responseData['rtrId'] ? 'success' : 'error',
        'rawdata' => $responseData,
        'transid' => $responseData['rtrId'] ? $responseData['rtrId'] : 'Not Refunded',
    );
}
