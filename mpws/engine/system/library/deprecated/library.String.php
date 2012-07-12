<?php

// PHP ��������.
// ��������� String.
// -----------------------
//
// ����������� ��� ������� �� ��������� �����.
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

class String
{
    // _�����������
    function __construct() { }

    // _����������
    function __destruct() { }

    public function ClearText($text, $unicode = false) {
        if ($unicode)
            return str_replace(' ', '_', preg_replace('/[^(\x20-\x7F)]*/','', $text));
        return str_replace(' ', '_', $text);
    }

    public function TextToTagID($text){
        $_objI = $text;
        $_objI = str_replace(' ', '-', $_objI);
        return $_objI;
    }

    // Գ����� ��������� �����.
    // $txt - ��������� ����� ��� ����������.
    // $max_len - ����������� ������ �������.
    // $chars - �������� �������.
    // ������� �������������� �����.
    public function TrimText($txt, $max_len, $chars='0123456789abcdefghijklmnopqrstuvwxyz')
    {
        $t = strlen($txt) > $max_len ? substr($txt, 0, $max_len) : $txt;
        $t_len = strlen($t);

        $res = '';
        for ($i = 0; $i < $t_len; $i++)
        {
            if (strrpos($chars, $t[$i]) >- 1 )
                $res .= $t[$i];
        }

        return $res;
    }

    // ���� ������ �� ������ ������ �����: '1234567890' -> '1 234 567 890'.
    // $str - ����� ���������.
    // ������� ��������� ����� �� �����.
    public function SplitNumberBySpaces($str)
    {
        $txt = (string)$str;
        $count = strlen($txt);

        $res = '';
        for ($i = $count ; $i >= 1 ; $i--)
        {
            if ($i % 3 === 0)
                $res .= ' ';
            $res .= $txt[$count-$i];
        }

        return $res;
    }

    public function strip_html_tags( $text )
    {/*
        $text = preg_replace(
            array(
              // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',
              // Add line breaks before and after blocks
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
            ),
            array(
                ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                "\n\$0", "\n\$0",
            ),
            $text );*/
        return strip_tags( $text );
    }

    // ���� �� �������� ���������� �� ����������� �������.
    // $data - ��������� �����.
    // ������� ���������� ��������� �����.
    public function AddSlash($data)
    {
        if (!get_magic_quotes_gpc())
            return addslashes($data);

        return $data;
    }

    // ������ ���������� ��������� ��������� �������.
    // $data - ��������� �����.
    // ������� ����� ��������� �����.
    public function RemSlash($data)
    {
        return stripslashes($data);
    }

    // ������� ������ ������� � ����� ��������� � ����.
    // $haystack - ���������� �����.
    // $needle - ������� ������.
    // ������� ����� ������� ������� � ����� ��� -1.
    public function LastIndexOf($haystack, $needle)
    {
        $index = strpos(strrev($haystack), strrev($needle));
        $index = strlen($haystack) - strlen($index) - $index;

        return $index;
    }

    public function GetTextByEncoding($text, $to = 'CP1251', $from = 'auto')
    {        if ($from == 'auto')
            $from = mb_detect_encoding($text, "auto");

        echo $from;

        if (empty($from))
            return iconv('UTF-8', 'CP1251', $text);

        return mb_convert_encoding($text, $to, $from);
    }

    public function is_utf8($str) {
        $c = 0;
        $b = 0;
        $bits = 0;
        $len = strlen($str);

        for ($i = 0; $i < $len; $i++)
        {
            $c = ord($str[$i]);
            if ($c > 128)
            {
                if (($c >= 254)) return false;
                elseif ($c >= 252) $bits=6;
                elseif ($c >= 248) $bits=5;
                elseif ($c >= 240) $bits=4;
                elseif ($c >= 224) $bits=3;
                elseif ($c >= 192) $bits=2;
                else
                    return false;

                if (($i+$bits) > $len)
                    return false;

                while ($bits > 1)
                {
                    $i++;
                    $b = ord($str[$i]);
                    if ($b < 128 || $b > 191)
                        return false;
                    $bits--;
                }
            }
        }

        return true;
    }
}
?>
