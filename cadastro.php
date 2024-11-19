<?php
session_start(); // Inicia a sessão
include 'db_connect.php';  // Conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['firstName'];
    $sobrenome = $_POST['lastName'];
    $email = $_POST['email'];
    $senha = $_POST['password'];
    $c_senha = $_POST['c_password'];

    // Validar se as senhas coincidem
    if ($senha !== $c_senha) {
        echo "As senhas não coincidem!";
        exit;
    }

    // Verificar se o e-mail já está cadastrado
    $email_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $email_check->bind_param("s", $email);
    $email_check->execute();
    $email_check->store_result();

    if ($email_check->num_rows > 0) {
        echo "Este e-mail já está cadastrado!";
        exit;
    }

    // Hash da senha antes de armazenar
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir os dados
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, sobrenome, email, senha) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $sobrenome, $email, $senha_hash);

    if ($stmt->execute()) {
        // Obtém o ID do usuário recém-cadastrado e o salva na sessão
        $_SESSION['usuario_id'] = $stmt->insert_id;

        echo "Cadastro realizado com sucesso!";
        header("Location: plano.html"); // Redireciona para a página de escolha de plano
        exit;
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    // Fechar a conexão
    $stmt->close();
    $email_check->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
</head>
<body>
    <!-- source: https://gist.github.com/nraloux/bce10c4148380061781b928cdab9b193 -->
<!-- I have added support for dark mode and improved UI -->
    <style>
        .work-sans {
            font-family: 'Work Sans', sans-serif;
        }
                
        #menu-toggle:checked + #menu {
            display: block;
        }
        
        .hover\:grow {
            transition: all 0.3s;
            transform: scale(1);
        }
        
        .hover\:grow:hover {
            transform: scale(1.02);
        }
        
        .carousel-open:checked + .carousel-item {
            position: static;
            opacity: 100;
        }
        
        .carousel-item {
            -webkit-transition: opacity 0.6s ease-out;
            transition: opacity 0.6s ease-out;
        }
        
        #carousel-1:checked ~ .control-1,
        #carousel-2:checked ~ .control-2,
        #carousel-3:checked ~ .control-3 {
            display: block;
        }
        
        .carousel-indicators {
            list-style: none;
            margin: 0;
            padding: 0;
            position: absolute;
            bottom: 2%;
            left: 0;
            right: 0;
            text-align: center;
            z-index: 10;
        }
        
        #carousel-1:checked ~ .control-1 ~ .carousel-indicators li:nth-child(1) .carousel-bullet,
        #carousel-2:checked ~ .control-2 ~ .carousel-indicators li:nth-child(2) .carousel-bullet,
        #carousel-3:checked ~ .control-3 ~ .carousel-indicators li:nth-child(3) .carousel-bullet {
            color: #000;
            /*Set to match the Tailwind colour you want the active one to be */
        }
    </style>

</head>


<body class="bg-black text-white">
    <nav id="header" class="w-full z-30 top-0 py-1"></nav>
        <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-6 py-3">

            <label for="menu-toggle" class="cursor-pointer md:hidden block">
                <svg class="fill-current text-white-900" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                    <title>menu</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                </svg>
            </label>
            <input class="hidden" type="checkbox" id="menu-toggle" />

            <div class="hidden md:flex md:items-center md:w-auto w-full order-3 md:order-1" id="menu">
                <nav>
                    <ul class="md:flex items-center justify-between text-base text-white pt-4 md:pt-0">
                        <li><a class="inline-block no-underline hover:text-blue-300 py-2 px-4" href="index.html">Início</a></li>
                        <span class="text-white">|</span>
                        <li><a class="inline-block no-underline hover:text-blue-300 py-2 px-4" href="">Cursos</a></li>
                        <span class="text-white">|</span>
                        <li><a class="inline-block no-underline hover:text-blue-300 py-2 px-4" href="faleconosco.html">Fale Conosco</a></li>
                      </ul>
                      
                </nav>
            </div>

            <div class="order-1 md:order-2 ml-auto">
                <a class="flex items-center tracking-wide no-underline hover:no-underline font-bold text-white-800 text-xl" href="#">
                    <img src="img/Imagem do WhatsApp de 2024-11-05 à(s) 20.10.40_39bb585d.jpg" width="80" height="16" alt="logo">
                    <span class="ml-2" style="font-family: 'Exo', sans-serif;">TECH CONNECT</span>
                </a>
            </div>
            <div class="order-2 md:order-3 flex items-center" id="nav-content">

                <a class="inline-block no-underline hover:text-black hidden" href="#">
                    <svg class="fill-current hover:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <circle fill="none" cx="12" cy="7" r="3" />
                        <path d="M12 2C9.243 2 7 4.243 7 7s2.243 5 5 5 5-2.243 5-5S14.757 2 12 2zM12 10c-1.654 0-3-1.346-3-3s1.346-3 3-3 3 1.346 3 3S13.654 10 12 10zM21 21v-1c0-3.859-3.141-7-7-7h-4c-3.86 0-7 3.141-7 7v1h2v-1c0-2.757 2.243-5 5-5h4c2.757 0 5 2.243 5 5v1H21z" />
                    </svg>
                </a>

                <a class="pl-3 inline-block no-underline hover:text-black hidden" href="#">
                  <svg class="fill-current hover:text-black" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                      <path d="M21,7H7.462L5.91,3.586C5.748,3.229,5.392,3,5,3H2v2h2.356L9.09,15.414C9.252,15.771,9.608,16,10,16h8 c0.4,0,0.762-0.238,0.919-0.606l3-7c0.133-0.309,0.101-0.663-0.084-0.944C21.649,7.169,21.336,7,21,7z M17.341,14h-6.697L8.371,9 h11.112L17.341,14z" />
                      <circle cx="10.5" cy="18.5" r="1.5" />
                      <circle cx="17.5" cy="18.5" r="1.5" />
                  </svg>
              </a>
              

            </div>
        </div>
    </nav>
<div class="h-full bg-black dark:bg-gray-900">
	<!-- Container -->
	<div class="mx-auto">
		<div class="flex justify-center px-6 py-12">
			<!-- Row -->
			<div class="w-full xl:w-3/4 lg:w-11/12 flex">
				<!-- Col -->
				<div class="w-full h-auto bg-gray-400 dark:bg-gray-800 hidden lg:block lg:w-5/12 bg-cover rounded-l-lg"
					style="background-image: url('img/cadastro.jpg')"></div>
				<!-- Col -->
				<div class="w-full lg:w-7/12 bg-white dark:bg-gray-700 p-5 rounded-lg lg:rounded-l-none">
					<h3 class="py-4 text-2xl text-center text-gray-800 dark:text-white">Crie Sua Conta!</h3>
                    <form class="px-8 pt-6 pb-8 mb-4 bg-white dark:bg-gray-800 rounded" action="cadastro.php" method="POST">
    <div class="mb-4 md:flex md:justify-between">
        <div class="mb-4 md:mr-2 md:mb-0">
            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white" for="firstName">
                Primeiro Nome
            </label>
            <input
                class="w-full px-3 py-2 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                id="firstName" name="firstName" type="text" placeholder="First Name" required />
        </div>
        <div class="md:ml-2">
            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white" for="lastName">
                Sobrenome
            </label>
            <input
                class="w-full px-3 py-2 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                id="lastName" name="lastName" type="text" placeholder="Last Name" required />
        </div>
    </div>
    <div class="mb-4">
        <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white" for="email">
            Email
        </label>
        <input
            class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
            id="email" name="email" type="email" placeholder="Email" required />
    </div>
    <div class="mb-4 md:flex md:justify-between">
        <div class="mb-4 md:mr-2 md:mb-0">
            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white" for="password">
                Senha
            </label>
            <input
                class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                id="password" name="password" type="password" placeholder="******************" required />
            <p class="text-xs italic text-red-500">Please choose a password.</p>
        </div>
        <div class="md:ml-2">
            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white" for="c_password">
                Confirme Sua Senha
            </label>
            <input
                class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                id="c_password" name="c_password" type="password" placeholder="******************" required />
        </div>
    </div>
    <div class="mb-6 text-center">
        <button
            class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-700 dark:bg-blue-700 dark:text-white dark:hover:bg-blue-900 focus:outline-none focus:shadow-outline"
            type="submit">
            Registre Sua Conta
        </button>
    </div>
    <div class="text-center">
        <a class="inline-block text-sm text-blue-500 dark:text-blue-500 align-baseline hover:text-blue-800"
            href="login.php">
            Já tem uma conta? Login
        </a>
    </div>
</form>

				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>

