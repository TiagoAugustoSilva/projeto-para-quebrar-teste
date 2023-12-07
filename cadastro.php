<?php

require_once('conexao.php');


$id = 0;
$descricao = '';
$situacao = '';


//Area onde vamos submter salvar no banco de dadps
if (isset($_POST['id'])) {
    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT); //: null;
    $descricao = filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $situacao = filter_input(INPUT_POST, "situacao", FILTER_SANITIZE_NUMBER_INT);

   // Verificação com UPDATE ou INSERT

    if (!$id) {
        // Se $id não existe, prepara a instrução INSERT
        $stm = $conn->prepare("INSERT INTO metas (descricao, situacao) VALUES (:descricao, :situacao)");
    } else {
        // Se $id existe, prepara a instrução UPDATE
        $stm = $conn->prepare("UPDATE metas SET descricao = :descricao, situacao = :situacao WHERE id = :id");
        $stm->bindValue(':id', $id);
    }
    
    $stm->bindValue(':descricao', $descricao);
    $stm->bindValue(':situacao', $situacao);
    $stm->execute();
    
   // Redireciona para a página principal
    header('Location: index.php');
    exit;
}

//area onde faremos com que nosso botão editar, realmente faça a edição 
if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

    if (!$id) {
        header('Location: index.php');
        exit;
    }

    $stm = $conn->prepare('SELECT * FROM metas WHERE id=:id');
    $stm->bindValue('id', $id);
    $stm->execute();
    $result = $stm->fetch();

    if (!$result) {
        header('Location: index.php');
        exit;
    }


    $descricao = $result['descricao'];
    $situacao = $result['situacao'];
}


include_once('./layout/_header.php')

?>

<div class="card mt-4">
    <!--Lembrando o Uso do d-flex e justify-content- between posiciona o botão ao final da minhas metas-->
    <div class="card-header d-flex justify-content-between align-items-center bg-warning">
        <!--Este nosso id verifica se existe um ID para Editar-->
        <h5 class="text-primary"><?= $id ? 'Editar Meta' : 'Minhas Metas Test' ?></h5>


    </div>
    <!--utilize o autocomplete para que ele não dê sugestões de auto-preenchimento-->
    <form method="post" autocomplete="off">
        <div class="card-body">
            <!-- Campo oculto para armazenar o ID -->
            <input type="hidden" name="id" value="<?= $id ?>" />
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <input type="text" class="form-control" id="descricao" name="descricao" value="<?= $descricao ?>" required />
            </div>
            <div class="form-group">
                <label for="situacao">Situação</label>
                <select class="form-select" id="situacao" name="situacao">
                    <!--atenção com essa área pois aqui iremos trabalhar com o Editar-->
                    <option value="1" <?= $situacao == 1 ? 'selected' : '' ?>>Aberta</option>
                    <option value="2" <?= $situacao == 2 ? 'selected' : '' ?>>Em andamento</option>
                    <option value="3" <?= $situacao == 3 ? 'selected' : '' ?>>Realizada</option>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success">Salvar</button>
            <a class="btn btn-primary" href="index.php">Voltar</a>

        </div>
    </form>
</div>


<?php include_once('./layout/_footer.php') ?>




<?php include_once('./layout/_footer.php');
