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

if (isset($_POST['submit'])) //si on appuit sur "s'inscrire" qui a la valeur "submit"
{

	$id_annonce = "100";

	if (isset($_POST['ph1-url'])) {

		// Retrieve dataURL, format : data:image/png;base64,iVBORw0K...
		$data_url_1 = $_POST['ph1-url'];

		// decode dataURL
		$data_1 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data_url_1));

		// save image in /storage folder
		$img_name_1 = basename(htmlspecialchars($_FILES['ph1']['name'])); // image name
		$ph1_tmp = __DIR__ . '/storage/' . $img_name_1; // image path
		file_put_contents($ph1_tmp, $data_1); //store image

		echo $id_annonce . "<br />";
		echo $ph1_tmp . "<br />";

		if (file_exists($ph1_tmp)) //si un fichier a été rentré par utilisateur
		{

			$storage_path = __DIR__ . '/images/ph1_' . $id_annonce . '.jpg';
			upload_avatar($ph1_tmp, $storage_path);
		}

	}

// same treatment for second image
	if (isset($_POST['ph2-url'])) {
		// Retrieve dataURL, format : data:image/png;base64,iVBORw0K...
		$data_url_2 = $_POST['ph2-url'];

		// decode dataURL
		$data_2 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data_url_2));

		// save image in /storage folder
		$img_name_2 = basename(htmlspecialchars($_FILES['ph2']['name'])); // image name
		$ph2_tmp = __DIR__ . '/storage/' . $img_name_2;
		file_put_contents($ph2_tmp, $data_2); // store image

		// ADD IMAGE TREATMENT THERE

		echo $id_annonce . "<br />";
		echo $ph2_tmp . "<br />";

		if (file_exists($ph2_tmp)) //si un fichier a été rentré par utilisateur
		{

			$storage_path = __DIR__ . '/images/ph2_' . $id_annonce . '.jpg';
			upload_avatar($ph2_tmp, $storage_path);
			$ph2 = "ph2_" . $id_annonce;
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
<script src="js/load-image.all.min.js"></script>


<script type="text/javascript">

$(window).load(function () {
	function addImg(event, dest, current) {
		if (event.target.files && event.target.files[0]) {
			var file = event.target.files[0]; // Our input file

			loadImage(
				file,
				function (img, data) {
					// check if image type is defined
					if (img.type === "error") {
						console.error("Error loading image " + file.name);
					} else {
						// img is a <canvas> element, we need to convert it to a data URL
						var dataURL = img.toDataURL();

						// modify image src in DOM
						$(dest).attr("src", dataURL);

						// Add also an hidden input contaiing dataURL
						var name = $(current).attr("name");
						name += "-url";

						if ($("input[name*='" + name + "'").length) {
							// if hiddent input already exists
							//console.log($("input[name*='"+name+"'").attr('name'));
							$("input[name*='" + name + "'").remove();
						}

						$(current).after(
							`<input type='hidden' name='${name}' value='${dataURL}' />`
						);
					}
				},
				{
					orientation: true,
					maxWidth: 200,
				}
			);
		}
	}

	// add event listener to our form inputs
	$("#imgInp").change(function (e) {
		addImg(e, "#blah", this);
	});
	$("#imgInp2").change(function (e) {
		addImg(e, "#blah2", this);
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

<form id="form1" method="post" action="" enctype="multipart/form-data" >

<p class="p_form">
<label class="l_1">Photo n°1</label>
<input type='file' id="imgInp" value="test" name="ph1" data-url /><br />
<img class="img_form" id="blah" src="#" alt="" />
</p>

<p class="p_form">
<label class="l_1">Photo n°2</label>
<input type='file' id="imgInp2" value="test" name="ph2" data-url /><br />
<img class="img_form" id="blah2" src="#" alt="" />
</p>

<p>
<button style="width:150px;margin-top: 5px;margin-bottom:5px;font-family: Lato;color: white;background: rgb(0, 148, 222);" type="submit" name="submit">Valider</button>
    </p>

</form>

</body>

</html>