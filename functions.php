<?php

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
			//

		}
	}
} // fin de creation de fonction