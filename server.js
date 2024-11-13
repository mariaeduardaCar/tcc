const express = require('express');
const bcrypt = require('bcryptjs');
const bodyParser = require('body-parser');
const session = require('express-session');
const mysql = require('mysql2');
const path = require('path');

// Configuração do banco de dados
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'tech_connect'
});

db.connect((err) => {
  if (err) {
    console.error('Erro ao conectar com o banco de dados:', err);
    return;
  }
  console.log('Conectado ao banco de dados!');
});

const app = express();
const port = 3006;

// Configuração do middleware
app.use(express.static(__dirname));  // Serve arquivos diretamente da raiz
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Configuração do express-session
app.use(session({
  secret: 'secreta',
  resave: false,
  saveUninitialized: true,
}));

// Rota para o cadastro
app.post('/register', async (req, res) => {
  const { firstName, lastName, email, password, c_password } = req.body;

  // Validação
  if (password !== c_password) {
    return res.status(400).send('As senhas não coincidem');
  }

  // Criptografando a senha
  const hashedPassword = await bcrypt.hash(password, 10);

  // Inserindo no banco de dados
  const query = 'INSERT INTO usuarios (nome, sobrenome, email, senha) VALUES (?, ?, ?, ?)';
  db.query(query, [firstName, lastName, email, hashedPassword], (err, result) => {
    if (err) {
      return res.status(500).send('Erro ao registrar o usuário');
    }
    // Armazena o id do usuário na sessão após o cadastro
    req.session.userId = result.insertId;
    res.redirect('/plano');
  });
});

// Rota para o login
app.post('/login', (req, res) => {
  const { email, password } = req.body;

  // Verificando o email no banco de dados
  const query = 'SELECT * FROM usuarios WHERE email = ?';
  db.query(query, [email], async (err, results) => {
    if (err) {
      return res.status(500).send('Erro ao fazer login');
    }

    if (results.length === 0) {
      return res.status(400).send('Email não encontrado');
    }

    const user = results[0];

    // Comparando a senha
    const isMatch = await bcrypt.compare(password, user.senha);
    if (!isMatch) {
      return res.status(400).send('Senha incorreta');
    }

    // Armazenar o id do usuário na sessão
    req.session.userId = user.id;

    // Verificar se o usuário já pagou
    const paymentQuery = 'SELECT * FROM pagamentos WHERE usuario_id = ? AND status = "concluído"';
    db.query(paymentQuery, [user.id], (err, results) => {
      if (err) {
        return res.status(500).send('Erro ao verificar pagamento');
      }

      if (results.length > 0) {
        // Pagamento concluído, redireciona para cursos
        res.redirect('/cursos');
      } else {
        // Pagamento não concluído, redireciona para o plano
        res.redirect('/plano');
      }
    });
  });
});

// Rota para a página de plano
app.get('/plano', (req, res) => {
  if (!req.session.userId) {
    return res.redirect('/login'); // Redireciona para o login se o usuário não estiver logado
  }
  res.sendFile(__dirname + '/plano.html');  // Caminho para o arquivo plano.html
});

// Rota para servir a página de pagamento (formulário)
app.get('/pagamento', (req, res) => {
  if (!req.session.userId) {
    return res.redirect('/login'); // Redireciona para o login se o usuário não estiver logado
  }
  res.sendFile(path.join(__dirname, 'pagamento.html')); // Serve o arquivo pagamento.html
});

// Rota para processar o pagamento (POST)
app.post('/pagamento', (req, res) => {
  const { name, cc_number, cc_month, cc_year, cc_cvc, valor } = req.body;

  // Verificar se os dados foram recebidos e se o usuário está logado
  if (!req.session.userId) {
    return res.status(400).send('Você precisa estar logado para realizar o pagamento');
  }

  // Simulação de registro do pagamento no banco de dados
  const query = 'INSERT INTO pagamentos (usuario_id, valor, status, metodo_pagamento) VALUES (?, ?, ?, ?)';
  db.query(query, [req.session.userId, valor, 'concluído', 'cartão'], (err, result) => {
    if (err) {
      console.error('Erro ao processar pagamento:', err);
      return res.status(500).send('Erro no processamento do pagamento');
    }

    // Redireciona para a página de cursos após pagamento
    res.redirect('/cursos'); 
  });
});

// Servir o formulário de login
app.get('/login', (req, res) => {
  res.sendFile(__dirname + '/login.html'); // Coloque o arquivo HTML do login aqui
});

// Rota para servir a página de cursos (curso.html)
app.get('/cursos', (req, res) => {
  if (!req.session.userId) {
    return res.redirect('/login'); // Redireciona para o login se o usuário não e stiver logado
  }
  res.sendFile(path.join(__dirname, 'cursos.html')); // Serve o arquivo cursos.html
});

// Rota para a página de logout
app.get('/logout', (req, res) => {
  req.session.destroy((err) => {
    if (err) {
      return res.status(500).send('Erro ao fazer logout');
    }
    res.redirect('/login');
  });
});

// Iniciar o servidor
app.listen(port, () => {
  console.log(`Servidor rodando em http://localhost:${port}`);
});
