<div id="modal_user" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">x</button>
					<h4 class="modal-title">Usuário</h4>
				</div>

				<div class="modal-body">
					<form id="form_user">

						<input id="user_id" name="user_id" hidden>

						<div class="form-group">
							<label class="col-lg-2 control-label">Login</label>
							<div class="col-lg-10">
								<input id="user_login" name="user_login" class="form-control" maxlength="30">
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Nome Completo</label>
							<div class="col-lg-10">
								<input id="user_full_name" name="user_full_name" class="form-control" maxlength="100">
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">E-mail</label>
							<div class="col-lg-10">
								<input id="user_email" name="user_email" class="form-control" maxlength="100">
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Confirmar E-mail</label>
							<div class="col-lg-10">
								<input id="user_email_confirm" name="user_email_confirm" class="form-control" maxlength="100">
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Senha</label>
							<div class="col-lg-10">
								<input type="password" id="user_password" name="user_password" class="form-control">
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Confirmar Senha</label>
							<div class="col-lg-10">
								<input type="password" id="user_password_confirm" name="user_password_confirm" class="form-control">
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group text-center">
							<button type="submit" id="btn_save_user" class="btn btn-primary">
								<i class="fa fa-save"></i>&nbsp;&nbsp;Salvar
							</button>
							<span class="help-block"></span>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>