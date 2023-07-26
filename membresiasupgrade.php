<?php
/*
Template Name: MembresiaUpgrade template
*/
function cyb_session_startupgradex() {
	if( !session_id() ) {
	session_start();
}

}
add_action('init', 'cyb_session_startupgradex', 1);

include('../plugins/wp-e-sinergyz/includes/ConfigSP.php');
include('../plugins/wp-e-sinergyz/conexion.php');

$sp = new ConfigSP();
$user_id = get_current_user_id();
$subscripcion = $sp->consultar_cuenta($user_id)->idSuscripcion;
//$subscripcionx = $sp->consultar_cuenta($user_id);
//$creacion_cuenta=$sp->edit_cuenta($subscripcionx->idUser,'sub_EvzrELP72XUr3N',$subscripcionx->idCuentaAddon,$subscripcionx->idCuentaPago,$subscripcionx->estatus,$subscripcionx->created_at);
$customer = $sp->consultar_cuenta($user_id)->idCostumer;
$producto = ConsultarSubscripcion($subscripcion)->items->data[0]->plan->product;
//$_SESSION['idcustomer'] = $customer;
//$_SESSION['idsubscripcion'] = $subscripcion;

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() ); ?>

<div id="main-content">

<?php if ( ! $is_page_builder_used ) : ?>

	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>

				<?php if ( ! $is_page_builder_used ) : ?>

					<h1 class="main_title"></h1>
				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_featured_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
						print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
				?>

				<?php endif; ?>
<style>
/**/
/**/
div.containerx
{
  font-family: Raleway;
	margin-bottom:5%;
	margin-top:5%;
	text-align: left;
}

div.container a
{
    color: gray;
    /*text-decoration: none;*/
    font: 20px Raleway;
    margin: 0px 10px;
    padding: 10px 10px;
    position: relative;
    z-index: 0;
    cursor: pointer;
}

.lime
{
    background: white;
}

/* Pull up  */
div.pullUp a:before
{
    position: absolute;
    width: 100%;
    height: 2px;
    left: 0px;
    bottom: 0px;
    content: '';
    background: gray;
    /*opacity: 0.3;*/
    transition: all 0.3s;
}

div.pullUp a:hover:before
{
	font-weight: bold;
		color:#000;
    height: 7%;
		background: #14B9FF;
}

</style>

</div> <!-- cd-popup -->
<div class="containerx lime pullUp">
  <a>MEMBRESIA</a>
  <a>ADDONS</a>
  <a>METODOS DE PAGO</a>
</div>
<style id="et-builder-module-design-cached-inline-styles">.et_pb_section_0{padding-top:50px;padding-right:0px;padding-bottom:7px;padding-left:0px}.et_pb_column_12{background-color:#f1f1f1}.et_pb_text_17 p{line-height:0.5em}.et_pb_text_17.et_pb_text{color:#000000!important}.et_pb_column_13{background-color:#f1f1f1}.et_pb_text_16{font-weight:700;font-size:30px;border-radius:0 0 0 0;overflow:hidden;border-color:#aaaaaa;border-bottom-width:1px;padding-top:0px!important;padding-bottom:15px!important;margin-top:0px!important}.et_pb_text_16.et_pb_text{color:#14b9ff!important}.et_pb_text_15{font-size:16px;line-height:0.5em;border-radius:0 0 0 0;overflow:hidden;border-top-width:1px;border-bottom-width:1px;border-color:#aaaaaa;padding-top:15px!important}.et_pb_text_15 p{line-height:0.5em}.et_pb_text_15.et_pb_text{color:#000000!important}.et_pb_text_14{font-weight:700;font-size:30px;border-radius:0 0 0 0;overflow:hidden;border-color:#aaaaaa;border-bottom-width:1px;padding-top:0px!important;padding-bottom:15px!important;margin-top:0px!important}.et_pb_text_18.et_pb_text{color:#14b9ff!important}.et_pb_text_14.et_pb_text{color:#14b9ff!important}.et_pb_text_13 h5{font-size:12px;line-height:0.7em}.et_pb_text_13{font-size:16px;line-height:1.4em;border-radius:0 0 0 0;overflow:hidden;border-top-width:1px;border-bottom-width:1px;border-color:#aaaaaa;padding-top:15px!important}.et_pb_text_13 p{line-height:1.4em}.et_pb_text_13.et_pb_text{color:#000000!important}.et_pb_column_11{background-color:#f1f1f1}.et_pb_text_12{font-weight:700;font-size:30px;border-radius:0 0 0 0;overflow:hidden;border-color:#aaaaaa;border-bottom-width:1px;padding-top:0px!important;padding-bottom:15px!important;margin-top:-10px!important}.et_pb_text_12.et_pb_text{color:#14b9ff!important}.et_pb_text_17{font-size:16px;line-height:0.5em;border-radius:0 0 0 0;overflow:hidden;border-top-width:1px;border-bottom-width:1px;border-color:#aaaaaa;padding-top:15px!important}.et_pb_text_18{font-weight:700;font-size:30px;border-radius:0 0 0 0;overflow:hidden;border-color:#aaaaaa;border-bottom-width:1px;padding-top:0px!important;padding-bottom:15px!important;margin-top:0px!important}.et_pb_text_11{font-size:16px;line-height:1em;border-radius:0 0 0 0;overflow:hidden;border-top-width:1px;border-bottom-width:1px;border-color:#aaaaaa;padding-top:15px!important}.et_pb_section_3{padding-top:0px;padding-right:0px;padding-bottom:51px;padding-left:0px}.et_pb_text_23 h3{font-weight:700;font-size:20px}.et_pb_text_23{font-size:18px}.et_pb_text_23.et_pb_text{color:#000000!important}.et_pb_code_0{padding-top:0px}.et_pb_column_17{padding-top:0px}.et_pb_row_8.et_pb_row{padding-top:0;padding-right:0px;padding-bottom:27px;padding-left:0px}.et_pb_section_4.et_pb_section{background-color:#eeeeee!important}.et_pb_section_4{padding-top:0px;padding-right:0px;padding-bottom:51px;padding-left:0px}.et_pb_image_0{text-align:center}.et_pb_column_14{background-color:#f1f1f1}.et_pb_text_21{padding-top:25px!important}.et_pb_text_21 h2{font-size:20px}.et_pb_row_6.et_pb_row{padding-top:55px;padding-right:0px;padding-bottom:0;padding-left:0px}.et_pb_text_20{font-weight:700;font-size:30px;border-radius:0 0 0 0;overflow:hidden;border-color:#aaaaaa;border-bottom-width:1px;padding-top:0px!important;padding-bottom:15px!important;margin-top:-10px!important}.et_pb_text_20.et_pb_text{color:#14b9ff!important}.et_pb_text_19{font-size:16px;line-height:0.5em;border-radius:0 0 0 0;overflow:hidden;border-top-width:1px;border-bottom-width:1px;border-color:#aaaaaa;padding-top:15px!important}.et_pb_text_19 p{line-height:0.5em}.et_pb_text_19.et_pb_text{color:#000000!important}.et_pb_text_11 h5{font-size:12px;line-height:0.7em}.et_pb_text_11 p{line-height:1em}.et_pb_text_0 h1{font-weight:700;color:#14b9ff!important}.et_pb_section_1{padding-top:40px;padding-right:0px;padding-bottom:0px;padding-left:0px}.et_pb_text_3{font-weight:700;font-size:30px;border-radius:0 0 0 0;overflow:hidden;border-color:#aaaaaa;border-bottom-width:1px;padding-top:0px!important;padding-bottom:15px!important;margin-top:-10px!important}.et_pb_text_3.et_pb_text{color:#14b9ff!important}.et_pb_text_2{font-size:16px;line-height:0.5em;border-radius:0 0 0 0;overflow:hidden;border-top-width:1px;border-bottom-width:1px;border-color:#aaaaaa;padding-top:15px!important}.et_pb_text_2 p{line-height:0.5em}.et_pb_text_2.et_pb_text{color:#000000!important}.et_pb_column_2{background-color:#f1f1f1}.et_pb_row_2.et_pb_row{padding-top:2px;padding-right:0px;padding-bottom:27px;padding-left:0px}.et_pb_row_1.et_pb_row{padding-top:0;padding-right:0px;padding-bottom:27px;padding-left:0px}.et_pb_pricing_tables_0 .et_pb_featured_table .et_pb_best_value{color:#8f8f8f!important}.et_pb_row_3.et_pb_row{padding-top:0;padding-right:0px;padding-bottom:27px;padding-left:0px}.et_pb_pricing_tables_0 .et_pb_pricing_heading{background-color:#fcfcfc; margin-bottom: 35px !important;}.et_pb_pricing_tables_0 .et_pb_featured_table{background-color:#b3b3b3}.et_pb_pricing_tables_0 .et_pb_pricing li span:before{display:none}body #page-container .et_pb_pricing_tables_0 .et_pb_pricing_table_button.et_pb_button:hover:after{color:}body #page-container .et_pb_pricing_tables_0 .et_pb_pricing_table_button.et_pb_button{color:#ffffff!important}.et_pb_pricing_tables_0 .et_pb_button_wrapper{text-align:center}.et_pb_pricing_tables_0 .et_pb_sum{font-size:60px;line-height:20px}.et_pb_pricing_tables_0 .et_pb_best_value{font-weight:700;font-style:italic;font-size:24px;color:#474747!important}.et_pb_pricing_tables_0 .et_pb_pricing_heading h2,.et_pb_pricing_tables_0 .et_pb_pricing_heading h1.et_pb_pricing_title,.et_pb_pricing_tables_0 .et_pb_pricing_heading h3.et_pb_pricing_title,.et_pb_pricing_tables_0 .et_pb_pricing_heading h4.et_pb_pricing_title,.et_pb_pricing_tables_0 .et_pb_pricing_heading h5.et_pb_pricing_title,.et_pb_pricing_tables_0 .et_pb_pricing_heading h6.et_pb_pricing_title{font-size:26px!important;color:#8f8f8f!important}.et_pb_section_2{padding-top:54px;padding-right:0px;padding-bottom:65px;padding-left:0px}.et_pb_text_24{font-size:18px}.et_pb_text_11.et_pb_text{color:#000000!important}.et_pb_column_7{background-color:#f1f1f1}.et_pb_column_10{background-color:#f1f1f1}.et_pb_text_10{font-weight:700;font-size:30px;border-radius:0 0 0 0;overflow:hidden;border-color:#aaaaaa;border-bottom-width:1px;padding-top:0px!important;padding-bottom:15px!important;margin-top:0px!important}.et_pb_text_10.et_pb_text{color:#14b9ff!important}.et_pb_text_9{font-size:16px;line-height:0.5em;border-radius:0 0 0 0;overflow:hidden;border-top-width:1px;border-bottom-width:1px;border-color:#aaaaaa;padding-top:15px!important}.et_pb_text_9 p{line-height:0.5em}.et_pb_text_9.et_pb_text{color:#000000!important}.et_pb_column_9{background-color:#f1f1f1}.et_pb_text_8{font-weight:700;font-size:30px;border-radius:0 0 0 0;overflow:hidden;border-color:#aaaaaa;border-bottom-width:1px;padding-top:0px!important;padding-bottom:15px!important;margin-top:0px!important}.et_pb_text_8.et_pb_text{color:#14b9ff!important}.et_pb_text_7{font-size:16px;line-height:0.5em;border-radius:0 0 0 0;overflow:hidden;border-top-width:1px;border-bottom-width:1px;border-color:#aaaaaa;padding-top:15px!important}.et_pb_text_7 p{line-height:0.5em}.et_pb_text_7.et_pb_text{color:#000000!important}.et_pb_column_8{background-color:#f1f1f1}.et_pb_text_6{font-weight:700;font-size:30px;border-radius:0 0 0 0;overflow:hidden;border-color:#aaaaaa;border-bottom-width:1px;padding-top:0px!important;padding-bottom:15px!important;margin-top:0px!important}.et_pb_text_6.et_pb_text{color:#14b9ff!important}.et_pb_text_5{font-size:16px;line-height:0.5em;border-radius:0 0 0 0;overflow:hidden;border-top-width:1px;border-bottom-width:1px;border-color:#aaaaaa;padding-top:15px!important}.et_pb_text_5 p{line-height:0.5em}.et_pb_text_5.et_pb_text{color:#000000!important}.et_pb_row_5.et_pb_row{margin-left:auto!important;margin-right:auto!important}.et_pb_row_4.et_pb_row{margin-left:auto!important;margin-right:auto!important;padding-top:0;padding-right:0px;padding-bottom:27px;padding-left:0px}</style> <!--[if lte IE 8]>

			
			<div class="et_pb_module et_pb_pricing_tables_0 et_pb_pricing clearfix et_pb_pricing_3 et_pb_second_featured">
			<?php echo $_SESSION['idcustomer'].", ".$_SESSION['idsubscripcion']; ?>	
			<input type="hidden" class="field" id="miproducto" name="miplan" value="<?php echo  $producto; ?>">
				<div class="et_pb_pricing_table_wrap">
				<div class="et_pb_pricing_table et_pb_pricing_table_0" id="prod_EWoNxz2dBi8k3K">
				<div class="et_pb_pricing_heading">
					<h2 class="et_pb_pricing_title">Plan Individual</h2>
					<span class="et_pb_best_value">-</span>
				</div> <!-- .et_pb_pricing_heading -->
				<div class="et_pb_pricing_content_top">
					<span class="et_pb_et_price"><span class="et_pb_sum">Gratis</span></span>
					<input type="hidden" id="opcion1" name="opcion1" value="prod_EWoNxz2dBi8k3K">
				</div> <!-- .et_pb_pricing_content_top -->
				<div class="et_pb_pricing_content">
					<ul class="et_pb_pricing">
						<li><span>Cursos e-learning: 1</span></li><li class="et_pb_not_available"><span><span style="text-decoration: line-through;">Certificados (uno por curso)</span></span></li><li class="et_pb_not_available"><span><span style="text-decoration: line-through;">Número de usuarios</span></span></li><li><span>Seminarios virtuales</span></li><li><span>Acceso a Base de Conocimiento</span></li><li><span>Resumen de Noticias de Internet</span></li><li><span>Boletines informativos</span></li><li><span>Acceso a la aplicación móvil</span></li><li><span>Acceso a la plataforma de soporte</span></li><li><span>Soporte técnico personalizado</span></li><li><span>Descuentos en eventos y actividades</span></li>
					</ul>
				</div> <!-- .et_pb_pricing_content -->
				<div class="et_pb_button_wrapper"><a class="et_pb_button et_pb_pricing_table_button" href="https://pluscomply.com/?page_id=2332&producto=prod_EWoNxz2dBi8k3K">EMPEZAR</a></div>
			</div>
			
			<div class="et_pb_pricing_table et_pb_pricing_table_1 et_pb_featured_table" id="prod_EWoUFZ9Z03eiv7">	
				<div class="et_pb_pricing_heading">
					<h2 class="et_pb_pricing_title">Plan Corporativo</h2>
					<span class="et_pb_best_value">Silver</span>
					<input type="hidden" id="opcion2" name="opcion2" value="prod_EWoUFZ9Z03eiv7">
				</div> <!-- .et_pb_pricing_heading -->
				<div class="et_pb_pricing_content_top">
					<span class="et_pb_et_price"><span class="et_pb_dollar_sign" style="margin-left: -10px;">$</span><span class="et_pb_sum">990</span><span class="et_pb_frequency">/Anual</span></span>
				</div> <!-- .et_pb_pricing_content_top -->
				<div class="et_pb_pricing_content">
					<ul class="et_pb_pricing">
						<li><span><p align="left">Cursos e-learning: <span style="color: #2ea3f2;">6</span></p></span></li><li><span><p align="left">Certificados (uno por curso)</p></span></li><li><span><p align="left">Número de usuarios: <span style="color: #2ea3f2;">1 – 50</span></p></span></li><li><span><p align="left">Seminarios virtuales</p></span></li><li><span><p align="left">Acceso a Base de Conocimiento</p></span></li><li><span><p align="left">Resumen de Noticias de Internet</p></span></li><li><span><p align="left">Boletines informativos</p></span></li><li><span><p align="left">Acceso a la aplicación móvil</p></span></li><li><span><p align="left">Acceso a la plataforma de soporte</p></span></li><li><span><p align="left">Soporte técnico personalizad</p></span></li><li><span><p align="left">Descuentos en eventos y actividades</p></span></li>
					</ul>
				</div> <!-- .et_pb_pricing_content -->
				<div class="et_pb_button_wrapper"><a class="et_pb_button et_pb_pricing_table_button"   href="https://pluscomply.com/?page_id=2332&producto=prod_EWoUFZ9Z03eiv7">EMPEZAR</a></div><!---->
			</div>
			
			<div class="et_pb_pricing_table et_pb_pricing_table_2" id="prod_EWoXCo5olBGeez">		
				<div class="et_pb_pricing_heading">
					<h2 class="et_pb_pricing_title">Plan Corporativo</h2>
					<span class="et_pb_best_value">Premium</span>
					<input type="hidden" id="opcion3" name="opcion3" value="prod_EWoXCo5olBGeez">
				</div> <!-- .et_pb_pricing_heading -->
				<div class="et_pb_pricing_content_top">
					<span class="et_pb_et_price"><span class="et_pb_dollar_sign" style="margin-left: -10px;">$</span><span class="et_pb_sum">1990</span><span class="et_pb_frequency">/Anual</span></span>
				</div> <!-- .et_pb_pricing_content_top -->
				<div class="et_pb_pricing_content">
					<ul class="et_pb_pricing">
						<li><span>Cursos e-learning: <span style="color: #2ea3f2; font-weight: bold;">10</span></span></li><li><span>Certificados (uno por curso)</span></li><li><span>Número de usuarios: <span style="color: #2ea3f2; font-weight: bold;">1 – 50</span></span></li><li><span>Seminarios virtuales</span></li><li><span>Acceso a Base de Conocimiento</span></li><li><span>Resumen de Noticias de Internet</span></li><li><span>Boletines informativos</span></li><li><span>Acceso a la aplicación móvil</span></li><li><span>Acceso a la plataforma de soporte</span></li><li><span>Soporte técnico personalizado</span></li><li><span>Descuentos en eventos y actividades</span></li>
					</ul>
				</div> <!-- .et_pb_pricing_content -->
				<div class="et_pb_button_wrapper"><a class="et_pb_button et_pb_pricing_table_button"  href="https://pluscomply.com/?page_id=2332&producto=prod_EWoXCo5olBGeez">EMPEZAR</a></div>
			</div>
				</div>
			</div>
			</div> <!-- .et_pb_column -->
				
				
			</div> <!-- .et_pb_row -->
				
				
			</div> <!-- .et_pb_section --><div class="et_pb_section et_pb_section_1 et_section_regular">
				
				
				
				
					<div class="et_pb_row et_pb_row_1" style="">
				<div class="et_pb_column et_pb_column_4_4 et_pb_column_1    et_pb_css_mix_blend_mode_passthrough et-last-child">
				
				
				<div class="et_pb_module et_pb_text et_pb_text_1 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<h2 style="text-align: left;"><strong>Add-on para Individuos</strong></h2>
				</div>
			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column -->
				
				
			</div> <!-- .et_pb_row --><div class="et_pb_row et_pb_row_2 et_pb_gutters2 et_pb_row_4col" style="">
				<div class="et_pb_column et_pb_column_1_4 et_pb_column_2    et_pb_css_mix_blend_mode_passthrough">
				
				
				<div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_2 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;"><span style="font-weight: 400;">Curso e-Learning</span></p>
<p style="text-align: center;"><span style="font-weight: 400;">(incluye examen y certificado)</span></p>
<p style="text-align: center;">
				</p></div>
			</div> <!-- .et_pb_text --><div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_3 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;">40 USD</p>
				</div>
			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_4 et_pb_column_3    et_pb_css_mix_blend_mode_passthrough et_pb_column_empty">
				
				
				
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_4 et_pb_column_4    et_pb_css_mix_blend_mode_passthrough et_pb_column_empty">
				
				
				
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_4 et_pb_column_5    et_pb_css_mix_blend_mode_passthrough et_pb_column_empty">
				
				
				
			</div> <!-- .et_pb_column -->
				
				
			</div> <!-- .et_pb_row -->
				
				
			</div> <!-- .et_pb_section --><div class="et_pb_section et_pb_section_2 et_section_regular" style="">
				
				
				
				
					<div class="et_pb_row et_pb_row_3" style="">
				<div class="et_pb_column et_pb_column_4_4 et_pb_column_6    et_pb_css_mix_blend_mode_passthrough et-last-child">
				
				
				<div class="et_pb_module et_pb_text et_pb_text_4 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<h2 style="text-align: left;"><strong>Add-ons para Organizaciones</strong></h2>
				</div>
			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column -->
				
				
			</div> <!-- .et_pb_row --><div class="et_pb_row et_pb_row_4 et_pb_equal_columns et_pb_gutters2 et_pb_row_4col">
				<div class="et_pb_column et_pb_column_1_4 et_pb_column_7    et_pb_css_mix_blend_mode_passthrough">
				
				
				<div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_5 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p>&nbsp;</p>
<p style="text-align: center;"><span style="font-weight: 400;">Curso e-Learning</span></p>
<p style="text-align: center;">
				</p></div>
			</div> <!-- .et_pb_text --><div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_6 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;">200 USD</p>
				</div>
			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_4 et_pb_column_8    et_pb_css_mix_blend_mode_passthrough">
				
				
				<div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_7 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p>&nbsp;</p>
<p style="text-align: center;"><span style="font-weight: 400;">Usuario Adicional</span></p>
<p style="text-align: center;">
				</p></div>
			</div> <!-- .et_pb_text --><div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_8 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;">10 USD</p>
				</div>
			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_4 et_pb_column_9    et_pb_css_mix_blend_mode_passthrough">
				
				
				<div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_9 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;"><span style="font-weight: 400;"></span></p>
<p style="text-align: center;"><span style="font-weight: 400;">Usuarios – Grupos de +100</span></p>
<p style="text-align: center;"><span style="font-weight: 400;"></span></p>
				</div>
			</div> <!-- .et_pb_text --><div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_10 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;">5 USD</p>
				</div>
			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_4 et_pb_column_10    et_pb_css_mix_blend_mode_passthrough">
				
				
				<div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_11 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;"><span style="font-weight: 400;">Curso virtual en vivo </span></p>
<p style="text-align: center;"><span style="font-weight: 400;">60 minutos</span></p>
<p style="text-align: center;">
</p><h5 style="text-align: center;"><span style="font-weight: 400;">(25 participantes –&nbsp;&nbsp;</span><span style="font-weight: 400;">Incluye un certificado </span></h5>
<h5 style="text-align: center;"><span style="font-weight: 400;">empresarial PDF)</span></h5>
				</div>
			</div> <!-- .et_pb_text --><div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_12 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;">300 USD</p>
				</div>
			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column -->
				
				
			</div> <!-- .et_pb_row --><div class="et_pb_row et_pb_row_5 et_pb_equal_columns et_pb_gutters2 et_pb_row_4col">
				<div class="et_pb_column et_pb_column_1_4 et_pb_column_11    et_pb_css_mix_blend_mode_passthrough">
				
				
				<div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_13 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;"><span style="font-weight: 400;">Curso virtual en vivo </span></p>
<p style="text-align: center;"><span style="font-weight: 400;">120 minutos</span></p>
<p style="text-align: center;">
</p><h5 style="text-align: center;"><span style="font-weight: 400;">(25 participantes –&nbsp;&nbsp;</span><span style="font-weight: 400;">Incluye un certificado </span></h5>
<h5 style="text-align: center;"><span style="font-weight: 400;">empresarial PDF)</span></h5>
				</div>
			</div> <!-- .et_pb_text --><div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_14 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;">450 USD</p>
				</div>
			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_4 et_pb_column_12    et_pb_css_mix_blend_mode_passthrough">
				
				
				<div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_15 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p>&nbsp;</p>
<p style="text-align: center;">Exámenes por usuarios</p>
<p style="text-align: center;">(2 intentos)</p>
<p style="text-align: center;">
				</p></div>
			</div> <!-- .et_pb_text --><div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_16 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;">20 USD</p>
				</div>
			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_4 et_pb_column_13    et_pb_css_mix_blend_mode_passthrough">
				
				
				<div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_17 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;">
</p><p style="text-align: center;">
</p><p style="text-align: center;"><span style="font-weight: 400;">Certificado por usuario/curso </span></p>
<p style="text-align: center;"><span style="font-weight: 400;">(digital PDF)</span></p>
<p style="text-align: center;">
</p><p style="text-align: center;">
</p><p style="text-align: center;">
				</p></div>
			</div> <!-- .et_pb_text --><div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_18 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;">20 USD</p>
				</div>
			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_4 et_pb_column_14    et_pb_css_mix_blend_mode_passthrough">
				
				
				<div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_19 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;">
</p><p style="text-align: center;">
</p><p style="text-align: center;"><span style="font-weight: 400;">Capacitación presencial</span></p>
<p style="text-align: center;"><span style="font-weight: 400;">(sede del cliente)</span></p>
<p style="text-align: center;">
</p><p style="text-align: center;">
</p><p style="text-align: center;">
				</p></div>
			</div> <!-- .et_pb_text --><div class="et_pb_with_border et_pb_module et_pb_text et_pb_text_20 et_clickable et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;"><span style="font-size: small;">Solicitar Presupuesto</span></p>
				</div>
			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column -->
				
				
			</div> <!-- .et_pb_row --><div class="et_pb_row et_pb_row_6">
				<div class="et_pb_column et_pb_column_4_4 et_pb_column_15    et_pb_css_mix_blend_mode_passthrough et-last-child">
				
				
				<div class="et_pb_module et_pb_text et_pb_text_21 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<h2 align="center">Grandes organizaciones eligen a Plus Comply para<br>&nbsp;su formación en temas de Compliance</h2>
				</div>
			</div> <!-- .et_pb_text --><div class="et_pb_module et_pb_image et_pb_image_0 et_always_center_on_mobile">
				
				
				<span class="et_pb_image_wrap"><img src="https://pluscomply.com/wp-content/uploads/2019/Marcas.jpg" alt=""></span>
			</div>
			</div> <!-- .et_pb_column -->
				
				
			</div> <!-- .et_pb_row -->
				
				
			</div> <!-- .et_pb_section --><div class="et_pb_section et_pb_section_4 footerdivi et_pb_with_background et_section_regular" style="">
				
				
				
				
					<div class="et_pb_row et_pb_row_7">
				<div class="et_pb_column et_pb_column_4_4 et_pb_column_16    et_pb_css_mix_blend_mode_passthrough et-last-child">
				
				
				<div class="et_pb_button_module_wrapper et_pb_button_0_wrapper et_pb_button_alignment_center et_pb_module ">
				<a class="et_pb_button et_pb_button_0 et_pb_bg_layout_dark" href="https://pluscomply.com/?page_id=879">Ir a Planes de Membresía</a>
			</div><div class="et_pb_module et_pb_text et_pb_text_22 et_pb_bg_layout_light  et_pb_text_align_center">
				
				
				<div class="et_pb_text_inner">
					<p><a class="footerlink" href="https://pluscomply.com/?page_id=274">Nosotros</a><a class="footerlink" href="https://pluscomply.com/?page_id=1187">&nbsp; &nbsp; &nbsp; &nbsp;Recursos&nbsp;&nbsp;</a><a class="footerlink">&nbsp; &nbsp; &nbsp;Soporte&nbsp;&nbsp;</a><a class="footerlink" href="https://pluscomply.com/?page_id=221">&nbsp; &nbsp; &nbsp;Contáctanos</a></p>
				</div>
			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column -->
				
				
			</div> <!-- .et_pb_row --><div class="et_pb_row et_pb_row_8">
				<div class="et_pb_column et_pb_column_1_3 et_pb_column_17    et_pb_css_mix_blend_mode_passthrough">
				
				
				<div class="et_pb_module et_pb_code et_pb_code_0 SocialMedia">
				
				
				<div class="et_pb_code_inner">
					<div style="width: 175px !important; display: block; margin: auto;">
<a class="rrsslink"><img src="/wp-content/uploads/2019/01/93.png"></a>
<a class="rrsslink"><img src="/wp-content/uploads/2019/01/94.png"></a>
<a class="rrsslink"><img src="/wp-content/uploads/2019/01/95.png"></a>
<a class="rrsslink"><img src="/wp-content/uploads/2019/01/96.png"></a>
</div>
				</div> <!-- .et_pb_code_inner -->
			</div> <!-- .et_pb_code -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_3 et_pb_column_18    et_pb_css_mix_blend_mode_passthrough">
				
				
				<div class="et_pb_module et_pb_text et_pb_text_23 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<h3 style="text-align: center;"><strong>2018 IDEAS PUBLISHING SOLUTIONS&nbsp;</strong></h3>
<p style="text-align: center;">Cape Coral, Florida</p>
<p style="text-align: center;">Todos los derechos reservados</p>
				</div>
			</div> <!-- .et_pb_text --><div class="et_pb_module et_pb_text et_pb_text_24 et_pb_bg_layout_light  et_pb_text_align_left">
				
				
				<div class="et_pb_text_inner">
					<p style="text-align: center;"><a href="https://pluscomply.com/?page_id=3" class="footerlink">Privacy Policy</a>&nbsp; &nbsp; &nbsp;&nbsp;<a href="https://pluscomply.com/?page_id=103" class="footerlink">Terms of Use</a></p>
				</div>
			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_3 et_pb_column_19    et_pb_css_mix_blend_mode_passthrough et_pb_column_empty">
				
				
				
			</div> <!-- .et_pb_column -->
				
				
			</div> <!-- .et_pb_row -->
				
				
			</div> <!-- .et_pb_section -->			</div>
			
		</div>					</div>

				
				</article> <!-- .et_pb_post -->
								
							
							
					<?php
						//the_content();

					

						if ( ! $is_page_builder_used )
							wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->

				<?php
					if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
				?>

				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>

<?php if ( ! $is_page_builder_used ) : ?>

			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->

<?php endif; ?>

</div> <!-- #main-content -->

<?php
var_dump($producto);
get_footer();
	 ?>
	
<script type="text/javascript">

		window.onload=function() {
			if(document.getElementById('miproducto').value=="prod_EWoNxz2dBi8k3K")
			{
				 document.getElementById('prod_EWoNxz2dBi8k3K').style.display='none';
        
			}
	    else 
			   if(document.getElementById('miproducto').value=="prod_EWoUFZ9Z03eiv7")
				 {
					 document.getElementById('prod_EWoNxz2dBi8k3K').style.display='none';
           document.getElementById('prod_EWoUFZ9Z03eiv7').style.display='none';
           
				 }

				 else 
				   {
						document.getElementById('prod_EWoNxz2dBi8k3K').style.display='none';
            document.getElementById('prod_EWoUFZ9Z03eiv7').style.display='none';
						document.getElementById('prod_EWoXCo5olBGeez').style.display='none';
					 }

				    
				 
		}

 function popupmio()
 {
	//document.getElementById('x1').classList.add("is-visible");
 }
	


	

</script>

<!--<div class="cd-popup" id="x1" role="alert" style="display:none;">
   <div class="cd-popup-container">
      <p>Are you sure you want to delete this element?</p>
      <ul class="cd-buttons">
         <li><a href="#0">Yes</a></li>
         <li><a href="#0">No</a></li>
      </ul>
      <a href="#0" class="cd-popup-close img-replace">Close</a>
   </div> 
</div>--> 