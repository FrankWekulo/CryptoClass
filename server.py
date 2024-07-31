import socket
import signal

HOST = '127.0.0.1'
PORT = 65432

# Global variable to hold the server socket
server_socket = None

def handle_interrupt(sig, frame):
    print("Server shutting down...")
    if server_socket:
        server_socket.close()
    exit(0)

signal.signal(signal.SIGINT, handle_interrupt)

with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
    server_socket = s
    server_address = (HOST, PORT)
    print('Server listening on %s port %s' % server_address)
    s.bind(server_address)
    s.listen()

    while True:
        try:
            conn, addr = s.accept()
            with conn:
                print('Connected by', addr)
                data = b"Welcome! This is the server."
                conn.sendall(data)
        except KeyboardInterrupt:
            handle_interrupt(None, None)
