<div id="content" style="margin-left: 100px;">
	<div>
	<h4>Настройки статусов сделок</h4>
	<table>
		<tr>
			<th>Название</th>
			<th>Активная</th>
			<th style="width: 10%;">Меню</th>
			<th style="width: 1%;">ЧПУ</th>
			<th style="width: 7%;">Цвет</th>
			<th style="width: 2%;">Сортировка</th>
		</tr>
	<?php
		foreach($stateDeal as $listStateDeal)
		{
			echo '<tr>';
				echo '<td><input type="text" value="' . $listStateDeal['value'] . '" id="status-deal-' . $listStateDeal['id'] . '" /></td>';
			if($listStateDeal['active'] == 1)
			{
				$checkActive = 'checked';
			}
			else
			{
				$checkActive = '';
			}
				echo '<td><input type="checkbox" ' . $checkActive . ' /></td>';

			if($listStateDeal['menu'] == 1)
			{
				$checkActive = 'checked';
			}
			else
			{
				$checkActive = '';
			}
				echo '<td><input type="checkbox" ' . $checkActive . ' /></td>';
				echo '<td><input type="text" value="' . $listStateDeal['rewrite'] . '" /></td>';
				echo '<td><input type="text" value="' . $listStateDeal['color'] . '" style="background: #' . $listStateDeal['color'] . '" /></td>';
				echo '<td><input type="text" value="' . $listStateDeal['order_deals'] . '" /></td>';
		}
	?>
	</table>
	</div>	

</div><!--/content-->