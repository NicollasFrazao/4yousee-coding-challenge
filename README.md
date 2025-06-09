# Teste Técnico PHP: Processador de Vídeos

## Requisitos

1.  **Sistema Operacional**
    *   Caso opte por utilizar o `Windows`, será necessário instalar os seguintes recursos previamente:
        *   `WSL2` (com `Ubuntu-20.04`), para mais informações acesse https://learn.microsoft.com/pt-br/windows/wsl/install;
        *   `Docker Desktop`, para mais informações acesse https://docs.docker.com/desktop/setup/install/windows-install/.
    *   Caso opte por utilizar `Linux` ou `WSL2`, abrir o terminal do `Ubuntu`.

2.  **Instalação**
    *   Com o terminal aberto, `clone` o repositório para o diretório de sua preferência, com o comando abaixo:
        ```
        git clone https://github.com/NicollasFrazao/4yousee_challenge.git
        ```
    *   Instale o `PHP` através dos seguintes comandos:
        ```
        sudo apt install software-properties-common
        ```

        ```
        sudo add-apt-repository ppa:ondrej/php
        ```
        
        ```
        sudo apt update
        ```
        
        ```
        sudo apt install -y php
        ```
        
        ```
        sudo apt install php-xml php-curl  php-gd php-sqlite3 php-mbstring php-pgsql php-mysql
        ```
    * Instale o `Composer`, com os comandos abaixo:
        ```
        sudo apt update
        ```

        ```
        sudo apt install php-cli unzip
        ```

        ```
        cd ~
        ```

        ```
        curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
        ```

        ```
        HASH=`curl -sS https://composer.github.io/installer.sig`
        ```

        ```
        php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
        ```

        ```
        sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
        ```
    *   Instale o `Docker Compose`, conforme a documentação que se encontra em https://docs.docker.com/engine/install/ubuntu/;
    *   Instale o `Make` com o comando abaixo:
        ```
        sudo apt-get -y install make
        ```
    *   Dentro do diretório onde o repositório foi clonado, instale as dependências com o comando abaixo:
        ```
        composer install
        ```
        * Crie o arquivo de variáveis de ambiente, com o comando abaixo:
            ```
            cp .env.example .env
            ```
        * Gere a key do projeto, com o comando abaixo:
            ```
            php artisan key:generate
            ```
        *   Execute esse comando:
            ```
            ./setup.sh 
            ```

        
3.  **Inicialização**
    *   Para iniciar o projeto, execute o comando abaixo:
        ```
        make up
        ```
        *   Com o projeto iniciado, para rodar as `migrations`, execute esses comandos:
            ```
            make sh
            ```

            ```
            php artisan migrate
            ```
        * Para execuar o `testes` unitário e/ou de integração, execute esses comandos:
             ```
            make sh
            ```

            ```
            php artisan test
            ```
        * Para se inscrever e disparar no canal de `mensagens`, excute esses grupos de comandos em janelas diferentes:
            * Queue Job
                ```
                make sh
                ```

                ```
                php artisan queue:work
                ```
            
            * Redis Subscribe
                ```
                make sh
                ```
                
                ```
                php artisan redis:subscribe videos.store
                ```
    * O projeto pode ser usado tanto via interface web (http://localhost), quanto por API (http://localhost/api)