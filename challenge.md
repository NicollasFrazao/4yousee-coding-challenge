# Teste Técnico PHP: Processador de Vídeos

## Objetivo

Avaliar a capacidade do candidato em desenvolver uma aplicação PHP que lida com upload de arquivos, processamento de mídia, persistência de dados em banco relacional, integração com serviços de armazenamento em nuvem (S3) e comunicação assíncrona através de filas de mensagens.

## Contexto

Você foi encarregado de desenvolver um módulo para uma plataforma de vídeos que permitirá aos usuários fazer upload de seus vídeos. Após o upload, o sistema deve processar o vídeo, armazená-lo de forma segura e notificar outros sistemas sobre o novo conteúdo.

## Requisitos Funcionais

1.  **Endpoint de Upload de Vídeo:**
    *   Criar um endpoint (ex: `POST /videos`) que aceite o upload de um arquivo de vídeo (`multipart/form-data`).
    *   Validações:
        *   O arquivo deve ser obrigatório.
        *   O arquivo deve ser um tipo de vídeo válido (ex: `video/mp4`, `video/quicktime`, `video/x-msvideo`).
        *   Definir um tamanho máximo para o arquivo (ex: 100MB - para fins de teste).

2.  **Extração e Persistência de Metadados do Vídeo:**
    *   Após o upload bem-sucedido, o sistema deve extrair os seguintes metadados do vídeo:
        *   **Nome Original do Arquivo:** O nome com o qual o arquivo foi enviado.
        *   **Resolução:** Largura x Altura (ex: "1920x1080").
        *   **Duração:** Em segundos (ou formato HH:MM:SS).
    *   Estes metadados, juntamente com outras informações relevantes (como o caminho para o arquivo no S3), devem ser persistidos em um banco de dados relacional (MySQL, PostgreSQL ou SQLite).

3.  **Armazenamento do Arquivo no S3:**
    *   O arquivo de vídeo original deve ser enviado para um bucket S3 (ou um serviço compatível como MinIO para desenvolvimento local).
    *   O caminho do arquivo no S3 deve ser único para evitar colisões (ex: `videos/{uuid}/{nome_original_com_timestamp}.mp4`).
    *   As credenciais do S3 e o nome do bucket devem ser configuráveis (ex: via variáveis de ambiente).

4.  **(Bônus) Notificação para Fila:**
    *   Após o arquivo ser armazenado no S3 e os metadados persistidos no banco, uma mensagem deve ser enviada para uma fila (ex: RabbitMQ, SQS, Redis ou um driver de fila síncrono para simplicidade no teste).
    *   A mensagem deve conter informações que permitam a outros serviços identificar o vídeo processado (ex: `video_id` do banco de dados, `s3_path`).
    *   Formato da mensagem: JSON.

## Requisitos Técnicos

*   **Linguagem:** PHP (versão 7.4 ou superior).
*   **Framework:** Laravel ou Symfony são preferíveis. Se optar por não usar um framework, justifique a escolha e demonstre boa organização do código.
*   **Banco de Dados:** MySQL, PostgreSQL ou SQLite. O uso de um ORM (Eloquent, Doctrine) é recomendado.
*   **Bibliotecas:**
    *   AWS SDK for PHP para interação com S3.
    *   FFmpeg (ou a biblioteca `php-ffmpeg`) para extração de metadados do vídeo. É esperado que o candidato saiba como instalar e utilizar o FFmpeg no ambiente de desenvolvimento.
    *   (Bônus) Biblioteca para interagir com a fila escolhida (ex: `php-amqplib` para RabbitMQ, ou a abstração de filas do framework).
*   **Gerenciamento de Dependências:** Composer.

## O que será Avaliado:
*	**Funcionalidade:** A aplicação atende a todos os requisitos funcionais obrigatórios.
*	**Qualidade do Código:** Clareza, organização, legibilidade, manutenibilidade e adesão aos padrões PSR.
*	**Tratamento de Erros:** Implementação de tratamento de exceções e erros de forma robusta.
*	**Segurança:** Considerações básicas de segurança (ex: validação de uploads, evitar SQL Injection).
*	**Implementação dos requisitos bônus (se houver):** A implementação da notificação para fila será um diferencial.
*	**Testes (Bônus adicional):** Presença de testes unitários e/ou de integração.
*	**Documentação:** Um arquivo README.md explicando como configurar, executar e testar a aplicação.
*	**Boas Práticas:** Uso de variáveis de ambiente para configuração, princípios SOLID (se aplicável), etc.
## Entregáveis:
* Link para um repositório Git (GitHub, Bitbucket, GitLab, etc.) contendo o código-fonte da solução.
* O arquivo README.md no repositório.