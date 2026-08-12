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

## Parte 3 — Model Leitura e comportamento relacionado

### 1) Qual entidade foi escolhida?
Escolhemos a entidade `Leitura` porque ela representa o registro central do consumo de água e guarda os dados diretamente relacionados ao medidor e ao consumo calculado.

### 2) O que foi analisado no Model?
No Model `Leitura`, verificamos:

- `$fillable`: lista dos campos que podem ser preenchidos em massa;
- `$casts`: conversão de tipos para garantir consistência de dados;
- relacionamentos: `consumidor()` e `fatura()`;
- `SoftDeletes`: uso do delete lógico da entidade;
- comportamentos: cálculo de consumo e verificação de consistência da leitura.

### 3) Quais são os principais pontos do Model `Leitura`?
O model possui:

- `fillable` com `consumidor_id`, `mes`, `ano`, `leitura_anterior`, `leitura_atual`, `consumo_m3` e `consumo_litros`;
- `casts` para transformar campos numéricos e datas em tipos específicos;
- relacionamento com `Consumidor` via `belongsTo()`;
- relacionamento com `Fatura` via `hasOne()`;
- `SoftDeletes`, permitindo que a leitura seja removida logicamente sem exclusão física;
- comportamento `calcularConsumo()` para gerar o consumo em litros;
- comportamento `leituraValida()` para verificar se a leitura atual é consistente com a anterior.

### 4) Como ficou o comportamento `leituraValida()`?
Foi adicionado ao model o método:

```php
public function leituraValida(): bool
{
    return $this->leitura_atual >= $this->leitura_anterior;
}
```

Esse método verifica se a leitura atual não é menor que a leitura anterior, o que faz sentido para a regra de consistência do registro de medição.

### 5) Por que essa regra é considerada comportamento do Model `Leitura`?
Porque ela está diretamente ligada ao domínio da entidade. A leitura é um objeto que conhece seus próprios dados: leitura anterior, leitura atual e o conceito de consistência entre ambos.

A regra faz parte da responsabilidade da entidade porque:

- pertence ao próprio conceito de leitura do medidor;
- depende apenas de atributos internos da própria classe;
- representa uma regra de integridade do registro;
- mantém a lógica próxima aos dados, em vez de espalhá-la no controller ou no request.

Em outras palavras, o Model `Leitura` é o lugar natural para encapsular a verificação de coerência entre os valores do medidor, pois essa regra é intrínseca ao próprio objeto.

### 6) Qual a diferença entre essa regra e a validação do Form Request?
A validação do request trata apenas se os dados recebidos estão no formato e na estrutura esperados. Já a regra `leitura_atual >= leitura_anterior` é uma regra de consistência do domínio e, por isso, pode ser encapsulada como comportamento do model.

Essa separação é importante:

- request: valida formato, tipo e existência;
- model: encapsula regras relacionadas ao objeto de negócio.

### 7) Conclusão da parte 3
A implementação do comportamento no Model `Leitura` deixa o código mais organizado, coeso e reutilizável. A entidade passa a responder perguntas sobre si mesma, como: “a leitura atual é consistente com a leitura anterior?”

Isso respeita os princípios de encapsulamento e mantém a lógica de negócio mais próxima da estrutura de dados que a representa.

## Parte 4 — Análise do FaturaCalculatorService

### 1) Qual é a responsabilidade do service?
O `FaturaCalculatorService` é responsável por calcular o valor da fatura de acordo com a política de cobrança da associação.

Sua função é concentrar a regra de cálculo da conta em um único ponto, evitando que essa lógica fique espalhada no controller ou diretamente na view.

### 2) Qual é a regra atual implementada?
A regra implementada é:

- até 10 m³: taxa fixa de R$ 25,00;
- acima de 10 m³ até 20 m³: R$ 25,00 + R$ 2,00 por m³ excedente;
- acima de 20 m³: R$ 25,00 + R$ 2,00 por m³ entre 10 e 20 + R$ 3,00 por m³ acima de 20.

### 3) Como o cálculo foi estruturado?
O método `calcular(float $consumoM3): array` verifica em qual faixa o consumo se encontra e aplica a regra apropriada.

O retorno do método inclui:

- `taxa_fixa`
- `valor_excedente`
- `total`
- `consumo_m3`

Isso facilita a utilização do resultado em outros pontos do sistema, como a geração da fatura no banco ou a apresentação na interface.

### 4) Por que esse service é uma boa escolha?
Porque ele centraliza a regra de negócio em um serviço dedicado, deixando o controller responsável apenas por receber a requisição, buscar os dados necessários e delegar o cálculo.

Esse padrão traz vantagens como:

- melhor organização;
- facilidade de manutenção;
- reutilização da regra em vários pontos do sistema;
- menor acoplamento entre controller e regra de faturamento.

### 5) Qual é a diferença entre esse service e validação?
O `FaturaCalculatorService` não valida se o dado foi informado corretamente. Sua função é calcular o valor total de acordo com a regra de negócio.

Já a validação continua sendo responsabilidade do `Form Request`, que garante que a entrada do usuário seja coerente e correta.

### 6) Exemplos da regra
Os exemplos da regra foram implementados e testados:

- 8 m³ → total R$ 25,00
- 15 m³ → total R$ 35,00
- 25 m³ → total R$ 60,00

### 7) Conclusão
O `FaturaCalculatorService` é um exemplo claro de como a regra de negócio deve ser deslocada para um service. Isso mantém a arquitetura mais limpa, com responsabilidades bem separadas, e evita que o controller acumule regras de cálculo e de negócio.
