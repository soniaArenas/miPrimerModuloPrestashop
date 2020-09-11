<div id="sony_modulo">
	<h1>{l s='texto fijo traducible a otros idiomas' mod='sonymodulo'}</h1><!-- Texto fijo que se puede traducir -->
	<p>
		{$texto_variable|escape:'html':'UTF-8'} <!--Variable que imprime el texto de bd  -->
		<!-- |escape:'html':'UTF-8' no es obligatorio, pero si lo quieres hacer oficial es standar -->
	</p>

</div>
	