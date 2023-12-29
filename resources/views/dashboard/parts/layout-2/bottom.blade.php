
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.6.3/jquery.timeago.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://dev-rochat.netsolutionindia.com/socket.io/socket.io.js"></script>
<script>
    $(async function(){
        try{
            
            const socketIoUrl = 'https://dev-rochat.netsolutionindia.com/';
            
            // Create a socket.io connection
            const socket = new io(socketIoUrl);
            
            // Set the socket.io connection as the broadcaster for Laravel Echo
            window.Echo = new window.EchoNew({
                broadcaster: 'socket.io',
                host: socketIoUrl,
                client: socket, // Pass the socket.io client instance
                encrypted: true // Use HTTPS
            });

            window.Echo.channel('check-socket').listen('.UserEvent', function(message) {
                console.log(message)
            });
        }
        catch(e){
            console.error(e);
        }
    })
</script>
<script src="{{ asset('assets/libs/dashboard/variable.js') }}"></script>
<script src="{{ asset('assets/libs/dashboard/dashboard.map-function.js') }}"></script>
<script src="{{ asset('assets/libs/dashboard/dashboard.event.js') }}"></script>
<script src="{{ asset('assets/libs/dashboard/dashboard.function.js') }}"></script>


