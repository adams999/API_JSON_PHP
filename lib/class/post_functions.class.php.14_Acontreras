<?php

ob_start();

require('quotegeneral.class.php');

require('quote_general_new.class.php');

ob_end_clean();

class post_functions extends general_functions
{
	function __construct()
	{
		$this->data	= ($_POST) ? $_POST : $this->getInputs('php://input');
		$this->api 	= $this->data['token'];
	}
	public function postFunctions($function, $apikey)
	{
		$format = $this->data['format'];
		$response   = $this->$function($_POST, $apikey);
		$countResponse = count($response);
		$this->logsave(array_merge($_POST, $_GET), json_encode($response), $function, '4', $apikey);
		($countResponse != 0) ? $this->response($response, '', $format) : $this->getError(9015, '', $format);
	}
	public function sendQuote()
	{
		$filters     = $_GET;
		$prefix 	 = $this->data['prefix'];
		$category 	 = $this->data['category'];
		$plans		 = $this->data['plans'];
		$plans 		 = explode(',', $plans);
		$plans       = array_unique($plans);
		$plans       = implode(',', $plans);
		$startDate	 = $this->data['startDate'];
		$endDate	 = $this->data['endDate'];
		$destination = $this->data['destination'];
		$origin      = $this->data['origen'];
		$emailQuote	 = $this->data['email'];
		$nameQuote	 = $this->data['name'];
		$agesQuote 	 = $this->data['ages'];
		$passQuote 	 = count(explode(',', $agesQuote));
		$lang_app    = "es";
		$lang_app    = $this->funcLangAppShort($this->funcLangApp());

		$dataValida			= [
			"6027"  => $origin,
			'6036'	=> $nameQuote,
			'4004'	=> $emailQuote,
			"1080"  => !empty($destination) ?: true,
			"6029"  => $startDate,
			"9094"  => $category,
			"6030"  => $endDate,
			'2001'	=> $this->checkDates($startDate),
			'2002'	=> $this->checkDates($endDate),
			//'9095'	=> is_array($agesQuote),
			'9092'	=> $prefix
		];
		$this->validatEmpty($dataValida);
		$departureTrans     = $this->transformerDate($startDate);
		$arrivalTrans     	= $this->transformerDate($endDate);
		$daysByPeople 		= $this->betweenDates($departureTrans, $arrivalTrans);
		$idBroker           = (!empty($filters['agency']) && $filters['agency'] != 'N/A')  ? $filters['agency'] : '';
		$idUser             = $filters['id_user'] ?: '';
		$min_days           = $filters['bloque']  ?: '';
		$userType           = $filters['userType'];

		$dataQuote = [
			'plan_tipo'		 => $category,
			'PlanSel'		 => $plans,
			'nom'			 => $nameQuote,
			'edades'		 => $agesQuote . ',',
			'salida'		 => $departureTrans,
			'llegada'		 => $arrivalTrans,
			'dias'			 => $daysByPeople,
			'nropasajeros'   => $passQuote,
			'origen'		 => $origin,
			'destino'		 => $destination,
			'email_cliente'	 => $emailQuote,
			'min_days'       => $min_days,
			'id_user'        => $idUser,
			'user_type'      => $userType,
			'broker_sesion'  => $idBroker, //parametro que recibe el core.lib de la plataforma para cargar los parametros de la agencia 
			'id_broker'      => $idBroker, //parametro que recibe el async_cotizador
			'type'			 => 'enviar_correo',
			'selectLanguage' => $lang_app
		];
		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkQuote 	= $link . "/app/pages/async_cotizador.php";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$resp = $this->curlGeneral($linkQuote, $dataQuote, $headers, 'GET');
		$preOrd = $this->preOrderApp(json_encode($_POST));
		return [
			'resp'      => strip_tags($resp),
			'status'	=> 'OK',
			'preOrden'  => json_decode($preOrd, true)
		];
	}
	public function postParamPlatform()
	{
		$prefix   = $this->data['prefix'];
		$dataValida			= [
			'9092'	=> $prefix
		];
		$this->validatEmpty($dataValida);
		$data = [
			'querys' => "SELECT
			parameter_key,
			parameter_value
		FROM
			parameters
		WHERE
			parameter_key IN('DOWNLOAD_PDF_VOUCHER_APP', 'SEND_EMAIL_APP', 'SEND_SMS_APP')
		ORDER BY
			parameter_key ASC"
		];
		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkParam 	= $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$response   = $this->curlGeneral($linkParam, json_encode($data), $headers);

		return json_decode($response);
	}
	public function postInformAgency()
	{
		$prefix   = $this->data['prefix'];
		$dataValida			= [
			'9092'	=> $prefix
		];

		$this->validatEmpty($dataValida);

		$data = [
			'querys' => "SELECT
						parameter_key,
						parameter_value,
						NAME,
						id_country,
						(
							SELECT
								description
							FROM
								states
							WHERE
								iso_state = parameters.id_state
						) AS id_state,
						IFNULL((
							SELECT
								description
							FROM
								cities
							WHERE
								iso_city = parameters.id_city AND iso_city > 0 
						LIMIT 1
						),'N/A') AS id_city,
						address_parameter,
						zip_code,
						phone1,
						phone2,
						phone3,
						phone4
					FROM
						parameters
					WHERE
						parameter_key IN (
							'SYSTEM_NAME',
							'EMAIL_FROM',
					'ID_WHATSAPP'
					)
					ORDER BY
						parameter_key ASC"
		];
		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkParam 	= $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$response = $this->curlGeneral($linkParam, json_encode($data), $headers);
		return json_decode($response);
	}
	public function sendSms($filters)
	{
		$codPhone = $this->data['codPhone'];
		$numPhone = $this->data['numPhone'];
		$prefix   = $this->data['prefix'];
		$code	  = $this->data['code'];
		$name     = $this->data['name'];
		$lang_app = $this->funcLangApp();
		$linkVoucher = $this->data['linkVoucher'];
		$salida = $this->data['salida'];
		$nomClient = $this->data['nomClient'];
		$id_user = (!empty($_GET['id_user'])) ? $_GET['id_user'] : '';
		(!empty($_GET['agency']) && $_GET['agency'] != 'N/A') ? $id_broker = $_GET['agency'] : '';

		$dataSMS = [
			'code'      => $code,
			'star_date' => $salida,
			'idBroker'  => $id_broker,
			'id_user'   => $id_user
		];

		$dataValida			= [
			"40095" => $codPhone,
			'6025'	=> $numPhone,
			'9092'	=> $prefix,
			"6023"  => $code,
			"40097" => $linkVoucher,
			"40099" => $salida,
			"50000"  => $nomClient
		];
		$this->validatEmpty($dataValida);

		if ($lang_app == 'eng') {
			$message = "$nomClient wishes you a Happy Journey Start:$salida Voucher:$code $linkVoucher";
		} else if ($lang_app == 'por') {
			$message = "$nomClient deseja-lhe Feliz Jornada Começar:$salida Ordem:$code $linkVoucher";
		} else {
			$message = "$nomClient le desea Feliz Viaje inicio:$salida Orden:$code $linkVoucher";
		}

		$dataSms = [
			"type"			=> "Send_message",
			"codPhone" 		=> $codPhone,
			"phone"			=> $numPhone,
			"message" 		=> $message,
			"salida"        => $salida,
			"id_user"       => $id_user,
			"dataSMS"       => $dataSMS,
			"subject"       => "SMS_APP"
		];
		$dataSmsResponse = [
			"type"			=> "Response_sms",
		];
		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkSms 	= $link . "/app/admin/sms.php";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$response = $this->curlGeneral($linkSms, http_build_query($dataSms), $headers);
		$this->curlGeneral($linkSms, http_build_query($dataSmsResponse), $headers);
		return trim(strip_tags($response));
	}
	public function postUpdatePreorden()
	{
		$allData        = json_encode(array_merge($_GET, $this->data));
		return [
			'preOrden' => json_decode($this->preOrderApp($allData), true),
			'data'   => json_decode($allData)
		];
	}
	public function postProcesarEmisionApp()
	{
		$allData        = array_merge($_GET, json_decode($_POST['data'], true), $_POST);
		$allData['TDC']["codigoTarjeta"] = str_replace(' ', '', $allData['TDC']["codigoTarjeta"]);
		(bool) $platfProcNuevo = (json_decode($allData['array_prices_app'], true)['calc_new'] == 'Y') ? true : false; ////// aqui se identifica si la plataforma es con la version nueva del cotizador 
		$tipoPagoApp    = $allData['tipoPagoApp'];
		$prefix         = $allData['prefix'];
		switch (true) {
			case $tipoPagoApp == 'PAY_CREDIT_CARD':
				$tipoPago = ($platfProcNuevo && !in_array($prefix, ['WM'])) ? 1 : 2;
				break;

			case $tipoPagoApp == 'USE_PAYPAL':
				$tipoPago = 3;
				break;

			case $tipoPagoApp == 'CREDIT_AGENCY':
				$tipoPago = ($platfProcNuevo && !in_array($prefix, ['WM'])) ? 0 : 1;
				break;

			case $tipoPagoApp == 'SHIPPING_LINK':
				$tipoPago = 4;
				break;
		}

		$cardNumber   	= $allData['TDC']["codigoTarjeta"];
		$cardExpiry   	= ($allData['TDC']["yearTarjetaVen"] && $allData['TDC']["mesTarjetaVen"]) ? (int) $allData['TDC']["yearTarjetaVen"] . '-' . (int) $allData['TDC']["mesTarjetaVen"] : '';
		$cardCvv      	= $allData['TDC']["CCV"];
		$cardName     	= $allData['TDC']["nombreTarjeta"];
		$cardLastname 	= $allData['TDC']["apellidoTarjeta"];
		$cardType     	= $allData['TDC']["tipoTarjeta"];
		$id_orden       = json_decode($allData['dataRespQuoteApp'], true)['id_orden'] ? json_decode($allData['dataRespQuoteApp'], true)['id_orden'] : '';
		$ids_pasaj      = json_decode($allData['dataRespQuoteApp'], true)['id_beneficiaries'] ? json_decode($allData['dataRespQuoteApp'], true)['id_beneficiaries'] : '';
		$preOrden     	= $allData["idPreOrden"];
		$invoice      	= !empty($allData["voucher"]) ? $allData["voucher"] : $this->genCodigoOrden($prefix, (!empty($allData['prefAgency']) && $allData['prefAgency'] != 'N/A') ? $allData['prefAgency'] : null);
		$attempt     	= ((int) $allData["intento"] + 1);
		$id_broker 		= ($allData['agency'] != 'N/A' && !empty($allData['agency'])) ? $allData['agency'] : 118;
		$lang_app  		= $this->funcLangAppShort($this->funcLangApp());
		$userType 	  	= $allData['userType'];
		$id_user	  	= !empty($allData['id_user']) ? $allData['id_user'] : 0;
		$dataPasajeros  = json_decode($_POST['data'], true)['dataPasajeros'];

		for ($i = 0; $i < count($dataPasajeros); $i++) { //////aqui genero la data de los pasajeros para ser guardados
			$dataPasajeros[$i]['codigoVoucher'] 		 = $invoice;
			$dataPreOrden['nacimiento' . $i] 	 		 = $dataPasajeros[$i]['fechaNacimiento'];
		}

		$dataPreOrden['pasajeros']     		= $dataPasajeros;
		$dataPreOrden['contacto_emergencia'] = json_decode($allData['data'], true)['contactoEmergencia'];
		$dataPreOrden['upgrades'] 			= json_decode($allData['data'], true)['upgrades'];
		$dataPreOrden['cupon'] 				= json_decode($allData['data'], true)['cupon'];
		$dataPreOrden['id_preorden'] 		= json_decode($allData['data'], true)['idPreOrden'];
		$dataPreOrden['array_prices_app']   = json_decode($allData['array_prices_app'], true);
		$dataPreOrden['bloque']   			= $allData['bloque'];
		$dataPreOrden['FechaSalida']   		= $allData['FechaSalida'];
		$dataPreOrden['FechaLlegada']   	= $allData['FechaLlegada'];
		$dataPreOrden['id_plan']  			= $allData['id_plan'];
		$dataPreOrden['edades'] 			= explode(',',  $allData['edades']);
		$dataPreOrden['id_plan_categoria']  = $allData['id_plan_categoria'];
		$dataPreOrden['origen']  			= $allData['origen'];
		$dataPreOrden['destino']  			= $allData['destino'];
		$dataPreOrden['paso']  			    = $allData['paso'];
		$dataPreOrden['estatus']  		    = '2';
		$dataPreOrden['codigo']  		    = $invoice;
		$dataPreOrden['dias']  		    	= $allData['total_dias'];
		$idPreOrden     					= $this->preOrderApp(json_encode($dataPreOrden));

		$dataValida = [
			'9092'	=> $prefix,
			'50029'	=> $preOrden,
			'50030'	=> $invoice
		];

		///validacion de tdc cuando el cupon no cubre toda la emision
		if (json_decode($allData['data'], true)['cupon']['PAGO_CUPON'] != 'Si' && $tipoPagoApp == 'PAY_CREDIT_CARD') {
			$dataValida = array_merge(
				$dataValida,
				[
					'50022'	=> $cardNumber,
					'50023'	=> $cardExpiry,
					'50024'	=> $cardCvv,
					'50025'	=> $cardName,
					'50026'	=> $cardLastname,
					'50027'	=> $cardType,
				]
			);
		}

		//intentos superados 
		if ($attempt >= 4) {
			$dataValida = array_merge(
				$dataValida,
				[
					'50032'	=> true
				]
			);
		}

		$this->validatEmpty($dataValida);

		///Ordenar data de cupones para versiones de cotizador con un solo descuento 
		if ($dataPreOrden['cupon']['NOMBRE_AMIGABLE'] || $dataPreOrden['cupon']['CODIGO']) {
			$descuentoAux = $dataPreOrden['cupon']['NOMBRE_AMIGABLE'] ? $dataPreOrden['cupon']['NOMBRE_AMIGABLE'] : ($dataPreOrden['cupon']['CODIGO'] ? $dataPreOrden['cupon']['CODIGO'] : '');
		} else { ////////validacion para los dos codigos de descuentos cupon y NC
			$auxDesc = '';

			foreach ($dataPreOrden['cupon'] as $key => $value) {
				foreach ($value as $key2 => $value2) {
					if ($key2 == 'TEXT_APP') {
						$auxDesc .= $value2 . ',';
					}
				}
			}

			$auxDesc = substr($auxDesc, 0, -1);
			$auxDesc = explode(',', $auxDesc);
			$dataDescOrd = '';
			if (strtoupper(substr($auxDesc[0], 0, 2)) == 'NC') {
				$descOrdenados['CUPON']		= $auxDesc[1];
				$descOrdenados['NC']		= $auxDesc[0];
			} else {
				$descOrdenados['CUPON']		= $auxDesc[0];
				$descOrdenados['NC']		= $auxDesc[1];
			}
			if (strtoupper(substr($auxDesc[0], 0, 2)) == 'NC' && strtoupper(substr($auxDesc[1], 0, 2)) == 'NC') {
				$descOrdenados['NC']		= $auxDesc[0];
				unset($descOrdenados['CUPON']);
			}
			if (strtoupper(substr($auxDesc[0], 0, 2)) != 'NC' && strtoupper(substr($auxDesc[1], 0, 2)) != 'NC') {
				$descOrdenados['CUPON']		= $auxDesc[0];
				unset($descOrdenados['NC']);
			}
			$descOrdenados = array_filter($descOrdenados);
			if (count($descOrdenados) >= 2) {
				$dataDescOrd = '2|' . $descOrdenados['CUPON'] . ',1|' . $descOrdenados['NC'];
			} else {
				if ($descOrdenados['CUPON']) {
					$dataDescOrd = '2|' . $descOrdenados['CUPON'];
				} else {
					$dataDescOrd .= '1|' . $descOrdenados['NC'];
				}
			}
		}

		$dataGenVoucher = [
			'cotiza_respuesta'              => 1,
			'vendedor'                      => (int) $id_user,
			'broker'                        => $id_broker,
			'action'                        => '',
			'idOrder'                       => '',
			'tipo_plan'                     => $dataPreOrden['id_plan_categoria'],
			'id_Preorden'                   => $dataPreOrden['id_preorden'],
			'user'                          => $id_user,
			'id_agencia'					=> $id_broker,
			'origen'                        => $dataPreOrden['origen'],
			'destino'                       => $dataPreOrden['destino'],
			'mindays'                       => $allData['bloque'] ? $allData['bloque'] : '',
			'viaje-fecha-inicio'            => $dataPreOrden['FechaSalida'],
			'viaje-fecha-fin'               => $dataPreOrden['FechaLlegada'],
			'fechas'                        => json_encode(['start' => $dataPreOrden['FechaSalida'], 'end' => $dataPreOrden['FechaLlegada']]),
			'llegada'                       => $dataPreOrden['FechaLlegada'],
			'salida'                        => $dataPreOrden['FechaSalida'],
			'intervalos'                    => $dataPreOrden['bloque'] ?: '',
			'cantidad'                      => $dataPreOrden['array_prices_app']['cntPrices'] ?: '',
			'edades'                        => implode(',', $dataPreOrden['edades']),
			'numpas'                        => count($dataPreOrden['edades']),
			'dias'                          => $dataPreOrden['dias'],
			'email_usado'                   => '',
			'accion_cotiza'                 => '',
			'id_categoria'                  => $dataPreOrden['id_plan_categoria'],
			'PlanSel'                       => $dataPreOrden['id_plan'],
			'selPlanId'                     => $dataPreOrden['id_plan'],
			'nombre_cliente'                => '',
			'email_cliente'                 => '',
			'x_codigo_final'                => $invoice,
			'x_id_orden'                    => $id_orden, ////id de la orden
			'x_id_benefi'                   => $ids_pasaj, ///ids de los benifiarios con ,
			'active_overage'                => 0,
			'array_' . $dataPreOrden['id_plan'] => json_encode($dataPreOrden['array_prices_app']),
			'tiepoid'                       => $dataPreOrden['array_prices_app']['tiepoid'],
			'n_riders'                      => count($dataPreOrden['upgrades']),
			'activofactor'                  => ($platfProcNuevo) ? (($dataPreOrden['array_prices_app']['activofactor']) == 'si' ? 'Y' : 'N') : (($dataPreOrden['array_prices_app']['activofactor']) == 'si' ? 'si' : 'no'),
			'family_plan'                   => ($dataPreOrden['array_prices_app']['planfamiliar'] > 0) ? 'Y' : 'N',
			'moneda_plan'                   => $dataPreOrden['array_prices_app']['moneda'],
			'tasa_cambio'                   => $dataPreOrden['array_prices_app']['tasa_cambio'],
			'moneda_local'                  => $dataPreOrden['array_prices_app']['moneda_local'],
			'overage_in_factor'             => 0,
			'valor_prima'                   => '',
			'pax_observacion'               => '',
			'nombrceC'                      => $dataPreOrden['contacto_emergencia']['nameE'],
			'EmailC'                        => $dataPreOrden['contacto_emergencia']['correoE'],
			'cod_telf_C'                    => $dataPreOrden['contacto_emergencia']['codigoTelE'],
			'id_R'                          => '',
			'descuento'                     => ($dataPreOrden['cupon']['TIPO_CALC'] == '%') ? ((((float) $allData['subTotal'] + (float) $allData['subTotalUpgrades']) * (float) $dataPreOrden['cupon']['VALUE_CUPON']) / 100) : (((((float) $allData['subTotal'] + (float) $allData['subTotalUpgrades']) - (float) $dataPreOrden['cupon']['VALUE_CUPON']) > 0) ?  (float) $dataPreOrden['cupon']['VALUE_CUPON'] : ((float) $allData['subTotal'] + (float) $allData['subTotalUpgrades'])),
			'cod_promocional'               => $descuentoAux,
			'descuentos'                    => !empty($dataDescOrd) ? $dataDescOrd : ((strtoupper(substr($descuentoAux, 0, 2)) == 'NC') ? "1|$descuentoAux" : "2|$descuentoAux"),
			'v_authorizado'                 => '',
			'x_respuesta_full'              => '',
			'x_contador_intentos'           => $attempt,
			'x_id_status'                   => '',
			'credit-cart-type'              => 1,
			'numero_tarjeta'                => $allData['TDC']['codigoTarjeta'] ?: '',
			'tipo_tarjeta'                  => $allData['TDC']['tipoTarjeta'] ?: '',
			'mes_vencimiento'               => (int) $allData['TDC']['mesTarjetaVen'] ?: '',
			'ano_vencimiento'               => (int) $allData['TDC']['yearTarjetaVen'] ?: '',
			'expiry-date'                   => (count($cardExpiry) > 2)  ? $cardExpiry : '',
			'cvv'                           => $allData['TDC']['CCV'] ?: '',
			'nombre_tarjeta'                => $allData['TDC']['nombreTarjeta'] ?: '',
			'apellido_tarjeta'              => $allData['TDC']['apellidoTarjeta'] ?: '',
			'credit-cart-type'              => 1,
			'paymentType'                   => $tipoPago,
			'pago_Preventa'                 => 'no',
			'condiciones'                   => 'on',
			'ac'                            => 'comprar',
			'plan_producto'                 => $dataPreOrden['id_plan'],
			'FechaSalida'                   => date('m/d/Y', strtotime($dataPreOrden['FechaSalida'])),
			'FechaLlegada'                  => date('m/d/Y', strtotime($dataPreOrden['FechaLlegada'])),
			'categoria'                     => $dataPreOrden['id_plan_categoria'],
			'cantidapasajero'               => count($dataPreOrden['edades']),
			'diaxpersona'                   => $dataPreOrden['dias'],
			'porcetajePFV'                  => ($platfProcNuevo) ? ($dataPreOrden['array_prices_app']['arrUsedPrices'][0]['pvpBase']) : ($dataPreOrden['array_prices_app']['valorMenor']),
			'porcetajePFC'                  => ($platfProcNuevo) ? ($dataPreOrden['array_prices_app']['arrUsedPrices'][0]['costBase']) : ($dataPreOrden['array_prices_app']['CostoMenor2']),
			'porcetajePFN'                  => ($platfProcNuevo) ? ($dataPreOrden['array_prices_app']['arrUsedPrices'][0]['net']) : ($dataPreOrden['array_prices_app']['netoMenor']),
			'pareja'                        => ($platfProcNuevo) ? (($dataPreOrden['array_prices_app']['planpareja'] > 0) ? 'Y' : 'N') : (($dataPreOrden['array_prices_app']['planpareja'] > 0) ? 1 : 'N'),
			'totalcosto'                    => $allData['subTotal'],
			'totalcostocost'                => $dataPreOrden['array_prices_app']['total_costo'],
			'totalcostoneta'                => $dataPreOrden['array_prices_app']['total_neto'],
			'telefono'                      => $dataPreOrden['contacto_emergencia']['codigoTelE'] . '-' . $dataPreOrden['contacto_emergencia']['TelefPE'],
			'dispositivo'                   => 'A',
			'id_broker'         			=> $id_broker,
			'broker_sesion'    				=> $id_broker,
			'selectLanguage'  				=> $lang_app,
			'id_user'           			=> $id_user,
			'user_type'         			=> $userType,
			'useEmailLink'                  => (!empty($allData['dataLinkPago']['correoLinkPago']) && $tipoPagoApp == 'SHIPPING_LINK') ? 1 : '', //parametros para el link de pago 
			'usePhoneLink'                  => (!empty($allData['dataLinkPago']['telefLinkPago']) && $tipoPagoApp == 'SHIPPING_LINK') ? 1 : '', //parametros para el link de pago 
			'email-link'                    => (!empty($allData['dataLinkPago']['correoLinkPago']) && $tipoPagoApp == 'SHIPPING_LINK') ? $allData['dataLinkPago']['correoLinkPago'] : '', //parametros para el link de pago 
			'phone-link-code'               => (!empty($allData['dataLinkPago']['codigoTelLinkPago']) && $tipoPagoApp == 'SHIPPING_LINK') ? $allData['dataLinkPago']['codigoTelLinkPago'] : '', //parametros para el link de pago 
			'phone-link'                    => (!empty($allData['dataLinkPago']['telefLinkPago']) && $tipoPagoApp == 'SHIPPING_LINK') ? $allData['dataLinkPago']['telefLinkPago'] : '', //parametros para el link de pago 
			'referencia'                    => $allData['referencia']
		];

		if ($dataPreOrden['array_prices_app']['dir_habit'] == 'Y') {
			$dataGenVoucher['hab_country']     = $allData['hab_country'];
			$dataGenVoucher['hab_state']       = $allData['hab_state'];
			$dataGenVoucher['hab_city']        = $allData['hab_city'];
			$dataGenVoucher['hab_postal']      = $allData['hab_postal'];
			$dataGenVoucher['hab_address']     = $allData['hab_address'];
			$dataGenVoucher['hab_address_alt'] = $allData['hab_address_alt'];
		}

		if (($dataPreOrden['cupon']['VALUE_CUPON'] == 100 && $dataPreOrden['cupon']['TIPO_CALC'] == '%') || ($dataPreOrden['cupon']['PAGO_CUPON'] == 'Si')) { //////validacion para cupon
			$dataGenVoucher['pagocupon'] = 'Si';
			$dataGenVoucher['pago_cupon'] = 'Si';
		} elseif (($dataPreOrden['cupon']['VALUE_CUPON'] >= ((float) $allData['subTotal'] + (float) $allData['subTotalUpgrades']) && $dataPreOrden['cupon']['TIPO_CALC'] == 'monto') || ($dataPreOrden['cupon']['PAGO_CUPON'] == 'Si')) {
			$dataGenVoucher['pagocupon'] = 'Si';
			$dataGenVoucher['pago_cupon'] = 'Si';
		} else {
			$dataGenVoucher['pagocupon'] = 'No';
			$dataGenVoucher['pago_cupon'] = 'No';
		}

		//////aqui genero la data de los pasajeros para ser guardados
		for ($i = 0; $i < count($dataPasajeros); $i++) {
			$dataGenVoucher['edades' . $i]				 = $dataPasajeros[$i]['edad'];
			$dataGenVoucher['nombre' . $i]				 = $dataPasajeros[$i]['nombre'];
			$dataGenVoucher['apellido' . $i]			 = $dataPasajeros[$i]['apellido'];
			$dataGenVoucher['edad' . $i]				 = $dataPasajeros[$i]['edad'];
			$dataGenVoucher['sexo' . $i]				 = strtolower($dataPasajeros[$i]['sexo']);
			$dataGenVoucher['nacionalidad' . $i]		 = $dataPasajeros[$i]['pais'];
			$dataGenVoucher['tipo_doc' . $i]    		 = $dataPasajeros[$i]['tipoDocumento'];
			$dataGenVoucher['numeropasa' . $i]  		 = $dataPasajeros[$i]['documento'];
			$dataGenVoucher['email' . $i]   			 = $dataPasajeros[$i]['email'];
			$dataGenVoucher['cod_telf_' . $i]   		 = $dataPasajeros[$i]['codigoTelfono'];
			$dataGenVoucher['telefonopasagero' . $i]   	 = $dataPasajeros[$i]['codigoTelfono'] . '-' . $dataPasajeros[$i]['telefono'];
			$dataGenVoucher['pax_condicion_' . $i] 		 = $dataPasajeros[$i]['condMed'] ? 'Y' : 'N';
			$dataGenVoucher['observacion_med' . $i]   	 = $dataPasajeros[$i]['condMed'];
			$dataGenVoucher['subtotalv' . $i]  		     = ($platfProcNuevo) ? ($dataPasajeros[$i]['subtotal']) : (($dataPreOrden['array_prices_app']['planfamiliar'] == 1) ? ($dataPreOrden['array_prices_app']['total'] / $dataPreOrden['array_prices_app']['numero_menores']) : ($dataPasajeros[$i]['subtotal'])); //////proceso y falidacion para que cuadren los montos netos y el total en la version sin calculos nuevos de las plataformas en plan familia
			$dataGenVoucher['subtotal' . $i] 		     = ($platfProcNuevo) ? ($dataPasajeros[$i]['subtotal']) : (($dataPreOrden['array_prices_app']['planfamiliar'] == 1) ? ($dataPreOrden['array_prices_app']['total'] / $dataPreOrden['array_prices_app']['numero_menores']) : ($dataPasajeros[$i]['subtotal'])); //////proceso y falidacion para que cuadren los montos netos y el total en la version sin calculos nuevos de las plataformas en plan familia
			$dataGenVoucher['costop' . $i]  		     = $dataPasajeros[$i]['costo'];
			$dataGenVoucher['netop' . $i] 		         = $dataPasajeros[$i]['neto'];
			$dataGenVoucher['valorplan' . $i]            = ($platfProcNuevo) ? ($dataPasajeros[$i]['subtotal']) : (($dataPreOrden['array_prices_app']['planfamiliar'] == 1) ? ($dataPreOrden['array_prices_app']['total'] / $dataPreOrden['array_prices_app']['numero_menores']) : ($dataPasajeros[$i]['subtotal'])); //////proceso y falidacion para que cuadren los montos netos y el total en la version sin calculos nuevos de las plataformas en plan familia
			$dataGenVoucher['valorplancost' . $i]        = $dataPasajeros[$i]['costo'];
			$dataGenVoucher['valorplanNeto' . $i]        = $dataPasajeros[$i]['neto'];
			if ($prefix == 'WM') {
				$dataGenVoucher['fechanaci' . $i]            = date('Y-m-d', strtotime($dataPasajeros[$i]['fechaNacimiento']));
			} else {
				$dataGenVoucher['fechanaci' . $i]            = date('m/d/Y', strtotime($dataPasajeros[$i]['fechaNacimiento']));
			}
		}

		////aqui se cargan la informacion de los raiders para realizar el guardado
		for ($i = 0; $i < count($dataPreOrden['upgrades']); $i++) {
			$dataGenVoucher['activoR' . $dataPreOrden['upgrades'][$i]['idUpgrade']] = 'YES';
			$dataGenVoucher['final_raider' . $dataPreOrden['upgrades'][$i]['idUpgrade']] = $dataPreOrden['upgrades'][$i]['monto_aplicado'];
			for ($a = 0; $a < count($dataPreOrden['upgrades'][$i]['pasajero']); $a++) {
				///////aqui comparo los pasajeros para cargar la data de cada pasajero para la data de raiders
				if ($dataPreOrden['upgrades'][$i]['pasajero'][$a] == $dataPasajeros[$a]['']) {
					$dataGenVoucher['nombre' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]]			= $dataPasajeros[$a]['nombre'];
					$dataGenVoucher['apellido' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 		= $dataPasajeros[$a]['apellido'];
					if ($prefix == 'WM') {
						$dataGenVoucher['fechanaci' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 		= date('Y-m-d', strtotime($dataPasajeros[$a]['fechaNacimiento']));
					} else {
						$dataGenVoucher['fechanaci' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 		= date('m/d/Y', strtotime($dataPasajeros[$a]['fechaNacimiento']));
					}
					$dataGenVoucher['edad' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 			= $dataPasajeros[$a]['edad'];
					$dataGenVoucher['sexo' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 			= strtolower($dataPasajeros[$a]['sexo']);
					$dataGenVoucher['nacionalidad' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 	= $dataPasajeros[$a]['pais'];
					$dataGenVoucher['tipo_doc' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 		= $dataPasajeros[$a]['tipoDocumento'];
					$dataGenVoucher['numeropasa' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 		= $dataPasajeros[$a]['documento'];
					$dataGenVoucher['email' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 			= $dataPasajeros[$a]['email'];
					$dataGenVoucher['cod_telf_' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 		= $dataPasajeros[$a]['codigoTelfono'];
					$dataGenVoucher['telefonopasagero' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] = $dataPasajeros[$a]['codigoTelfono'] . '-' . $dataPasajeros[$a]['telefono'];
					$dataGenVoucher['pax_condicion_' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 	= $dataPasajeros[$a]['condMed'] ? 'y' : 'n';
					$dataGenVoucher['observacion_med' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 	= $dataPasajeros[$a]['condMed'];
					$dataGenVoucher['subtotalv' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 		= ($platfProcNuevo) ? ($dataPasajeros[$i]['subtotal']) : (($dataPreOrden['array_prices_app']['planfamiliar'] == 1) ? ($dataPreOrden['array_prices_app']['total'] / $dataPreOrden['array_prices_app']['numero_menores']) : ($dataPasajeros[$i]['subtotal'])); //////proceso y falidacion para que cuadren los montos netos y el total en la version sin calculos nuevos de las plataformas en plan familia
					$dataGenVoucher['subtotal' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 		= ($platfProcNuevo) ? ($dataPasajeros[$i]['subtotal']) : (($dataPreOrden['array_prices_app']['planfamiliar'] == 1) ? ($dataPreOrden['array_prices_app']['total'] / $dataPreOrden['array_prices_app']['numero_menores']) : ($dataPasajeros[$i]['subtotal'])); //////proceso y falidacion para que cuadren los montos netos y el total en la version sin calculos nuevos de las plataformas en plan familia
					$dataGenVoucher['costop' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 			= $dataPasajeros[$a]['costo'];
					$dataGenVoucher['netop' . $dataPreOrden['upgrades'][$i]['pasajero'][$a]] 			= $dataPasajeros[$a]['neto'];
				}
				$dataGenVoucher['RaidersPax'] .= ',' . $dataPreOrden['upgrades'][$i]['idUpgrade'] . '|' . $dataPreOrden['upgrades'][$i]['pasajero'][$a];
			}
		}
		$dataGenVoucher['RaidersPax'] = '0' . $dataGenVoucher['RaidersPax'];

		///////validacion para enviar el parametro pagolinea en versiones vijas de cotizador
		if (in_array($prefix, ['TK', 'VY']) && $tipoPagoApp == 'PAY_CREDIT_CARD') {
			$dataGenVoucher['pagolinea'] = 'Si';
		}

		///////validacion para travelkit para epayco
		if ($prefix == 'TK' && $tipoPagoApp == 'PAY_CREDIT_CARD') {
			$allData['TDC']['pay_typeDoc'] ? $dataGenVoucher['pay_typeDoc'] = $allData['TDC']['pay_typeDoc'] : '';
			$allData['TDC']['pay_document'] ? $dataGenVoucher['pay_document'] = $allData['TDC']['pay_document'] : '';
			$allData['TDC']['pay_email'] ? $dataGenVoucher['pay_email'] = $allData['TDC']['pay_email'] : '';
			$allData['TDC']['pay_country'] ? $dataGenVoucher['pay_country'] = $allData['TDC']['pay_country'] : '';
			$allData['TDC']['pay_state'] ? $dataGenVoucher['pay_state'] = $allData['TDC']['pay_state'] : '';
			$allData['TDC']['pay_city'] ? $dataGenVoucher['pay_city'] = $allData['TDC']['pay_city'] : '';
			$allData['TDC']['pay_phoneCode'] ? $dataGenVoucher['pay_phoneCode'] = $allData['TDC']['pay_phoneCode'] : '';
			$allData['TDC']['pay_phoneNumbre'] ? $dataGenVoucher['pay_phoneNumbre'] = $allData['TDC']['pay_phoneNumbre'] : '';
		}

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkPlatf 	= $link . "/app/pages/quote.php";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$responseAddVoucher   = json_decode($this->curlGeneral($linkPlatf, http_build_query($dataGenVoucher), $headers), true);
		//return $responseAddVoucher;

		if (empty($responseAddVoucher)) {
			return $responseAddVoucher;
		}

		///valido si el cupon paga toda la emision
		if ($dataGenVoucher['pagocupon'] == 'Si') {
			$response['code_orden'] 		= $invoice;
			$response['preOrden']   		= json_decode($idPreOrden, true);
			$response['dataPreOrden'] 		= $dataPreOrden;
			$response['data_quote'] 		= $responseAddVoucher;
			$response['ID_ORDER']     	    = (int) $responseAddVoucher['id_orden'];
			$response['STATUS_EMISION'] 	= 'OK';
			return $response;
		}

		//valido si el credito de la agencia lo paga todo
		if ($tipoPagoApp == 'CREDIT_AGENCY') {
			$response['code_orden'] 		= $invoice;
			$response['preOrden']   		= json_decode($idPreOrden, true);
			$response['dataPreOrden'] 		= $dataPreOrden;
			$response['data_quote'] 		= $responseAddVoucher;
			$response['ID_ORDER']     	    = (int) $responseAddVoucher['id_orden'];
			$response['STATUS_EMISION'] 	= 'OK';
			return $response;
		}

		///valido  si se paga con paypal
		if ($tipoPagoApp == 'USE_PAYPAL') {
			$response['code_orden'] 		= $invoice;
			$response['preOrden']   		= json_decode($idPreOrden, true);
			$response['dataPreOrden'] 		= $dataPreOrden;
			$response['data_quote'] 		= $responseAddVoucher;
			$response['ID_ORDER']     	    = (int) $responseAddVoucher['id_orden'];
			$response['STATUS_EMISION'] 	= 'paypal';
			return $response;
		}

		///valido  si se paga con link de pago
		if ($tipoPagoApp == 'SHIPPING_LINK') {
			$response['code_orden'] 		= $invoice;
			$response['preOrden']   		= json_decode($idPreOrden, true);
			$response['dataPreOrden'] 		= $dataPreOrden;
			$response['data_quote'] 		= $responseAddVoucher;
			$response['ID_ORDER']     	    = (int) $responseAddVoucher['id_orden'];
			$response['STATUS_EMISION'] 	= 'OK';
			$response['link_pago_send'] 	= 'OK';
			return $response;
		}

		$dataCurl = [
			'type' 				=> 'payment',
			"numero_tarjeta" 	=> $cardNumber,
			"fecha_expiracion"	=> $cardExpiry,
			"cvv"				=> $cardCvv,
			"nombre"			=> $cardName,
			"apellido"			=> $cardLastname,
			"tipo_t"			=> $cardType,
			"orden"				=> (int) $responseAddVoucher['id_orden'],
			"pre_orden"			=> $preOrden,
			"voucher"			=> $invoice,
			"intento"			=> $attempt,
			'id_broker'         => $id_broker,
			'broker_sesion'    	=> $id_broker,
			'selectLanguage'  	=> $lang_app,
			'id_user'          	=> $id_user,
			'user_type'        	=> $userType
		];

		if (in_array($prefix, ['TK']) && $tipoPagoApp == 'PAY_CREDIT_CARD') { ////////// data para epayco
			$dataAdic = [
				'payCurrency' 		=> $dataPreOrden['array_prices_app']['moneda_paypal'] ?: 'USD',
				'tipoDoc'  			=> $allData['TDC']['pay_typeDoc'],
				'document' 			=> $allData['TDC']['pay_document'],
				'payEmail'    		=> $allData['TDC']['pay_email'],
				'payCountry'  		=> $allData['TDC']['pay_country'],
				'payState'    		=> $allData['TDC']['pay_state'],
				'payCity'     		=> $allData['TDC']['pay_city'],
				'PayCelular' 		=> $allData['TDC']['pay_phoneCode'] . $allData['TDC']['pay_phoneNumbre']
			];
			$dataCurl = array_merge($dataCurl, $dataAdic);
		}

		$link 						= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkPlatf 					= $link . "/app/pages/async_cotizador.php";
		$headers 					= "content-type: application/x-www-form-urlencoded";
		$response   				= json_decode($this->curlGeneral($linkPlatf, $dataCurl, $headers, 'GET'), true);
		$response['code_orden'] 	= $invoice;
		$response['preOrden']   	= json_decode($idPreOrden, true);
		$response['dataPreOrden'] 	= $dataPreOrden;
		$response['ID_ORDER']       = (int) $responseAddVoucher['id_orden'];
		$response['data_quote'] 	= $responseAddVoucher;

		if ($response['code'] == 0) {
			$response['STATUS_EMISION'] 	= 'ERROR';
			switch ($response['error']['code']) {
				case '27':
					///Se ha producido un error al procesar esta transacción.
					$response['error']['elem_app'] = 'PAY_CREDIT_CARD';
					break;

				case ('6' || 'E012'):
					///Se ha producido un error codigo de tarjeta
					$response['error']['elem_app'] = 'codigoTarjeta';
					break;

				case ('7' || 'E026'):
					///Se ha producido un error mes de vencimiento de tarjeta 
					$response['error']['elem_app'] = 'mesTarjetaVen';
					break;

				case '8':
					///Se ha producido un error tarjeta vencida caducada
					$response['error']['elem_app'] = 'mesTarjetaVen';
					break;

				case '11':
					///Se ha producido una transaccion duplicada
					$response['error']['elem_app'] = 'PAY_CREDIT_CARD';
					break;

				case '17':
					///Se ha producido una transaccion duplicada
					$response['error']['elem_app'] = 'codigoTarjeta';
					break;

				default:
					$response['error']['elem_app'] = 'codigoTarjeta';
					break;
			}
		}
		if ($response['code'] == 1) {
			$response['STATUS_EMISION'] 	= 'OK';
		}

		return $response;
	}

	public function postUpdateOrderPaypalApp()
	{
		$prefix   					= $this->data['prefix'];
		$lang_app_short 			= $this->funcLangAppShort($this->funcLangApp());
		$allData 					= array_merge($_GET, json_decode($_POST['dataEmisionApp'], true));
		$allData['resp_paypal_all'] = json_decode($_POST['resp_paypal'], true);
		$allData['id_trans_paypal'] = $allData['resp_paypal_all']['transaction_complete_paypal']['purchase_units'][0]['payments']['captures'][0]['id'];
		unset($allData['resp_paypal_all']['proccess_paypal']);
		$id_orden                   = $allData['dataQuote']['data_quote']['id_orden'];
		$email                      = $allData['dataQuote']['dataPreOrden']['pasajeros'][0]['email'];
		$id_broker 					= ($allData['agency'] != 'N/A' && !empty($allData['agency'])) ? $allData['agency'] : 118;
		$userType 	  				= $allData['userType'];
		$id_user	  				= !empty($allData['id_user']) ? $allData['id_user'] : 0;
		$payment_status             = (strtoupper($allData['resp_paypal_all']['details_paypal']['status'])  == "APPROVED" && strtoupper($allData['resp_paypal_all']['transaction_complete_paypal']['status']) == "COMPLETED") ? 'Completed' : '';
		$payer_id                   = $allData['resp_paypal_all']['details_paypal']['payer']['payer_id'];

		$dataValida			= [
			'9092'	=> $prefix
		];

		$this->validatEmpty($dataValida);

		$dataCurl      = [
			"type"                          => 'Update_order_payment',
			'id_broker'         			=> $id_broker,
			'broker_sesion'    				=> $id_broker,
			'selectLanguage'  				=> $lang_app_short,
			'id_user'           			=> $id_user,
			'user_type'         			=> $userType,
			'invoice'                       => $id_orden,
			'custom'                        => $email,
			'payment_status'                => $payment_status,
			'verify_sign'                   => $allData['id_trans_paypal'],
			'payer_id'                      => $payer_id,
			'resp_paypal_all'               => $allData['resp_paypal_all']
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkEmail 	= $link . "/app/pages/async_cotizador.php";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$response = $this->curlGeneral($linkEmail, http_build_query($dataCurl), $headers);
		return [
			'response_platf'     =>   $response,
			'data_enviada'       =>   $dataCurl,
			'status'             =>   'OK'
		];
	}

	public function postUpdateOrden()
	{
		$allData = array_merge($_GET, $_POST);
		$prefix  = $allData['prefix'];
		$idOrden = $allData['idOrden'];
		$statusNew = (string) $allData['statusNew'];
		$id_broker = ($allData['agency'] != 'N/A' && !empty($allData['agency'])) ? $allData['agency'] : 118;
		$lang_app  = $this->funcLangAppShort($this->funcLangApp());
		$userType  = $allData['userType'];
		$cupon     = $allData['cupon'];
		$id_user   = !empty($allData['id_user']) ? $allData['id_user'] : 0;
		$versionPhp = $this->generalPhpInfo($prefix);

		$dataValida			= [
			'9092'	=> $prefix,
			'50033' => $idOrden,
			'9065'  => $statusNew
		];

		$this->validatEmpty($dataValida);

		$query = "SELECT * FROM orders where prefijo = '{$prefix}' AND id = {$idOrden}";

		$dataOrden = $this->selectDynamic('', '', '', '', $query)[0];

		if (empty($dataOrden)) {
			return $dataOrden;
		}

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$headers 	= "content-type: application/x-www-form-urlencoded";

		if ((float)$versionPhp['Core']['PHP Version'] > 7) {
			$dataStatus = [
				'status' 			=> $statusNew == 'NC' ? '5' : $statusNew,
				'id' 				=> $idOrden,
				'voucher' 			=> $dataOrden['codigo'],
				'total' 			=> $dataOrden['total'],
				'notecredit' 		=> $statusNew == 'NC' ? 'Y' : 'N',
				'type' 				=> 'change_status',
				'broker_sesion'    	=> $id_broker,
				'selectLanguage'  	=> $lang_app,
				'id_user'           => $id_user,
				'user_type'         => $userType,
			];

			$linkCurl 	= $link . "/app/admin/async_manage_orders.php";
			$curlStatus  = strip_tags(preg_replace("/[\r\n|\n|\r|\t|\t\n|\n\n|\R]+/", PHP_EOL, $this->curlGeneral($linkCurl, http_build_query($dataStatus), $headers)));
			return ['data' => $curlStatus, "status" => 'OK'];
		} else {

			//curl para la plataforma en el envio de correo
			$dataAddCurlEmail = [
				'broker_sesion'    		=> $id_broker,
				'selectLanguage'  		=> $lang_app,
				'id_user'           	=> $id_user,
				'user_type'         	=> $userType,
				'act'                   => 'update_order',
				'idorder'               => $dataOrden['id'],
				'id_order'              => $dataOrden['id'],
				'Anulado_Por_Admin'     => $userType == 13 ? 1 : 0, /////si es usuario master 13
				'id_emision_type'       => $dataOrden['id_emision_type'],
				'id_group'		        => $dataOrden['id_group'],
				'codigo'				=> $dataOrden['codigo'],
				'id_status'             => $statusNew == 'NC' ? '0' : $statusNew,
				'cupon'                 => $dataOrden['cupon'],
				'nombre_contacto'		=> $dataOrden['nombre_contacto'],
				'telefono_contacto'		=> $dataOrden['telefono_contacto'],
				'email_contacto'		=> $dataOrden['email_contacto'],
				'origin'				=> $dataOrden['origen'],
				'destino'				=> $dataOrden['destino'],
				'territory'				=> $dataOrden['territory'],
				'id_broker'				=> $dataOrden['agencia'],
				'idplan'				=> $dataOrden['producto'],
				'idorder'				=> $dataOrden['id'],
				'precio'				=> $dataOrden['total'],
				'nombre_agencia'		=> $dataOrden['nombre_agencia'],
				'comentarios'			=> $dataOrden['comentarios'],
				'vendedor'				=> $dataOrden['vendedor'],
				'agente_vendedor'		=> $dataOrden['vendedor'],
				'comentario_interno'	=> $dataOrden['comentario_interno'],
				'name_broker'			=> $dataOrden['nombre_agencia'],
				'comentariolog'			=> 'Update status voucher ' . $dataOrden['codigo'],
				'cupon'                 => $cupon
			];

			if (!empty($cupon)) {
				$queryCupon = [
					'broker_sesion'    		=> $id_broker,
					'selectLanguage'  		=> $lang_app,
					'id_user'           	=> $id_user,
					'user_type'         	=> $userType,
					"querys"  =>  "SELECT
									*
								FROM
									coupons
								WHERE
									(
										codigo = '$cupon'
										OR codigo_secundario = '$cupon'
									)
								AND id_status = '1'
								AND NOW() BETWEEN DATE(fecha_desde)
								AND DATE(fecha_hasta)
								AND ussage > 0"
				];
				$linkCurl 	= $link . "/app/api/selectDynamic";
				$dataCupon   = json_decode($this->curlGeneral($linkCurl, json_encode($queryCupon), $headers), true)[0];

				if (empty($dataCupon)) {
					switch ($lang_app) {
						case 'es':
							return [['notes' => 'Cupón Ingresado Inválido']];
							break;

						case 'pr':
							return [['notes' => 'Cupom inserido inválido']];
							break;

						case 'en':
							return [['notes' => 'Coupon Entered Invalid']];
							break;

						default:
							return [['notes' => 'Cupon Ingresado Inválido']];
							break;
					}
				}
			}

			//return $dataCurlEmail = $dataAddCurlEmail;
			$linkCurl 	= $link . "/app/admin/async_report_man_serv.php";
			$curlEmail   = $this->curlGeneral($linkCurl, http_build_query($dataAddCurlEmail), $headers);

			return ['data' => $curlEmail, "status" => 'OK'];
		}
	}

	public function sendVouchEmail()
	{
		$id_orden = $this->data['id_orden'];
		$email    = $this->data['email'];
		$prefix   = $this->data['prefix'];
		$lang_app = "es";
		$lang_app = $this->funcLangAppShort($this->funcLangApp());

		$dataValida			= [
			"40098" => $id_orden,
			'4004'	=> $email,
			'9092'	=> $prefix
		];

		$this->validatEmpty($dataValida);

		$dataEmail      = [
			"id_orden" 		 => $id_orden,
			"email"    		 => $email,
			'selectLanguage' => $lang_app
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkEmail 	= $link . "/app/reports/email_compra.php";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$response = $this->curlGeneral($linkEmail, http_build_query($dataEmail), $headers);
		return strip_tags($response);
	}

	public function loginIls()
	{
		$user 		= $this->data['user'];
		$password 	= $this->data['password'];
		$dataValida			= [
			'6037'	=> !(empty($user) and empty($password)),
			'6040'	=> $user,
			'6041'	=> $password
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if (!empty($validatEmpty)) {
			return $validatEmpty;
		}
		$data				= [
			"user_type",
			"firstname",
			"lastname",
			"id",
			"language_id"
		];
		$userExist	= $this->selectDynamic('', 'users', "users='$user'", $data);
		if ($userExist) {
			$userActive	= $this->selectDynamic(['id_status' => '1'], 'users', "users='$user' AND user_type IN (1,2,30)", $data);
			if ($userActive) {
				$passwordEncript 	= $this->encriptKey($password);
				$dataUser			= $this->selectDynamic(['users' => $user, 'id_status' => '1'], 'users', " user_type IN (1,2,30) AND password='$passwordEncript'", $data);
				if ($dataUser) {

					switch ($dataUser[0]["language_id"]) {
						case '1':
							$dataUser[0]["language_id"] = 'eng';
							break;
						case '2':
							$dataUser[0]["language_id"] = 'spa';
							break;
						default:
							$dataUser[0]["language_id"] = 'spa';
							break;
					}

					return [
						'status'   		=> 'OK',
						'userType' 		=> $dataUser[0]["user_type"],
						'id_user'  		=> $dataUser[0]["id"],
						'firstname'	 	=> $dataUser[0]["firstname"],
						'lastname' 		=> $dataUser[0]["lastname"],
						'langApp'	    => $dataUser[0]["language_id"]
					];
				} else {
					return $this->getError(9090);
				}
			} else {
				return $this->getError(50004);
			}
		} else {
			return $this->getError(9088);
		}
	}

	public function login()
	{
		$user 		= $this->data['user'];
		$password 	= $this->data['password'];
		$prefix 	= $this->data['prefix'];
		$dataValida			= [
			'6037'	=> !(empty($user) and empty($password)),
			'6040'	=> $user,
			'6041'	=> $password,
			'9092'	=> $prefix
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if (!empty($validatEmpty)) {
			return $validatEmpty;
		}

		$data				= [
			"user_type",
			"language_id",
			"prefijo",
			"firstname",
			"lastname",
			"id",
			"email",
			"code_phone",
			"phone",
			"(
				SELECT
					user_associate.id_associate
				FROM
					user_associate
				WHERE
					user_associate.id_user = users_extern.id
				AND user_associate.prefijo = '$prefix'
				ORDER BY
					user_associate.id_user_associate DESC
				LIMIT 1
			) AS agency",
			"(
				SELECT
					countries.description
				FROM
					countries
				WHERE
					countries.iso_country = users_extern.id_country
				LIMIT 1
			) AS pais",
			"(
				SELECT
					broker_parameters.prefijo_ref
				FROM
					broker_parameters
				WHERE
					agency = broker_parameters.id_broker
				AND broker_parameters.prefijo = '$prefix'
				LIMIT 1
			) AS prefAgency",
			"	(
				SELECT
					broker.broker
				FROM
					broker
				WHERE
					broker.id_broker = agency
				AND broker.prefijo = '$prefix'
				LIMIT 1
			) as nombreAgenMaster",
			"(
				SELECT
					clients.client
				FROM
					clients
				WHERE
					clients.prefix = '$prefix'
				LIMIT 1
			) AS nomPlatf",
			"(
				SELECT
					clients.img_cliente
				FROM
					clients
				WHERE
					clients.prefix = '$prefix'
				LIMIT 1
			) AS imgPlatf",
			"(
				SELECT
					clients.colors_platform
				FROM
					clients
				WHERE
					clients.prefix = '$prefix'
				LIMIT 1
			) AS colorsPlatf"
		];

		$userExist	= $this->selectDynamic('', 'users_extern', " users='$user' AND prefijo = '$prefix' ", $data, '', '', ['field' => 'id', 'order' => 'desc']);
		if (!empty($userExist)) {
			$prefijExist	= $this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['prefix']);
			if (!empty($prefijExist)) {
				$userActive	= $this->selectDynamic('', '', '', '', "SELECT
																		user_type,
																		language_id,
																		prefijo,
																		firstname,
																		lastname,
																		id
																	FROM
																		users_extern
																	WHERE
																		users = '$user'
																	AND id_status = '1'
																	AND user_type IN ('1', '2', '5', '13')
																	AND prefijo = '$prefix' 
																	ORDER BY
																		id DESC", '', '', '', '');
				if (!empty($userActive)) {
					if ($prefix == 'CT' || $prefix == 'CE') {
						$dataUser			= $this->selectDynamic(['users' => $user, 'id_status' => '1', 'prefijo' => $prefix], 'users_extern', "password='$password'", $data, '', '', ['field' => 'id', 'order' => 'desc'], '');
					} else {
						$passwordEncript 	= $this->encriptKey($password);
						$dataUser			= $this->selectDynamic(['users' => $user, 'id_status' => '1', 'prefijo' => $prefix], 'users_extern', "password='$passwordEncript'", $data, '', '', ['field' => 'id', 'order' => 'desc'], '', '');
					}
					if (!empty($dataUser)) {
						$dataBasicMaster = $this->agencysParent($prefix, $dataUser[0]['agency']);
						$versionPhp = $this->generalPhpInfo($prefix);

						switch ($dataUser[0]["language_id"]) {
							case ($dataUser[0]["language_id"] == 'eng' || $dataUser[0]["language_id"] == 'en'):
								$dataUser[0]["language_id"] = 'eng';
								break;
							case ($dataUser[0]["language_id"] == 'spa' || $dataUser[0]["language_id"] == 'es'):
								$dataUser[0]["language_id"] = 'spa';
								break;
							case ($dataUser[0]["language_id"] == 'por' || $dataUser[0]["language_id"] == 'pt'):
								$dataUser[0]["language_id"] = 'por';
								break;
							default:
								$dataUser[0]["language_id"] = 'spa';
								break;
						}

						$pref_master = $this->selectDynamic('', '', '', '', "SELECT
														broker_parameters.prefijo_ref
														FROM
															broker_nivel
														INNER JOIN broker_parameters ON broker_parameters.id_broker = broker_nivel.`master`
														AND broker_parameters.prefijo = broker_nivel.prefijo
														WHERE
															broker_nivel.prefijo = '$prefix'
														AND broker_nivel.id_broker = '{$dataBasicMaster["id_broker"]}'
														ORDER BY
															id_broker_nivel DESC
														LIMIT 1", '', '', '')[0]['prefijo_ref'];

						$response = [
							'status'   		=> 'OK',
							'userType' 		=> $dataUser[0]["user_type"],
							'prefijo'  		=> $dataUser[0]["prefijo"],
							'nomPlatf'  	=> $dataUser[0]['nomPlatf'],
							'imgPlatf'  	=> $dataUser[0]['imgPlatf'],
							'colorsPlatf'   => $dataUser[0]['colorsPlatf'],
							'id_user'  		=> $dataUser[0]["id"],
							'firstname' 	=> $dataUser[0]["firstname"],
							'lastname' 		=> $dataUser[0]["lastname"],
							'email'     	=> $dataUser[0]["email"],
							'code_phone'    => $dataUser[0]["code_phone"],
							'phone'     	=> $dataUser[0]["phone"],
							'agency'    	=> $dataUser[0]["agency"] ?: 'N/A',
							'nivelAgency'   => (in_array($dataUser[0]["user_type"], [1, 13])) ? 'N/A' : $this->getAgencyMaster($prefix, $dataBasicMaster["id_broker"])[0]['nivel'],
							'agencyMaster'  => $dataBasicMaster['master'] == 'Y' ? $dataBasicMaster['id_broker'] : 'N/A',
							'pais'      	=> $dataUser[0]["pais"],
							'prefAgency'    => !empty($pref_master) ? $pref_master : 'N/A',
							'nombreAgenMaster' => !empty($dataBasicMaster['name_broker']) ? $dataBasicMaster['name_broker'] : 'N/A',
							'urlPlatform' 	=> $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']),
							'paramAgency' 	=> ($dataBasicMaster['master'] == 'Y') ? $this->selectDynamic('', '', '', '', "SELECT * FROM broker_parameters WHERE id_broker = '{$dataBasicMaster['id_broker']}' AND prefijo = '$prefix' ORDER BY id_broker_parameters DESC limit 1", '', '', '', '')[0] : 'N/A',
							'langApp'       => $dataUser[0]["language_id"],
							'platfNew'      => (((float)$versionPhp['Core']['PHP Version'] > 7) ? 'Y' : 'N')
						];

						if (empty($response['paramAgency']['color_fuente'])) {
							$response['paramAgency']['color_fuente'] = '#FFFFFF';
						}
						if (!empty($response['paramAgency']['dominio'])) {
							$response['paramAgency']['dominio'] = $this->baseURL($response['paramAgency']['dominio']);
						}
						if (!is_array($response['paramAgency'])) {
							$response['paramAgency'] = 'N/A';
						}
						return $response;
					} else {
						return $this->getError(9090);
					}
				} else {
					return $this->getError(50004);
				}
			} else {
				return $this->getError(9093);
			}
		} else {
			return $this->getError(9088);
		}
	}

	public function getoken()
	{
		$ArrayValida = array('6040' => $this->data['user'], '6041' => $this->data['pass']);
		$this->validatEmpty($ArrayValida);
		return $this->getApiKey($this->data['user'], $this->data['pass']);
	}

	public function addOrder()
	{
		$quoteGeneral 					= new quote_general_new();
		$api	      					= $this->api;
		$departure   					= $this->data['fecha_salida'];
		$arrival     					= $this->data['fecha_llegada'];
		$reference   					= $this->data['referencia'];
		$plan        					= $this->data['id_plan'];
		$destination 					= $this->data['pais_destino'];
		$origin      					= $this->data['pais_origen'];
		$coin        					= $this->data['moneda'];
		$numberPassengers   			= $this->data['pasajeros'];
		$language    					= $this->data['lenguaje'];
		$exchangeRate					= $this->data['tasa_cambio'];
		$nameContact					= $this->data['nombre_contacto'];
		$phoneContact					= $this->data['telefono_contacto'];
		$emailContact					= $this->data['email_contacto'];
		$generalConsiderations			= $this->data['consideraciones_generales'];
		$issue       					= $this->data['emision'];
		$code							= '';
		$upgrade						= $this->data['upgrade'];
		$documentPassenger 				= $this->data['documentos'];
		$birthDayPassenger				= $this->data['nacimientos'];
		$lastNamePassenger				= $this->data['apellidos'];
		$emailPassenger					= $this->data['correos'];
		$namePassenger					= $this->data['nombres'];
		$phonePassenger					= $this->data['telefonos'];
		$medicalConditionsPassenger		= $this->data['observaciones_medicas'];
		$dataValida			= [
			'6037'	=> !(empty($departure) and empty($arrival) and empty($plan) and empty($destination) and empty($origin) and  empty($coin) and empty($exchangeRate) and empty($numberPassengers) and empty($birthDayPassenger) and  empty($documentPassenger) and  empty($namePassenger) and empty($lastNamePassenger) and empty($phonePassenger) and empty($emailPassenger) and empty($medicalConditionsPassenger) and empty($nameContact) and empty($phoneContact) and empty($emailContact) and empty($lenguaje) and empty($upgrade)),
			'6029'	=> $departure,
			'6030'	=> $arrival,
			'6022'	=> $plan,
			'6028'	=> $destination,
			'6027'	=> $origin,
			'6034'	=> $coin,
			"6026"	=> $numberPassengers,
			'6021'	=> $language,
			'6036'	=> $nameContact,
			'4009'	=> $phoneContact,
			'4004'	=> $emailContact,
			'6035'	=> $issue,
			'4004'	=> (!$this->verifyMail($emailContact)) ? 0 : 1,
			'5010'	=> (!is_numeric($phoneContact)) ? 0 : 1,
			'4029'	=> (empty($numberPassengers) or $numberPassengers == 0 or !is_numeric($numberPassengers)) ? 0 : 1,
			'1080'	=> ($destination == "1" or $destination == "2" or $destination == "9") ? 1 : 0,
			'9011'	=> ($exchangeRate <= 0 or is_float($exchangeRate)) ? 0 : 1,
			'9012'	=> ($issue < 1 || !is_numeric($issue) || $issue > 3) ? 0 : 1,
			'1022'	=> (!$this->selectDynamic('', 'currency', "value_iso='$coin'", array("desc_small"))) ? 0 : 1,
			'1030'	=> $this->validLanguage($language),
			'9049'	=> ($this->countData($namePassenger, $numberPassengers)) ? 0 : 1,
			'9053'	=> ($this->countData($lastNamePassenger, $numberPassengers)) ? 0 : 1,
			'9051'	=> ($this->countData($birthDayPassenger, $numberPassengers)) ? 0 : 1,
			'9050'	=> ($this->countData($documentPassenger, $numberPassengers)) ? 0 : 1,
			'9052'	=> ($this->countData($emailPassenger, $numberPassengers)) ? 0 : 1,
			'9054'	=> ($this->countData($phonePassenger, $numberPassengers)) ? 0 : 1,
			'9055'	=> ($this->countData($medicalConditionsPassenger, $numberPassengers)) ? 0 : 1,
			'2001'	=> $this->checkDates($departure),
			'2002'	=> $this->checkDates($arrival),
			'9059'	=> $this->verifyOrigin($origin),
			'9060'	=> (!preg_match('(^[a-zA-Z ]*$)', $nameContact)) ? 0 : 1
		];
		$validatEmpty			= $this->validatEmpty($dataValida);
		if ($validatEmpty) {
			return $validatEmpty;
		}
		$validateDataPassenger	= $this->validateDataPassenger($numberPassengers, $namePassenger, $lastNamePassenger, $birthDayPassenger, $documentPassenger, $emailPassenger, $phonePassenger, $medicalConditionsPassenger);
		if ($validateDataPassenger) {
			return $validateDataPassenger;
		}
		$dataPlan			= $this->selectDynamic('', 'plans', "id='$plan'", array("id_plan_categoria", "name", "num_pas"));
		$datAgency			= $this->datAgency($api);
		$idCategoryPlan 	= $dataPlan[0]['id_plan_categoria'];
		$namePlan			= $dataPlan[0]['name'];
		$idAgency			= $datAgency[0]['id_broker'];
		$isoCountry			= $datAgency[0]['id_country'];
		$nameAgency			= $datAgency[0]['broker'];
		$userAgency			= $datAgency[0]['user_id'];
		$cantPassengerPlan	= $dataPlan[0]['num_pas'];
		$prefix				= ($datAgency[0]['prefijo']) ? $datAgency[0]['prefijo'] : 'IX';
		$arrivalTrans		= $this->transformerDate($arrival);
		$departureTrans		= $this->transformerDate($departure);
		$daysByPeople 		= $this->betweenDates($departureTrans, $arrivalTrans);
		$validateDateOrder	= $this->validateDateOrder($arrivalTrans, $departureTrans, $isoCountry);
		if ($validateDateOrder) {
			return $validateDateOrder;
		}
		$validatePlans		= $this->validatePlans($plan, $idAgency, $origin, $destination, $daysByPeople);
		if ($validatePlans) {
			return $validatePlans;
		}
		$agesPassenger		= $this->setAges($birthDayPassenger, $isoCountry);
		$countryAgency		= $this->getCountryAgency($api);
		$dataQuoteGeneral	= $quoteGeneral->quotePlanbenefis($idCategoryPlan, $daysByPeople, $countryAgency, $destination, $origin, $agesPassenger, $departure, $arrival, $idAgency, $plan);
		$validatBenefits	= $this->verifyBenefits($dataQuoteGeneral);
		if ($validatBenefits) {
			return $validatBenefits;
		}
		$cost							= $dataQuoteGeneral[0]['total_costo'];
		$price							= $dataQuoteGeneral[0]['total'];
		$familyPlan						= $dataQuoteGeneral[0]['family_plan'];
		if ($dataQuoteGeneral[0]['banda'] == "si") {
			for ($i = 0; $i < $dataQuoteGeneral[0]["total_rangos"]; $i++) {
				$pricePassenger[] 		= $price / $numberPassengers;
				$costPassenger[]		= $dataQuoteGeneral[0]["costo_banda$i"];
			}
		} else {
			if ($dataQuoteGeneral[0]['numero_menores'] > 0) {
				for ($i = 0; $i < $dataQuoteGeneral[0]['numero_menores']; $i++) {
					$pricePassenger[] 	= $dataQuoteGeneral[0]['valorMenor'];
					$costPassenger[] 	= $dataQuoteGeneral[0]['costoMenor'];
				}
			}
			if ($dataQuoteGeneral[0]['numero_mayores'] > 0) {
				for ($i = 0; $i < $dataQuoteGeneral[0]['numero_mayores']; $i++) {
					$pricePassenger[] 	= $dataQuoteGeneral[0]['valorMayor'];
					$costPassenger[] 	= $dataQuoteGeneral[0]['costoMayor'];
				}
			}
		}
		for ($i = 0; $i < $numberPassengers; $i++) {
			$birthDayPassengerTrans[]	= $this->transformerDate($birthDayPassenger[$i]);
		}
		$verifiedOrderDuplicate 		= $this->verifiedOrderDuplicate($departureTrans, $arrivalTrans, $origin, $destination);
		if (!empty($verifiedOrderDuplicate)) {
			$Verified_Beneficiaries		= $this->verifiedBeneficiariesDuplicate($verifiedOrderDuplicate, $documentPassenger, $birthDayPassengerTrans);
			if ($Verified_Beneficiaries) {
				return $Verified_Beneficiaries;
			}
		}
		$code			= $prefix . '-' . $this->valueRandom(6);
		$exchangeRate	= (empty($exchangeRate)) ? 0 : $exchangeRate;
		$language		= ($language == "spa") ? "es" : "en";
		if (!empty($upgrade)) {
			$data	= [
				"api"				=> $api,
				"upgrades"			=> $upgrade,
				"codigo"			=> $code,
				"plan"				=> $plan,
				"daybypeople"		=> $daysByPeople,
				"price"				=> $price,
				"cost"				=> $cost,
				"numberPassengers"	=> $numberPassengers,
				"source"			=> false,
				'beneficiaries'		=> $documentPassenger,
				"precio_vta"		=> $pricePassenger,
				"precio_cost"		=> $costPassenger
			];
			$dataUpgrade			= $this->addUpgrades($data, false);
			if (count($dataUpgrade["id"]) == 0) {
				return $dataUpgrade;
			} else {
				$price		= $dataUpgrade["price"];
				$cost		= $dataUpgrade["cost"];
				$idUpgrade 	= $dataUpgrade["id"];
			}
		}
		$data	= [
			'codigo'				=> $code,
			'salida'				=> $departureTrans,
			'retorno'				=> $arrivalTrans,
			'referencia'			=> $reference,
			'producto'				=> $plan,
			'destino'				=> $destination,
			'origen'				=> strtoupper($origin),
			'nombre_contacto'		=> $nameContact,
			'telefono_contacto'		=> $phoneContact,
			'agencia'				=> $idAgency,
			'nombre_agencia'		=> $nameAgency,
			'vendedor'				=> $userAgency,
			'programaplan'			=> $idCategoryPlan,
			'family_plan'			=> $familyPlan,
			'fecha'					=> 'now()',
			'cantidad'				=> $numberPassengers,
			'status'				=> '1',
			'origin_ip'				=> $_SERVER['REMOTE_ADDR'],
			'email_contacto'		=> $emailContact,
			'comentarios'			=> $generalConsiderations,
			'total'					=> $price,
			'tiempo_x_producto'		=> $daysByPeople,
			'neto_prov'				=> $cost,
			'comentario_medicas'	=> $generalConsiderations,
			'id_emision_type'		=> '2',
			'validez'				=> '1',
			'hora'					=> 'now()',
			'tasa_cambio'			=> $exchangeRate,
			'alter_cur'				=> $coin,
			'territory'				=> $destination,
			'total_mlc'				=> $price / $exchangeRate,
			'neto_prov_mlc'			=> $cost / $exchangeRate,
			'total_tax'				=> $dataQuoteGeneral[0]['total_tax1'],
			'total_tax_mlc'			=> $dataQuoteGeneral[0]['total_tax1'] / $exchangeRate,
			'lang'					=> $language,
			'procedencia_funcion'	=> '0'
		];
		$idOrden	= $this->insertDynamic($data, 'orders');
		for ($i = 0; $i < $numberPassengers; $i++) {
			$addBeneficiaries[$i]	= $this->addBeneficiares($documentPassenger[$i], $birthDayPassengerTrans[$i], $namePassenger[$i], $lastNamePassenger[$i], $phonePassenger[$i], $emailPassenger[$i], $idOrden, '1', $pricePassenger[$i], $costPassenger[$i], $medicalConditionsPassenger[$i], $pricePassenger[$i] / $exchangeRate, $costPassenger[$i] / $exchangeRate, 0, 0);
		}
		if (!empty($addBeneficiaries) && !empty($idOrden)) {
			$this->addCommission($idAgency, $idCategoryPlan, $price, $idOrden);
			if (count($idUpgrade) > 0) {
				foreach ($idUpgrade as $value) {
					$this->updateDynamic('orders_raider', 'id', $value, ['id_orden' => $idOrden]);
				}
			}
			$link = LINK_REPORTE_VENTAS . $code . "&selectLanguage=$language&broker_sesion=$idAgency";
			switch ($issue) {
				case '1':
					return [
						"status"		=> "OK",
						"codigo"		=> $code,
						"valor"			=> $price,
						"ruta"			=> $link,
						"documento"		=> implode(",", $documentPassenger),
						"referencia"	=> $reference
					];
					break;
				case '2':
					$this->Enviar_orden($emailPassenger[0], $idOrden, $language, $language);
					return [
						"status"		=> "OK",
						"codigo"		=> $code,
						"valor"			=> $price,
						"referencia"	=> $reference
					];
					break;
				default:
					$this->Enviar_orden($emailPassenger[0], $idOrden, $language, $language);
					return [
						"status"		=> "OK",
						"codigo"		=> $code,
						"documento"		=> implode(",", $documentPassenger),
						"referencia"	=> $reference
					];
					break;
			}
		}
	}
	public function reportOrder()
	{
		$quoteGeneral = new quote_general_new();
		$apikey      				= $this->data['apikey'];
		$code        				= $this->data['code'];
		$departure   				= $this->data['departure'];
		$arrival     				= $this->data['arrival'];
		$reference   				= $this->data['reference'];
		$plan        				= $this->data['plan'];
		$destination 				= $this->data['destination'];
		$origin      				= $this->data['origin'];
		$coin        				= $this->data['coin'];
		$numberPassengers   		= $this->data['numberpassengers'];
		$language    				= $this->data['language'];
		$price       				= $this->data['price'];
		$exchangeRate				= $this->data['exchangerate'];
		$namePassenger				= $this->data['namepassenger'];
		$lastNamePassenger			= $this->data['lastnamepassenger'];
		$birthDayPassenger  		= $this->data['birthdaypassenger'];
		$documentPassenger  		= $this->data['documentpassenger'];
		$emailPassenger				= $this->data['emailpassenger'];
		$medicalConditionsPassenger	= $this->data['medicalConditionsPassenger'];
		$phonePassenger				= $this->data['phonepassenger'];
		$nameContact				= $this->data['namecontact'];
		$phoneContact				= $this->data['phonecontact'];
		$emailContact				= $this->data['emailcontact'];
		$issue						= $this->data['issue'];
		$generalConsiderations		= $this->data['generalconsiderations'];
		$dataValida = array(
			'6020' => $apikey,
			'6048' => $code,
			'6029' => $departure,
			'6030' => $arrival,
			'6022' => $plan,
			'6028' => $destination,
			'6027' => $origin,
			'6034' => $coin,
			"6026" => $numberPassengers,
			'6021' => $language,
			'4006' => $namePassenger,
			'4007' => $lastNamePassenger,
			'5005' => $birthDayPassenger,
			'4006' => $documentPassenger,
			'5012' => $emailPassenger,
			'4008' => $phonePassenger,
			'5006' => $medicalConditionsPassenger,
			'6036' => $nameContact,
			'4009' => $phoneContact,
			'4004' => $emailContact,
			'1005' => ($this->checkapiKey($apikey)) ? 1 : 0,
			'4004' => (!$this->verifyMail($emailContact)) ? 0 : 1,
			'4010' => (!$this->verifyMail($emailPassenger)) ? 0 : 1,
			'5010' => (!is_numeric($phoneContact)) ? 0 : 1,
			'5008' => ($issue < 1 || $issue > 3 || !is_numeric($issue)) ? 0 : 1,
			'3150' => (empty($numberPassengers) or $numberPassengers == 0 or !is_numeric($numberPassengers)) ? 0 : 1,
			'6052' => ($price < 0 || !is_numeric($price)) ? 0 : 1,
			'9011' => ($exchangeRate <= 0 or is_float($exchangeRate)) ? 0 : 1,
			'6049' => (strlen($code) > 30) ? 0 : 1,
			'1022' => (!$this->selectDynamic('', 'currency', "value_iso='$coin'", array("desc_small"))) ? 0 : 1,
			'1030' => (!$this->selectDynamic('active = 1', 'languages', "lg_id='$language'", array("id"))) ? 0 : 1,
			'6054' => ($this->selectDynamic('', 'orders', "codigo='$code'", array("codigo"))) ? 0 : 1,
			'4005' => ($this->countData($namePassenger, $numberPassengers)) ? 0 : 1,
			'4007' => ($this->countData($lastNamePassenger, $numberPassengers)) ? 0 : 1,
			'5006' => ($this->countData($birthDayPassenger, $numberPassengers)) ? 0 : 1,
			'4006' => ($this->countData($documentPassenger, $numberPassengers)) ? 0 : 1,
			'5012' => ($this->countData($emailPassenger, $numberPassengers)) ? 0 : 1,
			'4008' => ($this->countData($phonePassenger, $numberPassengers)) ? 0 : 1,
			'5006' => ($this->countData($medicalConditionsPassenger, $numberPassengers)) ? 0 : 1
		);
		$this->validatEmpty($dataValida);
		$dataPlan = $this->selectDynamic('', 'plans', "id='$plan'", array("id_plan_categoria", "name", "num_pas"));
		$datAgency = $this->datAgency($apikey);
		$idCategoryPlan = $dataPlan[0]['id_plan_categoria'];
		$namePlan = $dataPlan[0]['name'];
		$idAgency = $datAgency[0]['id_broker'];
		$isoCountry = $datAgency[0]['id_country'];
		$nameAgency = $datAgency[0]['broker'];
		$userAgency = $datAgency[0]['user_id'];
		$cantPassengerPlan = $dataPlan[0]['num_pas'];
		$arrivalTrans = $this->transformerDate($arrival);
		$departureTrans = $this->transformerDate($departure);
		for ($i = 0; $i < $numberPassengers; $i++) {
			$birthDayPassengerTrans[] = $this->transformerDate($birthDayPassenger[$i]);
		}
		$this->validateDateOrder($arrivalTrans, $departureTrans, $isoCountry);
		$daysByPeople = $this->getDaysByPeople($departureTrans, $arrivalTrans);
		$this->validatePlans($plan, $idAgency, $origin, $destination, $daysByPeople);
		($cantPassengerPlan > $numberPassengers) ?: $this->getError('5003');
		$agesPassenger = $this->setAges($birthDayPassenger, $isoCountry);
		$countryAgency = $this->getCountryAgency($apikey);
		$dataQuoteGeneral = $quoteGeneral->quotePlanbenefis($idCategoryPlan, $daysByPeople, $countryAgency, $destination, $origin, $agesPassenger, $departure, $arrival, $idAgency, $plan);
		$cost = $dataQuoteGeneral[0]['total_costo'];
		$familyPlan = $dataQuoteGeneral[0]['family_plan'];
		if ($dataQuoteGeneral[0]['banda'] == "si") {
			for ($i = 0; $i < $dataQuoteGeneral[0]["total_rangos"]; $i++) {
				$pricePassenger[] = $price / $numberPassengers;
				$costPassenger[] = $dataQuoteGeneral[0]["costo_banda$i"];
			}
		} else {
			if ($dataQuoteGeneral[0]['numero_menores'] > 0) {
				for ($i = 0; $i < $dataQuoteGeneral[0]['numero_menores']; $i++) {
					$pricePassenger[] 	= $price / $numberPassengers;
					$costPassenger[] 	= $dataQuoteGeneral[0]['costoMenor'];
				}
			}
			if ($dataQuoteGeneral[0]['numero_mayores'] > 0) {
				for ($i = 0; $i < $dataQuoteGeneral[0]['numero_mayores']; $i++) {
					$pricePassenger[] 	= $price / $numberPassengers;
					$costPassenger[] 	= $dataQuoteGeneral[0]['costoMayor'];
				}
			}
		}
		$verifiedOrderDuplicate = $this->verifiedOrderDuplicate($departureTrans, $arrivalTrans, $origin, $destination);
		if (!empty($verifiedOrderDuplicate)) {
			$Verified_Beneficiaries = $this->verifiedBeneficiariesDuplicate($verifiedOrderDuplicate, $documentPassenger, $birthDayPassenger);
		}
		$exchangeRate = (empty($exchangeRate)) ? 0 : $exchangeRate;
		$data = [
			'codigo' => $code,
			'salida' => $departureTrans,
			'retorno' => $arrivalTrans,
			'referencia' => $reference,
			'producto' => $plan,
			'destino' => $destination,
			'origen' => $origin,
			'nombre_contacto' => $nameContact,
			'telefono_contacto' => $phoneContact,
			'agencia' => $idAgency,
			'nombre_agencia' => $nameAgency,
			'vendedor' => $userAgency,
			'programaplan' => $idCategoryPlan,
			'family_plan' => $familyPlan,
			'fecha' => 'now()',
			'cantidad' => $numberPassengers,
			'status' => '1',
			'origin_ip' => $_SERVER['REMOTE_ADDR'],
			'email_contacto' => $emailContact,
			'comentarios' => $generalConsiderations,
			'total' => $price,
			'tiempo_x_producto' => $daysByPeople,
			'neto_prov' => $cost,
			'comentario_medicas' => $generalConsiderations,
			'id_emision_type' => '4',
			'validez' => '1',
			'hora' => 'now()',
			'tasa_cambio' => $exchangeRate,
			'alter_cur' => $coin,
			'territory' => $price / $exchangeRate,
			'total_mlc' => $cost / $exchangeRate,
			'neto_prov_mlc' => $dataQuoteGeneral[0]['total_neto'] / $exchangeRate,
			'total_tax' => $dataQuoteGeneral[0]['total_tax1'],
			'total_tax_mlc' => $dataQuoteGeneral[0]['total_tax1'] / $exchangeRate,
			'lang' => $language
		];
		$idOrden = $this->insertDynamic($data, 'orders');
		for ($i = 0; $i < $numberPassengers; $i++) {
			$addBeneficiaries[$i] = $this->add_beneficiaries($documentPassenger[$i], $birthDayPassengerTrans[$i], $namePassenger[$i], $lastNamePassenger[$i], $phonePassenger[$i], $emailPassenger[$i], $idOrden, '1', $pricePassenger[$i], $costPassenger[$i], $medicalConditionsPassenger[$i], $pricePassenger[$i] / $exchangeRate, $costPassenger[$i] / $exchangeRate, 0, 0);
		}
		$response	= [
			"status"	=> "OK",
			'code'		=> $code
		];
		if (!empty($addBeneficiaries) && !empty($idOrden)) {
			$this->response($response);
		}
	}
	public function cancelOrder()
	{
		$api		= $this->api;
		$code		= $this->data['codigo'];
		$notify		= $this->data['notificar'];
		$language	= $this->arrLanguage[$datAgency[0]['language_id']];
		$dataValida	= [
			'6037'	=> !(empty($code) and empty($notify)),
			'6023'	=> $code,
			'9089'	=> $notify,
			'4050'	=> !($notify > 2 || $notify < 1)
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if (!empty($validatEmpty)) {
			return $validatEmpty;
		}
		$datAgency 	= $this->datAgency($api);
		$idAgency	= $datAgency[0]['id_broker'];
		$idUser		= $datAgency[0]['user_id'];
		$isoCountry = $datAgency[0]['id_country'];
		$verifyVoucher	= $this->verifyVoucher($code, $idUser, $isoCountry, 'ADD');
		if ($verifyVoucher) {
			return $verifyVoucher;
		}
		$data	= [
			'status'	=>	'5',
			'f_anulado'	=>	'NOW()'
		];
		$cancelOrder	= $this->updateDynamic('orders', 'codigo', $code, $data);
		if ($notify == '2') {
			$this->sendMailCancel($code, $idAgency, $language);
		}
		if ($cancelOrder) {
			return [
				"status" => "OK"
			];
		}
	}
	public function getOrderPrice()
	{
		$quoteGeneral 			= new quote_general_new();
		$api					= $this->api;
		$plan					= $this->data['plan'];
		$origin					= $this->data['pais_origen'];
		$destination			= $this->data['territorio_destino'];
		$departure				= $this->data['fecha_salida'];
		$arrival				= $this->data['fecha_llegada'];
		$numberPassengers		= $this->data['pasajeros'];
		$typeRange				= $this->data['typeRange'];
		$returnRange			= $this->data['typeResponse'];
		$prefix					= $this->data['prefix'];
		$dataRange				= ($typeRange == 1) ? $this->data['fecha_nacimiento'] : $this->data['edad'];
		$calculateRange  		= $dataRange;
		$arrayMerge				= [];
		$arrayRange				= [];
		if ($typeRange == 1) {
			$dataRange			= $this->data['fecha_nacimiento'];
			$nameRange          = "nacimientos";
			$errorRange         = "6031";
		} else {
			$dataRange          = $this->data['edad'];
			$nameRange          = "edades";
			$errorRange         = "6026";
		}
		if ($returnRange == 3) {
			$plan = "0";
		}
		$dataValidate	= [
			'6037'				=> !(empty($numberPassengers) and empty($origin) and empty($destination) and empty($departure) and empty($arrival)),
			'9058'				=> (!is_numeric($plan)) ? 0 : 1,
			'6027'				=> $origin,
			'6028'				=> $destination,
			'6029'				=> $departure,
			'6030'				=> $arrival,
			'6026'				=> $numberPassengers,
			$errorRange			=> count($calculateRange),
			'4029'				=> (empty($numberPassengers) || $numberPassengers == 0 || !is_numeric($numberPassengers)) ? 0 : 1,
			'6043'				=> ($this->countData($calculateRange, $numberPassengers)) ? 0 : 1,
			'2001'				=> $this->checkDates($departure),
			'1080'				=> ($destination == "1" or $destination == "2" or $destination == "9"),
			'2002'				=> $this->checkDates($arrival),
			'9059'				=> $this->verifyOrigin($origin),
		];
		switch ($typeRange) {
			case '1':
				$arrayRange		= [
					'1062'		=> $this->checkDates($calculateRange),
				];
				break;
			case '2':
				$arrayRange		= [
					'5016'		=> (is_numeric(implode('', $calculateRange))),
				];
				break;
		}
		$arrivalTrans		= $this->transformerDate($arrival);
		$departureTrans		= $this->transformerDate($departure);
		$dataPlan			= $this->selectDynamic('', 'plans', "id='$plan'", array("id_plan_categoria", "name", "num_pas"));
		$datAgency			= $this->datAgency($api);
		$idCategoryPlan		= $dataPlan[0]['id_plan_categoria'];
		$idAgency			= $datAgency[0]['id_broker'];
		$isoCountry			= $datAgency[0]['id_country'];
		$daysByPeople 		= $this->betweenDates($departureTrans, $arrivalTrans);
		$countryAgency		= $this->getCountryAgency($api);
		if ($returnRange != 3) {
			$arrayMerge	= [
				'6022'		=> $plan
			];
			$validatePlans	= $this->validatePlans($plan, $idAgency, $origin, $destination, $daysByPeople);
			if ($validatePlans) {
				return $validatePlans;
			}
		}
		$dataValida			= $dataValidate + $arrayMerge + $arrayRange;
		$validatEmpty		= $this->validatEmpty($dataValida);
		if (!empty($validatEmpty)) {
			return $validatEmpty;
		}
		$validateDateOrder	= $this->validateDateOrder($arrivalTrans, $departureTrans, $isoCountry);
		if (!empty($validateDateOrder)) {
			return $validateDateOrder;
		}
		if ($typeRange == 1) {
			$agesPassenger	= $this->setAges($calculateRange, $isoCountry);
			foreach ($calculateRange as $value) {
				$rangeTrans[] 	= $this->transformerDate($value);
			}
			$betweenDates 	= $this->betweenDates('', $rangeTrans, 'years');
			if (!empty($betweenDates)) {
				return $betweenDates;
			}
		} else {
			$plan           = ($returnRange == 3) ? '' : $plan;
			$agesPassenger	=  implode(',', $calculateRange);
		}
		$dataQuoteGeneral	= $quoteGeneral->quotePlanbenefis($idCategoryPlan, $daysByPeople, $countryAgency, $destination, $origin, $agesPassenger, $departure, $arrival, $idAgency, $plan, '', '', '', '', $prefix);
		$validatBenefits	= $this->verifyBenefits($dataQuoteGeneral);
		if ($validatBenefits) {
			return $validatBenefits;
		}
		switch ($returnRange) {
			case 1:
				$response			= [
					"total_orden"	=> $dataQuoteGeneral[0]['total'],
					"idplan"		=> $plan,
					"fecha_salida"	=> $departure,
					"fecha_regreso"	=> $arrival,
					"dias"			=> $daysByPeople,
					$nameRange      => $calculateRange
				];
				break;
			case 2:
				$response			= [
					"total_orden"	=> $dataQuoteGeneral[0]['total'],
					"idplan"		=> $plan,
					"fecha_salida"	=> $departure,
					"fecha_regreso"	=> $arrival,
					"dias"			=> $daysByPeople,
					$nameRange      => $calculateRange,
					"upgrade"		=> $this->dataUpgrades($plan, 'spa', $dataQuoteGeneral[0]['total'], $daysByPeople, $numberPassengers, '', '', $prefix)
				];
				break;
			case 3:
				$countDataQuoteGeneral	= count($dataQuoteGeneral);
				for ($i = 0; $i < $countDataQuoteGeneral; $i++) {
					$response[$dataQuoteGeneral[$i]['idp']] = [
						"fecha_salida"	=> $departure,
						"fecha_regreso"	=> $arrival,
						"dias"			=> $daysByPeople,
						'name'			=> $dataQuoteGeneral[$i]['name_plan'],
						'total_orden'	=> $dataQuoteGeneral[$i]['total'],
						$nameRange      => $calculateRange,
						//"upgrade"		=> $this->dataUpgrades($dataQuoteGeneral[$i]['idp'],'spa' ,$dataQuoteGeneral[$i]['total'],$daysByPeople,$numberPassengers,'','',$prefix)
					];
				}
				break;
		}
		return $response;
	}
	public function addUpgrades($data, $source = true)
	{
		$api      			= $this->api;
		$data				= $this->data;
		$code				= $data['codigo'];
		$upgrade			= $data['upgrades'];
		$plan				= $data['plan'];
		$daysByPeople		= $data['daybypeople'];
		$cost				= $data['cost'];
		$price				= $data['price'];
		$numberPassengers	= $data['numberPassengers'];
		$upgradeObj			= $data["upgrades"];
		$bDayBeneficiaries	= $data["beneficiaries"];
		$priceBeneficiaries = $data["precio_vta"];
		$costBeneficiaries 	= $data["precio_cost"];
		$idOrden			= 0;
		$upgradeObj			= (is_object($data['upgrades'])) ? (array) $data['upgrades'] : json_decode($data['upgrades'], true);
		$dataUpgrade 		= (array) $upgradeObj["item"];
		$countDataUpgrade 	= count($upgradeObj["item"]);
		$idUpgrade	= [];
		$arrUpgrade	= [];
		if ($countDataUpgrade == 1) {
			$idUpgrade[] = $dataUpgrade['id'];
		} else {
			$idUpgrade 			= array_map(function ($value) {
				return $value->id;
			}, $dataUpgrade);
		}
		$dataValida				= [
			'6037'	=> !(empty($code) and empty($dataUpgrade)),
			'6020'	=> $api,
			'6023'	=> $code,
			'6039'	=> $countDataUpgrade
		];
		if ($source) {
			$dataOrder			= $this->getOrderData($code);
			$plan 				= $dataOrder['producto'];
			$idOrden			= $dataOrder['id'];
			$datAgency 			= $this->datAgency($api);
			$numberPassengers	= $dataOrder['cantidad'];
			$idUser				= $datAgency[0]['user_id'];
			$daysByPeople 		= $this->betweenDates($dataOrder['salida'], $dataOrder['retorno']);
			$price 				= $dataOrder['total'];
			$cost 				= $dataOrder['neto_prov'];
			$isoCountry 		= $dataOrder['id_country'];
			$dataValidaUpgra	= [
				'6037'		=> count($this->selectDynamic('', 'orders', "codigo='$code'", array("id"))),
				'6047'		=> !($this->selectDynamic(['id_raider' => $idUpgrade], 'orders_raider', "id_orden='$idOrden'", array("id"))),
			];
			$dataValida		= $dataValida + $dataValidaUpgra;
			$verifyVoucher 	= $this->verifyVoucher($code, $idUser, $isoCountry, 'ADD');
			if ($verifyVoucher) {
				return $verifyVoucher;
			}
		}
		$validatEmpty	= $this->validatEmpty($dataValida);
		if ($validatEmpty) {
			return $validatEmpty;
		}
		$arrPricePassengers	= [];
		$arrUpgNotType2		= [];
		$idUpgradesOrden	= [];
		for ($i = 0; $i < $countDataUpgrade; $i++) {
			if ($countDataUpgrade == 1) {
				$id			= $dataUpgrade['id'];
				$document	= $dataUpgrade['documento'];
			} else {
				$id = $dataUpgrade[$i]->id;
				$document	= $dataUpgrade[$i]->documento;
			}
			$typeUpgrade	= $this->valUpgrades($plan, $id);
			if (!empty($typeUpgrade)) {
				if ($typeUpgrade == 2) {
					if (empty($document)) {
						return $this->getError('4006');
					} else {
						if (!$source) {
							if ($countDataUpgrade == 1) {
								$id			= $dataUpgrade['id'];
								$dataUpgradeDocument[]	= $dataUpgrade['documento'];
							} else {
								$dataUpgradeDocument = array_map(function ($value) {
									return $value->documento;
								}, $dataUpgrade);
							}
							$validateBeneficiaries = array_diff($bDayBeneficiaries, $dataUpgradeDocument);
							if (count($validateBeneficiaries) > 0) {
								return $this->getError('9028');
							}
							$arrPricePassengers[] = [
								'id_raider'		=> $id,
								'precio_vta'	=> $priceBeneficiaries[$i],
								'precio_cost'	=> $costBeneficiaries[$i],
								'id'	=> 0
							];
						} else {
							$pricePassengers = $this->dataBeneficiaries($code, '', $document);
							if (!empty($pricePassengers['Error_Code'])) {
								return $pricePassengers;
							} else {
								$arrPricePassengers[] = $pricePassengers[0] + ['id_raider' => $id];
							}
						}
					}
				} else {
					$arrUpgNotType2[] = $id;
				}
			} else {
				return $this->getError('1095');
			}
		}
		$priceUpgrades	= 0;
		$costUpgrades 	= 0;
		if (count($arrPricePassengers) > 0) {
			foreach ($arrPricePassengers as  $value) {
				$getPriceUpgrade	= $this->dataUpgrades($plan, 'spa', $price, $daysByPeople, $numberPassengers, $value['id_raider'], $value['precio_vta'])[0];
				$getCostUpgrade		= $this->dataUpgrades($plan, 'spa', $cost, $daysByPeople, $numberPassengers, $value['id_raider'], $value['precio_cost'])[0];
				$addOrderUpgrades[] = $this->addOrderUpgrades($idOrden, $value['id_raider'], $getPriceUpgrade['price_upgrade'], $getCostUpgrade['price_upgrade'], 0, $value['id']);
				$data =	[
					'precio_vta'	=> $getPriceUpgrade['price_upgrade'] + $value['precio_vta'],
					'precio_cost'	=> $getCostUpgrade['price_upgrade'] + $value['precio_cost']
				];
				if ($source) {
					$this->updateDynamic('beneficiaries', 'id', $value['id'], $data);
				}
				$priceUpgrades 	+= $getPriceUpgrade['price_upgrade'];
				$costUpgrades 	+= $getCostUpgrade['price_upgrade'];
			}
		}
		if (count($arrUpgNotType2) > 0) {
			$getPriceUpgrade	= $this->dataUpgrades($plan, 'spa', $price, $daysByPeople, $numberPassengers, implode(',', $arrUpgNotType2));
			$getCostUpgrade		= $this->dataUpgrades($plan, 'spa', $cost, $daysByPeople, $numberPassengers, implode(',', $arrUpgNotType2));
			for ($i = 0; $i < count($getPriceUpgrade); $i++) {
				$addOrderUpgrades[] = $this->addOrderUpgrades($idOrden, $getPriceUpgrade[$i]['id_raider'], $getPriceUpgrade[$i]['price_upgrade'], $getCostUpgrade[$i]['price_upgrade'], 0, 0);
				$priceUpgrades 	+= $getPriceUpgrade[$i]['price_upgrade'];
				$costUpgrades 	+= $getCostUpgrade[$i]['price_upgrade'];
			}
		}
		$priceNew 	= $price + $priceUpgrades;
		$costNew 	= $cost  + $costUpgrades;
		$addUpgradeOrder = $this->updateUpgradeOrder($code, $priceNew, $costNew);
		$arrResult = [
			'voucher' 			=> $code,
			'valor_adicional' 	=> $priceUpgrades,
			'upgrades' 			=> $upgrade,
		];
		if (!$source) {
			$arrResult	= array_merge($arrResult, ["id" => $addOrderUpgrades, "price" => $priceNew, "cost" => $costNew]);
		}
		return $arrResult;
	}
	public function cancelUpgrade()
	{
		$api			= $this->api;
		$code			= $this->data['codigo'];
		$upgrade		= $this->data['upgrades'];
		$idOrden		= $this->selectDynamic(['status' => '1'], 'orders', "codigo='$code'", array("id"))[0]['id'];
		$data			= [
			'value_raider',
			'cost_raider'
		];
		$dataRaider     = $this->selectDynamic(['id_raider' => $upgrade], 'orders_raider', "id_orden='$idOrden'", $data);
		$dataValida		= [
			'6037'	=> !(empty($code) and empty($upgrade)),
			'6023'	=> $code,
			'6039'	=> $upgrade,
			'1020'	=> $idOrden,
			'6046'	=> count($dataRaider),
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if (!empty($validatEmpty)) {
			return $validatEmpty;
		}
		$dataOrder			= $this->getOrderData($code);
		$datAgency			= $this->datAgency($api);
		$idOrden			= $dataOrder['id'];
		$price 				= $dataOrder['total'];
		$cost	 			= $dataOrder['neto_prov'];
		$idUser 			= $datAgency[0]['user_id'];
		$plan				= $dataOrder['producto'];
		$priceRaider		= $dataRaider[0]['value_raider'];
		$costRaider			= $dataRaider[0]['cost_raider'];
		$idCountry 			= $datAgency[0]['id_country'];
		$verifyVoucher 		= $this->verifyVoucher($code, $idUser, $idCountry, 'ADD');
		if ($verifyVoucher) {
			return $verifyVoucher;
		}
		$data	= [
			'total'		=> $price - $priceRaider,
			'neto_prov'	=> $cost - $costRaider
		];
		$updatePriceOrder 	= $this->updateDynamic('orders', 'codigo', $code, $data);
		$deleteUpgradeOrder	= $this->deleteUpgradeOrder($idOrden, $upgrade);
		if ($updatePriceOrder && $deleteUpgradeOrder) {
			return [
				'voucher' 			=> $code,
				'valor_descuento' 	=> $priceRaider,
				'pricer_order' 		=> $price - $priceRaider
			];
		}
	}
	public function changesForOrdersReported()
	{
		$api				= $this->api;
		$code				= $this->data['codigo'];
		$status				= $this->data['status'];
		$origin				= $this->data['pais_origen'];
		$destination		= $this->data['pais_destino'];
		$departure			= $this->data['fecha_salida'];
		$cost				= $this->data['costo'];
		$dataValida			= [
			'6037'	=> !(empty($code) and empty($status) and empty($origin) and empty($destination) and empty($departure) and  empty($cost)),
			'6023'	=> $code,
			'9020'	=> !(empty($status) && empty($origin) && empty($destination) && empty($departure) && empty($cost)),
			'9021'	=> (!empty($status)) ? !(!is_numeric($status) || ($status != 1 && $status != 5)) : true,
			'1090'	=> (!empty($origin)) ? $this->verifyOrigin($origin) : true,
			'9023'	=> (!empty($cost)) ? (is_numeric($cost)) : true,
			'2001'	=> (!empty($departure)) ? $this->checkDates($departure) : true,
			'1080'	=> (!empty($destination)) ? ($destination == "1" or $destination == "2" or $destination == "9") ? 1 : 0 : true
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if (!empty($validatEmpty)) {
			return $validatEmpty;
		}
		$data_broker		= $this->datAgency($api);
		$idAgency 			= $data_broker[0]['id_broker'];
		$idUser 			= $data_broker[0]['user_id'];
		$isoCountry			= $data_broker[0]['id_country'];
		$verifyVoucher 		= $this->verifyVoucher($code, $idUser, $isoCountry, 'REPORT');
		if ($verifyVoucher) {
			return $verifyVoucher;
		}
		$dataVoucher		= $this->getOrderData($code);
		$plan				= $dataVoucher['producto'];
		$departureTrans		= $this->transformerDate($departure);
		if (!empty($destination)) {
			$verifyDestination	= $this->verifyRestrictionDestination($destination, $plan);
			if ($verifyDestination) {
				return $verifyDestination;
			}
		}
		if (!empty($departure)) {
			$validateDateOrder	= $this->validateDateOrder($dataVoucher['retorno'], $departureTrans, $isoCountry);
			if ($validateDateOrder) {
				return $validateDateOrder;
			}
		}
		$data	=
			[
				'status'	=> $status,
				'origen'	=> $origin,
				'destino'	=> $destination,
				'salida'	=> $departureTrans,
				'total'		=> $cost
			];
		$updateOrder = $this->updateDynamic('orders', 'codigo', $code, $data);
		if ($updateOrder) {
			if ($status != 5) {
				if (!empty($departure) ||  !empty($cost) || !empty($destination)) {
					$data	=
						[
							'api'		=> $api,
							'action'	=> 'INTERNO',
							'codigo'	=> $code,
							'total'		=> $cost
						];
					$crudBeneficiaries	=	$this->crudBeneficiaries($data);
					if ($crudBeneficiaries['status'] == 'OK') {
						return ['status' 	=> 'OK'];
					} else {
						return $crudBeneficiaries;
					}
				} else {
					return ['status'	=> 'OK'];
				}
			} else {
				return ['status'	=> 'OK'];
			}
		}
	}
	public function crudBeneficiaries($data)
	{
		$quoteGeneral 				= new quote_general_new();
		$data 						= !empty($data) ? $data : $this->data;
		$api      					= $this->api;
		$code        				= $data['codigo'];
		$action		   				= $data['action'];
		$passengerObj				= (array) $data['databeneficiarie'];
		$birthDayPassenger			= $passengerObj['nacimiento'];
		$emailPassenger				= $passengerObj['email'];
		$namePassenger				= $passengerObj['nombres'];
		$lastNamePassenger			= $passengerObj['apellidos'];
		$documentPassenger			= $passengerObj['documento'];
		$medicalConditionsPassenger	= $passengerObj['medicas'];
		$phonePassenger				= $passengerObj['telefono'];
		$idPassenger				= $passengerObj['idbeneficiarie'];
		$dataValida		= [
			'6023'		=> $code,
			'9024'		=> $action
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if (!empty($validatEmpty)) {
			return $validatEmpty;
		}
		$datAgency	 		= $this->datAgency($api);
		$isoCountry			= $datAgency[0]['id_country'];
		$idAgency			= $datAgency[0]['id_broker'];
		$idUser				= $datAgency[0]['user_id'];
		$dataOrder			= $this->getOrderData($code);
		$plan				= $dataOrder['producto'];
		$idOrden			= $dataOrder['id'];
		$dataPlan			= $this->selectDynamic('', 'plans', "id='$plan'", array("id_plan_categoria"));
		$idCategoryPlan 	= $dataPlan[0]['id_plan_categoria'];
		$departure			= $dataOrder['salida'];
		$arrival			= $dataOrder['retorno'];
		$exchangeRate		= $dataOrder['tasa_cambio'];
		$departureTrans				= $this->transformerDate($departure, 2);
		$arrivalTrans				= $this->transformerDate($arrival, 2);
		$daysByPeople   			= $this->betweenDates($departure, $arrival);
		$birthDayPassengerTrans		= $this->transformerDate($birthDayPassenger);
		$countryAgency 				= $this->getCountryAgency($api);
		if ($action != 'INTERNO') {
			$verifyVoucher	= $this->verifyVoucher($code, $idUser, $isoCountry, 'REPORT');
			if (!empty($verifyVoucher)) {
				return $verifyVoucher;
			}
		}
		if ($action == 'PUT' || $action == 'DELETE') {
			$putValid	= [
				'9026'	=> $idPassenger,
				'9027'	=> is_numeric($idPassenger)
			];
			$validatEmpty	= $this->validatEmpty($putValid);
			if (!empty($validatEmpty)) {
				return $validatEmpty;
			}
			$verifiedBeneficiaries	= $this->verifiedBeneficiariesByVoucher($code, $idPassenger);
			if ($verifiedBeneficiaries) {
				return $verifiedBeneficiaries;
			}
		}
		if ($action == 'ADD' || $action == 'PUT') {
			$validateDataPassenger = $this->validateDataPassenger(1, (array) $namePassenger, (array) $lastNamePassenger, (array) $birthDayPassenger, (array) $documentPassenger, (array) $emailPassenger, (array) $phonePassenger, (array) $medicalConditionsPassenger);
			if ($validateDataPassenger) {
				return $validateDataPassenger;
			}
		}
		$dataBeneficiaries		= $this->getBeneficiariesByVoucher($code);
		$numberPassenger		= count($dataBeneficiaries);
		$birthDayBeneficiaries	=
			array_map(
				function ($value) {
					return $value['nacimiento'];
				},
				$dataBeneficiaries
			);
		array_push($birthDayBeneficiaries, $birthDayPassengerTrans);
		$agesPassenger			= $this->setAges($birthDayBeneficiaries, $isoCountry);
		$dataQuoteGeneral		= $quoteGeneral->quotePlanbenefis($idCategoryPlan, $daysByPeople, $countryAgency, $dataOrder['territory'], $dataOrder['origen'], $agesPassenger, $departureTrans, $arrivalTrans, $idAgency, $plan);
		$validatBenefits		= $this->verifyBenefits($dataQuoteGeneral);
		if ($validatBenefits) {
			return $validatBenefits;
		}
		switch ($action) {
			case 'ADD':
				$beneficiariesDuplicate = $this->verifiedBeneficiariesDuplicate($idOrden, array($documentPassenger), array($birthDayPassengerTrans), 9062);
				if (!empty($beneficiariesDuplicate)) {
					return $beneficiariesDuplicate;
				}
				$this->addBeneficiares($documentPassenger, $birthDayPassengerTrans, $namePassenger, $lastNamePassenger, $phonePassenger, $emailPassenger, $idOrden, '1', '0', '0', $medicalConditionsPassenger, '0', '0', '0', '0');
				break;
			case 'PUT':
				$data	= [
					'nombre'			=> $namePassenger,
					'apellido'			=> $lastNamePassenger,
					'telefono'			=> $phonePassenger,
					'nacimiento'		=> $birthDayPassengerTrans,
					'condicion_medica'	=> $medicalConditionsPassenger,
					'documento'			=> $documentPassenger,
					'email'				=> $emailPassenger
				];
				break;
			case 'DELETE':
				if ($dataOrder['cantidad'] == '1') {
					return $this->getError('9031');
				}
				$data	= [
					'ben_status'	=> '2',
				];
				break;
			case 'INTERNO':
				break;
			default:
				return $this->getError('9030');
				break;
		}
		if ($action == 'PUT' || $action == 'DELETE') {
			$updateDynamicBeneficiares = $this->updateDynamic('beneficiaries', 'id', $idPassenger, $data);
		}
		$cost							= $dataQuoteGeneral[0]['total_costo'];
		$price							= $dataQuoteGeneral[0]['total'];
		$familyPlan						= $dataQuoteGeneral[0]['family_plan'];
		if ($dataQuoteGeneral[0]['banda'] == "si") {
			for ($i = 0; $i < $dataQuoteGeneral[0]["total_rangos"]; $i++) {
				$pricePassenger[] 		= $price / $numberPassenger;
				$costPassenger[]		= $dataQuoteGeneral[0]["costo_banda$i"];
			}
		} else {
			if ($dataQuoteGeneral[0]['numero_menores'] > 0) {
				for ($i = 0; $i < $dataQuoteGeneral[0]['numero_menores']; $i++) {
					$pricePassenger[] 	= $price / $numberPassenger;
					$costPassenger[] 	= $dataQuoteGeneral[0]['costoMenor'];
				}
			}
			if ($dataQuoteGeneral[0]['numero_mayores'] > 0) {
				for ($i = 0; $i < $dataQuoteGeneral[0]['numero_mayores']; $i++) {
					$pricePassenger[] 	= $price / $numberPassenger;
					$costPassenger[] 	= $dataQuoteGeneral[0]['costoMayor'];
				}
			}
		}
		$dataBeneficiariesNew		= $this->getBeneficiariesByVoucher($code);
		$idBeneficiaries	= array_map(function ($value) {
			return $value['id'];
		}, $dataBeneficiariesNew);
		for ($i = 0; $i < $numberPassenger; $i++) {
			$data	= [
				'precio_vta'		=> $pricePassenger[$i],
				'precio_cost'		=> $costPassenger[$i],
				'precio_vta_mlc'	=> $costPassenger[$i] / $exchangeRate
			];
			$updateBeneficiares	= $this->updateDynamic('beneficiaries', 'id', $idBeneficiaries[$i], $data);
		}
		$data	= [
			'total'			=> $price,
			'cantidad'		=> $numberPassenger,
			'neto_prov'		=> $cost,
			'family_plan'	=> $familyPlan
		];
		$updateOrder	= $this->updateDynamic('orders', 'codigo', $code, $data);
		if ($updateOrder) {
			return array('status' => 'OK', 'Result' => 'successful ' . $action);
		}
	}
	public function requestChanges()
	{
		$quoteGeneral 					= new quote_general_new();
		$data 							= $this->data;
		$api							= $this->api;
		$code							= $data['codigo'];
		$reference						= $data['referencia'];
		$origin							= $data['pais'];
		$numberPassengers				= $data['pasajeros'];
		$reference						= $data['referencia'];
		$numberPassengers				= $data['pasajeros'];
		$nameContact					= $data['nombre_contacto'];
		$phoneContact					= $data['telefono_contacto'];
		$emailContact					= $data['email_contacto'];
		$issue							= $data['emision'];
		$language						= $data['lenguaje'];
		$namePassenger					= $data['nombres'];
		$lastNamePassenger				= $data['apellidos'];
		$documentPassenger  			= $data['documentos'];
		$emailPassenger					= $data['emails'];
		$medicalConditionsPassenger		= $data['medicas'];
		$phonePassenger					= $data['telefonos'];
		$dataValida			= [
			'6037'	=> !(empty($origin) and empty($numberPassengers) and  empty($documentPassenger) and  empty($namePassenger) and empty($lastNamePassenger) and empty($phonePassenger) and empty($emailPassenger) and empty($medicalConditionsPassenger) and empty($nameContact) and empty($phoneContact) and empty($emailContact) and empty($language)),
			'6027'	=> $origin,
			'6026'	=> $numberPassengers,
			'6021'	=> $language,
			'6036'	=> $nameContact,
			'4009'	=> $phoneContact,
			'4004'	=> $emailContact,
			'6035'	=> $issue,
			'4004'	=> (!$this->verifyMail($emailContact)) ? 0 : 1,
			'5010'	=> (!is_numeric($phoneContact)) ? 0 : 1,
			'4002'	=> (empty($numberPassengers) || $numberPassengers == 0 || !is_numeric($numberPassengers)) ? 0 : 1,
			'9012'	=> ($issue < 1 || !is_numeric($issue) || $issue > 2) ? 0 : 1,
			'1030'	=> $this->validLanguage($language),
			'9049'	=> ($this->countData($namePassenger, $numberPassengers)) ? 0 : 1,
			'9053'	=> ($this->countData($lastNamePassenger, $numberPassengers)) ? 0 : 1,
			'9050'	=> ($this->countData($documentPassenger, $numberPassengers)) ? 0 : 1,
			'9052'	=> ($this->countData($emailPassenger, $numberPassengers)) ? 0 : 1,
			'9054'	=> ($this->countData($phonePassenger, $numberPassengers)) ? 0 : 1,
			'9055'	=> ($this->countData($medicalConditionsPassenger, $numberPassengers)) ? 0 : 1,
			'9059'	=> $this->verifyOrigin($origin),
			'9060'	=> (!preg_match('(^[a-zA-Z ]*$)', $nameContact)) ? 0 : 1
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if (!empty($validatEmpty)) {
			return $validatEmpty;
		}
		$plan				= $this->selectDynamic('', 'orders', "codigo='$code'", ["producto"])[0]['producto'];
		$datAgency			= $this->datAgency($api);
		$idAgency			= $datAgency[0]['id_broker'];
		$isoCountry			= $datAgency[0]['id_country'];
		$nameAgency			= $datAgency[0]['broker'];
		$userAgency			= $datAgency[0]['user_id'];
		$cantPassengerPlan	= $dataPlan[0]['num_pas'];
		$prefix				= $datAgency[0]['prefijo'];
		$verifyVoucher 		= $this->verifyVoucher($code, $userAgency, $isoCountry, 'ADD');
		if ($verifyVoucher) {
			return $verifyVoucher;
		}
		$validatePlans		= $this->validatePlans($plan, '', $origin, '', '');
		if ($validatePlans) {
			return $validatePlans;
		}
		$validateDataPassenger	= $this->validateDataPassenger($numberPassengers, $namePassenger, $lastNamePassenger, '00/00/0000', $documentPassenger, $emailPassenger, $phonePassenger, $medicalConditionsPassenger, false);
		if ($validateDataPassenger) {
			return $validateDataPassenger;
		}
		$data	= [
			'referencia'		=> $reference,
			'nombre_contacto'	=> $nameContact,
			'origen'			=> $origin,
			'telefono_contacto'	=> $phoneContact,
			'email_contacto'	=> $emailContact
		];
		$updateOrder	= $this->updateDynamic('orders', 'codigo', $code, $data);
		if ($updateOrder) {
			$idBeneficiarie	= $this->traer_ids_beneficiarios($code);
			if ($idBeneficiarie) {
				for ($i = 0; $i < $numberPassengers; $i++) {
					$data	= [
						'email'		=> $emailPassenger[$i],
						'nombre'	=> $namePassenger[$i],
						'apellido'	=> $lastNamePassenger[$i],
						'documento'	=> $documentPassenger[$i],
						'condicion_medica'	=> $medicalConditionsPassenger[$i],
						'telefono'	=> $phonePassenger[$i]
					];
					$updateBeneficiares	= $this->updateDynamic('beneficiaries', 'id', $idBeneficiarie[$i]['id'], $data);
					if ($updateBeneficiares) {
						return
							[
								'status' => "OK"
							];
					}
				}
			}
		}
	}

	public function checkPreorder($data)
	{
		$quoteGeneral 					= new quote_general_new();
		$data 							= $this->data;
		$api							= $this->api;
		$origin							= $data['pais_origen'];
		$destination					= $data['pais_destino'];
		$departure						= $data['fecha_salida'];
		$arrival						= $data['fecha_llegada'];
		$reference						= $data['referencia'];
		$plan							= $data['id_plan'];
		$exchangeRate					= $data['tasa_cambio'];
		$coin							= $data['moneda'];
		$numberPassengers				= $data['pasajeros'];
		$language						= $data['lenguaje'];
		$nameContact					= $data['nombre_contacto'];
		$phoneContact					= $data['telefono_contacto'];
		$emailContact					= $data['email_contacto'];
		$generalConsiderations			= $data['consideraciones_generales'];
		$issue							= $data['emision'];
		$upgrade						= $data['upgrade'];
		$namePassengerObj				= (is_object($data['nombres'])) ? (array) $data['nombres'] : json_decode($data['nombres'], true);
		$lastNamePassengerObj			= (is_object($data['apellidos'])) ? (array) $data['apellidos'] : json_decode($data['apellidos'], true);
		$birthDayPassengerObj  			= (is_object($data['nacimientos'])) ? (array) $data['nacimientos'] : json_decode($data['nacimientos'], true);
		$documentPassengerObj  			= (is_object($data['documentos'])) ? (array) $data['documentos'] : json_decode($data['documentos'], true);
		$emailPassengerObj				= (is_object($data['correos'])) ? (array) $data['correos'] : json_decode($data['correos'], true);
		$medicalConditionsPassengerObj	= (is_object($data['observaciones_medicas'])) ? (array) $data['observaciones_medicas'] : json_decode($data['observaciones_medicas'], true);
		$phonePassengerObj				= (is_object($data['telefonos'])) ? (array) $data['telefonos'] : json_decode($data['telefonos'], true);
		$documentPassenger 				= (array) $documentPassengerObj["item"];
		$birthDayPassenger				= (array) $birthDayPassengerObj["item"];
		$lastNamePassenger				= (array) $lastNamePassengerObj['item'];
		$emailPassenger					= (array) $emailPassengerObj['item'];
		$namePassenger					= (array) $namePassengerObj['item'];
		$phonePassenger					= (array) $phonePassengerObj["item"];
		$medicalConditionsPassenger		= (array) $medicalConditionsPassengerObj["item"];
		$dataValida			= [
			'6037'	=> !(empty($departure) and empty($arrival) and empty($plan) and empty($destination) and empty($origin) and  empty($coin) and empty($exchangeRate) and empty($numberPassengers) and empty($birthDayPassenger) and  empty($documentPassenger) and  empty($namePassenger) and empty($lastNamePassenger) and empty($phonePassenger) and empty($emailPassenger) and empty($medicalConditionsPassenger) and empty($nameContact) and empty($phoneContact) and empty($emailContact) and empty($lenguaje) and empty($upgrade)),
			'6029'	=> $departure,
			'6030'	=> $arrival,
			'6022'	=> $plan,
			'6028'	=> $destination,
			'6027'	=> $origin,
			'6034'	=> $coin,
			"6026"	=> $numberPassengers,
			'6021'	=> $language,
			'6036'	=> $nameContact,
			'4009'	=> $phoneContact,
			'4004'	=> $emailContact,
			'6035'	=> $issue,
			'1062'	=> $this->checkDates($birthDayPassenger),
			'4004'	=> (!$this->verifyMail($emailContact)) ? 0 : 1,
			'5010'	=> (!is_numeric($phoneContact)) ? 0 : 1,
			'4002'	=> (empty($numberPassengers) or $numberPassengers == 0 or !is_numeric($numberPassengers)) ? 0 : 1,
			'1080'	=> ($destination == "1" or $destination == "2" or $destination == "9") ? 1 : 0,
			'9011'	=> ($exchangeRate <= 0 or is_float($exchangeRate)) ? 0 : 1,
			'9012'	=> ($issue < 1 || !is_numeric($issue) || $issue > 3) ? 0 : 1,
			'1022'	=> (!$this->selectDynamic('', 'currency', "value_iso='$coin'", array("desc_small"))) ? 0 : 1,
			'1030'	=> $this->validLanguage($language),
			'9049'	=> ($this->countData($namePassenger, $numberPassengers)) ? 0 : 1,
			'9053'	=> ($this->countData($lastNamePassenger, $numberPassengers)) ? 0 : 1,
			'9051'	=> ($this->countData($birthDayPassenger, $numberPassengers)) ? 0 : 1,
			'9050'	=> ($this->countData($documentPassenger, $numberPassengers)) ? 0 : 1,
			'9052'	=> ($this->countData($emailPassenger, $numberPassengers)) ? 0 : 1,
			'9054'	=> ($this->countData($phonePassenger, $numberPassengers)) ? 0 : 1,
			'9055'	=> ($this->countData($medicalConditionsPassenger, $numberPassengers)) ? 0 : 1,
			'2001'	=> $this->checkDates($departure),
			'2002'	=> $this->checkDates($arrival),
			'9060'	=> (!preg_match('(^[a-zA-Z ]*$)', $nameContact)) ? 0 : 1,
			'9059'	=> $this->verifyOrigin($origin),
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if (!empty($validatEmpty)) {
			return $validatEmpty;
		}
		foreach ($birthDayPassenger as $value) {
			$birthDayPassengerTrans[] 	= $this->transformerDate($value);
		}
		$betweenDates 	= $this->betweenDates('', $birthDayPassengerTrans, 'years');
		if (!empty($betweenDates)) {
			return $betweenDates;
		}
		$validateDataPassenger	= $this->validateDataPassenger($numberPassengers, $namePassenger, $lastNamePassenger, $birthDayPassenger, $documentPassenger, $emailPassenger, $phonePassenger, $medicalConditionsPassenger);
		if ($validateDataPassenger) {
			return $validateDataPassenger;
		}
		$dataPlan			= $this->selectDynamic('', 'plans', "id='$plan'", array("id_plan_categoria", "name", "num_pas"));
		$datAgency			= $this->datAgency($api);
		$idCategoryPlan 	= $dataPlan[0]['id_plan_categoria'];
		$namePlan			= $dataPlan[0]['name'];
		$idAgency			= $datAgency[0]['id_broker'];
		$isoCountry			= $datAgency[0]['id_country'];
		$nameAgency			= $datAgency[0]['broker'];
		$userAgency			= $datAgency[0]['user_id'];
		$cantPassengerPlan	= $dataPlan[0]['num_pas'];
		$prefix				= $datAgency[0]['prefijo'];
		$arrivalTrans		= $this->transformerDate($arrival);
		$departureTrans		= $this->transformerDate($departure);
		$validateDateOrder	= $this->validateDateOrder($arrivalTrans, $departureTrans, $isoCountry);
		if ($validateDateOrder) {
			return $validateDateOrder;
		}
		$daysByPeople 		= $this->betweenDates($departureTrans, $arrivalTrans);
		$validatePlans		= $this->validatePlans($plan, $idAgency, $origin, $destination, $daysByPeople);
		if ($validatePlans) {
			return $validatePlans;
		}
		($cantPassengerPlan > $numberPassengers) ?: $this->getError('5003');
		$agesPassenger		= $this->setAges($birthDayPassenger, $isoCountry);
		$countryAgency		= $this->getCountryAgency($api);
		$dataQuoteGeneral	= $quoteGeneral->quotePlanbenefis($idCategoryPlan, $daysByPeople, $countryAgency, $destination, $origin, $agesPassenger, $departure, $arrival, $idAgency, $plan);
		$validatBenefits	= $this->verifyBenefits($dataQuoteGeneral);
		if ($validatBenefits) {
			return $validatBenefits;
		}
		if (!empty($upgrade)) {
			$verifyUpgrade	= $this->valUpgrades($plan, $upgrade);
			if (!$verifyUpgrade) {
				return $this->getError('1095');
			}
		}
		return [
			'status' => "OK"
		];
	}
}
