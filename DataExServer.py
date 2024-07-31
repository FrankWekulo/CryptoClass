import socket
import signal

HOST = '127.0.0.1'
PORT = 65432

def handle_interrupt(sig, frame):
    print("Server shutting down...")
    server_socket.close()
    exit(0)

signal.signal(signal.SIGINT, handle_interrupt)

with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as server_socket:
    server_address = (HOST, PORT)
    print('Server listening on %s port %s' % server_address)
    server_socket.bind(server_address)
    server_socket.listen()

    while True:
        try:
            conn, addr = server_socket.accept()
            with conn:
                print('Connected by', addr)
                data = conn.recv(1024)  # Receive data from client
                if data:
                    print('Received from client:', data.decode())
                    # Process the received data (e.g., modify it)
                    response = b"Hello, client! Your data was: " + data
                    conn.sendall(response)  # Send response to client
                else:
                    print('Client disconnected')
        except KeyboardInterrupt:
            handle_interrupt(None, None)
