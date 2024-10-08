Tabela Ponto

Campos:
- id (inteiro, crescente)
- inicio (inteiro, chave primária da tabela tipos_ponto)
- hora_inicio (datetime)
- fim (inteiro, chave primária da tabela tipos_ponto)
- hora_fim (datetime)

Tabela tipo de ponto
- id (inteiro, crescente)
- tipos (inteiro, valores fixados [1] fixo, [2] variavel)

## Relacionamento

Se o campo inicio estiver com valor 1, então, ele irá buscar a hora que estará no campo hora_inicio.
Se o campo inicio estiver com valor 2, então, ele irá buscar o primeiro horário do colaborador.
Se o campo fim estiver com valor 1, então, ele irá buscar a hora que estará no campo hora_fim.
Se o campo fim estiver com valor 2, então, ele irá buscar o último horário do colaborador.

## Armazenamento

O sistema armazenará o valor em sessionstorage. Os valores serão salvos no momento do login e
de alteração nas configurações.