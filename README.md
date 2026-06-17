# Sistema de Controle de Consumo de Água

Uma associação comunitária gerencia o abastecimento de água de um pequeno bairro. Todo mês, um leiturista passa de casa em casa anotando o consumo de cada medidor e hoje envia essas informações pelo WhatsApp. Este sistema web foi desenvolvido para substituir esse processo manual, permitindo cadastrar consumidores, registrar leituras mensais, gerar faturas automaticamente e enviar notificações via WhatsApp.

## Dupla
Alissa Garcia Moreira & Tainá Rodrigues dos Santos

## Tecnologias Usadas

- PHP 8.0
- Laravel 8
- MySQL
- Composer

## Como instalar e rodar o projeto localmente
### Pré-requisitos
- PHP >= 8.0
- Composer
- MySQL

### Passo a passo
bash
# 1. Clone o repositório
git clone https://github.com/AlissaGarcia/Sistema_de_Agua.git

# 2. Entre na pasta do projeto
cd Sistema_de_Agua

# 3. Instale as dependências
composer install --ignore-platform-reqs

# 4. Copie o arquivo de ambiente
cp .env.example .env

# 5. Gere a chave da aplicação
php artisan key:generate

# 6. Configure o banco de dados no .env (veja abaixo)

# 7. Execute as migrations
php artisan migrate

# 8. Inicie o servidor
php artisan serve


## Como configurar o .env e rodar as migrations

Abra o arquivo .env e edite as seguintes linhas com os dados do seu banco MySQL:

env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=consumo_agua
DB_USERNAME=root
DB_PASSWORD=


Depois execute:

bash
php artisan migrate


## Funcionalidades

- Cadastro, listagem e edição de consumidores
- Registro de leitura mensal com cálculo automático de consumo
- Geração de fatura com valor calculado conforme regra de negócio
- Listagem de faturas com opção de marcar como paga
- Configuração da taxa fixa e valor excedente pelo gestor
- Botão de envio de fatura via WhatsApp


## Regra de Cobrança

| Consumo mensal | Cobrança |
|---|---|
| Até 10.000 litros (10 m³) | Taxa fixa (padrão: R$ 25,00) |
| Acima de 10.000 litros | Taxa fixa + R$ 2,00 por cada 1.000 L excedentes |

*Exemplo:* consumo de 15.000 L → R$ 25,00 + R$ 10,00 = R$ 35,00

## Usuário Padrão

Não há sistema de login nesta versão.