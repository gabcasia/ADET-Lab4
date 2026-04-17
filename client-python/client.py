print("CLIENT STARTED")
import requests

BASE_URL = "http://localhost:8000/service.php"

def register():
    data = {
        "username": input("Username: "),
        "password": input("Password: ")
    }
    res = requests.post(f"{BASE_URL}?action=register", json=data)
    print(res.json())

def login():
    data = {
        "username": input("Username: "),
        "password": input("Password: ")
    }
    res = requests.post(f"{BASE_URL}?action=login", json=data)
    print(res.json())

def get_user():
    user_id = input("User ID: ")
    res = requests.get(f"{BASE_URL}?action=get&id={user_id}")
    print(res.json())

def update_user():
    data = {
        "id": input("User ID: "),
        "username": input("New Username: ")
    }
    res = requests.post(f"{BASE_URL}?action=update", json=data)
    print(res.json())

while True:
    print("\n1. Register\n2. Login\n3. Get User\n4. Update\n5. Exit")
    choice = input("Choose: ")

    if choice == "1":
        register()
    elif choice == "2":
        login()
    elif choice == "3":
        get_user()
    elif choice == "4":
        update_user()
    elif choice == "5":
        break