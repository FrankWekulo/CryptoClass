import socket

HOST = '127.0.0.1'  # The server's hostname or IP address
PORT = 65432        # The port used by the server

try:
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
        server_address = (HOST, PORT)
        print('Connecting to %s port %s' % server_address)
        s.connect(server_address)

        # Send data to the server
        message = "Hello from the client!".encode()
        s.sendall(message)

        # Receive response from the server
        data = s.recv(1024)
        print('Received from server:', data.decode())

except ConnectionError:
    print('Failed to connect to the server.')
except KeyboardInterrupt:
    print('Client interrupted.')
