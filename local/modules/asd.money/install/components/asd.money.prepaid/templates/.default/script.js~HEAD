if (typeof jQuery != 'undefined') {
	$(document).on('ready', function(){
		recalcMoney = function() {
			var amount = parseInt($.trim($('#bx-asd-amount').val()) || 0);
			var factor = $('#bx-asd-account option:selected').data('factor');
			var comission = parseInt($('#bx-asd-comission').val());
			var tpl = $('#bx-asd-baseformat').val();
			$('#bx-asd-result').html(tpl.replace('#', Math.round(amount * factor + (amount*factor)/100*comission)));
		};
		$('#bx-asd-account').on('change', recalcMoney);
		$('#bx-asd-amount').on('keyup', recalcMoney);
		recalcMoney();
	});
}