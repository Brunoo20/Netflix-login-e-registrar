<?php
session_start();


// Definir o cabeçalho para JSON
header('Content-Type: application/json');

$dsn = 'mysql:host=localhost;dbname=netflix_db';
$usuario = 'root';
$senha = '';
$contato = $_POST['contato'];


$email= '';
$telefone = '';

// Verifica se o contato é um email
if(filter_var($contato,FILTER_VALIDATE_EMAIL)){
    // É um email válido
    $email = $contato;
   
}elseif(preg_match('/^\d{1,15}$/', $contato)){
    // É um telefone válido
    $telefone = $contato;
    
}else{
    echo json_encode(["mensagem" => "Por favor, insira uma email ou um número de telefone válido."]);
    exit();
}

// Verificação da senha
$senha_digitada = $_POST['senha'] ?? '';
if (empty($senha_digitada)){
    echo json_encode(["mensagem" => "Por favor, insira um senha."]);
    exit();
}



try{
    $conexao = new PDO($dsn, $usuario, $senha);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se os dados foram enviados via post
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Criptografar a nova senha
        $senha_hashed= password_hash($senha_digitada,PASSWORD_DEFAULT);
       
        //Verifica se o email já está registrado, apenas se o email for válido
        if($email !== ''){
            error_log("Verificando email: $email");
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $existeEmail = $stmt->fetchColumn();
    
            if ($existeEmail > 0) {
                echo json_encode(["success" => false, "mensagem" => "Este email já está registrado!"]);
                exit();
            }

        }

       //Verifica se o telefone já está registrado, apenas se o telefone for válido
        if($telefone !== ''){
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuarios WHERE telefone = :telefone");
            $stmt->bindParam(':telefone', $telefone);
            $stmt->execute();
            $existeTelefone = $stmt->fetchColumn();
    
            if ($existeTelefone > 0) {
                echo json_encode(["success" => false, "mensagem" => "Este telefone já está registrado!"]);
                exit();
            }

        }
       
        // Preparar a consulta SQL para registrar o novo usuário
        $stmt = $conexao->prepare("INSERT INTO usuarios (email, senha, telefone, data_criacao) VALUES (:email, :senha, :telefone, NOW())");

        // Bind dos parâmetros (se email ou telefone for vazio, será inserida uma string vazia)
        $stmt->bindValue(':email', $email !== '' ? $email : NULL, PDO::PARAM_STR);
        $stmt->bindValue(':senha', $senha_hashed);
        $stmt->bindValue(':telefone', $telefone !== '' ? $telefone : '');

        
        $stmt->execute();
        echo json_encode(["success" => true, "mensagem" => "Registrado com sucesso. Por favor, espere!", "redirect" => true]);
       
        exit();

 }
}catch(PDOException $e){
    echo json_encode(["mensagem" => "Erro na conexão: " . $e->getMessage()]);
}


?>





