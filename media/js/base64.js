<script>
$(document).ready(function() {
	
	function decode_base64(s) {
		var b=l=0, r='', s=s.split(''), i,
		m='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'.split('');
		for (i in s) {
			b=(b<<6)+m.indexOf(s[i]); l+=6;
			while (l>=8) r+=String.fromCharCode((b>>>(l-=8))&0xff);
		}
		return r.trim();
	}
	
	function ord(string) { var str = string + '', code = str.charCodeAt(0); if (0xD800 <= code && code <= 0xDBFF) { var hi = code; if (str.length === 1) { return code; } var low = str.charCodeAt(1); return ((hi - 0xD800) * 0x400) + (low - 0xDC00) + 0x10000; } if (0xDC00 <= code && code <= 0xDFFF) { return code; } return code; }
	function substr(str, start, len) {
		var i = 0,
			allBMP = true,
			es = 0,
			el = 0,
			se = 0,
			ret = '';
		str += '';
		var end = str.length;
		this.php_js = this.php_js || {};
		this.php_js.ini = this.php_js.ini || {};
		
		switch ((this.php_js.ini['unicode.semantics'] && this.php_js.ini['unicode.semantics'].local_value.toLowerCase())) {
			case 'on':
			for (i = 0; i < str.length; i++) {
				if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {
				allBMP = false;
				break;
				}
			}
		
			if (!allBMP) {
				if (start < 0) {
				for (i = end - 1, es = (start += end); i >= es; i--) {
					if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {
					start--;
					es--;
					}
				}
				} else {
				var surrogatePairs = /[\uD800-\uDBFF][\uDC00-\uDFFF]/g;
				while ((surrogatePairs.exec(str)) != null) {
					var li = surrogatePairs.lastIndex;
					if (li - 2 < start) {
					start++;
					} else {
					break;
					}
				}
				}
		
				if (start >= end || start < 0) {
				return false;
				}
				if (len < 0) {
				for (i = end - 1, el = (end += len); i >= el; i--) {
					if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {
					end--;
					el--;
					}
				}
				if (start > end) {
					return false;
				}
				return str.slice(start, end);
				} else {
				se = start + len;
				for (i = start; i < se; i++) {
					ret += str.charAt(i);
					if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {
					se++; 
					}
				}
				return ret;
				}
				break;
			}
			case 'off':
			default:
			if (start < 0) {
				start += end;
			}
			end = typeof len === 'undefined' ? end : (len < 0 ? len + end : len + start);
			return start >= str.length || start < 0 || start > end ? !1 : str.slice(start, end);
		}
		return undefined; 
	}
	function chr(codePt) {
		if (codePt > 0xFFFF) { 
		codePt -= 0x10000;
		return String.fromCharCode(0xD800 + (codePt >> 10), 0xDC00 + (codePt & 0x3FF));
		}
		return String.fromCharCode(codePt);
	}
	function decrypt($encrypted,$key) {
		$decrypted = '';
		$string = decode_base64($encrypted);
		for(var $i=0; $i<$string.length; $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % ($key.length))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$decrypted+=$char;
		}
		return $decrypted.replace(/[^ -~]+/g, '');
	}
});
</script>