	<div id="submenu-field">
		<!--<div style="width: 190px; height: 37px; background: rgba(40, 123, 123, 1); font-family: Segoe; color: #FFF; font-size: 1em; text-align: center; padding: 14px 0 0 0; margin: 0px 0 0 0;">Новая сделка</div>-->
		<ul id="submenu">
			<?php
				if( count($subMenu) > 0 )
				{
					foreach( $subMenu as $sublist )
					{
						echo '<li>' . $sublist . '</li>';
					}
				}
			?>
		</ul>
	</div>