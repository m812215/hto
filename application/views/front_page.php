<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title>Hyppytoimintaorganisaattorin testisivu</title>

</head>

<body>
	<p>This could be like a header. This is a test page that attempts to simulate any page that the HTO might be embedded in.</p>
	
	<div id="hTOWrapper">
		<script type="text/javascript" src="/hto/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript">
			$.get("/hto/ajax/get_fragment", function(data) {
				$('#hTOWrapper').append(data);
			});
		</script>
	</div>
	
	<p>.. and this could be a footer. Between them the HTO should appear.</p>
</body>
