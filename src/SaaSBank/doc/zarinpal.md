کد نمونه استفاده از وب سرويس نوشته شده به زبان PHP که بر اساس ٢ کلاس SoapClient و Nusoap موجود در PHP عمل می نمايد.
جهت استفاده از اين نمونه کد اول تنها کافی است تا از نصب بودن Soap Extension بر روی سرور خود اطمينان حاصل نماييد. جهت راهنما می توانيد به اين قسمت مراجعه نماييد.
برای نمونه کد دوم نیازی به نصب بودن ماژول خاصی بر روی PHP نمی باشد.

کد نمونه شامل ٢ فايل می باشد که به سادگی قابل استفاده می باشند. توصيه می شود جهت استفاده از webservice ابتدا راهنمای استفاده از وب سرويس زرين پال را مطالعه نماييد.

نمونه ی کد ایجاد شناسه ی پرداخت و ارجاع کاربر به درگاه پرداخت زرین پال :


	<?php
	$MerchantID = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX'; //Required
	$Amount = 1000; //Amount will be based on Toman - Required
	$Description = 'توضیحات تراکنش تستی'; // Required
	$Email = 'UserEmail@Mail.Com'; // Optional
	$Mobile = '09123456789'; // Optional
	$CallbackURL = 'http://www.yoursoteaddress.ir/verify.php'; // Required
	
	$client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', 
		['encoding' => 'UTF-8']);
	
	$result = $client->PaymentRequest([
		'MerchantID' => $MerchantID,
		'Amount' => $Amount,
		'Description' => $Description,
		'Email' => $Email,
		'Mobile' => $Mobile,
		'CallbackURL' => $CallbackURL,
	]);
	
	//Redirect to URL You can do it also by creating a form
	if ($result->Status == 100) {
		Header('Location: https://www.zarinpal.com/pg/StartPay/'.$result->Authority);
	} else {
		echo 'ERR: '.$result->Status;
	}

نمونه کد تصدیق اصالت پس از پرداخت :

	<?php
	$MerchantID = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX';
	$Amount = 1000; //Amount will be based on Toman
	$Authority = $_GET['Authority'];
	
	if ($_GET['Status'] == 'OK') {
		$client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', 
			['encoding' => 'UTF-8']);
		
		$result = $client->PaymentVerification(	[
			'MerchantID' => $MerchantID,
			'Authority' => $Authority,
			'Amount' => $Amount,
		]);
	
		if ($result->Status == 100) {
			echo 'Transation success. RefID:'.$result->RefID;
		} else {
			echo 'Transation failed. Status:'.$result->Status;
		}
	} else {
		echo 'Transaction canceled by user';
	}
