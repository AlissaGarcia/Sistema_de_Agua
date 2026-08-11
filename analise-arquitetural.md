# Análise arquitetural do projeto Sistema de Água

## 1. Estrutura analisada

Foram verificadas as principais pastas do projeto:

- app/Models
- app/Http/Controllers
- app/Http/Requests
- app/Services
- database
- routes
- resources

A arquitetura geral segue o padrão MVC (Model-View-Controller), com uso de Form Requests para validação e Services para regras de negócio e cálculos.

## 2. Elementos principais encontrados

| Elemento | Arquivo encontrado | Responsabilidade |
|---|---|---|
| Model | app/Models/User.php | Representa o usuário do sistema, autenticação, papéis e dados básicos como gestor e leiturista. |
| Model | app/Models/Consumidor.php | Representa os consumidores cadastrados, com relacionamento com leituras e faturas e métodos de cálculo do consumo. |
| Model | app/Models/Configuracao.php | Armazena a configuração das tarifas da água, com campos como taxa fixa, limite e valor excedente. |
| Controller | app/Http/Controllers/AuthController.php | Realiza login e logout, além de redirecionar usuários conforme seu papel. |
| Controller | app/Http/Controllers/ConfiguracaoController.php | Gerencia a tela e atualização da configuração de tarifas do sistema. |
| Controller | app/Http/Controllers/DashboardController.php | Exibe o painel principal para gestão do sistema. |
| Form Request | app/Http/Requests/LoginRequest.php | Valida os dados de login, como e-mail obrigatório e senha com mínimo de 6 caracteres. |
| Form Request | app/Http/Requests/StoreConfiguracaoRequest.php | Valida os dados de configuração de tarifas quando enviados pelo formulário. |
| Service | app/Services/TarifaService.php | Centraliza a regra de negócio para cálculo de tarifas, validação de configurações e atualização da base. |

## 3. Papel das pastas do projeto

### app/Models
A pasta Models concentra as entidades do domínio, como:

- User
- Consumidor
- Configuracao
- Leitura
- Fatura
- AuditLog

Esses arquivos representam as tabelas do banco e encapsulam relações e regras relacionadas ao negócio.

### app/Http/Controllers
A pasta Controllers concentra a lógica de recebimento de requisições HTTP e a interação com Models e Services.

Exemplos principais:

- AuthController: autenticação
- ConfiguracaoController: configuração de tarifas
- DashboardController: painel administrativo
- ConsumidorController: gestão de consumidores
- FaturaController: gestão de faturas e cobrança
- LeituraController: registro e listagem de leituras
- LeituristaController: área específica do papel leiturista

### app/Http/Requests
Os Form Requests validam a entrada do usuário antes de executar ações no controller.

Exemplo principal:

- LoginRequest
- StoreConfiguracaoRequest
- StoreConsumidorRequest
- StoreFaturaRequest
- StoreLeituraRequest

Essa abordagem deixa a validação separada da lógica de controller, deixando o código mais organizado e seguro.

### app/Services
A pasta Services guarda regras de negócio mais complexas que não devem ficar misturadas com os controllers.

Exemplo principal:

- TarifaService

Esse serviço é responsável por:

- obter a configuração atual de tarifas;
- validar dados,
- calcular o valor da conta por consumo;
- aplicar regras de excedente e limite de consumo.

### database
A pasta database contém as migrações e seeders do sistema, que organizam a estrutura do banco de dados e a inicialização de dados.

Arquivos relevantes:

- migrations/ create_users_table.php
- migrations/ create_consumidores_table.php
- migrations/ create_leituras_table.php
- migrations/ create_faturas_table.php
- migrations/ create_configuracoes_taxa_table.php
- seeders/ DatabaseSeeder.php

### routes
A rota principal do sistema está em routes/web.php, e define o fluxo de acesso por autenticação e perfil de usuário.

### resources
A pasta resources guarda as views Blade utilizadas pela interface do sistema, como:

- login
- dashboard
- configurações
- consumidores
- faturas
- leituras
- painel do leiturista

## 4. Principais rotas utilizadas pelo sistema

Arquivo principal: routes/web.php

| Rota | Método | Controller/Ação | Função |
|---|---|---|---|
| /login | GET | AuthController@showLoginForm | Exibe a tela de login |
| /login | POST | AuthController@login | Processa autenticação |
| / | GET | Closure | Redireciona para tela de login |
| /dashboard | GET | DashboardController@index | Painel principal do gestor |
| /leituras | GET/POST | LeituraController | Listagem e cadastro de leituras |
| /leituras/{id} | GET/DELETE | LeituraController | Visualização e remoção de leitura |
| /consumidores | GET/POST | ConsumidorController | Cadastro e listagem de consumidores |
| /consumidores/{id} | GET/PUT/PATCH/DELETE | ConsumidorController | Visualização e gerenciamento do consumidor |
| /faturas | GET | FaturaController@index | Listagem de faturas |
| /faturas/{id} | GET/DELETE | FaturaController | Visualização e exclusão de fatura |
| /faturas/{id}/marcar-pago | PATCH | FaturaController@marcarPago | Marca uma fatura como paga |
| /faturas/{id}/pdf | GET | FaturaController@gerarPDF | Gera PDF da fatura |
| /faturas/{id}/email | POST | FaturaController@enviarEmail | Envia fatura por e-mail |
| /configuracao | GET | ConfiguracaoController@index | Exibe configuração de tarifas |
| /configuracao | PATCH | ConfiguracaoController@update | Atualiza configuração de tarifas |
| /leiturista | GET | LeituristaController@index | Painel do leiturista |
| /logout | POST | AuthController@logout | Realiza logout |

## 5. Observações finais

O projeto está organizado em um padrão MVC claro, com separação de responsabilidades:

- Models: representam os dados e regras do domínio.
- Controllers: tratam requisições e orquestram ações.
- Requests: validam entradas do usuário.
- Services: centralizam regras de negócio complexas.
- Routes: definem o fluxo de navegação e permissões por perfil.

Essa organização torna o sistema mais manutenível, testável e escalável.
