<!DOCTYPE html>
<html>
<head>
<style>
body {
  background-color: lightblue;
}

h1 {
  color: white;
  text-align: center;
}

p {
  font-family: verdana;
  font-size: 20px;
}
</style>
</head>
<body>
<p></p>
<div>
@if(@$args['typeContact']==1)
<h4><?php print_r('Tipo de Contacto : Persona Natural');?></h4>
</br>
<h4><?php print_r('DNI: '.@$args['dni']);?></h4>
</br>
@endif
@if(@$args['typeContact']==2)
<h4><?php print_r('Tipo de Contacto : Representante de una empresa');?></h4>
</br>
<h4><?php print_r('RUC: '.@$args['ruc']);?></h4>
</br>
@endif
}
<h4><?php print_r('Nombres: '.@$args['name']);?> </h4>
</br>
<h4><?php print_r('Email: '.@$args['phone']);?></h4>
</br>
<h4><?php print_r('Nombres: '.@$args['schedule']);?> </h4>
</br>
<h4><?php print_r('Asunto: '.@$args['subject']);?></h4>
</br>
<div >
<h4>Mensaje:</h4>
</br>
<h4><?php print_r(@$args['bodyMessage']);?> </h4>
</div>


</div>
</body>
</html>