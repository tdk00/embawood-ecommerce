<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Payment...</title>
</head>
<body onload="document.getElementById('payment-form').submit();">

<form id="payment-form" action="{{ $data['action'] }}" method="post">
    <input type="hidden" name="AMOUNT" value="{{ $data['amount'] }}">
    <input type="hidden" name="CURRENCY" value="{{ $data['currency'] }}">
    <input type="hidden" name="ORDER" value="{{ $data['order_id'] }}">
    <input type="hidden" name="DESC" value="{{ $data['desc'] }}">
    <input type="hidden" name="MERCH_NAME" value="{{ $data['merch_name'] }}">
    <input type="hidden" name="MERCH_URL" value="{{ $data['merch_url'] }}">
    <input type="hidden" name="TERMINAL" value="{{ $data['terminal'] }}">
    <input type="hidden" name="EMAIL" value="{{ $data['email'] }}">
    <input type="hidden" name="TRTYPE" value="{{ $data['trtype'] }}">
    <input type="hidden" name="COUNTRY" value="{{ $data['country'] }}">
    <input type="hidden" name="MERCH_GMT" value="{{ $data['merch_gmt'] }}">
    <input type="hidden" name="TIMESTAMP" value="{{ $data['oper_time'] }}">
    <input type="hidden" name="NONCE" value="{{ $data['nonce'] }}">
    <input type="hidden" name="BACKREF" value="{{ $data['backref'] }}">
    <input type="hidden" name="LANG" value="{{ $data['lang'] }}">
    <input type="hidden" name="P_SIGN" value="{{ $data['p_sign'] }}">

    <noscript>
        <button type="submit">Proceed to Payment</button>
    </noscript>
</form>

</body>
</html>
