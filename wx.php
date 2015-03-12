<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "XXX");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
        public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
                echo $echoStr;
                exit;
        }
    }

   public function responseMsg()
    {
       // $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
         //$postStr = file_get_contents("php://input");
         $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            //用户关注推送消息
            $event=$postObj->Event;
            $time = time();
	    /****发送邮件***/
            $string="微信有新留言，留言内容：".$keyword;//邮件内容
            $email="xxxx@foxmail.com";//收件人
	        $subject="微信留言提醒";//邮件名
            $this->mailtoo($email,$string,$subject);
            /****发送邮件结束****/
            if($event=="subscribe"){
                echo $resultStr="<xml>
                                <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
                                <FromUserName><![CDATA[".$toUsername."]]></FromUserName>
                                <CreateTime>".$time."</CreateTime>
                                <MsgType><![CDATA[text]]></MsgType>
                                <Content><![CDATA[your message here]]></Content>
                                <FuncFlag>0</FuncFlag>
                                </xml>";
                                exit();
            }
            /***********自动匹配回复***************/
            /***********精确匹配回复开始**************/
            if($keyword=="入会流程"){
                echo $resultStr="<xml>
                                <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
                                <FromUserName><![CDATA[".$toUsername."]]></FromUserName>
                                <CreateTime>".$time."</CreateTime>
                                <MsgType><![CDATA[text]]></MsgType>
                                <Content><![CDATA[your message here]]></Content>
                                <FuncFlag>0</FuncFlag>
                                </xml>";
                                exit();
            }
            /***********精确匹配回复结束**************/
            /***********模糊匹配回复开始**************/
            if(strpos($keyword,"入会流程")>0){
                echo $resultStr="<xml>
                                <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
                                <FromUserName><![CDATA[".$toUsername."]]></FromUserName>
                                <CreateTime>".$time."</CreateTime>
                                <MsgType><![CDATA[text]]></MsgType>
                                <Content><![CDATA[your message here]]></Content>
                                <FuncFlag>0</FuncFlag>
                                </xml>";
                                exit();
            }
            /***********模糊匹配回复结束**************/

                echo $resultStr="<xml>
                                <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
                                <FromUserName><![CDATA[".$toUsername."]]></FromUserName>
                                <CreateTime>".$time."</CreateTime>
                                <MsgType><![CDATA[text]]></MsgType>
                                <Content><![CDATA[your message here]]></Content>
                                <FuncFlag>0</FuncFlag>
                                </xml>";
                                exit();

        }
    }

        private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
	}

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

                $token = TOKEN;
                $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
                sort($tmpArr, SORT_STRING);
                $tmpStr = implode( $tmpArr );
                $tmpStr = sha1( $tmpStr );

                if( $tmpStr == $signature ){
                        return true;
                }else{
                        return false;
		}
	}
	/**************邮件函数***************/
        private function mailtoo($email, $string ,$subject){
            //echo $nickname.$address.$id.$activation_code;
            date_default_timezone_set('PRC');
            include_once("class.phpmailer.php");
            $mail = new PHPMailer(); // defaults to using php "mail()"
            $mail->IsSMTP();
            $mail->SMTPDebug  = 1;
            $mail->Host = "";            // SMTP 服务器
            $mail->Port       = 25;
            $mail->SMTPAuth = true;                  // 打开SMTP认证
            $mail->Username = "";   // 用户名
            $mail->Password = "";          // 密码
            $body = $string;

            $mail->AddReplyTo("xxxx@foxmail","mail name");
            $mail->SetFrom('xxxx@foxmail', 'mail name');
            $mail->AddReplyTo("xxxx@foxmail","mail name");
            $mail->AddAddress($email);
            $mail->Subject = $subject;
            // optional, comment out and test
            $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
            $mail->MsgHTML($body);
            //$mail->AddAttachment("images/phpmailer.gif");      // attachment
            //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
	    $mail->Send();
            /*if(!$mail->Send()) {

                echo "Mailer Error: " . $mail->ErrorInfo;

            }

            else {

                echo "ok";
                //echo "Message sent!";

            }*/

        }
    /**************邮件函数结束***************/
	
}

?>

