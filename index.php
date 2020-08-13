<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';

use Gumlet\ImageResize;
use lsolesen\pel\PelJpeg;
/**
 * Handle images form submit
 */
if (isset($_POST["submit"])) {

	$id_annonce = "100";
	$extensions = ["jpeg", "jpg", "png", "gif"];

	if (!empty($_FILES)) {

		// Loop through files
		foreach ($_FILES as $input_name => $file) {

			// Retrieve file extension
			$tmp = explode('.', $file['name']);
			$file_ext = strtolower(end($tmp));

			// Check extension validity
			if (in_array($file_ext, $extensions)) {

				// Store and resize image
				$storage_path = __DIR__ . "/images/" . $input_name . "_" . $id_annonce . "." . $file_ext;

				$file_tmp = $file['tmp_name'];

				// get exif data
				$jpeg = new PelJpeg($file_tmp);
				$exif = $jpeg->getExif();

				// resize image
				$image = new ImageResize($file_tmp);
				$image->resizeToBestFit(609, 455);
				$image->save($storage_path, IMAGETYPE_JPEG);

				// add exif to output image
				if (isset($exif) && !empty($exif)) {
					$jpeg = new PelJpeg($storage_path);
					$jpeg->setExif($exif);
					$jpeg->saveFile($storage_path);
				}

			}

		}

	}

}

?>



<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<meta http-equiv="content-type" content="text/html; charset=UTF-8">

<script type='text/javascript' src='https://code.jquery.com/jquery-1.9.1.js'></script>

<script type="text/javascript">

// TODO : move to a script file (.js) and import
$(window).load(function () {

function readURL(input, imgID) {
	if (input.files && input.files[0]) {
		let reader = new FileReader();

		reader.onload = function (e) {
			$(imgID).attr("src", e.target.result);
		};

		reader.readAsDataURL(input.files[0]); // convert to base64 string
	}
}

$("#imgInp").change(function () {
	readURL(this, "#img1");
});

$("#imgInp2").change(function () {
	readURL(this, "#img2");
});

});
</script>


<style type="text/css">
<!-- TODO : move to stylesheet -->
.p_form
{
	color: rgba(90, 89, 89, 0.98);
	font-style: normal;
	font-weight: 700;
	font-size: 16px;
	font-family: Lato;
}

form
{
	background-color: #fff;
	padding-top:20px;
}

.img_form
{
	margin-left:315px;
	max-width:200px;
	height:auto;
	margin-top:5px
}

.submit-btn
{
	width:150px;
	margin-top:5px;
	margin-bottom:5px;
	font-family: Lato;
	color: white;
	background: rgb(0, 148, 222);
}

</style>
</head>

<body>

<form id="form1" method="post" action="" enctype="multipart/form-data">

	<p class="p_form">
		<label class="l_1">Photo n°1</label>
		<input type='file' id="imgInp" name="ph1" /><br />
		<img class="img_form" id="img1" src="#" alt="" />
	</p>

	<p class="p_form">
		<label class="l_1">Photo n°2</label>
		<input type='file' id="imgInp2" name="ph2" /><br />
		<img class="img_form" id="img2" src="#" alt="" />
	</p>

	<p>
		<button type="submit" class="submit-btn" name="submit" value="submit_files">Valider</button>
    </p>

</form>

</body>

</html>