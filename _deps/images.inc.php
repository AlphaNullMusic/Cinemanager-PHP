<?

function image_resize_auto($src, $dst, $size) {
	global $config;
	$sizes = $config['image_sizes'];
	
	// Check that our size exists
	if (!isset($sizes[$size])) {
		return false;
	}

	$image_done = false;

	// Create the image resource
	$image_resource = image_create($src);

	if (!$image_resource) {
		return false;
	}

	// Now get the extension of the output image
	$image = image_info($src);
	$mime = $image['mime'];
	
	switch ($mime) {
		case 'image/gif':
		case 'image/png':
		case 'image/jpg':
		case 'image/jpeg':
		case 'image/bmp':
		case 'image/wbmp':
			$ext = 'jpg';
			break;

		default:
			// Unworkable file type
			return false;
			break;

	}

	// Do we need to crop or resize this image?
	if (isset($sizes[$size]['crop_style'])) {

		// What sort of cropping are we doing to this image?
		switch ($sizes[$size]['crop_style']) {
			case 'narrow_centre':
				// Get the plane which is closest to the crop edge
				if ($image['width'] >= $sizes[$size]['width'] && $image['height'] >= $sizes[$size]['height']) {
					// Do a proportional comparison of the baseline for image cropping
					if (($image['width'] / $sizes[$size]['width']) <= ($image['height'] / $sizes[$size]['height'])) {
						$baseline = 'width';
					}
					else {
						$baseline = 'height';
					}
				}
				elseif ($image['width'] >= $sizes[$size]['width']) {
					$baseline = 'nc_width';
				}
				elseif ($image['height'] >= $sizes[$size]['height']) {
					$baseline = 'nc_height';
				}
				else {
					// we have a problem
					$baseline = 'none';
				}

				$background = isset($sizes[$size]['background']) ? $sizes[$size]['background'] : null;

				switch ($baseline) {
					case 'width':
						/*	The width is narrower than the height, so move the top down and leave the
								left at 0
							 _____
							|     |
							|-----|
							| XXX |
							|-----|
							|_____|

						*/

						// Which parts of the image are we going to use?
						$src_width = $image['width'];
						$src_height = floor($image['width'] * $sizes[$size]['height']/$sizes[$size]['width']);
						$src_x = 0;
						$src_y = floor(($image['height'] - $src_height)/2);

						// And where are we going to put them?
						$dst_width = $sizes[$size]['width'];
						$dst_height = $sizes[$size]['height'];
						$dst_x = 0;
						$dst_y = 0;
						break;

					case 'height':
						/*	This image needs to be cropped such that the top is at 0 and the left moves
								in half the scaled difference
							 _________
							|  | X |  |
							|  | X |  |
							|__|_X_|__|

						*/

						// Which parts of the image are we going to use?
						$src_width = floor($image['height'] * $sizes[$size]['width']/$sizes[$size]['height']);
						$src_height = $image['height'];
						$src_x = floor(($image['width'] - $src_width)/2);
						$src_y = 0;

						// And where are we going to put them?
						$dst_width = $sizes[$size]['width'];
						$dst_height = $sizes[$size]['height'];
						$dst_x = 0;
						$dst_y = 0;
						break;

					case 'nc_width':
						// Leave the height as it is, crop the width to suit the final size and centre

						// The destination dimensions
						$dst_x = 0;
						$dst_y = floor(($sizes[$size]['height'] - $image['height'])/2);
						$dst_width = $sizes[$size]['width'];
						$dst_height = $image['height'];

						// The source start dimensions
						$src_x = floor(($image['width'] - $sizes[$size]['width'])/2);
						$src_y = 0;
						$src_width = $sizes[$size]['width'];
						$src_height = $image['height'];
						break;

					case 'nc_height':
						// Leave the width as it is, crop the height to suit the final size and centre

						// The source start dimensions
						$src_width = $image['width'];
						$src_height = $sizes[$size]['height'];
						$src_x = 0;
						$src_y = floor(($image['height'] - $src_height)/2);

						// The destination dimensions
						$dst_width = $src_width;
						$dst_height = $src_height;
						$dst_x = floor(($sizes[$size]['width'] - $dst_width)/2);
						$dst_y = 0;
						break;

					case 'none':
						// The image is too small for narrow_centre cropping

						// The source start dimensions
						$src_width = $image['width'];
						$src_height = $image['height'];
						$src_x = 0;
						$src_y = 0;

						// The destination dimensions
						$dst_width = $src_width;
						$dst_height = $src_height;
						$dst_x = floor(($sizes[$size]['width'] - $dst_width) / 2);
						$dst_y = floor(($sizes[$size]['height'] - $dst_height) / 2);
						break;
				}

				$image_resource = image_resize_crop($image_resource, $sizes[$size]['width'], $sizes[$size]['height'], $dst_x, $dst_y, $dst_width, $dst_height, $src_x, $src_y, $src_width, $src_height, $background);
				break;

		}

	}
	else {
		$image_resource = image_resize($image_resource, $sizes[$size]['width'], $sizes[$size]['height']);
	}

	// Do we need to do any postprocessing?
	if (isset($sizes[$size]['postprocess'])) {
		foreach (explode('|', $sizes[$size]['postprocess']) as $effect_call) {
			$details = explode(':', $effect_call);
			$effect = $details[0];

			switch ($effect) {
				case 'greyscale':
					$image_resource = image_greyscale($image_resource);
					break;

				case 'overlay':
					// options are
					// <src image>:<position>[:<divisions per edge>]
					// <image>:<tl|tm|tr|ml|mm|mr|bl|bm|br>[:<1|2|3>]
					if (isset($details[3])) {
						$details[3] = $details[3];
					}
					else {
						$details[3] = 1;
					}

					$image_resource = image_overlay($image_resource, $details[1], $details[2], $details[3]);
					break;

			}
		}
	}

	if (!is_dir(dirname($dst))) {
		mkdir(dirname($dst));
	}

	// Do we have an output format we want to use?
	if (isset($sizes[$size]['output'])) {
		// Explode!
		$output = explode(':', $sizes[$size]['output']);

		$ext = $output[0];
		if (count($output) == 2) {
			$quality = $output[1];
		}
	}
	else {
		$quality = 85;
	}

	switch ($ext) {
		case 'jpg':
			imagejpeg($image_resource, $dst, $quality);
			break;

		case 'png':
			imagepng($image_resource, $dst);
			break;

	}
	
	chmod($dst,0755);
	return true;
	
}

function &image_create($src) {

	// May need more memory for this process
	ini_set("memory_limit","500M");
	
	// Fetch the type of the source image
	$image_details = image_info($src);
	$mime = $image_details['mime'];

	// Create a new image
	$im = false;
	switch ($mime) {
		case 'image/gif':
			$ext = 'png';
			$im = imagecreatefromgif($src);
			break;

		case 'image/png':
			$ext = 'png';
			$im = imagecreatefrompng($src);
			break;

		case 'image/jpg':
		case 'image/jpeg':
			$ext = 'jpg';
			$im = imagecreatefromjpeg($src);
			break;

		case 'image/bmp':
		case 'image/wbmp':
			$ext = 'png';
			$im = imagecreatefromwbmp($src);
			break;

		default:
			// Unworkable file type
			return false;
			break;

	}

	return $im;
}

function &image_overlay($im, $overlay, $position, $blocks_per_side) {
	$src_width = imagesx($im);
	$src_height = imagesy($im);

	// Load the overlay image
	$im2 = image_create($overlay);

	$overlay_width = imagesx($im2);
	$overlay_height = imagesy($im2);

	if (!$im2) {
		return $im;
	}

	// If we're dealing with territories, work out the maximum for each here
	$restrict = array(
		intval(1/$blocks_per_side * $src_width),
		intval(1/$blocks_per_side * $src_height)
		);

	// These are the vars we need to overlay the image
	//$dest_x, $dest_y, $overlay_x, $overlay_y, $overlay_w, $overlay_h;

	// These two will always be the same, everything else needs to be based on them
	$overlay_w = $restrict[0] < $overlay_width ? $restrict[0] : $overlay_width;
	$overlay_h = $restrict[1] < $overlay_height ? $restrict[1] : $overlay_height;

	switch ($position) {
		case 'tl':
			// Align it in the top left of the source image
			$dest_x = 0;
			$dest_y = 0;

			$overlay_x = 0;
			$overlay_y = 0;
			break;

		case 'tm':
			// Align it in the top middle of the source image
			$dest_x = ($src_width - $overlay_w)/2;
			$dest_y = 0;

			$overlay_x = ($overlay_width - $overlay_w)/2;
			$overlay_y = 0;
			break;

		case 'tr':
			// Align it in the top right of the source image
			$dest_x = $src_width - $overlay_w;
			$dest_y = 0;

			$overlay_x = $overlay_width - $overlay_w;
			$overlay_y = 0;
			break;

		case 'ml':
			// Align it in the middle left of the source image
			$dest_x = 0;
			$dest_y = ($src_height - $overlay_h)/2;

			$overlay_x = 0;
			$overlay_y = ($overlay_height - $overlay_h)/2;
			break;

		case 'mm':
			// Align it in the very middle of the source image
			$dest_x = $src_width/2 - $overlay_w/2;
			$dest_y = $src_height/2 - $overlay_h/2;

			$overlay_x = ($overlay_width - $overlay_w)/2;
			$overlay_y = ($overlay_height - $overlay_h)/2;
			break;

		case 'mr':
			// Align it in the top right of the source image
			$dest_x = $src_width - $overlay_w;
			$dest_y = ($src_height - $overlay_h)/2;

			$overlay_x = $overlay_width - $overlay_w;
			$overlay_y = ($overlay_height - $overlay_h)/2;
			break;

		case 'bl':
			// Align it in the top left of the source image
			$dest_x = 0;
			$dest_y = $src_height - $overlay_h;

			$overlay_x = 0;
			$overlay_y = $overlay_height - $overlay_h;
			break;

		case 'bm':
			// Align it in the bottom middle of the source image
			$dest_x = ($src_width - $overlay_w)/2;
			$dest_y = ($overlay_h - $src_height) * -1;

			$overlay_x = ($overlay_width - $overlay_w)/2;
			$overlay_y = $overlay_height - $overlay_h;
			break;

		case 'br':
			// Align it in the top right of the source image
			$dest_x = $src_width - $overlay_w;
			$dest_y = $src_height - $overlay_h;

			$overlay_x = $overlay_width - $overlay_w;
			$overlay_y = $overlay_height - $overlay_h;
			break;

		default:
			// We're not supposed to be here, man!
			return false;

	}

	// Copy the overlay on to the image
	imagecopy($im, $im2, $dest_x, $dest_y, $overlay_x, $overlay_y, $overlay_w, $overlay_h);

	imagedestroy($im2);

	return $im;
}

function &image_greyscale($im) {
	$src_width = imagesx($im);
	$src_height = imagesy($im);

	// Creating the Canvas
	$bwimage= imagecreate($src_width, $src_height);

	//Creates the 256 color palette
	for ($c = 0; $c < 256; $c++) {
		$palette[$c] = imagecolorallocate($bwimage, $c, $c, $c);
	}

	//Reads the origonal colors pixel by pixel
	for ($y=0; $y < $src_height; $y++) {
		for ($x=0; $x < $src_width; $x++) {
			$rgb = imagecolorat($im, $x, $y);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;

			// use yiq to modify the colours to greyscale
			$gs = (($r*0.299)+($g*0.587)+($b*0.114));

			imagesetpixel($bwimage, $x, $y, $palette[$gs]);
		}
	}

	// Now copy the black and white image over the top of the original canvas
	imagecopy($im, $bwimage, 0, 0, 0, 0, $src_width, $src_height);

	imagedestroy($bwimage);

	return $im;
}

function &image_resize($im, $dst_width, $dst_height) {
	$src_width = imagesx($im);
	$src_height = imagesy($im);

	// What size is our final image going to be?
	if ((!$dst_width && !$dst_height) || $src_width <= $dst_width && ($dst_height && ($src_height <= $dst_height) || !$dst_height)) {
		return $im;
	}

	// Which axis is proportionally larger?
	$p_width = $src_width / $dst_width;
	$p_height = $src_height / $dst_height;

	if ($dst_width && ($p_width < $p_height)) {
		$dst_width = ($dst_height / $src_height) * $src_width;
	}
	else {
		$dst_height = ($dst_width / $src_width) * $src_height;
	}

	$im2 = imagecreatetruecolor($dst_width, $dst_height);
	imagecopyresampled($im2, $im, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
	imagedestroy($im);

	return $im2;
}

function &image_resize_crop($im, $op_width, $op_height, $dst_x, $dst_y, $dst_width, $dst_height, $src_x, $src_y, $src_width, $src_height, $background = null) {
	$im2 = imagecreatetruecolor($op_width, $op_height);

	if (!is_null($background)) {
		$colour = colour_from_hex($background);

		$bgcolour = imagecolorallocate($im2, $colour['red'], $colour['green'], $colour['blue']);
		imagefill($im2, 0, 0, $bgcolour);
	}

	if ($src_width < $dst_width && $src_height < $dst_height) {
		imagecopy($im2, $im, $left, $top, 0, 0, $src_width, $src_height);
	}
	else {
		imagecopyresampled($im2, $im, $dst_x, $dst_y, $src_x, $src_y, $dst_width, $dst_height, $src_width, $src_height);
	}

	imagedestroy($im);

	return $im2;
}








/**
 * mixed image_info( file $file )
 *
 * Returns information about $file.
 *
 * Returns false if $file is not a file, no arguments are supplied, $file is not an image, or otherwise fails.
 *
 **/
function image_info($file = null) {

   // If $file is not supplied or is not a file, warn the user and return false.
   if (is_null($file) || !is_file($file)) {
       echo '<p><b>Warning:</b> image_info() => first argument must be a file.</p>';
       return false;
   }

   // Defines the keys we want instead of 0, 1, 2, 3, 'bits', 'channels', and 'mime'.
   $redefine_keys = array(
       'width',
       'height',
       'type',
       'attr',
       'bits',
       'channels',
       'mime',
   );

   // Assign usefull values for the third index.
   $types = array(
       1 => 'GIF',
       2 => 'JPG',
       3 => 'PNG',
       4 => 'SWF',
       5 => 'PSD',
       6 => 'BMP',
       7 => 'TIFF(intel byte order)',
       8 => 'TIFF(motorola byte order)',
       9 => 'JPC',
       10 => 'JP2',
       11 => 'JPX',
       12 => 'JB2',
       13 => 'SWC',
       14 => 'IFF',
       15 => 'WBMP',
       16 => 'XBM'
   );
   $temp = array();
   $data = array();

   // Get the image info using getimagesize().
   // If $temp fails to populate, warn the user and return false.
   if (!$temp = getimagesize($file)) {
       echo '<p><b>Warning:</b> image_info() => first argument must be an image.</p>';
       return false;
   }

		foreach (array(3 => 'attr', 2 => 'type', 1 => 'height', 0 => 'width') as $i => $attr) {
			$temp[$attr] = $temp[$i];
			unset($temp[$i]);
		}

		$data = $temp;

   // Make 'type' usefull.
   $data['type'] = $types[$data['type']];

   // Return the desired information.
   return $data;
}

function colour_from_hex($string) {
	preg_match('/(#|)([0-9A-F]{2})([0-9A-F]{2})([0-9A-F]{2})/', strtoupper($string), $matches);

	return array(
		'red' => hexdec($matches[2]),
		'green' => hexdec($matches[3]),
		'blue' => hexdec($matches[4])
		);
}

?>