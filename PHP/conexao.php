<?php

$dsn = 'mysql:host=localhost;dbname=netflix_db';
$usuario = 'root';
$senha = '';

try{
    $conexao = new PDO($dsn, $usuario, $senha);
    // Definindo o modo de erro do PDO para exceção 
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Dados do novo usuário (você pode obter esses dados de um formulário)
    $email = '';
    $senha = password_hash('sua_senha', PASSWORD_DEFAULT ); // senha criptografada
    $telefone = '';

    // Preparar a consulta SQL
    $stmt = $conexao->prepare("INSERT INTO usuarios (email,senha, telefone) VALUES(:email,:senha,:telefone)");

    // Bind dos parâmetros
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':telefone', $telefone);

    // Executar a consulta
    $stmt->execute();

    echo "Usuário inserido  com sucesso!";
}catch(PDOException $e){
    echo "Erro na conexão: " . $e->getMessage();
}


?>