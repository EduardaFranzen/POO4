<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Language" content="pt-br">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
            <link rel="stylesheet" href="style.css" media="all">
        <title>Turmas</title>
    </head>

    <body>
          <header id="header" class="fixed-top d-flex align-items-cente">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-lg-between">

      <h1 class="logo me-auto me-lg-0"><a>Eduarda Franzen</a></h1>
      <nav id="navbar" class="navbar order-last order-lg-0">
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->
      <a href="file:///C:/wamp64/www/CRUD2Aluno/CRUD/Tela%20Inicial/index.html">Tela Inicial</a>
      <a href="file:///C:/wamp64/www/CRUD2Aluno/CRUD/professores.php" class="book-a-table-btn scrollto d-none d-lg-flex">Professores</a>
            <a href="file:///C:/wamp64/www/CRUD2Aluno/CRUD/alunos.php" class="book-a-table-btn scrollto d-none d-lg-flex">Alunos</a>
    </div>
  </header>
  <br>
</br>
  <br>
</br>
  <br>
</br>
        <h1>Cadastro de Turmas</h1>
        <?php
        session_start();
        include "./classes/Turma.php";

        if (isset($_GET['acao'])) {
            if ($_GET['acao'] == "salvar") {
                if ($_POST['enviar-turma']) {
                    $professor = new Professor();
                    $professor->setProfessor($_POST['codigo-professor-turma'], null);
                    $turma = new Turma();

                    $turma->setTurma(
                        $_POST['codigo_turma'],
                        $_POST['curso-turma'],
                        $_POST['nome-turma'],
                        $professor
                    );

                    if ($turma->salvar()) {
                        $msg['msg'] = "Registro Salvo com sucesso!";
                        $msg['class'] = "success";
                    } else {
                        $msg['msg'] = "Falha ao sakval Registro!";
                        $msg['class'] = "success";
                    }
                    $_SESSION['msgs'][] = $msg;
                    unset($turma);
                }
            } else if ($_GET['acao'] == "excluir") {
                if (isset($_GET['codigo'])) {
                    if (Turma::excluir($_GET['codigo'])) {
                        $msg['msg'] = "Registro excluido com sucesso!";
                        $msg['class'] = "success";
                    } else {
                        $msg['msg'] = "Falha ao excluir Registro!";
                        $msg['class'] = "danger";
                    }
                    $_SESSION['msgs'][] = $msg;
                }
                header("location: turmas.php");
            } else if ($_GET['acao'] == "editar") {
                if (isset($_GET['codigo'])) {
                    $turma = Turma::getTurma($_GET['codigo']);
                }
            }
        }

        if (isset($_SESSION['msgs'])) {

            foreach ($_SESSION['msgs'] as $msg)
                echo "<div id='msg' class='alert alert-{$msg['class']}'>{$msg['msg']}</div>";

            echo "<script> 
        setTimeout(
            function(){
                document.querySelector('#msg').style='display:none';
            }
            ,
            5000
        );
    </script>";
            unset($_SESSION['msgs']);
        }

        if (!isset($turma)) {
            $turma = new Turma();
            $turma->setTurma(null, null, null, new Professor());
        }
        ?>
        <div class="container-fluid">
            <form name="form-turma" method="POST" action="?acao=salvar">
                <input type="hidden" name="codigo_turma" value="<?php echo $turma->getCodigo() ?>" />
                <div class="input-group mb-2 mb-2">
                    <label class="input-group-text" for="inputGroupCurso">Curso</label>
                    <select class="form-select" name="curso-turma">
                        <option value="<?php echo $turma->getCurso() ?>"><?php echo $turma->getCurso() ?></option>
                        <option value="Informática">Informática</option>
                        <option value="Eletronica">Eletrônica</option>
                        <option value="Eletrotécnica">Eletrotécnica</option>
                        <option value="Macânica">Mecânica</option>
                    </select>
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Nome da Turma:</span>
                    <input type="text" class="form-control" id="nome-turma" name="nome-turma" value="<?php echo $turma->getNome() ?>">
                </div>
                <div class="input-group mb-2 mb-2">
                    <label class="input-group-text" for="inputGroupProfessor">Professor</label>
                    <select class="form-select" name="codigo-professor-turma">
                        <option value="<?php echo $turma->getProfessor()->getCodigo()  ?>"><?php echo $turma->getProfessor()->getNome()  ?></option>
                        <?php
                        $professor = new Professor();
                        $professores = $professor->listar();
                        if ($professores) {
                            foreach ($professores as $item) {
                                echo "<option value='{$item->getCodigo()}'>{$item->getNome()}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <input type="submit" class="btn btn-primary" name="enviar-turma" value="Enviar" />

            </form>
            <hr />
        </div>
        <?php
       
        ?>
        <div class="container-fluid">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Turma</th>
                        <th scope="col">Curso</th>
                        <th scope="col">Professor</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                   
                    $turmas = Turma::listar();
                    foreach ($turmas as $turma) {
                        echo "<tr>
                        <td>{$turma->getCodigo()}</td>
                        <td>{$turma->getNome()}</td>
                        <td>{$turma->getCurso()}</td>
                        <td>{$turma->getProfessor()->getNome()}</td>
                        <td>
                            <span class='badge rounded-pill bg-primary'>
                                <a href='?acao=editar&codigo={$turma->getCodigo()}' style='color:#fff'><i class='bi bi-pencil-square'></i></a>
                            </span>
                            <span class='badge rounded-pill bg-danger'>
                                <a href='?acao=excluir&codigo={$turma->getCodigo()}'style='color:#fff'><i class='bi bi-trash'></i></a>
                            </span>
                        </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>    