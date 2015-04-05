<?php
/**
 * 한글 처리
 *
 * @author	peris <cshlacid@gmail.com>
 */
class Korean {
	/**
	 * 한글 자음/모음 분리
	 *
	 * @param	String $string
	 * @return	String
	 */
	public function breakKorean($string) {
		$jamo = array(
			'ㄱ', 'ㄱㄱ', 'ㄱㅅ', 'ㄴ', 'ㄴㅈ', 'ㄴㅎ', 'ㄷ', 'ㄷㄷ',
			'ㄹ', 'ㄹㄱ', 'ㄹㅁ', 'ㄹㅂ', 'ㄹㅅ', 'ㄹㅌ', 'ㄹㅍ', 'ㄹㅎ',
			'ㅁ', 'ㅂ', 'ㅂㅂ', 'ㅂㅅ', 'ㅅ', 'ㅅㅅ', 'ㅇ',
			'ㅈ', 'ㅈㅈ', 'ㅊ', 'ㅋ', 'ㅌ', 'ㅍ', 'ㅎ',
		);
		$k1 = array('ㄱ', 'ㄱㄱ', 'ㄴ', 'ㄷ', 'ㄷㄷ', 'ㄹ', 'ㅁ', 'ㅂ', 'ㅂㅂ', 'ㅅ', 'ㅅㅅ', 'ㅇ', 'ㅈ', 'ㅈㅈ', 'ㅊ', 'ㅋ', 'ㅌ', 'ㅍ', 'ㅎ');
		$k2 = array('ㅏ', 'ㅐ', 'ㅑ', 'ㅒ', 'ㅓ', 'ㅔ', 'ㅕ', 'ㅖ', 'ㅗ', 'ㅗㅏ', 'ㅗㅐ', 'ㅗㅣ', 'ㅛ', 'ㅜ', 'ㅜㅓ', 'ㅜㅔ', 'ㅜㅣ', 'ㅠ', 'ㅡ', 'ㅡㅣ', 'ㅣ');
		$k3 = array('', 'ㄱ', 'ㄱㄱ', 'ㄱㅅ', 'ㄴ', 'ㄴㅈ', 'ㄴㅎ', 'ㄷ', 'ㄹ', 'ㄹㄱ', 'ㄹㅁ', 'ㄹㅂ', 'ㄹㅅ', 'ㄹㅌ', 'ㄹㅍ', 'ㄹㅎ', 'ㅁ', 'ㅂ', 'ㅂㅅ', 'ㅅ', 'ㅅㅅ', 'ㅇ', 'ㅈ', 'ㅊ', 'ㅋ', 'ㅌ', 'ㅍ', 'ㅎ');

		$str = iconv('UTF-8', 'UTF-16LE', str_replace(' ', '', $string));

		$ret = '';
		for($i=0, $len=strlen($str); $i<$len; $i+=2) {
			$byte1 = '0'.dechex(ord($str[$i + 1]));
			$byte2 = '0'.dechex(ord($str[$i]));
			$code = hexdec(substr($byte1, strlen($byte1) - 2, 2).substr($byte2, strlen($byte2) - 2, 2));

			if($code >= 0x3131 && $code <= 0x318E) {	// Hangul Compatibility Jamo
				$temp = $code - 0x3131;
				if(isset($jamo[$temp])) {
					$ret .= $jamo[$temp];
				} else {
					$ret .= iconv('UTF-16LE', 'UTF-8', $str[$i].$str[$i + 1]);
				}
			} else if($code >= 0xAC00 && $code <= 0xD7A3) {	// Hangul Syllables
				$temp = $code - 0xAC00;
				$jong = $temp % 28;
				$jung = (($temp - $jong) / 28) % 21;
				$cho = floor((($temp - $jong) / 28) / 21);
				$ret .= $k1[$cho].$k2[$jung].$k3[$jong];
			} else {
				$ret .= iconv('UTF-16LE', 'UTF-8', $str[$i].$str[$i + 1]);
			}
		}

		return $ret;
	}
}
