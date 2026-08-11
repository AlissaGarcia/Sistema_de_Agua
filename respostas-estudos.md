# Respostas de estudo e registro de aprendizagem

## Parte 2 — Validação com Form Request

### 1) Qual é o fluxo de registro de leitura no projeto?
O fluxo principal de registro de leitura envolve:

- a rota de leitura em `routes/web.php`;
- o controller responsável, geralmente `LeituraController`;
- a model `Leitura`;
- e, quando aplicada, a validação via Form Request.

Em um fluxo típico, a requisição HTTP chega ao controller, que recebe os dados enviados pelo formulário. Antes de persistir no banco, a aplicação deve validar os campos para garantir que o dado recebido está correto e seguro.

### 2) Como os dados estão sendo recebidos atualmente?
Os dados de leitura chegam via formulário ou requisição web, normalmente como campos do tipo `POST` ou `PATCH`/`PUT`, enviados para uma rota do controller.

No contexto do sistema, o dado mais importante é a leitura do medidor, normalmente representado por:

- `consumidor_id`
- `leitura_anterior`
- `leitura_atual`

Esses valores podem ser recebidos em arrays do request e passados para a lógica de criação/atualização da leitura.

### 3) Por que usar Form Request?
O Form Request separa a validação da lógica de negócio e do controller. Isso deixa o código mais organizado, reutilizável e fácil de manter.

Ele permite:

- validar entradas antes de salvar;
- centralizar regras de formulário em um arquivo específico;
- reduzir a complexidade do controller;
- melhorar a legibilidade e a manutenção do código.

### 4) Qual é a validação mínima exigida para leitura?
Para o registro de leitura, a validação mínima sugerida é:

- `consumidor_id`: obrigatório, inteiro e existente na tabela de consumidores;
- `leitura_anterior`: obrigatória, numérica e maior ou igual a zero;
- `leitura_atual`: obrigatória, numérica e maior ou igual a zero.

A validação deve tratar os dados individualmente e verificar o formato numérico, evitando valores vazios, letras, texto e valores negativos.

### 5) O que significa “validação não é regra de negócio”?
Validação verifica se os dados recebidos têm formato e integridade esperados. Já a regra de negócio decide se a operação é permitida conforme a lógica do sistema.

Exemplo:

- Validação: garantir que `leitura_anterior` e `leitura_atual` sejam numéricos.
- Regra de negócio: decidir se a leitura atual pode ser menor que a anterior, se esse cenário é permitido ou não conforme a lógica do domínio.

Na atividade, a regra é explícita: um valor menor que o anterior é válido quando ambos forem numéricos e maiores ou iguais a zero. Ou seja, a validação não deve bloquear automaticamente `4500 < 5000`.

### 6) Como tratar a condição `4500 < 5000`?
Mesmo que:

- `leitura_anterior = 5000`
- `leitura_atual = 4500`

os valores sejam numéricos e maiores que zero, a validação individual não deve rejeitá-los. O problema real é a comparação entre os dois, que pode indicar uma ordem inconsistência de leitura, mas não é uma falha de tipo ou formato.

Portanto, a validação deve aceitar esse caso como válido no nível do request, e a regra de negócio, se existir, poderá decidir como tratar esse cenário em outra etapa.

### 7) Qual é a diferença entre validar e aplicar regra de negócio?
A validação responde a pergunta:

- “O valor informado está no formato esperado?”

A regra de negócio responde a pergunta:

- “Essa operação faz sentido para o sistema?”

Exemplo:

- `leitura_atual` não pode ser texto → validação.
- `leitura_atual` menor do que `leitura_anterior` pode ser aceito ou rejeitado conforme a regra do negócio → regra de negócio.

### 8) Como o Form Request deve ser implementado em Laravel?
Em Laravel, o Form Request normalmente fica em:

- `app/Http/Requests/LeituraRequest.php`

Ele deve conter:

- `authorize()` para decidir se o usuário pode enviar a requisição;
- `rules()` com as validações;
- e, opcionalmente, métodos auxiliares para retorno dos dados validados.

A validação mínima pode ser algo como:

- `consumidor_id` => `required|integer|exists:consumidores,id`
- `leitura_anterior` => `required|numeric|min:0`
- `leitura_atual` => `required|numeric|min:0`

Essas regras garantem que os dados tenham formato correto e que o consumidor exista no banco.

### 9) O que o estudante deve fazer na atividade?
O estudante deve:

- analisar o fluxo de leitura atual;
- identificar como os dados chegam ao sistema;
- criar ou refatorar um Form Request para essa operação;
- aplicar as validações mínimas solicitadas;
- respeitar a regra de que a comparação entre leitura anterior e atual não deve ser tratada como erro de validação, quando os valores forem numéricos válidos.

### 10) Observação importante
A atividade pede atenção especial: não fornecer uma solução completa pronta. O objetivo é que o estudante implemente o código e entenda a distinção entre:

- validação de formato;
- validação de integridade de dados;
- regras de negócio do sistema.

### 11) Conclusão
A validação com Form Request é uma boa prática em Laravel porque melhora a organização do código e reforça a integridade dos dados recebidos. No caso de leitura, é fundamental validar `consumidor_id`, `leitura_anterior` e `leitura_atual`, mas sem bloquear automaticamente um cenário em que a leitura atual seja menor que a anterior, desde que ambos sejam numéricos e válidos individualmente.
