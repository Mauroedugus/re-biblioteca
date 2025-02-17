<!-- Abrindo codigo php -->
<?php 

// verifica se foi enviado um arquivo
if (isset($_FILES['arquivo']['name']) && $_FILES['arquivo']['error'] == 0) {
    /* echo 'Você enviou o arquivo: <strong>' . $_FILES[ 'arquivo' ][ 'name' ] . '</strong><br />';
  echo 'Este arquivo é do tipo: <strong > ' . $_FILES[ 'arquivo' ][ 'type' ] . ' </strong ><br />';
  echo 'Temporáriamente foi salvo em: <strong>' . $_FILES[ 'arquivo' ][ 'tmp_name' ] . '</strong><br />';
  echo 'Seu tamanho é: <strong>' . $_FILES[ 'arquivo' ][ 'size' ] . '</strong> Bytes<br /><br />'; */

    $arquivo_tmp = $_FILES['arquivo']['tmp_name'];
    $nome = $_FILES['arquivo']['name'];

    // Pega a extensão
    $extensao = pathinfo($nome, PATHINFO_EXTENSION);

    // Converte a extensão para minúsculo
    $extensao = strtolower($extensao);

    // Somente imagens, .jpg;.jpeg;.gif;.png
    // Aqui eu enfileiro as extensões permitidas e separo por ';'
    // Isso serve apenas para eu poder pesquisar dentro desta String
    if (strstr('.jpg;.jpeg;.gif;.png', $extensao)) {
        // Cria um nome único para esta imagem
        // Evita que duplique as imagens no servidor.
        // Evita nomes com acentos, espaços e caracteres não alfanuméricos
        $novoNome = uniqid(time()) . '.' . $extensao;

        // Concatena a pasta com o nome
        $destino = '../img/' . $novoNome;

        // tenta mover o arquivo para o destino
        if (@move_uploaded_file($arquivo_tmp, $destino)) {
            /* echo 'Arquivo salvo com sucesso em : <strong>' . $destino . '</strong><br />';
          echo ' < img src = "' . $destino . '" />'; */
        } else
            header('location: cadastrar.php?=status=erro_cadastrar');
    } else
        header('location: cadastrar.php?=status=erro_cadastrar');

};

  /* Pegando valores do formulario */
  if(isset($_POST['submit'])){
    $cod_livro = $_POST['cod_livro'];
    $imagem = $novoNome;
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $cod_categoria = $_POST['cod_categoria'];
    $quantidade = $_POST['quantidade'];

    //Inserindo valores pego do formulario no banco de dados
    //Montando a query
    $query = $conn->prepare("INSERT INTO livro (cod_livro, imagem, titulo, autor, cod_categoria, quantidade) VALUES (?, ?, ?, ?, ?, ?)");
    //Verificando dados inseridos e passando parametro de qual tipo eles ser. (evitar SQL INJECTION)
    $query->bind_param("ssssii", $cod_livro, $imagem, $titulo, $autor, $cod_categoria, $quantidade);
    //executa a query
    $query->execute();

    header('location:livros.php?=status=success');
    /* echo "<pre>"; print_r($imagem); echo "</pre>"; exit; */
  };

?>

<section class="container-xl corpo">

   <div class="titulo-pagina">
    <h1>Cadastrar Livro</h1>
  </div>

<!-- Formulario de cadastro de livros -->
  <form class="mt-4" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Codigo do Livro</label>
        <input class="form-control" type="text" name="cod_livro" required>
      </div>

      <div class="mb-3">
        <label for="formFile" class="form-label">Imagem</label>
        <input class="form-control" type="file" name="arquivo" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Titulo</label>
        <input class="form-control" type="text" name="titulo" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Autor</label>
        <input class="form-control" type="text" name="autor" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Categoria</label>
        <input class="form-control" type="text" name="cod_categoria" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Quantidade</label>
        <input class="form-control" type="text" name="quantidade" required>
      </div>

      <div class="mb-3">
        <input class="btn btn-primary" type="submit" name="submit" value="Cadastrar Livro">
      </div>
  </form>

</section>