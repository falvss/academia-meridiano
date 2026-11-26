# Academia Meridiano de Estudos Temporais

**Projeto Final · My Crazy University**  
**Autor: Fernando Alves de Souza**  
**Faculdade Senac · Análise e Desenvolvimento de Sistemas**

No Observatório da Torre, em Curitiba, cada turma investiga uma época sob a mesma regra: observar sem interferir. O Curso de Formação de Agentes do Tempo começa com um ano comum e oferece quatro trilhas de pesquisa: Galileia e Judeia, Brasil Colônia, Segunda Guerra Mundial e Curitiba em 2125.

## Do vestibular à formação

O candidato escolhe uma missão e responde a oito questões de conhecimentos gerais e específicos. O sistema corrige a prova, guarda as respostas no MySQL e emite um comprovante com protocolo. A convocação para o teste de aptidão física exige cinco acertos no total e pelo menos dois em cada bloco. A Secretaria registra as marcas do TAF e acompanha a situação do candidato.

A entrada na Secretaria é pública e abre uma sessão com um clique. O painel apresenta os dados acadêmicos, enquanto os cadastros de turmas e cadetes permitem inclusão, consulta, edição e exclusão. A busca por parte do nome, o filtro por missão e a ordenação permanecem ativos durante a paginação. Uma turma com cadetes vinculados tem sua exclusão bloqueada.

## Implementação

A aplicação utiliza PHP, PDO, MySQL, HTML, CSS e JavaScript. A pasta pública é `pub/`. O banco `academia_meridiano` é criado por [banco.sql](sql/banco.sql), que inclui as quatro tabelas, oito turmas e cinquenta cadetes demonstrativos. A conexão utiliza `DB_DSN`, `DB_USER` e `DB_PASSWORD`, com configuração local em [config.example.php](app/config.example.php). A execução local corresponde a `php -S 127.0.0.1:8000 -t pub`, a partir da raiz do projeto, com PHP 8.1 ou superior, PDO MySQL e o banco inicializado.

## Documentação e avaliação

| Material | Conteúdo |
|---|---|
| [Documentação sistêmica](docs/SISTEMA.md) | Objetivo, organização dos arquivos, persistência e fluxo da aplicação |
| [Modelagem, regras e auditoria](docs/PARTE_2.md) | Dicionário de dados, cinco regras de negócio, seis testes e análise crítica |
| [Fluxograma](docs/fluxo.svg) e [modelo lógico](docs/modelo.svg) | Caminho dos dados, tabelas e relacionamentos |
| [Resultados dos testes relacionais](docs/evidencias/sql-local.json) | Valores esperados e obtidos nos seis testes executados em SQLite |
| [Auditoria PHP e MySQL](.github/workflows/auditoria.yml) | Rotina de integração dos formulários com um banco MySQL descartável |
| [Edital do vestibular](pub/edital.pdf) | Normas do processo seletivo e critérios de habilitação |

[Portal da Academia](https://portal-production-a487837b.up.railway.app/) · [Repositório do projeto](https://github.com/falvss/academia-meridiano)
