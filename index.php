<?php

require_once('conexao.php');


//area de excluir ao clicar no botao excluir
if(isset($_GET['excluir'])) {
    $id = filter_input(INPUT_GET, 'excluir', FILTER_SANITIZE_NUMBER_INT);

    if ($id)
    $conn->exec('DELETE FROM metas WHERE id=' . $id);


    header('Location: index.php');
    exit;
}


$results = $conn->query('select * from metas')->fetchAll();

$arraySituacao = [1 => 'Aberta', 2 => 'Em andamento', 3 => 'Realizada'];

include_once('./layout/_header.php');

?>

<div class="card mt-4">
    <!--Lembrando o Uso do d-flex e justify-content- between posiciona o botão ao final da minhas metas-->
    <div class="card-header d-flex justify-content-between align-items-center bg-warning">
        <h5 class="text-primary">Metas Test</h5>
        <a class="btn btn-success" href="cadastro.php">+Adicionar</a>

    </div>
    <div class="card-body">
        <table class="table table-striped  table-dark">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <!--tbody é onde vamos ter os resgitros percorrendo pelos $results-->
            <tbody>
                <!--foreach é o laço de repetição -->
                <!--Lembrando abrir em foreach e fechar em endforeach o laço de repetição-->
                <?php foreach ($results as $item) : ?>
                    <tr>
                        <td><?= $item['descricao'] ?></td>
                        <!--area do array que altera a situação de número para texto, para que apareça no browser-->
                        <td><?= $arraySituacao[$item['situacao']] ?></td>
                        <td>
                            <!--sm relaciona o tamanho do botão!!-->
                            <!--query param =$item['id] junto ao cadastro.php?id-->
                            <a class="btn btn-sm btn-primary" href="cadastro.php?id=<?= $item['id'] ?>">Editar</a>
                            <button class="btn btn-sm btn-danger" onclick="excluir(<?= $item['id'] ?>)">Excluir</button>


                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>
<script>
    function excluir(id) {
        if (confirm("Deseja excluir esta meta?")) {
       window.location.href = "index.php?excluir=" + id;
            }
    }
</script>






<?php include_once('./layout/_footer.php'); ?>
