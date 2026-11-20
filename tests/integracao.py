"""Execute na instância local descartável criada pelo workflow. Apenas dados de teste."""
import os, re, json, subprocess, uuid
from pathlib import Path
from urllib.request import build_opener, HTTPCookieProcessor, Request
from urllib.parse import urlencode
from http.cookiejar import CookieJar

base='http://127.0.0.1:8000/'
client=build_opener(HTTPCookieProcessor(CookieJar()))
run=uuid.uuid4().hex[:10]
docs=Path('resultado-ci'); docs.mkdir(exist_ok=True)
def page(path, data=None):
    body=urlencode(data).encode() if data is not None else None
    return client.open(Request(base+path,data=body),timeout=10).read().decode()
def post(path,data):
    token=re.search(r'name="token" value="([^"]+)"',page(path))
    assert token, path
    return page(path,dict(data,token=token.group(1)))
def sql(q):
    return subprocess.check_output(['mysql','-h','127.0.0.1','-uroot','-N','-B','academia_meridiano','-e',q],text=True).strip()
def evidence(n,body,expected,actual):
    assert expected==actual,(n,expected,actual)
    (docs/(n+'.html')).write_text(body)
    (docs/(n+'.json')).write_text(json.dumps(dict(esperado=expected,obtido=actual),ensure_ascii=False,indent=2))
name='Teste '+run; mail=run+'@example.test'
# Uma inscrição pelo formulário, com verificação independente no banco.
answers=dict(q0=0,q1=1,q2=2,q3=1,q4=0,q5=2,q6=1,q7=0)
body=post('vestibular.php',dict(nome=name,email=mail,interesse='brasil',**answers))
assert 'Convocado para o TAF' in body
evidence('01-inscricao',body,'8\t8',sql(f"SELECT nota,JSON_LENGTH(respostas) FROM inscricoes WHERE email='{mail}'"))
post('secretaria.php',{'senha':os.environ['TEST_ADMIN']})
route='registro.php?tipo=turmas'
record=dict(nome=name,missao='comum',ano='2027')
body=post(route,record); ident=sql(f"SELECT id FROM turmas WHERE nome='{name}'"); assert ident.isdigit()
evidence('02-turma',body,'2027',sql(f'SELECT ano FROM turmas WHERE id={ident}'))
route+='&id='+ident
body=post(route,dict(record,ano='2028')); evidence('03-edicao',body,'2028',sql(f'SELECT ano FROM turmas WHERE id={ident}'))
body=page('gestao.php?tipo=turmas&q='+run+'&ordem=za&missao=comum&p=1')
evidence('04-pesquisa',body,True,name in body and '1 registro(s)' in body)
body=post(route,dict(record,ano='10000')); evidence('05-invalido',body,True,'ano entre 1900 e 9999' in body and sql(f'SELECT ano FROM turmas WHERE id={ident}')=='2028')
body=post(route,dict(acao='excluir',confirmar='sim')); evidence('06-exclusao',body,'0',sql(f'SELECT COUNT(*) FROM turmas WHERE id={ident}'))
