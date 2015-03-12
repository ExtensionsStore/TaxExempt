<dt>
	<input id="p_method_authnetcim" value="authnetcim" type="radio"
		name="payment[method]" title="Credit Card (Authorize.Net CIM)"
		onclick="payment.switchMethod('authnetcim')" class="radio" /> <label
		for="p_method_authnetcim">Credit Card (Authorize.Net CIM) </label>
</dt>
<dd>
	<ul class="form-list" id="payment_form_authnetcim"
		style="display: none;">
		<li class="authnetcim_new"><label for="authnetcim_cc_type"
			class="required"><em>*</em>Credit Card Type</label>
			<div class="input-box">
				<select id="authnetcim_cc_type" name="payment[cc_type]"
					class="required-entry">
					<option value="">-- Select One --</option>
					<option value="AE">American Express</option>
					<option value="VI">Visa</option>
					<option value="MC">MasterCard</option>
					<option value="DI">Discover</option>
				</select>
			</div></li>
		<li class="authnetcim_new"><label for="authnetcim_cc_number"
			class="required"><em>*</em>Credit Card Number</label>
			<div class="input-box">
				<input type="text" id="authnetcim_cc_number"
					name="payment[cc_number]" title="Credit Card Number"
					class="input-text required-entry validate-cc-number"
					autocomplete="off" value="" />
			</div></li>
		<li class="authnetcim_new" id="authnetcim_cc_type_exp_div"><label
			for="authnetcim_expiration" class="required"><em>*</em>Expiration
				Date</label>
			<div class="input-box">
				<div class="v-fix">
					<select id="authnetcim_expiration" name="payment[cc_exp_month]"
						class="month required-entry">
						<option value="" selected="selected">Month</option>
						<option value="1">01 - January</option>
						<option value="2">02 - February</option>
						<option value="3">03 - March</option>
						<option value="4">04 - April</option>
						<option value="5">05 - May</option>
						<option value="6">06 - June</option>
						<option value="7">07 - July</option>
						<option value="8">08 - August</option>
						<option value="9">09 - September</option>
						<option value="10">10 - October</option>
						<option value="11">11 - November</option>
						<option value="12">12 - December</option>
					</select>
				</div>
				<div class="v-fix">
					<select id="authnetcim_expiration_yr" name="payment[cc_exp_year]"
						class="year required-entry">
						<option value="" selected="selected">Year</option>
						<option value="2015">2015</option>
						<option value="2016">2016</option>
						<option value="2017">2017</option>
						<option value="2018">2018</option>
						<option value="2019">2019</option>
						<option value="2020">2020</option>
						<option value="2021">2021</option>
						<option value="2022">2022</option>
						<option value="2023">2023</option>
						<option value="2024">2024</option>
						<option value="2025">2025</option>
					</select>
				</div>
			</div></li>
		<li class="authnetcim_new" id="authnetcim_cc_type_cvv_div"><label
			for="authnetcim_cc_cid" class="required"><em>*</em>Card Verification
				Number</label>
			<div class="input-box">
				<div class="v-fix">
					<input type="text" title="Card Verification Number"
						class="input-text cvv required-entry validate-cc-cvn"
						id="authnetcim_cc_cid" name="payment[cc_cid]" autocomplete="off"
						value="" maxlength="4" />
				</div>
				<a href="#" class="cvv-what-is-this">What is this?</a>
			</div></li>
	</ul>
</dd>
<dt>
	<input id="p_method_purchaseorder" value="purchaseorder" type="radio"
		name="payment[method]" title="Purchase Order"
		onclick="payment.switchMethod('purchaseorder')" class="radio" /> <label
		for="p_method_purchaseorder">Purchase Order </label>
</dt>
<dd>
	<ul class="form-list" id="payment_form_purchaseorder"
		style="display: none;">
		<li><label for="po_number" class="required"><em>*</em>Purchase Order
				Number</label>
			<div class="input-box">
				<input type="text" id="po_number" name="payment[po_number]"
					title="Purchase Order Number" class="input-text required-entry"
					value="" />
			</div></li>
	</ul>
</dd>
<dt>
	<input id="p_method_checkmo" value="checkmo" type="radio"
		name="payment[method]" title="Check / Money order"
		onclick="payment.switchMethod('checkmo')" checked="checked"
		class="radio" /> <label for="p_method_checkmo">Check / Money order </label>
</dt>
<dt>
	<input id="p_method_taxexempt" value="" type="checkbox"
		name="payment[method]" title="Tax Exempt"
		onclick="$('payment_form_taxexempt').style.display='block'"
		class="checkbox"> <label for="">Tax Exempt Number</label>
</dt>
<dd>
	<ul class="form-list" id="payment_form_taxexempt"
		style="display: none;">
		<li><label for="taxexempt_number" class="required"><em>*</em>Tax
				Exempt Number</label>
			<div class="input-box">
				<input type="text" id="taxexempt_number" name="payment[taxexempt]"
					title="Tax Exempt Number" class="input-text required-entry"
					value="" />
			</div></li>
	</ul>
</dd>
<div class="checkout-onepage-payment-additional-giftcardaccount">
	<p class="note">
		To add or remove gift cards, <a
			href="http://local.learningzonexpress.com/index.php/checkout/cart/">click
			here</a>.<br />
	</p>
</div>
<script type="text/javascript">    //<![CDATA[    quoteBaseGrandTotal = 16.95;var isGiftCardApplied = false;var epsilon = 0.0001;function enablePaymentMethods(free) {    Payment.prototype.init = function () {        var elements = Form.getElements(this.form);        var methodName = '';        for (var i=0; i < elements.length; i++) {            if (elements[i].name == 'payment[method]'                || elements[i].name == 'payment[use_customer_balance]'                || elements[i].name == 'payment[use_reward_points]'            ) {                methodName = elements[i].value;                if ((free && methodName == 'free') || (!free && methodName != 'free')) {                    $((elements[i]).parentNode).show();                    if ($('p_method_' + (methodName)) && $('p_method_' + (methodName)).checked) {                        payment.switchMethod(methodName);                    }                    if (free) {                        elements[i].checked = true;                        this.switchMethod('free');                    }                } else {                    $((elements[i]).parentNode).hide();                }            } else {                if ($('p_method_' + methodName) && $('p_method_' + methodName).checked) {                    elements[i].disabled = false;                } else {                    elements[i].disabled = true;                }            }        }    };}if (quoteBaseGrandTotal < epsilon && isGiftCardApplied) {    enablePaymentMethods(true);} else if (quoteBaseGrandTotal >= epsilon) {    enablePaymentMethods(false);}submittedPayments = {};Event.observe(this.document, 'payment-method:switched', function(event){  if (event.stopped == null) {      toggleContinueButton(event.target);      Event.stop(event);  }});if (typeof eventsObserved == 'undefined' && $('use_customer_balance')) {    Event.observe($('use_customer_balance'), 'click', function(event){        toggleContinueButton(event.target);        if (isPaymentSubmitted(payment.currentMethod)) {            reloadIframe(payment.currentMethod);        }    });    eventsObserved = true;}    payment.init();        //]]></script>