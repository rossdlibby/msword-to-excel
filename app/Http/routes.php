<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');
Route::post('/', function(){
	$file = Input::file('file');
	/**
	 * Move file to /uploads
	 */
	$path = $file->move(__DIR__.'uploads/'.$file->getClientOriginalName());

	/**
	 * Read .docx file
	 */
	function read_file_docx($filename)
	{
		$striped_content = '';
		$content = '';
		$zip = zip_open($filename);
		if (!$zip || is_numeric($zip))
			return false;
		while ($zip_entry = zip_read($zip))
		{
			if (zip_entry_open($zip, $zip_entry) == FALSE)
				continue;
			if (zip_entry_name($zip_entry) != "word/document.xml")
				continue;
			$content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			zip_entry_close($zip_entry);
		}
		// end while zip_close($zip);
		//echo $content;
		//echo "<hr>";
		//file_put_contents('1.xml', $content);
		$content = str_replace('</w:r></w:p></w:tc><w:tc>', "", $content);
		$content = str_replace('</w:t></w:r><w:r><w:rPr><w:rFonts w:ascii="Arial" w:eastAsia="Times New Roman" w:hAnsi="Arial" w:cs="Arial"/><w:color w:val="414141"/><w:sz w:val="21"/><w:szCs w:val="21"/></w:rPr><w:t>,', "][", $content);
//		$content = str_replace('</w:r></w:p>', "'\r\n'", $content);
		$content = str_replace('Miles', "Miles', 'address' => '", $content);
		$content = str_replace('Phone:</w:t></w:r><w:r w:rsidRPr="004263EF"><w:rPr><w:rFonts w:ascii="Arial" w:eastAsia="Times New Roman" w:hAnsi="Arial" w:cs="Arial"/><w:color w:val="414141"/><w:sz w:val="21"/><w:szCs w:val="21"/></w:rPr><w:t> ', "', 'phone' => '", $content);
		$content = str_replace('[', "[name' => '", $content);
		$content = str_replace(']', "]", $content);
		$content = str_replace('`', " ", $content);
		$decimals = [];
		preg_match_all("/[\d*]\.\d*/", $content, $decimals);
		foreach($decimals[0] as $val)
		{
			$content = preg_replace("/[\d*]\.\d*/", "'".$val, $content);
		}
		preg_match_all("/[\d*]\.\d*/", $content, $decimals);
		$content = str_replace("'''''''''''''''''''''", ", 'distance' => '", $content);
		$content = str_replace(", 'distance", "', 'distance", $content);
		$license = [];
		$content = str_replace("</w:t></w:r></w:p><w:p w:rsidR=\"00AD11AF\" w:rsidRPr=\"004263EF\" w:rsidRDefault=\"00AD11AF\" w:rsidP=\"00AD11AF\"><w:pPr><w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"F7F7F7\"/><w:spacing w:after=\"0\" w:line=\"240\" w:lineRule=\"exact\"/><w:contextualSpacing/><w:mirrorIndents/><w:rPr><w:rFonts w:ascii=\"Arial\" w:eastAsia=\"Times New Roman\" w:hAnsi=\"Arial\" w:cs=\"Arial\"/><w:color w:val=\"414141\"/><w:sz w:val=\"21\"/><w:szCs w:val=\"21\"/></w:rPr></w:pPr><w:r w:rsidRPr=\"004263EF\"><w:rPr><w:rFonts w:ascii=\"Arial\" w:eastAsia=\"Times New Roman\" w:hAnsi=\"Arial\" w:cs=\"Arial\"/><w:color w:val=\"414141\"/><w:sz w:val=\"21\"/><w:szCs w:val=\"21\"/></w:rPr><w:t>(", "'(", $content);
		$content = str_replace("MD'(", "', 'license' => 'MD(", $content);
		$striped_content = strip_tags($content);
		$content = nl2br($striped_content);
		return $content;
	}

	$filename = $path;

	$content = read_file_docx($filename);
	if($content)
	{
		$content = substr($content, 0, -13);
		$content = explode("][", $content);
		$content[0] = "name' => '".substr($content[0], 8);
		for($i = 0; $i < count($content); $i++)
		{
			$content[$i] = explode("', '", $content[$i]);
			for($j = 0; $j < count($content[$i]); $j++)
			{
				$arr = explode("' => '", $content[$i][$j]);
				$assocArr = [$arr[0] => trim($arr[1])];
				$content[$i][$j] = $assocArr;
			}
			$newContent = [];
			foreach($content[$i] as $row)
			{
				$newContent = array_merge($newContent, $row);
			}
			$content[$i] = $newContent;
		}
//		var_dump($content);
		$row = $content;
		Excel::create('Filename', function($excel) use($row) {

			$excel->sheet('Sheetname', function($sheet) use($row) {

				$sheet->fromArray($row, null, 'A1', true);
				$sheet->row(1, function($row){
					$row->setFontWeight('bold')->setBackground('#2e2e2e')->setFontColor('#ffffff');
				});

			});

		})->export('xls');
	}
	else
		echo "Sorry, we couldn't read that file.";
});

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);