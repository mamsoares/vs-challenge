<p align="center">
    <img src="https://i.imgur.com/2LUR2yy.png">
</p>

# Desafio Veus

### Desenvolver umam API com as seguintes funcionalidades:
1. Requisitos mandatório PHP > 7.0;
2. Desenvolver um CRUD de Produtos;
3. Características dos produtos: nome, marca, preço e quantidade em estoque;
4. A API também deve suportar pagination, versioning e sorting
5. Implementar um serviço de buscas desses produtos;
6. A API deve requerer autenticação e permitir search query através do método GET e suportar filtros opcionais nos campos do produto.
   Por exemplo: Um cliente deve conseguir buscar todas as seringas da marca BUNZL fazendo a seguinte requisição:
   https://example.com/api/v1/products?q=seringa&filter=brand:BUNZL
7. Sinta-se livre para usar qualquer library ou framework da sua preferência mas a regra de negócio deve estar o mais desaclopada possível deles.

### Minha experiência 
Com esse desafio tive a oportunidade de praticar e aprender um novo framework.

## Como preparar o ambiente do projeto
### Pré requisitos
- Laravel Framework 7.30.1 [link da documentação](https://laravel.com/docs/7.x)
- Composer [link da documentação](https://getcomposer.org/)

### Passos para instalação e configuração
1. Clonar o repositório com o comando:
   ```sh
   $ git clone https://github.com/mamsoares/vs-challenge.git
     ```
2. Ir para o diretório que foi clonado no item anterior:
   ```sh
   $ cd nome-do-diretorio
   ```
3. Executar o comando a seguir para instalção das dependências do projetp:
   ```sh
   $ composer install 
   ```
4. A aplicação precisa de uma chave que pode ser gerada com o seguinte comando:
   ```sh
   $ php artisan key:generate
   ```
5. Também será necassário gerar uma chave para o sistema de Autentição com JWT:
   ```sh
   $ php artisan jwt:secret
   ```
6. Agora vamos seguir os seguintes passos para criar o banco de dados:
   - Escolher o gerenciador de banco de dados relacional;
   - Criar o banco de dados com o nome de sua preferencia;
   - Anotar as seguintes configurações para incluir no arquivo de .env na raiz do projeto:
      - host
      - porta 
      - nome do banco
      - usuário
      - senha
7. Feita a configuração acima, salve e execute os seguintes passos:
   ```sh
   $ php artidan migrate:fresh
   ```
   Caso deseje fazer uma carga com dados FAKE para testar os endpoints, você pode executar o seguinte comando:
   ```sh
   $ php artisan db:seed
   ```
## Configurando o ambiente para Teste da API

Para começar os testes da API é necessário que você utilize a ferramenta [POSTMAN](https://www.postman.com/) ou alguma similar.

Com a ferramenta instalada, você pode fazer a importação do seguinte arquivo que está na raiz do projeto, e que vai facilitar muito o nosso trabalho, pois ele contém todas as rotas da nossa API, tanto de Autenticação como as de Produtos:

- Desafio_Veus.postman_collection.json

Para que tudo possa funcionar você pode executar o servidor do próprio Laravel, com o seguinte comando e em seguida inicie os seus testes:
  ```sh
  $ php artisan serve
  ```
Se você importou o arquivo que mencionamos no passo acima e fez a importação com os dados FAKE, vai encontrar algumas facilidades:

1. Faça o Login no sistema com as seguintes credenciais:
   - email: admin@gmail.com
   - password: password
2. Copie o token para o Header Authorization e todas as rotas serão liberadas;
3. Em seguida você pode se divertir navegando na API;

### Rota de Busca de Produtos

Tomei a liberdade de felxibilizar o pedido que foi feito no projeto e subsititui a chamada "q" implementando melhorias no "filter". Como  assim? 
  - O parâmetro "q" fi substituido por "fields˜, ou seja vai funcionar como uma resposta GraphQL, trazendo só os campos que desejamos;
  
  - Já o parâmetro "filter" recebu um super poder que foram os "operadores", isso mesmo, agora você pode combinar o nome do campo com um operador (=, >, <, <>, like) e incluir o termo da busca, além de combinar outros campos separando por ";", isso não é fantástico: veja um exemplo:
     ```sh
     $ http://loclahost:8000?filter=name:like:%seringa%;price:>:150.00
     ```
  - A busca também pode ser feita sem parâmetros e sua única limitação é número default (10) de registros por página, número este que pode ser alterado antes de ir para produção ou informado um novo valor na variável "perpage" a cada chamada do endpoint;
  
  - Temos os seguintes parâmetros:
    - orderby=nome-do-campo:DESC|ASC
    - fields=nome-do-campo-1, nome-do-campo-2,...
    - filter=nome-do-campo:operador:termo-da-busca;nome-do-campo:operador:termo-da-busca...
    - perpage: número de registros por página;
    - page: número de págian específico;
   
## PHPUnit
Execute o seguinte comando para rodar os testes no terminal:
   ```sh
   $ ./vendor/bin/phpunit
   ```

## Resultado dos testes (SEM AUTENTICAÇÃO)
```sh
   ❯ ./vendor/bin/phpunit tests/Feature/ProductTest.php
   PHPUnit 9.5.0 by Sebastian Bergmann and contributors.

   .........                                                           9 / 9 (100%)

   Time: 00:00.424, Memory: 30.00 MB

   OK (9 tests, 24 assertions)

```

Obs: - Deixei um explicação dentro do arquivo para poder rodar e verifica o resultado.

  - Estou enfrentando problemas para a fazer a autenticação funcionar nos testes; 

  - O único ponto que ainda não consegui resolver foi a questão da autentição do JWTAuth para a execução dos testes, sempre
    recebo um retorno de token vazio. Parece que perde o usuário no momento da geração do token;

  ** Ainda não desisti de implementar, mas vou precisar fazer uma pesquisa mais detalhada. **

## Aprendizado / Resumo
Como mencionei antes, foi uma grande oportunidade para aplicar e testar os meus conhecimentos neste framework simples, rápido e eficiênte para criação de projetos.

Me dirveti muito, além de me proporcionar mais um aprendizado e ver que a comunidade do Laravel também é muito ativa, pois todos os erros que encontrei ao longo do desenvolvimento com uma simples busca consegui sanar o problema, exceto os testes com o (tymon\jwt-auth) como mencionei acima.

## Próximos passos / TODO
  - Melhorar os testes;
  - Criar o frontend do sistema em VUEjs;
  - Incluir validações nas API's para poder dormir tranquilo;
  - Refatorar o que for necessário;
  - Publicar em produção, mas um desafio;
