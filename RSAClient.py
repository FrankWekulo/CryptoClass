import socket
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

client_private_key, client_public_key = generate_rsa_keys()
(e, d, n, p, q, phi_n) = client_private_key
(e, n) = client_public_key

print(f"Client's Public key: (e={e}, n={n})")

try:
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
        server_address = (HOST, PORT)
        print('Connecting to %s port %s' % server_address)
        s.connect(server_address)

        # Exchange public keys
        s.sendall(f"{e},{n}".encode())
        server_public_key = s.recv(1024).decode()
        server_e, server_n = map(int, server_public_key.split(','))

        print(f"Server's Public key: (e={server_e}, n={server_n})")

        while True:
            # Send message to server
            message = input("Enter your message (or 'quit' to exit): ")
            encrypted_message = encrypt(message.encode(), server_e, server_n)
            print(f"Sending encrypted message: {encrypted_message}")
            s.sendall(str(encrypted_message).encode())

            if message.lower() == 'quit':
                print("Closing connection.")
                break

            # Receive response from server
            encrypted_response = s.recv(1024).decode()
            if not encrypted_response:
                print("Server disconnected.")
                break

            print(f"Received encrypted message: {encrypted_response}")
            decrypted_response = decrypt(int(encrypted_response), d, n)
            print(f"Decrypted message: {decrypted_response.decode()}")

            if decrypted_response.decode().lower() == 'quit':
                print("Server requested to quit. Closing connection.")
                break

except ConnectionError:
    print('Failed to connect to the server.')
except KeyboardInterrupt:
    print('Client interrupted.')

print("Connection closed.")