"""Auditoria de comandos SQL em SQLite. Não substitui a integração HTTP/PDO/MySQL."""
import json, re, sqlite3
from pathlib import Path
root=Path(__file__).resolve().parents[1]
sql=(root/'sql/banco.sql').read_text()
conn=sqlite3.connect(':memory:')
conn.execute('PRAGMA foreign_keys=ON')
# Traduz o DDL de instalação mantendo NOT NULL, UNIQUE, FK, defaults e registros iniciais.
ddl=sql[sql.index('CREATE TABLE'):]
ddl=re.sub(r'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY','INTEGER PRIMARY KEY AUTOINCREMENT',ddl)
ddl=re.sub(r"ENUM\([^)]+\)","TEXT",ddl)
ddl=ddl.replace(' ENGINE=InnoDB','').replace('UNIQUE KEY email_edicao(','UNIQUE(')
conn.executescript(ddl)
src=(root/'pub/registro.php').read_text()
def source_query(prefix):
    return next(t for t in re.findall("'([^'\n]*)'",src) if t.startswith(prefix))
results=[]
def test(name,action,expected,actual):
    assert expected==actual,(name,expected,actual)
    results.append(dict(teste=name,acao=action,esperado=expected,obtido=actual))
def rows(sql,args=()): return [list(r) for r in conn.execute(sql,args)]
def run(sql,args):return conn.execute(sql,args).lastrowid
q=source_query('INSERT INTO turmas')
tid=run(q,dict(nome='Aurora de auditoria',missao='comum',ano=2027))
test('T1 Cadastro de turma',q,[['Aurora de auditoria',2027]],rows('SELECT nome,ano FROM turmas WHERE id=?',(tid,)))
q=source_query('INSERT INTO cadetes')
params=dict(nome='Lia Teste',email='lia@example.test',turma_id=tid,ano_formacao=1,especialidade='Corpo Básico')
cid=run(q,params)
test('T2 Cadastro de cadete',q,[['Lia Teste','Aurora de auditoria']],rows('SELECT c.nome,t.nome FROM cadetes c JOIN turmas t ON c.turma_id=t.id WHERE c.id=?',(cid,)))
q=source_query('UPDATE cadetes');run(q,dict(params,nome='Lia Revisada',id=cid))
test('T3 Edição',q,[['Lia Revisada']],rows('SELECT nome FROM cadetes WHERE id=?',(cid,)))
q="SELECT nome FROM turmas WHERE nome LIKE :q AND missao=:missao ORDER BY nome ASC,id LIMIT :lim OFFSET :off"
test('T4 Pesquisa com filtro e paginação',q,[['Primeiro ano · Aurora']],rows(q,dict(q='%Aurora%',missao='comum',lim=1,off=1)))
q=source_query('INSERT INTO cadetes')
try:run(q,dict(params,email='invalido@example.test',turma_id=999999))
except sqlite3.IntegrityError as e:err=str(e)
else:raise AssertionError('FK ausente')
test('T5 Cadastro inválido: turma inexistente',q,['FOREIGN KEY constraint failed',0],[err,rows("SELECT count(*) FROM cadetes WHERE email='invalido@example.test'")[0][0]])
q='DELETE FROM cadetes WHERE id=:id';run(q,dict(id=cid))
test('T6 Exclusão confirmada na camada SQL',q,[[0]],rows('SELECT count(*) FROM cadetes WHERE id=?',(cid,)))
log=dict(ambiente='SQLite '+sqlite3.sqlite_version,escopo='Comandos e integridade relacional em banco efêmero. Sem servidor PHP, sem PDO e sem MySQL.',resultados=results)
(root/'docs/evidencias/sql-local.json').write_text(json.dumps(log,indent=2,ensure_ascii=False)+'\n')
print(len(results),'testes SQL concluídos')
