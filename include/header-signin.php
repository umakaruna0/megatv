<div class="modal fade" id="auth-screens">
	<div class="modal-dialog">
		<div class="modal-content">
			<ul class="modal-nav">
				<li class="active"><a href="#singin-form" data-toggle="tab">Войти</a></li>
				<li><a href="#singup-form" data-toggle="tab">Регистрация</a></li>
				<li><a href="#reset-form" data-toggle="tab">Восстановить пароль</a></li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="singin-form">
                    <div class="modal-form-wrap signin-form-wrap">
                        <a href="#" class="close-link" data-dismiss="modal"><span data-icon="icon-times"></span></a>
                        <?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "auth_ajax",Array(
                             "REGISTER_URL" => "register.php",
                             "FORGOT_PASSWORD_URL" => "",
                             "PROFILE_URL" => "/",
                             "SHOW_ERRORS" => "Y" 
                             )
                        );?>
                    </div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="singup-form">
                    <div class="modal-form-wrap singup-userdata-form-wrap">
                        <a href="#" class="close-link" data-dismiss="modal"><span data-icon="icon-times"></span></a>
                        <?$APPLICATION->IncludeComponent("bitrix:system.auth.registration","",Array());?>
                    </div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="reset-form">
					<div class="modal-form-wrap reset-form-wrap">
						<a href="#" class="close-link" data-dismiss="modal"><span data-icon="icon-times"></span></a>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:system.auth.forgotpasswd",
                            ".default",
                            Array()
                        );?>
					</div>
				</div>
			</div>
		</div>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->