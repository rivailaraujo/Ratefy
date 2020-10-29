<section style="min-height: calc(100vh - 83px);" class="light-bg">
  <div class="container">
    <div class="row">
      <div class="col-lg-offset-3 col-lg-6 text-center">
        <div class="section-title">
          <h2>Área Restrita</h2>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-offset-5 col-lg-2 text-center">
        <div class="form-group">
          <a id="btn_your_user" class="btn btn-link" id_usuario="<?= $id_usuario ?>"><i class="fa fa-user"></i></a>
          <a class="btn btn-link" href="restrict/logoff"><i class="fa fa-sign-out"></i></a>
        </div>
      </div>
    </div>

    <!-- <div class="container">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_projetos" role="tab" data-toggle="tab">Projetos</a></li>
        <li><a href="#tab_team" role="tab" data-toggle="tab">Equipe</a></li>
        <li><a href="#tab_user" role="tab" data-toggle="tab">Usuários</a></li>
      </ul>
    </div> -->

    <div class="tab-content">
      <div id="tab_projetos" class="tab-pane active">
        <div class="container-fluid">
          <h2 class="text-center"><strong>Gerenciar Projetos</strong></h2>
          <a id="btn_add_projeto" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Adicionar Projeto</i></a>
          <table id="dt_projetos" class="table table-striped table-bordered">
            <thead>
              <tr class="tableheader">
                <th class="dt-center">Nome</th>
                <th class="dt-center no-sort">Imagem</th>
                <th class="no-sort">Descrição</th>
                <th class="no-sort">Key</th>
                <th class="dt-center no-sort">Ações</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <div id="tab_team" class="tab-pane">
        <div class="container-fluid">
          <h2 class="text-center"><strong>Gerenciar Equipe</strong></h2>
          <a id="btn_add_member" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Adicionar Membro</i></a>
          <table id="dt_team" class="table table-striped table-bordered">
            <thead>
              <tr class="tableheader">
                <th class="dt-center">Nome</th>
                <th class="dt-center no-sort">Foto</th>
                <th class="no-sort">DescriГ§ГЈo</th>
                <th class="dt-center no-sort">AГ§Гµes</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <div id="tab_user" class="tab-pane">
        <div class="container-fluid">
          <h2 class="text-center"><strong>Gerenciar UsuГЎrios</strong></h2>
          <a id="btn_add_user" class="btn btn-primary"><i class="fa fa-plus">&nbsp;&nbsp;Adicionar UsuГЎrio</i></a>
          <table id="dt_users" class="table table-striped table-bordered">
            <thead>
              <tr class="tableheader">
                <th>Login</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th class="dt-center no-sort">AГ§Гµes</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div id="modal_projeto" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">x</button>
          <h4 class="modal-title">Projetos</h4>
        </div>

        <div class="modal-body">
          <form id="form_projeto">

            <input id="projeto_id" name="projeto_id" hidden>
            

            <div class="form-group">
              <label class="col-lg-2 control-label">Nome</label>
              <div class="col-lg-10">
                <input id="projeto_nome" name="projeto_nome" class="form-control" maxlength="100">
                <span class="help-block"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-lg-2 control-label">Imagem</label>
              <div class="col-lg-10">
                <img id="projeto_img_path" src="" style="max-height: 400px; max-height: 400px">
                <label class="btn btn-block btn-info">
                  <i class="fa fa-upload"></i>&nbsp;&nbsp;Importar imagem
                  <input type="file" id="btn_upload_projeto_img" accept="image/*" style="display: none;">
                </label>
                <input id="projeto_img" name="projeto_img" hidden>
                <span class="help-block"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-lg-2 control-label">Descrição</label>
              <div class="col-lg-10">
                <textarea id="projeto_descricao" name="projeto_descricao" class="form-control"></textarea>
                <span class="help-block"></span>
              </div>
            </div>

            <div class="form-group text-center">
              <button type="submit" id="btn_save_projeto" class="btn btn-primary">
                <i class="fa fa-save"></i>&nbsp;&nbsp;Salvar
              </button>
              <span class="help-block"></span>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  




</section>