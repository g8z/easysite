<?php
//
// JSHttpRequest v2.04. (C) Dmitry Koterov, 2005-02-08.
// http://forum.dklab.ru/users/DmitryKoterov/
//
// Do not remove this comment if you want to use the script!
// Не удаляйте данный комментарий, если вы хотите использовать скрипт!
//

class JSHttpRequest
{
  var $SCRIPT_ENCODING = "windows-1251";
  var $SCRIPT_DECODE_MODE = '';
  var $UNIQ_HASH;
  var $SCRIPT_ID;
  var $SCRIPT_SID;
  var $QUERY_STRING;
  var $QUOTING = null;

  
  // Constructor.
  function JSHttpRequest($enc, $query_string=null)
  {
    if ($query_string === null) $query_string = $_SERVER['QUERY_STRING'];
    $this->QUERY_STRING = $query_string;

    // Parse QUERY_STRING wrapper format.
    $parts = explode(':', $this->QUERY_STRING, 3);
    $this->SCRIPT_ID  = @$parts[0];
    $this->SCRIPT_SID = @$parts[1];
    $this->QUERY_STRING = @$parts[2];

    // Start OB handling early.
    $this->UNIQ_HASH = md5(microtime().getmypid());
    ini_set('error_prepend_string', ini_get('error_prepend_string').$this->UNIQ_HASH);
    ini_set('error_append_string',  ini_get('error_append_string') .$this->UNIQ_HASH);
    ob_start(array(&$this, "_obHandler"));

    // Start session.
    if ($this->SCRIPT_SID) {
      @session_id($this->SCRIPT_SID);
      @session_start();
    }

    // Set up encoding.
    $this->setEncoding($enc);
  }


  // Set active script encoding & parse QUERY_STRING.
  // Examples:
  //   "windows-1251"          - set plain encoding (non-windows characters,
  //                             e.g. hieroglyphs, are totally ignored)
  //   "windows-1251 entities" - set windows encoding, BUT additionally replace:
  //                             "&"         ->  "&amp;"
  //                             hieroglyph  ->  &%DDDD; entity
  function setEncoding($enc)
  {
    // Parse encoding.
    preg_match('/^(\S*)(?:\s+(\S*))$/', $enc, $p);
    $this->SCRIPT_ENCODING    = @$p[1]? $p[1] : $enc;
    $this->SCRIPT_DECODE_MODE = @$p[2]? $p[2] : '';
    // Manually parse QUERY_STRING because of damned Unicode's %uXXXX.
    $this->_parseQueryString();
  }

  
  // Quote string according to input decoding mode.
  // If entities is used (see setEncoding()), no '&' character is quoted,
  // only '"', '>' and '<' (we presume than '&' is already quoted by
  // input reader function).
  //
  // Use this function INSTEAD of htmlspecialchars() for $_GET data
  // in your scripts.
  function quoteInput($s)
  {
    if ($this->SCRIPT_DECODE_MODE == 'entities')
      return str_replace(array('"', '<', '>'), array('&quot;', '&lt;', '&gt;'), $s);
    else
      return htmlspecialchars($s);
  }

  
  // Convert PHP scalar, array or hash to JS scalar/array/hash.
  function _php2js($a)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_int($a) || is_float($a)) return strval($a);
    if (is_scalar($a)) {
      $a = addslashes($a);
      $a = str_replace("\n", '\n', $a);
      $a = str_replace("\r", '\r', $a);
      return "'$a'";
    }
    $isList = true;
    for ($i=0, reset($a); $i<count($a); $i++, next($a))
      if (key($a) !== $i) { $isList = false; break; }
    $result = array();
    if ($isList) {
      foreach ($a as $v) $result[] = JSHttpRequest::_php2js($v);
      return '[ ' . join(',', $result) . ' ]';
    } else {
      foreach ($a as $k=>$v) $result[] = JSHttpRequest::_php2js($k) . ': ' . JSHttpRequest::_php2js($v);
      return '{ ' . join(',', $result) . ' }';
    }
  }


  // Parse & decode QUERY_STRING.
  function _parseQueryString()
  {
    $_GET = array();
    foreach (explode('&', $this->QUERY_STRING) as $pair) {
      @list ($k, $v) = explode('=', $pair, 2);
      $k = $this->_unescape($k);
      $v = $this->_unescape($v);
      if (ini_get('magic_quotes_gpc')) {
        $k = addslashes($k);
        $v = addslashes($v);
      }
      $_GET[$k] = $v;
    }
    $_REQUEST =
      (isset($_COOKIES)? $_COOKIES : array()) +
      (isset($_POST)? $_POST : array()) +
      (isset($_GET)? $_GET : array());
  }


  // Called in case of error too!
  function _obHandler($text)
  {
    // Check for error.
    if (preg_match('{'.$this->UNIQ_HASH.'(.*?)'.$this->UNIQ_HASH.'}sx', $text)) {
      $text = str_replace($this->UNIQ_HASH, '', $text);
      $this->WAS_ERROR = 1;
    }
    // Content-type header.
    Header("Content-type: text/javascript; charset=".$this->SCRIPT_ENCODING);
    // Make resulting hash.
    if (!isset($this->RESULT)) $this->RESULT = @$GLOBALS['_RESULT'];
    $result = $this->_php2js($this->RESULT);
    $text = "JSHttpRequest.dataReady(\n" .
      "  " . $this->_php2js($this->SCRIPT_ID) . ",\n" .
      "  " . $this->_php2js(trim($text)) . ",\n" .
      "  " . $result . "\n" .
    ")\n";
//    $f = fopen("debug", "w"); fwrite($f, $text); fclose($f);
    return $text;
  }


  // Undo JS's escape() function.
  function _unescape($s)
  {
    $s = preg_replace_callback(
      '/% (?: u([A-F0-9]{1,4}) | ([A-F0-9]{1,2})) /sxi',
      array(&$this, '_unescapeCallback'),
      $s
    );
    return $s;
  }


  // Inplace entity replacement.
  function _unescapeCallback($p)
  {
    if ($p[1]) {
      $u = pack('n', $dec=hexdec($p[1]));
      $c = @iconv('UCS-2BE', $this->SCRIPT_ENCODING, $u);
      if (!strlen($c) && $this->SCRIPT_DECODE_MODE == 'entities') {
        $c = '&#'.$dec.';';
      }
//      echo "{$p[1]} - $c<br>";
    } else {
      if ($p[2] === "26" && $this->SCRIPT_DECODE_MODE == 'entities') {
        $c = "&amp;";
      } else {
        $c = chr(hexdec($p[2]));
      }
    }
    return $c;
  }
}
?> 