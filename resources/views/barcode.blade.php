<!DOCTYPE html>
<html>
<head>
    <title>How to Generate Bar Code in Laravel? - ItSolutionStuff.com</title>
</head>
<body>
  
<h1>How to Generate Bar Code in Laravel? - ItSolutionStuff.com</h1>
   
<h3>HTML</h3>
@php
    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
@endphp
  
{!! $generator->getBarcode('0001245259636', $generator::TYPE_CODE_128) !!}
  
  
<h3>PNG</h3>
@php
    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
@endphp
  
<img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode('000005263635', $generatorPNG::TYPE_CODE_128)) }}">


<h3>SVG</h3>
@php
$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();

@endphp
{!! $generatorSVG->getBarcode('151515151515', $generatorSVG::TYPE_EAN_13) !!}


</body>
</html>