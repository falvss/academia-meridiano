# Documentação sistêmica

## Visão e problema
A Academia Meridiano reúne a seleção de agentes e o registro das turmas de estudo. A secretaria precisa saber quem pertence a cada turma, sua missão e seu ano letivo. A preferência informada no vestibular orienta a triagem. O cadastro administrativo do cadete ocorre após habilitação e matrícula.

## Áreas
Portal institucional com relógio atualizado a cada segundo no horário de Brasília, fundador, formação física e acadêmica e quatro itinerários de missão. As páginas são públicas. A secretaria autenticada gerencia turmas e cadetes. O ingresso corrige oito questões e emite protocolo e nota.

## Organização
| Arquivos | Responsabilidade |
|---|---|
| pub/index.php, institucional.php, formacao.php | Entrada e formação institucional |
| pub/missoes.php e quatro páginas de destino | Catálogo de missões e oficinas por época |
| pub/vestibular.php, edital.pdf, app/prova.php | Inscrição, regras e prova |
| pub/secretaria.php, dashboard.php, usuarios.php, gestao.php, registro.php | Acesso, consulta, cadastro, edição e exclusão por ID |
| app/bootstrap.php, config.example.php, catalogo.php | PDO, validações, autorização e catálogo de missões |
| app/header.php, footer.php, arquivos HTML, pub/assets | Componentes, conteúdo, fotografia, estilos e relógio |
| sql/banco.sql, tools/gerar-senha.php | Instalação de banco e geração local de hash |
| previa/, ABRIR.html | Leitura offline das páginas institucionais |

## Modelo de dados
| Tabela | Campos principais | Regra |
|---|---|---|
| admin_users | id, nome, email, senha_hash, ativo, criado | Cada conta tem e-mail único e senha armazenada como hash. |
| turmas | id, nome, missao, ano | Nome único. Uma turma pode reunir vários cadetes. |
| cadetes | id, nome, email, turma_id, ano_formacao, especialidade | E-mail único e chave estrangeira obrigatória. |
| inscricoes | id, nome, email, interesse, edicao, nota, gerais, especificos, corrida_m, percurso_s, avaliador, data_taf, criado | Uma inscrição por e-mail nesta edição. |

## Fluxos
O navegador envia formulários POST. O PHP verifica sessão e token CSRF, tamanho dos campos, domínio das opções e e-mail. Conexão PDO usa prepare, bindValue e execute, com parâmetros tipados. Os nomes de tabelas e as expressões de ordenação vêm de listas fechadas. Os campos exibidos usam htmlspecialchars.

A manutenção carrega o registro por ID e preenche o formulário. A atualização executa UPDATE; o cadastro executa INSERT. A exclusão pede confirmação explícita e só aceita POST. O banco impede excluir uma turma ocupada. A consulta usa SELECT, busca por LIKE, filtro de missão, ordenação e LIMIT/OFFSET. Os links de página preservam os filtros.

No vestibular, o servidor calcula a nota com base nas oito respostas recebidas, grava a inscrição e redireciona para o comprovante na sessão. O Edital 02/2027 exige cinco acertos e dois por bloco para convocação. A habilitação à matrícula depende das duas marcas presenciais. O hash administrativo é verificado com password_verify. A autenticação renova o ID de sessão. No servidor web, expor somente pub/.

## Identidade e pesquisa
Marfim de papel, verde-petróleo dos instrumentos e cobre dos pêndulos compõem a identidade. O monograma orbital e os retratos ilustram o memorial. As referências de leitura constam nas páginas de missão. Fotos da academia produzidas para o portal.

O filme integra o memorial e a prévia offline. taf.php restringe o lançamento das marcas à secretaria. O UPDATE condicional impede segundo lançamento concorrente. app/selecao.php calcula a situação da edição vigente e mantém a leitura de comprovantes antigos.
