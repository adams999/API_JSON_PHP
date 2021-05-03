<?php
class QuoteGeneral extends Model {
    public function restriction($dirigido, $broker_by_user, $id_broker, $id_broker1, $id_broker2, $id_broker3, $id_broker4, $id_broker5, $Plan_territory_destino, $destino, $Plan_territory_origen, $origen, $diaxpersona, $Plan_min_tiempo, $Plan_max_tiempo, $id_restric, $quote_general, $pers_disp, $cant_pas, $opcion_plan, $tipo_fechas,$plan_local) {
        $mostrarR   = 0;
        $mostrarRP  = 1;
        $mostrarRD  = 0;
        $mostrarRCP = 0;
        $mostrarlocal = 0;
        if ($dirigido == 1 || $dirigido == 0 || $dirigido == null) {
            if ($opcion_plan != 2) {
                $mostrarR = 1;
            }
        } elseif ($dirigido == 2) {
            if ($broker_by_user == $id_broker) {
                $mostrarR = 1;
            }
        } elseif ($dirigido == 3) {
            $arraybrokers = array($id_broker1, $id_broker2, $id_broker3, $id_broker4, $id_broker5);
            if (in_array($broker_by_user, $arraybrokers)) {
                $mostrarR = 1;
            }
        } elseif ($dirigido == 5) {
            if ($broker_by_user == 118) {
                $mostrarR = 1;
            }
        } elseif ($dirigido == 6) {
            $obj_broker    = new broker(); ######solo se requiere en este if#######
			$array_brokers = $obj_broker->Traer_SubBrokers($id_broker);
            if (in_array($broker_by_user, $array_brokers)) {
                $mostrarR = 1;
            }
        } elseif ($dirigido == 7) {
            if ($broker_by_user != 118) {
                if ($opcion_plan != 2) {
                    $mostrarR = 1;
                }
            }
        }
        if ($Plan_territory_destino != 0) {
            if ($Plan_territory_destino != $destino) {
                $mostrarRT = 0;
            } else {
                $mostrarRT = 1;
            }
        } else {
            $mostrarRT = 1;
        }
        if($destino==9){
            if($plan_local=='Y'){
                    $mostrarlocal=1;
                    }else{
                    $mostrarlocal=0;
                }
        }else{
            if($plan_local=='N'){
                $mostrarlocal=1;}
            }
        if ($Plan_territory_origen != 0) {
			$Array_Relacion = $this->Get_All_Relation_Restric2($id_restric, $origen, 1);
            if ($Array_Relacion[0]['iso_country']) {
                $mostrarRP = 0;
            } else {
                $mostrarRP = 1;
            }
        } else {
            $mostrarRP = 1;
        }
        if ($tipo_fechas == 2) {
            $mostrarRD = 1;
        } else {
            if ($diaxpersona >= $Plan_min_tiempo && $diaxpersona <= $Plan_max_tiempo) {
                $mostrarRD = 1;
            } else {
                $mostrarRD = 0;
            }
        }
        /////validacion de personas disponibles en el plan/////
        if (!empty($pers_disp)) {
            if ($cant_pas <= $pers_disp) {
                $mostrarRCP = 1;
            } else {
                $mostrarRCP = 0;
            }
        } else {
            $mostrarRCP = 1;
        }
        /////fin de validacion de personas disponibles en el plan/////
        if ($mostrarR == 1 && $mostrarRP == 1 && $mostrarRD == 1 && $mostrarRT == 1 && $mostrarRCP == 1 && $mostrarlocal== 1) {
            $result = true;
            $exito  = 1;
        } else {
            $result = false;
            $exito  = 0;
        }
        if ($quote_general == 1) {
            //retorno array con errores o aciertos
            $result = array($exito, $mostrarR, $mostrarRP, $mostrarRD, $mostrarRT, $mostrarRCP,$mostrarlocal);
        }
        return $result;
    }
    public function restriction2($dirigido, $broker_by_user, $id_broker, $id_broker1, $id_broker2, $id_broker3, $id_broker4, $id_broker5, $Plan_territory_destino, $destino, $Plan_territory_origen, $origen, $diaxpersona, $Plan_min_tiempo, $Plan_max_tiempo, $id_restric, $quote_general) {
        $mostrarR   = 0;
        $mostrarRP  = 1;
        $mostrarRD  = 0;
        $mostrarRDe = 1;
        if ($dirigido == 1 || $dirigido == 0 || $dirigido == 5 || $dirigido == null) {
            $mostrarR = 1;
        } elseif ($dirigido == 2) {
            if ($broker_by_user == $id_broker) {
                $mostrarR = 1;
            }
        } elseif ($dirigido == 3) {
            $arraybrokers = array($id_broker1, $id_broker2, $id_broker3, $id_broker4, $id_broker5);
            if (in_array($broker_by_user, $arraybrokers)) {
                $mostrarR = 1;
            }
        }
        if ($Plan_territory_destino != 0) {
            //var_dump("$Plan_territory_destino == $destino");
            if ($Plan_territory_destino == $destino) {
                $mostrarRDe = 1;
            } else {
                $mostrarRDe = 0;
            }
        }
        if ($Plan_territory_origen != 0) {
            $Array_Relacion = $this->Get_All_Relation_Restric2($id_restric, $origen, 1);
            if ($Array_Relacion[0]['iso_country']) {
                $mostrarRP = 0;
            } else {
                $mostrarRP = 1;
            }
        } else {
            $mostrarRP = 0;
        }
        if ($diaxpersona >= $Plan_min_tiempo && $diaxpersona <= $Plan_max_tiempo) {
            $mostrarRD = 1;
        } else {
            $mostrarRD = 0;
        }
        if ($mostrarR == 1 && $mostrarRP == 1 && $mostrarRD == 1 && $mostrarRDe == 1) {
            $result = true;
            $exito  = 1;
        } else {
            $result = false;
            $exito  = 0;
        }
        if ($quote_general == 1) {
            //retorno array con errores o aciertos
            $result = array($exito, $mostrarR, $mostrarRP, $mostrarRD, $mostrarRDe);
        }
        return $result;
    }
    public function restriction3($dirigido, $broker_by_user, $id_broker, $id_broker1, $id_broker2, $id_broker3, $id_broker4, $id_broker5, $Plan_territory_destino, $destino, $Plan_territory_origen, $origen, $Plan_min_tiempo, $Plan_max_tiempo, $id_restric, $quote_general) {
        $mostrarR   = 0;
        $mostrarRP  = 1;
        $mostrarRDe = 1;
        if ($dirigido == 1 || $dirigido == 0 || $dirigido == 5 || $dirigido == null) {
            $mostrarR = 1;
        } elseif ($dirigido == 2) {
            if ($broker_by_user == $id_broker) {
                $mostrarR = 1;
            }
        } elseif ($dirigido == 3) {
            $arraybrokers = array($id_broker1, $id_broker2, $id_broker3, $id_broker4, $id_broker5);
            if (in_array($broker_by_user, $arraybrokers)) {
                $mostrarR = 1;
            }
        }
        if ($Plan_territory_destino != 0) {
            if ($Plan_territory_destino == $destino) {
                $mostrarRDe = 1;
            } else {
                $mostrarRDe = 0;
            }
        }
        if ($Plan_territory_origen != 0) {
            $Array_Relacion = $this->Get_All_Relation_Restric2($id_restric, $origen, 1);
            if ($Array_Relacion[0]['iso_country']) {
                $mostrarRP = 0;
            } else {
                $mostrarRP = 1;
            }
        } else {
            $mostrarRP = 0;
        }
        if ($mostrarR == 1 && $mostrarRP == 1 && $mostrarRDe == 1) {
            $result = true;
            $exito  = 1;
        } else {
            $result = false;
            $exito  = 0;
        }
        if ($quote_general == 1) {
            //retorno array con errores o aciertos
            $result = array($exito, $mostrarR, $mostrarRP, $mostrarRDe);
        }
        return $result;
    }
    public function restric_edad_banda($edad, $idplan, $origen) {
        $bandera     = 0;
        $queEmptp    = $this->Get_plan_band_age_valor($edad, $idplan, $origen);
        $cntPremium = count($queEmptp);
        if ($cntPremium < 1) {
            //si no tiene precios por pais
            $queEmptp = $this->Get_plan_band_age_valor($edad, $idplan, 'all');
            $totalaux = count($queEmptp);
            if ($totalaux < 1) {
                $bandera = 1;
            }
        }
        return $bandera;
    }
    public function factorpareja($pasajeros, $arrPlan, $mayores75) {
        $arreglopareja['planpareja']         = 0;
        $arreglopareja['activofactorpareja'] = 'no';
        $arreglopareja['pareja_plan']        = ($arrPlan['plan_pareja'] == 'Y') ? 'Y' : 'N';
        if ($pasajeros == 2 && $arrPlan['plan_pareja'] == 'Y' && (OVERAGE_IN_FACTORS == 1 || $mayores75 == 0)) {
            $arreglopareja['planpareja']         = 1;
            $arreglopareja['factor_pareja']      = $arrPlan['factor_pareja'] != '0.0' ? $arrPlan['factor_pareja'] : 1;
            $arreglopareja['factor_pareja_cost'] = $arrPlan['factor_pareja_cost'];
            $arreglopareja['activofactorpareja'] = 'si';
        } else {
            $arreglopareja['factor_pareja']      = 0;
            $arreglopareja['factor_pareja_cost'] = 0;
            $arreglopareja['activofactorpareja'] = 'no';
        }
        return $arreglopareja;
    }
    public function quotePlanbenefis($idCategoria, $diasxpersona, $id_country_broker, $destino, $origen, $edades, $desde, $hasta, $id_broker='', $id_plan_cotiza=false, $id_multiple_plans=false, $ignoreRestrictions=false, $isGroup=false, $intervalo='') {
        $arrPlan          = $this->Get_Plans_categoria($idCategoria, '', '', $id_plan_cotiza, $id_multiple_plans, $id_broker, '', $intervalo);
        $cntPlan          = count($arrPlan);
        $arrBroker        = $this->Get_Broker_Price($id_broker);
        $arrAges          = explode(',', substr($edades, 0, -1));
        $cntAges          = count($arrAges);
        $priceType        = $arrBroker[0]['price_type'];
        $maxAgeFamilyPlan = 21;
        $cnt              = 0;
        $cntError         = 0;
        for ($i = 0; $i < $cntPlan; $i++) {
            $tmenor                     = 0;
            $tmenor_cost                = 0;
            $aux                        = 0;
            $mayores75                  = 0;
            $menores75                  = 0;
            $max_age                    = 0;
            $min_age                    = 0;
            $max_family                 = 0;
            $factor_family              = 0;
            $TotalValorBloquesAdicional = 0;
            $diaAdicional               = 0;
            $TotalValorBloquesAdicional = 0;
            $TotalCostoBloquesAdicional = 0;
            $total                      = 0;
            $valorMenor2                = 0;
            $subTotalMenores            = 0;
            $valorMayor                 = 0;
            $subTotalMayore             = 0;
            $CostoMenor2                = 0;
            $subTotalMenores_costo      = 0;
            $subTotalMenores_neto       = 0;
            $CostoMayor                 = 0;
            $netoMayor  				= 0;
            $subTotalMayore_cost        = 0;
            $subTotalMayore_neto        = 0;
            $tiepoid                    = 0;
            $planfamiliar               = 0;
            $total_costo                = 0;
            $total_neto               	= 0;
            $primer_bloque              = true;
            $valor_prima                = 0;
            $costo_prima                = 0;
            $neto_prima                 = 0;
            $ValorBloquesAdicional      = 0;
            $CostoBloquesAdicional      = 0;
            $valorbloques               = 0;
            $overageValid              = true;
            if (($arrPlan[$i]['overage_factor'] < 1 && $arrPlan[$i]['normal_age'] != $arrPlan[$i]['max_age']) || $arrPlan[$i]['normal_age'] > $arrPlan[$i]['max_age'] || $arrPlan[$i]['normal_age'] < $arrPlan[$i]['min_age'] || $arrPlan[$i]['unidad'] == 'bandas') {
                $overageValid = false;
            }
            for ($iEd = 0; $iEd < $cntAges; $iEd++) {
            	if ($overageValid) {
            		if ($arrAges[$iEd] > $arrPlan[$i]['normal_age']) {
                    	$mayores75++;
	                } else {
	                    $menores75++;
	                }
            	}
                if ($arrAges[$iEd] > $arrPlan[$i]['max_age']) {
                    $max_age++;
                }
                if ($arrAges[$iEd] < $arrPlan[$i]['min_age']) {
                    $min_age++;
                }
                if ($arrAges[$iEd] >= $maxAgeFamilyPlan) {
                    $max_family++;
                }
            }
            if ($ignoreRestrictions) {
                $mostrar_R[0] = 1;
            } else {
                $mostrar_R = $this->restriction($arrPlan[$i]['dirigido'], $id_broker, $arrPlan[$i]['id_broker'], $arrPlan[$i]['id_broker1'], $arrPlan[$i]['id_broker2'], $arrPlan[$i]['id_broker3'], $arrPlan[$i]['id_broker4'], $arrPlan[$i]['id_broker5'], $arrPlan[$i]['id_territory_destino'], $destino, $arrPlan[$i]['id_territory_origen'], $origen, $diasxpersona, $arrPlan[$i]['min_tiempo'], $arrPlan[$i]['max_tiempo'], $arrPlan[$i]['id_restric'], '1', $arrPlan[$i]['num_pas'], $cntAges, $arrBroker[0]['opcion_plan'],'',$arrPlan[$i]['plan_local']);
            }
            if ($max_age == 0 && $min_age == 0 && $mostrar_R[0] == 1) {
                $restCM       = $menores75;
                $planfamiliar = 0;
                $activofactor = "no";
                $moneda       = '';
                $pareja = $this->factorpareja($cntAges, $arrPlan[$i], $mayores75);
                $family_plan = ($arrPlan[$i]['family_plan'] == 'Y') ? 'Y' : 'N';
                if ($family_plan == 'Y' && (OVERAGE_IN_FACTORS == 1 || $mayores75 == 0)) {
                    $maxFamilyCnt = (!empty($arrPlan[$i]['family_plan_cantidad'])) ? $arrPlan[$i]['family_plan_cantidad'] : 4;
                    if ($cntAges > 2 && $cntAges <= ($maxFamilyCnt + 2) && $max_family == 2) {
                        $planfamiliar = 1;
                        $activofactor = "si";
                    }
                    $factor_family      = !empty($arrPlan[$i]['factor_family']) ? $arrPlan[$i]['factor_family'] : 1;
                    $factor_family_cost = !empty($arrPlan[$i]['factor_family_cost']) ? $arrPlan[$i]['factor_family_cost'] : 0;
                }
                if ($arrPlan[$i]['value_iso'] == "USD" || empty($arrPlan[$i]['value_iso'])) {
                    $moneda = "US$";
                } else {
                    $moneda = $arrPlan[$i]['value_iso'];
                }
                $overageFactor 	= $arrPlan[$i]['overage_factor'];
                $overageCost 	= $arrPlan[$i]['overage_factor_cost'];
                if ($arrPlan[$i]['unidad'] == 'bandas') {
                    $tiepoid               = ' ';
                    $total_banda           = 0;
                    $total_rangos          = 0;
                    $total                 = 0;
                    $conta                 = 0;
                    $cntProcess = 0;
                    $menores               = 0;
                    $primer_menores        = 0;
                    asort($arrAges);
                    $arreglopareja['factor_pareja']      = 0;
                    $arreglopareja['factor_pareja_cost'] = 0;
                    $arreglopareja['activofactorpareja'] = "no";
                    $arreglopareja['pareja_plan']        = "N";
                    $diaspasa                            = $diasxpersona;
                   	$arrPremium = $this->Get_plan_band_age($arrPlan[$i]['id'], $id_broker, $origen);
                    $cntPremium = count($arrPremium);
                    if (!$cntPremium) {
                    	$arrPremium = $this->Get_plan_band_age($arrPlan[$i]['id'], $id_broker, 'all');
                          $cntPremium = count($arrPremium);
                    }
					$tiepoid = $arrPremium[0]['id'];
                    if ($family_plan == 'Y') {
                        $menores = $cntAges - $max_family;
                        if ($max_family == 2 && $menores <= 4) {
                            $factor = 'si';
                        }
                    }
                    $firstMinorFamily = 0;
                    for ($cAg = 0; $cAg < $cntAges; $cAg++) {
                        if ($factor == 'si' && $arrAges[$cAg] <= $maxAgeFamilyPlan && $firstMinorFamily < 2) {
                            $arrPayFamily[$cAg]['menores'] = 2;
                            $firstMinorFamily++;
                        } else {
                            $arrPayFamily[$cAg]['menores'] = 1;
                        }
                        $arrPayFamily[$cAg]['edad'] = $arrAges[$cAg];
                    }
                    $edad_min      = 99999;
                    $edad_max      = 0;
                    $primAdicional = false;
                    $cantAdicional = 0;
                    $confgValida   = true;
                    $adicional     = [];
                    $arrPrimas     = [];
                    for ($iV = 0; $iV < $cntPremium; $iV++) {
                        if ($arrPremium[$iV]['adicional'] == '1') {
                            $adicional     = $arrPremium[$iV];
                            $primAdicional = true;
                            $cantAdicional++;
                        } else {
                            $arrPrimas[] = $arrPremium[$iV];
                        }
                    }
                    $cantPrimas = count($arrPrimas);
                    for ($iV = 0; $iV < $cantPrimas; $iV++) {
                        $precio_base = $arrPrimas[$iV]['precio_base'];
                        $costo_base  = $arrPrimas[$iV]['precio_base_cost'];
                        $neto_base   = $arrPrimas[$iV]['precio_base_neto'];
                        $valor_plan  = $arrPrimas[$iV]['valor'];
                        $costo_plan  = $arrPrimas[$iV]['cost'];
                        $valor_neto  = $arrPrimas[$iV]['valorNeto'];
                        if ($arrPrimas[$iV]['unidad'] == 'bloques') {
                            $tiempo = $arrPrimas[$iV]['tiempo'];
                            if ($tiempo < $diaspasa && $primAdicional === true) {
                                $tiempoAdicional = $diaspasa - $tiempo;
                                $valorAdicional  = 0;
                                $costoAdicional  = 0;
                                $netoAdicional   = 0;
                                if ($adicional['unidad'] == 'bloques' && $adicional['valor'] > 0) {
                                    if ($tiempoAdicional < $adicional['tiempo']) {
                                        $valorAdicional = $adicional['valor'];
                                        $costoAdicional = $adicional['cost'];
                                        $netoAdicional = $adicional['valorNeto'];
                                    } else {
                                        $numAdicionales = ($tiempoAdicional % $adicional['tiempo']) ? (intval($tiempoAdicional / $adicional['tiempo']) + 1) : intval($tiempoAdicional / $adicional['tiempo']);
                                        $valorAdicional = $adicional['valor'] * $numAdicionales;
                                        $costoAdicional = $adicional['cost'] * $numAdicionales;
                                        $netoAdicional = $adicional['valorNeto'] * $numAdicionales;
                                    }
                                } else if ($adicional['valor'] > 0) {
                                    $valorAdicional = $adicional['valor'] * $tiempoAdicional;
                                    $costoAdicional = $adicional['cost'] * $tiempoAdicional;
                                    $netoAdicional = $adicional['valorNeto'] * $tiempoAdicional;
                                }
                                $precio_baseAdicional = $adicional['precio_base'];
                                $costo_baseAdicional  = $adicional['precio_base_cost'];
                                $neto_baseAdicional   = $adicional['precio_neto'];
                                $valor_plan          += ($valorAdicional + $precio_baseAdicional);
                                $costo_plan          += ($costoAdicional + $costo_baseAdicional);
                           		$valor_neto			 += ($netoAdicional + $neto_baseAdicional);
                           	} else if ($tiempo < $diaspasa && $primAdicional === false) {
                                $numBloques = ($diaspasa % $tiempo) ? (intval($diaspasa / $tiempo) + 1) : intval($diaspasa / $tiempo);
                                $valor_plan = $valor_plan * $numBloques;
                                $costo_plan = $costo_plan * $numBloques;
                                $valor_neto = $valor_neto * $numBloques;
                            }
                            $precio_ban = $precio_base + $valor_plan;
                            $costo_ban  = $costo_base + $costo_plan;
                            $neto_ban  = $neto_base + $valor_neto;
                        } else {
                            $precio_ban = $precio_base + ($diaspasa * $valor_plan);
                            $costo_ban  = $costo_base + ($diaspasa * $costo_plan);
                            $neto_ban 	= $neto_base + ($diaspasa * $valor_neto);
                        }
                        $cntBnd     = 0;
                        $cntPasBnd	= 0;
                        $priceBnd	= 0;
                        //Busco la edad minima de los intervalos de edades
                        if ($arrPremium[$iV]['age_min'] < $edad_min) {
                            $edad_min = $arrPremium[$iV]['age_min'];
                        }
                        //Busco la edad maxima de los intervalos de edades
                        if ($arrPremium[$iV]['age_max'] > $edad_max) {
                            $edad_max = $arrPremium[$iV]['age_max'];
                        }
                        for ($iX = 0; $iX < $cntAges; $iX++) {
                            if ($arrPayFamily[$iX]['edad'] >= $arrPremium[$iV]['age_min'] && $arrPayFamily[$iX]['edad'] <= $arrPremium[$iV]['age_max']) {
                                if ($factor == 'si' && $arrPayFamily[$iX]['edad'] < $maxAgeFamilyPlan && $arrPayFamily[$iX]['menores'] == 2) {
                                    $cntBnd++;
                                } else {
                                    $priceBnd += $precio_ban;
                                    $costBnd  +=$costo_ban;
                                    $cntBnd++;
                                }
                                $cntPasBnd++;
                                $cntProcess++;
                            }
                        }
                        if ($cntBnd > 0) {
                            $array_max[$conta]      = $arrPremium[$iV]['age_max'];
                            $array_min[$conta]      = $arrPremium[$iV]['age_min'];
                            $array_precio[$conta]   = $precio_ban;
                            $array_costo[$conta]    = $costo_ban;
                            $array_neto[$conta]    	= $neto_ban;
                            $array_subtotal[$conta] = $priceBnd;
                            $total_banda           += $priceBnd;
                            $total_cost_banda      += $costBnd;
                            $array_npas[$conta]     = $cntPasBnd;
                            $conta++;
                        }
                    }
                    $banda           = 'si';
                    $total_rangos    = $conta;
                    $total           = $total_banda;
                    $total_costo     = $total_cost_banda;
                    $valorMenor2     = '-';
                    $valorMayor      = '-';
                    $subTotalMenores = '-';
                    $subTotalMayore  = '-';
                    if ($cntAges > $cntProcess) {
                        $total = 0;
                    }
                } else if ($overageValid) {
                    $banda                  = '';
                    $hay_adicional          = 0;
                    $siguiente_bloque_mayor = 0;
                    $contador               = 0;
                    $contador_sin_AD        = 0;
                    $primer_sel             = false;
                    $tipo_unidad_calculo    = '';
                    $mesAdicional           = 0;
                    $ValorAdicional         = 0;
                    $CostoAdicional         = 0;
                    $TiempoAdicional        = 0;
                    $UnidadAdicional        = '';
                    $lassemanas_totales     = 0;
                    $semana_adicional       = 0;
                    $tiempo_calculo         = '';
                    $arrPremium = $this->Get_premium_plan($arrPlan[$i]['id'], $id_broker, $origen);
                    $cntPremium = count($arrPremium);
                    if ($cntPremium < 1) {
                        $arrPremium = $this->Get_premium_plan($arrPlan[$i]['id'], $id_broker, 'all');
                    }
                    unset($unidades);
                    unset($unidades_sin_AD);
                    unset($unidad_adicional);
                    unset($tipo_adicional);
                    $unidades             = [];
                    $unidades_sin_AD      = [];
                    $unidad_adicional     = [];
                    $tipo_adicional       = [];
                    $cntAditionals 		  = 0;
                    foreach ($arrPremium as $premium) {
                        if ($premium['adicional'] == '1') {
                            $unidad_adicional[$cntAditionals] = $premium;
                            $tipo_adicional[$cntAditionals]   = $premium['unidad'];
                            $hay_adicional = 1;
                            $cntAditionals++;
                        } else {
                            $unidades_sin_AD[$contador_sin_AD] = $premium['unidad'];
                            $contador_sin_AD++;
                        }
                        $unidades[$contador] = $premium['unidad'];
                        $contador++;
                    }
                    $type_unidades_sin_AD     = array_unique($unidades_sin_AD);
                    $cantidad_unidades_sin_AD = count($type_unidades_sin_AD);
                    $type_unidades            = array_unique($unidades);
                    $cantidad_unidades        = count($type_unidades);
                    $diferencia   = $this->calcular_meses_adic2($desde, $hasta, 'MM/DD/AAAA');
                    $arradicional = explode("|", $diferencia);
                    $losmeses     = $arradicional["0"];
                    $lassemanas   = $arradicional["1"];
                    if ($losmeses <= 1) {
                        $losmeses   = 2;
                        $lassemanas = 0;
                    }
                    $lassemanas_totales = intval($diasxpersona / 7);
                    if (($diasxpersona % 7) && $hay_adicional == 0) {
                        $lassemanas_totales++;
                    }
                    for ($iV = 0; $iV < $contador; $iV++) {
                        if (!in_array("dias", $type_unidades_sin_AD) && $cantidad_unidades_sin_AD == 1 && $arrPremium[$iV]['adicional'] != 1) {
                            $tipo_unidad_calculo = $arrPremium[$iV]['unidad'];
                            $tiempo_calculo      = $diasxpersona;
                            if ($tipo_unidad_calculo == 'semanas') {
                                $tiempo_calculo = $lassemanas_totales;
                            } else if ($tipo_unidad_calculo == 'meses') {
                                $tiempo_calculo = $losmeses;
                            }
                            if ($tiempo_calculo >= $arrPremium[$iV]['tiempo'] || $primer_sel === false) {
                                ##########-SI HAY ADICIONAL O LA SIGUIENTE PRIMA ES VACIA-#########
                                if ($hay_adicional == 1 || empty($arrPremium[$iV + 1]['valor']) || $tiempo_calculo <= $arrPremium[$iV]['tiempo']) {
                                    $Tiempo_sel = $arrPremium[$iV]['tiempo'];
                                    $Valor_sel  = $arrPremium[$iV]['valor'];
                                    $Costo_sel  = $arrPremium[$iV]['cost'];
                                    $Neto_sel  	= $arrPremium[$iV]['valorNeto'];
                                    $tiepoid    = $arrPremium[$iV]['id'];
                                    $primer_sel = true;
                                } else {
                                    $Tiempo_sel = $arrPremium[$iV + 1]['tiempo'];
                                    $Valor_sel  = $arrPremium[$iV + 1]['valor'];
                                    $Costo_sel  = $arrPremium[$iV + 1]['cost'];
                                    $Neto_sel  	= $arrPremium[$iV + 1]['valorNeto'];
                                    $tiepoid    = $arrPremium[$iV + 1]['id'];
                                    $primer_sel = true;
                                }
                            }
                        } else if (in_array("dias", $type_unidades_sin_AD) && $cantidad_unidades_sin_AD == 1 && $arrPremium[$iV]['adicional'] != 1) {
                            $tipo_unidad_calculo = $arrPremium[$iV]['unidad'];
                            if ($diasxpersona > $aux) {
                                $tiepoid    = $arrPremium[$iV]['id'];
                                $aux        = $arrPremium[$iV]['tiempo'];
                                $Tiempo_sel = $arrPremium[$iV]['tiempo'];
                                $Valor_sel  = $arrPremium[$iV]['valor'];
                                $Costo_sel  = $arrPremium[$iV]['cost'];
                                $Neto_sel  	= $arrPremium[$iV]['valorNeto'];
                            }
                        }
                    }
                    if ($tipo_unidad_calculo == "bloques") {
                        $diaAdicional = ($diasxpersona - $Tiempo_sel);
                        $valorMenor2  = $Valor_sel;
                        $CostoMenor2  = $Costo_sel;
                        $netoMenor2	  = $Neto_sel;
                    } else if ($tipo_unidad_calculo == "dias") {
                        if ($Tiempo_sel < 365) {
                            $dias_calculo = $diasxpersona;
                            if ($Tiempo_sel < $diasxpersona && $hay_adicional == 1) {
                                $dias_calculo = $Tiempo_sel;
                                $diaAdicional = $diasxpersona - $Tiempo_sel;
                            }
                            $valorMenor2 = $Valor_sel * $dias_calculo;
                            $CostoMenor2 = $Costo_sel * $dias_calculo;
                            $netoMenor2	 = $Neto_sel * $dias_calculo;
                        } else {
                            $valorMenor2 = $Valor_sel;
                            $CostoMenor2 = $Costo_sel;
                            $netoMenor2	  = $Neto_sel;
                        }
                    } else if ($tipo_unidad_calculo == "meses") {
                        $valorMenor2  = $Valor_sel;
                        $CostoMenor2  = $Costo_sel;
                        $netoMenor2	  = $Neto_sel;
                        $diaAdicional = $diasxpersona - ($Tiempo_sel * 30);
                    } else if ($tipo_unidad_calculo == "semanas") {
                        $valorMenor2  = $Valor_sel * $lassemanas_totales;
                        $CostoMenor2  = $Costo_sel * $lassemanas_totales;
                        $netoMenor2	  = $Neto_sel * $lassemanas_totales;
                        $diaAdicional = $diasxpersona - ($Tiempo_sel * 7);
                    }
                    $diaAdicional         = ($diaAdicional >= 0) ? $diaAdicional : 0;
                    $semana_adicional     = ($semana_adicional >= 0) ? $semana_adicional : 0;
                    $mesAdicional         = ($mesAdicional >= 0) ? $mesAdicional : 0;
                    $AD_tipo              = '';
                    $valor_AD             = 0;
                    $costo_AD             = 0;
                    $neto_AD              = 0;
                    $tiempo_AD            = 0;
                    $AD_valid             = false;
                    $total_adicional      = 0;
                    $total_adicional_cost = 0;
                    $total_adicional_neto = 0;
                    if ($hay_adicional == 1 && $diaAdicional > 0) {
                        $AD_valid = true;
                        if ($unidad_adicional[0]['unidad'] == 'bloques' && $cntAditionals == 1) {
                            $AD_tipo     = 'bloques';
                            $valor_AD    = $unidad_adicional[0]['valor'];
                            $costo_AD    = $unidad_adicional[0]['cost'];
                            $neto_AD     = $unidad_adicional[0]['valorNeto'];
                            $tiempo_AD   = $unidad_adicional[0]['tiempo'];
                            $Num_bloques = intval($diaAdicional / $tiempo_AD);
                            if ($diaAdicional % $tiempo_AD) {
                                $Num_bloques++;
                            }
                            $total_adicional      = $valor_AD * $Num_bloques;
                            $total_adicional_cost = $costo_AD * $Num_bloques;
                            $total_adicional_neto = $neto_AD  * $Num_bloques;
                        } else if ($unidad_adicional[0]['unidad'] == 'dias' && $cntAditionals == 1) {
                            $AD_tipo              = 'dias';
                            $valor_AD             = $unidad_adicional[0]['valor'];
                            $costo_AD             = $unidad_adicional[0]['cost'];
                            $neto_AD              = $unidad_adicional[0]['valorNeto'];
                            $total_adicional      = $valor_AD * $diaAdicional;
                            $total_adicional_cost = $costo_AD * $diaAdicional;
                            $total_adicional_neto = $neto_AD  * $diaAdicional;
                        } else if ((in_array("semanas", $tipo_adicional) || in_array("meses", $tipo_adicional)) && $cntAditionals <= 2) {
                            $valor_AD_sem = 0;
                            $costo_AD_sem = 0;
                            $neto_AD_sem  = 0;
                            $cont_AD_sem  = 0;
                            $valor_AD_mes = 0;
                            $costo_AD_mes = 0;
                            $neto_AD_mes  = 0;
                            $cont_AD_mes  = 0;
                            foreach ($unidad_adicional as $UN_adc) {
                                if ($UN_adc['unidad'] == 'semanas') {
                                    $AD_tipo      = 'semanas';
                                    $valor_AD_sem = $UN_adc['valor'];
                                    $costo_AD_sem = $UN_adc['cost'];
                                    $neto_AD_sem  = $UN_adc['valorNeto'];
                                    $cont_AD_sem++;
                                } else if ($UN_adc['unidad'] == 'meses') {
                                    $AD_tipo      = 'meses';
                                    $valor_AD_mes = $UN_adc['valor'];
                                    $costo_AD_mes = $UN_adc['cost'];
                                    $neto_AD_mes  = $UN_adc['valorNeto'];
                                    $cont_AD_mes++;
                                }
                            }
                            if ($cont_AD_mes > 1 || $cont_AD_sem > 1) {
                                $AD_valid = false;
                            } else {
                                if (($cont_AD_mes + $cont_AD_sem) == 2) {
                                    $AD_tipo = 'meses/semanas';
                                }
                                $semanas = intval($diaAdicional / 7);
                                if ($diaAdicional % 7) {
                                    $semanas++;
                                }
                                $meses = intval($diaAdicional / 30);
                                if ($meses > 0 && $cont_AD_mes > 0 && $valor_AD_mes > 0) {
                                    $semanas = intval(($diaAdicional - ($meses * 30)) / 7);
                                    if (($diaAdicional - ($meses * 30)) % 7) {
                                        $semanas++;
                                    }
                                }
                                $total_adicional      = ($valor_AD_sem * $semanas) + ($valor_AD_mes * $meses);
                                $total_adicional_cost = ($costo_AD_sem * $semanas) + ($costo_AD_mes * $meses);
                                $total_adicional_neto = ($neto_AD_sem * $semanas) + ($neto_AD_mes * $meses);
                            }
                        } else {
                            $AD_valid = false;
                        }
                        if ($AD_valid && $total_adicional > 0 && $AD_tipo != '') {
                            $valorMenor2 = $valorMenor2 + $total_adicional;
                            $CostoMenor2 = $CostoMenor2 + $total_adicional_cost;
                            $netoMenor2  = $netoMenor2 + $total_adicional_neto;
                        } else {
                            $valorMenor2 = 0;
                            $CostoMenor2 = 0;
                            $netoMenor2  = 0;
                        }
                    }
                    $valor_prima = truncateFloat($valorMenor2, 3);
                    $costo_prima = truncateFloat($CostoMenor2, 3);
                    $neto_prima  = truncateFloat($netoMenor2, 3);
                    $subTotalMenores       = ($valorMenor2 * $restCM);
                    $subTotalMenores_costo = ($CostoMenor2 * $restCM);
                    $subTotalMenores_neto  = ($netoMenor2 * $restCM);
                    $valorMayor          = truncateFloat($valor_prima * $arrPlan[$i]['overage_factor'], 3);
                    $CostoMayor          = truncateFloat($costo_prima * $arrPlan[$i]['overage_factor_cost'], 3);
                    $netoMayor			 = truncateFloat($neto_prima * $arrPlan[$i]['overage_factor'], 3);
                    $subTotalMayore      = $valorMayor * $mayores75;
                    $subTotalMayore_cost = $CostoMayor * $mayores75;
                    $subTotalMayore_neto = $netoMayor * $mayores75;
                    $total       = $subTotalMenores + $subTotalMayore;
                    $total_costo = $subTotalMenores_costo + $subTotalMayore_cost;
                    $total_neto  = $subTotalMenores_neto + $subTotalMayore_neto;
                    if ($activofactor == 'si') {
                        $subTotalMenores       = 0;
                        $subTotalMayore        = 0;
                        $subTotalMenores_costo = 0;
                        $subTotalMayore_cost   = 0;
                        $subTotalMenores_neto  = 0;
                        $subTotalMayore_neto   = 0;
                        $total                 = ($valor_prima * $factor_family);
                        $total_costo           = ($costo_prima * $factor_family_cost);
                        $total_neto            = ($neto_prima * $factor_family);
                        $precioUnitario        = $total / $cntAges;
                        $costoUnitario         = $total_costo / $cntAges;
                        $netoUnitario          = $total_neto / $cntAges;
                        $valorMenorFamily      = $precioUnitario;
                        $valorMayorFamily      = $precioUnitario * $arrPlan[$i]['overage_factor'];
                        $subTotalMenores       = $precioUnitario * $menores75;
                        $subTotalMayore        = ($precioUnitario * $mayores75) * $arrPlan[$i]['overage_factor'];
                        $subTotalMenores_costo = $costoUnitario / $menores75;
                        $subTotalMayore_cost   = ($costoUnitario * $mayores75) * $arrPlan[$i]['overage_factor_cost'];
                        $subTotalMenores_neto  = $netoUnitario / $menores75;
                        $subTotalMayore_neto   = ($netoUnitario * $mayores75) * $arrPlan[$i]['overage_factor'];
                        $total       = $subTotalMenores + $subTotalMayore;
                        $total_costo = $subTotalMenores_costo + $subTotalMayore_cost;
                        $total_neto  = $subTotalMenores_neto + $subTotalMayore_neto;
                    } else if ($pareja['activofactorpareja'] == 'si') {
                        $subTotalMenores       = 0;
                        $subTotalMayore        = 0;
                        $subTotalMenores_costo = 0;
                        $subTotalMayore_cost   = 0;
                        $subTotalMenores_neto  = 0;
                        $subTotalMayore_neto   = 0;
                        $total                 = ($valor_prima * $pareja['factor_pareja']);
                        $total_costo           = ($costo_prima * $pareja['factor_pareja_cost']);
                        $total_neto            = ($neto_prima * $pareja['factor_pareja']);
                        $precioUnitario        = $total / $cntAges;
                        $costoUnitario         = $total_costo / $cntAges;
                        $netoUnitario          = $total_neto / $cntAges;
                        $valorMenorPareja      = $precioUnitario;
                        $valorMayorPareja      = $precioUnitario * $arrPlan[$i]['overage_factor'];
                        $subTotalMenores       = $precioUnitario * $menores75;
                        $subTotalMayore        = ($precioUnitario * $mayores75) * $arrPlan[$i]['overage_factor'];
                        $subTotalMenores_costo = $costoUnitario / $menores75;
                        $subTotalMayore_cost   = ($costoUnitario * $mayores75) * $arrPlan[$i]['overage_factor_cost'];
                        $subTotalMenores_neto  = $netoUnitario / $menores75;
                        $subTotalMayore_neto   = ($netoUnitario * $mayores75) * $arrPlan[$i]['overage_factor'];
                        $total       = $subTotalMenores + $subTotalMayore;
                        $total_costo = $subTotalMenores_costo + $subTotalMayore_cost;
                        $total_neto  = $subTotalMenores_neto + $subTotalMayore_neto;
                    }
                }
                    //~ ------- Manejo del impuesto del plan---------------/ 
    			$tax1 = 0;
    			$tax2 = 0;
    			$porcTax1 = 0;
    			$porcTax2 = 0;
    			$actTax = 0;
    			$total_neto = $total; 
    			if($arrPlan[$i]['impuesto'] == '1'){    
    				$arrImpuesto = $this->get_plans_impuesto($arrPlan[$i]['id'], $origen);
    				if(count($arrImpuesto) < 1){
    					$arrImpuesto = $this->get_plans_impuesto($arrPlan[$i]['id'], 'all');
    				}    
    				if(count($arrImpuesto) > 0){    						
    					if($arrImpuesto[0]['tax1'] > 0 &&  $arrImpuesto[0]['status1'] == 1){
    						$porcTax1 = $arrImpuesto[0]['tax1'];
    						$tax1 = truncateFloat(($arrImpuesto[0]['tax1'] * ($total_neto / 100)),3);
    						$actTax = 1;
    					}
    					if($arrImpuesto[0]['tax2'] > 0 &&  $arrImpuesto[0]['status2'] == 1){
    						$porcTax2 = $arrImpuesto[0]['tax2'];
    						$tax2 = truncateFloat(($arrImpuesto[0]['tax2'] * ($total_neto / 100)),3);
    						$actTax = 1;
    					}
    				}
    				$total = $total_neto + $tax1 + $tax2;
                } 
                if ($total >= 0 || $total_costo >= 0) {
                    $arrCurrency   = $this->get_currency_for_plan($arrPlan[$i]['id']);
                    $moneda        = "US$";
                    $moneda_local  = 'N';
                    $arrValoresUSD = [];
                   $tasa_cambio   = 0;
                    if (isset($arrCurrency[0]['usd_exchange']) && $arrCurrency[0]['usd_exchange'] > 0 && $arrCurrency[0]['value_iso'] != 'USD' && $arrCurrency[0]['id_country'] == $origen) {
                        $moneda        = $arrPlan[$i]['value_iso'];
                        $tasa_cambio   = $arrCurrency[0]['usd_exchange'];
                        $moneda_local  = 'Y';
                        $arrValoresUSD = array(
                            'valorMenor'            => $valorMenor2,
                            'subTotalMenores'       => $subTotalMenores,
                            'valorMayor'            => $valorMayor,
                            'subTotalMayores'       => $subTotalMayore,
                            'CostoMenor'            => $CostoMenor2,
                            'subTotalMenores_costo' => $subTotalMenores_costo,
                            'CostoMayor'            => $CostoMayor,
                            'subTotalMayore_cost'   => $subTotalMayore_cost,
                            'total'                 => $total,
                            'total_costo'           => $total_costo,
                        );
                        $valorMenor2 *= $tasa_cambio;
                        $subTotalMenores *= $tasa_cambio;
                        $valorMayor *= $tasa_cambio;
                        $subTotalMayore *= $tasa_cambio;
                        $CostoMenor2 *= $tasa_cambio;
                        $subTotalMenores_costo *= $tasa_cambio;
                        $CostoMayor *= $tasa_cambio;
                        $subTotalMayore_cost *= $tasa_cambio;
                        $total *= $tasa_cambio;
                        $total_costo *= $tasa_cambio;
                    }
                    if ($banda == 'si') {
                        $fields_array[$cnt]['banda']        = $banda;
                        $fields_array[$cnt]['total_rangos'] = $total_rangos;
                        $fields_array[$cnt]['edad_min']     = $edad_min;
                        $fields_array[$cnt]['edad_max']     = $edad_max;
                        $fields_array[$cnt]['tiepoid']      = 0;
                        for ($ct = 0; $ct < $total_rangos; $ct++) {
                            $fields_array[$cnt]['precio_banda' . $ct]   = truncateFloat($array_precio[$ct], 3);
                            $fields_array[$cnt]['costo_banda' . $ct]    = truncateFloat($array_costo[$ct], 3);
                            $fields_array[$cnt]['neto_banda' . $ct]    = truncateFloat($array_neto[$ct], 3);
                            $fields_array[$cnt]['subtotal_banda' . $ct] = truncateFloat($array_subtotal[$ct], 3);
                            $fields_array[$cnt]['rango_max' . $ct]      = $array_max[$ct];
                            $fields_array[$cnt]['rango_min' . $ct]      = $array_min[$ct];
                            $fields_array[$cnt]['npas_banda' . $ct]     = $array_npas[$ct];
                        }
                    } else {
                        $fields_array[$cnt]['valorMenor']            = truncateFloat($valorMenor2, 3);
                        $fields_array[$cnt]['subTotalMenores']       = truncateFloat($subTotalMenores, 3);
                        $fields_array[$cnt]['valorMayor']            = truncateFloat($valorMayor, 3);
                        $fields_array[$cnt]['subTotalMayor']         = truncateFloat($subTotalMayore, 3);
                        $fields_array[$cnt]['costoMenor']            = truncateFloat($CostoMenor2, 3);
                        $fields_array[$cnt]['subTotalMenores_costo'] = truncateFloat($subTotalMenores_costo, 3);
                        $fields_array[$cnt]['costoMayor']            = truncateFloat($CostoMayor, 3);
                        $fields_array[$cnt]['subTotalMayor_costo']   = truncateFloat($subTotalMayore_cost, 3);
                        $fields_array[$cnt]['netoMenor']             = truncateFloat($netoMenor2, 3);
                        $fields_array[$cnt]['subTotalMenores_neto']  = truncateFloat($subTotalMenores_neto, 3);
                        $fields_array[$cnt]['netoMayor']             = truncateFloat($netoMayor, 3);
                        $fields_array[$cnt]['subTotalMayor_neto']    = truncateFloat($subTotalMayore_neto, 3);
                        $fields_array[$cnt]['numero_menores']        = $menores75;
                        $fields_array[$cnt]['numero_mayores']        = $mayores75;
                        $fields_array[$cnt]['tiepoid']               = $tiepoid;
                    }
                    $fields_array[$cnt]['total']               = truncateFloat($total, 3);
                    $fields_array[$cnt]['total_costo']         = truncateFloat($total_costo, 3);
                    $fields_array[$cnt]['total_neto']          = truncateFloat($total_neto, 3);
                    $fields_array[$cnt]['name_plan']           = $arrPlan[$i]['titulo'];
                    $fields_array[$cnt]['planfamiliar']        = $planfamiliar;
                    $fields_array[$cnt]['planpareja']          = $pareja['planpareja'];
                    $fields_array[$cnt]['normal_age']          = $arrPlan[$i]['normal_age'];
                    $fields_array[$cnt]['max_age']             = $arrPlan[$i]['max_age']; 
                    $fields_array[$cnt]['min_age']             = $arrPlan[$i]['min_age'];
                    $fields_array[$cnt]['moneda_local']        = $moneda_local;
                    $fields_array[$cnt]['tasa_cambio']         = $tasa_cambio;                 
                    $fields_array[$cnt]['valore_USD']          = ($moneda_local == 'Y') ? $arrValoresUSD : null;
                    $fields_array[$cnt]['idp']                 = $arrPlan[$i]['id'];
                    $fields_array[$cnt]['price_voucher']       = $arrPlan[$i]['price_voucher'];
                    $fields_array[$cnt]['family_plan']         = $family_plan;
                    $fields_array[$cnt]['pareja_plan']         = $pareja['pareja_plan'];
                    $fields_array[$cnt]['factor_family']       = $factor_family;
                    $fields_array[$cnt]['maxFamilyCnt']        = $arrPlan[$i]['family_plan_cantidad'];
                    $fields_array[$cnt]['factor_family_cost']  = $factor_family_cost;
                    $fields_array[$cnt]['factor_family_age']   = $maxAgeFamilyPlan;
                    $fields_array[$cnt]['activofactor']        = $activofactor;
                    $fields_array[$cnt]['factor_pareja']       = $pareja['factor_pareja'];
                    $fields_array[$cnt]['activofactorpareja']  = $pareja['activofactorpareja'];
                    $fields_array[$cnt]['factor_pareja_cost']  = $pareja['factor_pareja_cost'];
                    $fields_array[$cnt]['moneda']              = $moneda;
                    $fields_array[$cnt]['overage_factor']      = $overageFactor;
                    $fields_array[$cnt]['overage_factor_cost'] = $overageCost;
                    $fields_array[$cnt]['activoTax'] = $actTax;
    				$fields_array[$cnt]['total_tax1'] = $tax1;
    				$fields_array[$cnt]['total_tax2'] = $tax2;
    				$fields_array[$cnt]['porc_tax1'] = $porcTax1;
    				$fields_array[$cnt]['porc_tax2'] = $porcTax2;
                    $cnt++;
                } else {
                    $errors_array[$cntError]['name_plan']         = $arrPlan[$i]['titulo'];
                    $errors_array[$cntError]['error_config_plan'] = 0;
                    $cntError++;
                }
            } else {
                /*####    RESTRICCIONES PARA LOS PLANES
                #############################################
                $mostrarR error broker permisos
                $mostrarRP error pais de origen
                $mostrarRD error destino
                $mostrarRT error time
                $mostrarlocal error no hay planes locales disponibles
                ##############################################*/
                $errors_array[$cntError]['name_plan'] = $arrPlan[$i]['titulo'];
                if ($max_age == 0 && $min_age == 0) {
                    $errors_array[$cntError]['error_age'] = 1;
                } else {
                    $errors_array[$cntError]['error_age'] = 0;
                }
                $errors_array[$cntError]['error_broker']         = $mostrar_R[1];
                $errors_array[$cntError]['error_country']        = $mostrar_R[2];
                $errors_array[$cntError]['error_time']           = $mostrar_R[3];
                $errors_array[$cntError]['error_territory']      = $mostrar_R[4];
                $errors_array[$cntError]['error_cant_passenger'] = $mostrar_R[5];
                $errors_array[$cntError]['error_local_plans']    = $mostrar_R[6];
                $cntError++;
            }
        }
        #############SI TENEMOS PLANES PARA MOSTRAR##########
        if (!empty($fields_array)) {
            return $fields_array;
        } else {
            ############RETORNAMOS LAS RESTRICCIONES##################
            return $errors_array;
        }
    }
    public function descuento_cupones($cod_promocional, $subtotal, $user_id, $userType, $destino, $idp, $descontar, $id_broker, $Cnropasajeros = 0) {
        if (!empty($cod_promocional)) {
            $obj_coupons = new Coupons();
            $obj_broker  = new Broker();
            $Arraycoupon = $obj_coupons->Get_Coupons_by_cod($cod_promocional);
            $totalPR     = count($Arraycoupon);
            if ($Arraycoupon[0]["ussage"] > 0) {
                if ($totalPR > 0) {
                    $isok       = "OK";
                    $elmesdesde = $Arraycoupon[0]["mes_desde"];
                    $eldiadesde = $Arraycoupon[0]["dia_desde"];
                    $elanodesde = $Arraycoupon[0]["ano_desde"];
                    $elmeshasta = $Arraycoupon[0]["mes_hasta"];
                    $eldiahasta = $Arraycoupon[0]["dia_hasta"];
                    $elanohasta = $Arraycoupon[0]["ano_hasta"];
                    $desdephp   = mktime(0, 0, 0, $elmesdesde, $eldiadesde, $elanodesde);
                    $hastaphp   = mktime(0, 0, 0, $elmeshasta, $eldiadhasta, $elanohasta);
                    $hoy        = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                    $hoy        = strtotime(date("d-m-Y H:i:00", time()));
                    $desdephp   = strtotime($eldiadesde . "-" . $elmesdesde . "-" . $elanodesde . " 00:00:00");
                    $hastaphp   = strtotime($eldiahasta . "-" . $elmeshasta . "-" . $elanohasta . " 23:59:59");
                    if (($hoy >= $desdephp) && ($hoy <= $hastaphp)) {
                        $isokTi = "OK";
                        ///////////////////VERIFICO EL PLAN
                        $errorPlan       = 0;
                        $ArraycouponPlan = $obj_coupons->Get_All_Relacion_Coupons_Plans($cod_promocional);
                        $totalPR2        = count($ArraycouponPlan);
                        if ($totalPR2 > 0) {
                            $errorPlan = 2; /////no es valido para el plan selecionado
                            for ($i = 0; $i < $totalPR2; $i++) {
                                if ($ArraycouponPlan[$i]['id_plan'] == $idp) {
                                    $errorPlan = 1; /////si es valido para el plan selecionado
                                }
                            }
                        }
                        if ($errorPlan == 1 || $errorPlan == 0) {
                            $isokP = "OK";
                        } else {
                            $isokP = "NA";
                        }
                        if (!empty($Arraycoupon[0]['type_coupon']) && $Arraycoupon[0]['type_coupon'] != 0) {
                            if ($Arraycoupon[0]['type_coupon'] == 1) {
                                ///////////////VERIFOCO EL TERRITORIO
                                if ($Arraycoupon[0]['id_territory'] != $destino) {
                                    $isokT = "NA";
                                } else {
                                    $isokT = "OK";
                                }
                            } elseif ($Arraycoupon[0]['type_coupon'] == 2) {
                                if ($arraycountries[0]['iso_country'] != $destino) {
                                    $isokT = "OK";
                                } else {
                                    $isokT = "NA";
                                }
                            }
                        }
                        /////////////VERIFICO DIRIGIDO
                        switch ($Arraycoupon[0]["dirigido"]) {
                            case "1":
                                $isokD = "OK";
                                break;
                            case "2":
                                if ($id_broker) {
                                    if ($id_broker == $Arraycoupon[0]["id_broker"]) {
                                        $isokD = "OK";
                                    } else {
                                        $isokD = "NA";
                                    }
                                } else {
                                    $isokD = "NA";
                                }
                                break;
                            case "3":
                                if ($id_broker) {
                                    if ($Arraycoupon[0]['id_broker'] == $id_broker || $Arraycoupon[0]["id_broker1"] == $id_broker || $Arraycoupon[0]['id_broker2'] == $id_broker || $Arraycoupon[0]['id_broker3'] == $id_broker || $Arraycoupon[0]['id_broker4'] == $idB || $Arraycoupon[0]['id_broker5'] == $id_broker) {
                                        $isokD = "OK";
                                    } else {
                                        $isokD = "NA";
                                    }
                                } else {
                                    $isokD = "NA";
                                }
                                break;
                            case "5":
                                if (empty($user_id)) {
                                    $isokD = "OK";
                                }
                                break;
                        }
                    } else {
                        $isokTi = "NA";
                    }
                } else {
                    $isok = "NA";
                }
            } else {
                $isok = "NA";
            }
            $nuevototal  = 0;
            $restriccion = 'OK';
            if ($isok != 'OK' && $isok != '') {
                $restriccion = 'isok';
            } else if ($isokP != 'OK' && !empty($isokP)) {
                $restriccion = 'isokP';
            } else if ($isokD != 'OK' && !empty($isokD)) {
                $restriccion = 'isokD';
            } else if ($isokT != 'OK' && !empty($isokT)) {
                $restriccion = 'isokT';
            } else if ($isokTi != 'OK' && !empty($isokTi)) {
                $restriccion = 'isokTi';
            }
            if ($isok == 'OK' && $isokP == 'OK' && $isokD == 'OK' && $isokT == 'OK' && $isokTi == 'OK') {
                $codi = $Arraycoupon[0]["codigo"];
                if ($descontar == 'si') {
                    $uss = $Arraycoupon[0]["ussage"] - 1;
                    $obj_coupons->Update_usagge_coupon($codi, $uss);
                }
                $porcentaje = $Arraycoupon[0]["porcentaje"];
                if (!empty($porcentaje) && $porcentaje != "0") {
                    $descuento = $Arraycoupon[0]["porcentaje"];
                    $totald    = ($subtotal * $descuento) / 100;
                    $totald    = $totald;
                } elseif (!empty($Arraycoupon[0]['credit_amount'])) {
                    $descuento = $Arraycoupon[0]["credit_amount"];
                    $totald    = $descuento;
                }
                $cupon = $cod_promocional;
            } else {
                $descuento = 0;
                $cupon     = '';
                $totald    = 0;
            }
            if ($Cnropasajeros > 0 && $totald > 0) {
                $totald = (truncateFloat(($totald / $Cnropasajeros), 3)) * $Cnropasajeros;
            }
            if ($subtotal > $totald) {
                $monto_cancelar = $subtotal - $totald;
            } else {
                $monto_cancelar = 0;
            }
            return array('total' => $monto_cancelar, 'descuento' => $totald, 'restriccion' => $restriccion, 'codigo' => $codi,'porcentaje'=>$Arraycoupon[0]["porcentaje"]);
        }
    }
    public function Get_All_Relation_Restric2($id_restic = '', $iso_country = '', $type_country) {
        $query = "SELECT * FROM
	    		relaciotn_restriction
	    	WHERE id_restric='$id_restic'
	    	AND iso_country='$iso_country'
	    	AND type_country='$type_country'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_plan_band_age_valor($edad = '', $id_plan = '', $id_country = '') {
        $query = "SELECT
				plan_band_age.cost,
				plan_band_age.precio_base,
				plan_band_age.precio_base_cost,
				plan_band_age.valor,
				plan_band_age.id
			FROM `plan_band_age`
			WHERE
				plan_band_age.age_min <= $edad
			AND plan_band_age.age_max >= $edad
			AND plan_band_age.id_plan = $id_plan
			AND plan_band_age.id_country = '$id_country'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_Plans_categoria($id_plan_categoria = '', $max_tiempo = 0, $origen = "", $id_plan = "", $id_multiple_plans = "", $id_broker = '', $opcion_plan = '', $intervalo = '') {
        $arrBroker = "";
        $query     = "SELECT
                plans.id,
                IF (
                plans.`name` IS NULL
                OR plans.`name` = '',
                plan_detail.titulo,
                plans.`name`
                ) AS titulo,
                plans.activo,
                plans.id_plan_categoria,
                plans.unidad,
                plans.min_tiempo,
                plans.max_tiempo,
                plans.imagen,
                plans.deducible,
                plans.id_currence,
                plans.remark,
                plans.commissions,
                plans.botht,
                plans.normal_age,
                plans.max_age,
                plans.min_age,
                plans.overage_factor,
        		plans.overage_factor_cost,
                plans.family_plan,
                plans.factor_family,
                plans.factor_family_cost,
				plans.family_plan_cantidad,
                plans.plan_local,
                plans.num_pas,
                plans.plan_pareja,
                plans.factor_pareja,
                plans.factor_pareja_cost,
                plans.plan_renewal,
                plans.price_voucher,
                plans.impuesto,
                restriction.id_restric,
                restriction.dirigido,
                restriction.id_territory_origen,
                restriction.id_territory_destino,
                restriction.id_broker,
                restriction.id_broker1,
                restriction.id_broker2,
                restriction.id_broker3,
                restriction.id_broker4,
                restriction.id_broker5,
                restriction.id_plans,
                restriction.id_client,
                restriction.created,
                restriction.modified,
                plan_detail.language_id,
                currency.value_iso,
                broker.broker,
                broker.opcion_plan
                FROM
                plans
                LEFT JOIN restriction ON plans.id = restriction.id_plans
                LEFT JOIN broker ON broker.id_broker = restriction.id_broker
                LEFT JOIN currency ON plans.id_currence = currency.id_currency
                INNER JOIN plan_detail ON plans.id = plan_detail.plan_id
                WHERE
                plans.activo = '1'
				AND eliminado = '1'";
        if (!empty($id_broker)) {
            $arrBroker    = "";
            $brokerParent = $this->niveles_agencias($id_broker)[0];
            while ($brokerParent['parent'] != 0) {
                $brokerParent = $this->niveles_agencias($brokerParent['parent'])[0];
                $arrBroker    = (!empty($arrBroker)) ? $arrBroker . ", '" . $brokerParent['id_broker'] . "'" : "'" . $brokerParent['id_broker'] . "'";
            }
            $arrBroker = (!empty($arrBroker)) ? $arrBroker : '0';
            $query .= "AND
					IF (
						(
							SELECT
								broker.opcion_plan
							FROM
								broker
							WHERE
								broker.id_broker = '$id_broker'
							AND broker.opcion_plan = 2
						),
						(
							(
								broker.id_broker IN ($arrBroker)
								AND dirigido = '6'
							)
							OR broker.id_broker = '$id_broker'
						),
						(
							broker.opcion_plan = '1'
							OR broker.opcion_plan IS NULL
							OR (
								(
									broker.id_broker IN ($arrBroker)
									AND dirigido = '6'
								)
								OR broker.id_broker = '$id_broker'
							)
						)
					)";
        }
        $query .= " AND plan_detail.language_id = '" . $_SESSION["lng_id"] . "' ";
        if (!empty($id_plan_categoria)) {
            $query .= " AND plans.id_plan_categoria =  '$id_plan_categoria'";
        }
        if (!empty($origen)) {
            $query .= " AND plans.plan_local =  '$origen'";
        }
        if (!empty($id_plan)) {
            $query .= " AND plans.id = '$id_plan'";
        }
        if (!empty($intervalo)) {
            $query .= " AND plans.dias_multiviajes  = '$intervalo'";
        }
        if (!empty($id_multiple_plans)) {
            $query .= " AND plans.id IN ($id_multiple_plans)";
        }
        $query .= " AND (
			modo_plan = 'W'
			OR modo_plan = 'N'
			OR modo_plan = 'T'
			OR modo_plan is NULL
			)";
        $query .= " ORDER BY plans.orden ASC";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_plan_band_age($idPlan=null, $idBroker=null, $idCountry='all', $renewal=false) {
        $query = "SELECT
                plan_band_age.id,
                plan_band_age.id_plan,
                plan_band_age.valor,
                plan_band_age.cost,
                plan_band_age.unidad,
                plan_band_age.tiempo,
                plan_band_age.adicional,
                IF (plan_banda_ag_net.pvp_neto IS NOT NULL AND broker.price_type = 2,
                    plan_banda_ag_net.pvp_neto,
                    0
                ) AS valorNeto,
                plan_band_age.precio_base,
                plan_band_age.precio_base_cost,
                IF (plan_banda_ag_net.precio_neto IS NOT NULL AND broker.price_type = 2,
                    plan_banda_ag_net.precio_neto,
                    0
                ) AS precio_base_neto,
                plan_band_age.id_country,
                plan_band_age.age_min,
                plan_band_age.age_max,
                plan_band_age.renewal,
                plan_band_age.cost_audit
            FROM
                plan_band_age
            LEFT JOIN plan_banda_ag_net ON plan_band_age.id = plan_banda_ag_net.id_banda
            AND plan_banda_ag_net.id_broker = '$idBroker'
            LEFT JOIN broker ON plan_banda_ag_net.id_broker = broker.id_broker
            WHERE
                plan_band_age.id_plan = '$idPlan'
            AND plan_band_age.id_country = '$idCountry' ";
		if (empty($renewal)) {
			$query.= " AND (
					renewal IS NULL
					OR renewal <> '2'
				) ";
		} else {
			$query.= " AND renewal = '$renewal' ";
		}
		$query.= " ORDER BY
			plan_band_age.age_min ASC,
			plan_band_age.adicional ASC,
			plan_band_age.tiempo ASC ";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_Plans_categoria_cot($id_plan = '', $lng_id = '') {
        $query = 'SELECT
                plans.id,
                plans.name,
                plans.description,
                plans.activo,
                plans.id_plan_categoria,
                plans.unidad,
                plans.min_tiempo,
                plans.max_tiempo,
                plans.imagen,
                plans.deducible,
                plans.id_currence,
                plans.remark,
                plans.commissions,
                plans.botht,
                plans.max_age,
                plans.normal_age,
                plans.overage_factor,
                plans.overage_factor_cost,
                plans.factor_family,
                plans.factor_family_cost,
                plans.plan_local,
                plans.percent_benefit,
                currency.value_iso,
                plan_detail.titulo,plan_detail.language_id
            FROM
                plans
            INNER JOIN currency ON plans.id_currence = currency.id_currency
            INNER JOIN plan_detail ON plans.id = plan_detail.plan_id
            WHERE 1
            AND plan_detail.language_id= "' . $_SESSION['lng_id'] . '"';
        if (!empty($id_plan)) {
            $query .= " AND plans.id =  '$id_plan'";
        }
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_premium_plan($idPlan=null, $idBroker=null, $isoCountry='all', $renewal=false){
    	$query = "SELECT
                plan_times.id,
                plan_times.tiempo,
                plan_times.unidad,
                plan_times.valor,
                plan_times.cost,
                IF (plan_times_ag_net.neto_ag IS NOT NULL AND broker.price_type = 2,
                    plan_times_ag_net.neto_ag,
                    0
                ) AS valorNeto,
                plan_times.adicional,
                plan_times.id_country,
                plan_times.renewal
            FROM
                plan_times
            LEFT JOIN plan_times_ag_net ON plan_times.id = plan_times_ag_net.id_plan_time 
            AND plan_times_ag_net.id_broker = '$idBroker'
            LEFT JOIN broker ON plan_times_ag_net.id_broker = broker.id_broker
			WHERE
				plan_times.id_plan = '$idPlan'
			AND plan_times.id_country = '$isoCountry' ";
			if (empty($renewal)) {
				$query.= " AND (
						renewal IS NULL
						OR renewal <> '2'
					) ";
			} else {
				$query.= " AND renewal = '$renewal' ";
			}
			$query.= " ORDER BY
				plan_times.id_country ASC,
				plan_times.tiempo ASC ";
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_Broker_Countri($id_broker = '') {
        $query = "SELECT
				broker.id_country,
				broker.price_type,
				broker.id_broker,
				broker.broker,
				broker.credito_actual
			FROM
				broker
			WHERE
				broker.id_broker='$id_broker'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Traer_SubBrokers($Agencia_Asociada = '', $nivel_del_Broker = '') {
        if (empty($nivel_del_Broker)) {
            $Arr_Broker_nivel = $this->Select_Broker_nivel($Agencia_Asociada);
            $nivel_del_Broker = $Arr_Broker_nivel[0]['nivel'];
        }
        if ($nivel_del_Broker == 1) {
            $Traer_broker_Nivel = $this->Buscador_broker_nivel($Agencia_Asociada);
        }
        if ($nivel_del_Broker == 2) {
            $Traer_broker_Nivel = $this->Buscador_broker_nivel('', $Agencia_Asociada);
        }
        if ($nivel_del_Broker == 3) {
            $Traer_broker_Nivel = $this->Buscador_broker_nivel('', '', $Agencia_Asociada);
        }
        if ($nivel_del_Broker == 4) {
            $Traer_broker_Nivel = $this->Buscador_broker_nivel('', '', '', $Agencia_Asociada);
        }
        if ($nivel_del_Broker == 5) {
            $Traer_broker_Nivel = $this->Buscador_broker_nivel('', '', '', '', $Agencia_Asociada);
        }
        $totalbrokers = $Traer_broker_Nivel . ',' . $Agencia_Asociada;
        $totalbrokers = trim($totalbrokers, ',');
        $totalbrokers = explode(',', $totalbrokers);
        return $totalbrokers;
    }
    public function Get_Broker_Price($id_broker = '', $status = '') {
        $query = "SELECT
				broker.price_type,
                broker.opcion_plan
			FROM `broker`
			WHERE 1";
        if (!empty($id_broker)) {
            $query .= " AND broker.id_broker ='$id_broker'";
        }
        if (!empty($status)) {
            $query .= " AND broker.id_status='$status'";
        }
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_Coupons_by_cod($codigo = '') {
        $query = "SELECT *,month(fecha_desde) mes_desde, year(fecha_desde) ano_desde, day(fecha_desde) dia_desde,month(fecha_hasta) mes_hasta, year(fecha_hasta) ano_hasta, day(fecha_hasta) dia_hasta FROM coupons WHERE id_status = 1 ";
        if (!empty($codigo)) {
            $query .= " AND codigo = '$codigo'";
        }
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_All_Relacion_Coupons_Plans($codigo = '', $id_plan = '') {
        $query = "SELECT * FROM relacion_plan_cupon WHERE 1 ";
        if (!empty($codigo)) {
            $query .= " AND codigo = '$codigo'";
        }
        if (!empty($id_plan)) {
            $query .= " AND id_plan = '$id_plan'";
        }
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Update_usagge_coupon($codigo = '', $ussage) {
        $sQuery = "UPDATE coupons SET ussage='$ussage' WHERE codigo='$codigo'";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery);
    }
    public function calcular_diff_dias($desde, $hasta, $formato) //resive en formato DD/MM/AAAA por defecto
    {
        $datetime1 = new DateTime('now');
        $desde     = str_replace('-', '/', $desde); //cambio / por -
        $hasta     = str_replace('-', '/', $hasta);
        $formato = str_replace('-', '/', $formato);
        $desde = explode("/", $desde);
        $hasta = explode("/", $hasta);
        if (empty($formato)) {
            $desde = $desde[2] . '-' . $desde[1] . '-' . $desde[0];
            $hasta = $hasta[2] . '-' . $hasta[1] . '-' . $hasta[0];
        } else {
            if ($formato == 'MM/DD/AAAA') {
                $desde = $desde[2] . '-' . $desde[0] . '-' . $desde[1];
                $hasta = $hasta[2] . '-' . $hasta[0] . '-' . $hasta[1];
            }
            if ($formato == 'AAAA/MM/DD') {
                $desde = $desde[0] . '-' . $desde[1] . '-' . $desde[2];
                $hasta = $hasta[0] . '-' . $hasta[1] . '-' . $hasta[2];
            }
        }
        $datetime1 = DateTime::createFromFormat('Y-m-d', $desde);
        $datetime2 = DateTime::createFromFormat('Y-m-d', $hasta);
        $intervalo = $datetime1->diff($datetime2)->days;
        $diaspasa  = $intervalo;
        if ($diaspasa >= 1) {
            $diaspasa = $diaspasa;
        } else {
            $diaspasa = 0;
        }
        $diaspasa = $diaspasa + 1;
        return $diaspasa;
    }
    public function calcular_meses_adic2($sal, $reg, $formato) {
        $desde       = $sal;
        $hasta       = $reg;
        $mesesAdic   = 0;
        $semanasAdic = 0;
        $unMes       = 30;
        $unaSemana   = 7;
        $diaspasa = $this->calcular_diff_dias($desde, $hasta, $formato);
        $res       = ($diaspasa % $unMes);
        $mesesAdic = intval($diaspasa / $unMes);
        if ($res > 0) {
            $semanasAdic = intval($res / $unaSemana);
            $res = ($res % $unaSemana);
            if ($res != 0) {
                $semanasAdic++;
            }
        }
        if ($mesesAdic >= 12) {
            //un maximo de 12 meses para los planes
            $semanasAdic = 0;
        }
        return $mesesAdic . "|" . $semanasAdic;
    }
    public function get_currency_for_plan($id_plan = '') {
        $query = "SELECT
				currency.*
			FROM
				currency
			INNER JOIN plans ON currency.id_currency = plans.id_currence
			WHERE
				plans.id = '$id_plan'
			AND id_status = '1' ";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public static function getMyIP() {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unknown';
    }
    public function Add_orden($tp, $origen, $destino, $fsalida_aux, $fregreso_aux, $nombre_categoria, $nombre_contacto, $email_contacto, $comentarios, $comentario_medicas, $telefono_contacto, $idp, $tipo_tarjeta, $numero_tarjeta, $credito_expira, $credito_cvv, $credito_nombre, $id_broker, $broker, $v, $codigo_final, $neta, $neta2, $neta3, $iduser, $Cnropasajeros, $status, $response_array, $cupon, $codeauto, $nombre_producto, $Transaction, $totalcostocost, $alter_cur = 0, $tasa_cambio = '', $formapago, $family_plan, $referencia, $territorio, $idemisiontype, $id_group, $mostrar_carnet, $id_cotiza, $id_preorden, $impuesto1, $impuesto2, $id_preventa, $total_mlc, $costo_mlc, $descuento, $descuento_mlc, $totalRaider, $CostoRaider, $compra_minima = 'N', $total_neta, $net_price=2, $dispositivo,$total_tax_mlc,$total_tax2_mlc) {
        $des_soap       = $destino;
        $tp             = $tp ?: 0;
        $fsalida_aux    = $fsalida_aux ?: 0;
        $id_preorden    = $id_preorden ?: 0;
        $fregreso_aux   = $fregreso_aux ?: 0;
        $idp            = $idp ?: 0;
        $tipo_tarjeta   = $tipo_tarjeta ?: 0;
        $id_broker      = $id_broker ?: 0;
        $v              = $v ?: 0;
        $neta           = $neta ?: 0;
        $neta2          = $neta2 ?: 0;
        $neta3          = $neta3 ?: 0;
        $iduser         = $iduser ?: 0;
        $Cnropasajeros  = $Cnropasajeros ?: 0;
        $total_tax_mlc = $total_tax_mlc ?: 0;
        $total_tax2_mlc = $total_tax2_mlc ?: 0;
        $status         = $status ?: 0;
        $totalcostocost = $totalcostocost ?: 0;
        $alter_cur      = $alter_cur ?: 0;
        $tasa_cambio    = $tasa_cambio ?: 0;
        $formapago      = $formapago ?: 0;
        $idemisiontype  = $idemisiontype ?: 0;
        $id_group       = $id_group ?: 0;
        $mostrar_carnet = $mostrar_carnet ?: 0;
        $id_cotiza      = $id_cotiza ?: 0;
        $id_preorden    = $id_preorden ?: 0;
        $descuento     = $descuento ?: 0;
        $descuento_mlc = $descuento_mlc ?: 0;
        $total_mlc     = $total_mlc ?: 0;
        $costo_mlc     = $costo_mlc ?: 0;
        $impuesto1   = $impuesto1 ?: 0;
        $impuesto2   = $impuesto2 ?: 0;
        $id_preventa = $id_preventa ?: 0;
        $totalRaider = $totalRaider ?: 0;
        $CostoRaider = $CostoRaider ?: 0;
        $total_neta = $total_neta ?: 0;
        $net_price = $net_price ?: 2;
        if ($destino == "9") {
            $territorio = $destino;
            $destino    = $origen;
            $validez    = 2;
        } else {
            $validez   = 1;
            $Territory = Countries::Select_Territory1($destino);
            if (!empty($Territory)) {
                $territorio = $destino;
                $destino    = "XX";
            } else {
                $territorio = '';
                $destino    = $destino;
            }
        }
        $query = "INSERT INTO orders(tiempo_x_producto,origen,destino,salida,retorno,programaplan,nombre_contacto,email_contacto,comentarios,comentario_medicas,telefono_contacto,producto,credito_tipo,credito_numero,credito_expira,credito_cvv,credito_nombre,agencia,nombre_agencia,total,codigo,neta,neta2,neta3,vendedor,cantidad,status,cupon,codeauto,origin_ip, v_authorizado, neto_prov, response, alter_cur, tasa_cambio, forma_pago, family_plan, referencia ,validez, fecha, hora, id_emision_type, territory, id_group, mostrar_carnet, id_cotiza, id_preorden, id_preventa, total_tax, total_tax_2, total_mlc, neto_prov_mlc,cupon_descto,cupon_dscto_mlc,total_raider,total_costo_raider, total_neta, net_price, dispositivo_emision, total_tax_mlc,total_tax2_mlc) " . "VALUES('$tp','$origen','$destino','$fsalida_aux','$fregreso_aux','$nombre_categoria','$nombre_contacto','$email_contacto','$comentarios','$comentario_medicas','$telefono_contacto','$idp','$tipo_tarjeta','$numero_tarjeta','$credito_expira','$credito_cvv','$credito_nombre','$id_broker','$broker','$v','$codigo_final','$neta','$neta2','$neta3','$iduser','$Cnropasajeros','$status','$cupon', '$codeauto', '" . $this->getMyIP() . "', '$Transaction', '$totalcostocost', '$response_array', '$alter_cur', '$tasa_cambio', '$formapago', '$family_plan', '$referencia' ,'$validez', now(), now(), '$idemisiontype', '$territorio', '$id_group', '$mostrar_carnet', '$id_cotiza', '$id_preorden', '$id_preventa' , '$impuesto1','$impuesto2', '$total_mlc', '$costo_mlc','$descuento','$descuento_mlc','$totalRaider','$CostoRaider', '$total_neta', '$net_price', '$dispositivo','$total_tax_mlc','$total_tax2_mlc')";
        return $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert orden <-> codigo:' . $codigo_final, '', '', '_DEFAULT', $codigo_final);
    }
    public function Add_beneficiarios($idorden, $a_nombre, $a_apellido, $a_email, $fnacimiento_aux = '', $a_pasaporte, $a_telefonopasagero = '', $a_precio_vta = 0, $a_precio_cost = 0, $status, $a_precio_neta = 0, $codigo_final = '', $nacionalidad = '', $condicion_medica = '', $a_sexo = '', $tipo_doc = '', $a_precio_neto_benfit = 0, $a_costo_neto_benfit = 0, $precio_vta_mlc = 0, $precio_cost_mlc = 0, $precio_neto_total = 0) {
        $idorden              = $idorden ?: 0;
        $fnacimiento_aux      = $fnacimiento_aux ?: 0;
        $a_precio_vta         = $a_precio_vta ?: 0;
        $a_precio_cost        = $a_precio_cost ?: 0;
        $a_precio_neta        = $a_precio_neta ?: 0;
        $a_precio_neto_benfit = $a_precio_neto_benfit ?: 0;
        $a_costo_neto_benfit  = $a_costo_neto_benfit ?: 0;
        $precio_vta_mlc       = $precio_vta_mlc ?: 0;
        $precio_cost_mlc      = $precio_cost_mlc ?: 0;
        $precio_neto_total    = $precio_neto_total ?: 0;
        $a_nombre             = addslashes($a_nombre);
        $a_apellido           = addslashes($a_apellido);
        $a_email              = addslashes($a_email);
        $condicion_medica     = addslashes($condicion_medica);
        $tipo_doc             = addslashes($tipo_doc);
        $query = "INSERT INTO beneficiaries(id_orden,nombre,apellido,email,nacimiento,documento,nacionalidad,titular,telefono,precio_vta,precio_cost, ben_status, id_rider, precio_neto, condicion_medica , sexo, tipo_doc, total_neto_benefit, neto_cost, precio_vta_mlc, precio_cost_mlc, precio_neto_total) " . "VALUES( '$idorden' ,'$a_nombre','$a_apellido','$a_email','$fnacimiento_aux','$a_pasaporte','$nacionalidad','0','$a_telefonopasagero','$a_precio_vta','$a_precio_cost','$status','0', '$a_precio_neta' , '$condicion_medica','$a_sexo', '$tipo_doc','$a_precio_neto_benfit','$a_costo_neto_benfit', '$precio_vta_mlc', '$precio_cost_mlc', '$precio_neto_total')";
        /*die($query);*/
        return $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert banficiario <-> id orden:' . $idorden, '', '', '_DEFAULT', $codigo_final);
    }
    public function update_cod_order($tp, $origen, $destino, $fsalida_aux, $fregreso_aux, $nombre_categoria, $nombre_contacto, $email_contacto, $comentarios, $comentario_medicas, $telefono_contacto, $idp, $tipo_tarjeta, $numero_tarjeta, $credito_expira, $credito_cvv, $credito_nombre, $id_broker, $broker, $v, $codigo_final, $neta, $neta2, $neta3, $iduser, $Cnropasajeros, $status, $response_array, $cupon, $codeauto, $nombre_producto, $Transaction, $totalcostocost, $alter_cur = 0, $tasa_cambio = '', $formapago, $family_plan, $referencia, $territorio, $idemisiontype, $id_group, $mostrar_carnet, $id_cotiza, $id_preorden, $impuesto1, $impuesto2, $id_preventa, $total_mlc, $costo_mlc, $descuento, $descuento_mlc, $totalRaider, $CostoRaider, $id_orden, $total_neta, $net_price=2, $dispositivo) {
        $des_soap       = $destino;
        $tp             = $tp ?: 0;
        $fsalida_aux    = $fsalida_aux ?: 0;
        $id_preorden    = $id_preorden ?: 0;
        $fregreso_aux   = $fregreso_aux ?: 0;
        $idp            = $idp ?: 0;
        $tipo_tarjeta   = $tipo_tarjeta ?: 0;
        $id_broker      = $id_broker ?: 0;
        $v              = $v ?: 0;
        $neta           = $neta ?: 0;
        $neta2          = $neta2 ?: 0;
        $neta3          = $neta3 ?: 0;
        $iduser         = $iduser ?: 0;
        $Cnropasajeros  = $Cnropasajeros ?: 0;
        $status         = $status ?: 0;
        $totalcostocost = $totalcostocost ?: 0;
        $alter_cur      = $alter_cur ?: 0;
        $tasa_cambio    = $tasa_cambio ?: 0;
        $formapago      = $formapago ?: 0;
        $idemisiontype  = $idemisiontype ?: 0;
        $id_group       = $id_group ?: 0;
        $mostrar_carnet = $mostrar_carnet ?: 0;
        $id_cotiza      = $id_cotiza ?: 0;
        $id_preorden    = $id_preorden ?: 0;
        $descuento     = $descuento ?: 0;
        $descuento_mlc = $descuento_mlc ?: 0;
        $total_mlc     = $total_mlc ?: 0;
        $costo_mlc     = $costo_mlc ?: 0;
        $impuesto1   = $impuesto1 ?: 0;
        $impuesto2   = $impuesto2 ?: 0;
        $id_preventa = $id_preventa ?: 0;
        $totalRaider = $totalRaider ?: 0;
        $CostoRaider = $CostoRaider ?: 0;
        $net_price = $net_price ?: 2;
        $total_neta = $total_neta ?: 0;
        if ($destino == "9") {
            $territorio = $destino;
            $destino    = $origen;
            $validez    = 2;
        } else {
            $validez   = 1;
            $Territory = Countries::Select_Territory1($destino);
            if (!empty($Territory)) {
                $territorio = $destino;
                $destino    = "XX";
            } else {
                $territorio = '';
                $destino    = $destino;
            }
        }
        $ip = $this->getMyIP();
        $query = "UPDATE orders SET tiempo_x_producto = '$tp', origen = '$origen', destino = '$destino', salida = '$fsalida_aux', retorno = '$fregreso_aux', programaplan = '$nombre_categoria', nombre_contacto = '$nombre_contacto', email_contacto = '$email_contacto', comentarios = '$comentarios', comentario_medicas = '$comentario_medicas', telefono_contacto = '$telefono_contacto', producto = '$idp', credito_tipo = '$tipo_tarjeta', credito_numero = '$numero_tarjeta', credito_expira = '$credito_expira', credito_cvv = '$credito_cvv', credito_nombre = '$credito_nombre', agencia = '$id_broker', nombre_agencia = '$broker', total = '$v', codigo = '$codigo_final',neta = '$neta', neta2 = '$neta2', neta3 = '$neta3',
    	vendedor = '$iduser', cantidad = '$Cnropasajeros', status = '$status', cupon = '$cupon', codeauto = '$codeauto', origin_ip = '$ip', v_authorizado = '$Transaction', neto_prov = '$totalcostocost', response = '$response_array', alter_cur = '$alter_cur', tasa_cambio = '$tasa_cambio', forma_pago = '$formapago', family_plan = '$family_plan', referencia = '$referencia', validez = '$validez', fecha = now(), hora = now(), id_emision_type = '$idemisiontype', territory = '$territorio', id_group = '$id_group', mostrar_carnet = '$mostrar_carnet', id_cotiza = '$id_cotiza', id_preorden = '$id_preorden', id_preventa = '$id_preventa', total_tax = '$impuesto1', total_tax_2 = '$impuesto2', total_mlc = '$total_mlc',
    	neto_prov_mlc = '$costo_mlc', cupon_descto = '$descuento', cupon_dscto_mlc = '$descuento_mlc', total_raider = '$totalRaider', total_costo_raider = '$CostoRaider', net_price = '$net_price', total_neta = '$total_neta', dispositivo_emision = '$dispositivo' WHERE id = '$id_orden'";
        return $this->_SQL_tool($this->UPDATE, __METHOD__, $query);
    }
    public function update_beneficiarios_cot($idorden, $a_nombre, $a_apellido, $a_email, $fnacimiento_aux = '', $a_pasaporte, $a_telefonopasagero = '', $a_precio_vta, $a_precio_cost, $status, $a_precio_neta, $codigo_final = '', $nacionalidad = '', $condicion_medica = '', $a_sexo = '', $tipo_doc = '', $a_precio_neto_benfit = 0, $a_costo_neto_benfit = 0, $precio_vta_mlc = 0, $precio_cost_mlc = 0, $precio_neto_total = 0, $id_beneficiario) {
        $idorden              = $idorden ?: 0;
        $fnacimiento_aux      = $fnacimiento_aux ?: 0;
        $a_precio_vta         = $a_precio_vta ?: 0;
        $a_precio_cost        = $a_precio_cost ?: 0;
        $a_precio_neta        = $a_precio_neta ?: 0;
        $a_precio_neto_benfit = $a_precio_neto_benfit ?: 0;
        $a_costo_neto_benfit  = $a_costo_neto_benfit ?: 0;
        $precio_vta_mlc       = $precio_vta_mlc ?: 0;
        $precio_cost_mlc      = $precio_cost_mlc ?: 0;
        $precio_neto_total    = $precio_neto_total ?: 0;
        $a_nombre             = addslashes($a_nombre);
        $a_apellido           = addslashes($a_apellido);
        $a_email              = addslashes($a_email);
        $condicion_medica     = addslashes($condicion_medica);
        $tipo_doc             = addslashes($tipo_doc);
        $query = "UPDATE beneficiaries SET id_orden = '$idorden', nombre = '$a_nombre', apellido = '$a_apellido', email = '$a_email', nacimiento = '$fnacimiento_aux', documento = '$a_pasaporte', nacionalidad = '$nacionalidad', titular = '0', telefono = '$a_telefonopasagero', precio_vta = '$a_precio_vta', precio_cost = '$a_precio_cost', ben_status = '$status', id_rider = '0', precio_neto = '$a_precio_neta', condicion_medica = '$condicion_medica', sexo = '$a_sexo', tipo_doc = '$tipo_doc', total_neto_benefit = '$a_precio_neto_benfit', neto_cost = '$a_costo_neto_benfit', precio_vta_mlc = '$precio_vta_mlc', precio_cost_mlc = '$precio_cost_mlc', precio_neto_total = '$precio_neto_total' WHERE id = '$id_beneficiario'";
        return $this->_SQL_tool($this->UPDATE, __METHOD__, $query);
    }
    public function update_beneficiarios($id_order, $id_status) {
        $query = "UPDATE beneficiaries SET ben_status = '$id_status' WHERE id_orden = '$id_order'";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $query);
    }
    public function niveles_agencias($id_broker = '') {
        $query = "SELECT DISTINCT
				broker.id_broker,
				broker_nivel.nivel,
				broker_nivel.parent,
				broker.broker,
				broker.opcion_plan,
				broker.id_status
			FROM
				broker_nivel
			INNER JOIN broker ON broker.id_broker = broker_nivel.id_broker
			WHERE
				broker.id_broker = '$id_broker'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function get_dias_multiviajes($idBroker = null) {
        $arrBroker    = "";
        $brokerParent = $this->niveles_agencias($idBroker)[0];
        while ($brokerParent['parent'] != 0) {
            $brokerParent = $this->niveles_agencias($brokerParent['parent'])[0];
            $arrBroker    = (!empty($arrBroker)) ? $arrBroker . ", '" . $brokerParent['id_broker'] . "'" : "'" . $brokerParent['id_broker'] . "'";
        }
        $arrBroker = (!empty($arrBroker)) ? $arrBroker : '0';
        $query = "SELECT
				plans.dias_multiviajes
			FROM
				plans
			LEFT JOIN restriction ON plans.id = restriction.id_plans
			LEFT JOIN broker ON restriction.id_broker = broker.id_broker
			WHERE
				dias_multiviajes IS NOT NULL
			AND dias_multiviajes > 0
			AND plans.activo = '1'
			AND eliminado = '1'
			AND (
				modo_plan IS NULL
				OR modo_plan != 'W'
			)
			AND
				IF (
					(
						SELECT
							broker.opcion_plan
						FROM
							broker
						WHERE
							broker.id_broker = '$idBroker'
						AND broker.opcion_plan = 2
					),
					(
						(
							broker.id_broker IN ($arrBroker)
							AND dirigido = '6'
						)
						OR broker.id_broker = '$idBroker'
					),
					(
						broker.opcion_plan = '1'
						OR broker.opcion_plan IS NULL
						OR (
							(
								broker.id_broker IN ($arrBroker)
								AND dirigido = '6'
							)
							OR broker.id_broker = '$idBroker'
						)
					)
				)
			GROUP BY plans.dias_multiviajes ";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Delete_order_raiders($idOrden=null){
        $query = "DELETE
            FROM
                orders_raider
            WHERE
                id_orden = '$idOrden' ";
        return $this->_SQL_tool($this->DELETE, __METHOD__, $query);
    }
    public function add_raider_by_orden($id_orden, $id_raider, $value_raider, $id_beneft, $porcentajeCost, $porcentajeNeta, $codigo_final) {
        $id_orden  = $id_orden ?: 0;
        $id_raider = $id_raider ?: 0;
        $id_beneft = $id_beneft ?: 0;
        $query  = "INSERT INTO orders_raider (
                id_orden,
                id_raider,
                value_raider,
                id_beneft,
                cost_raider,
                neta_raider
            )
            VALUES (
                '$id_orden',
                '$id_raider',
                '$value_raider',
                '$id_beneft',
                '$porcentajeCost',
                '$porcentajeNeta'
            ) ";
        return $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert orders_raider <-> id_orden:' . $id_orden, '', '', '_DEFAULT', $codigo_final);
    }
    function plans_intervalos_edades_new($idCategory='', $country='', $idbroker=''){
        $arrPlan = $this->get_plan_unidad_new($idCategory,'1',$idbroker);
        $cantPlan = count($arrPlan);
        $intervals = Array();
        $intervalsMin = Array();
        $intervalsMax = Array();
        $addFamilyInterval = false;
        $minAgeInterval = false;
        if($cantPlan < 1){
            return false;
        }
        for($i=0; $i < $cantPlan; $i++){
            if ($arrPlan[$i]['unidad']=='bandas'){
                $arrPemium = $this->get_all_age_banda($arrPlan[$i]['id'], $country);
                if(count($arrPemium) < 1){
                    $arrPemium = $this->get_all_age_banda($arrPlan[$i]['id']);
                }
                $cantPremium = count($arrPemium);
                for($j=0; $j < $cantPremium; $j++){
                    $intervalsMin[] = $arrPemium[$j]['age_min'];
                    $intervalsMax[] = $arrPemium[$j]['age_max'];
                }
            }else{
                $minAgeInterval = ($arrPlan[$i]['min_age'] < $minAgeInterval || $minAgeInterval === false)? $arrPlan[$i]['min_age']: $minAgeInterval;
                if($addFamilyInterval === false && $arrPlan[$i]['family_plan']=='Y') {
                    $intervalsMax[] = 20;
                    $intervalsMin[] = 21;
                    $addFamilyInterval = true;
                }
                if($arrPlan[$i]['overage_factor'] >= 1 && $arrPlan[$i]['overage_factor_cost'] >= 1 && $arrPlan[$i]['normal_age'] < $arrPlan[$i]['max_age'] && $arrPlan[$i]['normal_age'] >= $arrPlan[$i]['min_age']){
                    $intervalsMax[] = $arrPlan[$i]['normal_age'];
                    $intervalsMin[] = $arrPlan[$i]['normal_age']+1;
                    $intervalsMax[] = $arrPlan[$i]['max_age'];
                }else{
                    $intervalsMax[] = ($arrPlan[$i]['max_age'] > 0)? $arrPlan[$i]['max_age']: $intervalsMin[(count($intervalsMin) - 1)] + 1;
                } 
            } 
        }
        if($minAgeInterval !== false){
            $intervalsMin[] = $minAgeInterval;
        }
        //~ Se eliminan los repetidos
        $intervalsMin = array_unique($intervalsMin);
        $intervalsMax = array_unique($intervalsMax);
        //~ Ordenamiento de rangos
        sort($intervalsMin);
        sort($intervalsMax);
        $cantMin = count($intervalsMin);
        $cantMax = count($intervalsMax);
        $cantidad = ($cantMin < $cantMax)? $cantMax: $cantMin;
        $cMin = 0; //~ Contador de array de intervalo de menores
        $cMax = 0; //~ Contador de array de intervalo de mayores
        $cnt = 0; //~ Contador de intervalos totales
        $endIntervals = false;
        $minVal = 9999;
        $maxVal = 0;
        while(($cMin <= $cantMin || $cMax <= $cantMax) && $endIntervals === false){
            if(!isset($intervalsMin[$cMin]) && !isset($intervalsMax[$cMax])){
                $endIntervals = true;
            }else{
                //~ Segmento para buscar los valores menores
                if(($cnt == 0 || $intervalsMin[$cMin] == ($intervals[$cnt-1]['max'] + 1)) && isset($intervalsMin[$cMin])){
                    $intervals[$cnt]['min'] = $intervalsMin[$cMin];
                    $cMin++;
                }else{
                    $intervals[$cnt]['min'] = $intervals[$cnt-1]['max'] + 1;
                }
                //~ Segmento para buscar los valores mayores
                if(($intervalsMax[$cMax] < $intervalsMin[$cMin] && isset($intervalsMax[$cMax])) || !isset($intervalsMin[$cMin])){
                    $intervals[$cnt]['max'] = $intervalsMax[$cMax];
                    $cMax++;
                }else if(isset($intervalsMin[$cMin])){
                    $intervals[$cnt]['max'] = $intervalsMin[$cMin] - 1;
                }else{
                    $intervals[$cnt]['max'] = $intervalsMin[$cMin-1];
                }
                $minVal = ($intervals[$cnt]['min'] < $minVal)? $intervals[$cnt]['min']: $minVal;
                $maxVal = ($intervals[$cnt]['max'] > $maxVal)? $intervals[$cnt]['max']: $maxVal;
                $cnt++;
            }
        }
        $intervals[0]['minVal'] = $minVal;
        $intervals[0]['maxVal'] = $maxVal;
        $intervals[0]['cantidad'] = $cnt;
        return $intervals;
    }
    function get_plan_unidad_new($categoria='', $activo='', $id_broker='') {
		$query = "SELECT
                    plans.id,
                    plans.unidad,
                    plans.min_age,
                    plans.max_age,
                    plans. NAME,
                    plans.normal_age,
                    plans.overage_factor,
                    plans.overage_factor_cost,
                    plan_detail.description,
                    plans.family_plan
                FROM
                    plans
                                                LEFT JOIN restriction ON plans.id = restriction.id_plans
                                LEFT JOIN broker ON broker.id_broker = restriction.id_broker
                                                LEFT JOIN plan_detail ON plans.id = plan_detail.plan_id
                WHERE
                        plans.activo = '1'
                    AND plans.eliminado = '1'";
		if($id!='') {
			$query .=" and plans.id = '$id' ";
        }
        if (!empty($id_broker)) {
            $obj_quote_general 		= new Quote_general();
            $arrBroker    = "";
            $brokerParent = $this->niveles_agencias($id_broker)[0];
            while ($brokerParent['parent'] != 0) {
                $brokerParent = $this->niveles_agencias($brokerParent['parent'])[0];
                $arrBroker    = (!empty($arrBroker)) ? $arrBroker . ", '" . $brokerParent['id_broker'] . "'" : "'" . $brokerParent['id_broker'] . "'";
            }
            $arrBroker = (!empty($arrBroker)) ? $arrBroker : '0';
            $query .= "AND
					IF (
						(
							SELECT
								broker.opcion_plan
							FROM
								broker
							WHERE
								broker.id_broker = '$id_broker'
							AND broker.opcion_plan = 2
						),
						(
							(
								broker.id_broker IN ($arrBroker)
								AND dirigido = '6'
							)
							OR broker.id_broker = '$id_broker'
						),
						(
							broker.opcion_plan = '1'
							OR broker.opcion_plan IS NULL
							OR (
								(
									broker.id_broker IN ($arrBroker)
									AND dirigido = '6'
								)
								OR broker.id_broker = '$id_broker'
							)
						)
					)";
        }
		if($categoria!='') {
			$query .=" and plans.id_plan_categoria = '$categoria' ";
		}
		if ($language_id!=''){
            $query.=" AND plan_detail.language_id = '$language_id' ";
		}
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_All_Plan_Id2($id = '', $lang = '', $id_distin='', $namecategoria='') {
            $query = "SELECT
                        plan_category.id_plan_categoria,
                        plan_categoria_detail.description,
                        plan_categoria_detail.name_plan,
                        description_plan,
                        img
                        FROM plan_category Inner Join plan_categoria_detail ON 
                        plan_category.id_plan_categoria = plan_categoria_detail.id_plan_categoria WHERE 1
                        ";
                    if (!empty($id))
                        $query.=" AND plan_category.id_plan_categoria='$id'";
                    if (!empty($id_distin))
                        $query.=" AND plan_category.id_plan_categoria!='$id_distin'";
                    if (!empty($lang))
                        $query.=" AND plan_categoria_detail.language_id =  '$lang'";
                    if (!empty($namecategoria))
                        $query.=" AND plan_categoria_detail.name_plan =  '$namecategoria'";
                    //die($query);
                        $query.=" ORDER BY
                    plan_category.orden ASC";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function add_error_order($order,$data){
                    $data	 = $this-> eliminar_caracteres_prohibidos($data); 
                    $query = "Insert into order_error_save_data (id_order,data_save) values ('$order','$data')";
                    $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query);
        return $result;
    }
    function eliminar_caracteres_prohibidos($arreglo) {
            $caracteres_prohibidos = array("'","/","<",">",";");    
        return str_replace($caracteres_prohibidos,"",$arreglo); 
    }
    function Get_Plans_by_tiempo_cotizador_byid($id) {
            $query = "SELECT * FROM plan_times WHERE id = '$id'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_orden($idorden) {		
            $query = "SELECT id FROM orders WHERE codigo='" . $idorden . "' ORDER BY id DESC LIMIT 1";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
     }
     function get_dir_habitacion_plans($id_plan=''){		
            $query="SELECT
                        plans.dir_habitacion,
                        plans.plan_renewal
                        FROM  
                        plans
                        WHERE 1 ";
            if($id_plan!=''){ $query.=" AND id = '$id_plan' "; }		
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function add_order_address($id_orden, $direccion, $direccion1, $ciudad, $estado, $zipcode, $pais_iso){
            $id_orden = $id_orden?:0; 
            $query = "INSERT INTO order_address(id_orden, 
                        direccion, 
                        direccion1, 
                        ciudad, 
                        estado, 
                        zipcode, 
                        pais_iso) 
                    VALUES('$id_orden', 
                        '$direccion', 
                        '$direccion1', 
                        '$ciudad', 
                        '$estado', 
                        '$zipcode', 
                        '$pais_iso')";
        $this->_SQL_tool($this->INSERT, __METHOD__, $query);
    }
    public function Get_Broker_by_user($id = '', $id_broker, $forward = '0'){
                        $query = "SELECT
                        broker.id_broker,
                        user_associate.id_associate,
                        user_associate.id_user,
                        user_associate.id_type,
                        broker.broker,
                        broker.credito_base,
                        broker.credito_actual,
                        broker.id_country,
                        broker.phone1,
                        broker.id_state,
                        broker.id_city,
                        broker.fecha_credito,
                        broker.correlativo,
                        broker.img_broker,
                        broker.price_type,
                        month(fecha_credito) mes_credito,
                        year(fecha_credito) ano_credito,
                        day(fecha_credito) dia_credito
                        FROM
                        users
                        Inner Join user_associate ON users.id = user_associate.id_user
                        Inner Join broker ON user_associate.id_associate = broker.id_broker
                        WHERE users.id_status <> '4'";
                        if (!empty($id)) {$query .= " AND users.id =  '$id'";}
                        if (!empty($id_broker)) { $query .= " AND broker.id_broker =  '$id_broker'";}
                        if (!empty($forward)){$query .= " AND users.id_status =  '1'";}
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_Broker_Asoc($id_broker = ''){
                        $query = "SELECT * FROM broker WHERE 1";
                        if (!empty($id_broker)) {$query .= " AND broker.id_broker IN ($id_broker)";}
                        $query .= " ORDER BY broker DESC";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_Preventas_By_id($id_preventa=''){
                        if(!empty($id_preventa)){
                            $query ="SELECT * FROM preventas WHERE id = '$id_preventa'";
                        }  
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Update_Uso_Preventa($id_preventa){
                        if(!empty($id_preventa)){
                            $query="UPDATE preventas SET usado='1' WHERE id ='$id_preventa' ";
                        }
        $this->_SQL_tool($this->UPDATE, __METHOD__, $query, $comentario);
    }
    public function Descuenta_Dias_Preventa($id_preventa='', $dias=''){
                        if(!empty($id_preventa) && !empty($dias)){
                            $query="UPDATE preventas SET preventas.dias_restantes='$dias' WHERE preventas.id='$id_preventa'";
                        }
        $this->_SQL_tool($this->UPDATE, __METHOD__, $query, $comentario);
    }
    public function edit_broker_credito_quote($id, $credito_actual){
                        $query = "UPDATE broker set credito_actual='$credito_actual' WHERE id_broker='$id'";                        
                        $comentario = 'UPDATE | Broker credito  Name: ' . $broker . ', ID Broker: ' . $id;
        $this->_SQL_tool($this->UPDATE, __METHOD__, $query, $comentario);
    }
    public function Delete_Comisiones($id_order, $id_broker){
                        $query = "delete from order_comision where id_order = '$id_order' ";
                        if (!empty($id_broker)) {$query .= "  AND id_broker='$id_broker' "; }
        $this->_SQL_tool($this->DELETE, __METHOD__, $query);
    }
    public function Assign_Commissions($idBroker, $idCategory, $idOrder, $codeOrder, $TotalComisionable){
                        $arrBroker  = $this->Get_Broker_nivel('', $idBroker)[0];
                        $percentage = 0;
                            for ($i = $arrBroker['nivel']; $i > 0; $i--) {
                                $arrCommissions   = $this->Get_Broker_Comision($idBroker, 'eng', $idCategory)[0];
                                $percentage       = $arrCommissions['porcentaje_categoria'] - $percentage;
                                $amountCommission = ($percentage > 0) ? (($percentage / 100) * $TotalComisionable) : 0;
                                $this->Add_order_commission($idOrder, $idBroker, $percentage, $amountCommission, $codeOrder);
                                $percentage = $arrCommissions['porcentaje_categoria'];
                                $arrBroker  = $this->Get_Broker_nivel('', $idBroker)[0];
                                $idBroker   = $arrBroker['parent'];
                            }
    }
    public function Add_order_commission($id_order = '', $id_broker = '', $porcentage = 0, $monto_comision = 0, $codigo_final = ''){
                        $id_order        = $id_order ?: 0;
                        $id_broker       = $id_broker ?: 0;
                        $porcentage      = $porcentage ?: 0;
                        $monto_comision  = $monto_comision ?: 0;
                        $query           = "INSERT INTO order_comision (id_order, id_broker, porcentage, monto_comision, tr_date) VALUES ('$id_order', '$id_broker', '$porcentage', '$monto_comision', NOW())";
                        $result          = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert order comision <-> idOrder:' . $id_order, '', '', '_DEFAULT', $codigo_final);
        return $this->id = $result;
    }
    function getbenefisdetallelng_filtroraider($idplan = '', $raider_cotiza, $promocion='') {
                        $query = "SELECT
                                raiders.cost_raider,
                                raiders.value_raider,
                                raiders.neta_raider,
                                raiders.type_raider,
                                raiders.id_benefi,
                                raiders.id_raider,
                                raiders.id_status,
                                raiders.rd_calc_type,
                                plan_raider.id_plan,
                                raiders.promocion
                                FROM raiders
                                Inner Join plan_raider ON plan_raider.id_raider = raiders.id_raider
                                WHERE
                                plan_raider.id_plan =  '$idplan' AND
                                raiders.id_status =  '1'";
                                if(!empty($raider_cotiza)){
                                    $query.= " AND raiders.id_raider IN (";
                                    foreach ($raider_cotiza as $raider) {
                                    $query.= $raider['id_raider'].",";
                                    }
                                    $query = substr($query, 0, -1);
                                    $query.= ")";
                                } 
                                 if(!empty($promocion)) $query.=" AND raiders.promocion <> '$promocion'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
        }
    function beneficios_plan($id_plan="",$idioma=""){
                                $query = "SELECT
                                        benefit.`name`,
                                        benefit_plan.valor_$idioma
                                        FROM
                                        benefit_plan
                                        JOIN benefit ON benefit_plan.id_beneficio = benefit.id
                                        WHERE 
                                        benefit_plan.id_plan = $id_plan
                                        AND (
                                            benefit.eliminado IS NULL
                                            OR benefit.eliminado <> '2'
                                        )
                                        AND (
                                            benefit_plan.eliminado IS NULL
                                            OR benefit_plan.eliminado <> '2'
                                        )
                                        ";
            return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
          }
    function get_benefit_site($arrayPlans = '',$languaje = ''){
                                $query = "SELECT DISTINCT
                                            benefit.id,
                                            benefit_detail.`name` name
                                        FROM
                                            `benefit`
                                        INNER JOIN benefit_detail ON benefit_detail.id_benefit = benefit.id
                                        INNER JOIN benefit_plan ON benefit_plan.id_beneficio = benefit.id
                                        WHERE
                                            benefit_plan.id_plan IN ($arrayPlans)
                                        AND benefit_detail.language_id = '$languaje'
                                        AND benefit.activo = '1'
                                        AND (
                                            benefit.eliminado IS NULL
                                            OR benefit.eliminado <> '2'
                                        )
                                        AND (
                                        benefit_plan.eliminado IS NULL
                                        OR benefit_plan.eliminado <> '2'
                                        )
                                        ORDER BY
                                            benefit_plan.orden ASC";
            return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
        }
    function get_benefit_value_by_plan($arraybenefit = '',$id_plan = '', $languaje = ''){
                                $query = "SELECT
                                            benefit_detail.id_benefit id,
                                            valor_$languaje valor
                                        FROM
                                            `benefit_plan`
                                        INNER JOIN benefit_detail ON benefit_detail.id_benefit = benefit_plan.id_beneficio
                                        WHERE
                                            id_beneficio IN ($arraybenefit)
                                        and benefit_plan.id_plan = '$id_plan'
                                        AND benefit_detail.language_id = '$languaje'
                                        ORDER BY
                                            benefit_plan.orden ASC";
            return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
        }
        function get_benefit_by_plans($array_plans, $idioma){
                                $query = "SELECT DISTINCT
                                        benefit.id,
                                        benefit_detail.name,";
                                    foreach ($array_plans as $plan) {
                                        $id_plan = $plan['idp'];
                                        $query.="IFNULL((
                                        SELECT DISTINCT
                                            valor_$idioma
                                        FROM
                                            benefit_plan
                                        WHERE
                                            benefit_plan.id_beneficio = benefit.id
                                            AND benefit_plan.id_plan = '$id_plan' 
                                            AND (benefit_plan.eliminado IS NULL OR benefit_plan.eliminado <> '2') LIMIT 1), '-') AS '$id_plan',";
                                    }
                                        $query.="activo
                                    FROM
                                        `benefit`
                                    INNER JOIN benefit_detail ON benefit_detail.id_benefit = benefit.id
                                    INNER JOIN benefit_plan ON benefit_plan.id_beneficio = benefit.id
                                    WHERE
                                        benefit_plan.id_plan IN (";
                                        foreach ($array_plans as $plan) {
                                            $id_plan = $plan['idp'];
                                            $query.="'$id_plan',";
                                        }
                                        $query = substr($query, 0, -1);
                                        $query.=")
                                    AND benefit_detail.language_id = '$idioma'
                                    AND benefit.activo = '1'
                                        AND (benefit.eliminado IS NULL OR benefit.eliminado <> '2')
                                    ORDER BY
                                        benefit_plan.orden ASC";
            return $this->_SQL_tool($this->SELECT, __METHOD__, $query, '', '', '');
    }
    function getbenefisdetallelng_filtroraider_by_language_new($idplan = '', $idioma = "",$codigo='') {    
                                $query = "SELECT  
                                        IFNULL(  
                                            raiders_detail.name_raider,  
                                            raiders.name_raider  
                                        ) AS name_raider,  
                                        raiders.cost_raider,  
                                        raiders.value_raider,  
                                        raiders.neta_raider,  
                                        raiders.type_raider,  
                                        raiders.id_benefi,  
                                        raiders.id_raider,  
                                        raiders.id_status,  
                                        raiders.rd_calc_type,  
                                        plan_raider.id_plan,  
                                        raiders_detail.language_id,
                                            raiders_detail.description,
                                            raiders_detail.imagen,
                                            raiders_detail.document,
                                            raiders.promocion,
                                            raiders.campaign,
                                            raiders.link_promocion,
                                            raiders.img_codeqr 
                                        FROM raiders  
                                            INNER JOIN plan_raider ON plan_raider.id_raider = raiders.id_raider  
                                            LEFT JOIN raiders_detail ON raiders_detail.id_raider = raiders.id_raider  ";
                                        if(!empty($codigo)){$query.=" left join orders_raider ON orders_raider.id_raider=raiders.id_raider ";}
                                    $query.= " WHERE  
                                            plan_raider.id_plan = '$idplan'   
                                            AND raiders.id_status = '1'   
                                            AND raiders_detail.language_id = '$idioma' ";
                                    if(!empty($codigo)){$query.=" and orders_raider.id_orden='$codigo' ";
                                    $query.=" ORDER BY plan_raider.orden ASC ";
     }
            return $this->_SQL_tool($this->SELECT, __METHOD__, $query);   
    }
    function getbenefisdetallelng_Raider($idlan = '', $idtypebenefi='', $id_benefit='') {
                                    $query = "SELECT
                                                benefit_detail.id_benefit,
                                                benefit_detail.`name`,
                                                benefit_detail.language_id,
                                                benefit.activo,
                                                benefit.id,
                                                benefit.Type_benefit,
                                                benefit.eliminado
                                            FROM benefit
                                                Inner Join benefit_detail ON benefit.id = benefit_detail.id_benefit
                                            WHERE benefit.activo = 1
                                                    AND (benefit.eliminado IS NULL OR benefit.eliminado <> '2') ";
                                    if(!empty($idlan))$query.=" AND (benefit_detail.language_id = '$idlan' OR benefit_detail.language_id='eng')";
                                    if(!empty($idtypebenefi))$query.=" AND Type_benefit='$idtypebenefi'";
                                    if(!empty($id_benefit))$query.=" AND benefit.id = '$id_benefit'";
          return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_Raider_detalle($id = '',$language_id) {
                                     $query = "SELECT * FROM raiders_detail WHERE raiders_detail.id_raider='$id' AND language_id= '$language_id'";
          return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function get_email_beneficiaries($id_orden){
                                    $query = "SELECT
                                            beneficiaries.email
                                            FROM
                                            beneficiaries
                                            WHERE
                                            beneficiaries.id_orden = '$id_orden'
                                            ORDER BY
                                            beneficiaries.id_orden ASC
                                            LIMIT 1";
          return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_broker_parametrizado($broker = ''){
                                    $query = "SELECT
                                                broker.id_broker,
                                                broker.dominio,
                                                broker.broker_parametrizado
                                            FROM
                                                broker
                                            WHERE
                                                1";
                                    if (!empty($broker)) { $query .= " AND broker.id_broker='$broker'"; }
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_Broker($id_broker = '', $status = ''){
                                    $query = "SELECT * FROM broker WHERE 1";
                                        if (!empty($id_broker)) { $query .= " AND broker.id_broker='$id_broker'"; }
                                        if (!empty($status)) { $query .= " AND broker.id_status='$status'"; }
                                    $query .= " ORDER BY broker ASC";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_Broker_img($id_broker = ''){
                                    $query = "SELECT
                                                broker.img_broker,
                                                broker.broker
                                                FROM `broker`
                                                WHERE 1";
                                    if (!empty($id_broker)) {$query .= " AND id_broker = '$id_broker'";}
                                     $query .= " ORDER BY broker DESC";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_Preventas_By_Plan($id_broker='', $Broker_aso='', $id_plan=''){
                                    if(!empty($id_plan) && (!empty($Broker_aso) || !empty($id_broker))){
                                        $query="
                                            SELECT
                                                *
                                            FROM
                                                preventas
                                            WHERE
                                            (
                                                CASE 
                                                WHEN preventas.uso = '1' THEN
                                                    preventas.id_broker = '$id_broker'
                                                WHEN preventas.uso IN ('2','3','4') THEN
                                                    preventas.id_broker IN ($Broker_aso)
                                                END
                                            ) = 1
                                            AND preventas.id_plan = '$id_plan'
                                        ";
                                        $query.=" AND preventas.comprado = '1'";
                                        $query.=" AND preventas.id_status = '1'";
                                        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
                                    }else{
                                        return null;
                                    }
    }
    public function Get_Pasajeros($id_order = '', $nombre = '', $id_pasajero = '', $ben_status = ''){
                                    $query = "SELECT beneficiaries.*,
                                                beneficiaries_contacts.nombre AS nombre_contacto,
                                                beneficiaries_contacts.email AS email_contacto,
                                                beneficiaries_contacts.telefono AS telefono_contacto
                                            FROM
                                                beneficiaries
                                            LEFT JOIN beneficiaries_contacts ON beneficiaries.id = beneficiaries_contacts.id_beneficiaries
                                            WHERE
                                            1";
                                    if (!empty($id_order)) {
                                        $query .= " AND beneficiaries.id_orden ='$id_order'";
                                    }
                                    if (!empty($ben_status)) {
                                        $query .= " AND beneficiaries.ben_status ='$ben_status'";
                                    }
                                    if (!empty($id_pasajero)) {
                                        $query .= " AND beneficiaries.id ='$id_pasajero'";
                                    }
                                    if (!empty($nombre)) {
                                        $nombre = trim($nombre);
                                        $pana   = explode(' ', $nombre);
                                        $query .= " AND (concat_ws(' ', TRIM(BOTH ' ' FROM beneficiaries.nombre), TRIM(BOTH ' ' FROM beneficiaries.apellido)) LIKE '%$nombre%'
                                        OR TRIM(BOTH ' ' FROM beneficiaries.nombre) LIKE '%$pana[0]%'
                                        AND TRIM(BOTH ' ' FROM beneficiaries.apellido) LIKE '%$pana[1]%')";
                                    }
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_Broker_Link($id_broker = '', $language_id = ''){
                                    $query = "SELECT
                                                links.codigo,
                                                broker.logo_mostrar,
                                                broker.img_broker
                                                FROM
                                                broker
                                                INNER JOIN links ON broker.id_broker = links.id_broker
                                                WHERE 1";
                                    if (!empty($id_broker)) {
                                        $query .= " AND broker.id_broker='$id_broker'";
                                    }
                                    if (!empty($language_id)) {
                                        $query .= " AND links.language_id='$language_id'";
                                    }
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_broker_new($idBroker = ''){
                                     $query = "SELECT forma_pago FROM `broker` where  id_broker= '$idBroker' ";
        return $this->_SQL_tool($this->SELECT_SINGLE, __METHOD__, $query);
    }
    function validarIdBmiAgencia($idBmi){
                                    $query = "SELECT id_broker AS id FROM `broker` WHERE id_bmi = '$idBmi'";
                                    $broker = $this->_SQL_tool($this->SELECT, __METHOD__, $query);
        return ($broker) ? $broker[0]["id"]:false;
    }
    function get_cotiza_compra($id_cotiza, $id_plan){
                                    $query = "SELECT
                                            cotizas.id,
                                            cotizas.nombre_cliente,
                                            cotizas.apellido_cliente,
                                            cotizas.correo_cliente,
                                            cotizas.telf_cliente,
                                            cotizas.salida,
                                            cotizas.llegada,
                                            cotizas.dias,
                                            cotizas.pasajeros,
                                            cotizas.origen,
                                            cotizas.destino,
                                            cotizas.fecha,
                                            cotizas.id_agente,
                                            cotizas.id_broker,
                                            cotiza_plan.id_plan,
                                            cotiza_plan.id_plan_categoria,
                                            cotizas.plans,
                                            cotizas.ages
                                        FROM
                                            cotizas
                                        INNER JOIN cotiza_plan ON cotiza_plan.id_cotiza = cotizas.id
                                        WHERE
                                            cotizas.id = '$id_cotiza'
                                        AND cotiza_plan.id_plan = '$id_plan'
                                        AND cotizas.id_status = 2";
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function select_cotiza_subtotales($id_cotiza, $id_plan){
                                        $query = "SELECT
                                                cotiza_subtotales.id_plan,
                                                cotiza_subtotales.pasajeros,
                                                cotiza_subtotales.rango_min,
                                                cotiza_subtotales.rango_max,
                                                cotiza_subtotales.precio_individual,
                                                cotiza_subtotales.subtotal,
                                                cotiza_subtotales.banda
                                            FROM
                                                cotiza_subtotales
                                            WHERE
                                                cotiza_subtotales.id_cotiza = '$id_cotiza' AND id_plan = '$id_plan'";
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function update_cotcotiza($id_cotiza, $salida,$llegada){
		                                $query = "UPDATE cotizas SET salida = '$salida' , llegada = '$llegada' WHERE id = '$id_cotiza'";
		$this->_SQL_tool($this->UPDATE, __METHOD__, $query);
    }
    function select_montos_raiders($id_cotiza, $id_plan){
                                        $query = "SELECT
                                                id_raider,
                                                id_plan,
                                                pas_sel,
                                                total
                                            FROM
                                                cotiza_raider
                                            WHERE
                                                id_cotiza = '$id_cotiza'";
                                        if(!empty($id_plan)){$query.= " AND id_plan = '$id_plan'";}
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function get_logo_empresa(){
		                                $query = "SELECT parameter_value, logo_empresa FROM parameters WHERE parameter_key = 'SYSTEM_NAME'";
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function add_cotiza($nombre, $apellido, $correo, $telefono, $salida, $llegada, $dias, $origen, $destino, $pasajeros, $id_agente, $id_broker, $type=1, $planes='', $ages=''){
                                            $id_broker = $id_broker?:0; 
                                            $query = "INSERT INTO cotizas (
                                                    nombre_cliente,
                                                    apellido_cliente,
                                                    correo_cliente,
                                                    telf_cliente,
                                                    salida,
                                                    llegada,
                                                    dias,
                                                    pasajeros,
                                                    origen,
                                                    destino,
                                                    fecha,
                                                    id_agente,
                                                    id_broker,
                                                    id_status,
                                                    type,
                                                    plans,
                                                    ages
                                                )
                                                VALUES (
                                                    '$nombre',
                                                    '$apellido',
                                                    '$correo',
                                                    '$telefono',
                                                    '$salida',
                                                    '$llegada',
                                                    '$dias',
                                                    '$pasajeros',
                                                    '$origen',
                                                    '$destino',
                                                    NOW(),
                                                    '$id_agente',
                                                    '$id_broker',
                                                    '2',
                                                    '$type',
                                                    '$planes',
                                                    '$ages'
                                                ) ";
		return $this->_SQL_tool($this->INSERT, __METHOD__, $query);
    }
    function add_planes_cotiza($id_cotiza, $plan, $id_plan_categoria, $total, $total_neto){
                                                $query = "INSERT INTO cotiza_plan(id_cotiza,
                                                                    id_plan,
                                                                    id_plan_categoria,
                                                                    total,
                                                                    total_neto)
                                                            VALUES('$id_cotiza',
                                                                    '$plan',
                                                                    '$id_plan_categoria',
                                                                    '$total',
                                                                    '$total_neto')";
		$this->_SQL_tool($this->INSERT, __METHOD__, $query);
    }
    function add_subtotales($id_cotiza, $id_plan, $pasajeros, $rango_min, $rango_max, $banda, $precio, $subtotal){
		                                        $rango_min = $rango_min?:0;                                                
                                                $query = "INSERT INTO cotiza_subtotales (
                                                            id_cotiza,
                                                            id_plan,
                                                            pasajeros,
                                                            rango_min,
                                                            rango_max,
                                                            banda,
                                                            precio_individual,
                                                            subtotal) 
                                                        VALUES('$id_cotiza',
                                                            '$id_plan',
                                                            '$pasajeros',
                                                            '$rango_min',
                                                            '$rango_max',
                                                            '$banda',
                                                            '$precio',
                                                            '$subtotal')";
		$this->_SQL_tool($this->INSERT, __METHOD__, $query);
    }
    function update_comentarios_cotiza($id_cotiza, $comentarios){
		                                        $query = "UPDATE cotizas SET comentarios = '$comentarios' WHERE id = '$id_cotiza'";
		$this->_SQL_tool($this->UPDATE, __METHOD__, $query);
    }
    function get_plan_dias_multiviajes($id_plan){
                                                $query = "SELECT
                                                            plans.dias_multiviajes
                                                        FROM
                                                            plans
                                                        WHERE
                                                            id = '$id_plan'";
                                                return $this->_SQL_tool($this->SELECT_SINGLE, __METHOD__, $query)['dias_multiviajes'];
    }
    function Get_MAXAge_plans($id_plan_categoria = '') {
        $query = "SELECT MAX(max_age) as edad FROM plans where id_plan_categoria = '$id_plan_categoria' and eliminado = '1' AND activo = '1'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_orden_all($idorden='') {
                                                                $query = "SELECT *,month(retorno) mes_regreso, year(retorno) ano_regreso, day(retorno) dia_regreso,month(salida) mes_salida, year(salida) ano_salida, day(salida) dia_salida,(select description from countries where countries.iso_country=origen) elorigen,
                                                                IF (
                                                                orders.territory = '',
                                                                (
                                                                    SELECT
                                                                        description
                                                                    FROM
                                                                        countries
                                                                    WHERE
                                                                        countries.iso_country = destino
                                                                ),
                                                                (
                                                                    SELECT
                                                                        desc_small
                                                                    FROM
                                                                        territory
                                                                    WHERE
                                                                        territory.id_territory = territory
                                                                )
                                                            ) AS eldestino,month(fecha) mes_fecha, year(fecha) ano_fecha, day(fecha) dia_fecha,(select broker from broker where broker.id_broker=agencia) nombre_agencia,(select phone1 from broker where broker.id_broker=agencia) telefono_agencia ,(select name from plans where plans.id=producto) plan FROM orders WHERE id='".$idorden."'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_Plans($id_plan = '', $id_plan_categoria = '', $eliminado = '') {
                                                        $query = "SELECT * FROM plans WHERE 1";
                                                        if (!empty($id_plan))
                                                            $query.=" AND id = '$id_plan'";
                                                        if (!empty($id_plan_categoria))
                                                            $query.=" AND id_plan_categoria = '$id_plan_categoria'";
                                                        if (!empty($eliminado))
                                                            $query.=" AND eliminado = '$eliminado'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function get_pasos_preorden($id_preorden){
                                                        $query="SELECT data_pasos FROM preorder_select WHERE id = '$id_preorden'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Update_Preorden_select($id_preorden, $usuario_id,$id_plan_categoria,$salida,$retorno,$origen, $destino,$cantidad_pasajero,$nacimiento1,$nacimiento2,$nacimiento3,$nacimiento4,$nacimiento5,$nacimiento6,$nacimiento7,$nacimiento8,$nacimiento9,$plan_seleccionado, $id_agencia, $respuesta, $codigo, $email, $paso, $ip, $estatus, $datos_pasajeros, $data_pasos) {
                                                        $query = "UPDATE preorder_select SET usuario_id='$usuario_id', id_plan_categoria='$id_plan_categoria', salida='$salida', retorno='$retorno', origen='$origen', destino='$destino',cantidad_pasajero='$cantidad_pasajero', nacimiento1='$nacimiento1', nacimiento2='$nacimiento2', nacimiento3='$nacimiento3', nacimiento4='$nacimiento4', nacimiento5='$nacimiento5', nacimiento6='$nacimiento6', nacimiento7='$nacimiento7', nacimiento8='$nacimiento8', nacimiento9='$nacimiento9', plan_seleccionado='$plan_seleccionado', voucher='$codigo', authorize='$respuesta', agencia='$id_agencia', email_usado='$email', paso='$paso', ip_origen='$ip', estatus='$estatus', datos_pasajeros = '$datos_pasajeros', data_pasos = '$data_pasos' where id = '$id_preorden' ";
                                                        $this->_SQL_tool($this->UPDATE, __METHOD__, $query, 'Update preorder_select <-> user:' . $usuario_id);
        return $id_preorden;
    }
    function Add_Preorden_select($usuario_id,$id_plan_categoria,$salida,$retorno,$origen, $destino,$cantidad_pasajero,$nacimiento1,$nacimiento2,$nacimiento3,$nacimiento4,$nacimiento5,$nacimiento6,$nacimiento7,$nacimiento8,$nacimiento9,$plan_seleccionado, $id_agencia, $respuesta, $codigo, $email, $paso, $ip, $estatus, $datos_pasos) {
                                                        $plan_seleccionado = $plan_seleccionado?:0;
                                                        $usuario_id = $usuario_id?:0;
                                                        $id_plan_categoria = $id_plan_categoria?:0;
                                                        $cantidad_pasajero = $cantidad_pasajero?:0;
                                                        $query = "INSERT INTO preorder_select(usuario_id,id_plan_categoria,salida,retorno,origen, destino,cantidad_pasajero,nacimiento1,nacimiento2,nacimiento3,nacimiento4,nacimiento5,nacimiento6,nacimiento7,nacimiento8,nacimiento9, fecha, plan_seleccionado, voucher, authorize, agencia, email_usado, paso, ip_origen, estatus, data_pasos) VALUES('$usuario_id','$id_plan_categoria','$salida','$retorno','$origen','$destino','$cantidad_pasajero','$nacimiento1','$nacimiento2','$nacimiento3','$nacimiento4','$nacimiento5','$nacimiento6','$nacimiento7','$nacimiento8','$nacimiento9', NOW(), '$plan_seleccionado', '$codigo', '$respuesta', '$id_agencia', '$email', '$paso', '$ip', '$estatus', '$datos_pasos')";
        return $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert preorder_select <-> user:' . $usuario_id);
    }
    function getUsers($usuario_id = null) {
                                                        $query = "SELECT * FROM users ";
                                                        if (!is_null($usuario_id))
                                                            $query .= " WHERE id='$usuario_id' ";
                                                        $query .= " order by firstname";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
     }
     function getAllBorkerAdmin($idOrden){
                                                        $query = "SELECT
                                                                    users.email AS email,
                                                                    users.firstname AS nombre,
                                                                    users.lastname AS apellido
                                                                FROM
                                                                    `broker`
                                                                INNER JOIN user_associate ON broker.id_broker = user_associate.id_associate
                                                                INNER JOIN users ON users.id = user_associate.id_user
                                                                WHERE broker.id_broker = '$idOrden' AND users.user_type = '2'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_voucher_code($prefix='XX'){
                                                                $length = 6;
                                                                $charac = "0123456789ABCDEFGHIJKML";
                                                                $repeat = false;
                                                                do{
                                                                    $result = "";
                                                                    while(strlen($result) < $length){
                                                                        $result.= substr($charac, mt_rand(0,(strlen($charac))), 1);
                                                                    }
                                                                    $code= $prefix."-".$result;
                                                                    $query="SELECT * FROM voucher_codes WHERE code='$code'";
                                                                    $reply=$this->_SQL_tool($this->SELECT, __METHOD__, $query);
                                                                    if(!empty($reply)){
                                                                        $repeat=true;
                                                                    }else{
                                                                        $repeat=false;
                                                                        $query="INSERT INTO voucher_codes (code, status, date) VALUES ('$code','2', now())";
                                                                        $this->_SQL_tool($this->INSERT, __METHOD__, $query);
                                                                    }
                                                                }while($repeat);
        return($code);
    } 
    function Add_order_agent($idOrder='', $idAgent=''){
                                                                $query = "UPDATE orders 
                                                                        SET id_agente_relacionado = '$idAgent'
                                                                        WHERE id = '$idOrder' ";
		$this->_SQL_tool($this->UPDATE, __METHOD__, $query);
    }
    function Add_order_event($idOrden='', $notiEmail='', $addCalendar='', $notiSMS='', $numSMS=''){
                                                                $query = "INSERT INTO `orders_eventos` (
                                                                    oe_id_orden,
                                                                    noti_correo,
                                                                    add_calendar,
                                                                    noti_sms,
                                                                    num_sms
                                                                )
                                                                VALUES
                                                                    ('$idOrden', '$notiEmail', '$addCalendar', '$notiSMS', '$numSMS')";
		$this->_SQL_tool($this->INSERT, __METHOD__, $query);
    }
    function Get_All_Plan_Category_quote($idioma='',$search = '', $order = '', $min = '', $max = '') {
                                                                $query = "SELECT plan_category.id_plan_categoria, plan_category.name_plan, plan_category.valor_menor, plan_category.created, plan_category.modified,plan_category.id_status,plan_category.img,plan_categoria_detail.name_plan AS name, plan_categoria_detail.tiempos, plan_categoria_detail.description AS description FROM plan_category Inner Join plan_categoria_detail ON plan_category.id_plan_categoria = plan_categoria_detail.id_plan_categoria WHERE plan_categoria_detail.language_id =  '$idioma' and plan_category.vision_id=1 and plan_category.id_status=1 ";
                                                                if (!empty($search)) {
                                                                    $query.=" AND name like '%$search%' ";
                                                                }
                                                                $query .= " ORDER BY  plan_category.orden ASC ";
                                                                if (!empty($max)) {
                                                                    $query .= " LIMIT $min,$max ";
                                                                }
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_Plans_Category_Leng($id_plan_categoria, $leng) {
                                                                $query = "SELECT * FROM plan_categoria_detail WHERE id_plan_categoria = '$id_plan_categoria' AND  language_id='$leng'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_Plans_Category_Leng_name_plan($id_plan_categoria, $leng) {
                                                                 $query = "SELECT name_plan FROM plan_categoria_detail WHERE id_plan_categoria = '$id_plan_categoria' AND  language_id='$leng'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function get_plans_impuesto($id_plan, $country=''){
                                                                $query="SELECT
                                                                        id_tax,
                                                                        id_plan,
                                                                        iso_countrie,
                                                                        tax1,
                                                                        tax2,
                                                                        description1,
                                                                        description2,
                                                                        show1,
                                                                        show2,
                                                                        status1,
                                                                        status2
                                                                    FROM
                                                                        `plan_tax`
                                                                    WHERE 1 ";
                                                                if(!empty($id_plan)){
                                                                    $query.=" AND id_plan = '$id_plan' ";
                                                                }
                                                                if(!empty($country)){
                                                                    $query.=" AND iso_countrie = '$country' ";
                                                                }
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_Broker_Nivel($parent = '', $idbroker = '') {
                                                            $query = "SELECT * FROM broker_nivel WHERE 1";
                                                            if (!empty($parent))
                                                                $query.=" AND parent = '$parent'";
                                                            if (!empty($idbroker))
                                                                $query.=" AND id_broker = '$idbroker'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_Broker_Comision($id, $language_id, $idCategory) {
                                                            $id1 = (!empty($id))? $id : 0;
                                                            $query = "SELECT
                                                                        plan_category.id_plan_categoria,
                                                                        plan_categoria_detail.name_plan,
                                                                        plan_category.valor_menor,
                                                                        plan_category.created,
                                                                        plan_category.modified,
                                                                        plan_category.id_status,
                                                                        plan_category.vision_id,
                                                                        plan_category.img,
                                                                        plan_category.orden,
                                                                        IFNULL(
                                                                            (
                                                                                SELECT
                                                                                    porcentaje
                                                                                FROM
                                                                                    commissions
                                                                                WHERE
                                                                                    plan_category.id_plan_categoria = id_categoria
                                                                                AND id_agencia = '$id1'
                                                                            ),
                                                                            0
                                                                        ) porcentaje_categoria
                                                                    FROM
                                                                        plan_category
                                                                    INNER JOIN plan_categoria_detail ON plan_categoria_detail.id_plan_categoria = plan_category.id_plan_categoria
                                                                    WHERE
                                                                        id_status='1' ";
                                                            if(!empty($idCategory)){
                                                                $query.=" AND plan_category.id_plan_categoria = '$idCategory' ";
                                                            }
                                                            if(!empty($language_id)){
                                                                $query.=" AND plan_categoria_detail.language_id = '$language_id' ";
                                                            }
                                                            $query.=" ORDER BY plan_category.name_plan";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function get_plan_act_impuesto($id_plan){
		                                                    $query = "SELECT id, `name`, impuesto FROM plans WHERE id = '$id_plan' ";
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function add_order_tax($id_order, $id_tax, $iso_countrie, $tax1, $tax2, $tax1_value, $tax2_value, $tax1_value_mlc, $tax2_value_mlc){
                                                            $tax1 = $tax1 ?: 0;
                                                            $tax2 = $tax2 ?: 0;
                                                            $tax1_value = $tax1_value ?: 0;
                                                            $tax2_value = $tax2_value ?: 0;
                                                            $tax1_value_mlc = $tax1_value_mlc ?: 0;
                                                            $tax2_value_mlc = $tax2_value_mlc ?: 0;
                                                            $query = "INSERT INTO order_tax (
                                                                    id_order,
                                                                    id_tax,
                                                                    iso_countrie,
                                                                    tax1,
                                                                    tax2,
                                                                    tax1_value,
                                                                    tax2_value,
                                                                    tax1_value_mlc,
                                                                    tax2_value_mlc
                                                                )VALUES(
                                                                    '$id_order',
                                                                    '$id_tax',
                                                                    '$iso_countrie',
                                                                    '$tax1',
                                                                    '$tax2',
                                                                    '$tax1_value',
                                                                    '$tax2_value',
                                                                    '$tax1_value_mlc',
                                                                    '$tax2_value_mlc'
                                                                ) ";
		return $this->_SQL_tool($this->INSERT, __METHOD__, $query, "Registro de Impuesto de orden ID: '$id_orden' ");
	}
}