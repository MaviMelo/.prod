Tutorial Completo: Agente de IA Local (Ollama + Open Interpreter)
Este é um resumo das etapas essenciais para configurar seu ambiente de agente de IA no Linux.

1. Pré-requisitos (Python e Pipx)
Instale as ferramentas Python necessárias para gerenciar o Open Interpreter:

sudo apt update
sudo apt install python3-pip -y
python3 -m pip install --user pipx
python3 -m pipx ensurepath

2. Instalação do Ollama (Servidor de LLMs)
Instale o Ollama, que hospedará os modelos de linguagem.

curl -fsSL https://ollama.com/install.sh | sh

3. Instalação do Open Interpreter
Instale o Open Interpreter em um ambiente isolado usando o pipx.

pipx install open-interpreter

4. Modelos (LLMs) Recomendados
Baixe um modelo para usar com o Open Interpreter (exemplo: Gemma 2). Use \bye para sair após o download.

Para Raciocínio Geral: ollama run gemma2

Para Edição de Código: ollama run codellama:7b

Para Velocidade: ollama run mistral:7b

5. Prompt Robusto e Comando de Autonomia
Use este comando para iniciar o Open Interpreter com o modelo (gemma2 é o exemplo) e definir uma persona autônoma. A flag -y permite que ele execute comandos de consulta sem pedir aprovação (use com cautela).

Comando de Inicialização:

interpreter --local -y --system_message "Você é um Agente Linux autônomo e altamente proficiente. Seu objetivo principal é examinar, navegar, criar, editar e analisar arquivos e estruturas de diretórios. Responda SEMPRE em Português do Brasil de forma concisa. Para comandos de consulta (ls, cat, grep), execute-os **imediatamente** para obter as informações necessárias. Para ações que alteram o sistema (criar, mover, deletar), use comandos Bash no formato técnico, sempre explicando o plano de ação antes de executar. Mantenha a autonomia nas buscas, transitando entre diretórios sem hesitação. Seja lógico e proativo."
