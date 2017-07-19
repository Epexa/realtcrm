<?php

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://realtcrm.com/api/getBoards');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$objects = json_decode(curl_exec($ch), TRUE);
	curl_close($ch);

	$html = '';

	foreach ($objects as $index => $object)
	{
		if (isset($object['studios'][1])) $rowspan = ' rowspan="' . count($object['studios']) . '"'; else $rowspan = '';
		if ($index % 2 == 0) $tr_class = ' class="active"'; else $tr_class = '';
		$html .= '
		<tr' . $tr_class . '>
			<td' . $rowspan . ' style="vertical-align: middle;">' . ($object['url'] ? '<a class="object-name" target="_blank" href="' . $object['url'] . '" data-preview-photo="' . $object['preview_photo'] . '">' . $object['name'] . '</a>' : $object['name']) . '</td>
			<td' . $rowspan . ' style="vertical-align: middle;">' . $object['comments'] . '</td>
			<td' . $rowspan . ' style="vertical-align: middle;">' . $object['route'] . '</td>';
		if (isset($object['studios'][0])) $html .= '
			<td>' . $object['studios'][0]['studio'] . '</td>
			<td>' . $object['studios'][0]['floor'] . '</td>
			<td>' . $object['studios'][0]['area'] . '</td>
			<td>' . $object['studios'][0]['status'] . '</td>
			<td>' . number_format($object['studios'][0]['price'], 0, '.', ' ') . ' руб.</td>';
		$html .= '
			<td' . $rowspan . ' style="vertical-align: middle;">' . $object['registration'] . '</td>
			<td' . $rowspan . ' style="vertical-align: middle;">' . $object['metro'] . '</td>
		</tr>';
		if (isset($object['studios'][1]))
		{
			foreach ($object['studios'] as $index => $studio)
			{
				if ($index == 0) continue;
				$object_status = mb_strtolower($studio['status'], 'UTF-8');
				if ($object_status == 'продана') $temp_tr_class = ' class="danger"'; else $temp_tr_class = $tr_class;
				$html .= '
				<tr' . $temp_tr_class . '>
					<td>' . $studio['studio'] . '</td>
					<td>' . $studio['floor'] . '</td>
					<td>' . $studio['area'] . '</td>
					<td><span class="' . ($object_status == 'бронь' ? 'text-danger' : '') . '">' . $studio['status'] . '</span></td>
					<td>' . number_format($studio['price'], 0, '.', ' ') . ' руб.</td>
				</tr>';
			}
		}
	}

?>


<link rel="stylesheet" href="board.min.css">
<link rel="stylesheet" href="jquery.qtip.min.css">
<style>
.text-danger {
    color: red;
}
.qtip-bootstrap {
	max-width: 1000px;
}	
</style>
<table class="table table-bordered table-condensed">
	<thead>
		<th>Объект</th>
		<th>Комментарий</th>
		<th>Направление</th>
		<th>Студии</th>
		<th>Этаж</th>
		<th>Общая площадь (кв.м.) и описание</th>
		<th style="min-width: 130px;">Статус</th>
		<th style="min-width: 130px;">Цена студии</th>
		<th>Прописка</th>
		<th>Метро</th>
	</thead>
	<tbody><?=$html?>
	</tbody>
</table>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="jquery.qtip.min.js"></script>
<script>
$(function() {

	$('.object-name').each(function() {
		$(this).qtip({
			style: {classes: 'qtip-bootstrap'},
			content: '<img src="' + $(this).data('preview-photo') + '">'
		});
	});

});
</script>