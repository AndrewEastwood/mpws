<?php

// PHP ��������.
// ��������� Images.
// -----------------------
//
// ����������� ��� ������� ���������.
//
// ��� ������������ ����� ��������� ��� ����
// ������ ���� ������� ������� ���������
// ����� � ������ ��������� ����� ��������
// ����, ��� �������������� � ����� ���������.
//
// �����: � 2009, ����� ����������.
// Author: � 2009, Andriy Ivaskevych.
//
// E-mail: soulcor@narod.ru

class Images
{	// _�����������
	function __construct() { }

	// _����������
	function __destruct() { }

	// �������� ��������� ��� ����������.
	// $imagePath - ���� �� ����� ����������.
	// ������� ��� ��� ���������� ��� false.
	public function GetImageInfo($imagePath)
	{		if (!$imagePath && !file_exists($imagePath))
			return false;

		return getimagesize($imagePath);
	}

	// ������ ������� ����������.
	// $image - ����������.
	// $angle - ��� ��������.
	// ������� ��� ����������.
	public function RotateImage($image, $angle)
	{		return imagerotate($image, $angle, 0);	}

	// ����� ������ ����������.
	// $src - ���� �� ����� ����������.
	// $dest - ���� �� ������ ����� ����������
	// $width - ������ ������ ����������.
	// $height - ������ ������ ����������.
	// $rgb - ��� ������ ����������.
	// $quality - ����� ������ ����������.
	// ������� ,���� ���������� ���� ������ ������ .
	public function ResizeImage($src, $dest, $width, $height, $rgb = 0xFFFFFF, $quality = 100)
	{
		if (!file_exists($src))
			return false;

		$ext = '';
		$size = @getimagesize($src);
		switch ($size[2])
		{
			case 1:
				$ext = 'gif';
				break;
			case 2:
				$ext = 'jpg';
				break;
			case 3:
				$ext = 'png';
				break;
			default:
				return false;
		}


		if ($size === false)
			return false;

		// ���������� �������� ������ �� MIME-����������, ���������������
		// �������� getimagesize, � �������� ��������������� �������
		// imagecreatefrom-�������.
		$format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
		$icfunc = "imagecreatefrom" . $format;

		if (!function_exists($icfunc))
			return false;

		$x_ratio = $width / $size[0];
		$y_ratio = $height / $size[1];

		$ratio = min($x_ratio, $y_ratio);
		$use_x_ratio = ($x_ratio == $ratio);

		$new_width = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
		$new_height = !$use_x_ratio ? $height : floor($size[1] * $ratio);

		$new_left = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
		$new_top = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

		$isrc = $icfunc($src);
		$idest = imagecreatetruecolor($width, $height);

		imagefill($idest, 0, 0, $rgb);
		imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0,
			$new_width, $new_height, $size[0], $size[1]);

   		switch ($ext)
   		{   			case 'jpg':
   				imagejpeg($idest, $dest, $quality);
   				break;
   			case 'png':
   				imagepng($idest, $dest, $quality);
   				break;
   			case 'gif':
   				imagegif($idest, $dest);
   				break;
   			default:
   				return false;
   		}

		imagedestroy($isrc);
		imagedestroy($idest);

		return true;
	}

}

?>
