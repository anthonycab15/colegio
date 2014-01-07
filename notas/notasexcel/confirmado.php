<?php
include_once("../../login/check.php");
include_once("../../class/registronotasexcel.php");
include_once("../../class/registronotas.php");
include_once("../../class/casilleros.php");
$CodigoRegistro=$_SESSION['CodigoRegistro'];
$registronotasexcel=new registronotasexcel;
$registronotas=new registronotas;
$casilleros=new casilleros;
$rne=$registronotasexcel->mostrarTodoRegistro("CodRegistroNotasExcel=".$CodigoRegistro);
$rne=array_shift($rne);


//print_r($rne);
$NombreArchivo="archivos/".$rne['NombreArchivo'];
date_default_timezone_set('America/La_Paz');


/** Incluir PHPExcel */
require_once 'Classes/PHPExcel.php';
require_once 'funciones/funciones.php';
$objPHPExcel = PHPExcel_IOFactory::load($NombreArchivo);
/*
$objReader = new PHPExcel_Reader_Excel2007();
$objPHPExcel = $objReader->load("simple.xlsx");
*/
$dact=$objPHPExcel->getActiveSheet();
$periodo=$dact->getCell('C2')->getValue();
$codigodocente=$dact->getCell('E3')->getValue();
$codigomateria=$dact->getCell('E4')->getValue();
$codigocurso=$dact->getCell('E5')->getValue();
$codigocasilleros=$dact->getCell('F3')->getValue();
$codigodocentemateriacurso=$dact->getCell('G3')->getValue();
$cantidadalumnos=$dact->getCell('E6')->getValue();
$cantidadcasilleros=$dact->getCell('E7')->getValue();
$notas=array();
$totalalto=$cantidadalumnos+10;
$columnacodigo=adicionar("D",$cantidadcasilleros+4);
$columnanr=adicionar("D",$cantidadcasilleros+1);
$columnandps=adicionar("D",$cantidadcasilleros+2);
$columnanf=adicionar("D",$cantidadcasilleros+3);

for($i=11;$i<=$totalalto;$i++){
	$cod=$dact->getCell($columnacodigo.$i)->getValue();
	$n=array();
	$poscol="D";
	for($j=1;$j<=$cantidadcasilleros;$j++){
		$poscol=adicionar($poscol,1);
		$vn=$dact->getCell($poscol.$i)->getValue();
		$n[$j]=$vn;
	}
	$notaresultado=$dact->getCell($columnanr.$i)->getCalculatedValue();
	$notadps=$dact->getCell($columnandps.$i)->getCalculatedValue();
	$notanotafinal=$dact->getCell($columnanf.$i)->getCalculatedValue();
	$notas[$cod]=array("notas"=>$n,
						"resultado"=>$notaresultado,
						"dps"=>$notadps,
						"notafinal"=>$notanotafinal
					);
}
$cas=$casilleros->mostrar($codigocasilleros);
$cas=array_shift($cas);
foreach($notas as $nok=>$nov){
	$CodRegistroNotas=$nok;
	$val=array();
	for($i=1;$i<=$cantidadcasilleros;$i++){
		$NotaValor=$nov['notas'][$i];
		$val["Nota".$i]=$NotaValor;
		
	}
	$Resultado=$nov['resultado'];
	$Dps=$nov['dps'];
	$NotaFinal=$nov['notafinal'];
	
	if($Resultado<=22){
		if($cas['Dps']==1){
			$Resultado=22;// Sacamos nota Minima para cada Uno	
		}else{
			$Resultado=27;// Sacamos nota Minima para cada Uno	si no tiene dps
		}
	}
	if($cas['Dps']==1){///Condicion para la nota de 35
		if($Resultado==30){
			$Resultado=31;	
		}	
	}else{
		if($Resultado==35){
			$Resultado=36;	
		}
		
	}
	$NotaFinal=$Resultado+$Dps;
	$val['Resultado']=$Resultado;
	$val['Dps']=$Dps;
	$val['NotaFinal']=$NotaFinal;

	
	/*echo "<pre>";
	print_r($val);
	echo "</pre>";*/
	$registronotas->actualizarNota($val,"CodRegistroNotas=".$CodRegistroNotas);
	
}
/*
echo "<pre>";
print_r($notas);
echo "</pre>";*/

$registronotasexcel->actualizarRegistro(array("Correcto"=>"1"),"CodRegistroNotasExcel=".$CodigoRegistro);
if($rne['Direccion']=="modificarnotasadministrativo"){
	header("Location:../".$rne['Direccion']."/vernota.php?c=".$codigocasilleros);
}else{
	header("Location:../".$rne['Direccion']."/?c=".$codigocasilleros);	
}
?>