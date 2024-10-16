<?php
session_start();

// Definir o cabeçalho para JSON
header('Content-Type: application/json');

$dsn = 'mysql:host=localhost;dbname=netflix_db';
$usuario = 'root';
$senha = '';


try{
    $conexao = new PDO($dsn, $usuario, $senha);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se os dados foram enviados via post
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Pega o valor do campo 'contato', que pode ser um email ou telefone
        $contato= trim($_POST['contato']) ?? '';
        $senha_digitada = trim($_POST['senha']) ?? '';

        // Inicializar variáveis
        $mensagem = '';

        // Verificar se o contato é um email
        if(filter_var($contato, FILTER_VALIDATE_EMAIL)){
            $query = "SELECT * FROM usuarios WHERE email = :contato";
            
        }
        
        //Verificar se o contato é um telefone válido (10 a 15 dígitos)
        else if(preg_match('/^\d{10,15}$/', $contato)) {
            $query = "SELECT * FROM usuarios WHERE telefone = :contato";
            
        }else{
            $mensagem = "Por favor, insira um email ou um telefone válido.";
            
        }

        // Preparar a consulta para buscar o usuário com base no contato
        if(empty($mensagem )){
           
            $stmt = $conexao->prepare($query);
            $stmt->bindParam(':contato', $contato);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar se o usuário foi encontrado
            if ($usuario){
                // Verificar se a senha está correta
                if(password_verify($senha_digitada, $usuario['senha'])){
                    // Definir a variável de sessão para indicar que o usuário está logado
                    $_SESSION['usuario_logado'] = true;

                   // Se o login for bem-sucedido, retorna um JSON com sucesso
                   echo json_encode(['success' => true, 'redirect' => true]); 
                   exit(); // Finaliza o script após a resposta
                  
                }else{
                    $mensagem = "Senha incorreta. Tente novamente!";
                }
            }else{
                $mensagem = "Usuário não encontrado!";
            }
        }

        //Retorna a mensagem em formato JSON se houver
        if(!empty($mensagem)){
            echo json_encode(['mensagem' => $mensagem]);
            exit(); // Finaliza o script após a resposta
          
        }

       
       
    }
}

catch(PDOException $e){
    echo json_encode (['mensagem' => 'Erro na conexão: ' .  $e->getMessage()]);
    exit(); // Finaliza o script após a resposta
}


?>





