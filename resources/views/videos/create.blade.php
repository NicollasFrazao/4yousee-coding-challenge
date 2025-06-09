<!DOCTYPE html>

<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Language" content="pt-br">
        <meta charset="UTF-8">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>4seeyou Challenge - Cadastro de Vídeos</title>
    </head>

    <body>
        <form id="videos_store" method='post' action="{{ route('videos.store') }}" enctype="multipart/form-data">
            <label for="video">Arquivo de Vídeo: </label><br/>
            <input type="file" id="video" name="video" accept="video/mp4, video/quicktime, video/x-msvideo" multiple="false" required/></br>
            <br/>
            <input type="submit" value="Enviar"/>
        </form>
    </body>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
        $('form#videos_store').submit(function (event) {
            event.preventDefault();

            if (!this.video && this.video.files[0]) alert('O campo "Arquivo de Vídeo" é obrigatório!');
            else if (!this.video.accept.replaceAll(' ', '').split(',').includes(this.video.files[0].type)) alert('O campo "Arquivo de Vídeo" deve conter um arquivo de vídeo!');
            else if (this.video.files[0].size/1024/1000 > 100) alert('O campo "Arquivo de Vídeo" deve conter um arquivo com tamanho máximo de 100MB!');
            else 
            {
                $.ajax({
                    url: this.action,
                    method: this.method,
                    headers: { 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        //'Content-Type': 'application/json',
                    },
                    //dataType: 'json',
                    data: new FormData(this),
                    processData: false,
                    contentType: false, 
                    beforeSend: function () {},
                    success: function (response) {
                        alert(response.message);
                    },
                    error: function (error) {
                        alert(error.responseJSON.message);
                    },
                    complete: function (response) {}
                });  
            }
        });
    </script>
</html>