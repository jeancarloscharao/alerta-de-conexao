
# AlertadeConexão 🚀

O Alerta de Conexão é uma ferramenta que monitora a disponibilidade de sites e notifica o usuário quando eles estão fora do ar. Desenvolvido em PHP, o projeto utiliza Docker e Docker Compose para facilitar a configuração e execução.

## 📊 Fluxograma do Sistema

![Diagrama de Fluxo do Sistema de Monitoramento de Sites](https://showme.redstarplugin.com/d/d:bHVNoZKq)

Este diagrama de fluxo ilustra o processo de monitoramento dos sites. O sistema começa obtendo a lista de sites a serem monitorados, envia solicitações HTTP para cada site, verifica a resposta e determina se o site está funcionando ou fora do ar.

## 🛠️ Pré-requisitos

Antes de começar, certifique-se de ter instalado:

- Docker
- Docker Compose

## 🚀 Início Rápido

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/jeancarloscharao/alerta-de-conexao.git
   cd alerta-de-conexao
   ```

2. **Configure suas variáveis de ambiente**:
   - Copie o arquivo `.env-exemplo` para criar um novo chamado `.env`.
   - Preencha as variáveis de ambiente no arquivo `.env` com suas informações de SMTP, e-mail e senha.

3. **Crie sua lista de URLs**:
   - Crie um arquivo chamado `sites.txt`.
   - Adicione as URLs dos sites que você deseja monitorar, uma por linha.

4. **Construa e inicie os containers Docker**:
   ```bash
   docker-compose up -d
   ```

5. **Instale as dependências PHP**:
   Entre no container da aplicação e instale as dependências usando o Composer:
   ```bash
   docker exec -it alerta-de-conexao_php-app_1 bash
   composer install
   exit
   ```

6. **Monitoramento Automático**:
   Uma vez que os containers estejam rodando, o sistema irá verificar automaticamente os sites de minuto em minuto. Não é necessário nenhuma ação adicional. O cron integrado cuidará de tudo.

7. **Nota**: Este projeto utiliza dois containers: um para a aplicação (`app`) e outro para a cron (`cron`). Ambos devem estar em execução para o correto funcionamento do sistema.

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [clique aqui](https://opensource.org/licenses/MIT) para mais detalhes.

## 🤝 Contribuições

Contribuições, problemas e solicitações de recursos são bem-vindos! Confira a página de [issues](https://github.com/jeancarloscharao/alerta-de-conexao.git/issues) para mais detalhes.

---

