<?php
include_once("../../login/check.php");
include_once("../../class/factura.php");
$factura=new factura;
?>
<table class="table table-bordered table-hover table-striped">
<thead>
    <tr>
        <th>N</th>
        <th><?php echo $idioma['FechaFactura']?></th>
        <th><?php echo $idioma['NFactura']?></th>
        <th><?php echo $idioma['Nit']?></th>
        <th><?php echo $idioma['FacturaA']?></th>
        <th><?php echo $idioma['TotalBs']?></th>
        <th><?php echo $idioma['Cancelado']?></th>
        <th><?php echo $idioma['Cambio']?></th>
        <th><?php echo $idioma['Estado']?></th>
        <th></th>
    </tr>
</thead>
<?php foreach($factura->mostrarFacturas("") as $f){$i++;?>
<tr>
    <td><?php echo $i?></td>
    <td><?php echo fecha2Str($f['FechaFactura'])?></td>
    <td><?php echo ($f['NFactura'])?></td>
    <td><?php echo ($f['Nit'])?></td>
    <td><?php echo ($f['Factura'])?></td>
    <td class="der"><?php echo number_format($f['TotalBs'],2)?></td>
    <td class="der"><?php echo number_format($f['Cancelado'],2)?></td>
    <td class="der"><?php echo number_format($f['MontoDevuelto'],2)?></td>
    <td><?php echo ($f['Estado'])?></td>
    <td><a href="ver.php?f=<?php echo $f['CodFactura']?>" target="_blank" class="btn btn-mini"><?php echo $idioma['VerFactura']?></a></td>
</tr>
<?php }?>
</table>
  