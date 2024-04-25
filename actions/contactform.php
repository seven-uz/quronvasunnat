<?
session_start();

include '../blocks/brain.php';
include '../functions.php';

if(isset($_POST['name'])) {$name = $_POST['name'];}
if(isset($_POST['email'])) {$email = $_POST['email'];}
if(isset($_POST['text'])) {$text = $_POST['text'];}
if(isset($_POST['code'])) {$code = $_POST['code'];}
if(isset($_POST['mt1'])) {$mt1 = $_POST['mt1'];}
if(isset($_POST['mt2'])) {$mt2 = $_POST['mt2'];}
$codeft = $mt1 + $mt2;

?><script>
    $(".my-modal-confirm").on("click", function(){
    $(".mymodal").css("display", "none");
  });
</script><?
if($_POST) {
   $name = "";
   $email = "";
   $text = "";
   $code = "";

   if(isset($_POST['name'])) {
     $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   }

   if(isset($_POST['email'])) {
       $email = str_replace(array("\r", "\n", "%0a", "%0d"), '', $_POST['email']);
       $email = filter_var($email, FILTER_VALIDATE_EMAIL);
   }

   if(isset($_POST['text'])) {
       $text = filter_var($_POST['text'], FILTER_SANITIZE_STRING);
   }

   if(isset($_POST['code'])) {
       $code = filter_var($_POST['code'], FILTER_SANITIZE_STRING);
   }

   if(isset($_POST['visitor_message'])) {
       $visitor_message = htmlspecialchars($_POST['visitor_message']);
   }


   $name = textType2($name);
   $email = textType($email);
   $text = textType($text);

   $to = "seven.uz@mail.ru";

   $headers  = 'MIME-Version: 1.0' . "\r\n"
   .'Content-type: text/html; charset=utf-8' . "\r\n"
   .'From: ' . $email . "\r\n";

   if($code != $codeft){
      echo modalErr("Kodni to'g'ri termadingiz!");
      exit;
   }

   if(mail($to, $email, $text, $headers)) {
		echo modalScs("<p>Xabaringiz uchun tashakkur, $name. Xabar ko'rsatilgan manzilga yetkazildi</p>");
		?><script>
			$("#contactform").trigger("reset");
		</script><?
   } else {
		echo modalErr("<p>Xabar yuborilmadi, xatolik bor. Qaytadan urinib ko'ring</p>");
   }

} else {
   echo '<p>Qandaydur xatolik bor. Sayt administratoriga murojaat qiling!</p>';
}

// $string = 'One of your posts about inequalities mentioned that when x < y and y < z then x < z.';

// // Output: One of your posts about inequalities mentioned that when x
// echo filter_var($string, FILTER_SANITIZE_STRING);

// // Output: One of your posts about inequalities mentioned that when x &lt; y and y &lt; z then x &lt; z.
// echo htmlspecialchars($string);