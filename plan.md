# Refresh de interface — Academia Meridiano

## Direção de design

- **Movimentos considerados:** observatório editorial (0,04), brutalismo acadêmico (0,03), painel sci-fi neon (0,02). Escolha: observatório editorial.
- **Princípios:** atmosfera noturna e cobre, leitura em camadas, contraste entre arquivo e interface técnica, movimento discreto.
- **Layout:** hero assimétrico, faixas de prova social, exploração de missões por busca/filtros e CTA final de baixa fricção.
- **Interação:** menu móvel acessível, filtro instantâneo, estados vazios claros, animações respeitando redução de movimento.
- **Voz:** curiosa, precisa e convidativa. Ex.: “Antes do primeiro salto”; “Uma formação. Quatro destinos. Infinitas perguntas.”

## Implementação

1. Evoluir o cabeçalho/rodapé compartilhado com menu móvel, skip link, estado do observatório e links de retorno.
2. Redesenhar a home com faixa de diferenciais, explorador de missões e CTA de próxima etapa.
3. Expandir `tempo.js` com menu, filtro de missões e observação de entrada.
4. Publicar `manus-routes.json` com o conjunto de rotas principais.
5. Validar sintaxe PHP, assets, JSON e resposta HTTP local.

## Estrutura

- `app/`: templates compartilhados e conteúdo HTML das páginas.
- `pub/`: raiz pública, PHP de entrada, assets, PDF e manifest de rotas.
- `sql/`: banco e migrações existentes.
- `tests/`: verificações já existentes.
