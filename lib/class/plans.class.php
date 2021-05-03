<?php

class Plans extends Model {
    var $table = 'plans';
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
	function Get_Plans_especial($id_plan_categoria = '') {
        $query = "SELECT * FROM plans WHERE 1";
        if (!empty($id_plan_categoria))
            $query.=" AND id_plan_categoria = '$id_plan_categoria'";
        //var_dump ($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function GetPlanCategory($id_plan_categoria = '',$lenguaje = '') {
		if (!empty($lenguaje)) {
            $query.= "SELECT plan_categoria_detail.name_plan FROM plan_category 
                    INNER JOIN plan_categoria_detail ON plan_category.id_plan_categoria = plan_categoria_detail.id_plan_categoria
                    WHERE language_id = '$lenguaje'";
        }else{
        $query = "SELECT * FROM plan_category WHERE 1";
		}
        if (!empty($id_plan_categoria))
            $query.=" AND plan_category.id_plan_categoria = '$id_plan_categoria'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
   function Get_All_Plan($search = '', $order = '', $min = '', $max = '', $id_plan_categoria = '', $max_tiempo = '', $tipo_plan='', $language_id='', $id_broker='', $id_agencia = '', $id_plan='', $restriccion='') {
        $query = 'SELECT
                    plans.id,
                    plans.`name`,
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
                    plans.family_plan,
                    plans.id_provider,
                    plans.min_age,
                    plans.max_age,
                    plans.normal_age,
                    plans.overage_factor,
                    plans.overage_factor_cost,
                    plans.eliminado,
                    plans.tipo_pago,
                    plans.provider_id_plan,
                    plans.id_benefits_curr,
                    plans.factor_family,
                    plans.factor_family_cost,
                    plans.plan_local,
                    plans.type_tlf,
					plans.modo_plan,
					plans.plan_pareja,
					plans.factor_pareja,
					plans.factor_pareja_cost,
                    plan_detail.titulo,
                    plan_detail.language_id
                    FROM
                    plan_detail
                    INNER JOIN plans ON plans.id = plan_detail.plan_id 
                    LEFT JOIN restriction ON plans.id = restriction.id_plans
                    INNER JOIN plan_category ON plans.id_plan_categoria = plan_category.id_plan_categoria
                    WHERE 1 ';
        if (!empty($search))
            $query.=" AND plan_detail.titulo like'%$search%'";
         if (!empty($id_broker))
            $query.=" AND ((restriction.id_broker in ($id_broker) and restriction.dirigido='6') or (restriction.dirigido='2' and restriction.id_broker = '$id_agencia'))";
        if (!empty($language_id))
            $query.=" AND plan_detail.language_id='$language_id'";
        if (!empty($id_plan))
            $query.=" AND plans.id='$id_plan' ";
        if (!empty($id_plan_categoria))
            $query.=" AND plans.id_plan_categoria='$id_plan_categoria'";
        if (!empty($tipo_plan)){
            if($tipo_plan=='T'){
                $query.=" AND plans.id!=''";
            }
            if($tipo_plan=='F'){
                $query.=" AND plans.family_plan='Y'";
            }
            if($tipo_plan=='L'){
                $query.=" AND plans.plan_local='Y'";
            }
            if($tipo_plan=='1'){
                $query.=" AND plans.activo='1'";
            }
            if($tipo_plan=='2'){
                $query.=" AND plans.activo='2'";
            }
        }
		if(!empty($restriccion))
            $query.=" AND (restriction.dirigido ='0' OR restriction.dirigido is null OR restriction.dirigido <> '')";
        if (!empty($max_tiempo))
            $query.=" AND plans.max_tiempo='$max_tiempo'";
        $query.=" AND plans.eliminado = '1'";
        $query.="AND (modo_plan = 'N' OR modo_plan = 'W' OR modo_plan = 'T' OR modo_plan is NULL)";
        if (!empty($order)) {
            $query .= " ORDER BY $order ";
        } else{
            $query .= " ORDER BY plan_category.orden ASC, plans.orden ASC ";
        }
        if (!empty($max)) {
            $query .= " LIMIT $min,$max ";
        }
        return $this->_SQL_tool($this->SELECT, METHOD, $query);
    }
 function Get_Orden_All_Plan($search = '', $order = '', $min = '', $max = '', $id_plan_categoria = '', $max_tiempo = '', $tipo_plan='', $language_id='') {
        $query = 'SELECT
                    plans.id,
                    plans.`name`,
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
                    plans.family_plan,
                    plans.id_provider,
                    plans.min_age,
                    plans.max_age,
                    plans.normal_age,
                    plans.overage_factor,
                    plans.overage_factor_cost,
                    plans.eliminado,
                    plans.provider_id_plan,
                    plans.id_benefits_curr,
                    plans.factor_family,
                    plans.factor_family_cost,
                    plans.plan_local,
                    plans.type_tlf,
                    plan_detail.titulo,
                    plan_detail.language_id
                    FROM
                    plan_detail
                    INNER JOIN plans ON plans.id = plan_detail.plan_id WHERE 1 ';
        if (!empty($search))
            $query.=" AND plan_detail.titulo like'%$search%'";
        if (!empty($language_id))
            $query.=" AND plan_detail.language_id='$language_id'";
        if (!empty($id_plan_categoria))
            $query.=" AND plans.id_plan_categoria='$id_plan_categoria'";
        if (!empty($tipo_plan)){
            if($tipo_plan=='T'){
                $query.=" AND plans.id!=''";
            }
            if($tipo_plan=='F'){
                $query.=" AND plans.family_plan='Y'";
            }
            if($tipo_plan=='L'){
                $query.=" AND plans.plan_local='Y'";
            }
            if($tipo_plan=='1'){
                $query.=" AND plans.activo='1'";
            }
            if($tipo_plan=='2'){
                $query.=" AND plans.activo='2'";
            }
        }
        if (!empty($max_tiempo))
            $query.=" AND plans.max_tiempo='$max_tiempo'";
        $query.=" AND plans.eliminado = '1'";
        if (!empty($order)) {
            $query .= " ORDER BY $order ";
        }else{
            $query.=" ORDER BY plans.orden ASC";
        }
        if (!empty($max)) {
            $query .= " LIMIT $min,$max ";
        }
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_All_Relation_Restric($id_restic = '', $iso_country = '', $type_country) {
        $query = "SELECT * FROM relaciotn_restriction WHERE 1 ";
        if (!empty($id_restic))
            $query.=" AND id_restric='$id_restic'";
        if (!empty($iso_country))
            $query.=" AND iso_country='$iso_country'";
        if (!empty($type_country))
            $query.=" AND type_country='$type_country'";
        //var_dump($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_All_Relation_Restric2($id_restic = '', $iso_country = '', $type_country) {
        $query = "SELECT * FROM relaciotn_restriction WHERE id_restric='$id_restic' AND iso_country='$iso_country' AND type_country='$type_country'";
        //var_dump($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	function getLastOrderedPlan($categoria){
         $query="SELECT
                    MAX(plans.orden) AS orden,
                plan_category.id_plan_categoria
                FROM
                    plans
                INNER JOIN plan_category ON plans.id_plan_categoria = plan_category.id_plan_categoria
                WHERE
                    1
                AND plan_category.id_status = 1";
                if (!empty($categoria)){
                    $query.=" AND plan_category.id_plan_categoria = '$categoria'";
                }
                $query.=" AND plans.activo = 1 AND plans.eliminado = 1 
                GROUP BY
                    plan_category.id_plan_categoria DESC";
          return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
        }
    function Add_Plan($Titulo, $id_status, $descripcion, $remark, $Plan_categoria, $unidad, $min_tiempo, $Currence, $max_tiempo, $both, $comission, $id_provider, $max_age,$min_age, $normal_age, $overage_factor, $provider_id_plan, $family_plan, $family_plan_cantidad, $factor_familiar, $factor_family_cost, $overage_factor_cost, $plan_local, $type_plan, $type_tlf, $price_voucher, $send_mail, $num_pas, $dir_habitacion, $percent_benefit,$plan_pareja,$factor_pareja,$factor_pareja_cost, $plan_renewal, $tiene_impuesto, $impuesto1, $mostrar1, $impuesto2, $mostrar2, $plan_menores, $edad_menores, $tipo_cost, $modo_plan, $prima_renewal, $texto_footer,$dias_multiviajes,$LocalCurrency) {
		$id_status = $id_status?:0; 
		$Plan_categoria = $Plan_categoria?:0; 
		$min_tiempo = $min_tiempo?:0; 
		$max_tiempo = $max_tiempo?:0; 
		$Currence = $Currence?:0; 
		$remark = $remark?:0; 
		$id_provider = $id_provider?:0; 
		$max_age = $max_age?:0; 
		$min_age = $min_age?:0; 
		$normal_age = $normal_age?:0; 
		$overage_factor_cost = $overage_factor_cost?:0; 
		$overage_factor = $overage_factor?:0; 
		$overage_factor_cost = $overage_factor_cost?:0; 
		$factor_familiar = $factor_familiar?:0;
		$factor_pareja = $factor_pareja?:0;
		$factor_pareja_cost = $factor_pareja_cost?:0;
		$plan_familiar_cantidad = $family_plan_cantidad?:0;
		$type_tlf = $type_tlf?:0; 
		$type_plan = $type_plan?:0;
		$num_pas = $num_pas?:0; 
		$dir_habitacion = $dir_habitacion?:0;
		$plan_renewal = $plan_renewal?:0;
		$tiene_impuesto = $tiene_impuesto?:0;
		$impuesto1= $impuesto1?:0; 
		$mostrar1= $mostrar1?:0;
		$impuesto2= $impuesto2?:0;
		$mostrar2 = $mostrar2?:0;
		$edad_menores = $edad_menores?:0;
		$prima_renewal = $prima_renewal?:0;
        $dias_multiviajes = $dias_multiviajes?:0;
		$LocalCurrency = $LocalCurrency?:0;
		$ultimo_orden=$this->getLastOrderedPlan($Plan_categoria);
		$ultimo=$ultimo_orden[0]['orden']+1; 
        $query = "INSERT INTO plans (
                NAME,
                description,
                activo,
                id_plan_categoria,
                unidad,
                min_tiempo,
                max_tiempo,
                id_currence,
                remark,
                commissions,
                botht,
                id_provider,
                max_age,
                min_age,
                normal_age,
                overage_factor,
                provider_id_plan,
                family_plan,
                factor_family,
                overage_factor_cost,
                factor_family_cost,
                plan_local,
                type_plan,
                type_tlf,
                price_voucher,
                send_mail,
                num_pas,
                dir_habitacion,
                percent_benefit,
                plan_pareja,
                factor_pareja,
                factor_pareja_cost,
                orden,
                family_plan_cantidad,
                plan_renewal,
                impuesto,
                impuesto1,
                mostrar1,
                impuesto2,
                mostrar2,
                plan_menores,
                edad_menores,
                tipo_cost,
                modo_plan,
                prima_renewal,
                texto_footer,
                dias_multiviajes,
                tipo_pago
            )
            VALUES
                (
                    '$Titulo',
                    '$descripcion',
                    '$id_status',
                    '$Plan_categoria',
                    '$unidad',
                    '$min_tiempo',
                    '$max_tiempo',
                    '$Currence',
                    '$remark',
                    '$comission',
                    '$both',
                    '$id_provider',
                    '$max_age',
                    '$min_age',
                    '$normal_age',
                    '$overage_factor',
                    '$provider_id_plan',
                    '$family_plan',
                    '$factor_familiar',
                    '$overage_factor_cost',
                    '$factor_family_cost',
                    '$plan_local',
                    '$type_plan',
                    '$type_tlf',
                    '$price_voucher',
                    '$send_mail',
                    '$num_pas',
                    '$dir_habitacion',
                    '$percent_benefit',
                    '$plan_pareja',
                    '$factor_pareja',
                    '$factor_pareja_cost',
                    '$ultimo',
                    '$plan_familiar_cantidad',
                    '$plan_renewal',
                    '$tiene_impuesto',
                    '$impuesto1',
                    '$mostrar1',
                    '$impuesto2',
                    '$mostrar2',
                    '$plan_menores',
                    '$edad_menores',
                    '$tipo_cost',
                    '$modo_plan',
                    '$prima_renewal',
                    '$texto_footer',
                    '$dias_multiviajes',
                    '$LocalCurrency'
                )"; 
        $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert Plan Category <-> name:' . $Titulo);
        $this->id = $result;
        /**-----------------------SOAP----------------------*/
            $obj_soapclient = new soapcliente();
            $obj_soapclient->add_soap('plans','id');
        /**-----------------------FIN-SOAP-------------------*/
    }
	function Add_Plan_clone($campos,$valores_con, $title_new)
	{
		$query="INSERT INTO plans ($campos) VALUES($valores_con)";
		$result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert Plan clone  <-> Id Plans:' . $title_new);
		$this->id = $result;
        /**-----------------------SOAP----------------------*/
            $obj_soapclient = new soapcliente();
            $obj_soapclient->add_soap('plans','id');
        /**-----------------------FIN-SOAP-------------------*/
	}
    function TelefonoPlans($id_plans, $pais, $telefono) {
		$id_plans = $id_plans?$id_plans:0; 
        $query = "INSERT INTO phone_plans (plans_id, pais, telefono) VALUES ('$id_plans', '$pais', '$telefono')";
        $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert Plan Phone <-> Id Plans:' . $id_plans);
    }
    function Add_Restric($dirigido, $id_territory, $id_territory_destino, $id_broker, $id_broker1, $id_broker2, $id_broker3, $id_broker4, $id_broker5, $id, $id_client) {
		$dirigido = $dirigido?:0; 
		$id_territory = $id_territory?:0; 
		$id_territory_destino = $id_territory_destino?:0; 
		$id_broker = $id_broker?:0; 
		$id_broker1 = $id_broker1?:0; 
		$id_broker2 = $id_broker2?:0;	
		$id_broker1 = $id_broker1?:0; 
		$id_broker3 = $id_broker3?:0;
		$id_broker4 = $id_broker4?:0; 
		$id_broker5 = $id_broker5?:0;
		$id = $id?:0; 
		$id_client = $id_client?:0;
        $query = "INSERT INTO restriction (dirigido, id_territory_origen, id_territory_destino, id_broker, id_broker1, id_broker2, id_broker3, id_broker4, id_broker5, id_plans, id_client, created) VALUES ('$dirigido', '$id_territory', '$id_territory_destino', '$id_broker', '$id_broker1', '$id_broker2', '$id_broker3', '$id_broker4', '$id_broker5', '$id','$id_client', NOW())";
        $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert restriction <-> Plans id :' . $id);
        $this->id_restric = $result;
    }
    function Add_Relacion_Restric($iso_country, $id, $type_country) {
		$id = $id?:0;
		$type_country = $type_country?:0;
        $query = "INSERT INTO relaciotn_restriction (iso_country, id_restric, type_country) VALUES ('$iso_country', '$id', '$type_country')";
        $this->_SQL_tool($this->INSERT, __METHOD__, $query);
    }
    function Update_Restric($dirigido = '0', $id_territory = '0', $id_territory_destino = '0', $id_broker, $id_broker1 = '0', $id_broker2 = '0', $id_broker3 = '0', $id_broker4 = '0', $id_broker5 = '0', $id, $id_client = '0') {
		$dirigido = $dirigido?:0; 
		$id_territory = $id_territory?:0; 
		$id_territory_destino = $id_territory_destino?:0; 
		$id_broker = $id_broker?:0; 
		$id_broker1 = $id_broker1?:0; 
		$id_broker2 = $id_broker2?:0;	
		$id_broker1 = $id_broker1?:0; 
		$id_broker3 = $id_broker3?:0;
		$id_broker4 = $id_broker4?:0; 
		$id_broker5 = $id_broker5?:0;
		$id = $id?:0; 
		$id_client = $id_client?:0;
        $sQuery = "UPDATE restriction SET dirigido='$dirigido',id_territory_origen='$id_territory',id_territory_destino='$id_territory_destino', modified=NOW()";
        $sQuery.=" ,id_broker='$id_broker'";
        if (!empty($id_broker1))
            $sQuery.=" ,id_broker1='$id_broker1'";
        if (!empty($id_broker2))
            $sQuery.=" ,id_broker2='$id_broker2'";
        if (!empty($id_broker3))
            $sQuery.=" ,id_broker3='$id_broker3'";
        if (!empty($id_broker4))
            $sQuery.=" ,id_broker4='$id_broker4'";
        if (!empty($id_broker5))
            $sQuery.=" ,id_broker5='$id_broker5'";
        if (!empty($id_client))
            $sQuery.=" ,id_client='$id_client'";
        $sQuery .= " WHERE id_plans='$id'";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery);
    }
    function Get_Plan_Message($id_plan = '', $language_id = '', $IN_idPlan = '') {
        $query = "SELECT
                    plans.id,
                    plans.`name`,
                    plan_detail.language_id,
                    plan_detail.message_plan,
                    plan_detail.titulo,
                    plan_detail.description,
                    plans.id_plan_categoria
                    FROM plans
                    Inner Join plan_detail ON plans.id = plan_detail.plan_id
                    WHERE 1";
        if (!empty($id_plan))
            $query.=" AND plan_detail.plan_id= '$id_plan'";
        if (!empty($language_id))
            $query.=" AND language_id= '$language_id'";
        if (!empty($IN_idPlan))
            $query.=" AND plan_detail.plan_id IN ($IN_idPlan)";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	function Get_Plan_Message_message_plan($id_plan = '', $language_id = '', $IN_idPlan = '') {
        $query = "SELECT
                    plan_detail.message_plan
                    FROM plans
                    Inner Join plan_detail ON plans.id = plan_detail.plan_id
                    WHERE 1";
        if (!empty($id_plan))
            $query.=" AND plan_detail.plan_id= '$id_plan'";
        if (!empty($language_id))
            $query.=" AND language_id= '$language_id'";
        if (!empty($IN_idPlan))
            $query.=" AND plan_detail.plan_id IN ($IN_idPlan)";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_Plan_Message_Tlf($id_plan = '', $language_id = '') {
        $query = "SELECT * FROM phone_plans_text WHERE 1 ";
        if (!empty($id_plan))
            $query.=" AND plan_id= '$id_plan'";
        if (!empty($language_id))
            $query.=" AND language_id= '$language_id'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Delete_Relacion_Restric($id = '') {
        $sQuery = "DELETE from relaciotn_restriction WHERE id_restric='$id'";
        $this->_SQL_tool($this->DELETE, __METHOD__, $sQuery);
    }
    function Get_All_Restric($id_plans = '') {
        $query = "SELECT * FROM restriction WHERE 1 ";
        if (!empty($id_plans))
            $query.=" AND id_plans= '$id_plans'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_All_Raider_Plan($id_plans = '') {
        $query = "SELECT raiders.name_raider, raiders.id_benefi, raiders.type_raider, raiders.value_raider, benefit.`name`, plan_raider.id_plan FROM
plans Inner Join plan_raider ON plans.id = plan_raider.id_plan Inner Join raiders ON plan_raider.id_raider = raiders.id_raider Inner Join benefit ON raiders.id_benefi = benefit.id  WHERE 1";
        if (!empty($id_plans))
            $query.=" AND plan_raider.id_plan= '$id_plans'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Update_Plan($id,$Titulo, $id_status, $descripcion, $remark, $Plan_categoria, $unidad, $min_tiempo, $Currence, $max_tiempo, $both, $comission, $id_provider, $max_age, $min_age, $normal_age, $overage_factor, $provider_id_plan, $family_plan, $family_plan_cantidad, $factor_family, $factor_family_cost, $overage_factor_cost, $plan_local, $type_tlf, $type_plan, $price_voucher, $send_mail, $num_pas, $dir_habitacion, $percent_benefit,$plan_pareja,$factor_pareja='',$factor_pareja_cost='',$plan_renewal, $tiene_impuesto, $plan_menores, $edad_menores, $tipo_cost, $modo_plan, $prima_renewal, $texto_footer,$dias_multiviajes,$LocalCurrency) {  
        $factor_pareja = $factor_pareja?:0;
        $factor_pareja_cost = $factor_pareja_cost?:0;
        $sQuery = "UPDATE plans SET ";
        $sQuery.= "id = $id";
        if (!empty($Titulo))
            $sQuery.= ", name = '$Titulo'";
        if (!empty($id_status))
            $sQuery.= ", activo = '$id_status'";
        if (!empty($descripcion))
            $sQuery.= ", description = '$descripcion'";
        if (!empty($remark))
            $sQuery.= ", remark = '$remark'";
        if (!empty($Plan_categoria))
            $sQuery.= ", id_plan_categoria = '$Plan_categoria'";
        if (!empty($unidad))
            $sQuery.= ", unidad = '$unidad'";
        if (!empty($min_tiempo))
            $sQuery.= ", min_tiempo = '$min_tiempo'";
        if (!empty($Currence))
            $sQuery.= ", id_currence = '$Currence'";
        if (!empty($max_tiempo))
            $sQuery.= ", max_tiempo = '$max_tiempo'";
        if (!empty($both))
            $sQuery.= ", botht = '$both'";
        if (!empty($comission))
            $sQuery.= ", commissions = '$comission'";
        if (!empty($id_provider))
            $sQuery.= ", id_provider = '$id_provider'";
        if (!empty($max_age))
            $sQuery.= ", max_age = '$max_age'";
        if (!empty($min_age) OR $min_age=="0")
            $sQuery.= ", min_age = '$min_age'";
        if (!empty($normal_age))
            $sQuery.= ", normal_age = '$normal_age'";
        if (!empty($overage_factor))
            $sQuery.= ", overage_factor = '$overage_factor'";
        if (!empty($overage_factor_cost))
            $sQuery.= ", overage_factor_cost = '$overage_factor_cost'";
        if (!empty($plan_local))
            $sQuery.= ", plan_local = '$plan_local'";
        if (!empty($provider_id_plan))
            $sQuery.= ", provider_id_plan = '$provider_id_plan'";
        if (!empty($family_plan))
            $sQuery.= ", family_plan = '$family_plan'";
        if (!empty($type_tlf))
            $sQuery.= ", type_tlf = '$type_tlf'";
		if (!empty($type_plan))
            $sQuery.= ", type_plan = '$type_plan'";
        if (!empty($price_voucher))
            $sQuery.= ", price_voucher = '$price_voucher'";
        if (!empty($send_mail))
            $sQuery.= ", send_mail = '$send_mail'";
        if (!empty($num_pas))
            $sQuery.= ", num_pas = '$num_pas'";
        if (!empty($dir_habitacion))
            $sQuery.= ", dir_habitacion = '$dir_habitacion'";
       if (!empty($percent_benefit))
            $sQuery.= ", percent_benefit = '$percent_benefit'";
	 	if (!empty($plan_pareja))
            $sQuery.= ", plan_pareja = '$plan_pareja'";
        if (!empty($family_plan_cantidad))
            $sQuery.= ", family_plan_cantidad = '$family_plan_cantidad'";   
		 if (!empty($plan_renewal))
            $sQuery.= ", plan_renewal = '$plan_renewal'";
		 if (!empty($tiene_impuesto))
            $sQuery.= ", impuesto = '$tiene_impuesto'";	
		 if (!empty($factor_pareja))
            $sQuery.= ", factor_pareja = '$factor_pareja'"; 
	     if (!empty($factor_pareja_cost))
            $sQuery.= ", factor_pareja_cost = '$factor_pareja_cost' ";
         if (!empty($dias_multiviajes))
            $sQuery.= ", dias_multiviajes = '$dias_multiviajes'";
         if (!empty($LocalCurrency))
            $sQuery.= ", tipo_pago = '$LocalCurrency'";
        $sQuery.= ", factor_family = '$factor_family', factor_family_cost = '$factor_family_cost', plan_menores = '$plan_menores', edad_menores = '$edad_menores', tipo_cost = '$tipo_cost', modo_plan = '$modo_plan', prima_renewal= '$prima_renewal', texto_footer = '$texto_footer' ";
        $sQuery.=" WHERE id = $id";
        $comentario = 'Updated |  Plan: ' . $name_plan . ', ID id: ' . $id;
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery, $comentario);
        /**-----------------------SOAP----------------------*/
            $obj_soapclient = new soapcliente();
            $obj_soapclient->update_soap('plans','id', $id, '');
         /**-----------------------FIN-SOAP-------------------*/
    }
    function Delete_Plan($id) {
        $sQuery = "UPDATE plans SET eliminado='2' WHERE id = '$id'";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery);
        /**-----------------------SOAP----------------------*/
            $obj_soapclient = new soapcliente();
            $obj_soapclient->update_soap('plans','id', $id, '');
        /**-----------------------FIN-SOAP-------------------*/
    }
function getLastOrdenBenefit($id_plan){
         $query="SELECT
                    MAX(benefit_plan.orden) AS orden
                FROM
                    benefit_plan
                WHERE
                    1";
                if (!empty($id_plan)){
                    $query.=" AND id_plan = '$id_plan'";
                }
          return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
        }
//BENEFICION DEL PLAN
    function Add_Plan_Benefits($benefit = '', $Valor_spa = '', $Valor_eng = '', $Valor_por = '', $Valor_deu = '', $Valor_fra = '',$id, $orden, $eliminado) {
        $id = $id?$id:0;
        $benefit = $benefit?$benefit:0;
        $porcentaje = $porcentaje?$porcentaje:0;
        $eliminado = $eliminado?$eliminado:0;
        if (empty($orden)) {
            $ordenbeneficio=$this->getLastOrdenBenefit($id);
            $orden=$ordenbeneficio[0]['orden']+1; 
        } 
        $query = "INSERT INTO benefit_plan (id_plan, id_beneficio, valor_spa, valor_eng, valor_por, valor_deu, valor_fra, orden, porcentaje, eliminado) VALUES ('$id', '$benefit', '$Valor_spa', '$Valor_eng', '$Valor_por', '$Valor_deu', '$Valor_fra','$orden', '$porcentaje', '$eliminado')";
        $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert plan benefit <-> id:' . $benefit);
        $this->id = $result;
		 /**-----------------------SOAP----------------------*/
            $obj_soapclient = new soapcliente();
            $obj_soapclient->add_soap('benefit_plan','id');
         /**-----------------------FIN-SOAP-------------------*/
    }
   function Update_Plan_Benefits($id_benefit_plan = '', $benefit = '', $Valor_spa = '', $Valor_eng = '', $Valor_por = '', $Valor_deu = '', $Valor_fra = '', $id) {
        $sQuery = "UPDATE benefit_plan SET ";
        if (!empty($id))
            $sQuery.= " id_plan = '$id'";
        if (!empty($benefit))
            $sQuery.= ", id_beneficio = '$benefit'";
        if (!empty($Valor_spa))
            $sQuery.= ", valor_spa = '$Valor_spa'";
        if (!empty($Valor_eng))
            $sQuery.= ", valor_eng = '$Valor_eng'";
        if (!empty($Valor_por))
            $sQuery.= ", valor_por = '$Valor_por'";
        if (!empty($Valor_deu))
            $sQuery.= ", valor_deu = '$Valor_deu'";
        if (!empty($Valor_fra))
            $sQuery.= ", valor_fra = '$Valor_fra'";
        $sQuery.=" WHERE id = $id_benefit_plan";
        $comentario = 'Updated |  benefit_plan: ' . $Valor_spa . "-" . $Valor_eng . ', ID id: ' . $id;
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery, $comentario);
		 /**-----------------------SOAP----------------------*/
        $obj_soapclient = new soapcliente();
        $obj_soapclient->update_soap('benefit_plan','id', $id_benefit_plan, '');
        /**-----------------------FIN-SOAP-------------------*/
    }
    function Get_Plans_tiempo($id_plan, $renewal) { //funcion para obtener las primas y mostrarlas en el cluetip
       $query = "SELECT * FROM plan_times WHERE id_plan = '$id_plan'";
       if (!empty($renewal))
        $query.=" AND renewal='$renewal'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	function Get_Plans_impuesto_by_country($id_impuesto, $id_plan, $country) {
        $query = "SELECT * FROM impuesto WHERE 1";
        if (!empty($id_impuesto))
            $query.= " AND impuesto.id = '$id_impuesto'";
			if (!empty($id_plan))
            $query.= " AND impuesto.id_plan = '$id_plan'";
		if (!empty($country))
            $query.= " AND impuesto.id_country = '$country'";
        $query.= " ORDER BY impuesto.id_country ASC";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
   function Get_Plans_premium($id_plan, $renewal) {
        $query = "SELECT * FROM plan_times WHERE id_plan = '$id_plan'";
		if(!empty($renewal)){
			if($renewal < 2){
				$new_renewal = '2';
				$query.= " AND (renewal IS NULL or renewal <> '$new_renewal')";
			}
			if($renewal==2){
				$query.= " AND renewal='2'"; 
			}
		}
		$query.= " ORDER BY adicional ASC, tiempo ASC ";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
 function Get_Plans_band_age($id_plan) {   //funcion para obtener las primas y mostrarlas en el cluetip
        $query = "SELECT * FROM plan_band_age WHERE id_plan = '$id_plan' AND renewal='1'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    #BUSCO LOS TELEFONOS DEL PLAN
    function GetPhonePlans($id_plan) {
        $query = "SELECT * FROM phone_plans WHERE plans_id = '$id_plan'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
   function Get_Plans_tiempo_by_country($id_plan, $coutry) {
        $query = "SELECT
				plan_times.*,
				countries.description
			FROM
				plan_times
			LEFT JOIN countries ON plan_times.id_country = countries.iso_country
			WHERE id_plan = '$id_plan'";
        if (!empty($coutry)){
            $query.= " AND plan_times.id_country =  '$coutry' ";
		}
        $query.= " ORDER BY
			countries.description ASC,
			plan_times.id_country ASC,
			plan_times.adicional ASC,
			plan_times.tiempo ASC ";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	function Get_Plans_tiempo_by_country_only($id_plan, $coutry, $orden) {
        $query = "SELECT * FROM plan_times WHERE id_plan = '$id_plan'";
       if (!empty($coutry)){
            $query.= " AND plan_times.id_country <> '$coutry'";
	   }
	   $query.= " AND (renewal <> '2' OR renewal IS NULL) ";
        $query.= " ORDER BY plan_times.id_country ASC, plan_times.tiempo ASC";        
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	function Get_Plans_bandage_by_country_only($id_plan, $coutry, $orden) {
        $query = "SELECT * FROM plan_band_age WHERE id_plan = '$id_plan'";
       if (!empty($coutry)){
            $query.= " AND plan_band_age.id_country <> '$coutry'";
	   }
	   $query.= " AND renewal='1' ";
        $query.= " ORDER BY plan_band_age.id_country ASC";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_Plans_Restriccion($id_plan) {
        $query = "SELECT * FROM restriction WHERE id_plans = '$id_plan'";
//                die($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_Plans_Relacion_restricion($id_restric) {
        $query = "SELECT * FROM relaciotn_restriction WHERE id_restric = '$id_restric'";
        //die($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
/*    function Get_Plans_categoria($id_plan_categoria = '', $max_tiempo = 0, $origen="", $id_plan="", $id_multiple_plans="") {
        $query = 'SELECT  plans.id, plans.name, plans.description, plans.activo,
                plans.id_plan_categoria,plans.unidad, plans.min_tiempo,
                plans.max_tiempo,plans.imagen, plans.deducible, plans.id_currence,
                plans.remark, plans.commissions,plans.botht, plans.normal_age,
                plans.max_age, plans.min_age,plans.overage_factor,plans.overage_factor_cost, plans.family_plan, plans.factor_family, 
                plans.factor_family_cost, plans.plan_local, plans.num_pas, plans.plan_pareja, plans.factor_pareja, 
                plans.factor_pareja_cost, plans.plan_renewal, plans.price_voucher,
                restriction.id_restric, restriction.dirigido, restriction.id_territory_origen, restriction.id_territory_destino,
                restriction.id_broker, restriction.id_broker1, restriction.id_broker2,
                restriction.id_broker3, restriction.id_broker4, restriction.id_broker5,
                restriction.id_plans, restriction.id_client, restriction.created, restriction.modified, plan_detail.titulo,plan_detail.language_id, currency.value_iso
                FROM plans 
                Left Join restriction ON plans.id = restriction.id_plans
                Left Join currency ON plans.id_currence = currency.id_currency
                INNER JOIN plan_detail ON plans.id = plan_detail.plan_id
                WHERE plans.activo =  "1" AND eliminado = "1" AND plan_detail.language_id= "' . $_SESSION['lng_id'] . '"';
        $query.=" ";
        if (!empty($id_plan_categoria))
            $query.= " AND plans.id_plan_categoria =  '$id_plan_categoria'";
        if (!empty($origen)){
            $query.= " AND plans.plan_local =  '$origen'";
        }
		if (!empty($id_plan)){
            $query.= " and plans.id = '$id_plan'";
        }
        if (!empty($id_multiple_plans)){
            $query.= " and plans.id IN ($id_multiple_plans)";
        }
      // $query.= " ORDER BY plans.id";
	   $query.=" ORDER BY plans.orden ASC";
     //~ die($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
//return $this->select_tool($sQuery);
    }*/
	 function Get_Plans_categoria($id_plan_categoria = '', $max_tiempo = 0, $origen="", $id_plan="", $id_multiple_plans="", $id_broker = '', $opcion_plan = '', $dias_multiviajes) {
        $query = "SELECT
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
                plans.plan_local,
                plans.num_pas,
                plans.plan_pareja,
                plans.factor_pareja,
                plans.factor_pareja_cost,
                plans.plan_renewal,
                plans.price_voucher,
				plans.family_plan_cantidad,
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
                broker.opcion_plan,
                plans.impuesto,
                plans.tipo_pago
                FROM
                plans
                LEFT JOIN restriction ON plans.id = restriction.id_plans
                LEFT JOIN broker ON broker.id_broker = restriction.id_broker
                LEFT JOIN currency ON plans.id_currence = currency.id_currency
                INNER JOIN plan_detail ON plans.id = plan_detail.plan_id
                WHERE
                plans.activo = '1'
                AND plans.eliminado = '1'";
            if(!empty($id_broker)){
                $query.=" AND restriction.id_broker = CASE
                WHEN (
                SELECT
                count(restriction.id_broker)
                FROM
                plans
                INNER JOIN restriction ON plans.id = restriction.id_plans
                WHERE
                restriction.id_broker = '$id_broker'
                ) > 0 THEN
                '$id_broker'
                ELSE
                restriction.id_broker
                AND (
                broker.opcion_plan = '1'
                OR broker.opcion_plan IS NULL
                )
                END ";
            }
                $query.=" AND plan_detail.language_id = '".$_SESSION["lng_id"]."' ";
        if (!empty($id_plan_categoria))
            $query.= " AND plans.id_plan_categoria =  '$id_plan_categoria'";
        if (!empty($origen)){
            $query.= " AND plans.plan_local =  '$origen'";
        }
        if (!empty($id_plan)){
            $query.= " and plans.id = '$id_plan'";
        }
        if (!empty($id_multiple_plans)){
            $query.= " and plans.id IN ($id_multiple_plans)";
        }
        if (!empty($dias_multiviajes)) {
            $query.= " and plans.dias_multiviajes = '$dias_multiviajes'";
        }
        $query .=" AND (modo_plan != 'W' OR modo_plan = 'N' OR modo_plan = 'T' OR modo_plan is NULL)";
      // $query.= " ORDER BY plans.id";
       $query.=" ORDER BY plans.orden ASC";
    //echo $query;
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
//return $this->select_tool($sQuery);
    }
    function benefit_plans($id_plan_categoria='', $id_session =''){
        $query="SELECT
                    plans.id,
                    plan_detail.description,
                    plan_detail.language_id,
                    plans.id_plan_categoria,
                    plans.eliminado,
                    plans.activo,
                    plan_categoria_detail.name_plan,
                    plan_categoria_detail.language_id
                FROM
                    plans
                INNER JOIN plan_detail ON plan_detail.plan_id = plans.id
                INNER JOIN plan_categoria_detail ON plan_categoria_detail.id_plan_categoria = plans.id_plan_categoria
                WHERE
                    1 AND plans.eliminado=1 AND plans.activo=1";
            if(!empty($id_plan_categoria))
                $query.=" AND plans.id_plan_categoria = '$id_plan_categoria'";
            if(!empty($id_session))
                $query.=" AND plan_detail.language_id = '$id_session'";
            if(!empty($id_session))
                $query.=" AND plan_categoria_detail.language_id = '$id_session'";
         //die($query);
            //var_dump($query);
       return $this->_SQL_tool($this->SELECT, __METHOD__, $query);   
    }
    function Get_Plans_categoria_edadminima($id_plan_categoria = '', $max_tiempo = 0) {
        $query = "SELECT
            plans.id,
            plans.`name`,
            plans.activo,
            plans.eliminado,
            plans.normal_age
            FROM
            plans
            WHERE plans.activo = '1' ";
        $query.=" AND eliminado = '1'";
        if (!empty($id_plan_categoria))
            $query.= " AND plans.id_plan_categoria =  '$id_plan_categoria'";
        $query.= " ORDER BY plans.normal_age ASC";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_Plans_tiempo_min($id_plan_categoria = '', $orden) {
        $query = "SELECT  plans.min_tiempo,plans.max_tiempo,plans.unidad FROM plans  WHERE plans.activo =  '1' ";
        $query.=" AND eliminado = '1'";
        if (!empty($id_plan_categoria))
            $query.= " AND plans.id_plan_categoria =  '$id_plan_categoria'";
        $query.= " ORDER BY $orden";
        //var_dump($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
//return $this->select_tool($sQuery);
    }
    function Get_Plans_categoria2($id_plan_categoria = '', $max_tiempo = '') {
        $query = "SELECT  plans.id, plans.name, plans.description, plans.activo,
                        plans.id_plan_categoria,plans.unidad, plans.min_tiempo,
                        plans.max_tiempo,plans.imagen, plans.deducible, plans.id_currence,
                        plans.remark, plans.commissions,plans.botht, plans.normal_age,
                        plans.max_age,plans.overage_factor,plans.overage_factor_cost,restriction.id_restric,
                        restriction.dirigido, restriction.id_territory_origen, restriction.id_territory_destino,
                        restriction.id_broker, restriction.id_broker1, restriction.id_broker2,
                        restriction.id_broker3, restriction.id_broker4, restriction.id_broker5,
                        restriction.id_plans, restriction.id_client, restriction.created, restriction.modified
                        FROM plans Left Join restriction ON plans.id = restriction.id_plans
                        WHERE plans.activo =  '1' ";
        $query.=" AND eliminado = '1'";
        if (!empty($id_plan_categoria))
            $query.= " AND plans.id_plan_categoria =  '$id_plan_categoria'";
        if (!empty($max_tiempo))
            $query.=" AND max_tiempo='$max_tiempo'";
        $query.=" ORDER BY plans.orden ASC";
		// var_dump($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
//return $this->select_tool($sQuery);
    }
	function Get_Plans_categoria3($id_plan_categoria = '', $min_tiempo = 30, $id_currence='', $language_id="spa") {
        $query = "SELECT  plans.id, plans.name, plans.description, plans.activo, plans.eliminado,
                        plans.id_plan_categoria,plans.unidad, plans.min_tiempo,
                        plans.max_tiempo,plans.imagen, plans.deducible, plans.id_currence,
                        plans.remark, plans.commissions,plans.botht, plans.normal_age,
                        plans.max_age,plans.overage_factor,plans.overage_factor_cost,restriction.id_restric,
                        restriction.dirigido, restriction.id_territory_origen, restriction.id_territory_destino,
                        restriction.id_broker, restriction.id_broker1, restriction.id_broker2,
                        restriction.id_broker3, restriction.id_broker4, restriction.id_broker5,
                        restriction.id_plans, restriction.id_client, restriction.created, restriction.modified, plan_detail.titulo, plan_detail.description, plan_detail.language_id
                        FROM plans Left Join restriction ON plans.id = restriction.id_plans Left Join plan_detail on plans.id = plan_detail.plan_id WHERE plans.activo =  '1' And plan_detail.language_id = '$language_id' ";
        $query.=" AND plans.eliminado = '1'";
        if (!empty($id_plan_categoria))
            $query.= " AND plans.id_plan_categoria =  '$id_plan_categoria'";
        if (!empty($min_tiempo))
            $query.=" AND plans.min_tiempo='$min_tiempo'";
			if (!empty($id_currence))
            $query.=" AND plans.id_currence='$id_currence'";
        $query.=" ORDER BY plans.orden ASC";
		//	var_dump($query);
       // die(Get_Plans_categoria2);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
//return $this->select_tool($sQuery);
    }
    function Get_Plans_categoria_cot($id_plan = '', $lng_id='') {
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
                        Inner Join currency ON plans.tipo_pago = currency.id_currency
                        INNER JOIN plan_detail ON plans.id = plan_detail.plan_id
                        WHERE 1 AND plan_detail.language_id= "' . $_SESSION['lng_id'] . '"';
        if (!empty($id_plan))
            $query.= " AND plans.id =  '$id_plan'";
        //die($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
        //return $this->select_tool($sQuery);
    }
    function Delete_Benefit($id) {
        $sQuery = "Update benefit_plan SET eliminado='2' WHERE id='$id'";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery);
    }
    function Get_Plans_by_tiempo_cotizador($id_plan) {
        $query = "SELECT * FROM plan_times WHERE id_plan = '$id_plan' ORDER BY tiempo ";
        // die($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_Plans_by_tiempo_cotizador_byid($id) {
        $query = "SELECT * FROM plan_times WHERE id = '$id'";
        //die($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_comisiones($id, $categoria) {
        $query = "SELECT porcentaje from commissions where id_agencia=" . $id . " and id_categoria=" . $categoria . "";
        //$query="SELECT * FROM plan_times WHERE id = '$id'";
        //die($query."sdfsdf");
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    /**
     * Se obtiene la direccion IP del usuario
     * @return IP
     */
    static
            function getMyIP() {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unknown';
    }
	function Add_orden($tp, $origen, $destino, $fsalida_aux, $fregreso_aux, $nombre_categoria, $nombre_contacto, $email_contacto, $comentarios, $comentario_medicas, $telefono_contacto, $idp, $tipo_tarjeta, $numero_tarjeta, $credito_expira, $credito_cvv, $credito_nombre, $id_broker, $broker, $v, $codigo_final, $neta, $neta2, $neta3, $iduser, $Cnropasajeros, $status, $response_array, $cupon, $codeauto, $nombre_producto, $Transaction, $totalcostocost, $alter_cur, $tasa_cambio='', $formapago, $family_plan, $referencia, $territorio, $idemisiontype, $id_group, $mostrar_carnet, $id_cotiza, $id_preorden, $totaTax, $totaTax2, $id_preventa, $total_mlc, $costo_mlc,$descuento,$descuento_mlc,$totalRaider,$CostoRaider,$total_tax_mlc,$total_tax2_mlc,$total_neto){ 
    $des_soap = $destino; 
	$tp = $tp?:0;
	$fsalida_aux = $fsalida_aux?:0; 
    $id_preorden = $id_preorden?:0; 
	$fregreso_aux = $fregreso_aux?:0;
	$idp = $idp?:0; 
	$tipo_tarjeta = $tipo_tarjeta?:0;  
	$id_broker = $id_broker?:0; 
	$v = $v?:0;
	$total_neto = $total_neto ?: 0;
	$totaTax = $totaTax ?: 0;
	$total_tax_mlc = $total_tax_mlc ?: 0;
    $totaTax2 = $totaTax2 ?: 0;
	$total_tax2_mlc = $total_tax2_mlc ?: 0;
	$neta = $neta?:0; 
	$neta2 = $neta2?:0; 
	$neta3 = $neta3?:0; 
	$iduser = $iduser?:0; 
	$Cnropasajeros = $Cnropasajeros?:0; 
	$status = $status?:0; 
	$totalcostocost = $totalcostocost?:0; 
	$alter_cur = $alter_cur?:0; 
	$tasa_cambio = $tasa_cambio?:0; 
	$formapago = $formapago?:0;  
	$idemisiontype = $idemisiontype?:0; 
	$id_group = $id_group?:0; 
	$mostrar_carnet = $mostrar_carnet?:0; 
	$id_cotiza = $id_cotiza?:0;
	$id_preorden = $id_preorden?:0;
    $descuento =$descuento?:0;
    $descuento_mlc=$descuento_mlc?:0;
    $total_mlc=$total_mlc?:0;
    $costo_mlc=$costo_mlc?:0; 
	$id_preventa = $id_preventa?:0;
	$totalRaider = $totalRaider?:0;
    $id_preventa = $id_preventa?:0;		       	
    $CostoRaider = $CostoRaider?:0; 
		if($destino=="9")
		{
			$territorio = $destino;
			$destino= $origen;
			$validez=2;
		}
		else
		{	
			$validez=1;
			$Territory=Countries::Select_Territory1($destino);
			if(!empty($Territory)){
				 $territorio = $destino;
				 $destino= "XX"; 
			}else{		
				 $territorio = '';
				 $destino= $destino;
			} 
		}
        $query = "INSERT INTO orders(tiempo_x_producto,origen,destino,salida,retorno,programaplan,nombre_contacto,email_contacto,comentarios,comentario_medicas,telefono_contacto,producto,credito_tipo,credito_numero,credito_expira,credito_cvv,credito_nombre,agencia,nombre_agencia,total,codigo,neta,neta2,neta3,vendedor,cantidad,status,cupon,codeauto,origin_ip, v_authorizado, neto_prov, response, alter_cur, tasa_cambio, forma_pago, family_plan, referencia ,validez, fecha, hora, id_emision_type, territory, id_group, mostrar_carnet, id_cotiza, id_preorden, id_preventa, total_neto, total_tax, total_tax_2, total_tax_mlc,total_tax2_mlc, total_mlc, neto_prov_mlc,cupon_descto,cupon_dscto_mlc,total_raider,total_costo_raider) VALUES('$tp','$origen','$destino','$fsalida_aux','$fregreso_aux','$nombre_categoria','$nombre_contacto','$email_contacto','$comentarios','$comentario_medicas','$telefono_contacto','$idp','$tipo_tarjeta','$numero_tarjeta','$credito_expira','$credito_cvv','$credito_nombre','$id_broker','$broker','$v','$codigo_final','$neta','$neta2','$neta3','$iduser','$Cnropasajeros','$status','$cupon', '$codeauto', '" . $this->getMyIP() . "', '$Transaction', '$totalcostocost', '$response_array', '$alter_cur', '$tasa_cambio', '$formapago', '$family_plan', '$referencia' ,'$validez', now(), now(), '$idemisiontype', '$territorio', '$id_group', '$mostrar_carnet', '$id_cotiza', '$id_preorden', '$id_preventa' , '$total_neto', '$totaTax','$totaTax2','$total_tax_mlc','$total_tax2_mlc', '$total_mlc', '$costo_mlc','$descuento','$descuento_mlc','$totalRaider','$CostoRaider')";  
        $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert orden <-> codigo:'. $codigo_final,'','','_DEFAULT', $codigo_final);
        /**-----------------------SOAP----------------------*/
         $obj_soapclient = new soapcliente();
         $obj_soapclient->add_soap('orders','id');
        /**-----------------------FIN-SOAP-------------------*/
        /**-----------------------SOAP----------------------*/
        $obj_soapclient = new soapcliente();
        $obj_soapclient->update_soap('territory','id_territory', $des_soap, '');
        /**-----------------------FIN-SOAP-------------------*/
        //die($query."ADD2");
        return $result;
	}
	function add_order_multiples($array_order){
		 $query = "INSERT INTO orders(tiempo_x_producto,origen,destino,salida,retorno,programaplan,nombre_contacto,email_contacto,comentarios,comentario_medicas,telefono_contacto,producto,credito_tipo,credito_numero,credito_expira,credito_cvv,credito_nombre,agencia,nombre_agencia,total,codigo,neta,neta2,neta3,vendedor,cantidad,status,cupon,codeauto,origin_ip, v_authorizado, neto_prov, response, alter_cur, tasa_cambio, forma_pago, family_plan, referencia ,validez, fecha, hora, id_emision_type, territory, id_group, mostrar_carnet, id_cotiza, id_preorden) VALUES ";
        foreach ($array_order as $value) {
			$tp=$value['tp']; 
			$origen=$value['origen'];
			$destino=$value['destino'];
			$fsalida_aux=$value['fsalida_aux'];
			$fregreso_aux=$value['fregreso_aux'];
			$nombre_categoria=$value['nombre_categoria'];
			$nombre_contacto=$value['nombre_contacto'];
			$email_contacto=$value['email_contacto'];
			$comentarios=$value['comentarios'];
			$comentario_medicas=$value['comentario_medicas'];
			$telefono_contacto=$value['telefono_contacto'];
			$idp=$value['idp'];
			$tipo_tarjeta=$value['tipo_tarjeta'];
			$numero_tarjeta=$value['numero_tarjeta'];
			$credito_expira=$value['credito_expira'];
			$credito_cvv=$value['credito_cvv'];
			$credito_nombre=$value['credito_nombre'];
			$id_broker=$value['id_broker'];
			$broker=$value['broker'];
			$v=$value['v'];
			$codigo_final=$value['codigo_final'];
			$neta=$value['neta'];
			$neta2=$value['neta2'];
			$neta3=$value['neta3'];
			$iduser=$value['iduser'];
			$Cnropasajeros=$value['Cnropasajeros'];
			$status=$value['status'];
			$response_array=$value['response_array'];
			$cupon=$value['cupon'];
			$codeauto=$value['codeauto'];
			$nombre_producto='';
			$Transaction=$value['Transaction'];
			$totalcostocost=$value['totalcostocost'];
			$alter_cur=$value['alter_cur'];
			$tasa_cambio=0;
			$formapago=$value['formapago'];
			$family_plan=$value['family_plan'];
			$referencia=$value['referencia'];
			$territorio=$value['territorio'];
			$idemisiontype=$value['idemisiontype'];
			$id_group=$value['id_group'];
			$mostrar_carnet=$value['mostrar_carnet'];
			$id_cotiza=$value['id_cotiza'];
			$id_preorden=$value['id_preorden'];
		   if ($destino=="9"){
            $validez=2;
			}else{
				$validez=1;
			}
			if($destino=="9")
			{
				$territorio = $destino;
				$destino= $origen;
			}
			else
			{
				$territorio = $destino;
				$destino= "XX";
			}
		    $tp = $tp?:0;
			$fsalida_aux = $fsalida_aux?:0; 
			$fregreso_aux = $fregreso_aux?:0;
			$idp = $idp?:0; 
			$tipo_tarjeta = $tipo_tarjeta?:0;  
			$id_broker = $id_broker?:0; 
			$v = $v?:0; 
			$neta = $neta?:0; 
			$neta2 = $neta2?:0; 
			$neta3 = $neta3?:0; 
			$iduser = $iduser?:0; 
			$Cnropasajeros = $Cnropasajeros?:0; 
			$status = $status?:0; 
			$totalcostocost = $totalcostocost?:0; 
			$alter_cur = $alter_cur?:0; 
			$tasa_cambio = $tasa_cambio?:0; 
			$formapago = $formapago?:0;  
			$idemisiontype = $idemisiontype?:0; 
			$id_group = $id_group?:0; 
			$mostrar_carnet = $mostrar_carnet?:0; 
			$id_cotiza = $id_cotiza?:0; 
			$id_preorden = $id_preorden?:0; 
			$query.="('$tp','$origen','$destino','$fsalida_aux','$fregreso_aux','$nombre_categoria','$nombre_contacto','$email_contacto','$comentarios','$comentario_medicas','$telefono_contacto','$idp','$tipo_tarjeta','$numero_tarjeta','$credito_expira','$credito_cvv','$credito_nombre','$id_broker','$broker','$v','$codigo_final','$neta','$neta2','$neta3','$iduser','$Cnropasajeros','$status','$cupon', '$codeauto', '" . $this->getMyIP() . "', '$Transaction', '$totalcostocost', '$response_array', '$alter_cur', '0', '$formapago', '$family_plan', '$referencia' ,'$validez', now(), now(), '$idemisiontype', '$territorio', '$id_group', '$mostrar_carnet', '$id_cotiza', '$id_preorden'),"; 
        }
        $query = substr($query, 0, -1);
		$result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert orden <-> codigo:'. $codeauto,'','','_DEFAULT', $codigo_final);
	   return $result;
    }
    function Update_orden($id_orden, $status = '', $response='') {
        $sQuery = "UPDATE orders SET ";
        if(!empty($status)){
            $sQuery.= " status = '$status',";
        }
        if(!empty($response)){
            $sQuery.= " response = '$response'";
        }
        $sQuery.=" WHERE id = $id_orden";
        $comentario = 'Updated |  Orden status : ' . $status . ', ID id: ' . $id;
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery, $comentario,'','','_DEFAULT', $cod_voucher[0]['codigo']);
        /**-----------------------SOAP----------------------*/
        $obj_soapclient = new soapcliente();
        $obj_soapclient->update_soap('orders','id', $id_orden, '');
        /**-----------------------FIN-SOAP-------------------*/
    }
    function Add_beneficiarios($idorden, $a_nombre, $a_apellido, $a_email, $fnacimiento_aux = '', $a_pasaporte, $a_telefonopasagero = '', $a_precio_vta = 0, $a_precio_cost = 0, $a_precio_neta=0, $codigo_final='', $nacionalidad='', $condicion_medica = '',$a_sexo='', $tipo_doc = '', $a_precio_neto_benfit=0,$a_costo_neto_benfit=0) {
		$idorden = $idorden?:0;   
		$fnacimiento_aux = $fnacimiento_aux?:0; 
		$a_precio_vta = $a_precio_vta?:0;   
		$a_precio_cost = $a_precio_cost?:0;		
		$a_precio_neta = $a_precio_neta?:0; 
		$a_precio_neto_benfit = $a_precio_neto_benfit?:0;
		$a_costo_neto_benfit = $a_costo_neto_benfit?:0;		
        $query = "INSERT INTO beneficiaries(id_orden,nombre,apellido,email,nacimiento,documento,nacionalidad,titular,telefono,precio_vta,precio_cost, ben_status, id_rider, precio_neto, condicion_medica , sexo, tipo_doc,total_neto_benefit,neto_cost) VALUES(" . $idorden . ",'$a_nombre','$a_apellido','$a_email','$fnacimiento_aux','$a_pasaporte','$nacionalidad','0','$a_telefonopasagero','$a_precio_vta','$a_precio_cost','1','0', '$a_precio_neta' , '$condicion_medica','$a_sexo', '$tipo_doc','$a_precio_neto_benfit','$a_costo_neto_benfit')";
        $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert banficiario <-> id orden:' . $idorden,'','','_DEFAULT',$codigo_final);
        $this->id = $result;
        /**-----------------------SOAP----------------------*/
            $obj_soapclient = new soapcliente();
             $obj_soapclient->add_soap('beneficiaries','id');
         /**-----------------------FIN-SOAP-------------------*/
        return $result;
    }
	##########INSERTAR GRAN CANTIDAD DE ENEFICIARIOS##########
	 function Add_beneficiarios_Multiples($array_beneficiarios, $id_primer_orden, $type_group ){
		 $query = "INSERT INTO beneficiaries(id_orden,nombre,apellido,email,nacimiento,documento,nacionalidad,titular,telefono,precio_vta,precio_cost, ben_status, id_rider, precio_neto, condicion_medica , sexo, tipo_doc,total_neto_benefit,neto_cost) VALUES ";
		 $contador_order=$id_primer_orden;
        foreach ($array_beneficiarios as $value) {
			$idorden=$contador_order;
			$a_nombre= addslashes($value['a_nombre']);
			$a_apellido= addslashes($value['a_apellido']);
			$a_email= addslashes($value['a_email']);
			$fnacimiento_aux=$value['fnacimiento_aux'];
			$a_pasaporte= addslashes($value['a_pasaporte']);
			$a_telefonopasagero= addslashes($value['a_telefonopasagero']);
			$a_precio_vta=$value['a_precio_vta'];
			$a_precio_cost=$value['a_precio_cost'];
			$a_precio_neta=$value['a_precio_neta'];
			$codigo_final=$value['codigo_final'];
			$nacionalidad= addslashes($value['nacionalidad']);
			$condicion_medica= addslashes($value['condicion_medica']);
			$a_sexo= addslashes($value['a_sexo']);
			$tipo_doc= addslashes($value['tipo_doc']);
		$idorden = $idorden?:0;   
		$fnacimiento_aux = $fnacimiento_aux?:0; 
		$a_precio_vta = $a_precio_vta?:0;   
		$a_precio_cost = $a_precio_cost?:0;		
		$a_precio_neta = $a_precio_neta?:0;
        $a_precio_neto_benfit = $a_precio_vta;
        $a_costo_neto_benfit = $a_precio_cost;         
			$query.="(" . $idorden . ",'$a_nombre','$a_apellido','$a_email','$fnacimiento_aux','$a_pasaporte','$nacionalidad','0','$a_telefonopasagero','$a_precio_vta','$a_precio_cost','1','0', '$a_precio_neta' , '$condicion_medica','$a_sexo', '$tipo_doc','$a_precio_neto_benfit',' $a_costo_neto_benfit'),"; 
			if($type_group=='1'){
				$contador_order++;
			}
        }
        $query = substr($query, 0, -1);
		   $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert banficiario <-> id orden:' . $idorden,'','','_DEFAULT',$codigo_final);
        $this->id = $result;
        /**-----------------------SOAP----------------------*/
            $obj_soapclient = new soapcliente();
             $obj_soapclient->add_soap('beneficiaries','id');
         /**-----------------------FIN-SOAP-------------------*/
        return $result;
    }
	function eliminar_caracteres_prohibidos($arreglo)
    {
        $caracteres_prohibidos = array("'","/","<",">",";");    
        return str_replace($caracteres_prohibidos,"",$arreglo); 
		//return  mysql_real_escape_string($arreglo);
    }
    function Get_beneficiarios_by($idorden, $nombre, $LIMIT){
		$query="SELECT * FROM beneficiaries WHERE id_orden='".$idorden."' ORDER BY id DESC LIMIT 1";
		 return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
	}
    function Get_orden($idorden) {
       $query = "SELECT id FROM orders WHERE codigo='" . $idorden . "' ORDER BY id DESC LIMIT 1";
	// die($query. "ESTA ES EL SQUERY");
	  //$query = "SELECT id FROM orders ORDER BY id DESC LIMIT 1";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function select_order_codigo($idorden) {
        $query = "SELECT id, codigo, producto FROM orders WHERE id = '$idorden'";
        //die($query);
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
        //die($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_orden_resul_beneficiaries($idorden) {
        $query = "SELECT *,month(nacimiento) mes_nacimiento, year(nacimiento) ano_nacimiento, day(nacimiento) dia_nacimiento FROM beneficiaries WHERE id_orden=" . $idorden;
        //die($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
//    function Get_comisiones($id,$categoria){
//	$strSQL1="SELECT IFNULL((select porcentaje from comisiones where id_agencia=".$id." and id_categoria=".$categoria.") ,0) comisionN,correlativo,perfil,agencia1,agencia2,credito_actual,fecha_credito,month(fecha_credito) mes_credito, year(fecha_credito) ano_credito, day(fecha_credito) dia_credito FROM agencias WHERE id=".$id." LIMIT 1";	
//            //$query="SELECT * FROM plan_times WHERE id = '$id'";
//               // die($query);
//		 return $this->_SQL_tool($this->SELECT, __METHOD__, $strSQL1);
//	}
////////////////////////////////////////////////////funciones viejas///////////////////////////////////////////
////////////////////////////////////////////////////funciones viejas///////////////////////////////////////////
////////////////////////////////////////////////////funciones viejas///////////////////////////////////////////
    function Get_Last_Plans($id_plan = '', $id_product = '', $id_client = '', $id_status = '', $start_record = '', $page_size = '') {
        $sQuery = "SELECT * FROM plans WHERE 1";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
        if (!empty($id_product))
            $sQuery.=" AND id_product = '$id_product'";
        if (!empty($id_client))
            $sQuery.=" AND id_client = '$id_client'";
        if (!empty($id_status))
            $sQuery.=" AND id_status = '$id_status'";
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
        return $this->select_tool($sQuery);
    }
    function Get_PlansbyRestrictionsBroker($id_broker = '', $start_record = '', $page_size = '') {
        $sQuery = "SELECT 
				plans.id_plan,
				plans.id_product,
				plans.id_client,
				plans.desc_small,
				plans.desc_large,
				plans.id_policyfee,
				plans.policyfee,
				plans.ddate_begin,
				plans.ddate_end,
				plans.ddate_effective,
				plans.max_age,
				plans.max_age_children,
				plans.max_age_student,
				plans.pay_immediately,
				plans.id_status
				FROM
				plans
				left JOIN plans_restrictions ON (plans.id_plan = plans_restrictions.id_plan)
				WHERE
				( (plans_restrictions.type_restrictions = 2) and (plans_restrictions.id_relation <> $id_broker)) or (
				(plans_restrictions.type_restrictions is null))
				group by plans.id_plan";
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
        return $this->select_tool($sQuery);
    }
    function Get_PlansbyRestrictions($type_restrictions = '', $id_relation = '', $start_record = '', $page_size = '') {
        $sQuery = "SELECT
				plans.*
				FROM
				plans
				Inner Join plans_restrictions ON plans.id_plan = plans_restrictions.id_plan
				WHERE
				plans_restrictions.type_restrictions =  $type_restrictions AND
				plans_restrictions.id_relation =  $id_relation";
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
        return $this->select_tool($sQuery);
    }
    function Valid_Plans($id_plan) {
        $return = false;
        if ($this->Exits_Deductible($id_plan) == true && $this->Exits_Rates($id_plan) == true) {
            $return = true;
        }
        return $return;
    }
    function Get_StepOfPlan($id_plan) {
        $_SESSION['step'] = 3;
        if ($this->Valid_Plans($id_plan) == true) {
            $_SESSION['step'] = 4;
            $sQuery = "SELECT * FROM plans_restrictions WHERE id_plan = $id_plan";
            $res = mysql_query($sQuery);
            if (mysql_num_rows($res) > 0) {
                $_SESSION['step'] = 8;
            }
        }
    }
/*
    function Clone_Plan($id_plan) {
        $arrPlan = $this->Get_Plans($id_plan);
        $id_plan_new = $this->Add_Plan($arrPlan[0][id_product], $arrPlan[0][id_client], 'Clone to ' . $arrPlan[0][desc_small], $arrPlan[0][desc_large], $arrPlan[0][id_policyfee], $arrPlan[0][policyfee], funciones::formaDateMySql($arrPlan[0][ddate_begin]), funciones::formaDateMySql($arrPlan[0][ddate_end]), funciones::formaDateMySql($arrPlan[0][ddate_effective]), $arrPlan[0][max_age], $arrPlan[0][max_age_spouse], $arrPlan[0][max_age_children], $arrPlan[0][max_age_student], $arrPlan[0][pay_immediately], $arrPlan[0][id_status], $arrPlan[0][plan_group], $arrPlan[0][uw_approval], $arrPlan[0][uw_reviewprior], $arrPlan[0][uw_reviewprior_amount], $arrPlan[0][claims_alert], $arrPlan[0][claims_alert_amount], $arrPlan[0][claims_notifications], $arrPlan[0][notifications_normal], $arrPlan[0][notifications_renewal], $arrPlan[0][notifications_renewal_days], $arrPlan[0][template_renewal], $arrPlan[0][authorized_payment_forms], $arrPlan[0][authorized_payment_options], $arrPlan[0][authorized_payment_percentage], $arrPlan[0][creditcard_surcharge], $arrPlan[0][id_insurance_co]);
        $arrPlansOptions = $this->Get_Plans_Options('', $id_plan);
        for ($i = 0; $i < sizeof($arrPlansOptions); $i++) {
            $this->Add_Plan_Options($id_plan_new, $arrPlansOptions[$i][type_options], $arrPlansOptions[$i][options_response]);
        }
        $arrPlansBenefits = $this->Get_Plans_Benefits('', $id_plan);
        for ($i = 0; $i < sizeof($arrPlansBenefits); $i++) {
            $this->Add_Plan_Benefits($id_plan_new, $arrPlansBenefits[$i][id_benefit], $arrPlansBenefits[$i][desc_large], $arrPlansBenefits[$i][id_status], $arrPlansBenefits[$i][effective_date], $arrPlansBenefits[$i][inactive_date]);
        }
        $arrPlansDeductible = $this->Get_Plans_Deductible('', $id_plan);
        for ($i = 0; $i < sizeof($arrPlansDeductible); $i++) {
            $id_plan_deductible_new = $this->Add_Plan_Deductible($id_plan_new, $arrPlansDeductible[$i][desc_small], $arrPlansDeductible[$i][amount], $arrPlansDeductible[$i][amount_out], $arrPlansDeductible[$i][id_status], $arrPlansDeductible[$i][id_type_deductible], $arrPlansDeductible[$i][time_deductible], $arrPlansDeductible[$i][id_deductible_form], $arrPlansDeductible[$i][sf_exception], $arrPlansDeductible[$i][diff_rates_gender], $arrPlansDeductible[$i][children_waiver], $arrPlansDeductible[$i][cw_max_age]);
            $arrPlansRates = $this->Get_Plans_Rates('', $arrPlansDeductible[$i][id_plan_deductible]);
            for ($j = 0; $j < sizeof($arrPlansRates); $j++) {
                $this->Add_Plan_Rates($id_plan_deductible_new, $id_plan_new, $arrPlansRates[$j][age_min], $arrPlansRates[$j][age_max], $arrPlansRates[$j][rate_action], $arrPlansRates[$j][female_new], $arrPlansRates[$j][female_renewal], $arrPlansRates[$j][male_new], $arrPlansRates[$j][male_renewal], $arrPlansRates[$j][year_contract], $arrPlansRates[$j][id_status], $_SESSION['id_user'], date('Y-m-d H:i:s'), $arrPlansRates[$j][female_amount], $arrPlansRates[$j][male_amount]);
            }
        }
        $arrPlansRestrictions = $this->Get_Plans_Restrictions('', $id_plan);
        for ($i = 0; $i < sizeof($arrPlansRestrictions); $i++) {
            $this->Add_Plan_Restrictions($id_plan_new, $arrPlansRestrictions[$i][type_restrictions], $arrPlansRestrictions[$i][type_restrictions]);
        }
        $arrPlansWording = $this->Get_Plans_Wording('', $id_plan);
        for ($i = 0; $i < sizeof($arrPlansWording); $i++) {
            $this->Add_Plan_Wording($id_plan_new, $arrPlansWording[$i][type_document], $arrPlansWording[$i][url_document], $arrPlansWording[$i][anno], $arrPlansWording[$i][id_status], $arrPlansWording[$i][type_wording], $arrPlansWording[$i][year]);
        }
        $arrPlansRiders = $this->Get_Plans_Riders('', $id_plan);
        for ($i = 0; $i < sizeof($arrPlansRiders); $i++) {
            $id_plan_rider_new = $this->Add_Plans_Riders($id_plan_new, $arrPlansRiders[$i][rider], $arrPlansRiders[$i][id_rider_charge], $arrPlansRiders[$i][recurrence], $arrPlansRiders[$i][id_rider_covertype], $arrPlansRiders[$i][coverage_amount], $arrPlansRiders[$i][amount], $arrPlansRiders[$i][if_max_age], $arrPlansRiders[$i][max_age], $arrPlansRiders[$i][wording], $arrPlansRiders[$i][id_status], $arrPlansRiders[$i][effective_date]);
            $arrRiderVariable = $this->Get_Plans_RidersVariable('', $arrPlansRiders[$i][id_plan_rider]);
            for ($j = 0; $j < sizeof($arrRiderVariable); $j++) {
                $this->Add_Plans_RidersVariable($id_plan_rider_new, $arrRiderVariable[$j][individual], $arrRiderVariable[$j][couple], $arrRiderVariable[$j][family], $arrRiderVariable[$j][single_family], $arrRiderVariable[$j][id_status], $arrRiderVariable[$j][year], $arrRiderVariable[$j][female], $arrRiderVariable[$j][male]);
            }
            $arrRiderBrackets = $this->Get_Plans_RidersBrackets('', $arrPlansRiders[$i][id_plan_rider]);
            for ($j = 0; $j < sizeof($arrRiderBrackets); $j++) {
                $this->Add_Plans_RidersBrackets($id_plan_rider_new, $arrRiderBrackets[$j][age_min], $arrRiderBrackets[$j][age_max], $arrRiderBrackets[$j][cost_factor], $arrRiderBrackets[$j][id_status], $arrRiderBrackets[$j][year], $arrRiderBrackets[$j][female], $arrRiderBrackets[$j][male]);
            }
        }
        $arrPlansContact = $this->Get_All_plans_contact('', $id_plan);
        for ($i = 0; $i < sizeof($arrPlansContact); $i++) {
            $this->Add_plans_contact($id_plan_new, $arrPlansContact[$i][id_plan_contact_type], $arrPlansContact[$i][id_user], $arrPlansContact[$i][id_group], $arrPlansContact[$i][policy_distribution_start], $arrPlansContact[$i][policy_distribution_end], $arrPlansContact[$i][id_status], $arrPlansContact[$i][begin_date], $arrPlansContact[$i][end_date], $arrPlansContact[$i][amount_limit]);
        }
    }
*/
// Plans Options *********************************************************
    function Get_Plans_Options($id_plan_options = '', $id_plan = '', $type_options = '', $start_record = '', $page_size = '') {
        $sQuery = "SELECT * FROM plans_options WHERE 1";
        if (!empty($id_plan_options))
            $sQuery.=" AND id_plan_options = '$id_plan_options'";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
        if (!empty($type_options))
            $sQuery.=" AND type_options = '$type_options'";
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
        return $this->select_tool($sQuery);
    }
    function Add_Plan_Options($id_plan, $type_options, $options_response) {
		$type_options = $type_options?:0;   
        $ddate = date("Y-m-d");
        $insert = "INSERT INTO plans_options (id_plan, type_options, options_response) VALUES ('$id_plan', '$type_options', '$options_response')";
        return $this->ins_tool_return($insert);
    }
    function Delete_Plan_Options($id_plan, $type_options) {
        $ddate = date("Y-m-d");
        $delete = "DELETE from plans_options where id_plan = '$id_plan' and type_options = '$type_options'";
        $this->del_tool($delete);
    }
    function Exits_Plan_Options($id_plan, $type_options, $options_response) {
        $sQuery = "SELECT * FROM plans_options WHERE id_plan = $id_plan and type_options = '$type_options' and options_response = '$options_response'";
        return $this->select_tool_Boolean($sQuery);
    }
// Plans Benefits *********************************************************
    function Get_Plans_Benefits($id_plan_benefit = '', $id_plan = '', $id_benefit = '', $start_record = '', $page_size = '') {
        $sQuery = "SELECT * FROM plans_benefits WHERE 1 AND id_benefit_code <> 0";
        if (!empty($id_plan_benefit))
            $sQuery.=" AND id_plan_benefit = '$id_plan_benefit'";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
        if (!empty($id_benefit))
            $sQuery.=" AND id_benefit = '$id_benefit'";
        $arrResult[0][totalREG] = wwexpa::get_total_reg($sQuery);
## $sQuery.= " order by desc_large";
        $sQuery.= " order by id_plan_benefit ASC";
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
        return $this->select_tool($sQuery);
    }
    function Get_Plans_last_Benefits($id_plan_benefit = '', $id_plan = '', $id_benefit = '') {
        $sQuery = "SELECT * FROM plans_benefits WHERE 1 AND id_benefit_code <> 0";
        if (!empty($id_plan_benefit))
            $sQuery.=" AND id_plan_benefit = '$id_plan_benefit'";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
        if (!empty($id_benefit))
            $sQuery.=" AND id_benefit = '$id_benefit'";
        $sQuery.= " ORDER BY id_plan_benefit DESC";
        if (!empty($page_size))
            $sQuery.=" limit 1";
        return $this->select_tool($sQuery);
    }
    function Delete_Plan_Benefits($id_plan_benefit) {
        $sQuery = "delete from plans_benefits where  id_plan_benefit = $id_plan_benefit";
        $this->del_tool($sQuery);
    }
    function ExitsOrder($id_plan, $order) {
        $sQuery = "SELECT * FROM plans_benefits WHERE id_plan = $id_plan and desc_large = '$order'";
        return $this->select_tool_Boolean($sQuery);
    }
    function Reorder_Plan_Benefits($id_plan, $order) {
        $sQuery = "SELECT * FROM plans_benefits WHERE id_plan = $id_plan and desc_large >= '$order'";
        $res = mysql_query($sQuery);
        $i = 0;
        while ($row = mysql_fetch_array($res)) {
            $this->Update_Plan_Benefits($row['id_plan_benefit'], '', '', $row['desc_large'] + 1, '');
        }
    }
    function LastOrderBenefits($id_plan) {
        $sQuery = "SELECT max(desc_large) as last FROM plans_benefits WHERE id_plan = $id_plan";
        return $this->select_tool_field($sQuery, 'last');
    }
    function IFExist_Benefits($id_benefit = '') {
        $sQuery = "SELECT * FROM plans_benefits WHERE id_benefit = '$id_benefit'";
        return cls_tools_db::select_tool_Boolean($sQuery);
    }
// Plans Deductible *********************************************************
  /*  function Get_Plans_Deductible($id_plan_deductible = '', $id_plan = '', $id_status = '', $start_record = '', $page_size = '', $id_deductible_form = '') {
        $sQuery = "SELECT * FROM plans_deductible WHERE 1";
        if (!empty($id_plan_deductible))
            $sQuery.=" AND id_plan_deductible = '$id_plan_deductible'";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
        if (!empty($id_status))
            $sQuery.=" AND id_status = '$id_status'";
        if (!empty($id_deductible_form))
            $sQuery.=" AND id_deductible_form = '$id_deductible_form'";
        $arrResult[0][totalREG] = wwexpa::get_total_reg($sQuery);
        $sQuery.=" and id_status <> 2";
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
        return $this->select_tool($sQuery);
    }
*/
   /* function Add_Plan_Deductible($id_plan, $desc_small, $amount, $amount_out, $id_status, $id_type_deductible, $time_deductible, $id_deductible_form, $sf_exception, $diff_rates_gender, $children_waiver, $cw_max_age) {
        $insert = "INSERT INTO plans_deductible (id_plan, desc_small, amount, amount_out, id_status, id_type_deductible, time_deductible, id_deductible_form, sf_exception, diff_rates_gender, children_waiver, cw_max_age) VALUES ('$id_plan', '$desc_small', '$amount', '$amount_out', '$id_status', '$id_type_deductible', '$time_deductible', '$id_deductible_form', '$sf_exception', '$diff_rates_gender', '$children_waiver', '$cw_max_age')";
## echo $insert;
        return $this->ins_tool_return($insert);
    }
*/
  /*  function Update_Plan_Deductible($id_plan_deductible = '', $id_plan = '', $desc_small = '', $amount = '', $amount_out = '', $id_status = '', $id_type_deductible = '', $time_deductible = '', $id_deductible_form = '', $sf_exception = '', $diff_rates_gender = '', $children_waiver = '', $cw_max_age = '') {
        $sQuery = "UPDATE plans_deductible SET ";
        $sQuery.= "id_plan_deductible = $id_plan_deductible";
        if (!empty($id_plan))
            $sQuery.= ", id_plan = '$id_plan'";
        if (!empty($desc_small))
            $sQuery.= ", desc_small = '$desc_small'";
        if (!empty($amount))
            $sQuery.= ", amount = '$amount'";
        if (!empty($amount_out))
            $sQuery.= ", amount_out = '$amount_out'";
        if (!empty($id_status))
            $sQuery.= ", id_status = '$id_status'";
        if (!empty($id_type_deductible))
            $sQuery.= ", id_type_deductible = '$id_type_deductible'";
        if (!empty($time_deductible))
            $sQuery.= ", time_deductible = '$time_deductible'";
        if (!empty($id_deductible_form))
            $sQuery.= ", id_deductible_form = '$id_deductible_form'";
        if (!empty($sf_exception))
            $sQuery.= ", sf_exception = '$sf_exception'";
        if (!empty($diff_rates_gender))
            $sQuery.= ", diff_rates_gender = '$diff_rates_gender'";
        if (!empty($children_waiver))
            $sQuery.= ", children_waiver = '$children_waiver'";
        if (!empty($cw_max_age))
            $sQuery.= ", cw_max_age = '$cw_max_age'";
        $sQuery.=" WHERE id_plan_deductible = $id_plan_deductible";
        $this->upt_tool($sQuery);
    }
    function Delete_Plan_Deductible($id_plan_deductible) {
        $sQuery = "update plans_deductible set id_status = 2 where  id_plan_deductible = $id_plan_deductible";
        $this->Delete_Plan_RatesbyDeductibles($id_plan_deductible);
        groups::Delete_group_ratesbyDeductible($id_plan_deductible);
        $this->del_tool($sQuery);
    }
    function Exits_DeductiblebyID($id_plan, $id_plan_deductible) {
        $sQuery = "SELECT * FROM plans_deductible WHERE id_plan = '$id_plan' and id_plan_deductible = '$id_plan_deductible'";
//die(" Exits_DeductiblebyID => ".$sQuery);
        return cls_tools_db::select_tool_Boolean($sQuery);
    }
    function Exits_Deductible($id_plan) {
        $sQuery = "SELECT * FROM plans_deductible WHERE id_plan = $id_plan";
        return $this->select_tool_Boolean($sQuery);
    }
    function MaxAge_Deductible($id_plan_deductible) {
        $sQuery = "SELECT 
				max(age_max) as age_max
				FROM
				plans_rates WHERE id_plan_deductible = $id_plan_deductible";
        return $this->select_tool_field($sQuery, 'age_max');
    }*/
   /* function get_all_plan_deductible_form($id_deductible_form = '') {
        $sQuery = "SELECT * FROM plans_deductible_form WHERE 1";
        if (!empty($id_deductible_form))
            $sQuery.= " and id_deductible_form = '$id_deductible_form'";
        return $this->select_tool($sQuery);
    }*/
   /* function get_plan_deductible_form($id_deductible_form) {
        $sQuery = "SELECT * FROM plans_deductible_form WHERE id_deductible_form = '$id_deductible_form'";
        return $this->select_tool($sQuery);
    }*/
// Plans Rates *********************************************************
   /* function Get_Plans_Rates($id_plan_rates = '', $id_plan_deductible = '', $id_plan = '', $year_contract = '', $id_status = '', $start_record = '', $page_size = '') {
        $sQuery = "SELECT * FROM plans_rates WHERE 1";
        if (!empty($id_plan_rates))
            $sQuery.=" AND id_plan_rates = '$id_plan_rates'";
        if (!empty($id_plan_deductible))
            $sQuery.=" AND id_plan_deductible = '$id_plan_deductible' ";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
        if (!empty($year_contract))
            $sQuery.=" AND year_contract = '$year_contract'";
        if (!empty($id_status))
            $sQuery.=" AND id_status = '$id_status'";
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
        return $this->select_tool($sQuery);
    }
    function Get_Plans_RatesBetweenAge($id_plan_deductible = '', $age = '') {
        $sQuery = "SELECT * FROM plans_rates 
		WHERE $age BETWEEN age_min and age_max ";
        if (!empty($id_plan_deductible))
            $sQuery.= " AND id_plan_deductible= '$id_plan_deductible' ";
//echo "Get_Plans_RatesBetweenAge => ".$sQuery."</br>";
        return $this->select_tool($sQuery);
    }*/
    function Get_Plans_RatesBetweenAge_byDate($id_plan_deductible = '', $age = '', $date_Trans = '') {
        if (!empty($id_plan_deductible) && !empty($age)) {
            $arrPlanRates = $this->Get_Plans_RatesBetweenAge($id_plan_deductible, $age);
            $alert = "[Plan Deductible, Age, dateTrans] : [" . $id_plan_deductible . "," . $age . "," . $date_Trans . "]\n";
            $alert .= "Registros : " . "" . sizeof($arrPlanRates) . "\n";
            $alert .= "Diferencias :\n[rate, Contract date, Contract date next, date Trans, Diff 1, Diff 2, Conclussions]\n";
            for ($rate = 0; $rate < sizeof($arrPlanRates); $rate++) {
                if (!empty($arrPlanRates[$rate]['year_contract']) && $arrPlanRates[$rate]['year_contract'] != "0000-00-00") {
                    $break = 0;
                    $diff_1 = funciones::diffDate2(funciones::formaDate2($arrPlanRates[$rate]['year_contract']), $date_Trans);
                    $partTrans = explode("-", $date_Trans);
                    $partEffective = explode("/", $arrPlanRates[$rate]['year_contract']);
                    $rate_next = $rate + 1;
                    if (!empty($arrPlanRates[$rate_next]['year_contract'])) {
                        $diff_2 = funciones::diffDate2(funciones::formaDate2($arrPlanRates[$rate_next]['year_contract']), $date_Trans);
                    }
                    else {
                        $diff_2 = -1;
                        $break = -1;
                    }
                    if ($diff_1 == 0) {
                        $id_plan_rates = $arrPlanRates[$rate]['id_plan_rates'];
                    }
                    elseif ($diff_1 > 0 && $diff_2 < 0) {
                        $id_plan_rates = $arrPlanRates[$rate]['id_plan_rates'];
                    }
                    elseif ($diff_1 < 0 && $diff_2 < 0 && $break != -1) {
                        break;
//$id_plan_rates = $arrPlanRates[0]['id_plan_rates'];
                    }
                    $alert .= $rate . " => [" . $arrPlanRates[$rate]['id_plan_rates'] . ", " . funciones::formaDate2($arrPlanRates[$rate]['year_contract']) . ", " . funciones::formaDate2($arrPlanRates[$rate_next]['year_contract']) . ", " . $date_Trans . ", " . $diff_1 . ", " . $diff_2 . ", Result => " . $id_plan_rates . "]\n";
                }
            }
//echo "\n".$alert;
            $sQuery = "SELECT * FROM plans_rates WHERE $age BETWEEN age_min and age_max 
					 AND id_plan_deductible = '$id_plan_deductible' AND id_plan_rates = '$id_plan_rates'";
//echo "Get_Plans_RatesBetweenAge_byDate => ".$sQuery."</br>";
            return $this->select_tool($sQuery);
        }
    }
   /* function Add_Plan_Rates($id_plan_deductible, $id_plan, $age_min, $age_max, $rate_action, $female_new, $female_renewal, $male_new, $male_renewal, $year_contract, $id_status, $id_user, $date_modified, $female_amount, $male_amount) {
        $ddate = date("Y-m-d");
        $insert = "INSERT INTO plans_rates (id_plan_deductible, id_plan, age_min, age_max, rate_action, female_new, female_renewal, male_new, male_renewal, year_contract, id_status, id_user, date_modified, female_amount, male_amount) VALUES ('$id_plan_deductible', '$id_plan', '$age_min', '$age_max', '$rate_action', '$female_new', '$female_renewal', '$male_new', '$male_renewal', '$year_contract', '$id_status', '$id_user', '$date_modified', '$female_amount', '$male_amount')";
        return $this->ins_tool_return($insert);
    }*/
   /* function Update_Plan_Rates($id_plan_rates, $id_plan_deductible, $id_plan, $age_min, $age_max, $rate_action, $female_new, $female_renewal, $male_new, $male_renewal, $year_contract, $id_status, $id_user, $date_modified, $female_amount, $male_amount) {
        $ddate = date("Y-m-d");
        $sQuery = "UPDATE plans_rates SET ";
        $sQuery.= "id_plan_rates = $id_plan_rates";
        if (!empty($id_plan_deductible))
            $sQuery.= ", id_plan_deductible = '$id_plan_deductible'";
        if (!empty($id_plan))
            $sQuery.= ", id_plan = '$id_plan'";
        if (!empty($age_min))
            $sQuery.= ", age_min = '$age_min'";
        if (!empty($age_max))
            $sQuery.= ", age_max = '$age_max'";
        if (!empty($rate_action))
            $sQuery.= ", rate_action = '$rate_action'";
        if (!empty($female_new))
            $sQuery.= ", female_new = '$female_new'";
        if (!empty($female_renewal))
            $sQuery.= ", female_renewal = '$female_renewal'";
        if (!empty($male_new))
            $sQuery.= ", male_new = '$male_new'";
        if (!empty($male_renewal))
            $sQuery.= ", male_renewal = '$male_renewal'";
        if (!empty($year_contract))
            $sQuery.= ", year_contract = '$year_contract'";
        if (!empty($id_status))
            $sQuery.= ", id_status = '$id_status'";
        if (!empty($id_user))
            $sQuery.= ", id_user = '$id_user'";
        if (!empty($date_modified))
            $sQuery.= ", date_modified = '$date_modified'";
        if (!empty($female_amount))
            $sQuery.= ", female_amount = '$female_amount'";
        if (!empty($male_amount))
            $sQuery.= ", male_amount = '$male_amount'";
        $sQuery.=" WHERE id_plan_rates = $id_plan_rates";
        $this->upt_tool($sQuery);
    }
*/
 /*   function Delete_Plan_Rates($id_plan_rates) {
        $sQuery = "delete from plans_rates where  id_plan_rates = $id_plan_rates";
        $this->del_tool($sQuery);
    }
    function Delete_Plan_RatesbyDeductibles($id_plan_deductible) {
        $sQuery = "update plans_rates set id_status = 2 where  id_plan_deductible = $id_plan_deductible";
        $this->del_tool($sQuery);
    }
    function Exits_Rates($id_plan) {
        $sQuery = "SELECT * FROM plans_rates WHERE id_plan = $id_plan";
        return $this->select_tool_Boolean($sQuery);
    }*/
// Plans Restrictions *********************************************************
    function Get_Plans_Restrictions($id_plan_restrictions = '', $id_plan = '', $id_relation = '', $type_restrictions = '', $start_record = '', $page_size = '') {
        $sQuery = "SELECT * FROM plans_restrictions WHERE 1";
        if (!empty($id_plan_restrictions))
            $sQuery.=" AND id_plan_restrictions = '$id_plan_restrictions'";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
        if (!empty($type_restrictions))
            $sQuery.=" AND type_restrictions in ($type_restrictions)";
        if (!empty($id_relation))
            $sQuery.=" AND id_relation = '$id_relation'";
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
        return $this->select_tool($sQuery);
    }
    function Add_Plan_Restrictions($id_plan, $type_restrictions, $id_relation) {
        $ddate = date("Y-m-d");
		$type_restrictions = $type_restrictions?:0; 
		$id_relation = $id_relation?:0; 
        $insert = "INSERT INTO plans_restrictions (id_plan, type_restrictions, id_relation) VALUES ('$id_plan', '$type_restrictions', '$id_relation')";
        return $this->ins_tool_return($insert);
    }
    function Update_Plan_Restrictions($id_plan_restrictions, $id_plan, $type_restrictions, $id_relation) {
        $ddate = date("Y-m-d");
        $sQuery = "UPDATE plans_restrictions SET ";
        $sQuery.= "id_plan_restrictions = $id_plan_restrictions";
        if (!empty($id_plan))
            $sQuery.= ", id_plan = '$id_plan'";
        if (!empty($type_restrictions))
            $sQuery.= ", type_restrictions = '$type_restrictions'";
        if (!empty($id_relation))
            $sQuery.= ", id_relation = '$id_relation'";
        $sQuery.=" WHERE id_plan_restrictions = $id_plan_restrictions";
        $this->upt_tool($sQuery);
    }
    function Delete_Plan_Restrictions($id_plan, $type_restrictions) {
        $sQuery = "delete from plans_restrictions where  id_plan = $id_plan and $type_restrictions = type_restrictions";
        $this->del_tool($sQuery);
    }
// Plans Wording *********************************************************
    function Get_Plans_Wording($id_plan_wording = '', $id_plan = '', $anno = '', $id_status = '', $start_record = '', $page_size = '', $type_document = '', $year = '') {
        $sQuery = "SELECT * FROM plans_wording WHERE 1";
        if (!empty($id_plan_wording))
            $sQuery.=" AND id_plan_wording = '$id_plan_wording'";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
        if (!empty($anno))
            $sQuery.=" AND anno = '$anno'";
        if (!empty($id_status))
            $sQuery.=" AND id_status = '$id_status'";
        if (!empty($type_document))
            $sQuery.=" AND type_document = '$type_document'";
        if (!empty($year))
            $sQuery.=" AND year = '$year'";
        $arrResult[0][totalREG] = wwexpa::get_total_reg($sQuery);
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
        return $this->select_tool($sQuery);
    }		 
	function Update_Plans_Wording_status($id_status, $idioma) { 
        $sQuery = "UPDATE plans_wording SET ";
        if (!empty($id_status))
            $sQuery.= " id_status = '2'";
        $sQuery.=" WHERE id_status = $id_status";
		if (!empty($idioma))
            $sQuery.= " and language_id = '$idioma'";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery);
    }
    function Get_All_Wording_Docs($id_plan_wording = '', $id_plan = '', $anno = '', $id_status = '', $type_document = '') {
        $sQuery = "SELECT * FROM plans_wording WHERE 1";
        if (!empty($id_plan_wording))
            $sQuery.=" AND id_plan_wording = '$id_plan_wording'";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
//if(!empty($anno)) $sQuery.=" AND anno = '$anno'";
        if (!empty($anno))
            $sQuery.=" AND year = '$anno'";
        if (!empty($id_status))
            $sQuery.=" AND id_status = '$id_status'";
        if (!empty($type_document))
            $sQuery.=" AND type_document in ($type_document)";
//echo $sQuery;
        $res = mysql_query($sQuery);
        $i = 0;
        while ($row = mysql_fetch_array($res)) {
            if ($row['type_document'] == 2 || $row['type_document'] == 3 || $row['type_document'] == 5 || $row['type_document'] == 7 || $row['type_document'] == 9) {
                if ($row['type_wording'] == 2) {
                    $arrResult[$i][type_document] = txtWordingType2;
                }
                if ($row['type_wording'] == 3) {
                    $arrResult[$i][type_document] = txtWordingType3;
                }
                if ($row['type_wording'] == 5) {
                    $arrResult[$i][type_document] = txtWordingType5;
                }
                if ($row['type_wording'] == 7) {
                    $arrResult[$i][type_document] = txtWordingType7;
                }
                if ($row['type_wording'] == 9) {
                    $arrResult[$i][type_document] = txtWordingType9;
                }
                if ($row['type_wording'] == 0 || $row['type_wording'] == '') {
                    $arrResult[$i][type_document] = txtWordingType2;
                }
            }
            else {
## $arrResult[$i][type_document] = 'Summary of Benefits';
## $arrResult[$i][type_document] = txtScheduleBenefits;
                if ($row['type_wording'] == 1) {
                    $arrResult[$i][type_document] = txtWordingType1;
                }
                if ($row['type_wording'] == 4) {
                    $arrResult[$i][type_document] = txtWordingType4;
                }
                if ($row['type_wording'] == 6) {
                    $arrResult[$i][type_document] = txtWordingType6;
                }
                if ($row['type_wording'] == 8) {
                    $arrResult[$i][type_document] = txtWordingType8;
                }
                if ($row['type_wording'] == 10) {
                    $arrResult[$i][type_document] = txtWordingType10;
                }
                if ($row['type_wording'] == 11) {
                    $arrResult[$i][type_document] = txtWordingType11;
                }
            }
            $arrResult[$i][id_plan_wording] = $row['id_plan_wording'];
            $arrResult[$i][url_document] = $row['url_document'];
            $arrResult[$i][type_wording] = $row['type_wording'];
            $arrResult[$i][year] = $row['year'];
            $arrResult[$i][anno] = $row['anno'];
            $i++;
        }
        $arrWordings = $this->Get_Plans_Riders('', $id_plan);
        for ($j = 0; $j < sizeof($arrWordings); $j++) {
            if (!empty($arrWordings[$j][wording])) {
                $this->get_plan_riders_master($arrWordings[$j]['rider']);
                $arrResult[$i][type_document] = $this->plan_rider_master;
                $arrResult[$i][url_document] = $arrWordings[$j]['wording'];
                $i++;
            }
        }
        return ($arrResult);
    }
    function Get_Plans_Wording_anno($id_plan_wording = '', $id_plan = '', $anno = '', $id_status = '', $type_document = '',  $language='') {
        $sQuery = "SELECT * FROM plans_wording WHERE 1";
        if (!empty($id_plan_wording))
            $sQuery.=" AND id_plan_wording = '$id_plan_wording'";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
        if (!empty($anno))
            $sQuery.=" AND year = '$anno'";
        if (!empty($id_status))
            $sQuery.=" AND id_status = '$id_status'";
        if (!empty($type_document))
            $sQuery.=" AND type_document = $type_document";
        if (!empty($language))
            $sQuery.=" AND language_id = '$language'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $sQuery);
    }
    function Get_Plans_Wording_idplan($id_plan = '', $idioma = '', $id_status = '') {
        $sQuery = "SELECT * FROM plans_wording WHERE 1 ";
       if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
       if (!empty($idioma))
            $sQuery.=" AND language_id = '$idioma'";
	   if (!empty($id_status))
            $sQuery.=" AND id_status = '$id_status'";
 			$sQuery.=" order by id_status";	
		 // var_dump($sQuery);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $sQuery);
    }
	function Get_Plans_Url_Wording_idplan($id_plan = '', $idioma = '', $id_status = '') {
        $sQuery = "SELECT url_document FROM plans_wording WHERE 1 ";
       if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
       if (!empty($idioma))
            $sQuery.=" AND language_id = '$idioma'";
	   if (!empty($id_status))
            $sQuery.=" AND id_status = '$id_status'";
 			$sQuery.=" order by id_status";	
		 // var_dump($sQuery);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $sQuery);
    }
    function Add_Plan_Wording($id_plan, $type_document, $url_document, $anno, $year, $idioma) {
		$id_plan = $id_plan?:0;   
		$type_document = $type_document?:0;   
		$anno = $anno?:0;   
        $query = "INSERT INTO plans_wording (id_plan, type_document, url_document, anno, id_status, year, language_id) VALUES ('$id_plan', $type_document, '$url_document', '$anno', '1', '$year', '$idioma')";
        $this->_SQL_tool($this->INSERT, __METHOD__, $query);
        /**-----------------------SOAP----------------------*/
          $obj_soapclient = new soapcliente();
          $obj_soapclient->add_soap('plans_wording','id_broker');
        /**-----------------------FIN-SOAP-------------------*/
    }
    function Delete_Plan_Wording_Plan($id_plan, $url_document) {
        $sQuery = "delete from plans_wording where  id_plan = '$id_plan' AND url_document='$url_document'";
        $this->_SQL_tool($this->DELETE, __METHOD__, $sQuery);
    }
    function Delete_Wording_Parameter($id_parameter, $url_document) {
        $sQuery = "delete from wording_parameter where  id_parameter = '$id_parameter' AND url_document='$url_document'";
        $this->_SQL_tool($this->DELETE, __METHOD__, $sQuery);
    }
    function Update_Plan_Wording($id_plan_wording, $type_document, $anno, $id_status, $year, $idioma) {
        $sQuery = "UPDATE plans_wording SET ";
        if (!empty($type_document))
            $sQuery.= " type_document = '$type_document'";
        if (!empty($anno))
            $sQuery.= ", anno = '$anno'";
        if (!empty($id_status))
            $sQuery.= ", id_status = '$id_status'";
        if (!empty($year))
            $sQuery.= ", year = '$year'";
        if (!empty($idioma))
            $sQuery.= ", language_id = '$idioma'";
        $sQuery.=" WHERE id_plan_wording = $id_plan_wording";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery);
        /**-----------------------SOAP----------------------*/
          $obj_soapclient = new soapcliente();
          $obj_soapclient->update_soap('plans_wording','id_plan_wording',$id_plan_wording,'');
        /**-----------------------FIN-SOAP-------------------*/
    }
    function Delete_Plan_Wording($id_plan_wording) {
        $sQuery = "delete from plans_wording where  id_plan_wording = $id_plan_wording";
        $this->_SQL_tool($this->DELETE, __METHOD__, $sQuery);
    }
    function get_plans_wording_type($id_plans_wording_type = '', $order = '', $min = '', $max = '') {
        $query = "SELECT * FROM plans_wording_type WHERE 1 ";
        if (!empty($id_plans_wording_type))
            $query.=" AND id_plans_wording_type = '$id_plans_wording_type'";
        if (!empty($order)) {
            $query .= " ORDER BY $order ";
        }
        if (!empty($max)) {
            $query .= " LIMIT $min,$max ";
        }
			// $query.=" order by id_status";	
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
// Plans Riders *********************************************************
    function Get_Plans_Riders($id_plan_rider = '', $id_plan = '', $id_status = '', $start_record = '', $page_size = '') {
        $sQuery = "SELECT * FROM plans_riders WHERE 1";
        if (!empty($id_plan_rider))
            $sQuery.=" AND id_plan_rider = '$id_plan_rider'";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
        $arrResult[0][totalREG] = wwexpa::get_total_reg($sQuery);
        if (!empty($id_status))
            $sQuery.=" AND id_status = '$id_status'";
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
//echo " Get_Plans_Riders => ".$sQuery."</br>";
        return cls_tools_db::select_tool($sQuery);
    }
    function Get_Plans_Riders_byDate($id_plan_rider = '', $id_plan = '', $effective_date = '', $id_status = '') {
        if (!empty($effective_date)) {
            $date = explode("-", $effective_date);
        }
        $sQuery2 = "SELECT effective_date FROM plans_riders WHERE 1";
        if (!empty($id_plan_rider))
            $sQuery2.=" AND id_plan_rider = '$id_plan_rider'";
        if (!empty($id_plan))
            $sQuery2.=" AND id_plan = '$id_plan'";
        if (!empty($id_status))
            $sQuery2.=" AND id_status = '$id_status'";
        if (!empty($effective_date))
            $sQuery2.=" AND (effective_date <= '$effective_date' OR (MONTH(effective_date) <= MONTH('$effective_date') AND YEAR(effective_date) <= YEAR('$effective_date')))";
        if (!empty($id_status))
            $sQuery2.=" AND id_status = '$id_status'";
        $sQuery2.=" ORDER BY effective_date DESC LIMIT 1";
//echo $sQuery2;
        $arrbydate = $this->select_tool($sQuery2);
        $sQuery = "SELECT * FROM plans_riders WHERE 1";
        if (!empty($id_plan_rider))
            $sQuery.=" AND id_plan_rider = '$id_plan_rider'";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
        $arrResult[0][totalREG] = wwexpa::get_total_reg($sQuery);
        if (!empty($id_status))
            $sQuery.=" AND id_status = '$id_status'";
        if (!empty($effective_date))
            $sQuery.=" AND effective_date = '" . $arrbydate[0]['effective_date'] . "'";
        if (!empty($id_status))
            $sQuery.=" AND id_status = '$id_status'";
        $sQuery.=" ORDER BY rider ASC";
//echo " Get_Plans_Rates_byDate => ".$sQuery;
        return $this->select_tool($sQuery);
    }
    function Get_Plans_RidersByPlanType($id_plan = '', $rider = '') {
        $sQuery = "SELECT * FROM plans_riders WHERE 1";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
        if (!empty($rider))
            $sQuery.=" AND rider in ($rider)";
        return $this->select_tool($sQuery);
    }
    function Get_Plans_RidersByPlanriderlist($id_plan = '', $list_riders = '') {
        if (!empty($list_riders)) {
            $sQuery = "SELECT * FROM plans_riders WHERE 1";
            if (!empty($id_plan))
                $sQuery.=" AND id_plan = '$id_plan'";
            if (!empty($list_riders))
                $sQuery.=" AND id_plan_rider in ($list_riders)";
            $sQuery.=" ORDER BY rider ASC";
//echo "</br> Get_Plans_RidersByPlanriderlist => ".$sQuery."</br>";
            return $this->select_tool($sQuery);
        }
    }
    function Get_Plans_Riders_By_Date($id_plan_rider2 = '', $id_plan = '', $date_Trans = '') {
        $arrPlans_Rider = $this->Get_Plans_Riders($id_plan_rider2, $id_plan);
        if (sizeof($arrPlans_Rider) > 0) {
            $arrPlans_Riders = $this->Get_Plans_RidersByPlanType($arrPlans_Rider[0]['id_plan'], $arrPlans_Rider[0]['rider']);
            $alert = "[Plan, Id Rider, Rider Poliza, dateTrans] : [" . $arrPlans_Rider[0]['id_plan'] . " ," . $id_plan_rider2 . "," . $arrPlans_Rider[0]['rider'] . "," . $date_Trans . "]\n";
            $alert .= "Registros : " . sizeof($arrPlans_Riders) . "\n";
            $alert .= "Diferencias : \n [id_plan_rider, rider, Effective date, Effective date next, date Trans, Diff 1, Diff 2, Conclussions]\n";
            for ($rider2 = 0; $rider2 < sizeof($arrPlans_Riders); $rider2++) {
                if (!empty($arrPlans_Riders[$rider2]['effective_date']) && $arrPlans_Riders[$rider2]['effective_date'] != "0000-00-00") {
                    $id_plan_rider_val = 0;
                    $break = 0;
                    $diff_1 = funciones::diffDate2($arrPlans_Riders[$rider2]['effective_date'], $date_Trans);
                    $rider_next = $rider2 + 1;
                    if (!empty($arrPlans_Riders[$rider_next]['effective_date'])) {
                        $diff_2 = funciones::diffDate2($arrPlans_Riders[$rider_next]['effective_date'], $date_Trans);
                    }
                    else {
                        $diff_2 = -1;
                        $break = -1;
                    }
                    if ($diff_1 == 0) {
                        $id_plan_rider_val = $arrPlans_Riders[$rider2]['id_plan_rider'];
                    }
                    elseif ($diff_1 > 0 && $diff_2 < 0) {
                        $id_plan_rider_val = $arrPlans_Riders[$rider2]['id_plan_rider'];
                    }
                    elseif ($diff_1 < 0 && $diff_2 < 0 && $break != -1) {
                        break;
//$id_plan_rider2 = $arrPlans_Riders[0]['id_plan_rider'];
                    }
                    $alert .= $rider . " => [" . $arrPlans_Riders[$rider2]['id_plan_rider'] . ", " . $arrPlans_Riders[$rider2]['rider'] . ", " . $arrPlans_Riders[$rider2]['effective_date'] . ", " . $arrPlans_Riders[$rider_next]['effective_date'] . ", " . $date_Trans . ", " . $diff_1 . ", " . $diff_2 . ", Result => " . $id_plan_rider_val . "]\n";
                }
            }
//echo "\n\n".$alert."\n";
            if ($id_plan_rider_val != 0) {
                $sQuery = "SELECT * FROM plans_riders WHERE 1";
                if (!empty($id_plan_rider2))
                    $sQuery.=" AND id_plan_rider = '$id_plan_rider2'";
                if (!empty($id_plan))
                    $sQuery.=" AND id_plan = '$id_plan'";
                $sQuery.=" ORDER BY rider, id_plan_rider ASC";
//echo "<\br>Get_Plans_Riders_By_Date => ".$sQuery."<\br>";
                return cls_tools_db::select_tool($sQuery);
            }
        }
    }
    //La tabla plans_riders no existe, funcion deberia ser borrada
  /*  function Add_Plans_Riders($id_plan, $rider, $id_rider_charge, $recurrence, $id_rider_covertype, $coverage_amount, $amount, $if_max_age, $max_age, $wording, $id_status, $effective_date) {
        $insert = "INSERT INTO plans_riders (id_plan, rider, id_rider_charge, recurrence, id_rider_covertype, coverage_amount, amount, if_max_age, max_age, wording, id_status, effective_date, id_user, date_modified) VALUES ('$id_plan', '$rider', '$id_rider_charge', '$recurrence', '$id_rider_covertype', '$coverage_amount', '$amount', '$if_max_age', '$max_age', '$wording', '$id_status', '$effective_date', '$_SESSION[id_user]', NOW())";
        return $this->ins_tool_return($insert);
    }*/
    //La tabla plans_riders no existe, funcion deberia ser borrada
  /*  function Update_Plans_Riders($id_plan_rider, $id_plan, $rider, $id_rider_charge, $recurrence, $id_rider_covertype, $coverage_amount, $amount, $if_max_age, $max_age, $wording, $id_status, $effective_date, $id_user, $date_modified) {
        $sQuery = "UPDATE plans_riders SET ";
        $sQuery.= "id_plan_rider = $id_plan_rider";
        if (!empty($id_plan))
            $sQuery.= ", id_plan = '$id_plan'";
        if (!empty($rider))
            $sQuery.= ", rider = '$rider'";
        if (!empty($id_rider_charge))
            $sQuery.= ", id_rider_charge = '$id_rider_charge'";
        if (!empty($recurrence))
            $sQuery.= ", recurrence = '$recurrence'";
        if (!empty($id_rider_covertype))
            $sQuery.= ", id_rider_covertype = '$id_rider_covertype'";
        if (!empty($coverage_amount))
            $sQuery.= ", coverage_amount = '$coverage_amount'";
        if (!empty($amount))
            $sQuery.= ", amount = '$amount'";
        if (!empty($if_max_age))
            $sQuery.= ", if_max_age = '$if_max_age'";
        if (!empty($max_age))
            $sQuery.= ", max_age = '$max_age'";
        if (!empty($wording))
            $sQuery.= ", wording = '$wording'";
        if (!empty($id_status))
            $sQuery.= ", id_status = '$id_status'";
        if (!empty($effective_date))
            $sQuery.= ", effective_date = '$effective_date'";
        if (!empty($id_user))
            $sQuery.= ", id_user = '$id_user'";
        if (!empty($date_modified))
            $sQuery.= ", date_modified = '$date_modified'";
        $sQuery.=" WHERE id_plan_rider = $id_plan_rider";
        $this->upt_tool($sQuery);
    }*/
    //La tabla plans_riders no existe, funcion deberia ser borrada
/*    function Delete_Plans_Riders($id_plan_rider) {
        $sQuery = "delete from plans_riders where id_plan_rider = $id_plan_rider";
        $this->del_tool($sQuery);
    }
    //La tabla plans_riders no existe, funcion deberia ser borrada
    function Get_Plans_Riders_id($id_plan_rider) {
        $sQuery = "SELECT * FROM plans_riders WHERE 1";
        if (!empty($id_plan_rider))
            $sQuery.=" AND id_plan_rider = '$id_plan_rider'";
        return $this->select_tool($sQuery);
    }
    //La tabla plans_riders no existe, funcion deberia ser borrada
    function Exits_Rider($id_plan_rider, $id_plan, $rider) {
        $sQuery = "SELECT * FROM plans_riders 
				WHERE id_plan_rider = '$id_plan_rider' 
				AND id_plan = '$id_plan' 
				AND rider in ($rider)";
        return $this->select_tool_Boolean($sQuery);
    }
    //La tabla plans_riders no existe, funcion deberia ser borrada
    function Clone_Riders($id_plan_rider) {
        $arrPlansRiders = $this->Get_Plans_Riders_id($id_plan_rider);
        $id_plan_rider_new = $this->Add_Plans_Riders($arrPlansRiders[0][id_plan], $arrPlansRiders[0][rider], $arrPlansRiders[0][id_rider_charge], $arrPlansRiders[0][recurrence], $arrPlansRiders[0][id_rider_covertype], $arrPlansRiders[0][coverage_amount], $arrPlansRiders[0][amount], $arrPlansRiders[0][if_max_age], $arrPlansRiders[0][max_age], $arrPlansRiders[0][wording], $arrPlansRiders[0][id_status], date("Y-m-d"));
        $arrRiderVariable = $this->Get_Plans_RidersVariable('', $arrPlansRiders[0][id_plan_rider]);
        for ($j = 0; $j < sizeof($arrRiderVariable); $j++) {
            $this->Add_Plans_RidersVariable($id_plan_rider_new, $arrRiderVariable[$j][individual], $arrRiderVariable[$j][couple], $arrRiderVariable[$j][family], $arrRiderVariable[$j][single_family], $arrRiderVariable[$j][id_status], $arrRiderVariable[$j][year]);
        }
        $arrRiderBrackets = $this->Get_Plans_RidersBrackets('', $arrPlansRiders[0][id_plan_rider]);
        for ($j = 0; $j < sizeof($arrRiderBrackets); $j++) {
            $this->Add_Plans_RidersBrackets($id_plan_rider_new, $arrRiderBrackets[$j][age_min], $arrRiderBrackets[$j][age_max], $arrRiderBrackets[$j][cost_factor], $arrRiderBrackets[$j][id_status], $arrRiderBrackets[$j][year]);
        }
    }
## Plans Riders Variable *********************************************************
    //La tabla plans_riders_variable no existe, funcion deberia ser borrada
    function Get_Plans_RidersVariable($id_plan_rider_variable = '', $id_plan_rider = '', $id_status = '', $start_record = '', $page_size = '') {
        $sQuery = "SELECT * FROM plans_riders_variable WHERE 1";
        if (!empty($id_plan_rider_variable))
            $sQuery.=" AND id_plan_rider_variable = '$id_plan_rider_variable'";
        if (!empty($id_plan_rider))
            $sQuery.=" AND id_plan_rider = '$id_plan_rider'";
        if (!empty($id_status))
            $sQuery.=" AND id_status = '$id_status'";
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
        return $this->select_tool($sQuery);
    }
    //La tabla plans_riders_variable no existe, funcion deberia ser borrada
    function Add_Plans_RidersVariable($id_plan_rider, $individual, $couple, $family, $single_family, $id_status, $anho, $female, $male) {
        $ddate = date("Y-m-d");
        if (empty($anho)) {
            $lastdate = explode("/", date("Y/m/d"));
            $year = $lastdate[0];
        }
        else {
            $year = $anho;
        }
        $insert = "INSERT INTO plans_riders_variable (id_plan_rider, individual, couple, family, single_family, id_status, year, female, male) VALUES ('$id_plan_rider', '$individual', '$couple', '$family', '$single_family', '$id_status', '$year', '$female', '$male')";
        return $this->ins_tool_return($insert);
    }
    //La tabla plans_riders_variable no existe, funcion deberia ser borrada
    function Update_Plans_RidersVariable($id_plan_rider_variable, $id_plan_rider, $individual, $couple, $family, $single_family, $id_status) {
        $sQuery = "UPDATE plans_riders_variable SET ";
        $sQuery.= "id_plan_rider_variable = $id_plan_rider_variable";
        if (!empty($id_plan_rider))
            $sQuery.= ", id_plan_rider = '$id_plan_rider'";
        if (!empty($individual))
            $sQuery.= ", individual = '$individual'";
        if (!empty($couple))
            $sQuery.= ", couple = '$couple'";
        if (!empty($family))
            $sQuery.= ", family = '$family'";
        if (!empty($single_family))
            $sQuery.= ", single_family = '$single_family'";
        if (!empty($id_status))
            $sQuery.= ", id_status = '$id_status'";
        $sQuery.=" WHERE id_plan_rider_variable = $id_plan_rider_variable";
        $this->upt_tool($sQuery);
    }
    //La tabla plans_riders_variable no existe, funcion deberia ser borrada
    function Update_Plans_RidersVariable_byPlanRider($id_plan_rider, $individual, $couple, $family, $single_family, $id_status, $year, $female, $male) {
        $sQuery = "UPDATE plans_riders_variable SET ";
        $sQuery.= "id_plan_rider = $id_plan_rider";
        if (!empty($individual))
            $sQuery.= ", individual = '$individual'";
        if (!empty($couple))
            $sQuery.= ", couple = '$couple'";
        if (!empty($family))
            $sQuery.= ", family = '$family'";
        if (!empty($single_family))
            $sQuery.= ", single_family = '$single_family'";
        if (!empty($id_status))
            $sQuery.= ", id_status = '$id_status'";
        if (!empty($year))
            $sQuery.= ", year = '$year'";
        if (!empty($female))
            $sQuery.= ", female = '$female'";
        if (!empty($male))
            $sQuery.= ", male = '$male'";
        $sQuery.=" WHERE id_plan_rider = $id_plan_rider";
        $this->upt_tool($sQuery);
    }
    //La tabla plans_riders_variable no existe, funcion deberia ser borrada
    function Delete_Plans_RidersVariable($id_plan_rider_variable) {
        $sQuery = "delete from plans_riders_variable where id_plan_rider_variable = $id_plan_rider_variable";
        $this->del_tool($sQuery);
    }
    //La tabla plans_riders_variable no existe, funcion deberia ser borrada
    function Delete_Plans_RidersVariablebyRider($id_plan_rider) {
        $sQuery = "delete from plans_riders_variable where id_plan_rider = $id_plan_rider";
        $this->del_tool($sQuery);
    }
    //La tabla plans_riders_variable no existe, funcion deberia ser borrada
    function Exits_Riders_Charge($id_plan_rider) {
        $sQuery = "SELECT * FROM plans_riders_variable WHERE id_plan_rider = $id_plan_rider";
        return $this->select_tool_Boolean($sQuery);
    }
// Plans Riders Brackets *********************************************************
    //La tabla plans_riders_brackets no existe, funcion deberia ser borrada
    function Get_Plans_RidersBrackets($id_plan_rider_brackets = '', $id_plan_rider = '', $id_status = '', $start_record = '', $page_size = '') {
        $sQuery = "SELECT * FROM plans_riders_brackets WHERE 1";
        if (!empty($id_plan_rider_brackets))
            $sQuery.=" AND id_plan_rider_brackets = '$id_plan_rider_brackets'";
        if (!empty($id_plan_rider))
            $sQuery.=" AND id_plan_rider = '$id_plan_rider'";
        if (!empty($id_status))
            $sQuery.=" AND id_status = '$id_status'";
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
        return $this->select_tool($sQuery);
    }
//La tabla plans_riders_brackets no existe, funcion deberia ser borrada
    function Get_Plans_RidersBracketsBetweenDate($id_plan_rider = '', $age = '') {
        $sQuery = "SELECT 
				*
				FROM
				plans_riders_brackets
				WHERE
				$age BETWEEN age_min and age_max
				and id_plan_rider = $id_plan_rider";
//echo "Get_Plans_RidersBracketsBetweenDate => ".$sQuery."</br>";
        return $this->select_tool($sQuery);
    }
    //La tabla plans_riders_brackets no existe, funcion deberia ser borrada
    function Add_Plans_RidersBrackets($id_plan_rider, $age_min, $age_max, $cost_factor, $id_status, $anho) {
        if (empty($anho)) {
            $lastdate = explode("/", date("Y/m/d"));
            $year = $lastdate[0];
        }
        else {
            $year = $anho;
        }
        $insert = "INSERT INTO plans_riders_brackets (id_plan_rider, age_min, age_max, cost_factor, id_status, year) VALUES ('$id_plan_rider', '$age_min', '$age_max', '$cost_factor', '$id_status', '$year')";
        return $this->ins_tool_return($insert);
    }
    //La tabla plans_riders_brackets no existe, funcion deberia ser borrada
    function Update_Plans_RidersBrackets($id_plan_rider_brackets, $id_plan_rider, $age_min, $age_max, $cost_factor, $id_status, $year) {
        $sQuery = "UPDATE plans_riders_brackets SET ";
        $sQuery.= "id_plan_rider_brackets = $id_plan_rider_brackets";
        if (!empty($id_plan_rider))
            $sQuery.= ", id_plan_rider = '$id_plan_rider'";
        if (!empty($age_min))
            $sQuery.= ", age_min = '$age_min'";
        if (!empty($age_max))
            $sQuery.= ", age_max = '$age_max'";
        if (!empty($cost_factor))
            $sQuery.= ", cost_factor = '$cost_factor'";
        if (!empty($id_status))
            $sQuery.= ", id_status = '$id_status'";
        if (!empty($year))
            $sQuery.= ", year = '$year'";
        $sQuery.=" WHERE id_plan_rider_brackets = $id_plan_rider_brackets";
        $this->upt_tool($sQuery);
    }
    //La tabla plans_riders_brackets no existe, funcion deberia ser borrada
    function Delete_Plans_RidersBrackets($id_plan_rider_brackets) {
        $sQuery = "delete from plans_riders_brackets where id_plan_rider_brackets = $id_plan_rider_brackets";
        $this->del_tool($sQuery);
    }
    //La tabla plans_riders_brackets no existe, funcion deberia ser borrada
    function Delete_Plans_RidersBracketsbyRider($id_plan_rider) {
        $sQuery = "delete from plans_riders_brackets where id_plan_rider = $id_plan_rider";
        $this->del_tool($sQuery);
    }
// Riders Charge *********************************************************
    //La tabla riders_charge no existe, funcion deberia ser borrada
    function Get_Riders_Charge($id_rider_charge = '') {
        $sQuery = "SELECT * FROM riders_charge WHERE 1";
        if (!empty($id_rider_charge))
            $sQuery.=" AND id_rider_charge = '$id_rider_charge'";
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
        return $this->select_tool($sQuery);
    }
// Riders Covertype *********************************************************
    //La tabla riders_covertype no existe, funcion deberia ser borrada
    function Get_Riders_Covertype($id_rider_covertype = '') {
        $sQuery = "SELECT * FROM riders_covertype WHERE 1";
        if (!empty($id_rider_covertype))
            $sQuery.=" AND id_rider_covertype = '$id_rider_covertype'";
        if (!empty($page_size))
            $sQuery.=" limit $start_record, $page_size";
        return $this->select_tool($sQuery);
    }
// Plans Contacts *********************************************************
    function Get_All_plans_contact_type($id_plan_contact_type = '') {
        $sQuery = "SELECT * FROM plans_contact_type where 1";
        if (!empty($id_plan_contact_type))
            $sQuery.= " and id_plan_contact_type = '$id_plan_contact_type'";
        return $this->select_tool($sQuery);
    }
    function Get_All_plans_contact($id_plan_contact = '', $id_plan = '', $id_plan_contact_type = '', $id_user = '', $id_group = '', $start_record = '', $page_size = '') {
        $sQuery = "SELECT * FROM plans_contact where 1";
        if (!empty($id_plan_contact))
            $sQuery.= " and id_plan_contact = '$id_plan_contact'";
        if (!empty($id_plan))
            $sQuery.= " and id_plan = '$id_plan'";
        if (!empty($id_plan_contact_type))
            $sQuery.= " and id_plan_contact_type in ('$id_plan_contact_type')";
        if (!empty($id_user))
            $sQuery.= " and id_user = '$id_user'";
        if (!empty($id_group))
            $sQuery.= " and id_group = '$id_group'";
        return $this->select_tool($sQuery);
    }
    function Add_plans_contact($id_plan, $id_plan_contact_type, $id_user, $id_group, $policy_distribution_start, $policy_distribution_end, $id_status, $begin_date, $end_date, $amount_limit) {
        $sQuery = "INSERT INTO plans_contact (id_plan, id_plan_contact_type, id_user, id_group, policy_distribution_start, policy_distribution_end , id_status, begin_date, end_date, amount_limit) VALUES ('$id_plan', '$id_plan_contact_type', '$id_user', '$id_group', '$policy_distribution_start', '$policy_distribution_end', '$id_status', '$begin_date', '$end_date', '$amount_limit')";
        return $this->ins_tool_return($sQuery);
    }
    function Update_plans_contact($id_plan_contact = '', $id_plan = '', $id_plan_contact_type = '', $id_user = '', $id_group = '', $policy_distribution_start = '', $policy_distribution_end = '', $id_status = '') {
        $sQuery = "UPDATE plans_contact SET id_plans_contact = id_plans_contact";
        if (!empty($id_plan_contact))
            $sQuery.= " , id_plan_contact = '$id_plan_contact'";
        if (!empty($id_plan))
            $sQuery.= " , id_plan = '$id_plan'";
        if (!empty($id_plan_contact_type))
            $sQuery.= " , id_plan_contact_type = '$id_plan_contact_type'";
        if (!empty($id_user))
            $sQuery.= " , id_user = '$id_user'";
        if (!empty($id_group))
            $sQuery.= " , id_group = '$id_group'";
        if (!empty($policy_distribution_start))
            $sQuery.= " , policy_distribution_start = '$policy_distribution_start'";
        if (!empty($policy_distribution_end))
            $sQuery.= " , policy_distribution_end = '$policy_distribution_end'";
        if (!empty($id_status))
            $sQuery.= " , id_status = '$id_status'";
        $sQuery.=" where id_plans_contact = id_plans_contact";
        $this->upt_tool($sQuery);
    }
    function Delete_plans_contactsbyID($id_plan_contact) {
        $sQuery = "delete from plans_contact where  id_plan_contact = $id_plan_contact";
        $this->del_tool($sQuery);
    }
    function Delete_plans_contacts($id_plan, $id_plan_contact_type) {
        $sQuery = "delete from plans_contact where  id_plan = $id_plan and $id_plan_contact_type = id_plan_contact_type";
        $this->del_tool($sQuery);
    }
    function get_plan_policy_by_user($id_user) {
        $sQuery = "SELECT plans_contact.id_plan_contact,
		plans_contact.id_plan,
		plans_contact.id_user,
		plans_contact.id_plan_contact_type,
		policy.id_policy
		FROM plans_contact
		Inner Join policy ON plans_contact.id_plan = policy.id_plan
		WHERE plans_contact.id_user =  $id_user";
        return $this->select_tool($sQuery);
    }
/*
    function delete_all_plans($id_plan) {
        $delete = "DELETE from plans where id_plan = '$id_plan'";
        $this->del_tool($delete);
        $delete = "DELETE from plans_benefits where id_plan = '$id_plan'";
        $this->del_tool($delete);
        $delete = "DELETE from plans_contact where id_plan = '$id_plan'";
        $this->del_tool($delete);
        $delete = "DELETE from plans_deductible where id_plan = '$id_plan'";
        $this->del_tool($delete);
        $delete = "DELETE from plans_options where id_plan = '$id_plan'";
        $this->del_tool($delete);
        $delete = "DELETE from plans_rates where id_plan = '$id_plan'";
        $this->del_tool($delete);
        $delete = "DELETE from plans_restrictions where id_plan = '$id_plan'";
        $this->del_tool($delete);
        $delete = "DELETE from plans_riders where id_plan = '$id_plan'";
        $this->del_tool($delete);
        $delete = "DELETE from plans_wording where id_plan = '$id_plan'";
        $this->del_tool($delete);
        $delete = "DELETE from groups_plans where id_plan = '$id_plan'";
        $this->del_tool($delete);
    }*/
/*
    function get_all_plan_riders_master($start_record = null, $page_size = null, $search = null) {
        $sQuery = "SELECT * FROM plans_rider_master WHERE 1";
        if (!empty($search))
            $sQuery.=" AND plan_rider_master like '%$search%'";
        $arrResult[0][totalREG] = wwexpa::get_total_reg($sQuery);
        if (!is_null($page_size))
            $sQuery.=" LIMIT $start_record, $page_size";
        return $this->select_tool($sQuery);
    }
    function get_plan_riders_master($id_plan_rider_master) {
        $sQuery = "SELECT * FROM plans_rider_master WHERE 1  ";
        if (!empty($id_plan_rider_master))
            $sQuery.= " AND id_plan_rider_master = $id_plan_rider_master";
        $this->select_tool_this($sQuery);
    }
##******** POLICY TYPE *************
    function get_all_policy_type() {
        $sQuery = "SELECT * FROM policy_type WHERE 1";
        return $this->select_tool($sQuery);
    }
*/
## Trae el benefit que este activo para el plan
    function Get_Plans_Benefits_act($id_plan_benefit = '', $id_plan = '', $id_benefit = '', $effective_date = '') {
        $sQuery = "SELECT * FROM plans_benefits WHERE 1 AND id_benefit_code <> 0";
        if (!empty($id_plan_benefit))
            $sQuery.=" AND id_plan_benefit = '$id_plan_benefit'";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan = '$id_plan'";
        if (!empty($id_benefit))
            $sQuery.=" AND id_benefit = '$id_benefit'";
        if (!empty($id_benefit))
            $sQuery.=" AND id_benefit_code = '$id_benefit'";
        if (!empty($effective_date))
            $sQuery.=" AND effective_date >= '$effective_date' AND inactive_date <= '$effective_date'";
        $sQuery.= " order by id_plan_benefit ASC";
        return $this->select_tool($sQuery);
    }
    function Get_All_plans_contact_by_user($id_plan_contact = '', $id_plan = '', $id_plan_contact_type = '', $id_user = '', $id_group = '') {
        $sQuery = "SELECT * FROM plans_contact where 1";
        if (!empty($id_plan_contact))
            $sQuery.= " and id_plan_contact in ('$id_plan_contact')";
        if (!empty($id_plan))
            $sQuery.= " and id_plan in ('$id_plan')";
        if (!empty($id_plan_contact_type))
            $sQuery.= " and id_plan_contact_type in ('$id_plan_contact_type')";
        if (!empty($id_user))
            $sQuery.= " and id_user in ('$id_user')";
        if (!empty($id_group))
            $sQuery.= " and id_group in ($id_group')";
        return $this->select_tool($sQuery);
    }
    function get_claims_by_benefitcode($id_benefit_coding = '') {
        $query = "SELECT * FROM claims_adj WHERE 1=1 ";
        if (!empty($id_benefit_coding))
            $query.=" AND id_benefit_coding = '$id_benefit_coding'";
        return $this->select_tool($query);
    }
    function Get_PlansbyRestrictionsByGroup($type_restrictions = '', $id_relation = '', $id_plan = '') {
        $sQuery = "SELECT DISTINCT plans.* FROM plans Inner Join plans_restrictions ON plans.id_plan = plans_restrictions.id_plan WHERE plans_restrictions.type_restrictions =  $type_restrictions and plans_restrictions.id_relation =  $id_relation";
        if (!empty($id_plan))
            $sQuery.=" AND plans.id_plan in ($id_plan) ";
//echo $sQuery;
        return $this->select_tool($sQuery);
    }
    function get_plan_wording_Max_EffectiveDate($id_plan) {
        $query = "SELECT MAX(anno) as eff FROM plans_wording WHERE id_plan='$id_plan' and id_status=1";
        return $this->select_tool($query);
    }
    function get_plan_wording_Max_EffectiveDate_All($anno, $id_plan) {
        $query = "SELECT * FROM plans_wording WHERE anno='$anno' AND id_plan='$id_plan'";
        return $this->select_tool($query);
    }
//PLAN PREMIUM
    function Add_Premium($tiempo, $unidad, $valor, $adicional, $tax1, $show1, $tax2, $show2, $idplans, $cost, $id_country, $renewal, $cost_audit) {
		$tiempo = $tiempo?:0;
		$cost_audit = $cost_audit?:0; 
		$renewal = $renewal?:0;
		$valor = $valor?:0;   
		$adicional = $adicional?:0;   
		$tax1 = $tax1?:0;   
		$tax2 = $tax2?:0;   
		$idplans = $idplans?:0; 
		$cost = $cost?:0;  
		$cost_audit = $cost_audit?:0;
       $query = "INSERT INTO plan_times (tiempo, unidad, valor, adicional, tax1, show1, tax2, show2, id_plan, cost, id_country, renewal, cost_audit) VALUES ('$tiempo', '$unidad', '$valor', '$adicional', '$tax1', '$show1', '$tax2', '$show2', '$idplans', '$cost', '$id_country', '$renewal', '$cost_audit')";
		//die();
        $this->_SQL_tool($this->INSERT, __METHOD__, $query);
    }
    function Get_Plans_Time($id = '') {
        $query = "SELECT * FROM plan_times WHERE 1";
        if (!empty($id))
            $query.=" AND id = '$id'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
//return $this->select_tool($sQuery);
    }
    function Update_Premium($tiempo, $unidad, $valor, $adicional, $tax1, $show1, $tax2, $show2, $id, $cost, $id_country, $renewal, $cost_audit) {
		$tax1 = $tax1?:0; 
		$tax2 = $tax2?:0; 
        $sQuery = "update plan_times set tiempo = '$tiempo', unidad='$unidad', valor='$valor', adicional='$adicional', tax1='$tax1', show1='$show1', tax2='$tax2', show2='$show2', cost='$cost', id_country='$id_country', renewal='$renewal', cost_audit='$cost_audit' where  id = '$id'";
//var_dump($sQuery);
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery);
    }
    function Delete_Premium($id) {
        $sQuery = "DELETE from plan_times WHERE id='$id'";
        $this->_SQL_tool($this->DELETE, __METHOD__, $sQuery);
    }
//COSTO DEL PLAN
    /*function Add_Costo_Plan($free_1, $name, $id_provider, $value, $type_porc, $id, $id_raider, $pvp_raider, $included, $typepvp) {
        $query = "INSERT INTO costo_plan (free_1, name_costo, id_provider, value_costo, type_porc, id_plan, id_raider, pvp_raider, included, typepvp, created) VALUES ('$free_1', '$name', '$id_provider', '$value', '$type_porc', '$id', '$id_raider', '$pvp_raider', '$included', '$typepvp', NOW())";
        $this->_SQL_tool($this->INSERT, __METHOD__, $query);
    }*/
//LOS DOCUMENTOS QUE VIENEN DEL CLONE
    function Add_planswording($id_plan, $type_document, $url_document, $anno, $id_status, $type_wording, $year) {
		$id_plan = $id_plan?:0;   
		$type_document = $type_document?:0;   
		$anno = $anno?:0;   
		$id_status = $id_status?:0;   
		$type_wording = $type_wording?:0;   
        $query = "INSERT INTO plans_wording (id_plan, type_document, url_document, anno, id_status, year, type_wording) VALUES ('$id_plan', $type_document, '$url_document', '$anno', '$id_status', '$year', '$type_wording')";
        $this->_SQL_tool($this->INSERT, __METHOD__, $query);
        /**-----------------------SOAP----------------------*/
          $obj_soapclient = new soapcliente();
          $obj_soapclient->add_soap('plans_wording','id_plan_wording');
        /**-----------------------FIN-SOAP-------------------*/
    }
    function Get_Plans_Costo($id_plan) {
        $query = "SELECT costo_plan.*, providers.name_provider FROM costo_plan Inner Join providers ON costo_plan.id_provider = providers.id_provider WHERE costo_plan.id_plan = '$id_plan'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
   /* function Get_Plans_Costo_Planl($id = '') {
        $query = "SELECT * FROM costo_plan WHERE id = '$id'";
// die($query."asd");
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
//return $this->select_tool($sQuery);
    }
*/
   /* function Update_Costo_Plan($free_1, $name, $id_provider, $value, $type_porc, $id, $id_raider, $pvp_raider, $included, $typepvp) {
        $sQuery = "update costo_plan set free_1='$free_1', name_costo='$name', id_provider='$id_provider', value_costo='$value', type_porc='$type_porc', id_raider='$id_raider', pvp_raider='$pvp_raider', included='$included', typepvp='$typepvp' where  id = '$id'";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery);
//die($sQuery);
    }*/
    function Update_Plan_Benefits_Order($recordIDValue, $id_benefit, $id_plan) {
        $sQuery = "update benefit_plan set orden='$recordIDValue' where  id = '$id_benefit' AND id_plan='$id_plan'";
        //echo $sQuery;
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery);
        /**-----------------------SOAP----------------------*/
          $obj_soapclient = new soapcliente();
          $obj_soapclient->update_soap('benefit_plan', 'id', $id_benefit, '' );
        /**-----------------------FIN-SOAP-------------------*/
    }
	function Update_Plans_Order($recordIDValue, $id_plan, $id_categoria) {
        $sQuery = "update plans set orden='$recordIDValue' where  id = '$id_plan' AND id_plan_categoria='$id_categoria'";
        //echo $sQuery;
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery);
        /**-----------------------SOAP----------------------*/
          $obj_soapclient = new soapcliente();
          $obj_soapclient->update_soap('plans', 'id', $id_plan, '' );
        /**-----------------------FIN-SOAP-------------------*/
    }
    function Delete_Costo_Plan($id) {
        $sQuery = "DELETE from costo_plan WHERE id='$id'";
        $this->_SQL_tool($this->DELETE, __METHOD__, $sQuery);
    }
    function Verifica_IdPlanProvider($provideridplan = '', $idplanprov = '') {
        $query = "SELECT * FROM plans WHERE 1";
        if (!empty($provideridplan))
            $query.=" AND provider_id_plan='$provideridplan'";
        if (!empty($idplanprov))
            $query.=" AND id!='$idplanprov'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_Plans_Provider($id_provider = '') {
        $query = "SELECT * FROM plans WHERE 1";
        if (!empty($id_provider))
            $query.=" AND id_provider = '$id_provider'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Add_order_Comision($id_order = '', $id_broker = '', $porcentage = 0, $monto_comision = 0, $codigo_final='') {
		$id_order = $id_order?:0; 
		$id_broker = $id_broker?:0; 
		$porcentage = $porcentage?:0; 
		$monto_comision = $monto_comision?:0; 
        $query = "INSERT INTO order_comision (id_order, id_broker, porcentage, monto_comision, tr_date) VALUES ('$id_order', '$id_broker', '$porcentage', '$monto_comision', NOW())";
//        var_dump($query); die;
        $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert order comision <-> idOrder:' . $id_order,'','','_DEFAULT', $codigo_final);
        $this->id = $result;
        /**-----------------------SOAP----------------------*/
            $obj_soapclient = new soapcliente();
            $obj_soapclient->add_soap('order_comision','id');
        /**-----------------------FIN-SOAP-------------------*/
    }
    function Get_All_Plan_Corp($id_plan = '', $tipo = '', $status = '') {
        $sQuery = "SELECT * FROM plans WHERE 1";
        if (!empty($id_plan))
            $sQuery.=" AND id='$id_plan'";
        if (!empty($tipo))
            $sQuery.=" AND id_plan_categoria='$tipo'";
        if (!empty($status)){
            $sQuery.=" AND activo='$status'";
            if($status==1){
                $sQuery.=" AND eliminado = 1 ";
            }
        } 
        return $this->_SQL_tool($this->SELECT, METHOD, $sQuery);
    }
    function Get_All_Plan_Times($id_plan = '', $tiempo = '') {
        $sQuery = "SELECT * FROM plan_times WHERE 1";
        if (!empty($id_plan))
            $sQuery.=" AND id_plan='$id_plan'";
        if (!empty($tiempo))
            $sQuery.=" AND tiempo='$tiempo'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $sQuery);
    }
    function add_plan_message($id_plan, $message_plan, $language_id, $titlo, $description) {
		$id_plan = $id_plan?:0; 
        $query = "INSERT INTO plan_detail (plan_id, message_plan, language_id, titulo, description, created, modified) VALUES ('$id_plan', '$message_plan', '$language_id', '$titlo', '$description', NOW(), NOW())";
        $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert Message Plan <-> Plan Id:' . $id_plan);
        /**-----------------------SOAP----------------------*/
            $obj_soapclient = new soapcliente();
            $obj_soapclient->add_soap('plan_detail','id');
        /**-----------------------FIN-SOAP-------------------*/
    }
    #FUNCION PARA GUARDAR LOS TEXTO DE LOS TELEFONO DE LOS PLANES
    function AddTextPlansTlf($id_plan, $message_plan_tlf, $language_id) {
$id_plan = $id_plan?:0; 
        $query = "INSERT INTO phone_plans_text (plan_id, message_plan_tlf, language_id, created, modified) VALUES ('$id_plan', '$message_plan_tlf', '$language_id', NOW(), NOW())";
        $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert Message  Phone Plan <-> Plan Id:' . $id_plan);
    }
	  //  $query = "INSERT INTO plan_detail (plan_id, message_plan, language_id, titulo, description, created, modified) VALUES ('$id_plan', '$message_plan', '$language_id', '$titlo', '$description', NOW(), NOW())";
    function Update_plan_message($id_plan, $message_plan, $language_id, $titulo, $description) {
		$id_plan = $id_plan?:0; 
        $query = "SELECT * FROM plan_detail  WHERE plan_id='$id_plan' and language_id='$language_id'";
        $res_array = $this->_SQL_tool($this->SELECT, __METHOD__, $query);
        if ($res_array) {
            $query = "UPDATE plan_detail SET message_plan='" . $message_plan . "', titulo='$titulo', description='$description' WHERE language_id='" . $language_id . "' AND plan_id='$id_plan'";
            $this->_SQL_tool($this->UPDATE, __METHOD__, $query, 'Modified Message Plan: ' . $id_plan);
            /**-----------------------SOAP----------------------*/
                $obj_soapclient = new soapcliente();
                $obj_soapclient->update_soap('plan_detail','plan_id', $id_plan, $language_id);
            /**-----------------------FIN-SOAP-------------------*/
        }
        else {
            $query = "INSERT INTO plan_detail (plan_id, message_plan, language_id, titulo, description, created, modified) VALUES ('$id_plan', '$message_plan', '$language_id', '$titulo', '$description', NOW(), NOW())";	 
            $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert Plan Category <-> name:' . $name_plan);
            /**-----------------------SOAP----------------------*/
            $obj_soapclient = new soapcliente();
            $obj_soapclient->add_soap('plan_detail','id');
         /**-----------------------FIN-SOAP-------------------*/
        }
    }
    function Get_orden_reider($idorden) {
        $query = "SELECT Sum(orders_raider.value_raider) as totalRaider FROM orders_raider WHERE id_orden='" . $idorden . "' ";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    #BUSCO LOS TELEFONO ESPECIALES DEL PLANS
    function GetTlfPlans($id) {
        $query = "SELECT * FROM phone_plans WHERE plans_id='" . $id . "' ";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    #BUSCO EL ULTIMO DOCUMENTO AGREGADO
    function MaxIdWornig($id) {
        $query = "SELECT MAX(id_plan_wording) AS IdWornig FROM plans_wording WHERE id_plan='" . $id . "' ";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    #ELIMINO EL TELEFONO INDIVIDUAL PLAN
    function DeleteTlfPlans($id_phone) {
        $sQuery = "DELETE from phone_plans WHERE id_phone='$id_phone'";
        $this->_SQL_tool($this->DELETE, __METHOD__, $sQuery);
    }
    #ELIMINO TODOS LOS TELEFONO DEL PLAN
    function DeleteTlfPlanes($plans_id) {
        $sQuery = "DELETE from phone_plans WHERE plans_id='$plans_id'";
        $this->_SQL_tool($this->DELETE, __METHOD__, $sQuery);
    }
    #ELIMINO TODOS LOS TEXTO DE TELEFONO DEL PLAN
    function DeleteTextPlanes($plan_id) {
        $sQuery = "DELETE from phone_plans_text WHERE plan_id='$plan_id'";
        $this->_SQL_tool($this->DELETE, __METHOD__, $sQuery);
    }
    #BUSCO TIPO DE DOCUMENTO
    function GettypeDocument($id_document) {
        $query = "SELECT * FROM plans_wording_type WHERE id_plans_wording_type='" . $id_document . "' ";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    #BUSCO LA ETIQUETA
    function GetEtiqueta($id_label, $language_id) {
        $query = "SELECT * FROM resource_label_values WHERE id_label='$id_label' AND language_id='$language_id'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    #BUSCO LOS PLANES PARA ASIGNARLE LA AGENCIA
   function GetPlansAgencia($id_plans='', $lenguaje='', $OrderBy='') {
        $query = "SELECT
                    plan_times.id,
                    plan_times.id_plan,
                    plan_times.tiempo,
                    plan_times.unidad,
                    plan_times.valor,
                    plan_times.cost,
                    plan_times.id_country,
                    plan_detail.titulo,
                    plan_detail.language_id
                    FROM
                    plan_times
                    INNER JOIN plan_detail ON plan_times.id_plan = plan_detail.plan_id WHERE 1";
        if (!empty($id_plans))
            $query .= "  AND plan_times.id_plan='$id_plans'";
        if (!empty($lenguaje))
            $query .= "  AND plan_detail.language_id='$lenguaje'";
		if (!empty($OrderBy))
            $query .= "  ORDER BY
							plan_times.id_country ASC,
							plan_times.tiempo ASC";
     //  var_dump($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	/*
function Add_Preorden_select($usuario_id,$id_plan_categoria,$salida,$retorno,$origen, $destino,$cantidad_pasajero,$nacimiento1,$nacimiento2,$nacimiento3,$nacimiento4,$nacimiento5,$nacimiento6,$nacimiento7,$nacimiento8,$nacimiento9) {
        $query = "INSERT INTO preorder_select(usuario_id,id_plan_categoria,salida,retorno,origen, destino,cantidad_pasajero,nacimiento1,nacimiento2,nacimiento3,nacimiento4,nacimiento5,nacimiento6,nacimiento7,nacimiento8,nacimiento9, fecha  ) VALUES('$usuario_id','$id_plan_categoria','$salida','$retorno','$origen','$destino','$cantidad_pasajero','$nacimiento1','$nacimiento2','$nacimiento3','$nacimiento4','$nacimiento5','$nacimiento6','$nacimiento7','$nacimiento8','$nacimiento9', now())";
       // die($query."ss");
        $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert preorder_select <-> user:' . $usuario_id);
        return $result;
    }
*/
    function Buscar_raiders_plan($search= '', $Lenguage_Session_users='', $nomb_raider= '', $titulo='', $min = '', $max = '', $id_broker='', $id_agencia = ''){
        $query="SELECT
                    raiders.id_status,
                    raiders_detail.name_raider,
                    raiders_detail.language_id,
                    plan_raider.id_raider,
                    IFNULL(
                        plans.`name`,
                        plan_detail.titulo
                    ) AS titulo
                FROM
                    raiders
                INNER JOIN raiders_detail ON raiders.id_raider = raiders_detail.id_raider
                INNER JOIN plan_raider ON plan_raider.id_raider = raiders.id_raider
                INNER JOIN plans ON plans.id = plan_raider.id_plan
                INNER JOIN plan_detail ON plan_detail.plan_id = plans.id
                LEFT JOIN restriction ON plans.id = restriction.id_plans
                WHERE
                    1";
            if (!empty($id_broker))
                $query.=" AND ((restriction.id_broker in ($id_broker) and restriction.dirigido='6') or (restriction.dirigido='2' and restriction.id_broker = '$id_agencia'))";
            if(!empty($search))
                $query.=" AND raiders_detail.name_raider like '%$search%' OR plan_detail.titulo like '%$search%'";
            if(!empty($Lenguage_Session_users))
                $query.=" AND raiders_detail.language_id = '$Lenguage_Session_users'";
                $query.=" AND plan_detail.language_id = '$Lenguage_Session_users'";
            if(!empty($nomb_raider))
                $query.=" AND raiders_detail.name_raider LIKE '%$nomb_raider%'";
                $query.=" AND raiders.id_status=1";
                $query.=" AND plans.activo = 1";
                $query.=" AND plans.eliminado = 1";
            if(!empty($titulo))
                $query.=" AND plan_detail.titulo LIKE '%$titulo%'";
                $query.=" ORDER BY plan_detail.titulo";
             if (!empty($max))
            $query .= " LIMIT $min,$max ";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function buscar_raiders_voucher($search='', $name_raider='', $salida = '', $retorno ='', $Lenguage_Session_users='', $min = '', $max = '', $listabroker= '', $id_usuario = ''){
        $query="SELECT
                raiders_detail.name_raider,
                raiders_detail.language_id,
                raiders.id_status,
                orders_raider.id_orden,
                orders_raider.id_raider,
                orders.codigo,
                orders.salida,
                orders.retorno,
                orders.fecha
            FROM
                raiders
            INNER JOIN orders_raider ON raiders.id_raider = orders_raider.id_raider
            INNER JOIN orders ON orders.id = orders_raider.id_orden
            INNER JOIN raiders_detail ON raiders_detail.id_raider = raiders.id_raider
            WHERE
                1";
        if(!empty($search))
            $query.=" AND raiders_detail.name_raider like '%$search%'";
        if(!empty($name_raider))
            $query.=" AND raiders_detail.name_raider = '$name_raider'";
        if (!empty($listabroker))
            $query.=" AND orders.agencia IN ($listabroker)";
        if (!empty($id_usuario))
            $query .= " AND orders.vendedor IN ($id_usuario)";
        if(!empty($salida))
            $query.= " AND orders.salida>='$salida' and orders.retorno<='$retorno'";
        if(!empty($Lenguage_Session_users))
            $query.=" AND raiders_detail.language_id = '$Lenguage_Session_users'";
        $query.=" AND raiders.id_status =1";
        $query.=" ORDER BY orders.fecha DESC";
        if(!empty($max))
            $query.=" LIMIT $min,$max";
         //die($query);
        //var_dump($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function select_min_tiempo_planes($id_plan_categoria){
        $query = "SELECT DISTINCT min_tiempo FROM plans WHERE id_plan_categoria = '$id_plan_categoria'
        AND activo = 1 AND eliminado = 1 ORDER BY min_tiempo ASC";
        return $this->_SQL_tool($this->SELECT, METHOD, $query);
    }
    function add_error_order($order,$data){
		$data	 = $this-> eliminar_caracteres_prohibidos($data); 
		$query = "Insert into order_error_save_data (id_order,data_save) values ('$order','$data')";
        $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query);
        return $result;
    }
    function set_error_order($order=''){
        $query = "Select * from order_error_save_data where id_order = '$order' ";
        return $this->_SQL_tool($this->SELECT, METHOD, $query);
    }
    function delete_error_order($order=''){
        $query = "DELETE from order_error_save_data WHERE id_order='$order'";
        $this->_SQL_tool($this->DELETE, __METHOD__, $query);
    }
	function Add_beneficiarios_contacts($id_beneficiaries, $nombre, $email, $telefono){
       $id_beneficiaries = $id_beneficiaries?:0; 
        $query = "INSERT INTO beneficiaries_contacts(id_beneficiaries,nombre,email,telefono) VALUES('$id_beneficiaries','$nombre','$email','$telefono')";
        $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert beneficiaries_contacts <-> beneficiaries:' . $id_beneficiaries);
         return $result;
        /**-----------------------SOAP----------------------*/
        //    $obj_soapclient = new soapcliente();
        //    $obj_soapclient->add_soap('beneficiaries','id');
         /**-----------------------FIN-SOAP-------------------*/
    }
	function Add_beneficiarios_contacts_multiples($array_beneficiarios_contacts, $id_primer_beneficiario){
		 $query = "INSERT INTO beneficiaries_contacts(id_beneficiaries,nombre,email,telefono) VALUES";
		 $contador_beneficiarios=$id_primer_beneficiario ; 
        foreach ($array_beneficiarios_contacts as $value) {
			$id_beneficiaries=$contador_beneficiarios;
			$nombre=$value['nombre'];
			$email=$value['email'];
			$telefono=$value['telefono'];
			$query.="('$id_beneficiaries','$nombre','$email','$telefono'),"; 
			$contador_beneficiarios++;
        }
        $query = substr($query, 0, -1);
		   $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert banficiario <-> id orden:' . $idorden,'','','_DEFAULT',$codigo_final);
        $this->id = $result;
        /**-----------------------SOAP----------------------*/
            $obj_soapclient = new soapcliente();
             $obj_soapclient->add_soap('beneficiaries','id');
         /**-----------------------FIN-SOAP-------------------*/
        return $result;
    }
	 function Get_orden_exist($idorden) {
       $query = "SELECT id FROM orders WHERE id='" . $idorden . "' ORDER BY id DESC LIMIT 1";
	// die($query. "ESTA ES EL SQUERY");
	  //$query = "SELECT id FROM orders ORDER BY id DESC LIMIT 1";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	 function Get_orden_by_like($idorden, $limit) {
       $query = "SELECT
                orders.id,
                orders.codigo,
                orders.mostrar_carnet,
                orders.id_client,
                orders.producto,
                orders.agencia,
                orders.origen,
                orders.territory,
                orders.vendedor,
                orders.status,
                orders.salida,
                orders.retorno,
                orders.tiempo_x_producto,
                orders.fecha,
                orders.alter_cur,
                orders.tasa_cambio,
                orders.total,
				orders.total_tax,
				orders.total_tax_mlc,
				orders.total_tax_2,
				orders.total_tax2_mlc,
                orders.nombre_contacto,
                orders.telefono_contacto,
                orders.email_contacto,
                orders.credito_tipo,
                orders.credito_expira,
                orders.credito_nombre,
                orders.credito_numero,
                orders.v_authorizado,
                orders.comentarios,
                orders.comentario_medicas,
				orders.id_emision_type,
                beneficiaries.nombre,
                beneficiaries.apellido,
                beneficiaries.email,
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
	) AS eldestino 
                FROM
                orders
                INNER JOIN beneficiaries ON orders.id = beneficiaries.id_orden
                WHERE
                orders.codigo LIKE '%$idorden%'";
	  if(!empty($limit))
            $query.=" LIMIT $limit ";
        //die($query);
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_plan_band_age_country($id = '', $id_plan = '', $id_country = '', $order = '' ) {
        $query = "SELECT
                    plan_band_age.*,
                    countries.iso_country,
                    countries.description
                    FROM
                    plan_band_age
                    INNER JOIN countries ON plan_band_age.id_country = countries.iso_country
                    WHERE plan_band_age.id_country <> 'all'";
        if (!empty($id))
            $query.=" AND id = '$id'";
        if (!empty($id_plan))
            $query.=" AND id_plan = '$id_plan'"; 
        if (!empty($id_country))
            $query.=" AND id_country = '$id_country'";
        if (!empty($order))
            $query.=" ORDER BY $order"; 
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
 /*function Validar_RangoEdad($minage = '',$plan='',$banda='') {
        $query = "SELECT * FROM `plan_band_age`  WHERE
'$minage' <=  plan_band_age.age_max AND
'$minage' >=  plan_band_age.age_min AND
plan_band_age.id_country =  'all' AND plan_band_age.id_plan='$plan'";
	 if(!empty($banda)){
			$query.="AND plan_band_age.id<>'$banda'";
			}
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }*/
/* function Validar_RangoEdad2($maxage = '',$plan='',$banda='') {
        $query = "SELECT * FROM `plan_band_age`  WHERE
'$maxage' <=  plan_band_age.age_max AND
'$maxage' >=  plan_band_age.age_min AND
plan_band_age.id_country =  'all' AND plan_band_age.id_plan='$plan'";
		if(!empty($banda)){
			$query.="AND plan_band_age.id<>'$banda'";
			}
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	*/
 function Validar_RangoEdad3($renewal='',$minage='',$maxage = '',$plan='',$banda='') {
        $query = "SELECT id
FROM
 plan_band_age
WHERE 1  and 
 ('$minage'<= plan_band_age.age_max
  and '$maxage'>= plan_band_age.age_min)  
 
   
AND plan_band_age.id_country = 'all'
AND plan_band_age.id_plan = '$plan' 
AND plan_band_age.renewal = '$renewal'";
		if(!empty($banda)){
			$query.="AND plan_band_age.id<>'$banda'";
			}
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	/*function Validar_RangoEdad_Pais($minage = '', $country='',$plan='',$banda='') {
        $query = "SELECT * FROM `plan_band_age`  WHERE
'$minage' <=  plan_band_age.age_max AND
'$minage' >=  plan_band_age.age_min AND
plan_band_age.id_country =  '$country'  AND plan_band_age.id_plan='$plan'";
		if(!empty($banda)){
			$query.="AND plan_band_age.id<>'$banda'";
			}
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	*/
	/*function Validar_RangoEdad_Pais2($maxage = '', $country='',$plan='',$banda='') {
        $query = "SELECT * FROM `plan_band_age`  WHERE
'$maxage' <=  plan_band_age.age_max AND
'$maxage' >=  plan_band_age.age_min AND
plan_band_age.id_country =  '$country'  AND plan_band_age.id_plan='$plan'";
	if(!empty($banda)){
			$query.="AND plan_band_age.id<>'$banda'";
			}
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }*/
function Validar_RangoEdad_Pais3($renewal='', $minage='', $maxage = '', $country='',$plan='',$banda='') {
          $query = "SELECT id
FROM
 plan_band_age
WHERE 1  and 
 ('$minage'<= plan_band_age.age_max
  and '$maxage'>= plan_band_age.age_min)    
AND plan_band_age.id_country = '$country'
AND plan_band_age.id_plan = '$plan' 
AND plan_band_age.renewal = '$renewal'";
		if(!empty($banda)){
			$query.="AND plan_band_age.id<>'$banda'";
			}
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function Get_plan_band_age($id = '', $id_plan = '', $id_country = '', $order = '', $renewal = '' ) {
        $query = "SELECT
                    *
                    FROM  
                    plan_band_age";  
        if (!empty($id_country) && $id_country!='all')
            $query.=" INNER JOIN countries ON plan_band_age.id_country = countries.iso_country";
         $query.=" WHERE 1";
        if (!empty($id))
            $query.=" AND id = '$id'";
        if (!empty($id_plan))
            $query.=" AND id_plan = '$id_plan'"; 
        if (!empty($id_country))
            $query.=" AND id_country = '$id_country'";
        if (!empty($renewal))
            $query.=" AND renewal = '$renewal'";
        if (!empty($order))
            $query.=" ORDER BY $order"; 
      //var_dump($query);
      //die();
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	 function Get_plan_band_age_valor($edad = '', $id_plan = '', $id_country = '') {
        $query = "SELECT
					plan_band_age.cost,
					plan_band_age.precio_base,
					plan_band_age.precio_base_cost,
					plan_band_age.valor,
					plan_band_age.id
					FROM `plan_band_age`
					WHERE 
					plan_band_age.age_min <= $edad and
					plan_band_age.age_max >= $edad AND
					plan_band_age.id_plan = $id_plan AND
					plan_band_age.id_country = '$id_country'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	function Update_plan_band_age($id = '', $id_plan = '', $valor = '', $cost = '', $tax1 = '',$show1= '', $tax2 = '', $show2 = '', $id_country = '', $neto_ag = '', $age_min = '', $age_max = '', $precio_base = '', $precio_base_cost = '', $renewal = '', $cost_audit = '', $tiempo='', $unidad='', $adicional='') {
        $sQuery = "UPDATE plan_band_age SET ";
        if(!empty($id)){
            $sQuery.= " id = '$id'";
        }
        if(!empty($id_plan)){
            $sQuery.= ", id_plan = '$id_plan'";
        }
        if(!empty($valor)){
            $sQuery.= ", valor = '$valor'";
        }
        if(!empty($cost)){
            $sQuery.= ", cost = '$cost'";
        }
        if(!empty($tax1)){
            $sQuery.= ", tax1 = '$tax1'";
        }
        if(!empty($show1)){
            $sQuery.= ", show1 = '$show1'";
        }
        if(!empty($tax2)){
            $sQuery.= ", tax2 = '$tax2'";
        }
        if(!empty($show2)){
            $sQuery.= ", show2 = '$show2'";
        }   
        if(!empty($id_country)){
            $sQuery.= ", id_country = '$id_country'";
        }
        if(!empty($neto_ag)){
            $sQuery.= ", neto_ag = '$neto_ag'";
        }
        if(!empty($age_min)){
            $sQuery.= ", age_min = '$age_min'";
        }
        if(!empty($age_max)){
            $sQuery.= ", age_max = '$age_max'";
        }   
        if(!empty($precio_base)){
            $sQuery.= ", precio_base = '$precio_base'";
        }
        if(!empty($precio_base_cost)){
            $sQuery.= ", precio_base_cost = '$precio_base_cost'";
        }
        if (!empty($renewal)){
            $sQuery.= ", renewal = '$renewal'";
        }   
        if(!empty($cost_audit)){
            $sQuery.= ", cost_audit = '$cost_audit'";
        }   
        if(!empty($tiempo)){
            $sQuery.= ", tiempo = '$tiempo'";
        }
        if(!empty($unidad)){
            $sQuery.= ", unidad = '$unidad'";
        }
        if(!empty($adicional)){
            $sQuery.= ", adicional = '$adicional'";
        }
        $sQuery.=" WHERE id = $id";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery, $comentario);
    }
	function Delete_plan_band_age($id = '') {
        $sQuery = "DELETE from plan_band_age WHERE id='$id'";
        $this->_SQL_tool($this->DELETE, __METHOD__, $sQuery);
    }
    function Add_plan_band_age($id_plan = '', $valor = '', $cost = '', $tax1 = '',$show1= '', $tax2 = '', $show2 = '', $id_country = '', $neto_ag = '', $age_min = '', $age_max = '', $precio_base = '', $precio_base_cost = '', $renewal = '', $cost_audit='', $tiempo='', $unidad='', $adicional=''){
         $id_plan = $id_plan?:0; 
         $valor = $valor?:0; 
         $cost = $cost?:0; 
         $tax1 = $tax1?:0; 
         $tax2 = $tax2?:0; 
         $neto_ag = $neto_ag?:0; 
         $age_min = $age_min?:0; 
         $age_max = $age_max?:0; 
         $precio_base = $precio_base?:0; 
         $precio_base_cost = $precio_base_cost?:0; 
         $renewal = $renewal?:0; 
         $cost_audit = $cost_audit?:0;
         $query = "INSERT INTO plan_band_age (id_plan, valor, cost, tax1, show1, tax2, show2, id_country, neto_ag, age_min, age_max, precio_base, precio_base_cost, renewal, cost_audit, tiempo, unidad, adicional) VALUES ('$id_plan', '$valor', '$cost', '$tax1','$show1', '$tax2', '$show2', '$id_country', '$neto_ag', '$age_min', '$age_max', '$precio_base', '$precio_base_cost', '$renewal', '$cost_audit', '$tiempo', '$unidad', '$adicional')";
         //die($query);
         $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert plan_band_age <-> id_plan:' . $id_plan);
         $this->id = $result;
    }
		/* function Add_plan_band_age_renewal($id_plan = '', $valor = '', $cost = '', $tax1 = '',$show1= '', $tax2 = '', $show2 = '', $id_country = '', $neto_ag = '', $age_min = '', $age_max = '', $precio_base = '', $precio_base_cost = '', $renewal = ''){
		 $id_plan = $id_plan?:0; 
		 $valor = $valor?:0; 
		 $cost = $cost?:0; 
		 $tax1 = $tax1?:0; 
		 $tax2 = $tax2?:0; 
		 $neto_ag = $neto_ag?:0; 
		 $age_min = $age_min?:0; 
		 $age_max = $age_max?:0; 
		 $precio_base = $precio_base?:0; 
		 $precio_base_cost = $precio_base_cost?:0; 
		 $renewal = $renewal?:0; 
        $query = "INSERT INTO plan_band_age (id_plan, valor, cost, tax1, show1, tax2, show2, id_country, neto_ag, age_min, age_max, precio_base, precio_base_cost, renewal) VALUES ('$id_plan', '$valor', '$cost', '$tax1','$show1', '$tax2', '$show2', '$id_country', '$neto_ag', '$age_min', '$age_max', '$precio_base', '$precio_base_cost', '$renewal')";
        $result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert plan_band_age <-> id_plan:' . $id_plan);
        $this->id = $result;
    }*/
	//traer nombre de categoria y plan
	function Get_plan_names_by_id($id = '', $language_id = '',$status=''){
		$query = "SELECT
					plans.id,
                    plan_categoria_detail.name_plan,
                    plan_detail.description,
                    IF(
                        plan_detail.description IS NOT NULL AND plan_detail.description <> '', 
                        plan_detail.description,
                        plans.`name`
                    ) AS titulo,
                    plans.modo_plan
					FROM
					plan_categoria_detail
					INNER JOIN plan_category ON plan_categoria_detail.id_plan_categoria = plan_category.id_plan_categoria
					INNER JOIN plans ON plans.id_plan_categoria = plan_category.id_plan_categoria
					INNER JOIN plan_detail ON plans.id = plan_detail.plan_id
					WHERE 1";
		if (!empty($id))
            $query.=" AND plans.id = '$id'";
        if (!empty($status))
            $query.=" AND plans.activo= '$status' AND plans.eliminado= 1";
		if (!empty($language_id))
            $query.=" AND plan_detail.language_id = '$language_id'"; 
		if (!empty($language_id))
            $query.=" AND plan_categoria_detail.language_id = '$language_id'"; 
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
	}
	//traer los beneficios y valores para plan especifico
	function Get_Benefit_names_by_plan($id = '', $language_id = ''){
					$valor='valor_'.$language_id; 
		$query = "SELECT
					benefit_plan.valor_spa  AS valor,
					benefit_detail.`name`
					FROM
					plans
					INNER JOIN benefit_plan ON plans.id = benefit_plan.id_plan
					INNER JOIN benefit_detail ON benefit_plan.id_beneficio = benefit_detail.id_benefit
					where 1";
		 if (!empty($id))
            $query.=" AND plans.id = '$id'";
		if (!empty($language_id))
            $query.=" AND benefit_detail.language_id = '$language_id'"; 
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
	}
	//Traer la unidad del plan 
	function get_plan_unidad($id='', $categoria='', $activo='', $language_id='', $planWebservices = false) {
		$query = "SELECT plans.id, plans.unidad, plans.min_age, plans.max_age, plans.name, plans.normal_age, plans.overage_factor,plans.overage_factor_cost, plan_detail.description, plans.family_plan FROM plans
					LEFT JOIN plan_detail ON plans.id = plan_detail.plan_id
					where 1 ";
		if($id!='') {
			$query .=" AND plans.id = '$id' ";
		}
		if($activo!='') {
			$query .=" AND plans.eliminado = '1' AND plans.activo = '1'";
		}
		if($categoria!='') {
			$query .=" AND plans.id_plan_categoria = '$categoria' ";
		}
		if ($language_id!=''){
            $query.=" AND plan_detail.language_id = '$language_id' ";
		}
		if ($planWebservices===false){
            $query.="AND (plans.modo_plan != 'W' OR plans.modo_plan IS NULL) ";
		}				
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
	}
	function get_all_age_banda($id_plan='',$pais='all'){
		$query="SELECT
				plan_band_age.id,
				plan_band_age.id_plan,
				plan_band_age.age_min,
				plan_band_age.age_max,
				plan_band_age.precio_base,
				plan_band_age.valor,
				plan_band_age.precio_base_cost,
				plan_band_age.cost,
				plan_band_age.id_country,
				plan_band_age.renewal,
				plan_band_age.unidad,
				plan_band_age.tiempo,
				plan_band_age.adicional,
				countries.description
			FROM
				plan_band_age
			LEFT JOIN countries ON plan_band_age.id_country = countries.iso_country
			WHERE 1 ";
		if(!empty($id_plan)){
			$query.=" AND id_plan = '$id_plan' ";
		}
		if(!empty($pais)){
			$query.=" AND id_country = '$pais' ";
		}
		$query.= " ORDER BY
			plan_band_age.renewal ASC,
			countries.description ASC,
			plan_band_age.age_min ASC ";
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
	}
	/*---------------------------------------------------------------------------------------------------------------*/
	/*-----------------------------------------CALCULO DE INTERVALOS DE EDADES---------------------------------------*/
	/*-------------------------------------INCLUYENDO OVERAGE Y PLANES POR BANDAS------------------------------------*/
	/*---------------------------------------------------------------------------------------------------------------*/
	function plans_intervalos_edades($idCategoria='', $pais=''){
		$ArrayPlan = $this->get_plan_unidad('',$idCategoria,'1');
        $cont = count($ArrayPlan);
        $n=0;
        $family_interval='no';
        for($i=0; $i < $cont; $i++){
			if ($ArrayPlan[$i]['unidad']=='bandas'){
				$Array_int1 = $this->get_all_age_banda($ArrayPlan[$i]['id'], $pais);
				if(count($Array_int1) < 1){
					$Array_int1 = $this->get_all_age_banda($ArrayPlan[$i]['id'], 'all');
				}
				for($j=0; $j < count($Array_int1); $j++){
					$intervalos[$n]['min'] = $Array_int1[$j]['age_min'];
					$intervalos[$n]['max'] = $Array_int1[$j]['age_max'];
					$n++;
				}
			}else{
				if($ArrayPlan[$i]['min_age'] > 0){
					$intervalos[$n]['min'] = $ArrayPlan[$i]['min_age'];
				}else{
					$intervalos[$n]['min'] = 1;
				}
				if($family_interval=='no' && $ArrayPlan[$i]['family_plan']=='Y') {
					$intervalos[$n]['max'] = 20;
					$n++;
					$intervalos[$n]['min'] = 21;
					$family_interval='si';
				}
				if($ArrayPlan[$i]['overage_factor'] > 1 && $ArrayPlan[$i]['normal_age'] > $ArrayPlan[$i]['min_age'] && $ArrayPlan[$i]['normal_age'] <= $ArrayPlan[$i]['max_age']){
					$intervalos[$n]['max'] = $ArrayPlan[$i]['normal_age'];
					$n++;
					$intervalos[$n]['min'] = ($ArrayPlan[$i]['normal_age']+1);
					$intervalos[$n]['max'] = $ArrayPlan[$i]['max_age'];
					$n++;
				}else{
					if($ArrayPlan[$i]['max_age'] > 0){
						$intervalos[$n]['max'] = $ArrayPlan[$i]['max_age'];
					}else{
						$intervalos[$n]['max'] = $ArrayPlan[$i]['min_age']+1;
					}
					$n++;
				}	
			}	
		}
		for($i=0; $i < $n-1; $i++){
			for($j=$i+1; $j < $n; $j++){
				$aux1='';
				$aux2='';
				if($intervalos[$i]['min'] > $intervalos[$j]['min']){
					$aux1=$intervalos[$i]['min'];
					$intervalos[$i]['min'] = $intervalos[$j]['min'];
					$intervalos[$j]['min'] = $aux1;
				}
				if($intervalos[$i]['max'] > $intervalos[$j]['max']){
					$aux2=$intervalos[$i]['max'];
					$intervalos[$i]['max'] = $intervalos[$j]['max'];
					$intervalos[$j]['max'] = $aux2;
				}
			}
		}
		$c1=0;
		$c2=0;
		$ax1='';
		$ax2='';
		for($i=0; $i < $n; $i++){
			if($intervalos[$i]['min'] != $ax1){
				 $ax1 = $intervalos[$i]['min'];
				 $intervalos2[$c1]['min'] = $intervalos[$i]['min'];
				 $c1++;
			}
			if($intervalos[$i]['max'] != $ax2){
				 $ax2 = $intervalos[$i]['max'];
				 $intervalos2[$c2]['max'] = $intervalos[$i]['max'];
				 $c2++;
			}
		}
		if($c1>$c2){
			$n=$c1;
		}else{
			$n=$c2;
		}
		$max_val=$intervalos2[$c2-1]['max'];
		$c=0;
		for($i=0; $i < $n; $i++){
			if($i==0){
				$intrv[$c]['min'] = $intervalos2[$i]['min'];
				$lista[$c] = $intervalos2[$i]['min'];
			}else{
				$psm=0;
				$psmi=0;
				$cs=$i;
				$pas='';
				$pas1=false;
				$pas2=false;
				while($pas=='' and $cs < $n){
					if(!empty($intervalos2[$cs]['max']) and $pas1==false){
						$psm=$cs;
						$pas1=true;
					}
					if(!empty($intervalos2[$cs]['min']) and $pas2==false){
						$psmi=$cs;
						$pas2=true;
					}
					if($pas1==true and $pas2==true){
						$pas='1';
					}
					$cs++;
				}
				$pas1=false;
				$pas2=false;
				$pas='';
				$cs=0;
				if(empty($intervalos2[$i]['min'])){
					if(($intrv[$c-1]['max']+1) == $intervalos2[$psmi]['min']){
						$intrv[$c]['min'] = $intervalos2[$psmi]['min'];
						$intervalos2[$psmi]['min']='';
						$psmi=0;
					}else{
						$ca=$i-1;
						$pas='';
						do{
							if(!empty($intrv[$ca]['max'])){
								$intrv[$c]['min'] = ($intrv[$c-1]['max']+1);
								$lista[$c] = ($intrv[$c-1]['max']+1);
								$pas='1';
							}else{
								$ca--;
							}
						}while($pas=='');
						$pas='';
						$ca=0;
					}
				}else if($intervalos2[$i]['min'] == ($intrv[$c-1]['max']+1)){
					$intrv[$c]['min'] = $intervalos2[$i]['min'];
					$lista[$c] = $intervalos2[$i]['min'];
				}else{
					if($intervalos2[$i]['min'] <= $intervalos2[$psm]['max']){
						$intrv[$c]['min'] = ($intrv[$c-1]['max']+1);
						$lista[$c] = ($intrv[$c-1]['max']+1);
						$intrv[$c]['max'] = ($intervalos2[$i]['min']-1);
						$c++;
						$intrv[$c]['min'] = $intervalos2[$i]['min'];
						$lista[$c] = $intervalos2[$i]['min'];
					}else{
						$cs=$i;
						while($intervalos2[$i]['min'] > $intervalos2[$psm]['max']){
							$intrv[$c]['min'] = ($intrv[$c-1]['max']+1);
							$lista[$c] = ($intrv[$c-1]['max']+1);
							$intrv[$c]['max'] = $intervalos2[$psm]['max'];
							$c++;
							$intervalos2[$psm]['max']='';
							$cs++;
							$psm++;
						}
						$cs=0;
						$psm=0;
						if($intervalos2[$i]['min'] != ($intrv[$c-1]['max']+1)){
							$intrv[$c]['min'] = ($intrv[$c-1]['max']+1);
							$lista[$c] = ($intrv[$c-1]['max']+1);
							$intrv[$c]['max'] = ($intervalos2[$i]['min']-1);
							$c++;
						}
						$intrv[$c]['min'] = $intervalos2[$i]['min'];
						$lista[$c] = $intervalos2[$i]['min'];
					}
				}
			}
			if(empty($intervalos2[$i]['max'])){
				$cs=$i+1;
				$pas='';
				$asignar=0;
				$val_min=0;
				$val_max=0;
				$ps=0;
				$van=0;
				while($pas==''){
					if(!empty($intervalos2[$cs]['min']) and $val_min == 0){
						$val_min=$intervalos2[$cs]['min'];
						$van++;
					}
					if(!empty($intervalos2[$cs]['max']) and $val_max == 0){
						$val_max=$intervalos2[$cs]['max'];
						$van++;
						$ps=$cs;
					}
					$cs++;
					if($van == 2 or $cs >= $n){
						$pas='1';
					}
				}
				$asignar=($val_min-1);
				if($val_min > $val_max or empty($val_min)){
					$asignar=$val_max;
					$intervalos2[$ps]['max']='';
				}
				$ps=0;
				$intrv[$c]['max']=$asignar;
				$c++;
				$asignar='';
				$pas='';
				$cs=0;
			}else if($intervalos2[$i]['max'] < $intervalos2[$i+1]['min'] or empty($intervalos2[$i+1]['min'])){
				$intrv[$c]['max'] = $intervalos2[$i]['max'];
				$c++;
			}else{
				$cs=$i+1;
				do{
					$intrv[$c]['max'] = ($intervalos2[$cs]['min']-1);
					$c++;
					$intrv[$c]['min'] = $intervalos2[$cs]['min'];
					$intervalos2[$cs]['min']='';
					$cs++;
				}while($intervalos2[$i]['max'] >= $intervalos2[$cs]['min'] and !empty($intervalos2[$cs]['min']));
				$intrv[$c]['max'] = $intervalos2[$i]['max'];
				$c++;
			}
			if($intrv[$c-1]['max'] >= $max_val){
				$i=$n+1;
			}
		}
		$lista[$c] = $intrv[$c-1]['max'];
		$lista_final = implode(',',$lista);
		$intrv[0]['cantidad']=$c;
		$intrv[0]['lista']=$lista_final;
		return $intrv;
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	function get_id_plans(){
		$query="SELECT
					plans.id
					FROM
					plans";
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
	function get_order_address($id_orden){
        $query = "SELECT *  
            FROM
                order_address 
            WHERE
                id_orden = '$id_orden'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	function get_dir_habitacion_plans($id_plan=''){
		$query="SELECT
					plans.dir_habitacion,
                    plans.plan_renewal
					FROM  
					plans
					WHERE 1 ";
		if($id_plan!=''){
			$query.=" AND id = '$id_plan' ";
		}
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
	}
	function Add_Preorden_select($usuario_id,$id_plan_categoria,$salida,$retorno,$origen, $destino,$cantidad_pasajero,$nacimiento1,$nacimiento2,$nacimiento3,$nacimiento4,$nacimiento5,$nacimiento6,$nacimiento7,$nacimiento8,$nacimiento9,$plan_seleccionado, $id_agencia, $respuesta, $codigo, $email, $paso, $ip, $estatus) {
		$plan_seleccionado = $plan_seleccionado?:0;
        $usuario_id = $usuario_id?:0;
        $id_plan_categoria = $id_plan_categoria?:0;
        $cantidad_pasajero = $cantidad_pasajero?:0;
        $query = "INSERT INTO preorder_select(usuario_id,id_plan_categoria,salida,retorno,origen, destino,cantidad_pasajero,nacimiento1,nacimiento2,nacimiento3,nacimiento4,nacimiento5,nacimiento6,nacimiento7,nacimiento8,nacimiento9, fecha, plan_seleccionado, voucher, authorize, agencia, email_usado, paso, ip_origen, estatus) VALUES('$usuario_id','$id_plan_categoria','$salida','$retorno','$origen','$destino','$cantidad_pasajero','$nacimiento1','$nacimiento2','$nacimiento3','$nacimiento4','$nacimiento5','$nacimiento6','$nacimiento7','$nacimiento8','$nacimiento9', NOW(), '$plan_seleccionado', '$codigo', '$respuesta', '$id_agencia', '$email', '$paso', '$ip', '$estatus')";
        return $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert preorder_select <-> user:' . $usuario_id);
    }
    function Update_Preorden_select($id_preorden, $usuario_id,$id_plan_categoria,$salida,$retorno,$origen, $destino,$cantidad_pasajero,$nacimiento1,$nacimiento2,$nacimiento3,$nacimiento4,$nacimiento5,$nacimiento6,$nacimiento7,$nacimiento8,$nacimiento9,$plan_seleccionado, $id_agencia, $respuesta, $codigo, $email, $paso, $ip, $estatus) {
        $query = "UPDATE preorder_select SET usuario_id='$usuario_id', id_plan_categoria='$id_plan_categoria', salida='$salida', retorno='$retorno', origen='$origen', destino='$destino',cantidad_pasajero='$cantidad_pasajero', nacimiento1='$nacimiento1', nacimiento2='$nacimiento2', nacimiento3='$nacimiento3', nacimiento4='$nacimiento4', nacimiento5='$nacimiento5', nacimiento6='$nacimiento6', nacimiento7='$nacimiento7', nacimiento8='$nacimiento8', nacimiento9='$nacimiento9', plan_seleccionado='$plan_seleccionado', voucher='$codigo', authorize='$respuesta', agencia='$id_agencia', email_usado='$email', paso='$paso', ip_origen='$ip', estatus='$estatus' where id = '$id_preorden' ";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $query, 'Update preorder_select <-> user:' . $usuario_id);
        return $id_preorden;
    }
    function Update_porcentajes_beneficios($idplan, $porcentajes){
        $query = "UPDATE benefit_plan
            SET porcentaje = CASE id_beneficio";
        foreach ($porcentajes as $value) {
           $query.=" WHEN ".$value['id_beneficio']." THEN ".$value['porcentaje'];
        }
        $query.= " END 
            WHERE id_beneficio IN (";
        foreach ($porcentajes as $value) {
            $query.= $value['id_beneficio'].',';
        }
        $query = substr($query, 0, -1);
        $query.= ") AND id_plan = '$idplan'";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $query);
    }
    function consulta_porcentaje($idplan){
        $query = "SELECT percent_benefit FROM plans WHERE id = '$idplan'";
        return $this->_SQL_tool($this->SELECT_SINGLE, __METHOD__, $query, '', '', '');
    }
    function add_benefit_order_percent($idplan, $id_orden, $total_costo, $language_id){
        $query = "SELECT
            benefit_plan.*, benefit_detail.name, benefit.activo
        FROM
            benefit
        INNER JOIN benefit_plan ON benefit_plan.id_beneficio = benefit.id
		INNER JOIN benefit_detail On benefit_detail.id_benefit = benefit.id
        WHERE
            benefit_plan.id_plan = '$idplan' AND benefit_detail.language_id = '$language_id' 
			AND benefit_plan.eliminado IS NULL 
			AND benefit.activo = '1'";
        $porcentajes = $this->_SQL_tool($this->SELECT, __METHOD__, $query);
        if(!empty($porcentajes)){
            $query ="INSERT INTO orders_benefit (id_benefit, id_orden, porcentaje, monto, fecha, relacion_benefit) VALUES ";
            foreach ($porcentajes as $value) {
                $monto = ($total_costo * $value['porcentaje']) / 100;
                $query.="('".$value['id_beneficio']."', '$id_orden', '".$value['porcentaje']."', '$monto', NOW(),".$value['id']." ),";
            }
            $query = substr($query, 0, -1);
			//var_dump($query);
			//die();
            $this->_SQL_tool($this->INSERT, __METHOD__, $query);
        }
    }
    function totpas_group($codigo){
        $query = "SELECT count(*) as cantidad FROM orders where codigo LIKE '%$codigo%'";
        $cantidad =  $this->_SQL_tool($this->SELECT_SINGLE, __METHOD__, $query);
        return $cantidad['cantidad'];
    }
	function get_plans_renewal($id_plan, $id){
        $query = "SELECT
					plans_renewal.id,
					plans_renewal.type,
					plans_renewal.warning_type,
					plans_renewal.send_email_type,
					plans_renewal.id_plan
					FROM `plans_renewal`
					 WHERE  1 ";
			if (!empty($id_plan))
           	 	$query.=" AND id_plan = '$id_plan'";
			if (!empty($id))
            	$query.=" AND id = '$id'";
        return $this->_SQL_tool($this->SELECT_SINGLE, __METHOD__, $query, '', '', '');
    }
	function update_plans_renewal($id, $type, $warning_type, $send_email_type, $id_plan) {
        $type = $type?:0;
		$warning_type = $warning_type?:0;
		$send_email_type = $send_email_type?:0;
		$id_plan = $id_plan?:0;
	    $sQuery = "UPDATE plans_renewal SET type='$type',  warning_type='$warning_type', send_email_type='$send_email_type', id_plan='$id_plan'  WHERE id = '$id'";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery);  
    }
	function add_plans_renewal($type, $warning_type, $send_email_type, $id_plan) {
		$type = $type?:0;
		$warning_type = $warning_type?:0;
		$send_email_type = $send_email_type?:0;
		$id_plan = $id_plan?:0;
        $query = "INSERT INTO plans_renewal (type, warning_type, send_email_type, id_plan ) VALUES ('$type', '$warning_type', '$send_email_type', '$id_plan')";
        $this->_SQL_tool($this->INSERT, __METHOD__, $query);
    }
	function Get_Orders_for_renewal($id_order) {
		$query = "SELECT
				orders.id,
				orders.agencia,
				orders.retorno,
				orders.codigo,
				orders.salida,
				orders.vendedor,
				orders.retorno,
				orders.cantidad,
				orders.total,
				(DATEDIFF(orders.retorno, CURDATE())) AS dias_restantes,
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
				) AS eldestino,
			 	(
					SELECT
						description
					FROM
						countries
					WHERE
					countries.iso_country = origen
			) AS elorigen,
			 users.email,
			 users.users,
			 users.lastname,
			 users.firstname,
			 plans_renewal.type,
			 plans_renewal.warning_type,
			 plans_renewal.send_email_type,
			 relational_orders.id AS id_relational_orders
			FROM
				orders
			INNER JOIN plans ON orders.producto = plans.id
			LEFT JOIN users ON orders.vendedor = users.id
			INNER JOIN plans_renewal ON plans_renewal.id_plan = plans.id
			LEFT JOIN relational_orders ON orders.id = relational_orders.id_order
			WHERE
				plans.plan_renewal = '1'
			AND orders.status = '1'
			AND relational_orders.`status` <> '1' "; 
			if(!empty($id_order)){
				$query .= " AND  orders.id='$id_order' ";   
			}else{
				$query .= "
					AND (DATEDIFF(orders.retorno, CURDATE())) <= 30 
					AND(DATEDIFF(orders.retorno, CURDATE())) >= -15 
					AND plans_renewal.type = '2'"; 
			}
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
	}
	function Get_Orders_for_renewal_now() {
             $query = "SELECT
						orders.id,
						orders.agencia,
						orders.retorno,
						orders.codigo,
						orders.vendedor,
						orders.retorno,
						plans_renewal.type,
						plans_renewal.warning_type,
						plans_renewal.send_email_type,
						plans.id_plan_categoria,
						plans.id AS id_plan,
						orders.nombre_contacto,
						orders.email_contacto,
						orders.comentarios,
						orders.referencia,
						orders.telefono_contacto,
						plans.dir_habitacion,
						plans.plan_renewal,
						orders.producto,
						orders.forma_pago,
						orders.origen,
						orders.destino,
						orders.salida,
						orders.cantidad,
						orders.territory,
						relational_orders.id AS id_relational_orders
					FROM
						orders
					INNER JOIN plans ON orders.producto = plans.id
					INNER JOIN plans_renewal ON plans_renewal.id_plan = plans.id
					LEFT JOIN relational_orders ON orders.id = relational_orders.id_order
					WHERE
						(
							(
								(
									DATEDIFF(orders.retorno, CURDATE())
								) <= 30
							)
						)
					AND (
						(
							(
								DATEDIFF(orders.retorno, CURDATE())
							) >= 1
						)
					)
					AND plans.plan_renewal = '1'
					AND orders.`status` = '1'
					AND orders.producto = 1479
					AND relational_orders.`status` <> 1
					AND plans_renewal.type='1'  "; 
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	function Get_Order_SELECT_SINGLE($codigo){
        $query = "SELECT *
					FROM `orders` where codigo='$codigo'"; 
        return $this->_SQL_tool($this->SELECT_SINGLE, __METHOD__, $query, '', '', '');
    }
	function Get_beneficiarios_by_order($id_orden){
		$query="SELECT * FROM beneficiaries WHERE id_orden='$id_orden'";
		 return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
	}
	function Add_Order_Renewal($codigo){
		$Array_Renewal_Order=$this->Get_Order_SELECT_SINGLE($codigo); 
		$comentario='Insert orden for renewal <-> codigo:'. $codigo;
		$id_order=$this->Clone_Renewal($Array_Renewal_Order,'orders','id',$comentario);
		$i=0;
		$comentario2='Insert banficiario renewal <-> id orden:' . $id_order;
		$Array_Renewal_Beneficiaries=$this->Get_beneficiarios_by_order($Array_Renewal_Order['id']); 		
		foreach($Array_Renewal_Beneficiaries as $Beneficiario){ 
			$id_beneficiario[$i]=$this->Clone_Renewal($Beneficiario,'beneficiaries','id',$comentario);
			$i++;
		} 
		$Array_id_renewal =array( 'id_order' => $id_order, $id_beneficiario);  
		return $Array_id_renewal;
	}
	function Clone_Renewal($Array_Renewal, $tabla, $id_primary, $comentario ){
			$query = "INSERT INTO $tabla(";
			foreach($Array_Renewal as $key => $item){
				 if($key!=$id_primary){
					 $query.= "$key,";  
				 }
			}
			$query = substr($query, 0, -1);
			$query.= ") VALUES(";  
			foreach($Array_Renewal as $key => $item){
				if($key!=$id_primary){
				 	$query.= "'$item',";  
				}
			}
			$query = substr($query, 0, -1);
			$query.= ") ";  
		$result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, $comentario,'','','_DEFAULT', $codigo);
        return $result;
    }
	  function Get_Beneficiario_Principal($id_orden) {
              $query = "SELECT 
								beneficiaries.nombre,
								beneficiaries.apellido,
								email,
								id
								FROM
								beneficiaries
								WHERE
								beneficiaries.id_orden ='$id_orden'
								ORDER BY
								beneficiaries.id ASC"; 
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	function get_broker_for_order($codigo){
		$query="SELECT agencia FROM orders where codigo = '$codigo' ";
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
	function Update_Impuesto($id_tax, $id_plan, $impuesto1, $mostrar1, $impuesto2, $mostrar2, $description1, $description2, $status1, $status2){
		$query="UPDATE plan_tax SET ";
		if (!empty($impuesto1)){
			$query.= " tax1='$impuesto1' ";
		}
		if (!empty($mostrar1)){
			$query.= ", show1='$mostrar1' ";
		}
		if (!empty($impuesto2)){
			$query.= ", tax2='$impuesto2' ";
		}
		if (!empty($mostrar2)){
			$query.= ", show2='$mostrar2' ";
		}
		if (!empty($description1)){	
			$query.= ", description1='$description1' ";
		} 
		if (!empty($description2)){	
			$query.= ", description2='$description2' ";
		}
		if (!empty($status1)){	
			$query.= ", status1='$status1' ";
		} 
		if (!empty($status2)){	
			$query.= ", status2='$status2' ";
		}
		$query.=" WHERE id_plan = '$id_plan' AND id_tax = '$id_tax' ";
		$comentario = 'Updated |  plans: ' . $impuesto1 . "-" . $mostrar1 . "-" . $impuesto2. "-" . $mostrar2. "-" .$description1 . "-". $description2. ', ID id: ' . $id_plan;
		$this->_SQL_tool($this->UPDATE, __METHOD__, $query, $comentario);
	}
	function Add_Impuesto($id_plan, $pais, $impuesto1, $mostrar1, $impuesto2, $mostrar2, $description1, $description2, $status1, $status2){
		$query="INSERT INTO plan_tax (
				id_plan,
				iso_countrie,
				tax1,
				show1,
				tax2,
				show2,
				description1,
				description2,
				status1,
				status2
			)
			VALUES
			(
				'$id_plan',
				'$pais',
				'$impuesto1',
				'$mostrar1',
				'$impuesto2',
				'$mostrar2',
				'$description1',
				'$description2',
				'$status1',
				'$status2'
			) ";
		$this->_SQL_tool($this->INSERT, __METHOD__, $query, $comentario);
	}
	function Add_Pais_Impuesto($id_plan, $pais, $impuesto1, $mostrar1, $impuesto2, $mostrar2, $description1, $description2)
	{
		$query="INSERT INTO impuesto (id_plan, id_country, impuesto_1, description_1, mostrar_1, impuesto_2, description_2, mostrar_2) VALUES('$id_plan', '$pais', '$impuesto1', '$description1', '$mostrar1', '$impuesto2', '$description2', '$mostrar2')";
		$result = $this->_SQL_tool($this->INSERT, __METHOD__, $query, 'Insert impuesto  <-> Id Plans:' . $id_plan);
	}
    function Delete_country_Premium($id_plan, $id_country, $name_table){
        $query="DELETE FROM";
		$query.=" ".$name_table." ";
		$query.="WHERE id_plan = '$id_plan' AND id_country = '$id_country'";
        $this->_SQL_tool($this->DELETE, __METHOD__, $query);
    }
    function Get_plan_times($id_plan='', $tiempo=''){
        $query="SELECT * FROM plan_times WHERE 1";
        if(!empty($id_plan)){
            $query.=" AND plan_times.id_plan = '$id_plan'";    
        }
        if(!empty($tiempo)){
            $query.=" AND plan_times.tiempo <= '$tiempo'";
        }
        $query.=" ORDER BY plan_times.tiempo DESC LIMIT 1";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function get_name_plan($id_category, $lang){
		$query = "SELECT
			plans.id,
			IF(plan_detail.description IS NOT NULL AND plan_detail.description <> '', 
				plan_detail.description,
				plans.`name`
			) AS description
		FROM
			plans
		LEFT JOIN plan_detail ON plans.id = plan_detail.plan_id
		WHERE 1
			AND plans.activo = '1'
			AND eliminado = '1' ";
		if(!empty($id_category)){
			$query.=" AND plan_detail.language_id = '$lang' ";
		}
		if(!empty($lang)){
			$query.=" AND	plans.id_plan_categoria = '$id_category' ";
		}
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
	}
	function Select_Restriction_Countries($id_broker){
        $query="SELECT
                    relaciotn_restriction.iso_country,
                    restriction.id_broker
                FROM
                    restriction
                INNER JOIN relaciotn_restriction ON relaciotn_restriction.id_restric = restriction.id_restric
                INNER JOIN countries ON countries.iso_country = relaciotn_restriction.iso_country
                LEFT JOIN broker ON broker.id_broker = restriction.id_broker
                WHERE
                    1
                AND restriction.id_broker = CASE
                WHEN (
                    SELECT
                        count(restriction.id_broker)
                    FROM
                        plans
                    INNER JOIN restriction ON plans.id = restriction.id_plans
                    WHERE
                        restriction.id_broker = '$id_broker'
                ) > 0 THEN
                    '$id_broker'
                ELSE
                    restriction.id_broker
                AND (
                    broker.opcion_plan = '1'
                    OR broker.opcion_plan IS NULL
                )
                END";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function validar_titulo_lang_add($lenguaje,$titulo){
        $sql = "SELECT
                    titulo
                FROM
                    plan_detail
                INNER JOIN plans ON plan_detail.plan_id = plans.id
                WHERE
                    plans.eliminado = 1
                AND plan_detail.titulo = '$titulo'";
        if(!empty($lenguaje)){
            $sql.=" AND language_id = '$lenguaje'";
        }
        return $this->_SQL_tool($this->SELECT, __METHOD__, $sql);
    }
    function validar_titulo_lang_edit($lenguaje,$titulo,$id){
        $sql = 'SELECT * 
                FROM (SELECT titulo, language_id
                      FROM plan_detail 
                      WHERE plan_id != '.$id.' ) AS tab
                WHERE tab.titulo = "'.$titulo.'"
                AND tab.language_id = "'.$lenguaje.'"';
        return $this->_SQL_tool($this->SELECT, __METHOD__, $sql);
    }
    function Get_categoria_detail($id_categoria, $language){
        $query="SELECT name_plan, language_id, id, id_plan_categoria FROM plan_categoria_detail WHERE 1";
        if (!empty($id_categoria)) {
            $query.=" AND id_plan_categoria = '$id_categoria'";
        }
        if (!empty($language)) {
            $query.=" AND language_id= '$language'";
        }
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	function get_Plans_NotAddedTo_aBroker($id_broker,$plan_category,$lenguaje){
        $query = "SELECT
                    plan_detail.titulo,
                    plans.id
                FROM
                    plans
                INNER JOIN plan_detail ON plans.id = plan_detail.plan_id
                WHERE
                    id_plan_categoria = '$plan_category'
                AND plans.activo = 1
                AND plans.eliminado != 2
                AND plan_detail.language_id = '$lenguaje'
                AND plans.id NOT IN (
                    SELECT DISTINCT
                        plan_banda_ag_net.id_plan_banda
                    FROM
                        `plan_banda_ag_net`
                    WHERE
                        plan_banda_ag_net.id_broker = '$id_broker'
                )
                AND plans.id NOT IN (
                    SELECT DISTINCT
                        plan_times.id_plan
                    FROM
                        plan_times_ag_net
                    RIGHT JOIN plan_times ON plan_times_ag_net.id_plan_time = plan_times.id
                    WHERE
                        plan_times_ag_net.id_broker = '$id_broker'
                )";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	function Get_Max_Orden($id_plan_categoria = ''){
			$query = "SELECT MAX(orden) as orden FROM plans WHERE 1";
			if (!empty($id_plan_categoria))
				$query.=" AND id_plan_categoria = '$id_plan_categoria'";
			 return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
	}
	function Get_benefit_plan($id_categoria, $lenguage='spa', $dirigido=''){
		$query.="SELECT
					benefit_detail.id_benefit AS 'id_beneficio',
					benefit_plan.id AS 'id_beneficio_plan',
					plans.id AS 'id_plan',
					plan_detail.titulo AS name_plan,
					benefit_detail.`name` as name_beneficio,
					benefit_plan.valor_$lenguage AS cobertura
				FROM
					benefit_plan
				INNER JOIN benefit_detail ON benefit_plan.id_beneficio = benefit_detail.id_benefit
				INNER JOIN plans ON benefit_plan.id_plan = plans.id
				INNER JOIN plan_detail ON plans.id = plan_detail.plan_id
				LEFT JOIN restriction ON plans.id = restriction.id_plans
				WHERE 1 ";
			if(!empty($id_categoria)){
				$query.=" AND plans.id_plan_categoria = '$id_categoria'";
			}
			if(!empty($dirigido)){
				$query.=" AND restriction.dirigido IN ($dirigido) ";
			}
			$query.="
				AND plans.eliminado = '1'
				AND plans.activo = '1'
				AND plans.activo = '1'
				AND (plans.modo_plan IS NULL OR plans.modo_plan != 'W')
				AND benefit_detail.language_id = '$lenguage'
				AND plan_detail.language_id = '$lenguage'
				ORDER BY
				benefit_detail.id_benefit,
				plans.orden,
				benefit_plan.orden ";
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
	}
	function raider_voucher($id_orden){
        $query="SELECT
                    raiders.id_raider,
                    raiders.rd_calc_type,
                    raiders.type_raider,
                    raiders.value_raider,
                    raiders.cost_raider
                FROM
                    raiders
                INNER JOIN orders_raider ON orders_raider.id_raider = raiders.id_raider
                INNER JOIN orders ON orders.id = orders_raider.id_orden
                WHERE
                    1";
        if (!empty($id_orden)) {
            $query.=" AND orders_raider.id_orden = '$id_orden'";
        }
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	function raider_by_beneficiaries($id_beneficiario, $id_orden){
        $query="SELECT
                raiders.id_raider,
                raiders.cost_raider,
                raiders.value_raider,
                raiders.rd_calc_type,
                raiders.type_raider,
                orders_raider.id,
                beneficiaries.precio_vta,
                beneficiaries.precio_cost
            FROM
                beneficiaries
            INNER JOIN orders_raider ON orders_raider.id_beneft = beneficiaries.id
            INNER JOIN raiders ON raiders.id_raider = orders_raider.id_raider
            INNER JOIN orders ON orders.id = orders_raider.id_orden
            WHERE 
                1 AND (orders_raider.id_status IS NULL OR orders_raider.id_status <> '2')";
        if (!empty($id_beneficiario)) {
            $query.=" AND orders_raider.id_beneft = '$id_beneficiario'";
        }
        if (!empty($id_orden)) {
            $query.=" AND orders.id = '$id_orden'";
        }
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
	function edit_estatus_raider($id_order_raider, $status){
        $query="UPDATE orders_raider SET id_status = '$status' WHERE id = '$id_order_raider'";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $query);
    }
	function Get_Plans_categoria_grupos($id_plan_categoria = '', $max_tiempo = 0, $origen="", $id_plan="", $id_multiple_plans="", $id_broker = '', $opcion_plan = '') {
            $query = "SELECT
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
                    plans.plan_local,
                    plans.num_pas,
                    plans.plan_pareja,
                    plans.factor_pareja,
                    plans.factor_pareja_cost,
                    plans.plan_renewal,
                    plans.price_voucher,
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
                if(!empty($id_broker)){
                    $query.=" AND restriction.id_broker = CASE
                    WHEN (
                    SELECT
                    count(restriction.id_broker)
                    FROM
                    plans
                    INNER JOIN restriction ON plans.id = restriction.id_plans
                    WHERE
                    restriction.id_broker = '$id_broker'
                    ) > 0 THEN
                    '$id_broker'
                    ELSE
                    restriction.id_broker
                    AND (
                    broker.opcion_plan = '1'
                    OR broker.opcion_plan IS NULL
                    )
                    END ";
                }
                    $query.=" AND plan_detail.language_id = '".$_SESSION["lng_id"]."' ";
            if (!empty($id_plan_categoria))
                $query.= " AND plans.id_plan_categoria =  '$id_plan_categoria'";
            if (!empty($origen)){
                $query.= " AND plans.plan_local =  '$origen'";
            }
            if (!empty($id_plan)){
                $query.= " and plans.id = '$id_plan'";
            }
            if (!empty($id_multiple_plans)){
                $query.= " and plans.id IN ($id_multiple_plans)";
            }
          // $query.= " ORDER BY plans.id";
           $query.=" ORDER BY plans.orden ASC";
        //echo $query;
            return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    //return $this->select_tool($sQuery);
        }
		function Update_orden_credit($id_orden,$status='',$tipo='',$serialize='',$rrn='', $cvv='',$code='',$codresp='') {
        $sQuery= "UPDATE orders SET status = '$status' ";
			if(!empty($tipo))
				$sQuery.=" , credito_tipo='$tipo' ";
			if(!empty($cvv))
				$sQuery.=" , credito_numero ='$cvv' ";
			if(!empty($serialize))
				$sQuery.=" , response='$serialize' ";
			if(!empty($rrn))
				$sQuery.=" , v_authorizado='$rrn' ";
        $sQuery.=" WHERE id = '$id_orden'";
        $comentario = "'Updated | credito_tipo |  credito_numero | response | v_authorizado";
        $this->_SQL_tool($this->UPDATE, __METHOD__, $sQuery, $comentario,'','','_DEFAULT', $cod_voucher[0]['codigo']." ".$serialize );
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
	function get_order_tax($id_order){
		$query="SELECT
					orders.producto as plan, 
					order_tax.id_order, 
					order_tax.id_tax, 
					order_tax.iso_countrie, 
					order_tax.tax1,
					order_tax.tax2, 
					order_tax.tax1_value, 
					order_tax.tax2_value,
					plan_tax.description1, 
					plan_tax.description2,
					plan_tax.show1, 
					plan_tax.show2
				FROM	
					orders
				INNER JOIN order_tax ON orders.id = order_tax.id_order
				INNER JOIN plan_tax ON order_tax.id_tax = plan_tax.id_tax
				WHERE 1 ";
		if(!empty($id_order)){
			$query.=" AND orders.id = '$id_order'";
		}
		return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
	}
      function raiders($Lenguage_Session_users='',$id_raider){
      $query="SELECT
                raiders.id_raider,
                raiders_detail.name_raider,
                raiders.id_status,
                raiders.promocion
            FROM
                raiders
            INNER JOIN raiders_detail ON raiders.id_raider = raiders_detail.id_raider 
            WHERE
            raiders_detail.language_id = '$Lenguage_Session_users'";
            if (!empty($id_raider)) {
             $query.=" AND raiders.id_raider= '$id_raider'";
            }
      return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    function get_busqueda($lenguaje) {
        $query = "SELECT plans.id,
                IF(plan_detail.description IS NOT NULL AND plan_detail.description <> '', 
                    plan_detail.description,
                    plans.`name`
                ) AS name
                FROM plans
                LEFT JOIN plan_detail ON plans.id = plan_detail.plan_id
                WHERE plans.activo=1 and plans.eliminado= 1 and plan_detail.language_id = '$lenguaje'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    
function Buscar_upgades_plan($Lenguage_Session_users='', $nombre_raider= '', $id_plan='', $min = '', $max = '',$id_broker='', $id_agencia = '',$tipo_plan='',$tipo_raider='',$status='',$id_raider='',$orden=''){
        $query="SELECT
                    raiders.id_status,
                    raiders.type_raider,
                    raiders.id_benefi,
                    raiders.value_raider,
                    raiders.promocion,
                    raiders_detail.name_raider,
                    raiders_detail.language_id,
                    plan_raider.id_raider,
                    plans.id as id_plan,
                    plan_categoria_detail.id_plan_categoria,
                    plan_categoria_detail.name_plan as nombre_categoria,
                    IFNULL(
                        plans.`name`,
                        plan_detail.titulo
                    ) AS titulo
                FROM
                    raiders
                INNER JOIN raiders_detail ON raiders.id_raider = raiders_detail.id_raider
                INNER JOIN plan_raider ON plan_raider.id_raider = raiders.id_raider
                INNER JOIN plans ON plans.id = plan_raider.id_plan
                INNER JOIN plan_detail ON plan_detail.plan_id = plans.id
                LEFT JOIN restriction ON plans.id = restriction.id_plans
                LEFT JOIN plan_category ON plans.id_plan_categoria = plan_category.id_plan_categoria
                LEFT JOIN plan_categoria_detail ON plan_category.id_plan_categoria = plan_categoria_detail.id_plan_categoria WHERE 1";
                if (!empty($tipo_plan)) {
                    $query.=" AND plan_categoria_detail.id_plan_categoria = '$tipo_plan'";
                }
                if (!empty($tipo_raider)) {
                    $query.=" AND raiders.promocion= '$tipo_raider'";
                }
                if (!empty($status)) {
                    $query.=" AND raiders.id_status = '$status'";
                }
            if (!empty($id_broker))
                $query.=" AND ((restriction.id_broker in ($id_broker) and restriction.dirigido='6') or (restriction.dirigido='2' and restriction.id_broker = '$id_agencia'))";
            if(!empty($search))
                $query.=" AND raiders_detail.name_raider like '%$search%' OR plan_detail.titulo like '%$search%'";
            if(!empty($Lenguage_Session_users))
                $query.=" AND raiders_detail.language_id = '$Lenguage_Session_users'";
                $query.=" AND plan_detail.language_id = '$Lenguage_Session_users'";
                $query.=" AND plan_categoria_detail.language_id = '$Lenguage_Session_users'";
                $query.=" AND plans.activo = 1";
                $query.=" AND plans.eliminado = 1";
            if(!empty($id_raider))
                $query.=" AND raiders_detail.id_raider = '$id_raider'";
            if (!empty($nombre_raider)) {
                $query.=" AND raiders.id_raider = '$nombre_raider'";
            }   
            if(!empty($id_plan)){
                $query.=" AND plan_detail.plan_id = '$id_plan'";
            }
            if(!empty($orden)){
                $query.=" ORDER BY '$orden' ASC";
            }else{
            $query.=" ORDER BY raiders_detail.name_raider ASC";
            }
             if (!empty($max))
            $query .= " LIMIT $min,$max ";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
     function Get_All_Raider_2($nombre_raider = '', $tipo_upgrade='',$min = '', $max = '',$language_id='',$id_plan='',$estatus='') {
     $query = "SELECT 
                      raiders.id_raider,
                      raiders.id_benefi,
                      raiders.type_raider,
                      raiders.value_raider,
                      raiders.id_status,
                      raiders.promocion,
                      raiders_detail.name_raider";
                    if (!empty($id_plan)) {
                        $query .= ", plan_raider.id_plan";
                    }
                $query .= " FROM raiders  
                INNER JOIN raiders_detail ON raiders.id_raider = raiders_detail.id_raider";
                if (!empty($id_plan)) {
                    $query .= " INNER JOIN plan_raider ON raiders.id_raider = plan_raider.id_raider";
                }
                $query .=" WHERE raiders_detail.language_id='$language_id'";
                if (!empty($tipo_upgrade)) {
                    $query .= " AND raiders.promocion='$tipo_upgrade'";
                }
                if (!empty($nombre_raider))
                    $query.=" AND raiders.id_raider ='$nombre_raider'";
                if (!empty($estatus)) {
                    $query.=" AND raiders.id_status = '$estatus'";
                }
                if (!empty($id_plan)) {
                    $query.=" AND plan_raider.id_plan = '$id_plan'";
                }
                $query .= " ORDER BY raiders_detail.name_raider DESC";
                if (!empty($max)) {
                    $query .= " LIMIT $min,$max ";
                }
               // var_dump($query);
      return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    ##funcion que valida que no se puedan eliminar planes si tienen ventas activas
    function Get_plans_ussage($id_plans) {
        $query = "SELECT 
                COUNT(producto) AS ussage 
                FROM orders
                INNER JOIN plans ON plans.id = orders.producto
                where plans.id = '$id_plans'"; 
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
       function Get_All_Plan2($search = '', $order = '', $min = '', $max = '', $id_plan_categoria = '', $max_tiempo = '', $tipo_plan='', $language_id='', $id_broker='', $id_agencia = '', $id_plan='', $restriccion='') {
        $query = 'SELECT
                    plans.id,
                    plans.`name`,
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
                    plans.family_plan,
                    plans.id_provider,
                    plans.min_age,
                    plans.max_age,
                    plans.normal_age,
                    plans.overage_factor,
                    plans.overage_factor_cost,
                    plans.eliminado,
                    plans.provider_id_plan,
                    plans.id_benefits_curr,
                    plans.factor_family,
                    plans.factor_family_cost,
                    plans.plan_local,
                    plans.type_tlf,
                    plans.modo_plan,
                    plans.plan_pareja,
                    plans.factor_pareja,
                    plans.factor_pareja_cost
                    FROM
                    plans
                    WHERE 1 ';
        if (!empty($search))
            $query.=" AND plan.name like'%$search%'";
        if (!empty($id_plan))
            $query.=" AND plans.id='$id_plan' ";
        if (!empty($tipo_plan)){
            if($tipo_plan=='T'){
                $query.=" AND plans.id!=''";
            }
            if($tipo_plan=='F'){
                $query.=" AND plans.family_plan='Y'";
            }
            if($tipo_plan=='L'){
                $query.=" AND plans.plan_local='Y'";
            }
            if($tipo_plan=='1'){
                $query.=" AND plans.activo='1'";
            }
            if($tipo_plan=='2'){
                $query.=" AND plans.activo='2'";
            }
        }
        if (!empty($max_tiempo))
            $query.=" AND plans.max_tiempo='$max_tiempo'";
        $query.=" AND plans.eliminado = '1'";
        $query.=" AND (modo_plan = 'N' OR modo_plan = 'W' OR modo_plan = 'T' OR modo_plan is NULL)";
        if (!empty($order)) {
            $query .= " ORDER BY $order ";
        } 
        if (!empty($max)) {
            $query .= " LIMIT $min,$max ";
        }
        return $this->_SQL_tool($this->SELECT, METHOD, $query);
    }
    function Get_MAXAge_plans($id_plan_categoria = '') {
        $query = "SELECT MAX(max_age) as edad FROM plans where id_plan_categoria = '$id_plan_categoria' and eliminado = '1' AND activo = '1'";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_premium_list($idPlan='', $country=true, $lang=DEFAULT_LANGUAGE, $lang=DEFAULT_LANGUAGE, $renewal=1){
        $query = "SELECT
                plan_times.id,
                plan_times.id_plan,
                plan_times.tiempo,
                plan_times.unidad,
                plan_times.valor,
                plan_times.cost,
                plan_times.adicional,
                plan_times.id_country,
                plan_times.renewal,
                countries_detail.description
            FROM
                plan_times
            LEFT JOIN countries_detail ON plan_times.id_country = countries_detail.iso_country
            AND countries_detail.language_id = '$lang'
            WHERE
                plan_times.id_plan = '$idPlan' ";
        if ($country) {
            $query.= " AND plan_times.id_country = 'all' ";
        }else{
            $query.= " AND plan_times.id_country != 'all' ";
        }
        if (!empty($renewal)) {
            $query.= " AND plan_times.renewal = '$renewal' ";
        }
        $query.= " ORDER BY
                plan_times.id_country ASC,
                plan_times.adicional ASC,
                plan_times.tiempo ASC ";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
    public function Get_band_premium_list($idPlan='', $country=true, $lang=DEFAULT_LANGUAGE, $renewal='1'){
        $query = "SELECT
                plan_band_age.id,
                plan_band_age.age_min,
                plan_band_age.age_max,
                plan_band_age.id_plan,
                plan_band_age.tiempo,
                plan_band_age.unidad,
                plan_band_age.valor,
                plan_band_age.precio_base,
                plan_band_age.cost,
                plan_band_age.precio_base_cost,
                plan_band_age.adicional,
                plan_band_age.id_country,
                plan_band_age.renewal,
                countries_detail.description
            FROM
                plan_band_age
            LEFT JOIN countries_detail ON plan_band_age.id_country = countries_detail.iso_country
            AND countries_detail.language_id = '$lang'
            WHERE
                plan_band_age.id_plan = '$idPlan' ";
        if ($country) {
            $query.= " AND plan_band_age.id_country = 'all' ";
        }else{
            $query.= " AND plan_band_age.id_country != 'all' ";
        }
        if (!empty($renewal)) {
            $query.= " AND plan_band_age.renewal = '$renewal' ";
        }
        $query.= " ORDER BY
            plan_band_age.id_country ASC,
            plan_band_age.age_min ASC,
            plan_band_age.adicional ASC,
            plan_band_age.tiempo ASC ";
        return $this->_SQL_tool($this->SELECT, __METHOD__, $query);
    }
}
?>
