# Academia Meridiano de Estudos Temporais

Portal do Curso de Formação de Agentes do Tempo, sediado no Observatório da Torre, em Curitiba. O ingresso ocorre por vestibular online. Após o ano comum, os cadetes seguem para turmas de missão. O visitante pode ler o memorial de Elias Valença, conhecer a formação e consultar as quatro missões.

**Prévia:** extraia o ZIP e dê dois cliques em ABRIR.html. Para inscrever candidatos e operar a secretaria, execute o portal completo.

## Integrantes
Fernando Alves de Souza. A composição final do grupo está pendente de confirmação.

## Instalação com XAMPP no Windows
1. Use PHP 8.1 ou superior com pdo_mysql e MySQL ou MariaDB. Inicie o MySQL no painel XAMPP. Se usar phpMyAdmin, inicie também o Apache.
2. Pelo phpMyAdmin, importe sql/banco.sql em uma instalação nova. O script cria academia_meridiano, três tabelas e oito turmas. Esse banco é independente da versão anterior do portal.
3. Copie app/config.example.php para app/config.php. Informe host, porta, usuário e senha do banco. O exemplo está configurado para o XAMPP local com root e senha vazia.
4. Abra um terminal na pasta que contém este README e execute o gerador de senha administrativa. Copie o hash para admin_hash em app/config.php, entre aspas simples.

       C:\xampp\php\php.exe tools/gerar-senha.php

5. Na mesma pasta execute o servidor.

       C:\xampp\php\php.exe -S 127.0.0.1:8000 -t pub

6. Abra http://127.0.0.1:8000. No macOS ou Linux, substitua o caminho do executável por php. Com Apache, a raiz pública deve apontar para pub/.

## Apresentação
Na página inicial, observe o relógio. Selecione uma missão no campo ao lado da corrida e complete a inscrição. Guarde o protocolo. Abra o edital, o memorial e uma página de missão. Em Secretaria, crie uma turma, cadastre um cadete e edite sua atribuição. Use a consulta com pesquisa parcial, filtro, ordem e paginação. Para testar exclusão, exclua primeiro o cadete e depois a turma vazia.

## Tecnologias e entrega
PHP, PDO, MySQL, HTML, CSS, JavaScript e PDF. Consulte docs/SISTEMA.md. Para disponibilizar no GitHub, publique o conteúdo desta pasta no repositório do grupo. As credenciais locais de app/config.php ficam fora do controle de versão.

## Atualização do portal anterior
Caso já utilize a versão do Edital 01/2027, guarde uma cópia do banco e execute sql/atualizar_v1.sql e sql/atualizar_v2.sql, nessa ordem, uma vez. Preserve seu app/config.php local. A migração mantém as inscrições anteriores na edição 1 e cria os campos do TAF e do cadete. Para uma instalação nova, use somente sql/banco.sql.
 
## Edital 02/2027
A prova vale oito pontos, quatro por bloco. Cinco no total e pelo menos dois em cada bloco convocam para o TAF. Em Secretaria → Avaliações do TAF, informe as marcas, a data e o avaliador. A ficha de cada cadete inclui ano de formação e especialidade. No primeiro ano use Corpo Básico.
 
O filme de Elias está em A Academia → A fotografia torta. Abra ABRIR.html para assistir offline. O edital também fica disponível em pub/edital.pdf.

## Parte 2 e auditoria
Abra [Documentação da Parte 2](docs/PARTE_2.md) para consultar o dicionário, as cardinalidades, as cinco regras verificáveis e os resultados dos seis testes relacionais. [Fluxo dos formulários](docs/fluxo.svg) e [modelo lógico](docs/modelo.svg) também abrem offline.

**Instalação nova:** importe apenas sql/banco.sql. **Atualização:** se o banco já possui edicao e campos do TAF, aplique sql/atualizar_v2.sql uma única vez para adicionar respostas. Se ainda usa o primeiro edital, aplique atualizar_v1.sql e depois atualizar_v2.sql, preservando backup e configuração. Inscrições novas armazenam as oito escolhas e sua correção. No phpMyAdmin, após enviar a prova pelo portal, confira o resultado pelo protocolo usando a consulta abaixo (substitua 1 pelo número recebido).

    SELECT id, edicao, gerais, especificos, nota, JSON_LENGTH(respostas) AS itens_gravados
    FROM inscricoes WHERE id = 1;

Em uma instalação de testes, cadastre alguém com e-mail @example.test. A prévia ABRIR.html não grava no banco. O fluxo de auditoria do GitHub faz uma inscrição por HTTP em MySQL descartável e salva a evidência. O log de testes locais com SQLite está em docs/evidencias/sql-local.json.

## Implantação contínua proposta: GitHub + Railway
1. Publique **o conteúdo** desta pasta na raiz do repositório, inclusive .github/workflows e Dockerfile. Confira os nomes do grupo antes da entrega. O workflow usa dados descartáveis. Veja o resultado em Actions → Auditoria PHP e MySQL.
2. No projeto Railway, crie um serviço MySQL com volume persistente e um serviço web a partir do repositório GitHub. O Dockerfile instala pdo_mysql e expõe somente o diretório público pelo Apache na porta 80.
3. Configure o serviço web com DB_DSN (mysql:host=HOST_PRIVADO;port=3306;dbname=NOME_DO_BANCO;charset=utf8mb4), DB_USER, DB_PASSWORD e ADMIN_HASH. Use os valores ou referências do seu serviço MySQL e o hash obtido por tools/gerar-senha.php. Não envie app/config.php nem segredos ao repositório.
4. Em um banco novo do provedor, selecione o schema indicado no DSN e importe o conteúdo de sql/banco.sql **a partir de CREATE TABLE turmas**, mantendo todas as tabelas e o INSERT das turmas. As duas instruções iniciais criam e selecionam o nome usado no XAMPP. Não repita a importação sobre uma base já instalada.
5. Gere um domínio HTTPS no serviço web, apontando para a porta 80. Para operação contínua, mantenha Serverless desativado, verifique o plano e o consumo da conta e configure backups do volume. A publicação depende das permissões nas duas contas.
6. Antes de divulgar o endereço, envie uma inscrição de teste, confirme o protocolo com a consulta SQL acima, faça novo deploy do serviço web e confirme que o registro continua no banco.

Documentação: [MySQL](https://docs.railway.com/databases/mysql) · [Deploy pelo GitHub](https://docs.railway.com/build-deploy) · [Domínio público](https://docs.railway.com/networking/public-networking) · [Serverless](https://docs.railway.com/deployments/serverless).
