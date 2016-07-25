	<div id="hTOContainer">	
		<!-- HTO uses a "Silk" icons from http://www.famfamfam.com -->
		<?php /*<div id="iedebug" style="background:yellow;"></div> */ ?>
		
		<div id="hTOList"></div>	
		
		<div id="hTOInfo">
			<div id="hTOErrors"></div>
			
			<div id="hTOMessages"></div>		
		</div>
		
		<div id="hTOForm"></div>
		
		<script type="text/javascript" src="<?php echo URL::base(); ?>js/hto.js"></script>
		<script type="text/javascript" src="<?php echo URL::base(); ?>js/jquery.form.js"></script>
		<script type="text/javascript" src="<?php echo URL::base(); ?>js/jquery.url.js"></script>
		<script type="text/javascript" src="<?php echo URL::base(); ?>js/jquery.simpletip-1.3.1.min.js"></script>
		<script type="text/javascript">
			var hTORoot = '<?php echo URL::base();?>';
		
			$('head').append('<link rel="stylesheet" href="<?php echo URL::base(); ?>css/style.css" type="text/css" media="all" />');
			
			$('#hTOContainer').prepend('<h1>Hyppytoimintaorganisaattori</h1>');

			hTOInit();
		</script>
	</div>