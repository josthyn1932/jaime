<?php
require_once('tcpdf/tcpdf.php'); //Llamando a la Libreria TCPDF
require_once('config2.php'); //Llamando a la conexión para BD
require_once('includes/load.php');
date_default_timezone_set('America/Bogota');

ob_end_clean(); //limpiar la memoria

class MYPDF extends TCPDF{
      
    public function Header() {
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false, 0);
        $img_file = dirname( __FILE__ ) .'/assets/img/logo.png';
        $this->Image($img_file, 85, 8, 20, 25, '', '', '', false, 30, '', false, false, 0);
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        $this->setPageMark();
    }
}

//Iniciando un nuevo pdf
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, 'mm', 'Letter', true, 'UTF-8', false);

//Establecer margenes del PDF
$pdf->SetMargins(5, 35, 20);
$pdf->SetHeaderMargin(20);
$pdf->setPrintFooter(false);
$pdf->setPrintHeader(true); //Eliminar la linea superior del PDF por defecto
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM); //Activa o desactiva el modo de salto de página automático
 
//Informacion del PDF
$pdf->SetCreator('UrianViera');
$pdf->SetAuthor('UrianViera');
$pdf->SetTitle('Informe de Empleados');
 
/** Eje de Coordenadas
 *          Y
 *          -
 *          - 
 *          -
 *  X ------------- X
 *          -
 *          -
 *          -
 *          Y
 * 
 * $pdf->SetXY(X, Y);
 */

//Agregando la primera página
$pdf->AddPage();
$pdf->SetFont('helvetica','B',10); //Tipo de fuente y tamaño de letra
$pdf->SetXY(150, 20);
$pdf->Write(0, 'Código: 000009');
$pdf->SetXY(150, 25);
$pdf->Write(0, 'Fecha: '. date('d-m-Y'));
$pdf->SetXY(150, 30);
$pdf->Write(0, 'Hora: '. date('h:i A'));

$canal ='Ansu fati';
$pdf->SetFont('helvetica','B',10); //Tipo de fuente y tamaño de letra
$pdf->SetXY(15, 20); //Margen en X y en Y
$pdf->SetTextColor(204,0,0);

$pdf->Write(0, 'Empresa: Alejandro chupalo');
$pdf->SetTextColor(0, 0, 0); //Color Negrita
$pdf->SetXY(15, 25);
$pdf->Write(0, 'Canal: '. $canal);



$pdf->Ln(35); //Salto de Linea
$pdf->Cell(40,26,'',0,0,'C');
/*$pdf->SetDrawColor(50, 0, 0, 0);
$pdf->SetFillColor(100, 0, 0, 0); */
$pdf->SetTextColor(34,68,136);
//$pdf->SetTextColor(255,204,0); //Amarillo
//$pdf->SetTextColor(34,68,136); //Azul
//$pdf->SetTextColor(153,204,0); //Verde
//$pdf->SetTextColor(204,0,0); //Marron
//$pdf->SetTextColor(245,245,205); //Gris claro
//$pdf->SetTextColor(100, 0, 0); //Color Carne
$pdf->SetFont('helvetica','B', 15); 
$pdf->Cell(100,6,'LISTA DE PRODUCTOS',0,0,'C');


$pdf->Ln(10); //Salto de Linea
$pdf->SetTextColor(0, 0, 0); 

//Almando la cabecera de la Tabla
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('helvetica','B',9); //La B es para letras en Negritas
$pdf->Cell(35,6,'name',1,0,'C',1);
$pdf->Cell(20,6,'brand_name',1,0,'C',1);
$pdf->Cell(20,6,'model_name',1,0,'C',1);
$pdf->Cell(22,6,'serial_number',1,0,'C',1); 
$pdf->Cell(20,6,'sst',1,0,'C',1);
$pdf->Cell(35,6,'descipcion',1,0,'C',1);
$pdf->Cell(25,6,'start_date',1,0,'C',1);
$pdf->Cell(25,6,'end_date',1,0,'C',1);
$pdf->Cell(30,6,'id_cliente',1,1,'C',1);
/*El 1 despues de  Fecha Ingreso indica que hasta alli 
llega la linea */

$pdf->SetFont('helvetica','',8);


//SQL para consultas Empleados
//$fechaInit = date("Y-m-d", strtotime($_POST['fecha_ingreso']));
//$fechaFin  = date("Y-m-d", strtotime($_POST['fechaFin']));


$productsSql = join_product_table_send();
$products = exp_90_days($productsSql);

$sqlTrabajadores = ("SELECT * FROM products");
$query = mysqli_query($con, $sqlTrabajadores);

foreach ($products as $product):
    $pdf->Cell(35,7,($product['name']),1,0,'C');
       $pdf->Cell(20,7,$product['brand_name'],1,0,'C');
       $pdf->Cell(20,7,($product['model_name']),1,0,'C');
       $pdf->Cell(22,7,($product['serial_number']),1,0,'C');
        $pdf->Cell(20,7,($product['sst']),1,0,'C');
        $pdf->Cell(35,7,($product['description']),1,0,'C');
        $pdf->Cell(25,7,($product['start_date']),1,0,'C');
        $pdf->Cell(25,7,($product['end_date']),1,0,'C');
        $pdf->Cell(35,7,($product['client_name']),1,1,'C');
endforeach;

//while ($dataRow = mysqli_fetch_array($query)) {
  //      $pdf->Cell(35,7,($dataRow['name']),1,0,'C');
    //    $pdf->Cell(20,7,$dataRow['brand_name'],1,0,'C');
      //  $pdf->Cell(20,7,($dataRow['model_name']),1,0,'C');
       // $pdf->Cell(22,7,($dataRow['serial_number']),1,0,'C');
       // $pdf->Cell(20,7,($dataRow['sst']),1,0,'C');
       // $pdf->Cell(35,7,($dataRow['description']),1,0,'C');
       // $pdf->Cell(25,7,(date('m-d-Y', strtotime($dataRow['start_date']))),1,0,'C');
       // $pdf->Cell(35,7,(date('m-d-Y', strtotime($dataRow['end_date']))),1,0,'C');
       // $pdf->Cell(35,7,($dataRow['id_client']),1,1,'C');
   // }


//$pdf->AddPage(); //Agregar nueva Pagina

$pdf->Output('Resumen_Pedido_'.date('d_m_y').'.pdf', 'I'); 
// Output funcion que recibe 2 parameros, el nombre del archivo, ver archivo o descargar,
// La D es para Forzar una descarga
