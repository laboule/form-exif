<?php
session_start();

function upload_avatar($ph_tmp, $storage_path) // création de la fonction upload image
{

	if (file_exists($ph_tmp)) //si un fichier a été rentré par utilisateur
	{
		$image_size = getimagesize($ph_tmp); // fonction renvoi largeur ,hauteur et l'extension de l'image

		// on récupere l'extension et on verifie que le contenue est bien une image au cas ou l'utilisateur mettrer un script php a l'intérieur
		if ($image_size['mime'] == 'image/jpeg') //jpg compris dans le jpeg
		{
			$image_src = imagecreatefromjpeg($ph_tmp);
		} else if ($image_size['mime'] == 'image/png') {
			$image_src = imagecreatefrompng($ph_tmp);
		} else if ($image_size['mime'] == 'image/gif') {
			$image_src = imagecreatefromgif($ph_tmp);
		} else {
			$res_req = "Votre image n'est pas valide , veuillez en choisir une autre !";
			$image_src = false;
		}

		if ($image_src !== false) {
			$image_width = 609;

			if ($image_size[0] > $image_width) {
				/*
							$image_finale=$image_src;
							}else{
							*/

				$new_width[0] = $image_width;
				$new_height[1] = ($image_size[1] / $image_size[0]) * $image_width;
				if ($new_height[1] > 455) {
					$new_width[0] = (609 / $new_height[1]) * 455;
					$new_height[1] = 455;
				}
				$image_finale = imagecreatetruecolor($new_width[0], $new_height[1]);

				$bg = imagecolorallocate($image_finale, 255, 255, 255);
				imagefill($image_finale, 0, 0, $bg);
				imagecopy($image_finale, $image, 0, 0, 0, 0, $width, $height);

				imagecopyresampled($image_finale, $image_src, 0, 0, 0, 0, $new_width[0], $new_height[1], $image_size[0], $image_size[1]);
			}

			if ($image_size[0] <= $image_width) {
				if ($image_size[1] <= 455) {
					$image_finale = $image_src;
				} else {
					$new_width[0] = ($image_size[0] / $image_size[1]) * 455;
					$new_height[1] = 455;

					$image_finale = imagecreatetruecolor($new_width[0], $new_height[1]);

					$bg = imagecolorallocate($image_finale, 255, 255, 255);
					imagefill($image_finale, 0, 0, $bg);
					imagecopy($image_finale, $image, 0, 0, 0, 0, $width, $height);

					imagecopyresampled($image_finale, $image_src, 0, 0, 0, 0, $new_width[0], $new_height[1], $image_size[0], $image_size[1]);
				}
			}

			imagejpeg($image_finale, $storage_path); // fonction qui envoi vers le dossier choisi
			//imagejpeg($image_finale,'images/'.$testim.'.jpg');

		}
	}
} // fin de creation de fonction

// Handle Form Submit
if (isset($_POST["submit"])) {

	$id_annonce = "100";
	$extensions = array("jpeg", "jpg", "png", "gif");

	if (!empty($_FILES)) {
		// Loop through files
		foreach ($_FILES as $key => $file) {

			$tmp = explode('.', $file['name']);
			$file_ext = strtolower(end($tmp));

			// check file extension
			if (in_array($file_ext, $extensions)) {

				$storage_path = __DIR__ . "/images/" . $key . "_" . $id_annonce . "." . $file_ext;

				$file_tmp = $file['tmp_name'];

				move_uploaded_file($file_tmp, $storage_path);
				// upload_avatar($ph1_tmp, $storage_path);

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

$(window).load(function () {

function readURL(input, imgID) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $(imgID).attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$("#imgInp").change(function() {
  readURL(this, "#img1");
});

$("#imgInp2").change(function() {
  readURL(this, "#img2");
});


});
</script>


<style type="text/css">
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
		<button style="width:150px;margin-top: 5px;margin-bottom:5px;font-family: Lato;color: white;background: rgb(0, 148, 222);" type="submit" name="submit" value="submit_files">Valider</button>
    </p>

</form>

</body>

</html>