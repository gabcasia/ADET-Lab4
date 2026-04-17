import requests

BASE_URL = "http://localhost:8000/service.php"

while True:
    print("\n1. Add Pokemon\n2. Get Pokemon\n3. Update Level\n4. Delete Pokemon\n5. Exit")
    choice = input("Choose: ")

    if choice == "1":
        name = input("Name: ")
        type_ = input("Type: ")
        level = input("Level: ")

        res = requests.post(f"{BASE_URL}?action=add", json={
            "name": name,
            "type": type_,
            "level": level
        })
        print(res.json())

    elif choice == "2":
        pid = input("Pokemon ID: ")
        res = requests.get(f"{BASE_URL}?action=get&id={pid}")
        print(res.json())

    elif choice == "3":
        pid = input("Pokemon ID: ")
        level = input("New Level: ")

        res = requests.post(f"{BASE_URL}?action=update", json={
            "id": pid,
            "level": level
        })
        print(res.json())

    elif choice == "4":
        pid = input("Pokemon ID: ")
        res = requests.get(f"{BASE_URL}?action=delete&id={pid}")
        print(res.json())

    elif choice == "5":
        break