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
                            <?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "auth_ajax",Array(
                                 "REGISTER_URL" => "register.php",
                                 "FORGOT_PASSWORD_URL" => "",
                                 "PROFILE_URL" => "/",
                                 "SHOW_ERRORS" => "Y" 
                                 )
                            );?>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="singup-form">
                            <?$APPLICATION->IncludeComponent("bitrix:system.auth.registration","",Array());?>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="reset-form">
							<div class="modal-form-wrap reset-form-wrap">
								<a href="#" class="close-link" data-dismiss="modal"><span data-icon="icon-times"></span></a>
								<form action="#" class="reset-form">
									<div class="form-group">
										<label for="" class="sr-only">Эл. почта</label>
										<input type="text" name="" id="" class="form-control" placeholder="Эл. почта">
									</div>
									<button type="submit" class="btn btn-primary btn-block">Восстановить пароль</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->