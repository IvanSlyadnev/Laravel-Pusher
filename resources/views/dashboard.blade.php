<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="messages">
{{--                @foreach($messages as $message)--}}
{{--                    <span id = '{{'message_'.$message->id}}' style="<?=$message->style ?? ''?>>">--}}
{{--                        {{$message->user->name.": ".$message->text}}--}}
{{--                    </span>--}}
{{--                    <br>--}}
{{--                @endforeach--}}
            </div>
            <br>
            <div>
                <input type="text" name="message" class="message">
                <button type="submit" id = 'button' class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    Отправить
                </button>
            </div>
        </div>
    </div>
    <script type="module">

        $(document).ready(function () {
            getMessages();
            $("#button").on('click', function () {
                $.ajax({
                    method : "post",
                    url : 'message/create',
                    data :{
                        "_token": "{{ csrf_token() }}",
                        "message": $('input.message').val()
                    }
                }).done(function () {
                    $('input.message').val('');
                });
            });
        });

        window.Echo.channel('test')
            .listen('MessageCreated', (e) => {
                let element = $("<span>").text(e.user.name + ": " + e.message.text);
                //let element = "<span>" +e.user.name + ": " + e.message.text + "</span>";
                if (e.message.configuration) {
                    console.log(e.message.configuration);
                    $(element).css(e.message.configuration);
                    console.log($(element));
                }
                $("#messages").append($(element)).append("<br>");
            });

        function getMessages() {
            axios.get('http://localhost/message')
                .then(function(response) {
                    response.data.result.forEach(function (e) {
                        let element = $("<span>").text(e.user_name + ": " + e.text);
                        $("#messages").append($(element)).append("<br>");
                    });

                })
                .catch(function(error) {
                    console.error(error);
                });
        }
    </script>
</x-app-layout>
