برنامه نویسی درگاه با توجه به نیاز به استفاده از توابع Soap در PHP کاری بسیار چالش برانگیز بوده، زیرا این تابع در نسخه های مختلف PHP رفتار متفاوتی را از خود نشان می دهد. شرکت پرداخت بانک ملت کد نمونه ای را جهت اتصال به سرور پرداخت آنلاین که با استفاده از کتابخانه NuSOAP ارائه نموده که در سرورهایی با نسخه PHP 5.3 به بالا به مشکل بر می خورد. در بخش زیر نحوه برنامه نویسی درگاه پرداخت آنلاین توسط تابع SoapClient که از توابع داخلی PHP بوده را برای شما بیان می نمایم. قبل از هر چیز دقت نمایید این تابع در تنظیمات PHP فعال شده باشد . در صورت عدم فعال سازی با سرور خود تماس حاصل فرمایید.

برای ارسال درخواست خود فرم پرداخت آنلاین را که شامل فیلدهای موجود در بخش دریافت اطلاعات از کاربر در کد زیرین می باشد ایجاد نمایید.

* دقت نمایید جهت پرداخت آنلاین از طریق بانک ملت می بایست قبل از ارسال کاربر به صفحه پرداخت درخواست خود را از طریق SOAP به سرور ارسال نموده و تایید درخواست خود را بگیرید. برای انجام این کار از کد SoapClient استفاده می نماییم:

	try { 
		$client = @new SoapClient('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
	} catch (Exception $e) { 
		die($e->getMessage()); 
	}
پس از اتصال به سرور پرداخت آنلاین و عدم بروز خطا می بایست درخواست خود را به سرور ارسال نمایید. برای انجام این کار از کد زیر استفاده می نمایید:

	
	$namespace='http://interfaces.core.sw.bps.com/';
	$terminalId = $_POST['TerminalId'];
	$userName = $_POST['UserName'];
	$userPassword = $_POST['UserPassword'];
	$orderId = filter_var($_POST['PayOrderId'], FILTER_SANITIZE_NUMBER_INT);
	$amount = $_POST['PayAmount'];
	//$date = date("YYMMDD");
	//$time = date("HHIISS");
	$localDate = $_POST['PayDate'];
	$localTime = $_POST['PayTime'];
	$additionalData = $_POST['PayAdditionalData'];
	$callBackUrl = $_POST['PayCallBackUrl'];
	$payerId = $_POST['PayPayerId'];
	// قرار دادن پارامترها در یک آرای
	$parameters = array(
		'terminalId' => $terminalId,
		'userName' => $userName,
		'userPassword' => $userPassword,
		'orderId' => $orderId,
		'amount' => $amount,
		'localDate' => $localDate,
		'localTime' => $localTime,
		'additionalData' => $additionalData,
		'callBackUrl' => $callBackUrl,
		'payerId' => $payerId);
	
	// ارسال درخواست پرداخت به سرور بانک
	$result = $client->bpPayRequest($parameters, $namespace);

تابع bpPayRequest کار ارسال درخواست پرداخت به سرور را انجام می دهد که در صورتی که برگشتی این تابع 0 باشد به آن معنی می باشد که پرداخت قابل انجام می باشد و در صورت برگشتی هر عددی به جز 0 به آن معنا بوده که خطایی در انجام پرداخت وجود دارد. برای چک نمودن مقدار برگشتی تابع bpPayRequest از کد زیر استفاده نمایید:

	$res = @explode (',',$resultStr);
	if(is_array($res)){
		echo "<script>alert('Pay Response is : " . $resultStr . "');</script>";
		echo "Pay Response is : " . $resultStr;
		$ResCode = $res[0];
		if ($ResCode == "0") {
			// Update table, Save RefId
			echo "<script language='javascript' type='text/javascript'>postRefId('" . $res[1] . "');</script>";
		} 
		else {
			// log error in app
			// Update table, log the error
			// Show proper message to user
		}
	}

در کد بالا ResCode بخش اول خروجی تابع bpPayRequest می باشد که در صورتی که این عدد 0 باشد به آن معناست که پرداخت قابل انجام می باشد و می بایست کاربر را به سمت سرور انتقال دهید. برای انتقال کاربر به سرور از کد جاوا اسکریپت زیر استفاده نمایید که این کد می بایست در HTML صفحه پرداخت قرار داده شود:

	<script language="javascript" type="text/javascript"> 
		function postRefId (refIdValue) {
		var form = document.createElement("form");
		form.setAttribute("method", "POST");
		form.setAttribute("action", "https://bpm.shaparak.ir/pgwchannel/startpay.mellat"); 
		form.setAttribute("target", "_self");
		var hiddenField = document.createElement("input"); 
		hiddenField.setAttribute("name", "RefId");
		hiddenField.setAttribute("value", refIdValue);
		form.appendChild(hiddenField);
		document.body.appendChild(form); 
		form.submit();
			document.body.removeChild(form);
		}
	</script>

تابع جاوا اسکریپت بالا کاربر را به صفحه پرداخت بانک ملت هدایت می نماید. پس از اینکه کاربر در صفحه پرداخت بانک ملت عملیات پرداخت را انجام نمود دوباره به سایت شما بازگشت داده خواهد شد. کاربر به صفحه ای بازگشت داده خواهد شد که شما در متغیر callBackUrl در مرحله قبل به سرور اعلام نموده اید. درگاه پرداخت بانک ملت 4 پارامتر را به صورت POST به آدرس callBackUrl وب سایت شما ارسال می نماید که این چهار متغیر شامل موارد زیر می باشند.

	$RefId = $_POST['RefId'];
	$ResCode = $_POST['ResCode'];
	$saleOrderId = $_POST['SaleOrderId'];
	$SaleReferenceId = $_POST['SaleReferenceId'];

در صورتی که مقدار متغیر ResCode عددی جز 0 باشد به این معناست که خطایی در پرداخت رخ داده و می توانید کار را ادامه ندهید. در صورتی که مقدار ResCode برابر با 0 باشد می بایست پرداخت را تایید نمایید.

	if($ResCode==0){
	>alert('Pay Response is : " . $resultStr . "');</script>";
		//echo "Pay Response is : " . $resultStr;
		
		$ResCode = $res[0];
		
		if ($ResCode == "0") {
				// Update table, Save RefId
				$resultsettle = $client->bpSettleRequest($parameters, $namespace);
				$resultStrsettle = $resultsettle->return;
				$ressettle = @explode (',',$resultStrsettle);
				$ResCodesettle = $ressettle[0];
				if ($ResCodesettle == "0") {
					$paymentdone="done";
				}
		} else {
			// log error in app
			// Update table, log the error
			// Show proper message to user
		}	//
	try { 
		$client = @new SoapClient('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
	} catch (Exception $e) { 
		die($e->getMessage()); 
	} 

	$namespace='http://interfaces.core.sw.bps.com/';
	
	$terminalId = terminalid;
	$userName = "username";
	$userPassword = "password";
	
	
	$parameters = array(
	'terminalId' => $terminalId,
	'userName' => $userName,
	'userPassword' => $userPassword,
	'orderId' => $saleOrderId,
	'saleOrderId' => $saleOrderId,
	'saleReferenceId' => $SaleReferenceId);
	$result = $client->bpVerifyRequest($parameters, $namespace);
	
	$resultStr = $result->return;
	$res = @explode (',',$resultStr);
	if(is_array($res)){
		echo "<script>alert('Pay Response is : " . $resultStr . "');</script>";
		//echo "Pay Response is : " . $resultStr;
		
		$ResCode = $res[0];
		
		if ($ResCode == "0") {
				// Update table, Save RefId
				$resultsettle = $client->bpSettleRequest($parameters, $namespace);
				$resultStrsettle = $resultsettle->return;
				$ressettle = @explode (',',$resultStrsettle);
				$ResCodesettle = $ressettle[0];
				if ($ResCodesettle == "0") {
					$paymentdone="done";
				}
		} else {
			// log error in app
			// Update table, log the error
			// Show proper message to user
		}
	}

در کد بالا تابع bpVerifyRequest عملیات تایید پرداخت را انجام می دهد و در صورتی که خروجی آن نیز 0 باشد می توانید وجه را از حساب کاربر با دستور bpSettleRequest به حساب خود منتقل نمایید. متغیر paymentdone زمانی برابر با done قرار داده می شود که پرداخت به درستی انجام شده باشد و واریز وجه به حساب شما انجام شده باشد. دقت نمایید ممکن است عملیات واریز وجه به حساب شما چندین ساعت طول بکشد. در بخش پایین همچنین آموزشی شرکت به پرداخت ملت را برای شما ضمیمه کردم تا بتوانید توضیحات بیشتر را مطالعه نمایید. در صورت وجود مشکل و یا سوال لطفا موارد را در بخش نظرات اعلام نمایید.