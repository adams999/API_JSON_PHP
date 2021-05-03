<?php

class get_functions extends general_functions
{
	public function get_fuctions($function, $apikey)
	{
		$response   = $this->$function($_GET, $apikey);
		$countResponse  = count($response);
		$this->logsave($_GET, json_encode($response), $function, '4', $apikey);
		($countResponse != 0) ? $this->response($response, '', $format) : $this->getError(9015, '', $format);
	}
	public function getPlans($filters, $api)
	{
		$language	= $filters['lenguaje'];
		$plan       = $filters['plan'];
		$dataValida	= [
			'6021'	=> $language,
			'1030'	=> $this->validLanguage($language)
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if (!empty($validatEmpty)) {
			return $validatEmpty;
		}
		$datAgency			= $this->datAgency($api);
		$idAgency			= $datAgency[0]['id_broker'];
		return $this->verifyRestrictionPlan($idAgency, $plan, $language, true);
	}
	public function getCoverages($data, $api)
	{
		$plan		= $data['plan'];
		$prefix		= $data['prefix'];
		$language	= $this->funcLangApp();
		$dataValida	= [
			'6037'	=> !(empty($plan) and empty($language)),
			'6022'	=> $plan,
			'6021'	=> $language,
			'1030'	=> in_array($language, ['spa', 'eng', 'por', 'fra', 'deu'])
		];

		$validatEmpty	= $this->validatEmpty($dataValida);

		if (!empty($validatEmpty)) {
			return $validatEmpty;
		}

		//$restrictionPlan	= $this->verifyRestrictionPlan('',$plan,$language,false,$api,true);
		// if($restrictionPlan){
		// 	return $restrictionPlan;
		// }
		return $this->dataCoverages($language, $plan, $prefix);
	}
	public function getClients()
	{
		$response = $this->selectDynamic('', 'clients', "data_activa='si' AND id_status= '1' AND IFNULL(inactive_platform, 0) <> 2 AND notificacion_error_ws = 1 AND type_platform = '1' ORDER BY client ASC", ['client', 'id_country', 'img_cliente', 'web', 'urlPrueba', 'prefix', 'type_platform', 'id_broker', 'email', 'colors_platform'], '', '', '', '', '');
		for ($i = 0; $i < count($response); $i++) {
			$response[$i]['web'] = $this->baseURL($response[$i]['web']);
		}
		return $response;
	}
	public function getCountries($filters)
	{
		$prefix     = $filters['prefix'];
		$dataValida	= [
			"9092"  => $prefix,
		];

		$this->validatEmpty($dataValida);

		$data = [
			'querys' => "SELECT
				iso_country,
				description
			FROM
				countries
			WHERE
				c_status = 'Y'
			AND (
				iso_country IS NOT NULL
				AND iso_country <> ''
			)
			ORDER BY
				description "
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkParam 	= $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$response = $this->curlGeneral($linkParam, json_encode($data), $headers);
		return json_decode($response);
		//return $this->selectDynamic('', 'countries', "c_status='Y'", ['iso_country', 'description'], '', ['min' => '0', 'max' => '349'], ['field' => 'description', 'order' => 'ASC']);
	}

	public function getTerritorios($filters)
	{
		$prefix     = $filters['prefix'];
		$dataValida	= [
			"9092"  => $prefix,
		];

		$this->validatEmpty($dataValida);

		$data = [
			'querys' => "SELECT
				*
			FROM
				territory
			WHERE
				id_status = 1 
			AND (
				id_territory IS NOT NULL
				AND id_territory <> ''
			)
			ORDER BY
				desc_small,
				id_territory "
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkParam 	= $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$response = $this->curlGeneral($linkParam, json_encode($data), $headers);
		return json_decode($response);
	}
	public function getIpUser()
	{
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if (getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if (getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if (getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if (getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if (getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	public function getStates()
	{
		return $this->selectDynamic('', '', '', '', "SELECT
										iso_country,
										iso_state,
										description,
										nameID
									FROM
										`states`
									ORDER BY
										iso_country ASC", '', '', '', '');
	}
	public function getCitys()
	{
		return $this->selectDynamic('', '', '', '', "SELECT
										iso_city,
										description
									FROM
										`cities`
									ORDER BY
										iso_city ASC", '', '', '', '');
	}
	public function checkVersionApp()
	{
		return $this->selectDynamic('', 'versions_app', "status='Y'", ['version', 'fecha_version'], '', ['min' => '0', 'max' => '1'], ['field' => 'id_version', 'order' => 'DESC']);
	}
	public function checkVersionAppA($filters)
	{
		$prefix    = $filters['prefixApp'] ?: $filters['prefix'];
		$plataforma	   = $filters['plataforma'];
		$versionApp = $filters['versionAppApi'];
		$dataValida	= [
			"9092"  => $prefix,
			"50001"  => $plataforma
		];
		$validatEmpty  = $this->validatEmpty($dataValida);
		if (!empty($filters['prefixApp'] && $versionApp != 'DEV')) { //validacion si se envia la version de la app para responder a esto
			return $this->selectDynamic('', 'versions_app', "status='Y' AND prefijo = '$prefix' AND plataforma= '$plataforma' AND version > '$versionApp' ", ['MAX(version) AS version', 'MAX(fecha_version) AS fecha_version', 'REPLACE (GROUP_CONCAT(CONCAT(descripcion, CHAR(13))),","," ") as descripcion'], '', ['min' => '0', 'max' => '1'], '', '', '');
		} else {
			return $this->selectDynamic('', 'versions_app', "status='Y' AND prefijo = '$prefix' AND plataforma= '$plataforma' ", ['version', 'fecha_version', 'descripcion'], '', ['min' => '0', 'max' => '1'], ['field' => 'id_version', 'order' => 'DESC'], '', '');
		}
	}
	public function getPlan($filters)
	{
		$idPlan    = $filters['idPlan'];
		$prefix	   = $filters['prefix'];
		$lang_app  = $this->funcLangApp();
		$dataValida	= [
			"5022"  => $idPlan,
			"9092"  => $prefix
		];

		$this->validatEmpty($dataValida);

		return $this->selectDynamic('', '', '', '', "
			SELECT
				plan_detail.description as description,
				id_plans, 
				plans.id, 
				name, 
				activo, 
				id_plan_categoria
			FROM
				plans
			JOIN plan_detail ON plans.id = plan_detail.plan_id
			AND plan_detail.language_id = '$lang_app'
			AND plan_detail.prefijo = '$prefix'
			WHERE
				plans.id = '$idPlan'
			AND plans.prefijo = '$prefix' ", '', '', '');
	}
	public function getVendedor($filters)
	{
		$idVendedor    = $filters['idVendedor'];
		$prefix	       = $filters['prefix'];
		$dataValida	= [
			"40094"  => $idVendedor,
			"9092"  => $prefix
		];
		$validatEmpty  = $this->validatEmpty($dataValida);
		return $this->selectDynamic('', 'users_extern', "id = $idVendedor AND prefijo = '$prefix'", ['firstname', 'lastname', 'email', 'phone', 'id'], '', '', '', '', '');
	}
	public function getAgencyParam($filters)
	{
		$prefix	    = $filters['prefix'];
		$dataValida	= [
			"9092"  => $prefix
		];
		$validatEmpty  = $this->validatEmpty($dataValida);
		return $this->selectDynamic('', 'clients', "prefix = '$prefix' AND data_activa = 'si' AND id_status= '1' AND IFNULL(inactive_platform, 0) <> 2", ['client', 'phone1', 'cod_phone2', 'phone2', 'cod_phone3', 'phone3', 'cod_phone4', 'phone4', 'address', 'id_country', 'img_cliente', 'id_country', 'id_city', 'zip_code', 'date_up', 'id_state', 'web', 'urlPrueba', 'prefix', 'type_platform', 'id_broker', 'email', 'colors_platform'], '', '', '', '', '', '');
	}

	///FUNCION OPTIMIZADA PARA ALIVIANAR SUBCONSULTAS EN SERVIDOR MAS OPTIMA
	public function getOrders($filters)
	{
		$document  = $filters['document'];
		$code	   = $filters['code'];
		$name      = $filters['name'];
		$prefix	   = $filters['prefix'];
		$prefixApp = $filters['prefixApp'];
		$userType  = ($filters['userType']) ? $filters['userType'] : 1;
		$startDate = $filters['startDate'];
		$endDate   = $filters['endDate'];
		$source    = $filters['source'];
		$min	   = ($filters['min'] <= 0 || empty($filters['min'])) ? 0 : $filters['min'];
		$max	   = ($filters['max'] <= 0 || empty($filters['max'] || ($filters['max'] <= $filters['min']))) ? 50 : $filters['max'];
		$status	   = ($filters['status']) ? $filters['status'] : 1;
		$today 	   = date('Y-m-d');
		$id_user   = $filters['id_user'];
		$estatus   = $filters['estatus'];
		$agencyFilter = $filters['agencyFilter'];
		$lang_app  = $this->funcLangApp();

		$dataValida	= [
			"9092"  => $prefix,
			"9017"  => !empty($status) ? in_array($status, [1, 2, 3, 4, 5]) : true,
			'3030'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($startDate) > strtotime($endDate)) : true,
			'9068'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($endDate)	> strtotime($today)) : true,
			'9069'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($startDate)	> strtotime($today)) : true,
		];


		$validatEmpty	= $this->validatEmpty($dataValida);
		if (!empty($validatEmpty)) {
			return $validatEmpty;
		}

		$dataCurl = [
			'querys' => "SELECT
							parameter_value
						FROM
							parameters
						WHERE
							parameters.parameter_key = 'GRACE_PERIOD'
						AND show_parameter = 1 LIMIT 1"
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkplatf 	= $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$periodGrace = json_decode($this->curlGeneral($linkplatf, json_encode($dataCurl), $headers), true)[0]['parameter_value'];

		$valueOrders = [
			'orders.id',
			'orders.id_orders',
			'codigo',
			'origen',
			"IF (
				destino = 'XX'
				OR destino = ''
				OR destino IS NULL,
				IF (
					territory.desc_small = ''
					OR territory.desc_small IS NULL,
					'N/A',
					territory.desc_small
				),
				destino
			) AS destino",
			"DATE_FORMAT(salida,'%d-%m-%Y') as fsalida",
			'retorno',
			"DATE_FORMAT(retorno,'%d-%m-%Y') as fretorno",
			"DATE_FORMAT(fecha,'%d-%m-%Y') as ffecha",
			"REPLACE( orders.nombre_contacto,'''','') AS nombre_contacto",
			'email_contacto',
			'telefono_contacto',
			'agencia',
			'nombre_agencia',
			'status',
			'cantidad',
			'referencia',
			'territory',
			'producto',
			'(DATEDIFF(orders.retorno, orders.salida) + 1 ) as diasViaje',
			'plan_categoria_detail.name_plan AS categoria',
			'v_authorizado',
			'currency.value_iso AS moneda',
			'credito_tipo',
			'credito_nombre',
			'orders.prefijo',
			'credito_expira'
		];

		if ($source != 'public') {
			array_push(
				$valueOrders,
				'orders.family_plan',
				'comentario_medicas',
				'total',
				'vendedor',
				'cupon',
				'cupon_descto',
				'codeauto',
				'procesado',
				'response',
				'v_authorizado',
				'neto_prov',
				'tasa_cambio',
				'alter_cur',
				'total_mlc',
				'orders.forma_pago',
				'neto_prov_mlc',
				'total_mlc',
				'orders.compra_minima',
				'pareja_plan',
				'orders.Voucher_Individual',
				'v_authorizado',
				'credito_numero',
				'broker.id_broker as id_agencia',
				'broker.code_phone1 AS code1_agencia',
				'broker.phone1 AS phone1_agencia',
				'broker.code_phone2 AS code2_agencia',
				'broker.phone2 AS phone2_agencia',
				'broker.code_phone3 AS code3_agencia',
				'broker.phone3 AS phone3_agencia',
				'orders.type_anulacion',
				'credit_note.monto_nc',
				"DATE_FORMAT(credit_note.Fecha_aplicado,'%d-%m-%Y') as Fecha_aplicado",
				'orders.USE_nro_notaCredito'
			);
		}

		$codeWhere = '1';
		$arrWhere = [];
		// $idBroker	= $this->getBrokersByApiKey($apikey);
		// if (!empty($idBroker) && in_array($userType, [2, 5]) && $prefixApp != 'ILS') {
		// 	$arrWhere['agencia'] = $idBroker;
		// }
		if (!empty($code)) {
			$codeWhere .= " AND codigo LIKE '%$code%' ";
		}
		if (!empty($estatus) && $estatus != 'all') {
			$codeWhere .= " AND orders.status IN ('$estatus') ";
		}
		if (!empty($agencyFilter) && $agencyFilter != 'all') {
			$codeWhere .= " AND orders.agencia = '$agencyFilter' ";
		}

		$arrWhere['orders.prefijo'] = $prefix;
		$arrLimit = ['min' => $min, 'max' => $max];
		if (!empty($name)) {
			$name = trim($name);
			$nameSearch = explode(' ', $name);
			$arrJoin = " INNER JOIN beneficiaries ON orders.id = beneficiaries.id_orden
			AND beneficiaries.prefijo = orders.prefijo 
			AND (
				concat_ws(
					' ',
					TRIM(
						BOTH ' '
						FROM
							beneficiaries.nombre
					),
					TRIM(
						BOTH ' '
						FROM
							beneficiaries.apellido
					)
				) LIKE '%$name%'
				OR (
					TRIM(
						BOTH ' '
						FROM
							beneficiaries.nombre
					) LIKE '%$nameSearch[0]%'
					AND TRIM(
						BOTH ' '
						FROM
							beneficiaries.apellido
					) LIKE '%$nameSearch[1]%'
				)
				OR (
					TRIM(
						BOTH ' '
						FROM
							beneficiaries.nombre
					) LIKE '%$nameSearch[1]%'
					AND TRIM(
						BOTH ' '
						FROM
							beneficiaries.apellido
					) LIKE '%$nameSearch[0]%'
				)
			) ";
			// $arrJoin = [
			// 	'table'		=> 'beneficiaries',
			// 	'field'		=> "id_orden AND beneficiaries.prefijo = orders.prefijo AND (concat_ws(' ', TRIM(BOTH ' ' FROM beneficiaries.nombre),
			// 		TRIM(BOTH ' ' FROM beneficiaries.apellido)) LIKE '%$name%'
			// 		OR (TRIM(BOTH ' ' FROM beneficiaries.nombre) LIKE '%$nameSearch[0]%'
			// 		AND TRIM(BOTH ' ' FROM beneficiaries.apellido) LIKE '%$nameSearch[1]%')
			// 		OR (TRIM(BOTH ' ' FROM beneficiaries.nombre) LIKE '%$nameSearch[1]%'
			// 		AND TRIM(BOTH ' ' FROM beneficiaries.apellido) LIKE '%$nameSearch[0]%'))",
			// 	'fieldComp'	=> 'id'
			// ];
		}

		if (!empty($document)) {
			$arrJoin = " INNER JOIN beneficiaries ON orders.id = beneficiaries.id_orden
			AND beneficiaries.prefijo = orders.prefijo AND beneficiaries.documento LIKE '%$document%' ";
			// $arrJoin = [
			// 	'table' => "beneficiaries",
			// 	'field' => "id_orden AND beneficiaries.prefijo = orders.prefijo AND beneficiaries.documento LIKE '%$document%'",
			// 	'fieldComp' => "id"
			// ];
			$arrLimit = ['min' => $min, 'max' => $max];
			array_push($valueOrders, 'beneficiaries.documento');
		}

		if (!empty($startDate) && !empty($endDate)) {
			$between = [
				'start' => $startDate,
				'end'   => $endDate,
				'field' => 'orders.fecha'
			];
		}
		if (!empty($pagination)) {
			$arrPagination  = implode(',', $pagination);
			if (is_array($arrPagination)) {
			}
		}

		$id_agencia = [];
		$arrBrokers = [];
		$arr;
		if (!empty($userType) && in_array($userType, [5, 2]) && $prefixApp != 'ILS') { ////// usuario tipo 5 broker access solo vera de su agencia y 2 es broker admin su agencia y las debajo de ella
			$id_agencia =  $this->agencyBroker($id_user, $userType, $prefix)[0]["id_associate"];
			$arrWhere['orders.agencia'] = $id_agencia ?: 0;

			if (in_array($userType, [2])) { ////// usuario tipo 2 broker admin vera vouchers de ella y sus agencias hijas
				$arrWhere['orders.agencia'] = null;
				$broker_nivel = $this->agencysChildren($id_agencia, $prefix);
				if ($broker_nivel) {
					$arrBrokers = $broker_nivel;
					array_push($arrBrokers, (($id_agencia) ?: 0));
					$arrBrokers = array_values($arrBrokers); //agencias hijas y su agencia master
					$arr = implode(',', array_unique($arrBrokers));
				} else {
					$arr = $id_agencia;
				}
				$arr = ' AND orders.agencia IN (' . $arr . ') ';
				$codeWhere .= $arr;
			}
		}

		//$arrWhere['status']=$status;
		$dataOrders = $this->selectDynamic(
			$arrWhere,
			" orders 
			JOIN plans ON orders.producto = plans.id
			AND orders.prefijo = plans.prefijo
			JOIN plan_category ON plan_category.id_plan_categoria = plans.id_plan_categoria
			AND plan_category.prefijo = plans.prefijo
			JOIN plan_categoria_detail ON plan_category.id_plan_categoria = plan_categoria_detail.id_plan_categoria
			AND plan_categoria_detail.prefijo = plan_category.prefijo AND plan_categoria_detail.language_id = '$lang_app' 
			JOIN currency ON currency.id_currency = plans.id_currence 
			LEFT JOIN credit_note ON credit_note.nro_voucher = orders.codigo 
			AND credit_note.prefijo = orders.prefijo 
			JOIN territory ON IF (
				orders.territory = ''
				OR orders.territory IS NULL,
				0,
				orders.territory
			) = territory.id_territory
			AND orders.prefijo = territory.prefijo
			JOIN broker ON broker.id_broker = orders.agencia
			AND broker.prefijo = orders.prefijo " . $arrJoin . "",
			"$codeWhere",
			$valueOrders,
			false,
			$arrLimit,
			["field" => "fecha", "order" => "DESC"],
			$between,
			false //$arrJoin,

		);
		$dataIdsOrder = array_column($dataOrders, 'id');

		////si tiene ids procedo a buscar ordenes y raiders si no retorno vacio
		if (!empty($dataIdsOrder)) {
			$sqlBeneficiaries = "SELECT
				beneficiaries.id,
				id_orden,
				REPLACE (
					beneficiaries.nombre,
					'''',
					''
				) AS nombre,
				REPLACE (
					beneficiaries.apellido,
					'''',
					''
				) AS apellido,
				documento,
				email,
				DATE_FORMAT(nacimiento, '%d-%m-%Y') AS nacimiento,
				nacionalidad,
				tipo_doc,
				telefono,
				precio_vta,
				precio_cost,
				TIMESTAMPDIFF(
					YEAR,
					beneficiaries.nacimiento,
					orders.retorno
				) AS edad,
				condicion_medica
			FROM
				beneficiaries
			INNER JOIN orders ON orders.prefijo = beneficiaries.prefijo
			AND orders.id = beneficiaries.id_orden
			WHERE
				beneficiaries.id_orden IN (" . implode(',', $dataIdsOrder) . ")
			AND beneficiaries.prefijo = '$prefix'
			ORDER BY
				nombre ASC";

			$databeneficiaries = $this->selectDynamic('', '', '', '', $sqlBeneficiaries, '', '', '', '');

			$sqlraidersOrders = "SELECT
			orders_raider.id,
			orders_raider.id_orden,
			orders_raider.id_raider,
			orders_raider.value_raider,
			orders_raider.cost_raider,
			orders_raider.id_beneft,
			raiders_detail.name_raider,
			raiders.rd_calc_type,
			raiders.specific_benefit,
			raiders.promocion,
			raiders_detail.language_id,
			orders.codigo,
			beneficiaries.nombre,
			beneficiaries.apellido,
			TIMESTAMPDIFF(
				YEAR,
				beneficiaries.nacimiento,
				orders.retorno
			) AS edad
		FROM
			orders_raider
		INNER JOIN raiders ON orders_raider.id_raider = raiders.id_raider
		AND orders_raider.prefijo = raiders.prefijo
		INNER JOIN raiders_detail ON raiders.id_raider = raiders_detail.id_raider
		AND orders_raider.prefijo = raiders_detail.prefijo
		LEFT JOIN orders ON orders.id = orders_raider.id_orden
		AND orders.prefijo = orders_raider.prefijo
		LEFT JOIN beneficiaries ON beneficiaries.id = orders_raider.id_beneft
		AND orders_raider.prefijo = beneficiaries.prefijo
		WHERE
			orders_raider.id_orden IN (" . implode(',', $dataIdsOrder) . ")
		AND orders_raider.prefijo = '$prefix'
		AND raiders_detail.language_id = '$lang_app'
		AND (
			orders_raider.id_status IS NULL
			OR orders_raider.id_status <> '2'
		)
		GROUP BY
			id_raider,
			id_beneft
		ORDER BY
			nombre ASC";

			$dataOrdersRaiders = $this->selectDynamic('', '', '', '', $sqlraidersOrders, '', '', '', '');

			$coloresFondo = [
				'actSVig' => '#CEE3F6',
				'actVig'  => '#CEF6CE',
				'actExp'  => '#F5F6CE',
				'invAnul' => '#F6CECE',
				'oter'    => '#FFFFFF'
			];

			foreach ($dataOrders as $key => &$value) {
				$value['response'] = json_decode($value['response'], true);
				$value['period_grace'] = (int) $periodGrace ?: 3;
				$value['total']    = bcdiv($value['total'], 1, 2);
				$value['total_mlc']  = bcdiv($value['total_mlc'], 1, 2);

				switch (true) {
					case (strtotime($value['fsalida']) < strtotime(date('d-m-Y')) && $value['status'] == 1):
						$value['bg'] = $coloresFondo['actSVig'];
						break;

					case (strtotime($value['fsalida']) >= strtotime(date('d-m-Y')) && $value['status'] == 1):
						$value['bg'] = $coloresFondo['actVig'];
						break;

					case ($value['status'] == 3):
						$value['bg'] = $coloresFondo['actExp'];
						break;

					case ($value['status'] == 5 || $value['status'] == 4):
						$value['bg'] = $coloresFondo['invAnul'];
						break;

					default:
						$value['bg'] = $coloresFondo['oter'];
						break;
				}

				foreach ($databeneficiaries as $key2 => &$value2) {
					if ($value['id'] == $value2['id_orden']) {
						$dataOrders[$key]['beneficiaries'][] = $databeneficiaries[$key2];
					}
				}
				foreach ($dataOrdersRaiders as $key2 => &$value2) {
					if ($value['id'] == $value2['id_orden']) {
						$dataOrders[$key]['raiders'][] = $dataOrdersRaiders[$key2];
					}
				}
				if (empty($dataOrders[$key]['beneficiaries'])) {
					$dataOrders[$key]['beneficiaries'] = [];
				}
				if (empty($dataOrders[$key]['raiders'])) {
					$dataOrders[$key]['raiders'] = [];
				}
			}
			unset($databeneficiaries, $dataOrdersRaiders);
			return $dataOrders;
		} else {
			return [];
		}
	}

	public function getOrdersPrueba($filters)
	{
		$document  = $filters['document'];
		$code	   = $filters['code'];
		$name      = $filters['name'];
		$prefix	   = $filters['prefix'];
		$prefixApp = $filters['prefixApp'];
		$userType  = ($filters['userType']) ? $filters['userType'] : 1;
		$startDate = $filters['startDate'];
		$endDate   = $filters['endDate'];
		$source    = $filters['source'];
		$min	   = ($filters['min'] <= 0 || empty($filters['min'])) ? 0 : $filters['min'];
		$max	   = ($filters['max'] <= 0 || empty($filters['max'] || ($filters['max'] <= $filters['min']))) ? 50 : $filters['max'];
		$status	   = ($filters['status']) ? $filters['status'] : 1;
		$today 	   = date('Y-m-d');
		$id_user   = $filters['id_user'];
		$estatus   = $filters['estatus'];
		$agencyFilter = $filters['agencyFilter'];
		$lang_app  = $this->funcLangApp();

		$dataValida	= [
			"9092"  => $prefix,
			"9017"  => !empty($status) ? in_array($status, [1, 2, 3, 4, 5]) : true,
			'3030'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($startDate) > strtotime($endDate)) : true,
			'9068'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($endDate)	> strtotime($today)) : true,
			'9069'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($startDate)	> strtotime($today)) : true,
		];

		$validatEmpty	= $this->validatEmpty($dataValida);
		if (!empty($validatEmpty)) {
			return $validatEmpty;
		}

		$dataCurl = [
			'querys' => "SELECT
							parameter_value
						FROM
							parameters
						WHERE
							parameters.parameter_key = 'GRACE_PERIOD'
						AND show_parameter = 1 LIMIT 1"
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkplatf 	= $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$periodGrace = json_decode($this->curlGeneral($linkplatf, json_encode($dataCurl), $headers), true)[0]['parameter_value'];

		$valueOrders = [
			'orders.id',
			'orders.id_orders',
			'codigo',
			'origen',
			"IF (
				destino = 'XX'
				OR destino = ''
				OR destino IS NULL,
				IF (
					territory.desc_small = ''
					OR territory.desc_small IS NULL,
					'N/A',
					territory.desc_small
				),
				destino
			) AS destino",
			"DATE_FORMAT(salida,'%d-%m-%Y') as fsalida",
			'retorno',
			"DATE_FORMAT(retorno,'%d-%m-%Y') as fretorno",
			"DATE_FORMAT(fecha,'%d-%m-%Y') as ffecha",
			"REPLACE( orders.nombre_contacto,'''','') AS nombre_contacto",
			'email_contacto',
			'telefono_contacto',
			'agencia',
			'nombre_agencia',
			'status',
			'cantidad',
			'referencia',
			'territory',
			'producto',
			'(DATEDIFF(orders.retorno, orders.salida) + 1 ) as diasViaje',
			'plan_categoria_detail.name_plan AS categoria',
			'v_authorizado',
			'currency.value_iso AS moneda',
			'credito_tipo',
			'credito_nombre',
			'credito_expira'
		];

		if ($source != 'public') {
			array_push(
				$valueOrders,
				'orders.family_plan',
				'comentario_medicas',
				'total',
				'vendedor',
				'cupon',
				'cupon_descto',
				'codeauto',
				'procesado',
				'response',
				'v_authorizado',
				'neto_prov',
				'tasa_cambio',
				'alter_cur',
				'total_mlc',
				'orders.forma_pago',
				'neto_prov_mlc',
				'total_mlc',
				'orders.compra_minima',
				'pareja_plan',
				'orders.Voucher_Individual',
				'v_authorizado',
				'credito_numero',
				'broker.id_broker as id_agencia',
				'broker.code_phone1 AS code1_agencia',
				'broker.phone1 AS phone1_agencia',
				'broker.code_phone2 AS code2_agencia',
				'broker.phone2 AS phone2_agencia',
				'broker.code_phone3 AS code3_agencia',
				'broker.phone3 AS phone3_agencia',
				'orders.type_anulacion',
				'credit_note.monto_nc',
				"DATE_FORMAT(credit_note.Fecha_aplicado,'%d-%m-%Y') as Fecha_aplicado",
				'orders.USE_nro_notaCredito'
			);
		}

		$codeWhere = '1';
		$arrWhere = [];
		// $idBroker	= $this->getBrokersByApiKey($apikey);
		// if (!empty($idBroker) && !in_array($userType, [1, 2, 5, 13]) && $prefixApp != 'ILS') {
		// 	$arrWhere['agencia'] = $idBroker;
		// }
		if (!empty($code)) {
			$codeWhere .= " AND codigo LIKE '%$code%' ";
		}
		if (!empty($estatus) && $estatus != 'all') {
			$codeWhere .= " AND orders.status IN ('$estatus') ";
		}
		if (!empty($agencyFilter) && $agencyFilter != 'all') {
			$codeWhere .= " AND orders.agencia = '$agencyFilter' ";
		}

		$arrWhere['orders.prefijo'] = $prefix;
		$arrLimit = ['min' => $min, 'max' => $max];
		if (!empty($name)) {
			$name = trim($name);
			$nameSearch = explode(' ', $name);
			$arrJoin = " INNER JOIN beneficiaries ON orders.id = beneficiaries.id_orden
			AND beneficiaries.prefijo = orders.prefijo 
			AND (
				concat_ws(
					' ',
					TRIM(
						BOTH ' '
						FROM
							beneficiaries.nombre
					),
					TRIM(
						BOTH ' '
						FROM
							beneficiaries.apellido
					)
				) LIKE '%$name%'
				OR (
					TRIM(
						BOTH ' '
						FROM
							beneficiaries.nombre
					) LIKE '%$nameSearch[0]%'
					AND TRIM(
						BOTH ' '
						FROM
							beneficiaries.apellido
					) LIKE '%$nameSearch[1]%'
				)
				OR (
					TRIM(
						BOTH ' '
						FROM
							beneficiaries.nombre
					) LIKE '%$nameSearch[1]%'
					AND TRIM(
						BOTH ' '
						FROM
							beneficiaries.apellido
					) LIKE '%$nameSearch[0]%'
				)
			) ";
			// $arrJoin = [
			// 	'table'		=> 'beneficiaries',
			// 	'field'		=> "id_orden AND beneficiaries.prefijo = orders.prefijo AND (concat_ws(' ', TRIM(BOTH ' ' FROM beneficiaries.nombre),
			// 		TRIM(BOTH ' ' FROM beneficiaries.apellido)) LIKE '%$name%'
			// 		OR (TRIM(BOTH ' ' FROM beneficiaries.nombre) LIKE '%$nameSearch[0]%'
			// 		AND TRIM(BOTH ' ' FROM beneficiaries.apellido) LIKE '%$nameSearch[1]%')
			// 		OR (TRIM(BOTH ' ' FROM beneficiaries.nombre) LIKE '%$nameSearch[1]%'
			// 		AND TRIM(BOTH ' ' FROM beneficiaries.apellido) LIKE '%$nameSearch[0]%'))",
			// 	'fieldComp'	=> 'id'
			// ];
		}

		if (!empty($document)) {
			$arrJoin = " INNER JOIN beneficiaries ON orders.id = beneficiaries.id_orden
			AND beneficiaries.prefijo = orders.prefijo AND beneficiaries.documento LIKE '%$document%' ";
			// $arrJoin = [
			// 	'table' => "beneficiaries",
			// 	'field' => "id_orden AND beneficiaries.prefijo = orders.prefijo AND beneficiaries.documento LIKE '%$document%'",
			// 	'fieldComp' => "id"
			// ];
			$arrLimit = ['min' => $min, 'max' => $max];
			array_push($valueOrders, 'beneficiaries.documento');
		}

		if (!empty($startDate) && !empty($endDate)) {
			$between = [
				'start' => $startDate,
				'end'   => $endDate,
				'field' => 'orders.fecha'
			];
		}
		if (!empty($pagination)) {
			$arrPagination  = implode(',', $pagination);
			if (is_array($arrPagination)) {
			}
		}

		$id_agencia = [];
		$arrBrokers = [];
		$arr;
		if (!empty($userType) && in_array($userType, [5, 2]) && $prefixApp != 'ILS') { ////// usuario tipo 5 broker access solo vera de su agencia y 2 es broker admin su agencia y las debajo de ella
			$id_agencia =  $this->agencyBroker($id_user, $userType, $prefix)[0]["id_associate"];
			$arrWhere['orders.agencia'] = $id_agencia ?: 0;

			if (in_array($userType, [2])) { ////// usuario tipo 2 broker admin vera vouchers de ella y sus agencias hijas
				$arrWhere['orders.agencia'] = null;
				$broker_nivel = $this->agencysChildren($id_agencia, $prefix);
				if ($broker_nivel) {
					$arrBrokers = $broker_nivel;
					array_push($arrBrokers, (($id_agencia) ?: 0));
					$arrBrokers = array_values($arrBrokers); //agencias hijas y su agencia master
					$arr = implode(',', array_unique($arrBrokers));
				} else {
					$arr = $id_agencia;
				}
				$arr = ' AND orders.agencia IN (' . $arr . ') ';
				$codeWhere .= $arr;
			}
		}

		//$arrWhere['status']=$status;
		$dataOrders = $this->selectDynamic(
			$arrWhere,
			" orders 
			JOIN plans ON orders.producto = plans.id
			AND orders.prefijo = plans.prefijo
			JOIN plan_category ON plan_category.id_plan_categoria = plans.id_plan_categoria
			AND plan_category.prefijo = plans.prefijo
			JOIN plan_categoria_detail ON plan_category.id_plan_categoria = plan_categoria_detail.id_plan_categoria
			AND plan_categoria_detail.prefijo = plan_category.prefijo AND plan_categoria_detail.language_id = '$lang_app' 
			JOIN currency ON currency.id_currency = plans.id_currence 
			LEFT JOIN credit_note ON credit_note.nro_voucher = orders.codigo 
			AND credit_note.prefijo = orders.prefijo 
			JOIN territory ON IF (
				orders.territory = ''
				OR orders.territory IS NULL,
				0,
				orders.territory
			) = territory.id_territory
			AND orders.prefijo = territory.prefijo
			JOIN broker ON broker.id_broker = orders.agencia
			AND broker.prefijo = orders.prefijo " . $arrJoin . "",
			"$codeWhere",
			$valueOrders,
			false,
			$arrLimit,
			["field" => "fecha", "order" => "DESC"],
			$between,
			false //$arrJoin,

		);

		foreach ($dataOrders as $key => &$value) {

			$value['response'] = json_decode($value['response'], true);
			$dataOrders[$key]['period_grace'] = (int) $periodGrace ?: 3;
			$dataOrders[$key]['total']    = substr($dataOrders[$key]['total'], 0, strpos($dataOrders[$key]['total'], '.') + 3);
			$dataOrders[$key]['total_mlc']  = substr($dataOrders[$key]['total_mlc'], 0, strpos($dataOrders[$key]['total_mlc'], '.') + 3);
			$dataOrders[$key]['beneficiaries'] = $this->selectDynamic(
				['beneficiaries.prefijo' => $prefix],
				'beneficiaries',
				"id_orden= '" . $value['id'] . "'",
				['id', 'id_orden', "REPLACE(beneficiaries.nombre,'''','') AS nombre ", "REPLACE(beneficiaries.apellido,'''','') AS apellido ", 'documento', 'email', "DATE_FORMAT(nacimiento,'%d-%m-%Y') as nacimiento", 'nacionalidad', 'tipo_doc', 'telefono', 'precio_vta', 'precio_cost', "TIMESTAMPDIFF( YEAR, beneficiaries.nacimiento, '{$value['retorno']}' ) AS edad", 'condicion_medica'],
				'',
				'',
				[
					'field' => 'nombre',
					'order' => 'ASC'
				],
				'',
				''
			);

			///si existe raiders
			$dataOrders[$key]['raiders'] = $this->raidersOrdersApp($prefix, $lang_app, $value['id'], $value['retorno']);
		}

		return $dataOrders;
	}

	public function getAgencyParent($filters)
	{
		return [$filters, $this->agencysParent($filters['prefix'], $filters['idAgency'])];
	}

	public function getAgencys($filters)
	{
		$prefix  = $filters['prefix'];
		$idAgency = $filters['agency'];

		$dataValida	= [
			"9092"  => $prefix
		];

		$this->validatEmpty($dataValida);

		if (empty($idAgency) || $idAgency == 'N/A') {
			$query = "SELECT
					id_broker as id,
					broker AS name
				FROM
					broker
				WHERE
					prefijo = '$prefix'
				AND id_status = 1
				ORDER BY
					broker ASC";

			return $this->selectDynamic('', '', '', '', $query, '', '', '');
		}
		if (!empty($idAgency) && $idAgency != 'N/A') {
			$idagencys = $this->agencysChildren($idAgency, $prefix);
			if ($idagencys) {
				$arrBrokers = $idagencys;
				array_push($arrBrokers, (($idAgency) ?: 0));
				$arrBrokers = array_values($arrBrokers); //agencias hijas y su agencia master
				$arr = implode(',', array_unique($arrBrokers));
			} else {
				$arr = $idAgency;
			}
			$query = "SELECT
						id_broker as id,
						broker AS name
					FROM
						broker
					WHERE
						prefijo = '$prefix'
					AND id_status = 1
					AND id_broker IN ($arr)
					ORDER BY
						broker ASC";
			return $this->selectDynamic('', '', '', '', $query, '', '', '');
		}
	}

	public function getInfoSocialsPlatform($filters)
	{
		$prefix  = $filters['prefix'];

		$dataValida	= [
			"9092"  => $prefix
		];

		$this->validatEmpty($dataValida);

		$dataCurl = [
			'querys' => "SELECT
							parameter_key,
							parameter_value
						FROM
							parameters
						WHERE
							parameter_key IN (
								'SKYPE',
								'EMAIL_FROM',
								'ID_WHATSAPP'
							)
						AND id_status = 1"
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkplatf = $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$aux = json_decode($this->curlGeneral($linkplatf, json_encode($dataCurl), $headers), true);
		for ($i = 0; $i < count($aux); $i++) {
			$auxResp[$aux[$i]['parameter_key']] = $aux[$i]['parameter_value'];
		}
		return $auxResp;
	}

	public function getCondicionadosApp($filters)
	{
		$prefix  	= $filters['prefix'];
		$language	= $this->funcLangApp();

		$dataValida	= [
			"9092"  => $prefix
		];

		$this->validatEmpty($dataValida);

		$query = "SELECT url_document FROM wording_parameter WHERE 1 AND language_id = '$language' AND id_status = '1' order by id_status";
		$dataCurl = [
			'querys' => $query
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkplatf = $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		return $link . '/app/admin/server/php/files/' . json_decode($this->curlGeneral($linkplatf, json_encode($dataCurl), $headers), true)[0]['url_document'];
	}
	public function getinfoTextTelef($filters)
	{
		$prefix  	= $filters['prefix'];
		$language	= $this->funcLangApp();

		$dataValida	= [
			"9092"  => $prefix
		];

		$this->validatEmpty($dataValida);

		$query = "SELECT
					language_id,
					text_inf_carntet1,
					text_inf_carntet2
				FROM
					content_detalle_backend
				WHERE
				 language_id = '$language'
				AND tipotext IN ('reverso')";

		$dataCurl = [
			'querys' => $query
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkplatf = $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		return json_decode($this->curlGeneral($linkplatf, json_encode($dataCurl), $headers), true);
	}

	public function getGuardarOrdenEventsApp($filters)
	{
		$prefix             = $filters['prefix'];
		$idBroker           = (!empty($filters['agency']) && $filters['agency'] != 'N/A')  ? $filters['agency'] : '';
		$idUser             = $filters['id_user'] ?: '';
		$userType           = $filters['userType'];
		$lang_app    		= $this->funcLangAppShort($this->funcLangApp());
		$id_orden			= $filters['id_orden'];
		$email				= $filters['email'] ?: '1';
		$calendario			= $filters['calendario'] ?: '2';
		$sms				= $filters['sms'] ?: '1';
		$smstelefono		= $filters['smstelefono'];

		$dataValida			= [
			'9092'	=> $prefix,
			'50005'	=> $idUser,
			'40098'	=> $id_orden,
			'5029'	=> $smstelefono
		];

		$this->validatEmpty($dataValida);

		$sql = "INSERT INTO `orders_eventos` (
					oe_id_orden,
					noti_correo,
					add_calendar,
					noti_sms,
					num_sms
				)
				VALUES
					('$id_orden', '$email', '$calendario', '$sms', '$smstelefono')";

		$dataquote = [
			'querys'         => $sql
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkQuote 	= $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		return $this->curlGeneral($linkQuote, json_encode($dataquote), $headers);
	}

	public function getParamAgencyMaster($filters)
	{
		$id_user = $filters['id_user'];
		$prefix  = $filters['prefix'];
		$agency  = $filters['agency'];

		$dataValida	= [
			"50005" => $id_user,
			"9092"  => $prefix,
			"50006" => $agency
		];

		$this->validatEmpty($dataValida);

		$response = $this->selectDynamic(
			'',
			'',
			'',
			'',
			"SELECT
			*, (
				SELECT
					broker_nivel.nivel
				FROM
					broker_nivel
				WHERE
					broker_nivel.id_broker = broker_parameters.id_broker
				AND broker_nivel.prefijo = broker_parameters.prefijo
				ORDER BY
					id_broker_nivel DESC
				LIMIT 1
			) AS nivel,
			(
				SELECT
					broker.broker
				FROM
					broker
				WHERE
					broker.id_broker = broker_parameters.id_broker
				AND broker.prefijo = broker_parameters.prefijo
				LIMIT 1
			) AS nombreAgencia,
			(
				SELECT
					broker.address
				FROM
					broker
				WHERE
					broker.id_broker = broker_parameters.id_broker
				AND broker.prefijo = broker_parameters.prefijo
			) AS direccion,
			(
				SELECT
					countries.description
				FROM
					broker
				INNER JOIN countries ON countries.iso_country = broker.id_country
				WHERE
					broker.id_broker = broker_parameters.id_broker
				AND broker.prefijo = broker_parameters.prefijo
			) AS pais,
			(
				SELECT
					states.description
				FROM
					broker
				INNER JOIN states ON states.iso_state = broker.id_state
				WHERE
					broker.id_broker = broker_parameters.id_broker
				AND broker.prefijo = broker_parameters.prefijo
			) AS estado,
			(
				SELECT
					cities.description
				FROM
					broker
				INNER JOIN cities ON cities.iso_city = broker.id_city
				WHERE
					broker.id_broker = broker_parameters.id_broker
				AND broker.prefijo = broker_parameters.prefijo
			) AS ciudad
		FROM
			broker_parameters
		WHERE
			broker_parameters.prefijo = '$prefix'
		AND broker_parameters.id_broker = '$agency'
		ORDER BY
			id_broker_parameters DESC
		LIMIT 1",
			'',
			'',
			'',
			''
		);

		if (empty($response)) {
			$respons = $this->selectDynamic(
				'',
				'',
				'',
				'',
				"SELECT
				*, (
					SELECT
						broker_nivel.nivel
					FROM
						broker_nivel
					WHERE
						broker_nivel.id_broker = broker.id_broker
					AND broker_nivel.prefijo = broker.prefijo
					LIMIT 1
				) AS nivel,
				(
					SELECT
						countries.description
					FROM
						countries
					WHERE
						countries.iso_country = broker.id_country
				) AS pais,
				(
					SELECT
						states.description
					FROM
						states
					WHERE
						states.iso_state = broker.id_state
				) AS estado,
				(
					SELECT
						cities.description
					FROM
						cities
					WHERE
						cities.iso_city = broker.id_city
				) AS ciudad
			FROM
				broker
			WHERE
				broker.prefijo = '$prefix'
			AND broker.id_broker = '$agency'
			ORDER BY
				id_new_broker DESC
			LIMIT 1",
				'',
				'',
				'',
				''
			);

			$response = [
				[
					"id_broker" 	   => $respons[0]['id_broker'],
					"dominio"          => $respons[0]['dominio'],
					"email_from"       => $respons[0]['email'],
					"email_cotizacion" => $respons[0]['email'],
					"skype"            => $respons[0]['skype'],
					"whatsapp" 		   => $respons[0]['code_phone1'] . $respons[0]['phone1'],
					"prefijo_ref" 	   => $respons[0]['prefijo_ref'],
					"instagram"        => $respons[0]['instagram'],
					"facebook" 		   => $respons[0]['facebook'],
					"pagina_web"       => $respons[0]['pagina_web'],
					"prefijo"          => $respons[0]['prefijo'],
					"enlace_app_android" => $respons[0]['enlace_app_android'],
					"enlace_app_apple" => $respons[0]['enlace_app_apple'],
					"email_from_name"  => $respons[0]['email_from_name'],
					"nivel"            => $respons[0]['nivel'],
					"nombreAgencia"    => $respons[0]['broker'],
					"direccion"        => $respons[0]['address'],
					"pais"   		   => $respons[0]['pais'],
					"estado"		   => $respons[0]['estado'],
					"ciudad"           => $respons[0]['ciudad']
				]
			];
		}

		if (!empty($response[0]['whatsapp'])) {
			switch (strpos($response[0]['whatsapp'], '+')) {
				case true:
					$response[0]['whatsapp'] = '+' . $response[0]['whatsapp'];
					break;

				default:
					$response[0]['whatsapp'] = $response[0]['whatsapp'];
					break;
			}
		}

		return $response;
	}

	public function getCuponDescuento($filters)
	{
		$prefix 	  = $filters['prefix'];
		$userType 	  = $filters['userType'];
		$id_user	  = $filters['id_user'];
		$id_broker    = ($filters['agency'] != 'N/A' && !empty($filters['agency'])) ? $filters['agency'] : 118;
		$idPlan       = $filters['idPlan'];
		$cupon        = $filters['cupon'];
		$subTotal     = str_replace(',', '', $filters['subTotal']);
		$destino      = $filters['destino'];
		$numpasajeros = $filters['numpasajeros'];
		$moneda_local = $filters['moneda_local'];
		$tasa_cambio  = $filters['tasa_cambio'];
		$lang_app     = "es";
		$lang_app     = $this->funcLangAppShort($this->funcLangApp());

		$dataValida	= [
			"9092"  => $prefix,
			"50005" => $id_user,
			"5022"  => $idPlan,
			'50018'	=> $cupon,
			'50019'	=> $subTotal,
			'50007'	=> $destino,
			'50020'	=> $numpasajeros
		];

		$this->validatEmpty($dataValida);

		$dataQuote = [
			'id_user'        => $id_user,
			'user_type'      => $userType,
			'broker_sesion'  => $id_broker, //parametro que recibe el core.lib de la plataforma para cargar los parametros de la agencia 
			'type'			 => 'descuento',
			'selectLanguage' => $lang_app,
			'cod_promocional' => $cupon,
			'monto_cancelar' => $subTotal,
			'iduser'         => $id_user,
			'IdUser_type'    => $userType,
			'destino'        => $destino,
			'plan_producto'  => $idPlan,
			'id_broker'      => $id_broker,
			'numpasajeros'   => $numpasajeros,
			'moneda_local'   => $moneda_local,
			'tasa_cambio'    => $tasa_cambio
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkQuote 	= $link . "/app/pages/async_cotizador.php";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$resp = $this->curlGeneral($linkQuote, $dataQuote, $headers, 'GET');
		$resp = json_decode($resp, true);

		if ($resp['STATUS'] == 'OK') {

			if (strtoupper(substr($cupon, 0, 2)) == 'NC') {

				$dataCurl = [
					'querys' => "SELECT
									id,
									monto_nc 
								FROM
									credit_note
								INNER JOIN orders ON orders.codigo = credit_note.nro_voucher
								WHERE
									credit_note.nro_notaCredito = '$cupon'
								AND (
									ISNULL(orders.USE_nro_notaCredito)
									OR orders.USE_nro_notaCredito = '')"
				];

				$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
				$linkplatf = $link . "/app/api/selectDynamic";
				$headers 	= "content-type: application/x-www-form-urlencoded";
				$respuesta = $this->curlGeneral($linkplatf, json_encode($dataCurl), $headers);
				$resp2 = json_decode($respuesta, true);

				$resp['VALUE_CUPON'] 		= (float) $resp2[0]['monto_nc'];
				$resp['TIPO_CALC']   		= 'monto';
				$resp['SUBTOTAL']    		= (float) $subTotal;
				$resp['CODIGO']          	= $cupon;
				$resp['NOMBRE_AMIGABLE'] 	= $cupon;
				$resp['ID_NC']        	 	= $resp2[0]['id'];
				$resp['NOMBRE_INGRESADO'] 	= $cupon;
				return $resp;
			} else {
				$dataCurl = [
					'querys' => "SELECT
					coupons.id,
					codigo,
					codigo_secundario,
					porcentaje,
					credit_amount
				FROM
					coupons
				WHERE
					(codigo = '$cupon'
				OR codigo_secundario = '$cupon')
				AND coupons.id_status = 1 "
				];

				$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
				$linkplatf = $link . "/app/api/selectDynamic";
				$headers 	= "content-type: application/x-www-form-urlencoded";
				$respuesta = $this->curlGeneral($linkplatf, json_encode($dataCurl), $headers);
				$resp2 = json_decode($respuesta, true);

				if ((float) $resp2[0]['porcentaje'] > 0) {
					$resp['VALUE_CUPON'] 	= (float) $resp2[0]['porcentaje'];
					$resp['TIPO_CALC']   	= '%';
					$resp['SUBTOTAL']    	= (float) $subTotal;
				} else {
					$resp['VALUE_CUPON'] 	= (float) $resp2[0]['credit_amount'];
					$resp['TIPO_CALC']   	= 'monto';
					$resp['SUBTOTAL']    	= (float) $subTotal;
				}
				$resp['CODIGO']          	= $resp2[0]['codigo'];
				$resp['NOMBRE_AMIGABLE'] 	= !empty($resp2[0]['codigo_secundario']) ? $resp2[0]['codigo_secundario'] : $resp2[0]['codigo'];
				$resp['ID_CUPON']        	= $resp2[0]['id'];
				$resp['NOMBRE_INGRESADO'] 	= $cupon;
				return $resp;
			}
		} else {
			$resp['NOMBRE_INGRESADO']   = $cupon;
			return $resp;
		}
	}

	public function getCuponDescuentoNC($filters)
	{
		$prefix 	  = $filters['prefix'];
		$userType 	  = $filters['userType'];
		$id_user	  = $filters['id_user'];
		$id_broker    = ($filters['agency'] != 'N/A' && !empty($filters['agency'])) ? $filters['agency'] : 118;
		$idPlan       = $filters['idPlan'];
		$cupon        = $filters['cupon'];
		$cupones      = explode(',', $cupon);
		$subTotal     = str_replace(',', '', $filters['subTotal']);
		$destino      = $filters['destino'];
		$numpasajeros = $filters['numpasajeros'];
		$moneda_local = $filters['moneda_local'];
		$tasa_cambio  = $filters['tasa_cambio'];
		$lang_app     = "es";
		$lang_app     = $this->funcLangAppShort($this->funcLangApp());
		$descOrdenados = [];

		$dataValida	= [
			"9092"  => $prefix,
			"50005" => $id_user,
			"5022"  => $idPlan,
			'50018'	=> $cupon,
			'50019'	=> $subTotal,
			'50007'	=> $destino,
			'50020'	=> $numpasajeros,
			'50035' => !(count($cupones) > 2)
		];

		$this->validatEmpty($dataValida);

		if (strtoupper(substr($cupones[0], 0, 2)) == 'NC') {
			$descOrdenados['CUPON']		= $cupones[1];
			$descOrdenados['NC']		= $cupones[0];
		} else {
			$descOrdenados['CUPON']		= $cupones[0];
			$descOrdenados['NC']		= $cupones[1];
		}
		if (strtoupper(substr($cupones[0], 0, 2)) == 'NC' && strtoupper(substr($cupones[1], 0, 2)) == 'NC') {
			$descOrdenados['NC']		= $cupones[0];
			unset($descOrdenados['CUPON']);
		}
		if (strtoupper(substr($cupones[0], 0, 2)) != 'NC' && strtoupper(substr($cupones[1], 0, 2)) != 'NC') {
			$descOrdenados['CUPON']		= $cupones[0];
			unset($descOrdenados['NC']);
		}
		$descOrdenados = array_filter($descOrdenados);

		$dataQuote = [
			'id_user'        	=> $id_user,
			'user_type'      	=> $userType,
			'broker_sesion'  	=> $id_broker, //parametro que recibe el core.lib de la plataforma para cargar los parametros de la agencia 
			'type'			 	=> 'descuento',
			'selectLanguage' 	=> $lang_app,
			'cod_promocional' 	=> $cupon,
			'monto_cancelar' 	=> $subTotal,
			'iduser'         	=> $id_user,
			'IdUser_type'    	=> $userType,
			'destino'        	=> $destino,
			'plan_producto'  	=> $idPlan,
			'id_broker'      	=> $id_broker,
			'numpasajeros'   	=> $numpasajeros,
			'moneda_local'   	=> $moneda_local,
			'tasa_cambio'    	=> $tasa_cambio
		];

		foreach ($descOrdenados as $key => &$value) {
			$dataQuote['cod_promocional'] = $descOrdenados[$key];
			$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
			$linkQuote 	= $link . "/app/pages/async_cotizador.php";
			$headers 	= "content-type: application/x-www-form-urlencoded";
			$resp[$key]   = json_decode($this->curlGeneral($linkQuote, $dataQuote, $headers, 'GET'), true);
		}

		if ($resp['NC']['STATUS'] == 'OK') { /////////si es nota de credito

			$dataCurl = [
				'querys' => "SELECT
					*
				FROM
					orders
				where USE_nro_notaCredito = '{$descOrdenados['NC']}'
				AND status IN(1, 2, 3, 6)
				limit 1"
			];

			$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
			$linkplatf  = $link . "/app/api/selectDynamic";
			$headers 	= "content-type: application/x-www-form-urlencoded";
			$respuesta  = $this->curlGeneral($linkplatf, json_encode($dataCurl), $headers);
			$respNCDisp = json_decode($respuesta, true);

			if (empty($respNCDisp)) { /////aqui se valida si el cupon no ha sido aplicado para retornar la data correcta 
				$dataCurl = [
					'querys' => "SELECT
									id,
									monto_nc 
								FROM
									credit_note
								INNER JOIN orders ON orders.codigo = credit_note.nro_voucher
								WHERE
									credit_note.nro_notaCredito = '{$descOrdenados['NC']}'
								AND (
									ISNULL(orders.USE_nro_notaCredito)
									OR orders.USE_nro_notaCredito = '')"
				];

				$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
				$linkplatf = $link . "/app/api/selectDynamic";
				$headers 	= "content-type: application/x-www-form-urlencoded";
				$respuesta = $this->curlGeneral($linkplatf, json_encode($dataCurl), $headers);
				$resp1 = json_decode($respuesta, true);

				$resp['NC']['VALUE_CUPON'] 		= (float) $resp1[0]['monto_nc'];
				$resp['NC']['TIPO_CALC']   		= 'monto';
				$resp['NC']['SUBTOTAL']    		= (float) $subTotal;
				$resp['NC']['CODIGO']          	= $cupon;
				$resp['NC']['NOMBRE_AMIGABLE'] 	= $cupon;
				$resp['NC']['ID_NC']        	= $resp1[0]['id'];
				$resp['NC']['NOMBRE_INGRESADO'] = $descOrdenados['NC'];
				$resp['NC']['TEXT_APP'] 		= $descOrdenados['NC'];
			} else { ///// el cupon ya fue aplicado a una orden no se aplica de nuevo
				unset($resp['NC']);
				$resp['NC']['STATUS'] = 'ERROR';
				$resp['NC']['MSG'] = 'Nota de credito seleccionado no es valido o expiró';
			}
		}
		if ($resp['CUPON']['STATUS'] == 'OK') { ///////////////si es cupon
			$dataCurl = [
				'querys' => "SELECT
								coupons.id,
								codigo,
								codigo_secundario,
								porcentaje,
								credit_amount
							FROM
								coupons
							WHERE
								(codigo = '{$descOrdenados['CUPON']}'
							OR codigo_secundario = '{$descOrdenados['CUPON']}')
							AND coupons.id_status = 1 "
			];

			$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
			$linkplatf = $link . "/app/api/selectDynamic";
			$headers 	= "content-type: application/x-www-form-urlencoded";
			$respuesta = $this->curlGeneral($linkplatf, json_encode($dataCurl), $headers);
			$resp2 = json_decode($respuesta, true);

			if ((float) $resp2[0]['porcentaje'] > 0) {
				$resp['CUPON']['VALUE_CUPON'] 	= (float) $resp2[0]['porcentaje'];
				$resp['CUPON']['TIPO_CALC']   	= '%';
				$resp['CUPON']['SUBTOTAL']    	= (float) $subTotal;
			} else {
				$resp['CUPON']['VALUE_CUPON'] 	= (float) $resp2[0]['credit_amount'];
				$resp['CUPON']['TIPO_CALC']   	= 'monto';
				$resp['CUPON']['SUBTOTAL']    	= (float) $subTotal;
			}
			$resp['CUPON']['CODIGO']          	= $resp2[0]['codigo'];
			$resp['CUPON']['NOMBRE_AMIGABLE'] 	= !empty($resp2[0]['codigo_secundario']) ? $resp2[0]['codigo_secundario'] : $resp2[0]['codigo'];
			$resp['CUPON']['ID_CUPON']        	= $resp2[0]['id'];
			$resp['CUPON']['NOMBRE_INGRESADO'] 	= $descOrdenados['CUPON'];
			$resp['CUPON']['TEXT_APP'] 			= $descOrdenados['CUPON'];
		} else {
			return $resp;
		}
		return $resp;
	}

	public function getDatosUser($filters)
	{
		$prefix  = $filters['prefix'];
		$id_user = $filters['id_user'];

		$dataValida	= [
			"50005" => $id_user,
			"9092"  => $prefix
		];

		$this->validatEmpty($dataValida);

		$select = [
			"id",
			"firstname",
			"lastname",
			"s_first",
			"email",
			"code_phone",
			"phone",
			"user_type",
			"(
				SELECT
					countries.description
				FROM
					countries
				WHERE
					users_extern.id_country = countries.iso_country
				LIMIT 1
			) AS pais",
			"users_extern.prefijo"
		];

		$where = [
			'id'        => $id_user,
			'prefijo'   => $prefix,
			'id_status' => 1
		];

		$order = [
			"field" => "id_users",
			"order" => "DESC"
		];

		$response = $this->selectDynamic($where, 'users_extern', '1', $select, false, '', $order, '', '');

		switch ($response[0]['user_type']) {
			case '1':
				$response[0]['user_type'] = 'MASTER';
				break;

			case '2':
				$response[0]['user_type'] = 'BROKER ADMIN';
				break;

			case '5':
				$response[0]['user_type'] = 'BROKER ACCESS';
				break;

			case '13':
				$response[0]['user_type'] = 'ADMIN';
				break;

			default:
				$response[0]['user_type'] = $response[0]['user_type'];
				break;
		}

		return $response;
	}

	public function getCategories($filters)
	{
		$prefix 	= $filters['prefix'];
		$agencia    = $filters['agencyMaster'];
		$language	= $this->funcLangApp();

		$dataValida	= [
			"9092"  => $prefix
		];

		$this->validatEmpty($dataValida);

		$query = "SELECT
			REPLACE( plan_categoria_detail.name_plan,'''','') as name_plan ,
			plan_categoria_detail.id_plan_categoria ,
			REPLACE( plan_categoria_detail.description_plan,'''','') AS description_plan,
			plan_category.img AS img
		FROM
			plans
		INNER JOIN plan_category ON plan_category.id_plan_categoria = plans.id_plan_categoria
		AND plans.eliminado = 1
		AND plans.activo = 1
		INNER JOIN plan_categoria_detail ON plan_category.id_plan_categoria = plan_categoria_detail.id_plan_categoria
		LEFT JOIN restriction ON plans.id = restriction.id_plans
		WHERE
			plan_categoria_detail.language_id = '$language'
		AND plan_category.vision_id = 1
		AND plan_category.id_status = 1";
		if ($agencia != 'N/A' && !empty($agencia)) {
			$query .= " AND (
				(
					restriction.id_broker IN ('$agencia')
					AND restriction.dirigido IN ('6', '2')
				)
				OR (
					restriction.dirigido = '0'
					OR restriction.dirigido IS NULL
					OR restriction.dirigido = '1'
					OR 
						restriction.id_broker IN ('$agencia')
						AND restriction.dirigido IN ('6', '2')
				)
			) ";
		} else {
			if (in_array($prefix, ['AF', 'TH', 'TK', 'VY'])) { ///Plataformas que si poseen esta restriccion
				$query .= " AND (
					restriction.dirigido = '0'
					OR restriction.dirigido IS NULL
					OR restriction.dirigido = '1'
				) ";
			}
		}
		$query .= " AND (
			modo_plan = 'N'
			OR modo_plan = 'W'
			OR modo_plan = 'T'
			OR modo_plan IS NULL
		)
		GROUP BY
			plan_category.id_plan_categoria
		ORDER BY
			plan_category.orden ASC";

		$data = [
			'querys' => $query
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkParam 	= $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$response = $this->curlGeneral($linkParam, json_encode($data), $headers);
		$respuesta = json_decode($response, true);
		for ($i = 0; $i < count($respuesta); $i++) {
			if (!empty($respuesta[$i]['img'])) {
				$respuesta[$i]['img'] = $link . '/app/images/images/' . $respuesta[$i]['img'];
				$respuesta[$i]['description_plan'] = strip_tags($respuesta[$i]['description_plan']);
			} else {
				$respuesta[$i]['img'] = '';
				$respuesta[$i]['description_plan'] = strip_tags($respuesta[$i]['description_plan']);
			}
		}
		return $respuesta;
		//return $this->dataCategories($prefix);
	}

	public function logoutApp()
	{
		return ['Session Cerrada'];
	}

	public function getIntervaloFechas($filters)
	{
		$idCategory = $filters['idCategory'];
		$paisOrigen = $filters['paisOrigen'];
		$prefix     = $filters['prefix'];

		$dataValida	= [
			"9092"  => $prefix,
			"9094"  => $idCategory,
			"6027"  => $paisOrigen
		];

		$this->validatEmpty($dataValida);

		$response = $this->intervaloDias($prefix, $idCategory, $paisOrigen);
		switch (true) {
			case ($response[0]['type_category'] == 'MULTI_TRIP' || $response[0]['type_category'] == 3):
				$bloquesMultiViaje = $this->bloquesMultiViajes($prefix);

				return [
					[
						'dias_min'          	=> (int) '365',
						'dias_max'          	=> (int) '365',
						'id_plan_categoria' 	=> (int) $response[0]['id_plan_categoria'],
						'type_category'     	=> 'MULTI_TRIP',
						'bloques_multi_viajes' 	=> (!empty($bloquesMultiViaje) && !$bloquesMultiViaje['ERROR_CODE'])  ? $bloquesMultiViaje : $bloquesMultiViaje = [['dias_multiviajes' => 365]]
					]
				];
				break;
			default:
				if (!empty($response[0]['dias_min']) && !empty($response[0]['dias_max']) && (int) $response[0]['dias_min'] == (int) $response[0]['dias_max'] && (int) $response[0]['dias_max'] < 365) {
					(int) $response[0]['dias_max'] = (int) $response[0]['dias_min'] + 1;
				}
				return [
					[
						'dias_min'          	=> (int) $response[0]['dias_min'] ?: 1,
						'dias_max'          	=> (int) $response[0]['dias_max'] ?: 120,
						'id_plan_categoria' 	=> (int) $response[0]['id_plan_categoria'],
						'type_category'     	=> $response[0]['type_category'],
					]
				];
				break;
		}
	}

	public function getIntervaloDeEdades($filters)
	{
		$idCategory = $filters['idCategory'];
		$country    = $filters['country'];
		$idPlan     = $filters['idPlan'];
		$prefix     = $filters['prefix'];

		$dataValida	= [
			"9092"  => $prefix,
			"9094"  => $idCategory,
			"6027"  => $country
		];

		$this->validatEmpty($dataValida);

		$intervalos = $this->plans_intervalos_edades($idCategory, $country, '', $prefix);

		return [$intervalos];
	}

	public function getStatusCreditAgency($filters)
	{
		$prefix	   = $filters['prefix'];
		$id_broker = ($filters['agency'] != 'N/A' && !empty($filters['agency'])) ? $filters['agency'] : 118;

		$dataValida	= [
			"9092"  => $prefix
		];

		$this->validatEmpty($dataValida);

		$sql = "SELECT
				fecha_credito,
				credito_actual,
				credito_base,
				id_broker,
				broker,
				id_bmi,
				forma_pago
			FROM
				broker
			WHERE
			broker.id_broker = '$id_broker'
			AND broker.id_status = '1'";

		$dataCurl = [
			"querys" => $sql
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkParam 	= $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		return $response   = json_decode($this->curlGeneral($linkParam, json_encode($dataCurl), $headers), true);
	}

	public function GetPricesApiQuoteGeneral($filters)
	{
		$prefix	   = $filters['prefix'];
		$origin	   = $filters['origin'];
		$startDate = $filters['startDate'];
		$endDate   = $filters['endDate'];
		$destiny   = $filters['destiny'];
		$category  = $filters['category'];
		$id_broker = ($filters['agency'] != 'N/A' && !empty($filters['agency'])) ? $filters['agency'] : 118;
		$ages	   = explode(',', $filters['ages']);
		$bloque    = $filters['bloque'] ?: '';
		$today 	   = $this->datePlatform($prefix)[0]['date']; //Obtengo la fecha del servidor que cotizo mas no la de ils
		$lang_app  = "es";
		$id_plan_cotiza = (!empty($filters['idPlan'])) ? $filters['idPlan'] : '';
		$lang_app  = $this->funcLangAppShort($this->funcLangApp());
		$dataPreOrdn = $filters['dataPreOrder'];

		$dataValida	= [
			"9092"  => $prefix,
			"6027"  => $origin,
			"1080"  => !empty($destiny) ?: true,
			"6029"  => $startDate,
			"9094"  => $category,
			"6030"  => $endDate,
			'2001'	=> $this->checkDates($startDate),
			'2002'	=> $this->checkDates($endDate),
			'9095'	=> is_array($ages)
		];
		for ($i = 0; $i < count($ages); $i++) { //verifica si envia una edad como 05 o 075 para poder informar al cliente la edad 0 es valida 
			if ((substr((string) $ages[$i], 0, 1) == '0') && ((string) strlen($ages[$i]) > 1)) {
				$dataValida['50017'] = false;
			}
		}
		if (!empty($startDate) && !empty($endDate)) {
			$startDate  = $this->transformerDate($startDate);
			$endDate 	= $this->transformerDate($endDate);
		}
		$arrVerifyDate = [
			'3030'	=> !(strtotime($startDate) > strtotime($endDate)),
			'50009'	=> !(strtotime($endDate)   < strtotime($today)),
			'50008'	=> !(strtotime($startDate) < strtotime($today)),
		];
		$this->validatEmpty($dataValida + $arrVerifyDate);
		$interval = $this->betweenDates($startDate, $endDate, '');

		if (in_array($prefix, ['TC'])) {
			$ages = implode(',', $ages);
		} else {
			$ages = implode(',', $ages) . ',';
		}

		$dataCurl = [
			'plan_tipo'         =>  $category,
			'dias'              =>  $interval,
			'id_country_broker' =>  '',
			'destino'           =>  $destiny,
			'origen'            =>  $origin,
			'edades'            =>  $ages,
			'salida'            =>  $startDate,
			'llegada'           =>  $endDate,
			'id_broker'         =>  $id_broker,
			'PlanSel'           =>  '',
			'min_days'          =>  $bloque,
			'selectLanguage'    =>  $lang_app,
			'id_plan_cotiza'    =>  $id_plan_cotiza
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkParam 	= $link . "/app/api/quotePlansApp";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$respons    = $this->curlGeneral($linkParam, json_encode($dataCurl), $headers);
		$response   = json_decode($respons, true);

		foreach ($response as $key => &$value) {
			$idPlans[] = $value['idp'];
		}

		$idPlans = implode(",", $idPlans);

		if (!empty($idPlans)) {
			$queryPlanHabit = "SELECT
							id,
							dir_habitacion
						FROM
							plans
						WHERE
							prefijo = '$prefix'
						AND id IN (" . $idPlans . ")";

			$resultHabit = $this->selectDynamic('', '', '', '', $queryPlanHabit, '', '', '');
		}

		for ($i = 0; $i < count($response); $i++) { ///aqui traigo el parametro para saber si tiene direccion de habitacion o no
			for ($a = 0; $a < count($resultHabit); $a++) {
				if ($response[$i]['idp'] == $resultHabit[$a]['id'] && $resultHabit[$a]['dir_habitacion'] == 'Y') {
					$response[$i]['dir_habit'] = 'Y';
				} else {
					$response[$i]['dir_habit'] = 'N';
				}
			}
		}

		$PreOrd = $this->preOrderApp($dataPreOrdn);

		for ($i = 0; $i < count($response); $i++) { //Simplifica precios
			$response[$i]['normal_age'] ? $response[$i]['normal_age'] = (int) $response[$i]['normal_age'] : '';
			$response[$i]['max_age']    ? $response[$i]['max_age']    = (int) $response[$i]['max_age'] : '';
			$response[$i]['min_age']    ? $response[$i]['min_age']    = (int) $response[$i]['min_age'] : '';
			$response[$i]['preOrden'] = json_decode($PreOrd, true);
			$response[$i]['total']    = substr($response[$i]['total'], 0, strpos($response[$i]['total'], '.') + 3);
			$response[$i]['USDTotal'] ? ($response[$i]['USDTotal'] = substr($response[$i]['USDTotal'], 0, strpos($response[$i]['USDTotal'], '.') + 3)) : ($response[$i]['USDTotal'] = $response[$i]['total']);
			strtoupper($response[$i]['moneda_local'])  == 'Y' ? $response[$i]['tasa_cambio'] = (float) $response[$i]['tasa_cambio'] : $response[$i]['tasa_cambio'] = (float) 1;
			if (!empty($response[$i]['arrUsedPrices'])) {
				if (count($response[$i]['arrUsedPrices']) > 1) { //si tiene menores y mayores
					if ($response[$i]['arrUsedPrices'][1]) { ////si es mayor
						$response[$i]['numero_mayores'] = $response[$i]['arrUsedPrices'][1]['numPas'];
						$response[$i]['valorMayor'] = $response[$i]['arrUsedPrices'][1]['pvpBase'];
						$response[$i]['subTotalMayor'] = $response[$i]['arrUsedPrices'][1]['pvpSubTotal'];
					}
					if ($response[$i]['arrUsedPrices'][0]) { ///si es menor
						$response[$i]['numero_menores'] = $response[$i]['arrUsedPrices'][0]['numPas'];
						$response[$i]['valorMenor'] = $response[$i]['arrUsedPrices'][0]['pvpBase'];
						$response[$i]['subTotalMenores'] = $response[$i]['arrUsedPrices'][0]['pvpSubTotal'];
					}
				} else {
					if ($response[$i]['arrUsedPrices'][0]['ageMin'] > $response[$i]['normal_age']) { ///si tiene solo mayores
						$response[$i]['numero_mayores'] = $response[$i]['arrUsedPrices'][0]['numPas'];
						$response[$i]['valorMayor'] = $response[$i]['arrUsedPrices'][0]['pvpBase'];
						$response[$i]['subTotalMayor'] = $response[$i]['arrUsedPrices'][0]['pvpSubTotal'];
					}
					if ($response[$i]['arrUsedPrices'][0]['ageMin'] <= $response[$i]['normal_age']) { ///si tiene solo menores
						$response[$i]['numero_menores'] = $response[$i]['arrUsedPrices'][0]['numPas'];
						$response[$i]['valorMenor'] = $response[$i]['arrUsedPrices'][0]['pvpBase'];
						$response[$i]['subTotalMenores'] = $response[$i]['arrUsedPrices'][0]['pvpSubTotal'];
					}
				}
				if (count($response[$i]['arrPrices']) > 0) {
					$response[$i]['calc_new'] = 'Y'; /////parametro para saber si son calculos nuevos 
				}
			} else {
				//////calcular bien el precio de costo por pasajero y costo toal cuando aplica plan pareja
				if ($response[$i]['planpareja'] == 1) {
					$response[$i]['costoMenor'] = (($response[$i]['costoMenor'] * $response[$i]['factor_pareja']) / $response[$i]['numero_menores']);
					$response[$i]['total_costo'] = ($response[$i]['costoMenor'] * $response[$i]['numero_menores']);
					$response[$i]['subTotalMenores_costo'] = ($response[$i]['costoMenor'] * $response[$i]['numero_menores']);
					$response[$i]['subTotalMenores_costo'] = substr($response[$i]['subTotalMenores_costo'], 0, strpos($response[$i]['subTotalMenores_costo'], '.') + 3);
					$response[$i]['costoMenor'] = substr($response[$i]['costoMenor'], 0, strpos($response[$i]['costoMenor'], '.') + 3);
					$response[$i]['total_costo'] = substr($response[$i]['total_costo'], 0, strpos($response[$i]['total_costo'], '.') + 3);
					if (!empty($id_plan_cotiza)) {
						///////// proceso para cuadrar precios de los pasajeros con 2 decimales y poder cuadrar el total y el subtotal de menores
						$response[$i]['total'] = ($response[$i]['total'] / $response[$i]['numero_menores']);
						$response[$i]['total'] = substr($response[$i]['total'], 0, strpos($response[$i]['total'], '.') + 3);
						$response[$i]['total'] = ($response[$i]['total'] * $response[$i]['numero_menores']);
						$response[$i]['total'] = substr($response[$i]['total'], 0, strpos($response[$i]['total'], '.') + 3);
						$response[$i]['subTotalMenores'] = $response[$i]['total'];
						$response[$i]['USDTotal'] = $response[$i]['total'];
					}
				}
				//////calcular bien el precio de costo por pasajero y costo toal cuando aplica plan familiar
				elseif ($response[$i]['planfamiliar'] == 1) {
					$response[$i]['costoMenor'] = (($response[$i]['costoMenor'] * $response[$i]['factor_family']) / $response[$i]['numero_menores']);
					$response[$i]['total_costo'] = ($response[$i]['costoMenor'] * $response[$i]['numero_menores']);
					$response[$i]['subTotalMenores_costo'] = ($response[$i]['costoMenor'] * $response[$i]['numero_menores']);
					$response[$i]['subTotalMenores_costo'] = substr($response[$i]['subTotalMenores_costo'], 0, strpos($response[$i]['subTotalMenores_costo'], '.') + 3);
					$response[$i]['costoMenor'] = substr($response[$i]['costoMenor'], 0, strpos($response[$i]['costoMenor'], '.') + 3);
					$response[$i]['total_costo'] = substr($response[$i]['total_costo'], 0, strpos($response[$i]['total_costo'], '.') + 3);
				}
			}

			///////////switch para la moneda en la cotizacion
			switch (true) {
				case $response[$i]['moneda'] == 'US$':
					$response[$i]['moneda_paypal'] = 'USD';
					break;

				case $response[$i]['moneda'] == 'EUR':
					$response[$i]['moneda_paypal'] = 'EUR';
					break;

				default:
					$response[$i]['moneda_paypal'] = 'USD';
					break;
			}
		}

		if ($prefix == 'BT') { //Aplica para BTA
			for ($i = 0; $i < count($response); $i++) {
				if (empty($response[$i]['numero_menores'])) {
					$response[$i]['numero_menores'] = $response[$i]['arrUsedPrices'][0]['numPas'];
				}
				if (empty($response[$i]['valorMenor'])) {
					$response[$i]['valorMenor'] = $response[$i]['arrPrices'][0]['pvp'];
				}
				if (empty($response[$i]['subTotalMenores'])) {
					$response[$i]['subTotalMenores'] = $response[$i]['USDTotal'];
				}
			}
		}

		if ($prefix == 'ME') { //Aplica para Meridional
			for ($i = 0; $i < count($response); $i++) {
				if (empty($response[$i]['numero_menores'])) {
					$response[$i]['numero_menores'] = $response[$i]['arrUsedPrices'][0]['numPas'];
				}
				if (!empty($response[$i]['arrUsedPrices'][1])) { //PRECIOS PARA MAYORES
					$response[$i]['valorMayor'] = $response[$i]['arrUsedPrices'][1]['pvp'];
					$response[$i]['subTotalMayor'] = $response[$i]['arrUsedPrices'][1]['pvpSubTotal'];
					$response[$i]['numero_mayores'] = $response[$i]['arrUsedPrices'][1]['numPas'];
				}
				if (empty($response[$i]['valorMenor'])) {
					$response[$i]['valorMenor'] = $response[$i]['arrPrices'][0]['pvp'];
				}
				if (empty($response[$i]['subTotalMenores'])) {
					$response[$i]['subTotalMenores'] = $response[$i]['arrPrices'][2]['pvp'];
				}
			}
		}

		foreach ($response as $key => &$value) {
			if (empty($value['total'])) {
				if ($value['error_age'] == 0) {
					return $this->getError(50010);
				} elseif ($value['error_broker'] == 0) {
					return $this->getError(50011);
				} elseif ($value['error_country'] == 0) {
					return $this->getError(50012);
				} elseif ($value['error_time'] == 0) {
					return $this->getError(50013);
				} elseif ($value['error_territory'] == 0) {
					return $this->getError(50014);
				} elseif ($value['error_cant_passenger'] == 0) {
					return $this->getError(50015);
				} elseif ($value['error_local_plans'] == 0) {
					return $this->getError(50016);
				} elseif (count($response) == 0) {
					return $this->getError(1060);
				}
			} else {
				//$this->ordenarArray($response, 'name_plan'); //ordenar por nombre
				return $response;
			}
		}
	}

	public function getListMethodPagoApp($filters)
	{
		$prefix	   = $filters['prefix'];

		$dataValida	= [
			"9092"  => $prefix
		];

		$this->validatEmpty($dataValida);

		$dataCurl = [
			"querys" => "SELECT
			parameter_key,
			parameter_value
		FROM
			parameters
		WHERE
			parameter_key IN (
				'PAY_CREDIT_CARD',
				'USE_PAYPAL',
				'SHIPPING_LINK',
				'REFERENCIA_QUOTE',
				'PAYMENT_MANAGER_MESSAGE'
			)"
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkParam 	= $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$response   = json_decode($this->curlGeneral($linkParam, json_encode($dataCurl), $headers), true);

		for ($i = 0; $i < count($response); $i++) {
			if ($response[$i]['parameter_key'] == 'PAY_CREDIT_CARD' && (int) $response[$i]['parameter_value'] >= 1) {
				$respons['PAY_CREDIT_CARD'] =  1;
			}
			if ($response[$i]['parameter_key'] == 'USE_PAYPAL' && (int) $response[$i]['parameter_value'] == 1) {
				$respons['USE_PAYPAL'] = 1;
				$dataCurl = ['querys' => "SELECT
											parameter_key,
											parameter_value
											FROM
											parameters
											WHERE
											parameter_key = 'PAYPAL_CLIENT_ID'
												AND show_parameter = 1"];

				$response   = json_decode($this->curlGeneral($linkParam, json_encode($dataCurl), $headers), true);
				$respons['CREDENTIAL_PAYPAL'] = $response[0]['parameter_value'];
			}
			if ($response[$i]['parameter_key'] == 'SHIPPING_LINK' && (int) $response[$i]['parameter_value'] == 1) {
				$respons['SHIPPING_LINK'] = 1;
			}
			if ($response[$i]['parameter_key'] == 'REFERENCIA_QUOTE' && (int) $response[$i]['parameter_value'] == 1) {
				$respons['REFERENCIA_QUOTE'] = 1;
			}
			if ($response[$i]['parameter_key'] == 'PAYMENT_MANAGER_MESSAGE' && (int) $response[$i]['parameter_value'] == 1) {
				$respons['PAYMENT_MANAGER_MESSAGE'] = 1;
			}
		}

		return array_merge($response, $respons);
	}

	public function getTermCond($filters)
	{
		$prefix	   = $filters['prefix'];
		$lang	   = $this->funcLangApp();
		$idPlan    = $filters['idPlan'];

		$dataValida	= [
			"9092"  => $prefix,
			"6021"  => $lang
			//,"5022"  => $idPlan
		];
		$this->validatEmpty($dataValida);
		$dataCurl1 = [
			'lang'     => $lang,
			'typeCond' => 'CONDICIONADO_GENERAL',
			'idPlan'   => $idPlan
		];
		$dataCurl2 = [
			'lang'     => $lang,
			'typeCond' => 'POLITICAS_PRIVACIDAD'
		];
		$dataCurl3 = [
			'lang'     => $lang,
			'typeCond' => 'POLITICAS_RENOVACION'
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkParam 	= $link . "/app/api/termCondApp";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$respons1    = json_decode($this->curlGeneral($linkParam, json_encode($dataCurl1), $headers), true);
		$respons2    = json_decode($this->curlGeneral($linkParam, json_encode($dataCurl2), $headers), true);
		$respons3    = json_decode($this->curlGeneral($linkParam, json_encode($dataCurl3), $headers), true);

		$response = [
			'COND_GEN' => $respons1,
			'POL_PRIV' => $respons2,
			'POL_REN'  => $respons3
		];
		return $response;
	}

	public function getPrices($filters, $apikey)
	{
		$quote	   = new quoteils();
		$prefix	   = $filters['prefix'];
		$origin	   = $filters['origin'];
		$startDate = $filters['startDate'];
		$endDate   = $filters['endDate'];
		$destiny   = $filters['destiny'];
		$category  = $filters['category'];
		$ages	   = explode(',', $filters['ages']);
		$today 	   = date('Y-m-d');
		$dataValida	= [
			"9092"  => $prefix,
			"6027"  => $origin,
			"1080"  => !empty($destiny) ?: true,
			"6029"  => $startDate,
			"9094"  => $category,
			"6030"  => $endDate,
			'2001'	=> $this->checkDates($startDate),
			'2002'	=> $this->checkDates($endDate),
			'9095'	=> is_array($ages)
		];
		if (!empty($startDate) && !empty($endDate)) {
			$startDate  = $this->transformerDate($startDate);
			$endDate 	= $this->transformerDate($endDate);
		}
		$arrVerifyDate = [
			'3030'	=> !(strtotime($startDate) > strtotime($endDate)),
			'50009'	=> !(strtotime($endDate)   < strtotime($today)),
			'50008'	=> !(strtotime($startDate) < strtotime($today)),
		];
		$this->validatEmpty($dataValida + $arrVerifyDate);
		$interval = $this->betweenDates($startDate, $endDate, '');
		$dataQuote = $quote->Quote([
			'category'		=> $category,
			'days'			=> $interval,
			'destinoQuote'	=> $destiny,
			'origenQuote'	=> $origin,
			'edades'		=> $ages,
			'prefijo'		=> $prefix
		]);
		$dataPrices = [];
		foreach ($dataQuote as $key => $value) {
			$dataPrices[] = [
				'idPlan'        => $value['id'],
				'Price'			=> $value['totalShow'],
				'Nombre'		=> $value['title'],
				'Description'	=> $value['description'],
				'Couple'		=> $value['couple']["active"],
				'Family'		=> $value['family']["active"]
			];
		}
		return ($dataPrices) ? $dataPrices : $this->getError(1060);
	}
	function get_cod_phones()
	{
		$query = "SELECT
                    iso_country,
                    description,
                    phone AS cod_phone
                FROM
                    countries
                WHERE
                    phone IS NOT NULL ";
		$query .= " AND c_status = 'Y'";
		if (!empty($code_phone)) $query .= " AND iso_numeric IS NOT NULL";
		$query .= " ORDER BY description ASC ";
		$this->selectDynamic('', '', '', '', $query, '', '', '', '');
		$result = $this->_SQL_tool($this->SELECT, METHOD, $query);
		return array_reduce($result, function ($response, $element) {
			$split = array_map(function ($value) use ($element) {
				return [
					'cod_phone' => '+' . preg_replace('/[^0-9]+/', '', $value),
					'iso_country' => $element['iso_country'],
					'description' => $element['description'],
				];
			}, explode("and", $element['cod_phone']));
			return array_merge($response, $split);
		}, []);
		return $arrCountries;
	}
	public function getInformIls()
	{
		$query = "SELECT
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
					IFNULL(
						(
							SELECT
								description
							FROM
								cities
							WHERE
								iso_city = parameters.id_city
							AND iso_city > 0
							LIMIT 1
						),
						'N/A'
					) AS id_city,
					address_parameter,
					zip_code,
					REPLACE (
						REPLACE (phone1, '(', ''),
						')',
						''
					) AS phone1,
					REPLACE (
						REPLACE (phone2, '(', ''),
						')',
						''
					) AS phone2,
					REPLACE (
						REPLACE (phone3, '(', ''),
						')',
						''
					) AS phone3,
					REPLACE (
						REPLACE (phone4, '(', ''),
						')',
						''
					) AS phone4
				FROM
					parameters
				WHERE
					parameter_key IN (
						'SYSTEM_NAME',
						'EMAIL_FROM',
						'ID_WHATSAPP'
					)
				ORDER BY
					parameter_key ASC";
		return $this->selectDynamic('', '', '', '', $query, '', '', '', '');
	}
	public function getInformUserIls($filters)
	{
		$idUser = $filters['id_user'];
		$dataValida	= [
			'6040' => $idUser
		];
		$this->validatEmpty($dataValida);
		$query = "SELECT id, firstname, lastname, email, phone, cod_pais, user_type, id_country from users WHERE id = '$idUser' ";
		$response = $this->selectDynamic('', '', '', '', $query, '', '', '', '');

		switch ($response[0]['user_type']) {
			case '1':
				$response[0]['user_type'] = 'MASTER';
				break;

			case '2':
				$response[0]['user_type'] = 'BROKER ADMIN';
				break;

			case '5':
				$response[0]['user_type'] = 'BROKER ACCESS';
				break;

			case '13':
				$response[0]['user_type'] = 'ADMIN';
				break;
		}

		return $response;
	}
	////////////////////////////////////////////// GRAFICOS DE TODAS LAS AGENCIAS ////////////////////
	public function getGrafGenAgen($filters)
	{
		$startDate  = $filters['startDate'];
		$endDate   	= $filters['endDate'];
		$typeClient = $filters['typeClient'];
		$today 	   	= date('Y-m-d');
		$year       = date('Y');
		$dataValida	= [
			'3030'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($startDate) > strtotime($endDate)) : true,
			'9068'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($endDate)	> strtotime($today)) : true,
			'9069'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($startDate)	> strtotime($today)) : true,
		];
		$this->validatEmpty($dataValida);

		//AQUI SE CONSULTA LOS MESES TRANSCURRIDOS EN VENTAS PARA MOSTRAR LOS MESES DINAMICAMENTE EN LAS GRAFICAS
		$meses = "SELECT DISTINCT
				MONTH (orders.fecha) AS mes,
				DATE_FORMAT(orders.fecha, '%M') AS nameMes
			FROM
				orders
			JOIN clients ON clients.prefix = orders.prefijo
			WHERE
				orders. STATUS IN (1, 3)
			AND IFNULL(inactive_platform, 0) <> 2
			AND YEAR (orders.fecha) = '$year'
			AND clients.data_activa = 'SI'
			ORDER BY
				MONTH (orders.fecha) ASC";
		$respMeses = $this->selectDynamic('', '', '', '', $meses);

		//GRAFICA DRILLDOWN DE VENTAS DIARIAS DE TODAS LAS AGENCIAS
		$query1 = "SELECT
			prefijo,
			producto,
			IFNULL((
				SELECT
					plans. NAME
				FROM
					plans
				WHERE
					orders.producto = plans.id
				AND orders.prefijo = plans.prefijo
				LIMIT 1
			),'N/A') AS name_plan,
			client,
			COUNT(cantidad) AS cantidad
		FROM
			orders
		JOIN clients ON clients.prefix = orders.prefijo
		WHERE
			IFNULL(inactive_platform, 0) <> 2
		AND orders.fecha = '$today'
		AND orders. STATUS IN (1, 3)
		AND clients.data_activa = 'SI'
		AND orders.prefijo != 'IX' ";
		if (!empty($typeClient) && $typeClient != 'all') {
			$query1 .= " AND clients.type_platform = {$typeClient} ";
		}
		$query1 .= " GROUP BY
			prefijo,
			producto
		ORDER BY client";

		$respGraf1 = $this->selectDynamic('', '', '', '', $query1, '', '', '', '');
		foreach ($respGraf1 as &$element) {
			$sumatori[$element['prefijo']] += (int) $element['cantidad'] ?: 0;
			$clientsName[$element['prefijo']] = $element['client'];
			$drilldownRaw[$element['prefijo']][] = [$element['name_plan'], (int) $element['cantidad']];
		}
		foreach ($clientsName as $key => &$val) {
			$series[] = [
				'name' => $val,
				'y' => (int) $sumatori[$key],
				'drilldown' => $key,
			];
			$drilldown[] = [
				'name' => $val,
				'id' => $key,
				'data' => $drilldownRaw[$key],
			];
		}
		//GRAFICA CANTIDAD DE VOUCHERS VENDIDOS ANUALES DE HOME ILS 
		$query2 = "SELECT
			orders.prefijo,
			MONTH (orders.fecha) AS mes,
			COUNT(orders.cantidad) AS cantidad,
			clients.client AS description
		FROM
			orders
		JOIN clients ON clients.prefix = orders.prefijo
		WHERE
			orders. STATUS IN (1, 3)
		AND YEAR (orders.fecha) = '$year'
		AND IFNULL(inactive_platform, 0) <> 2
		AND clients.data_activa = 'SI'
		AND orders.prefijo != 'IX' ";
		if (!empty($typeClient) && $typeClient != 'all') {
			$query2 .= " AND clients.type_platform = {$typeClient} ";
		}
		$query2 .= "
		GROUP BY
			orders.prefijo,
			mes
		ORDER BY 
			description 
		ASC";
		$respGraf2 = $this->selectDynamic('', '', '', '', $query2, '', '', '', '');
		$mountDesc = [
			'01' => 'Enero',
			'02' => 'Febrero',
			'03' => 'Marzo',
			'04' => 'Abril',
			'05' => 'Mayo',
			'06' => 'Junio',
			'07' => 'Julio',
			'08' => 'Agosto',
			'09' => 'Septiembre',
			'10' => 'Octubre',
			'11' => 'Noviembre',
			'12' => 'Diciembre',
		];

		$mesMax = 0;
		foreach ($respGraf2 as &$element) {
			$clientsAnual[$element['prefijo']][(int) $element['mes']] = (int) $element['cantidad'] ?: 0;
			$clientsAnual[$element['prefijo']]['description'] = $element['description'];
			if ((int) $element['mes'] > $mesMax) {
				$mesMax = (int) $element['mes'];
			}
		}
		foreach ($clientsAnual as $key1 => &$val) {
			$seriesAnual = [];
			foreach ($mountDesc as $key2 => &$value) {
				if ($key2 <= ($mesMax > date('m') ? $mesMax : date('m'))) {
					$seriesAnual[] = (int) $val[(int) $key2] ?: 0;
				}
			}
			$clientAnual[] = [
				'name' => $val['description'],
				'data' => $seriesAnual,
			];
		}
		//VENTAS NETAS TODAS LAS AGENCIAS DEL AÑO ACTUAL
		$query3 = "SELECT
			orders.prefijo,
			MONTH (orders.fecha) AS mes,
			SUM(orders.neto_prov) AS neto,
			clients.client AS description
		FROM
			orders
		JOIN clients ON clients.prefix = orders.prefijo
		WHERE
			orders. STATUS IN (1, 3)
		AND IFNULL(inactive_platform, 0) <> 2
		AND clients.data_activa = 'SI'
		AND YEAR (orders.fecha) = '$year'
		AND orders.prefijo != 'IX' ";
		if (!empty($typeClient) && $typeClient != 'all') {
			$query3 .= " AND clients.type_platform = {$typeClient} ";
		}
		$query3 .= "
		GROUP BY
			orders.prefijo,
			mes
		ORDER BY description ASC";
		$respGraf3 = $this->selectDynamic('', '', '', '', $query3);

		foreach ($respMeses as &$element) {
			$Months[(int) $element['mes']] = $element['nameMes'];
		}
		$mesMax = 0;
		foreach ($respGraf3 as &$element) {
			$SalInt[$element['prefijo']]['ventas'][(int) $element['mes']] = (float) $element['neto'] ?: 0;
			$SalInt[$element['prefijo']]['description'] = $element['description'];
			if ((int) $element['mes'] > $mesMax) {
				$mesMax = (int) $element['mes'];
			}
		}
		foreach ($SalInt as &$val) {
			$setSal = [];
			foreach ($Months as $key2 => &$value) {
				if ($key2 <= ($mesMax > date('m') ? $mesMax : date('m'))) {
					$setSal[] = (float) $val['ventas'][(int) $key2] ?: 0;
				}
			}
			$AnSales[] = [
				'name' => $val['description'],
				'data' => $setSal,
			];
		}
		return [[$series, $drilldown], [$clientAnual], [$AnSales], $Months];
	}

	////////////////////////GRAFICOS PARA LA SEGUNDA PESTAÑA DE GENERAL/TOTAL HOME ILS
	public function getGrafGenAgenGeneral($filters)
	{
		$yearBus  = $filters['yearBus'];
		$mesBus   = $filters['mesBus'];
		$typeClient = $filters['typeClient'];
		$yearActual = date('Y');
		$lang_app   = $this->funcLangAppShort($this->funcLangApp());
		$dataValida	= [
			'50002'	=> $yearBus,
			'50003'	=> $mesBus,
		];

		$this->validatEmpty($dataValida);
		/////////////////query para consultar meses del a;o y poder graficar bien las graficas de columnas de higcharts
		$queryMeses = "SELECT DISTINCT
			MONTH (orders.fecha) AS mes,
			DATE_FORMAT(orders.fecha, '%M') AS nameMes
		FROM
			orders
		JOIN clients ON clients.prefix = orders.prefijo
		WHERE
			orders. STATUS IN (1, 3)
		AND IFNULL(inactive_platform, 0) <> 2
		AND YEAR (orders.fecha) = '$yearBus'
		AND clients.data_activa = 'SI'
		ORDER BY
			MONTH (orders.fecha) ASC";
		$respMeses = $this->selectDynamic('', '', '', '', $queryMeses, '', '', '', '');
		foreach ($respMeses as &$element) {
			$Months[(int) $element['mes']] = $element['nameMes'];
		}

		////////////////////GRAFICA DE TORTA DRILLDOWN DE AGENCIAS VOUCHERS ACTIVOS GENERAL/TOTAL
		$status = Clients::getLabelStAssist(false, $lang_app);
		$query = "SELECT
					orders.status,
					COUNT(orders.status) AS total,
					prefijo,
					clients.client
				FROM
					orders
				JOIN clients ON clients.prefix = orders.prefijo
				WHERE 1 
		AND orders.prefijo != 'IX' ";
		if ($mesBus == 'ALL') {
			$query .= "AND YEAR(orders.fecha) = '$yearBus' ";
		} else {
			$query .= "AND YEAR (orders.fecha) = '$yearBus'
			AND MONTH (orders.fecha) = '$mesBus' ";
		}
		if (!empty($typeClient) && $typeClient != 'all') {
			$query .= " AND clients.type_platform = {$typeClient} ";
		}
		$query .= " AND IFNULL(inactive_platform, 0) <> 2
				AND clients.data_activa = 'SI'
				GROUP BY
					status,
					orders.prefijo";

		$dataSql = $this->selectDynamic('', '', '', '', $query, '', '', '', '', '');
		foreach ($dataSql as &$element) {
			$ordersTot[$status[$element['status']]] += (int) $element['total'] ?: 0;
			$ordersSt[$status[$element['status']]] = $element['client'] ?: "N/A";
			$drillOrder[$status[$element['status']]][] = [$element['client'] ?: "N/A", (int) $element['total']];
		}
		$seriesOrd = [];
		$drilldownOrd = [];
		foreach ($ordersSt as $key => &$val) {
			$seriesOrd[] = [
				'name' => $key ?: 'N/A',
				'y' => (int) $ordersTot[$key],
				'drilldown' => $key ?: 'N/A',
			];
			$drilldownOrd[] = [
				'name' => $key ?: 'N/A',
				'id' => $key ?: 'N/A',
				'data' => $drillOrder[$key],
			];
		}
		///////////grafica de vouchers activos de netos 
		$query2 = "SELECT
			orders.prefijo,
			clients.client AS description,
			MONTH (orders.fecha) AS mes,
			SUM(orders.neto_prov) AS neto,
				(
					SELECT
						plan_category.name_plan
					FROM
						plans
					JOIN plan_category ON plan_category.id_plan_categoria = plans.id_plan_categoria
					AND plan_category.prefijo = plans.prefijo
					WHERE
						orders.producto = plans.id
					AND orders.prefijo = plans.prefijo
					LIMIT 1
				) AS category
			FROM
				orders
			JOIN clients ON clients.prefix = orders.prefijo
			WHERE
				clients.data_activa = 'SI'
			AND orders.`status` IN (1, 3)
			AND orders.prefijo != 'IX' ";
		if ($mesBus == 'ALL') {
			$query2 .= "AND YEAR(orders.fecha) = '$yearBus'";
		} else {
			$query2 .= "AND YEAR (orders.fecha) = '$yearBus'
				AND MONTH (orders.fecha) = '$mesBus'";
		}
		if (!empty($typeClient) && $typeClient != 'all') {
			$query2 .= " AND clients.type_platform = {$typeClient} ";
		}
		$query2 .= "AND IFNULL(inactive_platform, 0) <> 2
			GROUP BY
				orders.prefijo,
				category, mes
			ORDER BY
				description ASC";
		$respGraf2 = $this->selectDynamic('', '', '', '', $query2, '', '', '', '');
		$VouchNum = [];
		foreach ($respGraf2 as &$element) {
			$VouchNum[$element['description']] += (float) $element['neto'] ?: 0;
			$VouchNeto[$element['description']] = $element['category'];
			$drillNet[$element['description']][] = [$element['category'], (float) $element['neto']];
		}
		$seriesNeto = [];
		$drilldownNeto = [];
		foreach ($VouchNeto as $key => &$val) {
			$seriesNeto[] = [
				'name' => $key,
				'y' => (float) $VouchNum[$key],
				'drilldown' => $key,
			];
			$drilldownNeto[] = [
				'name' => $key,
				'id' => $key,
				'data' => $drillNet[$key],
			];
		}
		////////grafico de origenes
		$query3 = "SELECT
			orders.prefijo,
			clients.client,
			orders.origen,
			COUNT(orders.cantidad) AS cantidad,
			(
				SELECT
					description
				FROM
					countries
				WHERE
					iso_country = orders.origen
				LIMIT 1
			) AS country
		FROM
			orders
		JOIN clients ON clients.prefix = orders.prefijo
		WHERE
			clients.data_activa = 'SI'
		AND orders.`status` IN (1, 3)
		AND orders.prefijo != 'IX' ";
		if ($mesBus == 'ALL') {
			$query3 .= "AND YEAR(orders.fecha) = '$yearBus'";
		} else {
			$query3 .= "AND YEAR (orders.fecha) = '$yearBus'
			AND MONTH (orders.fecha) = '$mesBus'";
		}
		if (!empty($typeClient) && $typeClient != 'all') {
			$query3 .= " AND clients.type_platform = {$typeClient} ";
		}
		$query3 .= "AND IFNULL(inactive_platform, 0) <> 2
		GROUP BY
			prefijo,origen
		ORDER BY
			country ASC";
		$respGraf3 = $this->selectDynamic('', '', '', '', $query3, '', '', '', '');
		$OrigNum = [];
		foreach ($respGraf3 as &$element) {
			$OrigNum[$element['client']] += (int) $element['cantidad'] ?: 0;
			$OrigClient[$element['client']] = $element['country'];
			$drillOrig[$element['client']][] = [$element['country'], (int) $element['cantidad']];
		}
		$seriesOrig = [];
		$drilldownOrig = [];
		foreach ($OrigClient as $key => &$val) {
			$seriesOrig[] = [
				'name' => $key,
				'y' => (int) $OrigNum[$key],
				'drilldown' => $key,
			];
			$drilldownOrig[] = [
				'name' => $key,
				'id' => $key,
				'data' => $drillOrig[$key],
			];
		}
		//////////grafica de cantidad de ventas por mes grafico de columna
		$query4 = "SELECT
			orders.prefijo,
			MONTH (orders.fecha) AS mes,
			COUNT(orders.cantidad) AS cantidad,
			clients.client AS description,
			DATE_FORMAT(orders.fecha, '%M') AS nameMes
		FROM
			orders
		JOIN clients ON clients.prefix = orders.prefijo
		WHERE
			orders. STATUS IN (1, 3)
		AND IFNULL(inactive_platform, 0) <> 2
		AND clients.data_activa = 'SI'
		AND orders.prefijo != 'IX' ";
		if ($mesBus == 'ALL') {
			$query4 .= "AND YEAR(orders.fecha) = '$yearBus'";
		} else {
			$query4 .= "AND YEAR (orders.fecha) = '$yearBus'
			AND MONTH (orders.fecha) = '$mesBus'";
		}
		if (!empty($typeClient) && $typeClient != 'all') {
			$query4 .= " AND clients.type_platform = {$typeClient} ";
		}
		$query4 .= " GROUP BY
			orders.prefijo,
			mes
		ORDER BY
			mes,
			client ASC";
		$respGraf4 = $this->selectDynamic('', '', '', '', $query4, '', '', '', '');
		$mountDesc = [
			'01' => 'Enero',
			'02' => 'Febrero',
			'03' => 'Marzo',
			'04' => 'Abril',
			'05' => 'Mayo',
			'06' => 'Junio',
			'07' => 'Julio',
			'08' => 'Agosto',
			'09' => 'Septiembre',
			'10' => 'Octubre',
			'11' => 'Noviembre',
			'12' => 'Diciembre',
		];

		foreach ($respGraf4 as $element) {
			$timeInt[$element['prefijo']]['ventas'][(int) $element['mes']] = (int) $element['cantidad'] ?: 0;
			$timeInt[$element['prefijo']]['description'] = $element['description'];
		}

		foreach ($timeInt as $val) {
			$setMonth = [];
			foreach ($Months as $key2 => $value) {
				$setMonth[] = (int) $val['ventas'][(int) $key2] ?: 0;
			}
			$MonthInt[] = [
				'name' => $val['description'],
				'data' => $setMonth
			];
		}
		///////////////////GRAFICO DE COLUMNAS DE NETO DE VENTAS 
		$query5 = "SELECT
			orders.prefijo,
			MONTH (orders.fecha) AS mes,
			SUM(orders.neto_prov) AS neto,
			clients.client AS description,
			DATE_FORMAT(orders.fecha, '%M') AS nameMes
		FROM
			orders
		JOIN clients ON clients.prefix = orders.prefijo
		WHERE
			orders. STATUS IN (1, 3)
		AND IFNULL(inactive_platform, 0) <> 2
		AND clients.data_activa = 'SI'
		AND orders.prefijo != 'IX' ";
		if ($mesBus == 'ALL') {
			$query5 .= "AND YEAR(orders.fecha) = '$yearBus'";
		} else {
			$query5 .= "AND YEAR (orders.fecha) = '$yearBus'
			AND MONTH (orders.fecha) = '$mesBus'";
		}
		if (!empty($typeClient) && $typeClient != 'all') {
			$query5 .= " AND clients.type_platform = {$typeClient} ";
		}
		$query5 .= " GROUP BY
			orders.prefijo,
			mes
		ORDER BY
			mes,
			client ASC";
		$respGraf5 = $this->selectDynamic('', '', '', '', $query5, '', '', '', '');
		$monts = [];
		foreach ($respGraf5 as &$element) {
			$SalInt[$element['prefijo']]['ventas'][(int) $element['mes']] = (float) $element['neto'] ?: 0;
			$SalInt[$element['prefijo']]['description'] = $element['description'];
			$monts[$element['mes']] = $element['nameMes'];
		}
		foreach ($SalInt as &$val) {
			$setSal = [];
			foreach ($Months as $key2 => &$value) {
				$setSal[] = (float) $val['ventas'][$key2] ?: 0;
			}
			$AnSales[] = [
				'name' => $val['description'],
				'data' => $setSal
			];
		}
		/////////////GRAFICA DRILL DOWN COLUMNAS DE EDADES CON FILTRO DE FECHA
		$queryClientes = "SELECT
			client,
			id_status,
			prefix
		FROM
			clients
		WHERE
			IFNULL(inactive_platform, 0) <> 2
		AND id_status = '1'
		AND data_activa = 'SI'
		AND clients.prefix != 'IX' ";
		if (!empty($typeClient) && $typeClient != 'all') {
			$queryClientes .= " AND clients.type_platform = {$typeClient} ";
		}
		$queryClientes .= "
		ORDER BY
			client ASC";
		$respClientes = $this->selectDynamic('', '', '', '', $queryClientes, '', '', '', '');
		$query6 = "SELECT
			orders.prefijo,
			beneficiaries.sexo AS sexo,
			TIMESTAMPDIFF(
				YEAR,
				beneficiaries.nacimiento,
				orders.fecha
			) AS edad,
			COUNT(*) AS cant
		FROM
			orders
		JOIN clients ON clients.prefix = orders.prefijo
		JOIN beneficiaries ON beneficiaries.id_orden = orders.id
		AND orders.prefijo = beneficiaries.prefijo
		WHERE
			orders. STATUS IN (1, 3)
		AND IFNULL(inactive_platform, 0) <> 2
		AND clients.data_activa = 'SI'
		AND orders.prefijo != 'IX' ";
		if ($mesBus == 'ALL') {
			$query6 .= "AND YEAR(orders.fecha) = '$yearBus'";
		} else {
			$query6 .= "AND YEAR (orders.fecha) = '$yearBus'
			AND MONTH (orders.fecha) = '$mesBus'";
		}
		if (!empty($typeClient) && $typeClient != 'all') {
			$query6 .= " AND clients.type_platform = {$typeClient} ";
		}
		$query6 .= "GROUP BY
			prefijo,
			sexo,
			edad
		ORDER BY
			prefijo,
			edad ASC";
		$respGraf6 = $this->selectDynamic('', '', '', '', $query6, '', '', '', '');
		foreach ($respGraf6 as &$element) {
			statistics::EdadResult($BarD[$element['prefijo']], $element['edad'], $element['sexo'], $element['cant']);
			statistics::EdadResult($BarA, $element['edad'], $element['sexo'], $element['cant']);
		}
		$IntEdad = [
			'S-E',
			'0-10',
			'11-20',
			'21-30',
			'31-40',
			'41-50',
			'51-60',
			'61-70',
			'71-75',
			'76-84',
			'85+',
		];
		$sex  = [
			"I",
			"F",
			"M",
			"N/A",
		];
		$SerEd = [];
		foreach ($BarA as $sexo => &$edad) {
			$SexEdad = [];
			$dataEd = [];
			foreach ($IntEdad  as  $key2 => &$values) {
				$SexEdad[] = [
					'name' => $values,
					'y' => (int) $edad[$values] ?: 0,
					'drilldown' => $sexo . '-' . $values,
				];
			}
			$SerEd[] = [
				'name' => $sexo,
				'data' => $SexEdad,
			];
		}
		$dataEdad = [];
		foreach ($BarD as $prefix => &$element) {
			foreach ($element as $sex => &$val) {
				foreach ($val  as $edad => &$values) {
					foreach ($respClientes as &$key3) {
						if ($prefix == $key3['prefix']) {
							$dataEdad[$sex . '-' . $edad][] = [
								$key3['client'], (int) $values ?: 0
							];
						}
					}
				}
			}
		}
		$DrillEd = [];
		foreach ($dataEdad as $key => &$value) {
			$DrillEd[] = [
				'id' => $key,
				'data' => $value,
			];
		}
		////////////////// grafica de ventas de años anteriores columnas
		$yearInicio = (Date('Y') - 2); ///array para filtrar solo del año actual menos 2 años 
		$yearsBus = '';
		for ($i = $yearInicio; $i <= $yearActual; $i++) {
			$yearsBus .= $i . ',';
		}
		$yearsBus = substr($yearsBus, 0, -1); ///aqui suprimo la ultima coma
		$query7 = "SELECT
			orders.prefijo,
			YEAR (orders.fecha) AS anio,
			COUNT(orders.cantidad) AS cantidad,
			clients.client AS description
		FROM
			orders
		JOIN clients ON clients.prefix = orders.prefijo
		WHERE
			orders. STATUS IN (1, 3)
		AND YEAR (orders.fecha) IN ($yearsBus)
		AND IFNULL(inactive_platform, 0) <> 2
		AND clients.data_activa = 'SI'
		AND orders.prefijo != 'IX' ";
		if (!empty($typeClient) && $typeClient != 'all') {
			$query7 .= " AND clients.type_platform = {$typeClient} ";
		}
		$query7 .= "
		GROUP BY
			orders.prefijo,
			anio
		ORDER BY
			orders.prefijo ASC,
			anio DESC";
		$respQuery7 = $this->selectDynamic('', '', '', '', $query7, '', '', '', '');
		foreach ($respQuery7 as &$element) {
			$temp[$element['anio']][$element['prefijo']] = (int) $element['cantidad'] ?: 0;
		}
		foreach ($temp as $anio => &$element) {
			$clAn1 = [];
			foreach ($respClientes as &$key) {
				$clAn1[] = (int) $element[$key['prefix']] ?: 0;
				$platName[] = $element['client'] ?: 0;
			}
			$final[] = [
				'name' => $anio,
				'data' => $clAn1
			];
		}
		$platName = [];
		foreach ($respClientes as &$element) {
			$platName[] = $element['client'] ?: 0;
		}
		return [
			[$seriesOrd, $drilldownOrd],
			[$seriesNeto, $drilldownNeto],
			[$seriesOrig, $drilldownOrig],
			[$MonthInt, array_values($Months)],
			[$AnSales, array_values($Months)],
			[$SerEd, $DrillEd],
			[$final, $platName],
			'YEAR' => $yearBus
		];
	}

	///////////////////////////////////////GRAFICOS CON TODOS LOS STATUS DE LAS ORDENES Y DRILLDOWN ILS
	public function getChartVouchersStatus($filters)
	{
		$prefix	    = $filters['prefix'];
		$endDate   	= $filters['endDate'];
		$today 	   	= date('Y-m-d');
		$startDate  = $filters['startDate'];
		$language	= $this->funcLangApp();
		$lang_app   = $this->funcLangAppShort($this->funcLangApp());
		$status     = Clients::getLabelStAssist(false, $lang_app);

		$dataValida	= [
			"9092"  => $prefix,
			'3030'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($startDate) > strtotime($endDate)) : true,
			'9068'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($endDate)	> strtotime($today)) : true,
			'9069'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($startDate)	> strtotime($today)) : true,
		];
		$this->validatEmpty($dataValida);

		$IntEdad = [
			'0-10',
			'11-20',
			'21-30',
			'31-40',
			'41-50',
			'51-60',
			'61-70',
			'71-75',
			'76-84',
			'85+',
		];

		//    GRAFICA VOUCHERS CATEGORIAS 
		$query1 = "SELECT
				COUNT(orders.cantidad) AS total,
				orders.status,
				(
					SELECT
						plan_category.name_plan
					FROM
						plans
					JOIN plan_category ON plan_category.id_plan_categoria = plans.id_plan_categoria
					AND plan_category.prefijo = plans.prefijo
					WHERE
						orders.producto = plans.id
					AND orders.prefijo = plans.prefijo
					LIMIT 1
				) AS category
			FROM
				orders
			JOIN clients ON clients.prefix = orders.prefijo
			WHERE
				clients.data_activa = 'SI'
			AND orders.fecha BETWEEN '$startDate'
			AND '$endDate'
			AND IFNULL(inactive_platform, 0) <> 2
			AND orders.prefijo = '$prefix'
			GROUP BY
				status,
				category";
		$respGraf1 = $this->selectDynamic('', '', '', '', $query1, '', '', '', '');
		foreach ($respGraf1 as &$element) {
			$salesTot[$status[$element['status']]] += (int) $element['total'] ?: 0;
			$salesSt[$status[$element['status']]] = $element['category'] ?: "N/A";
			$drillSales[$status[$element['status']]][] = [$element['category'] ?: "N/A", (int) $element['total']];
		}
		$seriesCat = [];
		$drilldownCat = [];
		foreach ($salesSt as $key => &$val) {
			$seriesCat[] = [
				'name' => $key ?: 'N/A',
				'y' => (int) $salesTot[$key],
				'drilldown' => $key ?: 'N/A',
			];
			$drilldownCat[] = [
				'name' => $key ?: 'N/A',
				'id' => $key ?: 'N/A',
				'data' => $drillSales[$key],
			];
		}

		//GRAFICA 2 NETOS VOUCHERS
		$query2 = "SELECT
				SUM(neto_prov) AS neto,
                (
                SELECT
                    plans.`name`
                FROM
                    plans
                WHERE
                    orders.producto = plans.id
                AND orders.prefijo = plans.prefijo
                LIMIT 1
            ) AS name_plan,
                orders.status
			FROM
				orders
			JOIN clients ON clients.prefix = orders.prefijo
			WHERE
				clients.data_activa = 'SI'
			AND orders.fecha BETWEEN '$startDate'
			AND '$endDate'
			AND IFNULL(inactive_platform, 0) <> 2
			AND orders.prefijo = '$prefix'
			GROUP BY
				status,
				name_plan";
		$respGraf2 = $this->selectDynamic('', '', '', '', $query2, '', '', '', '');
		foreach ($respGraf2 as &$element) {
			$netoTot[$status[$element['status']]] += (float) $element['neto'] ?: 0;
			$netoSt[$status[$element['status']]] = $element['name_plan'] ?: "N/A";
			$drillNeto[$status[$element['status']]][] = [$element['name_plan'] ?: "N/A", (float) $element['neto']];
		}
		$seriesNet = [];
		$drilldownNet = [];
		foreach ($netoSt as $key => &$val) {
			$seriesNet[] = [
				'name' => $key ?: 'N/A',
				'y' => (float) $netoTot[$key],
				'drilldown' => $key ?: 'N/A',
			];
			$drilldownNet[] = [
				'name' => $key ?: 'N/A',
				'id' => $key ?: 'N/A',
				'data' => $drillNeto[$key],
			];
		}
		//GRAFICO 3 DE PAISES PARA VOUCHERS
		$query3 = "SELECT
					orders.origen,
                orders.status,
                COUNT(orders.cantidad) AS total,
				(
					SELECT
						description
					FROM
						countries
					WHERE
						iso_country = orders.origen
				LIMIT 1
				) AS country
				FROM
					orders
				JOIN clients ON clients.prefix = orders.prefijo
				WHERE
					clients.data_activa = 'SI'
				AND orders.fecha BETWEEN '$startDate'
				AND '$endDate'
				AND IFNULL(inactive_platform, 0) <> 2
				AND orders.prefijo = '$prefix'
				GROUP BY 
					status, 
					orders.origen";
		$respGraf3 = $this->selectDynamic('', '', '', '', $query3, '', '', '', '');
		foreach ($respGraf3 as &$element) {
			$origTot[$status[$element['status']]] += (int) $element['total'] ?: 0;
			$origSt[$status[$element['status']]] = $element['country'] ?: "N/A";
			$drillorig[$status[$element['status']]][] = [$element['country'] ?: "N/A", (int) $element['total']];
		}
		$seriesOrig = [];
		$drilldownOrig = [];
		foreach ($origSt as $key => &$val) {
			$seriesOrig[] = [
				'name' => $key ?: 'N/A',
				'y' => (int) $origTot[$key],
				'drilldown' => $key ?: 'N/A',
			];
			$drilldownOrig[] = [
				'name' => $key ?: 'N/A',
				'id' => $key ?: 'N/A',
				'data' => $drillorig[$key],
			];
		}

		//GRAFICA SOLO PARA STATUS EN CANTIDADES SENCILLA
		$query4 = "SELECT
					COUNT(orders.status) AS total,
                	orders.status
				FROM orders
				JOIN clients ON clients.prefix = orders.prefijo
				WHERE
					clients.data_activa = 'SI'
				AND orders.fecha BETWEEN '$startDate'
				AND '$endDate'
				AND IFNULL(inactive_platform, 0) <> 2
				AND orders.prefijo = '$prefix'
				GROUP BY 
					status";

		$respGraf4 = $this->selectDynamic('', '', '', '', $query4, '', '', '', '');
		foreach ($respGraf4 as $key => &$val) {
			$response[] = [
				'name' => $status[$val['status']] ?: 'N/A',
				'y' => (int) $val['total'],
			];
		}

		//////GRAFICA DE EDADES STATUS ILS
		$query5 = "SELECT
					orders.status,
					UPPER(IFNULL(beneficiaries.sexo,'N/A')) AS sexo,
                	TIMESTAMPDIFF(
                        YEAR,
                        beneficiaries.nacimiento,
                        orders.fecha) AS edad,
                	COUNT(*) AS cant
				FROM orders
                JOIN clients ON clients.prefix = orders.prefijo
                JOIN beneficiaries ON beneficiaries.id_orden = orders.id
                AND orders.prefijo = beneficiaries.prefijo
				AND beneficiaries.ben_status = 1
				WHERE
					clients.data_activa = 'SI'
				AND orders.fecha BETWEEN '$startDate'
				AND '$endDate'
				AND IFNULL(inactive_platform, 0) <> 2
				AND orders.prefijo = '$prefix'
				GROUP BY 
					status, 
					sexo, 
					edad 
				ORDER BY 
					edad ASC";

		$respGraf5 = $this->selectDynamic('', '', '', '', $query5, '', '', '', '');

		$BarD = [];
		foreach ($respGraf5 as &$element) {
			$arrSt[$element['sexo']][$element['status']] += $element['cant'];
			statistics::EdadResult($BarD[$element['status']], $element['edad'], $element['sexo'], $element['cant']);
		}
		$SerEd = [];
		foreach ($arrSt as $sex => &$st) {
			$SexEdad = [];
			foreach ($st  as  $key => &$val) {
				$SexEdad[] = [
					'name' => $status[$key],
					'y' => (int) $val ?: 0,
					'drilldown' => $sex . '-' . $status[$key],
				];
			}
			$SerEd[] = [
				'name' => $sex,
				'data' => $SexEdad,
			];
		}
		$dataEdad = [];
		foreach ($arrSt as $sex => &$st) {
			foreach ($st as $key => &$val) {
				foreach ($BarD[$key][strtoupper($sex)] as $k => &$v) {
					$dataEdad[$sex . '-' . $status[$key]][] = [
						$k, (int) $v
					];
				}
			}
		}
		foreach ($dataEdad as $key => &$value) {
			$DrillEd[] = [
				'id' => $key,
				'data' => $value,
			];
		}

		//////GRAFICA DE EDADES
		$IntEdad2 = [
			'S-E',
			'0-10',
			'11-20',
			'21-30',
			'31-40',
			'41-50',
			'51-60',
			'61-70',
			'71-75',
			'76-84',
			'85+',
		];

		$BarA = [];
		$BarD = [];
		foreach ($respGraf4 as &$element) {
			statistics::EdadResult($BarD[$element['prefijo']], $element['edad'], $element['sexo'], $element['cant']);
			statistics::EdadResult($BarA, $element['edad'], $element['sexo'], $element['cant']);
		}
		$SexEdad = [];
		$data = [];

		foreach ($BarD  as $key => &$val) {
			foreach ($val  as $key1 => &$value) {
				$data = [];
				foreach ($IntEdad  as  $key2 => &$values) {
					$data[] = (int) $value[$values] ?: 0;
				}
				$SexEdad[] = [
					'name' => $key1,
					'data' => $data,
				];
			}
		}

		//////GRAFICAS PLATAFORMA de agencias
		$sqlBroker = "SELECT
			REPLACE( broker.broker,'''','') AS broker,
			COUNT(orders.id) AS cant,
			SUM(orders.total) AS monto,
			broker.id_broker AS id_broker
		FROM
			orders 
		INNER JOIN broker ON orders.agencia = broker.id_broker
		AND broker.prefijo = orders.prefijo
		WHERE
			orders.fecha BETWEEN '$startDate'
		AND '$endDate'
		AND orders. STATUS IN (1, 3)
		AND orders.prefijo = '$prefix'
		GROUP BY
			orders.prefijo,
			broker
		ORDER BY
			cant DESC";

		$arrCounterData = $this->selectDynamic('', '', '', '', $sqlBroker, '', '', '', '');
		$cntCounterData = count($arrCounterData);

		$sqlBrokerMonto = "SELECT
			REPLACE( broker.broker,'''','') AS broker,
			COUNT(orders.id) AS cant,
			SUM(orders.total) AS monto,
			broker.id_broker AS id_broker
		FROM
			orders
		INNER JOIN broker ON orders.agencia = broker.id_broker
		AND broker.prefijo = orders.prefijo
		WHERE
			orders.fecha BETWEEN '$startDate'
		AND '$endDate'
		AND orders. STATUS IN (1, 3)
		AND orders.prefijo = '$prefix'
		GROUP BY
			orders.prefijo,
			broker
		ORDER BY
			cant DESC";

		$arrAmountData = $this->selectDynamic('', '', '', '', $sqlBrokerMonto, '', '', '', '');

		$cntData = $cntCounterData;
		$arrAmount  = [];
		$arrCount   = [];
		$sortAmount = [];
		$sortCount  = [];
		$totalAmount = [0, 0];
		$totalConter = [0, 0];
		$arrDataCnt  = [
			[
				'id' => 1,
				'name' => '20 Primeros',
				'data' => []
			]
		];
		if ($cntData > 20) {
			$arrDataCnt[1] = [
				'id' => 2,
				'name' => 'Otros',
				'data' => []
			];
		}
		$arrDataAmn = [
			[
				'id' => 1,
				'name' => '20 Primeros',
				'data' => []
			]
		];
		if ($cntData > 20) {
			$arrDataAmn[1] = [
				'id' => 2,
				'name' => 'Otros',
				'data' => []
			];
		}

		for ($i = 0; $i < $cntData; $i++) {
			$group = ($i < 20) ? 0 : 1;
			$totalCount = $arrCounterData[$i]['cant'];
			$totalPrice = $arrAmountData[$i]['monto'];

			settype($totalCount, 'integer');
			settype($totalPrice, 'float');

			$totalAmount[$group] += $totalPrice;
			$totalConter[$group] += $totalCount;

			$sortCount[$group][] = $totalCount;
			$sortAmount[$group][] = $totalPrice;

			$arrCount[$group][] = [$arrCounterData[$i]['broker'], $totalCount];
			$arrAmount[$group][] = [$arrAmountData[$i]['broker'], $totalPrice];
		}

		$arrDataCnt[0]['data'] = $arrCount[0]; //?: [['' => 0], ['' => 0]];
		$arrDataCnt[1]['data'] = $arrCount[1];
		$arrDataAmn[0]['data'] = $arrAmount[0]; //?: [['' => 0], ['' => 0]];
		$arrDataAmn[1]['data'] = $arrAmount[1];

		// if (empty($arrDataCnt[1]['data'])) {
		// 	unset($arrDataCnt[1]);
		// }
		// if (empty($arrDataAmn[1]['data'])) {
		// 	unset($arrDataAmn[1]);
		// }

		$seriesCnt = [
			[
				'y' => $totalConter[0],
				'drilldown' => 1,
				'name' => ($language == 'spa') ? '20 Primeros' : '20 First',
			]
		];
		if ($cntData > 10) {
			$seriesCnt[1] = [
				'y' => $totalConter[1],
				'drilldown' => 2,
				'name' => ($language == 'spa') ? 'Otros' : 'Other',
			];
		}
		$seriesAmn = [
			[
				'y' => $totalAmount[0],
				'drilldown' => 1,
				'name' => ($language == 'spa') ? '20 Primeros' : '20 First',
			]
		];
		if ($cntData > 10) {
			$seriesAmn[1] = [
				'y' => $totalAmount[1],
				'drilldown' => 2,
				'name' => ($language == 'spa') ? 'Otros' : 'Other',
			];
		}

		//////CONSULTA GENERAL PARA LA DATA DE LAS GRAFICAS DE EDADES PARA LA APP CANTIDAD Y MONTO
		$dataGrafEdVentasCantidadMonto = $this->grafVentEd($prefix, $startDate, $endDate);

		///////GRAFICAS EDADES CANTIDAD
		$BarAPlatf2 = [];
		$BarDPlatf2 = [];
		foreach ($dataGrafEdVentasCantidadMonto as &$element) {
			statistics::EdadResult($BarDPlatf2[$element['prefijo']], $element['edad'], $element['sexo'],  $element['cant']);
			statistics::EdadResult($BarAPlatf2, $element['edad'], $element['sexo'],  $element['cant']);
		}
		$SexEdad2 = [];
		$data3 = [];

		foreach ($BarDPlatf2  as $key => &$val) {
			foreach ($val  as $key1 => &$value) {
				$data3 = [];
				foreach ($IntEdad2  as  $key2 => &$values) {
					$data3[] = (float) $value[$values] ?: 0;
				}
				$SexEdad2[] = [
					'name' => $key1,
					'data' => $data3,
				];
			}
		}

		/////GRAFICAS PLATAFORMA DE EDADES / VENTAS MONTO
		$BarAPlatf3 = [];
		$BarDPlatf3 = [];
		foreach ($dataGrafEdVentasCantidadMonto as &$element) {
			statistics::EdadResult($BarDPlatf3[$element['prefijo']], $element['edad'], $element['sexo'],  $element['neto']);
			statistics::EdadResult($BarAPlatf3, $element['edad'], $element['sexo'],  $element['neto']);
		}
		$SexEdadPlatf3 = [];
		$data3 = [];

		foreach ($BarDPlatf3  as $key => &$val) {
			foreach ($val  as $key1 => &$value) {
				$data3 = [];
				foreach ($IntEdad2  as  $key2 => &$values) {
					$data3[] = (float) $value[$values] ?: 0;
				}
				$SexEdadPlatf3[] = [
					'name' => $key1,
					'data' => $data3,
				];
			}
		}

		return [
			[$seriesCat, $drilldownCat],
			[$seriesNet, $drilldownNet],
			[$seriesOrig, $drilldownOrig],
			$response,
			[$SerEd, $DrillEd, array_values($status)],
			array([
				'arrDataCnt' => $arrDataCnt,
				'arrDataAmn' => $arrDataAmn,
				'seriesCnt' => $seriesCnt,
				'seriesAmn' => $seriesAmn
			]),
			$SexEdad2,
			$SexEdadPlatf3,
			$this->grafTipoVenta($prefix, $startDate, $endDate, false),
			$this->grafTipoVenta($prefix, '', '', true)
		];
	}

	////////////////////////////////////////////// GRAFICOS PARA CADA AGENCIA ////////////////////
	public function getChartVouchersPie($filters)
	{
		$prefix	    = $filters['prefix'];
		$startDate  = $filters['startDate'];
		$endDate   	= $filters['endDate'];
		$today 	   	= date('Y-m-d');
		$language	= $this->funcLangApp();

		$dataValida	= [
			"9092"  => $prefix,
			'3030'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($startDate) > strtotime($endDate)) : true,
			'9068'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($endDate)	> strtotime($today)) : true,
			'9069'	=> (!empty($endDate) && !empty($endDate)) ? !(strtotime($startDate)	> strtotime($today)) : true,
		];
		$this->validatEmpty($dataValida);

		$IntEdad = [
			'0-10',
			'11-20',
			'21-30',
			'31-40',
			'41-50',
			'51-60',
			'61-70',
			'71-75',
			'76-84',
			'85+',
		];
		//    GRAFICA VOUCHERS CATEGORIAS 
		$query1 = "SELECT
		COUNT(orders.cantidad) AS cantidad,
		(
			SELECT
				plan_category.name_plan
			FROM
				plans
			JOIN plan_category ON plan_category.id_plan_categoria = plans.id_plan_categoria
			AND plan_category.prefijo = plans.prefijo
			WHERE
				orders.producto = plans.id
			AND orders.prefijo = plans.prefijo
			LIMIT 1
		) AS category
		FROM
			orders
		JOIN clients ON clients.prefix = orders.prefijo
		WHERE
			orders. STATUS IN (1, 3)
		AND orders.fecha BETWEEN '$startDate'
		AND '$endDate'
		AND IFNULL(inactive_platform, 0) <> 2
		AND orders.prefijo = '$prefix'
		AND clients.data_activa = 'SI'
		GROUP BY
			category
		ORDER BY
			category";
		$respGraf1 = $this->selectDynamic('', '', '', '', $query1, '', '', '', '');
		$respGraf1 = array_reduce(
			$respGraf1,
			function ($response, $value) {
				$response[] = [
					'name' => $value['category'],
					'y' => (int) $value['cantidad'],
				];
				return $response;
			},
			[]
		);
		//GRAFICA 2 NETOS VOUCHERS
		$query2 = "SELECT 
		SUM(orders.neto_prov) AS neto,
		REPLACE (
			(
				SELECT
					plans.`name`
				FROM
					plans
				WHERE
					orders.producto = plans.id
				AND orders.prefijo = plans.prefijo
				LIMIT 1
			),
			'''',
			''
		) AS name_plan
		FROM
			orders
		JOIN clients ON clients.prefix = orders.prefijo
		WHERE
			orders. STATUS IN (1, 3)
		AND orders.fecha BETWEEN '$startDate'
		AND '$endDate'
		AND IFNULL(inactive_platform, 0) <> 2
		AND orders.prefijo = '$prefix'
		AND clients.data_activa = 'SI'
		GROUP BY
			name_plan
		ORDER BY
			name_plan ASC";
		$respGraf2 = $this->selectDynamic('', '', '', '', $query2, '', '', '', '');
		$respGraf2 = array_reduce(
			$respGraf2,
			function ($response, $value) {
				$response[] = [
					'name' => $value['name_plan'],
					'y' =>  (float) $value['neto']
				];
				return $response;
			},
			[]
		);
		//GRAFICO 3 DE PAISES PARA VOUCHERS
		$query3 = "SELECT
		orders.origen,
		COUNT(orders.cantidad) AS cantidad,
			(
				SELECT
					description
				FROM
					countries
				WHERE
					iso_country = orders.origen
				LIMIT 1
			) AS country
		FROM
			orders
		JOIN clients ON clients.prefix = orders.prefijo
		WHERE
			orders. STATUS IN (1, 3)
		AND orders.fecha BETWEEN '$startDate'
		AND '$endDate'
		AND IFNULL(inactive_platform, 0) <> 2
		AND orders.prefijo = '$prefix'
		AND clients.data_activa = 'SI'
		GROUP BY
			orders.origen
		ORDER BY
			country ASC";
		$respGraf3 = $this->selectDynamic('', '', '', '', $query3, '', '', '', '');
		$respGraf3 = array_reduce(
			$respGraf3,
			function ($response, $value) {
				$response[] = [
					'name' => $value['country'],
					'y' => (int) $value['cantidad'],
				];
				return $response;
			},
			[]
		);
		//GRAFICA DE LINEAS EDAD Y SEXO
		$query4 = "SELECT
		orders.prefijo,
		UPPER(IFNULL(beneficiaries.sexo,'N/A')) AS sexo,
			TIMESTAMPDIFF(
				YEAR,
				beneficiaries.nacimiento,
				orders.fecha
			) AS edad,
			COUNT(*) AS cant
		FROM
			orders
		JOIN clients ON clients.prefix = orders.prefijo
		JOIN beneficiaries ON beneficiaries.id_orden = orders.id
		AND orders.prefijo = beneficiaries.prefijo
		AND beneficiaries.ben_status = 1
		AND clients.data_activa = 'SI'
		AND orders.fecha BETWEEN '$startDate'
		AND '$endDate'
		AND IFNULL(inactive_platform, 0) <> 2
		AND orders.prefijo = '$prefix'
		GROUP BY
			prefijo,
			sexo,
			edad
		ORDER BY
			prefijo,
			edad ASC";

		$respGraf4 = $this->selectDynamic('', '', '', '', $query4, '', '', '', '');

		$IntEdad2 = [
			'S-E',
			'0-10',
			'11-20',
			'21-30',
			'31-40',
			'41-50',
			'51-60',
			'61-70',
			'71-75',
			'76-84',
			'85+',
		];

		$BarA = [];
		$BarD = [];
		foreach ($respGraf4 as &$element) {
			statistics::EdadResult($BarD[$element['prefijo']], $element['edad'], $element['sexo'], $element['cant']);
			statistics::EdadResult($BarA, $element['edad'], $element['sexo'], $element['cant']);
		}
		$SexEdad = [];
		$data = [];

		foreach ($BarD  as $key => &$val) {
			foreach ($val  as $key1 => &$value) {
				$data = [];
				foreach ($IntEdad  as  $key2 => &$values) {
					$data[] = (int) $value[$values] ?: 0;
				}
				$SexEdad[] = [
					'name' => $key1,
					'data' => $data,
				];
			}
		}

		//////GRAFICAS PLATAFORMA de agencias
		$sqlBroker = "SELECT
			REPLACE( broker.broker,'''','') AS broker,
			COUNT(orders.id) AS cant,
			SUM(orders.total) AS monto,
			broker.id_broker AS id_broker
		FROM
			broker
		INNER JOIN orders ON orders.agencia = broker.id_broker
		AND broker.prefijo = orders.prefijo
		WHERE
			orders. STATUS IN (1, 3)
		AND orders.fecha BETWEEN '$startDate'
		AND '$endDate'
		AND orders.prefijo = '$prefix'
		GROUP BY
			orders.prefijo,
			broker
		ORDER BY
			cant DESC";

		$arrCounterData = $this->selectDynamic('', '', '', '', $sqlBroker, '', '', '', '');
		$cntCounterData = count($arrCounterData);

		$sqlBrokerMonto = "SELECT
			REPLACE( broker.broker,'''','') AS broker,
			COUNT(orders.id) AS cant,
			SUM(orders.total) AS monto,
			broker.id_broker AS id_broker
		FROM
			broker
		INNER JOIN orders ON orders.agencia = broker.id_broker
		AND broker.prefijo = orders.prefijo
		WHERE
			orders.fecha BETWEEN '$startDate'
		AND '$endDate'
		AND orders. STATUS IN (1, 3)
		AND orders.prefijo = '$prefix'
		GROUP BY
			orders.prefijo,
			broker
		ORDER BY
			cant DESC";

		$arrAmountData = $this->selectDynamic('', '', '', '', $sqlBrokerMonto, '', '', '', '');

		$cntData = $cntCounterData;
		$arrAmount  = [];
		$arrCount   = [];
		$sortAmount = [];
		$sortCount  = [];
		$totalAmount = [0, 0];
		$totalConter = [0, 0];
		$arrDataCnt  = [
			[
				'id' => 1,
				'name' => '20 Primeros',
				'data' => []
			]
		];
		if ($cntData > 20) {
			$arrDataCnt[1] = [
				'id' => 2,
				'name' => 'Otros',
				'data' => []
			];
		}
		$arrDataAmn = [
			[
				'id' => 1,
				'name' => '20 Primeros',
				'data' => []
			]
		];
		if ($cntData > 20) {
			$arrDataAmn[1] = [
				'id' => 2,
				'name' => 'Otros',
				'data' => []
			];
		}

		for ($i = 0; $i < $cntData; $i++) {
			$group = ($i < 20) ? 0 : 1;
			$totalCount = $arrCounterData[$i]['cant'];
			$totalPrice = $arrAmountData[$i]['monto'];

			settype($totalCount, 'integer');
			settype($totalPrice, 'float');

			$totalAmount[$group] += $totalPrice;
			$totalConter[$group] += $totalCount;

			$sortCount[$group][] = $totalCount;
			$sortAmount[$group][] = $totalPrice;

			$arrCount[$group][] = [$arrCounterData[$i]['broker'], $totalCount];
			$arrAmount[$group][] = [$arrAmountData[$i]['broker'], $totalPrice];
		}

		$arrDataCnt[0]['data'] = $arrCount[0];
		$arrDataCnt[1]['data'] = $arrCount[1];
		$arrDataAmn[0]['data'] = $arrAmount[0];
		$arrDataAmn[1]['data'] = $arrAmount[1];

		$seriesCnt = [
			[
				'y' => $totalConter[0],
				'drilldown' => 1,
				'name' => ($language == 'spa') ? '20 Primeros' : '20 First',
			]
		];
		if ($cntData > 10) {
			$seriesCnt[1] = [
				'y' => $totalConter[1],
				'drilldown' => 2,
				'name' => ($language == 'spa') ? 'Otros' : 'Other',
			];
		}
		$seriesAmn = [
			[
				'y' => $totalAmount[0],
				'drilldown' => 1,
				'name' => ($language == 'spa') ? '20 Primeros' : '20 First',
			]
		];
		if ($cntData > 10) {
			$seriesAmn[1] = [
				'y' => $totalAmount[1],
				'drilldown' => 2,
				'name' => ($language == 'spa') ? 'Otros' : 'Other',
			];
		}

		//////CONSULTA GENERAL PARA LA DATA DE LAS GRAFICAS DE EDADES PARA LA APP CANTIDAD Y MONTO
		$dataGrafEdVentasCantidadMonto = $this->grafVentEd($prefix, $startDate, $endDate);

		///////GRAFICAS EDADES CANTIDAD
		$BarAPlatf2 = [];
		$BarDPlatf2 = [];
		foreach ($dataGrafEdVentasCantidadMonto as &$element) {
			statistics::EdadResult($BarDPlatf2[$element['prefijo']], $element['edad'], $element['sexo'],  $element['cant']);
			statistics::EdadResult($BarAPlatf2, $element['edad'], $element['sexo'],  $element['cant']);
		}
		$SexEdad2 = [];
		$data3 = [];

		foreach ($BarDPlatf2  as $key => &$val) {
			foreach ($val  as $key1 => &$value) {
				$data3 = [];
				foreach ($IntEdad2  as  $key2 => &$values) {
					$data3[] = (float) $value[$values] ?: 0;
				}
				$SexEdad2[] = [
					'name' => $key1,
					'data' => $data3,
				];
			}
		}

		/////GRAFICAS PLATAFORMA DE EDADES / VENTAS MONTO
		$BarAPlatf3 = [];
		$BarDPlatf3 = [];
		foreach ($dataGrafEdVentasCantidadMonto as &$element) {
			statistics::EdadResult($BarDPlatf3[$element['prefijo']], $element['edad'], $element['sexo'],  $element['neto']);
			statistics::EdadResult($BarAPlatf3, $element['edad'], $element['sexo'],  $element['neto']);
		}
		$SexEdadPlatf3 = [];
		$data3 = [];

		foreach ($BarDPlatf3  as $key => &$val) {
			foreach ($val  as $key1 => &$value) {
				$data3 = [];
				foreach ($IntEdad2  as  $key2 => &$values) {
					$data3[] = (float) $value[$values] ?: 0;
				}
				$SexEdadPlatf3[] = [
					'name' => $key1,
					'data' => $data3,
				];
			}
		}

		return [
			$respGraf1,
			$respGraf2,
			$respGraf3,
			$SexEdad,
			array([
				'arrDataCnt' => $arrDataCnt,
				'arrDataAmn' => $arrDataAmn,
				'seriesCnt' => $seriesCnt,
				'seriesAmn' => $seriesAmn
			]),
			$SexEdad2,
			$SexEdadPlatf3,
			$this->grafTipoVenta($prefix, $startDate, $endDate, false),
			$this->grafTipoVenta($prefix, '', '', true)
		];
	}

	public function getUpgrades($filters)
	{
		$prefix	    = $filters['prefix'];
		$idPlan 	= $filters['idPlan'];
		$language	= $this->funcLangApp();

		$dataValida	= [
			"9092"  => $prefix,
			'5022'	=> $idPlan
		];
		$this->validatEmpty($dataValida);

		$data = [
			'querys' => "SELECT
							raiders_detail.name_raider,
							raiders_detail.description,
							raiders.id_benefi,
							raiders.type_raider,
							value_raider,
							cost_raider,
							raiders.id_provider,
							raiders.rd_calc_type,
							raiders.rd_coverage_amount,
							raiders.neta_raider,
							raiders.promocion,
							raiders.campaign,
							raiders.specific_benefit,
							raiders.limiteage,
							raiders.limiteedadmin,
							raiders.limiteedadmax,
							raiders.id_raider
						FROM
							plan_raider
						INNER JOIN raiders ON plan_raider.id_raider = raiders.id_raider
						INNER JOIN raiders_detail ON raiders.id_raider = raiders_detail.id_raider
						AND raiders_detail.language_id = '$language'
						INNER JOIN plans ON plan_raider.id_plan = plans.id
						INNER JOIN currency ON plans.id_currence = currency.id_currency
						WHERE
							plan_raider.id_plan = '$idPlan'
						AND raiders.id_status = '1'
						ORDER BY
						raiders_detail.name_raider ASC"
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkParam 	= $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$response   = $this->curlGeneral($linkParam, json_encode($data), $headers);
		$response = json_decode($response, true);

		for ($i = 0; $i < count($response); $i++) {
			$response[$i]['value_raider'] = bcdiv($response[$i]['value_raider'], 1, 2);
			if (empty($response[$i]['limiteage'])) {
				$response[$i]['limiteage'] = 'N';
			}
			switch ($response[$i]['rd_calc_type']) {
				case '1':
					$response[$i]['tipo_upgrade'] = 'Comprobante';
					break;

				case '2':
					$response[$i]['tipo_upgrade'] = 'Pasajero Especifico';
					break;

				case '3':
					$response[$i]['tipo_upgrade'] = 'Pasajero General';
					break;

				case '4':
					$response[$i]['tipo_upgrade'] = 'Por Dia por Voucher';
					break;

				case '5':
					$response[$i]['tipo_upgrade'] = 'Por Dia por Pasajero';
					break;

				default:
					$response[$i]['tipo_upgrade'] = $response[$i]['rd_calc_type'];
					break;
			}
		}
		return $response;
	}

	public function getOverageInFactors($filters)
	{
		$prefix	    = $filters['prefix'];

		$dataValida	= [
			"9092"  => $prefix
		];
		$this->validatEmpty($dataValida);

		$data = [
			'querys' => "SELECT
							parameter_key,
							parameter_value
						FROM
							`parameters`
						WHERE
							parameter_key = 'OVERAGE_IN_FACTORS'"
		];

		$link 		= $this->baseURL($this->selectDynamic(['prefix' => $prefix], 'clients', "data_activa='si'", ['web'])[0]['web']);
		$linkParam 	= $link . "/app/api/selectDynamic";
		$headers 	= "content-type: application/x-www-form-urlencoded";
		$response = $this->curlGeneral($linkParam, json_encode($data), $headers);
		return json_decode($response, true);
	}

	public function getStatesApp($filters)
	{
		$pais	    = $filters['pais'];

		$dataValida	= [
			"5019"  => $pais
		];
		$this->validatEmpty($dataValida);

		$query = "SELECT iso_state, description FROM states WHERE iso_country='$pais'";

		return $this->selectDynamic('', '', '', '', $query, '', '', '');
	}

	public function getCityApp($filters)
	{
		$state	    = $filters['state'];

		$dataValida	= [
			"50036"  => $state
		];
		$this->validatEmpty($dataValida);

		$query = "SELECT iso_city, description FROM cities WHERE iso_state = '$state';";

		return $this->selectDynamic('', '', '', '', $query, '', '', '');
	}

	/*  public function getBrokers($filters,$limit){
        $fields =
        [
            "id_broker",
            "broker",
            "id_status",
            "id_country",
            "id_state",
            "id_city",
            "zip_code",
            "credito_base",
            "credito_actual",
            "id_bmi",
            "type_broker",
            "prefijo",
            "forma_pago",
            "opcion_plan"
        ];
        return $this->selectDynamic($filters,'broker','1',$fields);
	} 
	public function getVouchers($data,$api){
		$code		= $data['codigo'];
		$language	= $data['lenguaje'];
		$dataValida	= [
			'1020'	=> $code,
			'1030'	=> !empty($language)?$this->validLanguage($language):true
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if(!empty($validatEmpty)){
			return $validatEmpty;
		}
		$datAgency 		= $this->datAgency($api);
		$idUser			= $datAgency[0]['user_id'];
		$getDataOders	= $this->getDataOders($code, $idUser);
		$passenger 		= $this->dataBeneficiaries($code);
		$agency			= $getDataOders['agencia'];
		$language		= ($language=='spa' || empty($language))?'es':'en';
		if ($getDataOders){
			if ($getDataOders['status']!= 1) {
				return $this->getError('1021');
			}else{
				$dato['id'] 				= $getDataOders['id'];
				$dato['origen'] 			= $getDataOders['origen'];
				$dato['destino'] 			= $getDataOders['destino'];
				$dato['salida'] 			= $getDataOders['salida'];
				$dato['retorno'] 			= $getDataOders['retorno'];
				$dato['programaplan'] 		= $getDataOders['programaplan'];
				$dato['nombre_contacto'] 	= $getDataOders['nombre_contacto'];
				$dato['email_contacto'] 	= $getDataOders['email_contacto'];
				$dato['comentarios'] 		= $getDataOders['comentarios'];
				$dato['telefono_contacto'] 	= $getDataOders['telefono_contacto'];
				$dato['producto'] 			= $getDataOders['producto'];
				$dato['agencia'] 			= $getDataOders['agencia'];
				$dato['nombre_agencia'] 	= $getDataOders['nombre_agencia'];
				$dato['total'] 				= $getDataOders['total'];
				$dato['codigo'] 			= $getDataOders['codigo'];
				$dato['fecha'] 				= $getDataOders['fecha'];
				$dato['vendedor'] 			= $getDataOders['vendedor'];
				$dato['cantidad'] 			= $getDataOders['cantidad'];
				$dato['status'] 			= $getDataOders['status'];
				$dato['origin_ip'] 			= $getDataOders['origin_ip'];
				$dato['tasa_cambio'] 		= $getDataOders['tasa_cambio'];
				$dato['family_plan'] 		= $getDataOders['family_plan'];
				$dato['referencia'] 		= $getDataOders['referencia'];
				$dato['link'] 				= LINK_REPORTE_VENTAS.$getDataOders['codigo']."&selectLanguage=$language&broker_session=$agency";
				$dato['beneficiaries'] 		= $passenger;
				return	$dato;
			}
		}else{
			return $this->getError('1020');
		}
    }
    public function getPvpPrice($data,$api){
		$plan		= $data['plan'];
		$country	= $data['pais'];
		$prefix		= $data['prefix'];
		$dataValida	= [
			'9092'	=> $prefix,
			'6037'	=> !(empty($plan) AND empty($country)),
			'6022'	=> $plan
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if(!empty($validatEmpty)){
			return $validatEmpty;
		}
		$restrictionPlan	= $this->verifyRestrictionPlan('',$plan,'',false,$api,true);
		if($restrictionPlan){
			return $restrictionPlan;
		}
		$verifyRestrictionOrigin	= $this->verifyRestrictionOrigin($plan,$country,$prefix);
		if(!empty($country)){
			if($verifyRestrictionOrigin){
				return $verifyRestrictionOrigin;
			}
		}else{
			$country	= 'all';
		}
		$data	= [
			'valor',
			'age_min',
			'age_max'
		];
		$dataPvpPriceBandas	= $this->selectDynamic('','plan_band_age',"id_plan='$plan'",$data);
		if(!empty($dataPvpPriceBandas)){
			return $dataPvpPriceBandas;
		}else{
			$filters = [
				'id_country' => $country
			];
			$dataPvpPrice	= $this->selectDynamic($filters,'plan_times',"id_plan='$plan'",['unidad','tiempo','valor']);
			if(!empty($dataPvpPrice)){
				return $dataPvpPrice;
			}else{
				return $this->getError('1060');
			}
		}
    }
    public function getTerms($data,$api){
		$plan							= $data['plan'];
		$language						= $data['lenguaje'];
		$dataValida	= [
			'6022'	=> $plan,
			'6021'	=> $language,
			'1030'	=> $this->validLanguage($language)
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if(!empty($validatEmpty)){
			return $validatEmpty;
		}
		$restrictionPlan		= $this->verifyRestrictionPlan('',$plan,'',false,$api,true);
		if($restrictionPlan){
			return $restrictionPlan;
		}
		$dataPlan			= $this->selectDynamic('','plans',"id='$plan'",array("name","description"));
		$datAgency			= $this->datAgency($api);
		$namePlan			= $dataPlan[0]['name'];
		$descriptionPlan	= $dataPlan[0]['description'];
		$idAgency			= $datAgency[0]['id_broker'];
		return [
			'id'			=> $plan,
			'name'			=> $namePlan,
			'description'	=> $descriptionPlan,
			'terms'			=> $this->getTermsMaster($plan,$language,$idAgency)
		];
    }
    public function getBeneficiariesByCode($data,$api){
		$code			= $data['code'];
		$dataValida		= [
			'6023'	=> $code
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if(!empty($validatEmpty)){
			return $validatEmpty;
		}
		$datAgency	= $this->datAgency($api);
		$id_broker 	= $datAgency[0]['id_broker'];
		$idUser 	= $datAgency[0]['user_id'];
		$isoCountry = $datAgency[0]['id_country'];
		$verifyVoucher 		= $this->verifyVoucher($code,$idUser,$isoCountry,'ADD');
		if($verifyVoucher){
			return $verifyVoucher;
		}
		return $this->dataBeneficiaries($code,'1');
	}
	public function getCountryRestricted($data,$api){
		$plan			= $data['plan'];
		$dataValida		= [
			'6022'	=> $plan
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if(!empty($validatEmpty)){
			return $validatEmpty;
		}
		$restrictionPlan		= $this->verifyRestrictionPlan('',$plan,'',false,$api,true);
		if($restrictionPlan){
			return $restrictionPlan;
		}
		$dataCountryRestricted = $this->dataCountryRestricted($plan);
		if(!empty($dataCountryRestricted)){
			return $dataCountryRestricted;
		}else{
			return [
				'status'	=> "No hay resultados",
				'message'	=> "No hay restricción de países para el plan seleccionado"
			];
		}
	}
	public function getUpgrades($data,$api){
		$plan 		= $data['plan'];
		$language	= $data['lenguaje'];
		$dataValida	= [
			'6037'	=> !(empty($plan) AND empty($language)),
			'9024'	=> $plan,
			'6021'	=> $language,
			'1030'	=> $this->validLanguage($language)
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if(!empty($validatEmpty)){
			return $validatEmpty;
		}
		$restrictionPlan		= $this->verifyRestrictionPlan('',$plan,'',false,$api,true);
		if($restrictionPlan){
			return $restrictionPlan;
		}
		$arrTypeUpgrades	= [
			'1' =>[
				'type_raider' => 'Valor' ,
				'rd_calc_type'=> 'Comprobante'
			],
			'2' =>[
				'type_raider' => 'Porcentage %' ,
				'rd_calc_type'=> 'Pasajero Especifico'
			],
			'3' =>[
				'type_raider' => 'Valor' ,
				'rd_calc_type'=> 'Pasajero General'
			],
			'4' =>[
				'type_raider' => 'Valor' ,
				'rd_calc_type'=> 'Por dia por Voucher'
			],
			'5' =>[
				'type_raider' => 'Valor' ,
				'rd_calc_type'=> 'Por dia por Pasajero'
			]
		];
		$result = $this->dataUpgradesPlan($plan,$language);
		if ($result){
			foreach ($result as $i => $value) {
				$arrResult[]['type_raider']		= $arrTypeUpgrades[$value['type_raider']]['type_raider'];
				$arrResult[$i]['rd_calc_type']	= $arrTypeUpgrades[$value['rd_calc_type']]['rd_calc_type'];
				$arrResult[$i]['id_raider']		= $value['id_raider'];
				$arrResult[$i]['cost_raider']	= $value['cost_raider'];
				$arrResult[$i]['name_raider']	= $value['name_raider'];
				$arrResult[$i]['value_raider']	= $value['value_raider'];
			}
			return $arrResult;
		}else{
			return $this->getError('5017');
		}
	}
	public function getSalesReport($data,$api){
		$startDate	= $data["desde"];
		$endDate	= $data["hasta"];
		$status		= $data["estatus"];
		$format		= $data["formato"];
		$startDateTrans = $this->transformerDate($startDate);
		$endDateTrans	= $this->transformerDate($endDate);
		$today 	= date('Y-m-d');
		$format	= strtolower($format);
		$ArrayValida	= [
			'6037'	=> !(empty($startDate) AND empty($endDate) AND empty($status)),
			'9063'	=> $startDate,
			'9064'	=> $endDate,
			'9065'	=> $status,
			'9017'	=> (in_array($status,[1,2,3])),
			'9066'	=> (in_array($format,['','json','excel'])),
			'3020'	=> $this->checkDates($startDate),
			'9067'	=> $this->checkDates($endDate),
			'3030'	=> !(strtotime($startDateTrans)	> strtotime($endDateTrans)),
			'9068'	=> !(strtotime($endDateTrans)	> strtotime($today)),
			'9069'	=> !(strtotime($startDateTrans)	> strtotime($today)),
		];
		$valid	= $this->validatEmpty($ArrayValida);
		if(!empty($valid)){
			return $valid;
		}
		switch ($status) {
			case 2:
				$statusEx = 5;
				break;
			case 3:
				$statusEx = 6;
				$status	= '';	
			break;
			default:
				$statusEx = 1;
				break;
		}
		$dataAgency	= $this->datAgency($api);
		$idUser		= $dataAgency['user_id'];
		$arrParameters 	= [
			'wbs'	=> 1,
			'desde'	=> $startDateTrans,
			'hasta'	=> $endDateTrans,
			'id_status'	=> $statusEx,
			'rangofecha'=> 1,
			'IdUser'	=> $idUser
		];
		$parameters = http_build_query($arrParameters);
		$urReport	= 'https://ilsbsys.com/app/reports/rpt_xls_reporte_ils.php?';
		$urlShort	= $this->shortUrl($urReport.$parameters);
		switch ($format) {
			case 'json':
				$response   =  $this->GetOrderdetallada_ils('',$startDateTrans,$endDateTrans,'',1, $status, '', '', 'orden','', $idUser,'spa',1);
				break;
			default:
				$response	= [
					'status' => 'OK',
					'Enlace de Descarga'=> trim($urlShort)
				];
				break;
		}
		if(empty($response)){
			$response = $this->getError('9015');
		}
		return $response;
	}
	public function getExchangeRate($data){
		$isoCountry = $data['iso_country'];
		$dataExchangeRate	= $this->dataExchangeRate($isoCountry);
		if(!empty($dataExchangeRate)){
			return $dataExchangeRate;
		}else{
			return [
				'status'	=> "No hay resultados",
				'message'	=> "No hay tasa de cambio asociada a éste país"
			];
		}
	}
	public function getRegionCountry(){
		return $this->dataCountryRegion();
	}
	public function getPlansCategory($data,$api){
		$language	= $data['lenguaje'];
		$dataValida	= [
			'6021'	=> $language,
			'1030'	=> $this->validLanguage($language)
		];
		$validatEmpty	= $this->validatEmpty($dataValida);
		if(!empty($validatEmpty)){
			return $validatEmpty;
		}
		return $this->dataPlanCategory($language);
	}
	public function getLanguages(){
		return $this->selectDynamic('','languages',"languages.active = '1'",['id','lg_id','name','short_name']);
	}
	public function getCurrencies(){
		return $this->selectDynamic('','currency',"id_status='1'",['id_currency','value_iso','desc_small'],'','',11);
	}
	public function getCountries(){
		return $this->selectDynamic('','countries',"c_status='Y'",['iso_country','description'],'','',260);
	}
	public function getRegions(){
		return $this->selectDynamic('','territory',"id_status='1'",['id_territory','desc_small']);
	}
*/
}
