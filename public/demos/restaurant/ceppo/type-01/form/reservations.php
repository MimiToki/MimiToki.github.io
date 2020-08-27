<?php header("Content-Type:text/html;charset=utf-8"); ?>
<?php 
if (version_compare(PHP_VERSION, '5.1.0', '>=')) {
	date_default_timezone_set('Etc/GMT+12');
}

//---------------------------　Required setting (Please be sure to set)　-----------------------

$site_top = "http://okirakun.com/demos/ceppo/";

$to = "example@example.com";

$Email = "Email";

$Referer_check = 1;

$Referer_check_domain = "okirakun.com";

//---------------------------　Required setting End　------------------------------------

//---------------------- Option setting　(Please set as necessary) ------------------------

$userMail = 1;

$BccMail = "";

$subject = "Reservation Mail";

$confirmDsp = 1;

$jumpPage = 0;

$thanksPage = "http://thanks.com/index.html";

$requireCheck = 1;

$require = array('Date','Time','People','Name','Email');


//----------------------------------------------------------------------
// Automatic reply mail setting 
//----------------------------------------------------------------------

$remail = 0;

$refrom_name = "";

$re_subject = "Thank you for your Reservation";

$dsp_name = '';

$remail_text = <<< TEXT

Thank you for your inquiry.
It takes time until confirmation. Please wait until then.

The transmission contents are as follows.

TEXT;

$mailFooterDsp = 1;

$mailSignature = <<< FOOTER

──────────────────────
OKIRAKUYA　TARO OGAWA
123-4567 Okiraku-ken, kiraku-shi, raku-ku, okirakun, 8 Chome−1,
TEL：0120-3456-7890
E-mail: example.com
URL: http://example.com/
──────────────────────

FOOTER;

//----------------------------------------------------------------------
// Automatic reply mail setting End
//----------------------------------------------------------------------

$mail_check = 1;

$hankaku = 0;

$hankaku_array = array('Email');


//----------------------------------------------------------------------
//  Function execution,Variable initialization
//----------------------------------------------------------------------

$encode = "UTF-8";

if(isset($_GET)) $_GET = sanitize($_GET);
if(isset($_POST)) $_POST = sanitize($_POST);
if(isset($_COOKIE)) $_COOKIE = sanitize($_COOKIE);
if($encode == 'SJIS') $_POST = sjisReplace($_POST,$encode);
$funcRefererCheck = refererCheck($Referer_check,$Referer_check_domain);

$sendmail = 0;
$empty_flag = 0;
$post_mail = '';
$errm ='';
$header ='';

if($requireCheck == 1) {
	$requireResArray = requireCheck($require);
	$errm = $requireResArray['errm'];
	$empty_flag = $requireResArray['empty_flag'];
}
if(empty($errm)){
	foreach($_POST as $key=>$val) {
		if($val == "confirm_submit") $sendmail = 1;
		if($key == $Email) $post_mail = h($val);
		if($key == $Email && $mail_check == 1 && !empty($val)){
			if(!checkMail($val)){
				$errm .= "<p class=\"error_messe\">【".$key."】the format of the mail address is incorrect.</p>\n";
				$empty_flag = 1;
			}
		}
	}
}
  
if(($confirmDsp == 0 || $sendmail == 1) && $empty_flag != 1){
	
	if($remail == 1) {
		$userBody = mailToUser($_POST,$dsp_name,$remail_text,$mailFooterDsp,$mailSignature,$encode);
		$reheader = userHeader($refrom_name,$to,$encode);
		$re_subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($re_subject,"JIS",$encode))."?=";
	}
	$adminBody = mailToAdmin($_POST,$subject,$mailFooterDsp,$mailSignature,$encode,$confirmDsp);
	$header = adminHeader($userMail,$post_mail,$BccMail,$to);
	$subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($subject,"JIS",$encode))."?=";
	
	mail($to,$subject,$adminBody,$header);
	if($remail == 1 && !empty($post_mail)) mail($post_mail,$re_subject,$userBody,$reheader);
}
else if($confirmDsp == 1){ 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="" hreflang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reservation Ceppo</title>
<meta name="description" content="description Here" />
<meta name="keywords" content="RESTAURANT,Restaurant,Food" />
<link rel="shortcut icon" href="../../image/LogoIcon.jpg">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<!-- STYLESHEETS -->
<link rel="stylesheet" href="../page/reservation/style.css" />
<link rel="stylesheet" href="../stylesheet/hamburgers.min.css">
<link rel="stylesheet" href="../stylesheet/component.css" />
<link rel="stylesheet" href="../stylesheet/bootstrap.min.css">
<link rel="stylesheet" href="../stylesheet/font-awesome.min.css">
<!-- GOOGLE FONTS -->
<link href="https://fonts.googleapis.com/css?family=Cormorant+Unicase" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">

</head>

<body>
<!-- Nav -->
<div class="NavLogo">
	<a href="../index.html"><img src="../image/head/LogoBlack.png"></a>
</div>
	
<div class="hamburger hamburger--collapse"> <span class="hamburger-box"> <span class="hamburger-inner"></span> </span> </div>
<nav class="navimavi">
  <ul class="navi-nav">
    <li><a href="../index.html">HOME</a></li>
    <li><a href="../page/about/index.html">ABOUT</a></li>
    <li><a href="../page/menu/index.html">MENU</a></li>
    <li><a href="../page/team/index.html">TEAM</a></li>
    <li><a href="../page/blog/index.html">BLOG</a></li>
    <li><a href="../page/contact/index.html">CONTACT</a></li>
  </ul>
</nav>
<!-- Nav End --> 
	
<section class="section">
<!-- Top Title -->
<div class="wBg">
	<div class="TitleSec">
              <div class="TitleText">
                <div class="TitleItem">
					
					<p><span class="MainTitle">In this way</span></p>
					<p><span class="MainTitle"> you can save time.</span></p>
					
                </div><!-- item end --> 
              </div><!-- AboutDescription end --> 
        </div><!-- grid end --> 
</div>
<!-- Top Title End --> 
	
  <div class="booking">
    <div class="booking-contents">
      <div class="bk-a">
		  <div class="stg-til-a"><span>Booking System:</span></div>
		  <p>CEPPO offer reservation services.Book online or give us a call on (123) 456-7890 between 9.30am-10pm, or send us an email on <a href="mailto:info&#64;example.com" class="link">example.com</a></p>
		  <p>Reservation to enable a customer to comfortably meal. Can be utilizedby the previous day (reservation by phone is accepted).</p>
		  <p>Cancellation of the reservation on the day will incur a cancellation charge.</p>
		  
      </div>
      <div class="bk-b">
		  
		  <div class="stg-til-b"><span>Hours:</span></div>
		  <p>MON-THU: 8:30 - 10PM</p>
		  <p>FRI-SAT: 8:00 - 11:30PM</p>
		  
      </div>
    </div>
    <div class="booking-date">
      <div class="form-group">
			<div class="stg-til-c"><span>Book a Table</span></div>
	  </div>
<div id="formWrap">
<?php if($empty_flag == 1){ ?>
<div align="center">
	<p>>There is an error in the input.</p>
	<p>Please check the following and correct by the "Back" button.</p>
<?php echo $errm; ?><br /><br />
<div class="btn-sub">
<div class="reservation-widget">
<input type="button" value=" Back " onClick="history.back()">
</div>
</div>
</div>
<?php }else{ ?>
<h3>Confirmation</h3>
<p align="center">If there is no mistake with the contents below, please push "send" button.</p>
<form action="<?php echo h($_SERVER['SCRIPT_NAME']); ?>" method="POST">
<table class="formTable">
<?php echo confirmOutput($_POST);?>
</table>
	<p align="center">IP address is recorded.</p>
	<p align="center">Please refrain from mischief and harassment etc.</p>
<p align="center">
<div class="btn-sub">
<div class="reservation-widget">
<input type="hidden" name="mail_set" value="confirm_submit">
<input type="hidden" name="httpReferer" value="<?php echo h($_SERVER['HTTP_REFERER']);?>">
<input type="submit" value="  Send  ">
<input type="button" value="  Back  " onClick="history.back()">
</div>
</div>
</p>
</form>
</div>
<?php } ?>
</div>
</div>
</body>
</html>
<?php
}

if(($jumpPage == 0 && $sendmail == 1) || ($jumpPage == 0 && ($confirmDsp == 0 && $sendmail == 0))) { 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="" hreflang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reservation Ceppo</title>
<meta name="description" content="description Here" />
<meta name="keywords" content="RESTAURANT,Restaurant,Food" />
<link rel="shortcut icon" href="../../image/LogoIcon.jpg">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<!-- STYLESHEETS -->
<link rel="stylesheet" href="../page/reservation/style.css" />
<link rel="stylesheet" href="../stylesheet/hamburgers.min.css">
<link rel="stylesheet" href="../stylesheet/component.css" />
<link rel="stylesheet" href="../stylesheet/bootstrap.min.css">
<link rel="stylesheet" href="../stylesheet/font-awesome.min.css">
<!-- GOOGLE FONTS -->
<link href="https://fonts.googleapis.com/css?family=Cormorant+Unicase" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">

</head>

<body>
<!-- Nav -->
<div class="NavLogo">
	<a href="../index.html"><img src="../image/head/LogoBlack.png"></a>
</div>
	
<div class="hamburger hamburger--collapse"> <span class="hamburger-box"> <span class="hamburger-inner"></span> </span> </div>
<nav class="navimavi">
  <ul class="navi-nav">
    <li><a href="../index.html">HOME</a></li>
    <li><a href="../page/about/index.html">ABOUT</a></li>
    <li><a href="../page/menu/index.html">MENU</a></li>
    <li><a href="../page/team/index.html">TEAM</a></li>
    <li><a href="../page/blog/index.html">BLOG</a></li>
    <li><a href="../page/contact/index.html">CONTACT</a></li>
  </ul>
</nav>
<!-- Nav End --> 
	
<section class="section">
<!-- Top Title -->
<div class="wBg">
	<div class="TitleSec">
              <div class="TitleText">
                <div class="TitleItem">
					
					<p><span class="MainTitle">In this way</span></p>
					<p><span class="MainTitle"> you can save time.</span></p>
					
                </div><!-- item end --> 
              </div><!-- AboutDescription end --> 
        </div><!-- grid end --> 
</div>
<!-- Top Title End --> 
	
  <div class="booking">
    <div class="booking-contents">
      <div class="bk-a">
		  <div class="stg-til-a"><span>Booking System:</span></div>
		  <p>CEPPO offer reservation services.Book online or give us a call on (123) 456-7890 between 9.30am-10pm, or send us an email on <a href="mailto:info&#64;example.com" class="link">example.com</a></p>
		  <p>Reservation to enable a customer to comfortably meal. Can be utilizedby the previous day (reservation by phone is accepted).</p>
		  <p>Cancellation of the reservation on the day will incur a cancellation charge.</p>
		  
      </div>
      <div class="bk-b">
		  
		  <div class="stg-til-b"><span>Hours:</span></div>
		  <p>MON-THU: 8:30 - 10PM</p>
		  <p>FRI-SAT: 8:00 - 11:30PM</p>
		  
      </div>
    </div>
    <div class="booking-date">
      <div class="form-group">
			<div class="stg-til-c"><span>Book a Table</span></div>
	  </div>
<div align="center">
<?php if($empty_flag == 1){ ?>
	<p>There is an error in the input.</p>
	<p> Please check the following and correct by the "Back" button.</p>
<div style="color:red"><?php echo $errm; ?></div>
<br /><br />
<div class="btn-sub">
<div class="reservation-widget">
<input type="button" value=" Back " onClick="history.back()">
</div>
</div>
</div>
</body>
</html>
<?php }else{ ?>
<div id="formWrap">
	<p>Thank you for sending.</p>
<div class="btn-sub">
<div class="reservation-widget">
<a href="<?php echo $site_top ;?>"><input type="button" value=" HOME "></a>
</div>
</div>
</div>
</section>
</body>
</html>
<?php 
  }
}
else if(($jumpPage == 1 && $sendmail == 1) || $confirmDsp == 0) { 
	if($empty_flag == 1){ ?>
<div align="center">
	<p>There is an error in the input.</p>
	<p>Please check the following and correct by the "Back" button.</p>
<div style="color:red">
<?php echo $errm; ?>
</div>
<br />
<br />
<div class="btn-sub">
<div class="reservation-widget">
<input type="button" value="  Back  " onClick="history.back()">
</div>
</div>
</div>
<?php 
	}else{ header("Location: ".$thanksPage); }
}

//----------------------------------------------------------------------
//  Function definition
//----------------------------------------------------------------------

function checkMail($str){
	$mailaddress_array = explode('@',$str);
	if(preg_match("/^[\.!#%&\-_0-9a-zA-Z\?\/\+]+\@[!#%&\-_0-9a-z]+(\.[!#%&\-_0-9a-z]+)+$/", "$str") && count($mailaddress_array) ==2){
		return true;
	}else{
		return false;
	}
}
function h($string) {
	global $encode;
	return htmlspecialchars($string, ENT_QUOTES,$encode);
}
function sanitize($arr){
	if(is_array($arr)){
		return array_map('sanitize',$arr);
	}
	return str_replace("\0","",$arr);
}
function sjisReplace($arr,$encode){
	foreach($arr as $key => $val){
		$key = str_replace('＼','ー',$key);
		$resArray[$key] = $val;
	}
	return $resArray;
}
function postToMail($arr){
	global $hankaku,$hankaku_array;
	$resArray = '';
	foreach($arr as $key => $val) {
		$out = '';
		if(is_array($val)){
			foreach($val as $key02 => $item){ 
				if(is_array($item)){
					$out .= connect2val($item);
				}else{
					$out .= $item . ', ';
				}
			}
			$out = rtrim($out,', ');
			
		}else{ $out = $val; }
		if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
		
		if($hankaku == 1){
			$out = zenkaku2hankaku($key,$out,$hankaku_array);
		}
		if($out != "confirm_submit" && $key != "httpReferer") {
			$resArray .= "【 ".h($key)." 】 ".h($out)."\n";
		}
	}
	return $resArray;
}
function confirmOutput($arr){
	global $hankaku,$hankaku_array;
	$html = '';
	foreach($arr as $key => $val) {
		$out = '';
		if(is_array($val)){
			foreach($val as $key02 => $item){ 
				if(is_array($item)){
					$out .= connect2val($item);
				}else{
					$out .= $item . ', ';
				}
			}
			$out = rtrim($out,', ');
			
		}else{ $out = $val; }
		if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
		$out = nl2br(h($out));
		$key = h($key);
		
		if($hankaku == 1){
			$out = zenkaku2hankaku($key,$out,$hankaku_array);
		}
		
		$html .= "<tr><th>".$key."</th><td>".$out;
		$html .= '<input type="hidden" name="'.$key.'" value="'.str_replace(array("<br />","<br>"),"",$out).'" />';
		$html .= "</td></tr>\n";
	}
	return $html;
}

function zenkaku2hankaku($key,$out,$hankaku_array){
	global $encode;
	if(is_array($hankaku_array) && function_exists('mb_convert_kana')){
		foreach($hankaku_array as $hankaku_array_val){
			if($key == $hankaku_array_val){
				$out = mb_convert_kana($out,'a',$encode);
			}
		}
	}
	return $out;
}
function connect2val($arr){
	$out = '';
	foreach($arr as $key => $val){
		if($key === 0 || $val == ''){
			$key = '';
		}elseif(strpos($key,"$") !== false && $val != '' && preg_match("/^[0-9]+$/",$val)){
			$val = number_format($val);
		}
		$out .= $val . $key;
	}
	return $out;
}

function adminHeader($userMail,$post_mail,$BccMail,$to){
	$header = '';
	if($userMail == 1 && !empty($post_mail)) {
		$header="From: $post_mail\n";
		if($BccMail != '') {
		  $header.="Bcc: $BccMail\n";
		}
		$header.="Reply-To: ".$post_mail."\n";
	}else {
		if($BccMail != '') {
		  $header="Bcc: $BccMail\n";
		}
		$header.="Reply-To: ".$to."\n";
	}
		$header.="Content-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
		return $header;
}
function mailToAdmin($arr,$subject,$mailFooterDsp,$mailSignature,$encode,$confirmDsp){
	$adminBody="I received an email from「".$subject."」\n\n";
	$adminBody .="＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
	$adminBody.= postToMail($arr);
	$adminBody.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n";
	$adminBody.="Date and time sent：".date( "Y/m/d (D) H:i:s", time() )."\n";
	$adminBody.="Sender's IP address：".@$_SERVER["REMOTE_ADDR"]."\n";
	$adminBody.="Host name of sender：".getHostByAddr(getenv('REMOTE_ADDR'))."\n";
	if($confirmDsp != 1){
		$adminBody.="Inquiry page URL：".@$_SERVER['HTTP_REFERER']."\n";
	}else{
		$adminBody.="Inquiry page URL：".@$arr['httpReferer']."\n";
	}
	if($mailFooterDsp == 1) $adminBody.= $mailSignature;
	return mb_convert_encoding($adminBody,"JIS",$encode);
}

function userHeader($refrom_name,$to,$encode){
	$reheader = "From: ";
	if(!empty($refrom_name)){
		$default_internal_encode = mb_internal_encoding();
		if($default_internal_encode != $encode){
			mb_internal_encoding($encode);
		}
		$reheader .= mb_encode_mimeheader($refrom_name)." <".$to.">\nReply-To: ".$to;
	}else{
		$reheader .= "$to\nReply-To: ".$to;
	}
	$reheader .= "\nContent-Type: text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
	return $reheader;
}
function mailToUser($arr,$dsp_name,$remail_text,$mailFooterDsp,$mailSignature,$encode){
	$userBody = '';
	if(isset($arr[$dsp_name])) $userBody = h($arr[$dsp_name]). " Mr\n";
	$userBody.= $remail_text;
	$userBody.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
	$userBody.= postToMail($arr);
	$userBody.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
	$userBody.="Sent date：".date( "Y/m/d (D) H:i:s", time() )."\n";
	if($mailFooterDsp == 1) $userBody.= $mailSignature;
	return mb_convert_encoding($userBody,"JIS",$encode);
}
function requireCheck($require){
	$res['errm'] = '';
	$res['empty_flag'] = 0;
	foreach($require as $requireVal){
		$existsFalg = '';
		foreach($_POST as $key => $val) {
			if($key == $requireVal) {
				if(is_array($val)){
					$connectEmpty = 0;
					foreach($val as $kk => $vv){
						if(is_array($vv)){
							foreach($vv as $kk02 => $vv02){
								if($vv02 == ''){
									$connectEmpty++;
								}
							}
						}
						
					}
					if($connectEmpty > 0){
						$res['errm'] .= "<p class=\"error_messe\">【".h($key)."】 This is required.</p>\n";
						$res['empty_flag'] = 1;
					}
				}
				elseif($val == ''){
					$res['errm'] .= "<p class=\"error_messe\">【".h($key)."】 This is required.</p>\n";
					$res['empty_flag'] = 1;
				}
				
				$existsFalg = 1;
				break;
			}
			
		}
		if($existsFalg != 1){
				$res['errm'] .= "<p class=\"error_messe\">【".$requireVal."】 This isn't selected.</p>\n";
				$res['empty_flag'] = 1;
		}
	}
	
	return $res;
}
function refererCheck($Referer_check,$Referer_check_domain){
	if($Referer_check == 1 && !empty($Referer_check_domain)){
		if(strpos($_SERVER['HTTP_REFERER'],$Referer_check_domain) === false){
			return exit('<p align="center">Referrer check error. The domain of the form page does not match the domain of this file.</p>');
		}
	}
}
//----------------------------------------------------------------------
//  Function definition End
//----------------------------------------------------------------------
?>
</div>
</div>
	<div class="AboutContainer">
		<div class="FooterMob">
			<div class="FooterCnt">
				<div class="FooterContact">
					<div class="FtText">
		  
						<p>If there's anything you are unclear on, please contact us. We are recruiting colleagues to work together with us. Please take a look at social media as well.</p>
	
					</div>
				</div><!-- FooterContact end --> 
				<div class="FooterContact">
			
				<span>FOLLOW US</span>
				
				<div class="FooterSclIcons">
			
					<a href="#"><div class="SclIcon"><i class="fa fa-facebook-square"></i></div></a>
					<a href="#"><div class="SclIcon"><i class="fa fa-twitter-square"></i></div></a>
					<a href="#"><div class="SclIcon"><i class="fa fa-google-plus-square"></i></div></a>
					<a href="#"><div class="SclIcon"><i class="fa fa-instagram"></i></div></a>
					<a href="#"><div class="SclIcon"><i class="fa fa-dribbble"></i></div></a>
					<a href="#"><div class="SclIcon"><i class="fa fa-linkedin-square"></i></div></a>
					<a href="#"><div class="SclIcon"><i class="fa fa-pinterest-square"></i></div></a>
				
				</div>
				</div><!-- FooterContact end -->
	</div><!-- about-cotents end --> 
	</div>
  </div><!-- about-group end --> 
<div class="copy"><p><i class="fa fa-copyright"></i> CEPPO 2018 / Call Us At:(123) 456-7890 / Address:Kanetsu-cho, Chuo-ku, Kobe, Hyogo Prefecture 650-0001</p></div>
</section>

<!-- Parallax map js --> 
<script src="../js/jquery-2.1.3.min.js"></script> 
<script src="../js/TweenMax.min.js"></script> 
<script src="../js/mousemove-n.js"></script> 
	
<script src="../js/jquery-2.2.4.min.js"></script>
<script src="../js/jquery.hamburger.js"></script>