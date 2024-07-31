import socket
import signal
from Crypto.Util.number import getPrime, inverse, bytes_to_long, long_to_bytes

# RSA Key generation
def generate_rsa_keys(key_size=1024):
    e = 65537
    p = getPrime(key_size // 2)
    q = getPrime(key_size // 2)
    n = p * q
    phi_n = (p - 1) * (q - 1)
    d = inverse(e, phi_n)
    return (e, d, n, p, q, phi_n), (e, n)

# Encryption and decryption functions
def encrypt(m, e, n):
    return pow(bytes_to_long(m), e, n)

def decrypt(c, d, n):
    return long_to_bytes(pow(c, d, n))

HOST = '127.0.0.1'
PORT = 65432

server_private_key, server_public_key = generate_rsa_keys()
(e, d, n, p, q, phi_n) = server_private_key
(e, n) = server_public_key

print(f"Server's Public key: (e={e}, n={n})")

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
        print("Waiting for a connection...")
        conn, addr = server_socket.accept()
        with conn:
            print('Connected by', addr)
            
            # Exchange public keys
            client_public_key = conn.recv(1024).decode()
            conn.sendall(f"{e},{n}".encode())

            client_e, client_n = map(int, client_public_key.split(','))
            print(f"Client's Public key: (e={client_e}, n={client_n})")

            while True:
                # Receive encrypted data from client
                encrypted_data = conn.recv(1024).decode()
                if not encrypted_data:
                    print("Client disconnected.")
                    break

                print(f"Received encrypted message: {encrypted_data}")
                decrypted_data = decrypt(int(encrypted_data), d, n)
                message = decrypted_data.decode()
                print(f"Decrypted message: {message}")

                if message.lower() == 'quit':
                    print("Client requested to quit. Closing connection.")
                    break

                # Send a response
                response = input("Enter your message (or 'quit' to exit): ")
                encrypted_response = encrypt(response.encode(), client_e, client_n)
                print(f"Sending encrypted message: {encrypted_response}")
                conn.sendall(str(encrypted_response).encode())

                if response.lower() == 'quit':
                    print("Closing connection.")
                    break

        print("Connection closed. Waiting for new connections...")