		<meta charset="utf-8" />
		<meta name="author" content="www.frebsite.nl" />
		<meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />

		<title>jQuery.mmenu demo</title>

		<!--link type="text/css" rel="stylesheet" href="https://happymatchmaking.herokuapp.com/jQuery.mmenu-master/demo/css/demo.css" /-->
		<link type="text/css" rel="stylesheet" href="https://happymatchmaking.herokuapp.com/jQuery.mmenu-master/dist/css/jquery.mmenu.css" />
		<link type="text/css" rel="stylesheet" href="https://happymatchmaking.herokuapp.com/jQuery.mmenu-master/dist/addons/dragopen/jquery.mmenu.dragopen.css" />
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.css"/>
<script src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.js"></script>
		<!-- for the one page layout -->
		<style type="text/css">
			#intro,
			#first,
			#second,
			#third
			{
				height: 400px;
			}
			#intro
			{
				padding-top: 0;
			}
			#first,
			#second,
			#third
			{
				border-top: 1px solid #ccc;
				padding-top: 150px;
			}
		</style>

		<!-- for the fixed header -->
		<style type="text/css">
			.header,
			.footer
			{
				box-sizing: border-box;
				width: 100%;
				position: fixed;
			}
			.header
			{
				top: 0;
			}
			.footer
			{
				bottom: 0;
			}
		</style>

		<script type="text/javascript" src="https://hammerjs.github.io/dist/hammer.min.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.0.js"></script>

		<script type="text/javascript" src="https://happymatchmaking.herokuapp.com/jQuery.mmenu-master/dist/js/jquery.mmenu.min.js"></script>
		<script type="text/javascript" src="https://happymatchmaking.herokuapp.com/jQuery.mmenu-master/dist/addons/dragopen/jquery.mmenu.dragopen.min.js"></script>
		<script type="text/javascript" src="https://happymatchmaking.herokuapp.com/jQuery.mmenu-master/dist/addons/fixedelements/jquery.mmenu.fixedelements.min.js"></script>
		<script type="text/javascript">
			$(function() {
				var $menu = $('nav#menu'),
					$html = $('html, body');

				$menu.mmenu({
					dragOpen: true
				});

				var $anchor = false;
				$menu.find( 'li > a' ).on(
					'click',
					function( e )
					{
						$anchor = $(this);
					}
				);

				var api = $menu.data( 'mmenu' );
				api.bind( 'closed',
					function()
					{
						if ( $anchor )
						{
							var href = $anchor.attr( 'href' );
							$anchor = false;

							//	if the clicked link is linked to an anchor, scroll the page to that anchor
							if ( href.slice( 0, 1 ) == '#' )
							{
								$html.animate({
									scrollTop: $( href ).offset().top
								});
							}
						}
					}
				);
			});
		</script>