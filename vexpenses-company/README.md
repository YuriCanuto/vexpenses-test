## Microsseviço Empresas

- Possui banco de dados independente.
- No banco deve haver as tabelas de empresas e usuários dessas empresas, ambos utilizando softdeletes.
- A empresa deve possuir nome e um token único e para os usuários apenas nome e um email e uma referência para a empresa.
- Fortalecer os estudos de Jobs e Filas para o Laravel utilizando o Redis.
- Instalar a biblioteca para criação de XLS e CSV, no projeto será utilizado o CSV.
- Procurar uma biblioteca para o uso de Logs caso necessário.
- Criar command para criar uma empresa e 10 usuários relacionados a esse empresa, cada usuário só poderá existir para uma determinada empresa
- Criar command para gerar um aquivo CSV com os dados de empresa e usuários, para cada empresa será gerado um arquivo CSV próprio, para melhor organização será utilizado um Job para fazer uso de uma fila ou filas para gerar o arquivo para cada empresa e armazenar no storage do Laravel com o nome da empresa. Fazer uso do Laravel Generator para melhorar a dinâmica e o uso de memória para o foreach de todos esses dados.
- Ao excluir uma empresa o arquivo CSV referente a mesma deverá ser excluído por segurança. 
- Criar um token para ser passado para outros clientes terem acesso aos endpoints. Na criação da empresa será gerado esse token.
- Criar endpoint para algum outro service poder acessar o arquivo referente a sua empresa. O endpoint deve conter o ID referente a sua empresa
- Criar endpoint para um cliente atualizar os dados de um usuário e refletir no microsserviço sempre se baseando no campo updated_at para realizar as atualizações.
- Criar endpoint para ser possível deletar uma empresa e consequentemente deletar todos os usuários dessa empresa, usar o softdelete para manter esses dados no banco.
- Criar logs para todas as ações para melhorar o estudo do fluxo de cada ação.
